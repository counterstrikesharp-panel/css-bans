<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonInterval;

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
}
