<?php

namespace App\Http\Controllers\K4Ranks;

use App\Helpers\CommonHelper;
use App\Helpers\ModuleHelper;
use App\Http\Controllers\Controller;
use App\Models\K4Ranks\Ranks;
use App\Models\K4Ranks\ZenithPlayerStorage;
use App\Models\ModuleServerSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
                $formattedData[] = [
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
}
