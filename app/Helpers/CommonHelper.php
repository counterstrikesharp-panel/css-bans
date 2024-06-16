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

    public static function getCSRankImage($rank)
    {
        if ($rank == 'Silver I') {
            $imagePath = 'images/ratings/1.png';
            $classRating = 'silver1';
        } elseif ($rank == 'Silver II') {
            $imagePath = 'images/ratings/2.png';
            $classRating = 'silver2';
        } elseif ($rank == 'Silver III') {
            $imagePath = 'images/ratings/3.png';
            $classRating = 'silver3';
        } elseif ($rank == 'Silver IV') {
            $imagePath = 'images/ratings/4.png';
            $classRating = 'silver4';
        } elseif ($rank == 'Silver Elite') {
            $imagePath = 'images/ratings/5.png';
            $classRating = 'silverelite';
        } elseif ($rank == 'Silver Elite Master') {
            $imagePath = 'images/ratings/6.png';
            $classRating = 'silverelitemaster';
        } elseif ($rank == 'Gold Nova I') {
            $imagePath = 'images/ratings/7.png';
            $classRating = 'goldnova1';
        } elseif ($rank == 'Gold Nova II') {
            $imagePath = 'images/ratings/8.png';
            $classRating = 'goldnova2';
        } elseif ($rank == 'Gold Nova III') {
            $imagePath = 'images/ratings/9.png';
            $classRating = 'goldnova3';
        } elseif ($rank == 'Gold Nova Master') {
            $imagePath = 'images/ratings/10.png';
            $classRating = 'goldnovamaster';
        } elseif ($rank == 'Master Guardian I') {
            $imagePath = 'images/ratings/11.png';
            $classRating = 'masterguardian1';
        } elseif ($rank == 'Master Guardian II') {
            $imagePath = 'images/ratings/12.png';
            $classRating = 'masterguardian2';
        } elseif ($rank == 'Master Guardian Elite') {
            $imagePath = 'images/ratings/13.png';
            $classRating = 'masterguardianelite';
        } elseif ($rank == 'Distinguished Master Guardian') {
            $imagePath = 'images/ratings/14.png';
            $classRating = 'distinguishedmasterguardian';
        } elseif ($rank == 'Legendary Eagle') {
            $imagePath = 'images/ratings/15.png';
            $classRating = 'legendaryeagle';
        } elseif ($rank == 'Legendary Eagle Master') {
            $imagePath = 'images/ratings/16.png';
            $classRating = 'legendaryeaglemaster';
        } elseif ($rank == 'Supreme Master First Class') {
            $imagePath = 'images/ratings/17.png';
            $classRating = 'suprememasterfirstclass';
        } elseif ($rank == 'Global Elite') {
            $imagePath = 'images/ratings/18.png';
            $classRating = 'globalelite';
        }
        else {
            $imagePath = 'images/ratings/1.png';
            $classRating = 'none';
        }

        return '<img src="' . asset($imagePath) . '" class="cs2rating" alt="CS Rating">';
    }
}
