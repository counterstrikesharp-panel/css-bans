<?php

namespace App\Http\Controllers;

use App\Helpers\AdminLogHelper;
use App\Models\AdminLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.logs.index');
    }
    
    public function getLogsList(Request $request)
    {
        // Get pagination parameters from DataTables
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value');
        
        // Get sorting parameters
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $columns = $request->input('columns');
        $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'created_at';
        
        // Base query
        $query = AdminLog::query();
        
        // Apply search filter
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('admin_name', 'like', "%{$searchValue}%")
                  ->orWhere('admin_steam_id', 'like', "%{$searchValue}%")
                  ->orWhere('action', 'like', "%{$searchValue}%")
                  ->orWhere('target_name', 'like', "%{$searchValue}%")
                  ->orWhere('target_steam_id', 'like', "%{$searchValue}%");
            });
        }
        
        // Apply date range filter if provided
        if ($request->has('start_date') && !empty($request->input('start_date'))) {
            $query->where('created_at', '>=', Carbon::parse($request->input('start_date'))->startOfDay());
        }
        
        if ($request->has('end_date') && !empty($request->input('end_date'))) {
            $query->where('created_at', '<=', Carbon::parse($request->input('end_date'))->endOfDay());
        }
        
        // Apply admin filter if provided
        if ($request->has('admin_id') && !empty($request->input('admin_id'))) {
            $query->where('admin_id', $request->input('admin_id'));
        }
        
        // Apply action filter if provided
        if ($request->has('action') && !empty($request->input('action'))) {
            $query->where('action', $request->input('action'));
        }
        
        // Get total count for pagination
        $totalRecords = $query->count();
        
        // Apply sorting
        if ($orderColumnName !== 'description') {
            $query->orderBy($orderColumnName, $orderDir);
        }
        
        // Get paginated results
        $logs = $query->offset($start)->limit($length)->get();
        
        // Prepare formatted data for DataTables
        $formattedData = [];
        
        foreach ($logs as $log) {
            $description = AdminLogHelper::getActionDescription($log);
            
            $formattedData[] = [
                'id' => $log->id,
                'admin_name' => "<a href='https://steamcommunity.com/profiles/{$log->admin_steam_id}' target='_blank'>{$log->admin_name}</a>",
                'action' => $log->action,
                'target_name' => $log->target_steam_id ? 
                    "<a href='https://steamcommunity.com/profiles/{$log->target_steam_id}' target='_blank'>{$log->target_name}</a>" : 
                    $log->target_name,
                'description' => $description,
                'details' => '<button class="btn btn-info btn-sm view-details" data-details="' . htmlspecialchars(json_encode(json_decode($log->details, true))) . '">View Details</button>',
                'ip_address' => $log->ip_address,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        }
        
        if ($orderColumnName === 'description') {
            // Custom sort for description field
            usort($formattedData, function ($a, $b) use ($orderDir) {
                if ($orderDir === 'asc') {
                    return strcmp($a['description'], $b['description']);
                } else {
                    return strcmp($b['description'], $a['description']);
                }
            });
        }
        
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => AdminLog::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $formattedData
        ]);
    }
    
    public function getFilters()
    {
        // Get unique admin IDs and names for filter dropdown
        $admins = AdminLog::select('admin_id', 'admin_name')
            ->distinct('admin_id')
            ->whereNotNull('admin_id')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->admin_id,
                    'name' => $log->admin_name
                ];
            });
            
        // Get unique actions for filter dropdown
        $actions = AdminLog::select('action')
            ->distinct('action')
            ->pluck('action');
            
        return response()->json([
            'admins' => $admins,
            'actions' => $actions
        ]);
    }

    /**
     * Display detailed information for a specific log entry.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showDetails($id)
    {
        try {
            // Fetch the log entry
            $log = AdminLog::findOrFail($id);
            
            // Process the details
            $serverDetails = [];
            
            // For ban related actions, fetch server information from sa_bans and sa_servers tables
            if (in_array($log->action, ['BAN', 'UNBAN', 'MUTE', 'UNMUTE', 'EDIT_BAN']) && !empty($log->target_steam_id)) {
                // Query to get all bans for this target and join with servers
                $bansWithServers = DB::table('sa_bans')
                    ->where('player_steamid', $log->target_steam_id)
                    ->join('sa_servers', 'sa_bans.server_id', '=', 'sa_servers.id')
                    ->select(
                        'sa_bans.*',
                        'sa_servers.id as server_id',
                        'sa_servers.hostname as server_name',
                        'sa_servers.ip as server_ip',
                        'sa_servers.port as server_port',
                        'sa_servers.rcon_pw as server_rcon',
                        'sa_servers.status as server_status'
                    )
                    ->get();
                    
                // If we found related bans with server info
                if ($bansWithServers->count() > 0) {
                    foreach ($bansWithServers as $ban) {
                        // Convert to object for consistency
                        $details = (object)[
                            // Ban details
                            'reason' => $ban->reason,
                            'duration' => $ban->duration,
                            'ends' => $ban->ends,
                            'created_at' => $ban->created_at,
                            'unbanned' => $ban->unbanned,
                            'time_left' => $ban->ends ? Carbon::parse($ban->ends)->diffForHumans() : 'Permanent',
                            
                            // Server details
                            'server_id' => $ban->server_id,
                            'server_name' => $ban->server_name,
                            'server_ip' => $ban->server_ip,
                            'server_port' => $ban->server_port,
                            'server_address' => $ban->server_ip . ':' . $ban->server_port,
                            'server_status' => $ban->server_status,
                            
                            // Target details
                            'player_name' => $ban->player_name,
                            'player_steamid' => $ban->player_steamid,
                            'player_ip' => $ban->player_ip,
                            
                            // Admin details
                            'admin_name' => $ban->admin_name,
                            'admin_steamid' => $ban->admin_steamid
                        ];
                        
                        $serverDetails[] = $details;
                    }
                }
            }
            
            // If we couldn't find any server details from the sa_bans table,
            // fallback to the details stored in the log
            if (empty($serverDetails) && !empty($log->details)) {
                if (is_string($log->details)) {
                    $details = json_decode($log->details);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // Convert to array if it's a valid JSON object
                        if (is_array($details)) {
                            $serverDetails = $details;
                        } else {
                            $serverDetails = [$details];
                        }
                    } else {
                        // If invalid JSON, create a single item
                        $serverDetails = [(object)['error' => 'Invalid JSON data in details field']];
                    }
                } elseif (is_array($log->details)) {
                    $serverDetails = $log->details;
                } else {
                    // Handle any other type
                    $serverDetails = [$log->details];
                }
            }
            
            // Add action information to log object
            $log->action_description = AdminLogHelper::getActionDescription($log);
            
            return view('admin.logs.details', [
                'log' => $log,
                'serverDetails' => $serverDetails
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.logs')->with('error', 'Error showing log details: ' . $e->getMessage());
        }
    }
}
