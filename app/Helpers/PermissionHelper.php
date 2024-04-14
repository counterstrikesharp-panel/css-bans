<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionsHelper
{
    public static function isAdmin()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has the specified permission
        if ($user && $user->permissions()->where('player_steamid', $user->steam_id)->exists()) {
            return true;
        }

        return false;
    }

    public static function isSuperAdmin()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has the specified permission
        if ($user && $user->permissions()->where('player_steamid', $user->steam_id)->where('flags' ,'@css/root')->exists()) {
            return true;
        }

        return false;
    }

    public static function hasUnBanPermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has the specified permission
        if ($user && $user->permissions()->where('player_steamid', $user->steam_id)->whereIn('flags', ['@css/unban','@css/root'])->exists()) {
            return true;
        }

        return false;
    }

    public static function hasUnMutePermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has the specified permission
        if ($user && $user->permissions()->where('player_steamid', $user->steam_id)->whereIn('flags', ['@css/chat','@css/root'])->exists()) {
            return true;
        }

        return false;
    }

    public static function hasBanPermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has the specified permission
        if ($user && $user->permissions()->where('player_steamid', $user->steam_id)->whereIn('flags', ['@css/root', '@css/ban'])->exists()) {
            return true;
        }

        return false;
    }

    public static function hasMutePermission()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has the specified permission
        if ($user && $user->permissions()->where('player_steamid', $user->steam_id)->whereIn('flags', ['@css/root', '@css/chat'])->exists()) {
            return true;
        }

        return false;
    }
}
