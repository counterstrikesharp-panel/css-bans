<?php

namespace App\Http\Controllers\K4Ranks;

use App\Helpers\CommonHelper;
use App\Helpers\ModuleHelper;
use App\Http\Controllers\Controller;
use App\Models\K4Ranks\Ranks;
use App\Models\ModuleServerSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        // Start building the query

        // Define the subquery for calculating rank
        $query = Ranks::selectRaw('*, (SELECT COUNT(*) + 1 FROM k4ranks AS kr WHERE kr.points > k4ranks.points) AS `position`');
        // Apply search filter
        if (!empty($searchValue)) {
            $query->where('steam_id', 'like', '%' . $searchValue . '%')
                ->orWhere('name', 'like', '%' . $searchValue . '%');
        }

        // Apply sorting
        if ($orderColumn !== null) {
            $query->orderBy($request->input('columns.' . $orderColumn . '.data'), $orderDirection);
        }

        // Fetch all players
        $players = $query->offset($start)->limit($length)->get();

        // Format the data for the table
        $formattedData = [];
        foreach ($players as $player) {
            $player->player_steamid = $player->steam_id;
            $response = CommonHelper::steamProfile($player);
            $player->points = CommonHelper::getCSRatingImage($player->points);
            $formattedData[] = [
                "position" => $player->position,
                "name" => $player->name,
                "player_steamid" => $player->steam_id,
                "points" => $player->points,
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
        }

        // Return the formatted data as JSON for the DataTable
        return response()->json([
            'draw' => $request->input('draw'),
            "recordsTotal" => Ranks::count(),
            "recordsFiltered" => !empty($searchValue) ? count($formattedData) : Ranks::count() ,
            "data" => $formattedData
        ]);
    }
}
