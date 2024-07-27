<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PermissionsHelper
{

    public static function isSuperAdmin()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has the specified permission
        if ($user && (
                $user->permissions()->where('flag', '@css/root')->exists() ||
                $user->groupPermissions()->where('flag', '@css/root')->exists()
            )) {
            return true;
        }
        return false;
    }

    public static function hasAdminCreatePermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && (
                $user->permissions()->where('flag', '@web/admin.create')->exists() ||
                $user->groupPermissions()->where('flag', '@web/admin.create')->exists()
            ) && self::validateExpiryOnAllServers($user)) {
           return true;
        }
        return false;
    }

    public static function hasAdminEditPermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && (
                $user->permissions()->where('flag', '@web/admin.edit')->exists() ||
                $user->groupPermissions()->where('flag', '@web/admin.edit')->exists()
            ) && self::validateExpiryOnAllServers($user)) {
            return true;
        }
        return false;
    }

    public static function hasAdminDeletePermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && (
                $user->permissions()->where('flag', '@web/admin.delete')->exists() ||
                $user->groupPermissions()->where('flag', '@web/admin.delete')->exists()
            ) && self::validateExpiryOnAllServers($user)) {
            return true;
        }
        return false;
    }

    public static function hasGroupCreatePermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && (
                $user->permissions()->where('flag', '@web/group.create')->exists() ||
                $user->groupPermissions()->where('flag', '@web/group.create')->exists()
            ) && self::validateExpiryOnAllServers($user)) {
            return true;
        }
        return false;
    }
    public static function hasGroupEditPermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && (
                $user->permissions()->where('flag', '@web/group.edit')->exists() ||
                $user->groupPermissions()->where('flag', '@web/group.edit')->exists()
            ) && self::validateExpiryOnAllServers($user)) {
            return true;
        }
        return false;
    }
    public static function hasGroupDeletePermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && (
                $user->permissions()->where('flag', '@web/group.delete')->exists() ||
                $user->groupPermissions()->where('flag', '@web/group.delete')->exists()
            ) && self::validateExpiryOnAllServers($user)) {
            return true;
        }
        return false;
    }

    public static function hasWebBanEditPermissions(int $serverId=null) {
        // Get the authenticated user
        $allowed = false;
        $user = Auth::user();
        // Admin expired on all servers
        if (!self::validateExpiryOnAllServers($user) && !$serverId) {
            $allowed = false;
        } elseif ($serverId && self::hasValidPermission($user, $serverId, '@web/ban.edit')) {
            // has permission on the server
            $allowed = true;
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@web/ban.edit'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@web/ban.edit'])->exists())) {
            // Check  perms exists for atleast one of the server - For UI Actions
            $allowed = true;
        }

        return $allowed;
    }

    public static function hasWebMuteEditPermissions(int $serverId=null) {
        // Get the authenticated user
        $allowed = false;
        $user = Auth::user();
        // Admin expired on all servers
        if (!self::validateExpiryOnAllServers($user) && !$serverId) {
            $allowed = false;
        } elseif ($serverId && self::hasValidPermission($user, $serverId, '@web/mute.edit')) {
            // has permission on the server
            $allowed = true;
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@web/mute.edit'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@web/mute.edit'])->exists())) {
            // Check  perms exists for atleast one of the server - For UI Actions
            $allowed = true;
        }

        return $allowed;
    }

    public static function hasUnBanPermission(int $serverId=null)
    {
        // Get the authenticated user
        $allowed = false;
        $user = Auth::user();
        // Admin expired on all servers
        if (!self::validateExpiryOnAllServers($user) && !$serverId) {
            $allowed = false;
        } elseif ($serverId && self::hasValidPermission($user, $serverId, '@css/unban')) {
            // has permission on the server
            $allowed = true;
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/unban', '@web/ban.unban'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@css/unban', '@web/ban.unban'])->exists())) {
            // Check  perms exists for atleast one of the server - For UI Actions
            $allowed = true;
        }

        return $allowed;
    }

    public static function hasUnMutePermission(int $serverId=null)
    {
        // Get the authenticated user
        $allowed = false;
        $user = Auth::user();
        // Admin expired on all servers
        if (!self::validateExpiryOnAllServers($user) && !$serverId) {
            $allowed = false;
        } elseif ($serverId && self::hasValidPermission($user, $serverId, '@css/chat')) {
            // has permission on the server
            $allowed = true;
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/chat', '@web/mute.unmute'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@web/mute.unmute'])->exists())) {
            // Check  perms exists for atleast one of the server - For UI Actions
            $allowed = true;
        }

        return $allowed;
    }

    public static function hasBanPermission(int $serverId=null)
    {
        // Get the authenticated user
        $allowed = false;
        $user = Auth::user();
        // Admin expired on all servers
        if (!self::validateExpiryOnAllServers($user) && !$serverId) {
            $allowed = false;
        } elseif ($serverId && self::hasValidPermission($user, $serverId, '@css/ban')) {
            // has permission on the server
            $allowed = true;
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/ban', '@web/ban.add'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@css/ban', '@web/ban.add'])->exists())) {
            // Check perms exists for atleast one of the server - For UI Actions
            $allowed = true;
        }

        return $allowed;
    }

    public static function hasMutePermission(int $serverId=null)
    {
        // Get the authenticated user
        $allowed = false;
        $user = Auth::user();
        // Admin expired on all servers
        if (!self::validateExpiryOnAllServers($user) && !$serverId) {
            $allowed = false;
        } elseif ($serverId && self::hasValidPermission($user, $serverId, '@css/chat')) {
            // has permission on the server
            $allowed = true;
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/chat', '@web/mute.add'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@css/chat', '@web/mute.add'])->exists())) {
            // Check  perms exists for atleast one of the server - For UI Actions
            $allowed = true;
        }

        return $allowed;
    }

    public static function hasKickPermission(int $serverId=null)
    {
        // Get the authenticated user
        $allowed = false;
        $user = Auth::user();
        // Admin expired on all servers
        if (!self::validateExpiryOnAllServers($user) && !$serverId) {
            $allowed = false;
        } elseif ($serverId && self::hasValidPermission($user, $serverId, '@css/kick')) {
            // has permission on the server
            $allowed = true;
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/kick'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@css/kick'])->exists())) {
            // Check  perms exists for atleast one of the server - For UI Actions
            $allowed = true;
        }

        return $allowed;
    }

    private static function validateExpiryOnAllServers(?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
       return $user?->servers()
            ->where(function ($query) {
                $query->where('ends', '>=', Carbon::now()->toDateTimeString())
                    ->orWhereNull('ends');
            })
            ->exists();
    }

    private static function hasValidPermission(?\Illuminate\Contracts\Auth\Authenticatable $user, int $serverId, string $flag)
    {
        $web = [];
        $validPerms = false;
        $server = [$flag, '@css/root'];
        if($flag == '@css/ban'){
            $web = ['@web/ban.add'];
        } else if($flag == '@css/unban'){
            $web = ['@web/ban.unban'];
        }else if($flag == '@css/chat'){
            $web = ['@web/mute.add', '@web/mute.unmute'];
        }
        $flags = array_merge($server, $web);
        $serverPermissions = $user?->servers()
            ->where('server_id', $serverId)
            ->where(function ($query) {
                $query->where('ends', '>=', Carbon::now()->toDateTimeString())
                    ->orWhereNull('ends');
            })
            ->whereNull('group_id')
            ->get() ?? [];

        foreach ($serverPermissions as $permission) {
            if ($permission?->adminFlags()->whereIn('flag', $flags)->exists()) {
                $validPerms = true;
                break;
            }
        }
        if(!$validPerms){
            // check if perms exist in a  group
            $serverGroups = $user?->servers()
                ->where('server_id', $serverId)
                ->where(function ($query) {
                    $query->where('ends', '>=', Carbon::now()->toDateTimeString())
                        ->orWhereNull('ends');
                })
                ->whereNotNull('group_id')
                ->get() ?? [];

            foreach ($serverGroups as $server) {
                $groupServer = $server->groupsServers()->get();

                foreach ($groupServer as $group) {
                    if ($group->groupsFlags()->whereIn('flag', $flags)->exists()) {
                        $validPerms = true;
                        break 2;
                    }
                }
            }
        }
        return $validPerms;
    }
}
