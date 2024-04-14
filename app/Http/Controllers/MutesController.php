<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\PermissionsHelper;
use App\Models\SaMute;
use App\Models\SaServer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class MutesController extends Controller
{
    public function mutes()
    {
        return view('admin.mutes.list');
    }

    public function getMutesList(Request $request)
    {
        // Extract parameters sent by DataTables
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumn = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');

        // Start building the query
        $query = SaMute::query();

        // Apply search filter
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('player_steamid', 'like', '%' . $searchValue . '%')
                    ->orWhere('player_name', 'like', '%' . $searchValue . '%');
            });
        }

        // Apply sorting
        if ($orderColumn !== null) {
            $query->orderBy($request->input('columns.' . $orderColumn . '.data'), $orderDirection);
        }

        // Paginate the results
        $mutes = $query->offset($start)->limit($length)->get();

        // Get total count for pagination
        $totalMutes = SaMute::count();

        $formattedData = [];

        // Format each ban record
        foreach ($mutes as $mute) {
            $formattedData[] = [
                "id" => $mute->id,
                "player_steamid" => $mute->player_steamid,
                "player_name" => $mute->player_name,
                "admin_steamid" => $mute->admin_steamid,
                "admin_name" => $mute->admin_name,
                "reason" => $mute->reason,
                "ends" => $mute->ends,
                "created" => $mute->created,
                "server_id" => $mute->server->hostname,
                "status" => $mute->status == 'ACTIVE' ? "<h6><span class='badge badge-success'>Active</span></h6>" : ($mute->status == 'UNMUTED' ? "<h6><span class='badge badge-primary'>Unmuted</span></h6>" : "<h6><span class='badge badge-danger'>Expired</span></h6>"),
                'action' => $mute->status == 'ACTIVE' && PermissionsHelper::hasUnMutePermission() ? "<button class='btn btn-success btn-sm unmute-btn' data-player-steamid='{$mute->player_steamid}'><i class='fas fa-ban'></i></button>" : "",
                "duration" => $mute->duration == 0 && $mute->status != 'UNMUTED' ? "<h6><span class='badge badge-danger'>Permanent</span></h6>" : CommonHelper::minutesToTime($mute->duration),
            ];
        }

        $response = [
            'draw' => $request->input('draw'),
            "recordsTotal" => $totalMutes,
            "recordsFiltered" => !empty($searchValue) ? count($formattedData) : $totalMutes ,
            "data" => $formattedData
        ];

        return response()->json($response);
    }

    public function unmute(Request $request, $playerSteamid)
    {
        $mutes = SaMute::where('player_steamid', $playerSteamid)->get();

        try {
            // Start a database transaction
            DB::beginTransaction();

            foreach ($mutes as $mute) {
                // Update each ban record to mark it as unbanned
                $mute->status = 'UNMUTED';
                $mute->ends = now();
                $mute->save();
            }

            // If all unbans are successful, commit the transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'All players uunmuted successfully']);
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function create()
    {
        $servers = SaServer::all();
        return view('admin.mutes.add', ['servers' => $servers]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'player_steam_id' => 'required|numeric|digits:17',
            'reason' => 'required',
            'duration' => 'required',
            'server_ids' => 'required|array',
            'server_ids.*' => 'exists:sa_servers,id',
        ]);

        try {
            $steamId = $validatedData['player_steam_id'];
            $steamApiKey = env('STEAM_CLIENT_SECRET');
            $response = Http::get("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$steamApiKey}&steamids={$steamId}");

            if ($response->failed()) {
                return Redirect::back()->withErrors(['msg' => 'Invalid Steam ID or unable to connect to Steam API.']);
            }

            $responseData = $response->json();
            if (!isset($responseData['response']['players'][0])) {
                return Redirect::back()->withErrors(['msg' => 'Invalid Steam ID or unable to connect to Steam API.']);
            }

            $profileName = $responseData['response']['players'][0]['personaname'];
            DB::beginTransaction();
            $bansAdded = false;
            foreach ($validatedData['server_ids'] as $serverId) {
                $existingBan = SaMute::where('player_steamid', $steamId)
                    ->where('server_id', $serverId)
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($existingBan) {
                    continue;
                }
                $carbonTimestamp = Carbon::parse($validatedData['duration']);
                $minutesDifference = $carbonTimestamp->diffInMinutes(Carbon::now());
                $samute = new SaMute();
                $samute->player_steamid = $validatedData['player_steam_id'];
                $samute->reason = $validatedData['reason'];
                $samute->player_name = $profileName;
                $samute->duration = $minutesDifference;
                $samute->server_id = $serverId;
                $samute->admin_name = auth()->user()->name;
                $samute->admin_steamid = auth()->user()->steam_id;
                $samute->ends = $validatedData['duration'];
                $samute->save();
                $bansAdded = true;
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            Log::error('ban.error: ' . $e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'There was an error while adding the ban.']);
        }
        if (!$bansAdded) {
            return Redirect::back()->withErrors(['msg' => 'Bans already exist for all specified servers.']);
        }

        return redirect()->route('list.mutes')->with('success', 'Mute added successfully');
    }
}
