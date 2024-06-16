<?php

namespace App\Helpers;

use App\Models\Appeal\Appeal;
use App\Models\Report\Report;
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

   public static function appealCheck() {
       return Appeal::where('status', 'pending')->count();
   }

   public static function reportCheck() {
       return Report::count();
   }

    public static function getCSRatingImage($score)
    {
        $imagePath = '';
        $classRating = '';
        if ($score >= 0 && $score <= 4999) {
            $imagePath = 'images/ratings/rating.common.png';
            $classRating = 'common';
        } elseif ($score >= 5000 && $score <= 9999) {
            $imagePath = 'images/ratings/rating.uncommon.png';
            $classRating = 'uncommon';
        } elseif ($score >= 10000 && $score <= 14999) {
            $imagePath = 'images/ratings/rating.rare.png';
            $classRating = 'rare';
        } elseif ($score >= 15000 && $score <= 19999) {
            $imagePath = 'images/ratings/rating.mythical.png';
            $classRating = 'mythical';
        } elseif ($score >= 20000 && $score <= 24999) {
            $imagePath = 'images/ratings/rating.legendary.png';
            $classRating = 'legendary';
        } elseif ($score >= 25000 && $score <= 29999) {
            $imagePath = 'images/ratings/rating.ancient.png';
            $classRating = 'ancient';
        } elseif ($score >= 30000) {
            $imagePath = 'images/ratings/rating.unusual.png';
            $classRating = 'unusual';
        }
        return '<span class="cs2rating-text-' . $classRating . '">'.$score.'</span><img src="' . asset(getAppSubDirectoryPath().$imagePath) . '" class="cs2rating" alt="CS Rating">';
    }
}
