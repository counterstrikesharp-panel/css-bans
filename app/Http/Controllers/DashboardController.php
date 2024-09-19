<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\ModuleHelper;
use App\Helpers\PermissionsHelper;
use App\Models\K4Ranks\Ranks;
use App\Models\K4Ranks\ZenithPlayerStorage;
use App\Models\ModuleServerSetting;
use App\Models\SaAdmin;
use App\Models\SaBan;
use App\Models\SaMute;
use App\Models\SaServer;
use App\Models\ServerStats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function check() {
        return view('requirement');
    }

    public function home(Request $request)
    {
        $updates = [];
        $activeBans = null;
        $activeMutes = null;
        $totalBans = SaBan::count();
        $totalServers = SaServer::count();
        $totalMutes = SaMute::count();
        $totalAdmins = SaAdmin::distinct('player_steamid')->count();
        $totalActiveBans =  SaBan::where('status', 'ACTIVE')->count();
        $totalActiveMutes =  SaMute::where('status', 'ACTIVE')->count();
        if(auth()->check()){
            $activeBans = SaBan::where('player_steamid', Auth::user()->steam_id)->where('status', 'ACTIVE')->count();
            $activeMutes = SaMute::where('player_steamid', Auth::user()->steam_id)->where('status', 'ACTIVE')->count();
        }
        if(PermissionsHelper::isSuperAdmin()) {
            $updates = $this->checkUpdates();
        }
        $topPlayersData = [];
        $servers = [];
        if(env('RANKS') == 'Enabled') {
            $serverId = $request->query('server_id');
            ModuleHelper::useConnection('Ranks', $serverId);
            $servers = ModuleServerSetting::all();
            $topPlayersData = $this->getTop5Players();
        }
        $playerChart = $this->getServerStatsWithSeriesFormat($request->input('interval'));
        $playerMapChart = $this->getMapStatsWithSeriesFormat($request->input('interval'));
        return view('admin.dashboard',
            compact(
                'totalBans',
                'totalServers',
                'totalMutes',
                'totalAdmins',
                'updates',
                'topPlayersData',
                'activeBans',
                'activeMutes',
                'totalActiveBans',
                'totalActiveMutes',
                'servers',
                'playerChart',
                'playerMapChart'
            )
        );
    }

    public function getMutes()
    {
        $recentMutes = SaMute::orderBy('created', 'desc')->take(5)->get();
        foreach($recentMutes as $mute){
            $mute->ends = $mute->duration == 0 ? "<h6><span class='badge badge-danger'>" . __('dashboard.permanent') . "</span></h6>" : $mute->ends;
        }
        return response()->json($recentMutes);
    }

    public function getBans()
    {
        $recentBans = SaBan::orderBy('created', 'desc')->take(5)->get();
        foreach($recentBans as $ban){
            $ban->ends = $ban->duration == 0 ? "<h6><span class='badge badge-danger'>" . __('dashboard.permanent') . "</span></h6>" : $ban->ends;
        }
        return response()->json($recentBans);
    }

    /**
     * @return array
     */
    private function checkUpdates()
    {
        $appVersion = config('app.version');
        $response = Http::get('https://css-bans-updates.matchclub.xyz/api/version');
        $updates = [];
        if ($response->successful()) {
            $data = $response->json();
            $latestVersion = $data['release_notes']['version'];
            $releaseNotes = $data['release_notes']['html'];

            if ($appVersion !== $latestVersion) {
                $updates = [
                    'version' => $latestVersion,
                    'notes' => $releaseNotes
                ];
            }
        }
        return $updates;
    }

    public function getTop5Players()
    {
        // Get the flag to determine whether to use old or new logic
        $useOldLogic = env('K4LegacySupport', 'no') == 'yes' ? true : false;

        if ($useOldLogic) {
            // Old Logic
            $topPlayers = Ranks::with('k4stats')
                ->orderBy('points', 'desc')
                ->take(5)
                ->get();

            foreach ($topPlayers as $player) {
                $player->player_steamid = $player->steam_id;
                $response = CommonHelper::steamProfile($player);
                $player->avatar = !empty($response['response']['players'][0]['avatar']) ? $response['response']['players'][0]['avatar'] : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg';
                $player->ratingImage = CommonHelper::getCSRatingImage($player->points);
                $player->rank = CommonHelper::getCSRankImage($player->rank);
                $player->last_seen = Carbon::parse($player->k4stats->lastseen ?? now())->diffForHumans();
                $player->kills = $player->k4stats->kills;
                $player->deaths = $player->k4stats->deaths;
                $player->game_win = $player->k4stats->game_win;
                $player->game_lose = $player->k4stats->game_lose;
                $player->profile = '';
            }

            $totalPlayers = Ranks::count();
        } else {
            // New Logic
            $topPlayers = ZenithPlayerStorage::orderByRaw('CAST(JSON_UNQUOTE(JSON_EXTRACT(`K4-Zenith-Ranks.storage`, "$.Points")) AS UNSIGNED) DESC')                ->take(5)
                ->get();
            $serverId = Crypt::encrypt(Session::get('Ranks_server'));
            foreach ($topPlayers as $player) {
                $playerRank = $player['K4-Zenith-Ranks.storage'];
                $playerStats = $player['K4-Zenith-Stats.storage'];
                $player->player_steamid = $player->steam_id;
                $response = CommonHelper::steamProfile($player);
                $player->name = !empty($response['response']['players'][0]['personaname']) ? $response['response']['players'][0]['personaname'] : 'Profile';
                $player->avatar = !empty($response['response']['players'][0]['avatar']) ? $response['response']['players'][0]['avatar'] : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg';
                $player->ratingImage = CommonHelper::getCSRatingImage($playerRank['Points']);
                $player->rank = CommonHelper::getCSRankImage($playerRank['Rank'] ?? 'N/A');
                $player->profile = env('VITE_SITE_DIR')."/ranks/profile/$player->player_steamid/$serverId";
                // Assigning additional stats
                $player->kills = $playerStats['Kills'] ?? 0;
                $player->deaths = $playerStats['Deaths'] ?? 0;
                $player->game_win = $playerStats['GameWin'] ?? 0;
                $player->game_lose = $playerStats['GameLose'] ?? 0;
                $player->last_seen = Carbon::parse($player->last_online ?? now())->diffForHumans();
            }

            $totalPlayers = ZenithPlayerStorage::count();
        }

        return ['topPlayers' => $topPlayers, 'totalPlayers' => $totalPlayers];
    }


    public function getServerStatsWithSeriesFormat($intervalType)
    {
        $maxSteps = 12;

        switch ($intervalType) {
            case '1hour':
                $interval = 60; // 60 minutes for 1 hour
                $dateFormat = 'Y-m-d H:00:00'; // Group by hour
                // Adjust totalMinutes to include the current hour
                $totalMinutes = ($interval * $maxSteps) - Carbon::now()->minute;
                break;
            case '1day':
                $interval = 1440; // 1440 minutes for 1 day
                $dateFormat = 'Y-m-d'; // Group by day
                // Adjust totalMinutes to include the current day
                $totalMinutes = ($interval * $maxSteps) - ((Carbon::now()->hour * 60) + Carbon::now()->minute);
                break;
            case '1month':
                // Assuming 43200 minutes per month (30 days), use this as an approximation
                $interval = 43200; // 30 days * 24 hours * 60 minutes = 43200 minutes per month
                $dateFormat = 'Y-m'; // Group by month
                // Adjust totalMinutes to approximate months, including the current month
                $currentDayOfMonth = Carbon::now()->day;
                $totalMinutes = ($interval * $maxSteps) - (($currentDayOfMonth - 1) * 1440 + (Carbon::now()->hour * 60) + Carbon::now()->minute);
                break;
            default: // '5min' or other minute intervals
                $interval = 5; // 5 minutes for the default 5-minute interval
                $dateFormat = 'Y-m-d H:i:00'; // Group by 5-minute intervals

                $totalMinutes = ($interval * $maxSteps) - (Carbon::now()->minute % $interval);

        }
        if($interval == 5) {
            $startTimeOriginal = Carbon::now()->subMinutes($totalMinutes);
            $minute = floor($startTimeOriginal->minute / 5) * 5;
            $startTime = Carbon::parse($startTimeOriginal->format("Y-m-d H:$minute"));
        }
        else
            $startTime = Carbon::now()->subMinutes($totalMinutes);

        $endTime = Carbon::now();

        $serverStats = ServerStats::selectRaw('server_id, MAX(player_count) as player_count, FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(recorded_at) / (' . ($interval * 60) . ')) * (' . ($interval * 60) . ')) as interval_time')
            ->where('recorded_at', '>=', $startTime)
            ->groupBy('server_id', 'interval_time')
            ->orderBy('interval_time')
            ->get();

        $intervals = [];
        $steps = 0;

        while ($startTime <= $endTime && $steps < $maxSteps) {
            $intervals[] = $startTime->format($dateFormat);  // Format according to the interval type
            $startTime->addMinutes($interval);
            $steps++;
        }

        $groupedData = [];
        $serverIds = $serverStats->pluck('server_id');
        $allServers = SaServer::has('visible')->get();
        foreach ($serverStats as $stat) {
            $serverId = $stat->server_id;
            $intervalTime = ($intervalType == '1day' || $intervalType == '1month' || $intervalType == '1hour') ? Carbon::parse($stat->interval_time)->format($dateFormat):$stat->interval_time;
            $playerCount = $stat->player_count;
            $groupedData[$serverId][$intervalTime] = $playerCount;
        }
        $seriesData = [];

        foreach ($groupedData as $serverId => $intervalData) {
            $series = [
                'name' => $allServers->where('id', $serverId)->value('hostname'),
                'data' => []
            ];

            foreach ($intervals as $interval) {
                // Check against different intervals (hours, days, weeks, months)
                $series['data'][] = $intervalData[$interval] ?? 0;
            }

            $seriesData[] = $series;
        }
        // Format intervals for JavaScript before sending to the view
        $formattedIntervals = array_map(function ($interval) use ($intervalType) {

            // Default case for other intervals
            switch ($intervalType) {
                case '1hour':
                    $format = 'D H:00';
                    break;
                case '1day':
                    $format = 'D, M j';
                    break;
                case '5min':
                    $format = 'D H:i';
                    break;
                case '1month':
                    $format = 'M Y';
                    break;
                default:
                    $format = 'Y-m-d H:i:s';  // Default format
            }

            return Carbon::parse($interval, config('app.timezone'))->format($format);
        }, $intervals);
        // Prepare other active servers if no data
        $allServerData = array_diff($allServers->pluck('id')->toArray(), $serverIds->toArray());
        foreach($allServerData as $serverId) {
            $data = [];
            for ($i = 0; $i < count($intervals); $i++) {
                $data[] = 0;
            }
            $seriesData[] = [
                "name" => $allServers->where('id', $serverId)->value('hostname'),
                'data'=> $data
            ];
        }

        return [
            'seriesData' => json_encode($seriesData),
            'intervals' => json_encode($formattedIntervals)
        ];
    }

    public function getMapStatsWithSeriesFormat($intervalType)
    {

        $maxSteps = 12;

        switch ($intervalType) {
            case '1hour':
                $interval = 60; // 60 minutes for 1 hour
                $dateFormat = 'Y-m-d H:00:00'; // Group by hour
                $totalMinutes = ($interval * $maxSteps) - Carbon::now()->minute;
                break;
            case '1day':
                $interval = 1440; // 1440 minutes for 1 day
                $dateFormat = 'Y-m-d 00:00:00'; // Group by day
                $totalMinutes = ($interval * $maxSteps) - ((Carbon::now()->hour * 60) + Carbon::now()->minute);
                break;
            case '1month':
                // Assuming 43200 minutes per month (30 days), use this as an approximation
                $interval = 43200; // 30 days * 24 hours * 60 minutes = 43200 minutes per month
                $dateFormat = 'Y-m'; // Group by month
                // Adjust totalMinutes to approximate months, including the current month
                $currentDayOfMonth = Carbon::now()->day;
                $totalMinutes = ($interval * $maxSteps) - (($currentDayOfMonth - 1) * 1440 + (Carbon::now()->hour * 60) + Carbon::now()->minute);
                break;
            default:
                $interval = 5; // 5 minutes for the default 5-minute interval
                $dateFormat = 'Y-m-d H:i:00'; // Group by 5-minute intervals
                $totalMinutes = ($interval * $maxSteps) - (Carbon::now()->minute % $interval);
        }

        if($interval == 5) {
            $startTimeOriginal = Carbon::now()->subMinutes($totalMinutes);
            $minute = floor($startTimeOriginal->minute / 5) * 5;
            $startTime = Carbon::parse($startTimeOriginal->format("Y-m-d H:$minute"));
        }
        else
            $startTime = Carbon::now()->subMinutes($totalMinutes);
        $endTime = Carbon::now();

        // Fetch all unique maps from the stats table
        $allMaps = ServerStats::distinct('map')->pluck('map');

        // Query the stats grouped by map and interval time
        $mapStats = ServerStats::selectRaw('map, MAX(player_count) as player_count, FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(recorded_at) / (' . ($interval * 60) . ')) * (' . ($interval * 60) . ')) as interval_time')
            ->where('recorded_at', '>=', $startTime)
            ->groupBy('map', 'interval_time')
            ->orderBy('interval_time')
            ->get();

        $intervals = [];
        $steps = 0;

        while ($startTime <= $endTime && $steps < $maxSteps) {
            $intervals[] = $startTime->format($dateFormat);  // Format according to the interval type
            $startTime->addMinutes($interval);
            $steps++;
        }

        // Group data by map
        $groupedData = [];
        $mapNames = $mapStats->pluck('map');
        foreach ($mapStats as $stat) {
            $map = $stat->map;
            $intervalTime = ($intervalType == '1day' || $intervalType == '1month' || $intervalType == '1hour') ? Carbon::parse($stat->interval_time)->format($dateFormat):$stat->interval_time;
            $playerCount = $stat->player_count;
            $groupedData[$map][$intervalTime] = $playerCount;
        }

        // Prepare series data for each map
        $seriesData = [];
        foreach ($groupedData as $map => $intervalData) {
            $series = [
                'name' => $map,  // Use map name instead of server hostname
                'data' => []
            ];

            foreach ($intervals as $interval) {
                // Check for player count data for each interval, default to 0
                $series['data'][] = $intervalData[$interval] ?? 0;
            }

            $seriesData[] = $series;
        }

        // Format intervals for JavaScript before sending to the view
        $formattedIntervals = array_map(function ($interval) use ($intervalType) {

            // Default case for other intervals
            switch ($intervalType) {
                case '1hour':
                    $format = 'D H:00';
                    break;
                case '1day':
                    $format = 'D, M j';
                    break;
                case '5min':
                    $format = 'D H:i';
                    break;
                case '1month':
                    $format = 'M Y';
                    break;
                default:
                    $format = 'Y-m-d H:i:s';  // Default format
            }

            return Carbon::parse($interval, config('app.timezone'))->format($format);
        }, $intervals);

        // Prepare all maps if no data for certain maps
        $missingMapData = array_diff($allMaps->toArray(), $mapNames->toArray());
        foreach ($missingMapData as $map) {
            $data = array_fill(0, count($intervals), 0);  // Fill with zeros for missing data
            $seriesData[] = [
                "name" => $map,
                'data' => $data
            ];
        }
        return [
            'seriesData' => json_encode($seriesData),
            'intervals' => json_encode($formattedIntervals)
        ];
    }


}
