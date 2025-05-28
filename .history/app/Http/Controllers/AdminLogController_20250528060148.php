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
     * Show detailed view of a specific admin log
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Get the main log record
        $log = AdminLog::findOrFail($id);
        $multipleServers = [];
        
        // For BAN/UNBAN actions, we need to fetch all related logs to get all affected servers
        if (in_array(strtoupper($log->action), ['BAN', 'UNBAN'])) {
            // Find all logs with the same target
            $relatedLogs = AdminLog::where('action', $log->action)
                ->where(function($query) use ($log) {
                    // Match by target_id if it exists
                    if (!empty($log->target_id)) {
                        $query->where('target_id', $log->target_id);
                    } 
                    // Also match by target_steam_id from the details if available
                    if (!empty($log->details)) {
                        $details = is_string($log->details) ? json_decode($log->details, true) : $log->details;
                        if (is_array($details) && isset($details['target_steam_id'])) {
                            $query->orWhereRaw("JSON_EXTRACT(details, '$.target_steam_id') = ?", [$details['target_steam_id']]);
                        } elseif (is_array($details) && isset($details[0]['target_steam_id'])) {
                            $query->orWhereRaw("JSON_EXTRACT(details, '$[0].target_steam_id') = ?", [$details[0]['target_steam_id']]);
                        }
                    }
                })
                ->whereDate('created_at', $log->created_at->toDateString())
                ->get();
                
            // Process all related logs to get complete server list
            foreach ($relatedLogs as $relatedLog) {
                $this->processLogDetailsForBan($relatedLog, $multipleServers);
            }
            
            // If still no servers, try to fetch directly from the bans table
            if (empty($multipleServers) && !empty($log->target_steam_id)) {
                // Connect to the bans_css table to get server details
                $bans = DB::table('bans_css')
                    ->where('player_steamid', $log->target_steam_id)
                    ->whereDate('ban_created', $log->created_at->toDateString())
                    ->get();
                    
                foreach ($bans as $ban) {
                    $server = [];
                    $server['id'] = $ban->server_id;
                    
                    // Get server name from servers table
                    $serverInfo = DB::table('servers')->where('id', $ban->server_id)->first();
                    $server['name'] = $serverInfo ? $serverInfo->server_name : "Server #{$ban->server_id}";
                    $server['ip'] = $serverInfo ? $serverInfo->server_ip : null;
                    
                    // Add target info
                    $server['target_info'] = [
                        'name' => $ban->player_name,
                        'steam_id' => $ban->player_steamid,
                        'ip' => $ban->player_ip
                    ];
                    
                    // Add action info
                    $server['action_info'] = [
                        'reason' => $ban->reason,
                        'duration' => $ban->duration,
                        'ends' => $ban->ends,
                        'created' => $ban->ban_created
                    ];
                    
                    // Server info
                    $server['server_info'] = [
                        'id' => $ban->server_id,
                        'name' => $server['name']
                    ];
                    
                    // Add to servers list if not already there
                    if (!$this->serverExists($multipleServers, $ban->server_id)) {
                        $multipleServers[] = $server;
                    }
                }
            }
        } else {
            // For other actions, just process the current log
            $this->processLogDetails($log, $multipleServers);
        }
        
        // Sort servers by ID for consistent display
        usort($multipleServers, function($a, $b) {
            return $a['id'] <=> $b['id'];
        });
        
        return view('admin.logs.show', compact('log', 'multipleServers'));
    }
    
    /**
     * Process log details to extract server information
     * 
     * @param AdminLog $log
     * @param array &$multipleServers
     */
    private function processLogDetails($log, &$multipleServers)
    {
        if (empty($log->details)) return;
        
        $details = $log->details;
        
        // Convert to array if it's a JSON string
        if (is_string($details)) {
            $details = json_decode($details, true);
        }
        
        // If details is not an array but a single object, convert it to array with one item
        if (!is_array($details) || (is_array($details) && !isset($details[0]) && !empty($details))) {
            $details = [$details];
        }
        
        // Process each server's details
        foreach ($details as $serverDetail) {
            if (!is_array($serverDetail) && !is_object($serverDetail)) continue;
            
            $serverDetail = (array)$serverDetail;
            $serverId = $serverDetail['server_id'] ?? null;
            
            // Skip if we already have this server
            if ($serverId && $this->serverExists($multipleServers, $serverId)) {
                continue;
            }
            
            $server = [];
            
            // Extract server information
            $server['id'] = $serverId ?? count($multipleServers) + 1;
            $server['name'] = $serverDetail['server_name'] ?? ('Server #' . $server['id']);
            $server['ip'] = $serverDetail['server_ip'] ?? null;
            
            // Separate information into categories
            $server['target_info'] = [
                'name' => $serverDetail['target_name'] ?? $serverDetail['player_name'] ?? null,
                'steam_id' => $serverDetail['target_steam_id'] ?? $serverDetail['player_steamid'] ?? $serverDetail['steam_id'] ?? null,
                'ip' => $serverDetail['target_ip'] ?? $serverDetail['player_ip'] ?? $serverDetail['ip'] ?? null,
            ];
            
            $server['action_info'] = [
                'reason' => $serverDetail['reason'] ?? null,
                'duration' => $serverDetail['duration'] ?? null,
                'ends' => $serverDetail['ends'] ?? null,
                'created' => $serverDetail['created_at'] ?? null,
            ];
            
            // Extract all server-related fields
            $server['server_info'] = [];
            foreach ($serverDetail as $key => $value) {
                if (strpos($key, 'server_') === 0 && $key !== 'server_name') {
                    $server['server_info'][substr($key, 7)] = $value;
                }
            }
            
            // Other information
            $server['other_info'] = [];
            foreach ($serverDetail as $key => $value) {
                if (!in_array($key, ['server_name', 'server_id', 'server_ip', 'target_name', 'player_name', 
                                   'target_steam_id', 'player_steamid', 'steam_id', 
                                   'target_ip', 'player_ip', 'ip',
                                   'reason', 'duration', 'ends', 'created_at']) && 
                    strpos($key, 'server_') !== 0) {
                    $server['other_info'][$key] = $value;
                }
            }
            
            $multipleServers[] = $server;
        }
    }
    
    /**
     * Check if server already exists in the servers array
     * 
     * @param array $servers
     * @param mixed $serverId
     * @return bool
     */
    private function serverExists($servers, $serverId)
    {
        foreach ($servers as $server) {
            if ($server['id'] == $serverId) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Special processing for ban actions to extract ALL server information
     * 
     * @param AdminLog $log
     * @param array &$multipleServers
     */
    private function processLogDetailsForBan($log, &$multipleServers)
    {
        if (empty($log->details)) return;
        
        $details = $log->details;
        
        // Convert to array if it's a JSON string
        if (is_string($details)) {
            $details = json_decode($details, true);
        }
        
        // If details is not an array but a single object, convert it to array with one item
        if (!is_array($details) || (is_array($details) && !isset($details[0]) && !empty($details))) {
            $details = [$details];
        }
        
        // Check if this might be a multi-server ban
        $isMultiServer = false;
        foreach ($details as $item) {
            if (isset($item['servers']) && is_array($item['servers'])) {
                $isMultiServer = true;
                break;
            }
        }
        
        if ($isMultiServer) {
            // Handle multi-server ban format
            foreach ($details as $item) {
                if (isset($item['servers']) && is_array($item['servers'])) {
                    foreach ($item['servers'] as $serverInfo) {
                        $server = [];
                        $serverId = $serverInfo['server_id'] ?? $serverInfo['id'] ?? null;
                        
                        if ($serverId && $this->serverExists($multipleServers, $serverId)) {
                            continue;
                        }
                        
                        $server['id'] = $serverId;
                        $server['name'] = $serverInfo['server_name'] ?? $serverInfo['name'] ?? ('Server #' . $serverId);
                        $server['ip'] = $serverInfo['server_ip'] ?? $serverInfo['ip'] ?? null;
                        
                        // Copy target info from parent
                        $server['target_info'] = [
                            'name' => $item['target_name'] ?? $item['player_name'] ?? null,
                            'steam_id' => $item['target_steam_id'] ?? $item['player_steamid'] ?? $item['steam_id'] ?? null,
                            'ip' => $item['target_ip'] ?? $item['player_ip'] ?? $item['ip'] ?? null,
                        ];
                        
                        $server['action_info'] = [
                            'reason' => $item['reason'] ?? null,
                            'duration' => $item['duration'] ?? null,
                            'ends' => $item['ends'] ?? null,
                            'created' => $item['created_at'] ?? null,
                        ];
                        
                        $server['server_info'] = [
                            'id' => $serverId,
                            'name' => $server['name'],
                        ];
                        
                        $multipleServers[] = $server;
                    }
                }
            }
        } else {
            // Regular processing for single-server entries
            $this->processLogDetails($log, $multipleServers);
        }
    }
}
