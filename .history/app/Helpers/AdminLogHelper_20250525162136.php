<?php

namespace App\Helpers;

use App\Models\AdminLog;
use App\Models\SaBan;
use App\Models\SaMute;
use App\Models\SaAdmin;
use App\Models\SaGroups;
use App\Models\Appeal\Appeal;
use App\Models\Report\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AdminLogHelper
{
    /**
     * Log an admin action
     *
     * @param string $action The action performed (ban, unban, mute, unmute, add_admin, etc.)
     * @param string $targetType Type of target (ban, mute, admin, group, etc.)
     * @param int $targetId The ID of the target
     * @param array $details Additional details about the action
     * @return void
     */
    public static function log($action, $targetType, $targetId, $details = [])
    {
        // Get current admin
        $admin = Auth::user();
        
        if (!$admin) {
            return; // No user is authenticated
        }

        $targetData = self::getTargetData($targetType, $targetId);
        
        // Create log entry
        AdminLog::create([
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'admin_steam_id' => $admin->steam_id,
            'action' => $action,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'target_name' => $targetData['name'] ?? null,
            'target_steam_id' => $targetData['steam_id'] ?? null,
            'details' => json_encode($details),
            'ip_address' => Request::ip()
        ]);

        // Also send to Discord if webhook is configured
        if (method_exists('App\Helpers\CommonHelper', 'sendActionLog')) {
            CommonHelper::sendActionLog($action, $targetId);
        }
    }
    
    /**
     * Get target data based on type and ID
     *
     * @param string $targetType
     * @param int $targetId
     * @return array
     */
    private static function getTargetData($targetType, $targetId)
    {
        $data = [];
        
        switch ($targetType) {
            case 'ban':
                $ban = SaBan::find($targetId);
                if ($ban) {
                    $data['name'] = $ban->player_name;
                    $data['steam_id'] = $ban->player_steamid;
                }
                break;
                
            case 'mute':
                $mute = SaMute::find($targetId);
                if ($mute) {
                    $data['name'] = $mute->player_name;
                    $data['steam_id'] = $mute->player_steamid;
                }
                break;
                
            case 'admin':
                $admin = SaAdmin::find($targetId);
                if ($admin) {
                    $data['name'] = $admin->player_name;
                    $data['steam_id'] = $admin->player_steamid;
                }
                break;
                
            case 'group':
                $group = SaGroups::find($targetId);
                if ($group) {
                    $data['name'] = $group->name;
                }
                break;
                
            case 'appeal':
                $appeal = Appeal::find($targetId);
                if ($appeal) {
                    $data['name'] = $appeal->name;
                    $data['steam_id'] = $appeal->steamid;
                }
                break;
                
            case 'report':
                $report = Report::find($targetId);
                if ($report) {
                    $data['name'] = $report->nickname;
                    $data['steam_id'] = $report->steamid;
                }
                break;
        }
        
        return $data;
    }
    
    /**
     * Get a human-readable description of the action
     *
     * @param string $action
     * @param array $data
     * @return string
     */
    public static function getActionDescription($log)
    {
        $details = json_decode($log->details, true) ?? [];
        $description = '';
        
        switch ($log->action) {
            case 'ban':
                $reason = $details['reason'] ?? '';
                $duration = $details['duration'] ?? '';
                $description = "banned {$log->target_name} " . ($duration ? "for $duration" : "permanently") . ($reason ? " for reason: $reason" : "");
                break;
                
            case 'unban':
                $description = "unbanned {$log->target_name}";
                break;
                
            case 'mute':
                $reason = $details['reason'] ?? '';
                $duration = $details['duration'] ?? '';
                $description = "muted {$log->target_name} " . ($duration ? "for $duration" : "permanently") . ($reason ? " for reason: $reason" : "");
                break;
                
            case 'unmute':
                $description = "unmuted {$log->target_name}";
                break;
                
            case 'add_admin':
                $description = "added {$log->target_name} as admin";
                break;
                
            case 'edit_admin':
                $description = "edited admin {$log->target_name}";
                break;
                
            case 'delete_admin':
                $description = "deleted admin {$log->target_name}";
                break;
                
            case 'add_group':
                $description = "created group {$log->target_name}";
                break;
                
            case 'edit_group':
                $description = "edited group {$log->target_name}";
                break;
                
            case 'delete_group':
                $description = "deleted group {$log->target_name}";
                break;
                
            case 'approve_appeal':
                $description = "approved appeal from {$log->target_name}";
                break;
                
            case 'reject_appeal':
                $description = "rejected appeal from {$log->target_name}";
                break;
                
            case 'delete_report':
                $description = "deleted report about {$log->target_name}";
                break;
                
            default:
                $description = "{$log->action} on {$log->target_type} {$log->target_name}";
        }
        
        return $description;
    }
}
