<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\PermissionsHelper;
use App\Models\SaBan;
use App\Models\SaServer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class BansController extends Controller
{
    public function bans()
    {
        return view('admin.bans.list');
    }

    public function getBansList(Request $request)
    {
        // Extract parameters sent by DataTables
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumn = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');

        // Start building the query
        $query = SaBan::query();

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
        $bans = $query->offset($start)->limit($length)->get();

        // Get total count for pagination
        $totalBans = SaBan::count();

        $formattedData = [];

        // Format each ban record
        foreach ($bans as $ban) {
            $formattedData[] = [
                "id" => $ban->id,
                "player_steamid" => $ban->player_steamid,
                "player_name" => $ban->player_name,
                "admin_steamid" => $ban->admin_steamid,
                "admin_name" => $ban->admin_name,
                "reason" => $ban->reason,
                "duration" => $ban->duration == 0 && $ban->status != 'UNBANNED' ? "<h6><span class='badge badge-danger'>Permanent</span></h6>" : CommonHelper::minutesToTime($ban->duration),
                "ends" => $ban->ends,
                "created" => $ban->created,
                "server_id" => $ban->server->hostname,
                'action' => $ban->status == 'ACTIVE' && PermissionsHelper::hasUnBanPermission() ? "<button class='btn btn-success btn-sm unban-btn' data-player-steamid='{$ban->player_steamid}'><i class='fas fa-ban'></i></button>" : "",
                "status" => $ban->status == 'ACTIVE' ? "<h6><span class='badge badge-success'>Active</span></h6>" : ($ban->status == 'UNBANNED' ? "<h6><span class='badge badge-primary'>Unbanned</span></h6>" : "<h6><span class='badge badge-danger'>Expired</span></h6>"),
            ];
        }

        $response = [
            'draw' => $request->input('draw'),
            "recordsTotal" => $totalBans,
            "recordsFiltered" => !empty($searchValue) ? count($formattedData) : $totalBans ,
            "data" => $formattedData
        ];

        return response()->json($response);
    }

    public function unban(Request $request, $playerSteamid)
    {
        $bans = SaBan::where('player_steamid', $playerSteamid)->get();

        try {
            // Start a database transaction
            DB::beginTransaction();

            foreach ($bans as $ban) {
                // Update each ban record to mark it as unbanned
                $ban->status = 'UNBANNED';
                $ban->ends = now();
                $ban->save();
            }

            // If all unbans are successful, commit the transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => 'All players unbanned successfully']);
        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function create()
    {
        $servers = SaServer::all();
        return view('admin.bans.add', ['servers' => $servers]);
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
                $existingBan = SaBan::where('player_steamid', $steamId)
                    ->where('server_id', $serverId)
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($existingBan) {
                    continue;
                }
                $carbonTimestamp = Carbon::parse($validatedData['duration']);
                $minutesDifference = $carbonTimestamp->diffInMinutes(Carbon::now());
                $saban = new SaBan();
                $saban->player_steamid = $validatedData['player_steam_id'];
                $saban->reason = $validatedData['reason'];
                $saban->player_name = $profileName;
                $saban->duration = $minutesDifference;
                $saban->server_id = $serverId;
                $saban->admin_name = auth()->user()->name;
                $saban->admin_steamid = auth()->user()->steam_id;
                $saban->ends = $validatedData['duration'];
                $saban->save();
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

        return redirect()->route('list.bans')->with('success', 'Ban added successfully');
    }
}
