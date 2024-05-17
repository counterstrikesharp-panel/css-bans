<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CommonHelper
{

   public static function minutesToTime($minutes) {
       $interval = CarbonInterval::minutes($minutes);
       return $interval->cascade()->forHumans();
   }

   public static function formatDate($dateTime) {
       $carbonDate = Carbon::parse($dateTime);
       return $carbonDate->format('Y-m-d H:i:s');
   }

   public static function steamProfile($resource) {
       $steamApiKey = env('STEAM_CLIENT_SECRET');
       if (Cache::has('steam_player_summary_' . $resource->player_steamid)) {
           return Cache::get('steam_player_summary_' . $resource->player_steamid);
       } else {
           // If not found in cache, fetch from API and cache it
          Cache::remember('steam_player_summary_' . $resource->player_steamid, 60 * 60 * 24, function () use ($steamApiKey, $resource) {
               try {
                   return Http::get("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$steamApiKey}&steamids={$resource->player_steamid}")->json();
               } catch (\Exception $e) {
                   Log::error('production.steam.avatar.error'. $e->getMessage());
                   return null;
               }
           });
       }
   }
}
