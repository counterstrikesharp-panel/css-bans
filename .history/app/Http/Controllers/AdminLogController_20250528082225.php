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
        // This method handles pagination and filtering for the admin logs list
        // It accepts a request with various parameters and returns JSON data for DataTables
        
        // Step 1: Extract pagination parameters from the request
        // These come from DataTables' client-side configuration
        $start = $request->input('start', 0);      // Starting record index (for pagination)
        $length = $request->input('length', 10);   // Number of records per page
        $searchValue = $request->input('search.value'); // Global search term
        
        // Step 2: Handle sorting parameters
        $orderColumnIndex = $request->input('order.0.column', 0); // Column index to sort by
        $orderDir = $request->input('order.0.dir', 'desc');      // Sort direction (asc/desc)
        $columns = $request->input('columns');                   // Column definitions
        // Get the actual column name to sort by (defaulting to created_at if not found)
        $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'created_at';
        
        // Step 3: Begin building the query for admin logs
        $query = AdminLog::query();
        
        // Step 4: Apply search filter if provided
        // This performs a LIKE search across multiple fields
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('admin_name', 'like', "%{$searchValue}%")
                  ->orWhere('admin_steam_id', 'like', "%{$searchValue}%")
                  ->orWhere('action', 'like', "%{$searchValue}%")
                  ->orWhere('target_name', 'like', "%{$searchValue}%")
                  ->orWhere('target_steam_id', 'like', "%{$searchValue}%");
            });
        }
        
        // Step 5: Apply date range filters if provided
        // This allows filtering logs by a specific time period
        if ($request->has('start_date') && !empty($request->input('start_date'))) {
            $query->where('created_at', '>=', Carbon::parse($request->input('start_date'))->startOfDay());
        }
        if ($request->has('end_date') && !empty($request->input('end_date'))) {
            $query->where('created_at', '<=', Carbon::parse($request->input('end_date'))->endOfDay());
        }
        
        // Step 6: Apply specific filters for admin and action
        if ($request->has('admin_id') && !empty($request->input('admin_id'))) {
            $query->where('admin_id', $request->input('admin_id'));
        }
        if ($request->has('action') && !empty($request->input('action'))) {
            $query->where('action', $request->input('action'));
        }
        
        // Step 7: Calculate total number of records (for pagination info)
        $totalRecords = $query->count();
        
        // Step 8: Apply sorting (special handling for description field which requires custom sorting)
        if ($orderColumnName !== 'description') {
            $query->orderBy($orderColumnName, $orderDir);
        }
        
        // Step 9: Apply pagination and get results
        $logs = $query->offset($start)->limit($length)->get();
        
        // Step 10: Format the data for DataTables response
        $formattedData = [];
        foreach ($logs as $log) {
            // Get a human-readable description of the action
            $description = AdminLogHelper::getActionDescription($log);
            
            // Format each log entry for the table
            // Note the links to Steam profiles for admin and target
            // Details button contains JSON data that will be displayed in a modal/details page
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
        
        // Step 11: Handle custom sorting for the description field if needed
        if ($orderColumnName === 'description') {
            usort($formattedData, function ($a, $b) use ($orderDir) {
                if ($orderDir === 'asc') {
                    return strcmp($a['description'], $b['description']);
                } else {
                    return strcmp($b['description'], $a['description']);
                }
            });
        }
        
        // Step 12: Return the response in the format expected by DataTables
        return response()->json([
            'draw' => intval($request->input('draw')), // Draw counter for DataTables
            'recordsTotal' => AdminLog::count(),       // Total records in database
            'recordsFiltered' => $totalRecords,        // Records after filtering
            'data' => $formattedData                   // Formatted data for display
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
     * Display detailed information for log entries related to the same action.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showDetails($id)
    {
        try {
            // Fetch the primary log entry
            $primaryLog = AdminLog::findOrFail($id);
            $serverDetails = [];
            
            // Find grouped logs with the same action against the same target around the same time
            $timeWindow = 5; // minutes
            $createdTime = $primaryLog->created_at;
            $startTime = (clone $createdTime)->subMinutes($timeWindow);
            $endTime = (clone $createdTime)->addMinutes($timeWindow);
            
            // Find all related logs (same action, same target, close timestamp)
            $relatedLogs = AdminLog::where('action', $primaryLog->action)
                ->where('target_steam_id', $primaryLog->target_steam_id)
                ->whereBetween('created_at', [$startTime, $endTime])
                ->get();
                
            // Extract all unique server IDs from related logs' details
            $serverIds = [];
            foreach ($relatedLogs as $log) {
                $detailsArray = [];
                if (!empty($log->details)) {
                    if (is_string($log->details)) {
                        $detailsArray = json_decode($log->details, true);
                    } else if (is_array($log->details)) {
                        $detailsArray = $log->details;
                    }
                    
                    if (is_array($detailsArray)) {
                        // Check if server_id exists directly in details
                        if (isset($detailsArray['server_id'])) {
                            if (is_array($detailsArray['server_id'])) {
                                $serverIds = array_merge($serverIds, $detailsArray['server_id']);
                            } else {
                                $serverIds[] = $detailsArray['server_id'];
                            }
                        }
                        
                        // Check if single server object with id 
                        if (isset($detailsArray['id'])) {
                            $serverIds[] = $detailsArray['id'];
                        }
                    }
                }
            }
            $serverIds = array_unique(array_filter($serverIds));
            
            // If we found server IDs, fetch those servers with ban details
            if (!empty($serverIds)) {
                $bans = DB::table('sa_bans')
                    ->whereIn('sa_bans.server_id', $serverIds)
                    ->where('player_steamid', $primaryLog->target_steam_id)
                    ->join('sa_servers', 'sa_bans.server_id', '=', 'sa_servers.id')
                    ->select(
                        'sa_bans.*',
                        'sa_servers.id as server_id',
                        'sa_servers.hostname as server_name',
                        'sa_servers.address as server_address'
                    )
                    ->get();
                    
                if ($bans->count() > 0) {
                    foreach ($bans as $ban) {
                        $serverDetails[] = $this->formatBanToServerDetail($ban);
                    }
                }
            }
            
            // If we still have no server details, get all player's bans around that time
            if (empty($serverDetails) && !empty($primaryLog->target_steam_id)) {
                $playerBans = DB::table('sa_bans')
                    ->where('player_steamid', $primaryLog->target_steam_id)
                    ->whereBetween('sa_bans.created_at', [$startTime, $endTime])
                    ->join('sa_servers', 'sa_bans.server_id', '=', 'sa_servers.id')
                    ->select(
                        'sa_bans.*',
                        'sa_servers.id as server_id',
                        'sa_servers.hostname as server_name',
                        'sa_servers.address as server_address'
                    )
                    ->get();
                    
                if ($playerBans->count() > 0) {
                    foreach ($playerBans as $ban) {
                        $serverDetails[] = $this->formatBanToServerDetail($ban);
                    }
                }
            }
            
            // If still no server details, try to find all bans for this player
            if (empty($serverDetails) && !empty($primaryLog->target_steam_id)) {
                $allBans = DB::table('sa_bans')
                    ->where('player_steamid', $primaryLog->target_steam_id)
                    ->join('sa_servers', 'sa_bans.server_id', '=', 'sa_servers.id')
                    ->select(
                        'sa_bans.*',
                        'sa_servers.id as server_id',
                        'sa_servers.hostname as server_name',
                        'sa_servers.address as server_address'
                    )
                    ->orderBy('sa_bans.created_at', 'desc')
                    ->get();
                    
                if ($allBans->count() > 0) {
                    foreach ($allBans as $ban) {
                        $serverDetails[] = $this->formatBanToServerDetail($ban);
                    }
                }
            }
            
            // Final fallback - use details from all related logs
            if (empty($serverDetails)) {
                foreach ($relatedLogs as $log) {
                    if (!empty($log->details)) {
                        if (is_string($log->details)) {
                            $details = json_decode($log->details);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                if (is_array($details)) {
                                    foreach ($details as $detail) {
                                        $serverDetails[] = $detail;
                                    }
                                } else {
                                    $serverDetails[] = $details;
                                }
                            }
                        } elseif (is_array($log->details)) {
                            foreach ($log->details as $detail) {
                                $serverDetails[] = $detail;
                            }
                        } else {
                            $serverDetails[] = $log->details;
                        }
                    }
                }
            }
            
            // Add action information to log object
            $primaryLog->action_description = AdminLogHelper::getActionDescription($primaryLog);
            
            return view('admin.logs.details', [
                'log' => $primaryLog,
                'serverDetails' => $serverDetails
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('admin.logs')->with('error', 'Error showing log details: ' . $e->getMessage());
        }
    }

    /**
     * Format ban data into a consistent server detail object
     * 
     * @param object $ban Ban data from database
     * @return object Formatted server detail object
     */
    private function formatBanToServerDetail($ban)
    {
        // Extract IP and port from address field if available
        $serverIp = '';
        $serverPort = '';
        if (!empty($ban->server_address)) {
            $addressParts = explode(':', $ban->server_address);
            $serverIp = $addressParts[0];
            $serverPort = $addressParts[1] ?? '';
        }
        
        // Parse date fields safely
        $createdAt = property_exists($ban, 'created_at') ? $ban->created_at : null;
        $ends = property_exists($ban, 'ends') ? $ban->ends : null;
        $timeLeft = null;
        
        if ($ends) {
            try {
                $timeLeft = Carbon::parse($ends)->diffForHumans();
            } catch (\Exception $e) {
                $timeLeft = 'Unknown';
            }
        } else {
            $timeLeft = 'Permanent';
        }
        
        return (object)[
            // Ban details
            'reason' => property_exists($ban, 'reason') ? $ban->reason : '',
            'duration' => property_exists($ban, 'duration') ? $ban->duration : '',
            'ends' => $ends,
            'created_at' => $createdAt,
            'unbanned' => property_exists($ban, 'unbanned') ? $ban->unbanned : 0,
            'time_left' => $timeLeft,
            
            // Server details
            'server_id' => $ban->server_id,
            'server_name' => $ban->server_name,
            'server_address' => $ban->server_address,
            'server_ip' => $serverIp,
            'server_port' => $serverPort,
            
            // Target details
            'player_name' => property_exists($ban, 'player_name') ? $ban->player_name : '',
            'player_steamid' => property_exists($ban, 'player_steamid') ? $ban->player_steamid : '',
            'player_ip' => property_exists($ban, 'player_ip') ? $ban->player_ip : '',
            
            // Admin details
            'admin_name' => property_exists($ban, 'admin_name') ? $ban->admin_name : '',
            'admin_steamid' => property_exists($ban, 'admin_steamid') ? $ban->admin_steamid : ''
        ];
    }
}
