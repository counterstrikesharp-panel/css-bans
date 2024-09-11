<?php

namespace App\Http\Controllers\K4Ranks;

use App\Helpers\CommonHelper;
use App\Helpers\ModuleHelper;
use App\Http\Controllers\Controller;
use App\Models\K4Ranks\Ranks;
use App\Models\K4Ranks\ZenithMapStats;
use App\Models\K4Ranks\ZenithPlayerStorage;
use App\Models\K4Ranks\ZenithWeaponStats;
use App\Models\ModuleServerSetting;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class RanksController extends Controller
{
    public function index(Request $request)
    {
        $serverId = $request->query('server_id');
        ModuleHelper::useConnection('Ranks', $serverId);
        $servers = ModuleServerSetting::all();
        return view('k4Ranks.list', compact('servers'));
    }
    public function getPlayersList(Request $request)
    {
        ModuleHelper::useConnection('Ranks');

        // Extract parameters sent by DataTables
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumn = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');

        // Get the flag to determine whether to use old or new logic
        $useOldLogic = env('K4LegacySupport', 'no') == 'yes' ? true : false;

        if ($useOldLogic) {
            // Old Logic
            $query = Ranks::selectRaw('*, (SELECT COUNT(*) + 1 FROM k4ranks AS kr WHERE kr.points > k4ranks.points) AS `position`');
            if (!empty($searchValue)) {
                $query->where('steam_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('name', 'like', '%' . $searchValue . '%');
            }
            if ($orderColumn !== null) {
                $query->orderBy($request->input('columns.' . $orderColumn . '.data'), $orderDirection);
            }
        } else {
            // New Logic
            $query = ZenithPlayerStorage::selectRaw('*, (SELECT COUNT(*) + 1 FROM zenith_player_storage AS zps WHERE JSON_EXTRACT(zps.`K4-Zenith-Ranks.storage`, "$.Points") > JSON_EXTRACT(zenith_player_storage.`K4-Zenith-Ranks.storage`, "$.Points")) AS `position`');
            if (!empty($searchValue)) {
                $query->where('steam_id', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(`K4-Zenith-Ranks.storage`, "$.Rank")) like ?', ['%' . $searchValue . '%']);
            }
            if ($orderColumn !== null) {
                $columnName = $request->input('columns.' . $orderColumn . '.data');
                if ($columnName == 'points') {
                    $query->orderByRaw('JSON_EXTRACT(`K4-Zenith-Ranks.storage`, "$.Points") ' . $orderDirection);
                } else {
                    $query->orderBy($columnName, $orderDirection);
                }
            }
        }

        // Fetch all players
        $players = $query->offset($start)->limit($length)->get();

        // Format the data for the table
        $formattedData = [];
        foreach ($players as $player) {
            if ($useOldLogic) {
                // Old Logic
                $formattedData[] = [
                    "position" => $player->position,
                    "name" => $player->name,
                    "player_steamid" => $player->steam_id,
                    "points" => CommonHelper::getCSRatingImage($player->points),
                    "rank" => CommonHelper::getCSRankImage($player->rank),
                    "kills" => $player->k4stats->kills,
                    "deaths" => $player->k4stats->deaths,
                    "assists" => $player->k4stats->assists,
                    "headshots" => $player->k4stats->headshots,
                    "rounds_ct" => $player->k4stats->rounds_ct,
                    "rounds_t" => $player->k4stats->rounds_t,
                    "rounds_overall" => $player->k4stats->rounds_overall,
                    "games_won" => $player->k4stats->game_win,
                    "games_lost" => $player->k4stats->game_lose,
                    "avatar" => !empty($response['response']['players'][0]['avatar']) ? $response['response']['players'][0]['avatar'] : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg',
                    "last_seen" => Carbon::parse($player->k4stats->lastseen)->diffForHumans(),
                ];
            } else {
                // New Logic
                $playerData = $player['K4-Zenith-Stats.storage'];
                $playerRank = $player['K4-Zenith-Ranks.storage'];
                $player->player_steamid = $player->steam_id;
                $response = CommonHelper::steamProfile($player);
                $serverId = Crypt::encrypt(Session::get('Ranks_server'));
                $formattedData[] = [
                    "profile" =>  env('VITE_SITE_DIR')."/ranks/profile/$player->player_steamid/$serverId",
                    "position" => $player->position,
                    "name" => !empty($response['response']['players'][0]['personaname']) ? $response['response']['players'][0]['personaname'] : 'Profile',
                    "player_steamid" => $player->steam_id,
                    "points" => CommonHelper::getCSRatingImage($playerRank['Points']),
                    "rank" => CommonHelper::getCSRankImage($playerRank['Rank'] ?? 'N/A'),
                    "kills" => $playerData['Kills'] ?? 0,
                    "deaths" => $playerData['Deaths'] ?? 0,
                    "assists" => $playerData['Assists'] ?? 0,
                    "headshots" => $playerData['Headshots'] ?? 0,
                    "rounds_ct" => $playerData['RoundsCT'] ?? 0,
                    "rounds_t" => $playerData['RoundsT'] ?? 0,
                    "rounds_overall" => ($playerData['RoundsCT'] ?? 0) + ($playerData['RoundsT'] ?? 0),
                    "games_won" => $playerData['GameWin'] ?? 0,
                    "games_lost" => $playerData['GameLose'] ?? 0,
                    "avatar" => !empty($response['response']['players'][0]['avatar']) ? $response['response']['players'][0]['avatar'] : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg',
                    "last_seen" => Carbon::parse($player->last_online ?? now())->diffForHumans(),
                ];
            }
        }
        // Return the formatted data as JSON for the DataTable
        return response()->json([
            'draw' => $request->input('draw'),
            "recordsTotal" => $useOldLogic ? Ranks::count() : ZenithPlayerStorage::count(),
            "recordsFiltered" => !empty($searchValue) ? count($formattedData) : ($useOldLogic ? Ranks::count() : ZenithPlayerStorage::count()),
            "data" => $formattedData
        ]);

    }

    public function viewProfile(Request $request, $steam_id, $server_id) {
        ModuleHelper::useConnection('Ranks',$server_id);
        $player = ZenithPlayerStorage::where('steam_id', $steam_id)->firstOrFail();
        $playerTimeStats = $player['K4-Zenith-TimeStats.storage'];
        $playerStats = $player['K4-Zenith-Stats.storage'];
        $playerRank = $player['K4-Zenith-Ranks.storage'];

        // Fetch the total playtime, Terrorist playtime, and CT playtime
        $totalPlaytime = $playerTimeStats['TotalPlaytime'] ?? 0;
        $terroristPlaytime = $playerTimeStats['TerroristPlaytime'] ?? 0;
        $ctPlaytime = $playerTimeStats['CounterTerroristPlaytime'] ?? 0;

        // Format to 1 decimal point and ensure it's in hours
        $formattedPlaytime = number_format(CarbonInterval::minutes($totalPlaytime)->totalHours, 2);
        $formattedTPlaytime = number_format(CarbonInterval::minutes($terroristPlaytime)->totalHours, 2);
        $formattedCTPlaytime = number_format(CarbonInterval::minutes($ctPlaytime)->totalHours, 2);

        // Extract round statistics
        $roundWin = $playerStats['RoundWin'] ?? 0;
        $roundLose = $playerStats['RoundLose'] ?? 0;
        $roundsOverall = $playerStats['RoundsOverall'] ?? 0;
        $mvp = $playerStats['MVP'] ?? 0;
        $headshots = $playerStats['Headshots'] ?? 0;
        $totalKills = $playerStats['Kills'] ?? 0;
        $gamesWon = $playerStats['GameWin'] ?? 0;

        // Fetch top 5 maps based on the sum of kills
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $topMaps =  ZenithMapStats::where('steam_id', $steam_id)
            ->select('map_name', 'game_win', 'game_lose')
            ->where('steam_id', $steam_id)
            ->groupBy('map_name')
            ->orderBy('game_win', 'desc')
            ->limit(8)
            ->get();
        // Calculate win rate for each map
        $topMaps =  $topMaps->transform(function ($map) {
            $totalRounds = $map->game_win + $map->game_lose;
            $map->win_rate = $totalRounds > 0 ? ($map->game_win / $totalRounds) * 100 : 0;
            return $map;
        })->sortByDesc('win_rate');

        $weaponStats = ZenithWeaponStats::where('steam_id', $steam_id)
            ->select('weapon', 'kills', 'headshots', 'hits', 'chest_hits', 'stomach_hits')
            ->orderBy('kills', 'desc')
            ->limit(5)
            ->get()
            ->transform(function ($weapon) {
                // Define the image path based on weapon name
                $imagePath = 'images/weapons/weapon_' . strtolower($weapon->weapon) . '.png';

                // Check if the image exists, if not, use a default image
                $weapon->image_url = File::exists(public_path($imagePath))
                    ? asset($imagePath)
                    : asset('images/weapons/weapon_elite.png');

                return $weapon;
            });

        // Fetch Steam profile info (avatar and name) from CommonHelper
        $player->player_steamid = $player->steam_id;
        $response = CommonHelper::steamProfile($player);

        // Extract avatar and name from the response
        $avatar = !empty($response['response']['players'][0]['avatar'])
            ? $response['response']['players'][0]['avatar']
            : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg';
        $name = !empty($response['response']['players'][0]['personaname'])
            ? $response['response']['players'][0]['personaname']
            : 'Profile';

        // Fetch the rating image based on points
        $points = $playerRank['Points'] ?? 0;

        $rank = $playerRank['Rank'] ?? 'N/A';
        $rankImage = CommonHelper::getCSRankImage($rank);
        $ratingImage = CommonHelper::getCSRatingImage($points);
        $firstBlood = $playerStats['FirstBlood'];
        $bombPlanted = $playerStats['BombPlanted'];
        $seen = Carbon::parse($player->last_online ?? now())->diffForHumans();
        return view('k4Ranks.profile', compact('firstBlood','bombPlanted','seen','avatar','name','rankImage','ratingImage','mvp','headshots','totalKills','gamesWon','player', 'formattedPlaytime', 'formattedTPlaytime', 'formattedCTPlaytime', 'roundWin', 'roundLose', 'roundsOverall', 'topMaps', 'weaponStats'));

    }
}
