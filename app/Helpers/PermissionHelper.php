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
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/unban'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@css/unban'])->exists())) {
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
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/chat'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@css/chat'])->exists())) {
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
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/ban'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@css/ban'])->exists())) {
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
        } elseif ($user && !$serverId && ($user->permissions()->whereIn('flag', ['@css/root', '@css/chat'])->exists()
                || $user->groupPermissions()->whereIn('flag',['@css/root', '@css/chat'])->exists())) {
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
        $validPerms = $user?->servers()
            ->where('server_id', $serverId)
            ->where(function ($query) {
                $query->where('ends', '>=', Carbon::now()->toDateTimeString())
                    ->orWhereNull('ends');
            })
            ->whereNull('group_id')
            ->first()
            ?->adminFlags()
            ->whereIn('flag', [$flag, '@css/root'])
            ->exists();
        if(!$validPerms){
            // check if perms exist in a  group
            $validPerms =$user?->servers()
                ->where('server_id', $serverId)
                ->where(function ($query) {
                    $query->where('ends', '>=', Carbon::now()->toDateTimeString())
                        ->orWhereNull('ends');
                })
                ->whereNotNull('group_id')
                ->first()
                ?->groupsServers()
                ->first()
                ?->groupsFlags()
                ->whereIn('flag', [$flag, '@css/root'])
                ->exists();
        }
        return $validPerms;
    }
}
