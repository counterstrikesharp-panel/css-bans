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
        $siteDir = env('VITE_SITE_DIR');
        // Format each mute record
        foreach ($mutes as $mute) {
            $editAction = '';
            $unmuteAction = '';
            if(!empty($mute->server)) {
                if (PermissionsHelper::hasMutePermission($mute->server_id) || PermissionsHelper::hasWebMuteEditPermissions($mute->server_id)) {
                    $editAction = "<a href='$siteDir/mute/edit/{$mute->id}' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>";
                }
                if (empty($mute->player_steamid))
                    $mute->player_steamid = $mute->id;
                if (PermissionsHelper::hasUnMutePermission($mute->server_id) && $mute->status == 'ACTIVE') {
                    $unmuteAction = "<button class='btn btn-success btn-sm unmute-btn' data-player-steamid='{$mute->player_steamid}'><i class='fas fa-ban'></i></button>";
                }
            }
            $response = CommonHelper::steamProfile($mute);
            $formattedData[] = [
                "id" => $mute->id,
                "player_steamid" => $mute->player_steamid,
                "player_name" => $mute->player_name,
                'avatar' => !empty($response['response']['players'][0]['avatar']) ? $response['response']['players'][0]['avatar'] : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg' ,
                "admin_steamid" => $mute->admin_steamid,
                "admin_name" => $mute->admin_name,
                "reason" => $mute->reason,
                "ends" => $mute->ends,
                "created" => $mute->created,
                "server_id" => $mute->server?->hostname,
                "status" => $mute->status == 'ACTIVE' ? "<h6><span class='badge badge-success'>" . __('dashboard.active') . "</span></h6>" : ($mute->status == 'UNMUTED' ? "<h6><span class='badge badge-primary'>" . __('dashboard.unmuted') . "</span></h6>" : "<h6><span class='badge badge-danger'>" . __('dashboard.expired') . "</span></h6>"),
                'action' =>  $unmuteAction." ".$editAction,
                "duration" => $mute->duration == 0 && $mute->status != 'UNMUTED' ? "<h6><span class='badge badge-danger'>" . __('dashboard.permanent') . "</span></h6>" : CommonHelper::minutesToTime($mute->duration),
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

    public function unmute(Request $request, $dataId)
    {
        $mutes = SaMute::where('player_steamid', $dataId)
            ->orWhere('id', $dataId)
            ->get();

        try {
            // Start a database transaction
            DB::beginTransaction();

            foreach ($mutes as $mute) {
                // Update each mute record to mark it as unmuted
                $mute->status = 'UNMUTED';
                $mute->ends = now();
                $mute->save();
            }

            // If all unmutes are successful, commit the transaction
            DB::commit();

            return response()->json(['success' => true, 'message' => __('admins.unmuteSuccess')]);
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
            'duration' => 'required_without:permanent',
            'server_ids' => 'required|array',
            'type' => 'required'
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
            $mutesAdded = false;
            if(in_array('all', $validatedData['server_ids'])) {
                $validatedData['server_ids'] = SaServer::all()->pluck('id')->toArray();
            }
            foreach ($validatedData['server_ids'] as $serverId) {
                $existingMute = SaMute::where('player_steamid', $steamId)
                    ->where('server_id', $serverId)
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($existingMute) {
                    continue;
                }
                $minutesDifference = 0;
                if(isset($validatedData['duration'])) {
                    $carbonTimestamp = Carbon::parse($validatedData['duration']);
                    $minutesDifference = $carbonTimestamp->diffInMinutes(Carbon::now());
                }
                $samute = new SaMute();
                $samute->player_steamid = $validatedData['player_steam_id'];
                $samute->reason = $validatedData['reason'];
                $samute->player_name = $profileName;
                $samute->duration = $minutesDifference;
                $samute->server_id = $serverId;
                $samute->admin_name = auth()->user()->name;
                $samute->admin_steamid = auth()->user()->steam_id;
                $samute->type = $validatedData['type'];
                $samute->ends = !empty($minutesDifference) ? CommonHelper::formatDate($validatedData['duration']): Carbon::now();
                $samute->save();
                $mutesAdded = true;
                CommonHelper::sendActionLog('mute', $samute->id);
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            Log::error('mute.error: ' . $e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'There was an error while adding the mute.']);
        }
        if (!$mutesAdded) {
            return Redirect::back()->withErrors(['msg' => 'Mutes already exist for all specified servers.']);
        }

        return redirect()->route('list.mutes')->with('success', __('admins.muteAdded'));
    }

    public function edit($id)
    {
        $mute = SaMute::findOrFail($id);
        $servers = SaServer::all();
        return view('admin.mutes.edit', ['mute' => $mute, 'servers' => $servers]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'player_steam_id' => 'required|numeric|digits:17',
            'reason' => 'required',
            'duration' => 'required_without:permanent',
            'type' => 'required'
        ]);

        try {
            $mute = SaMute::findOrFail($id);
            $mute->player_steamid = $validatedData['player_steam_id'];
            $mute->reason = $validatedData['reason'];
            $minutesDifference = 0;
            $mute->duration = $minutesDifference;
            if(!$request->has('permanent')) {
                $carbonTimestamp = Carbon::parse($validatedData['duration']);
                $minutesDifference = $carbonTimestamp->diffInMinutes(Carbon::now());
                $mute->duration = $minutesDifference;
                $mute->ends = CommonHelper::formatDate(Carbon::parse($validatedData['duration']));
            }
            $mute->status = 'ACTIVE';
            $mute->type = $validatedData['type'];
            $mute->save();
            CommonHelper::sendActionLog('unmute', $mute->id);
            return redirect()->route('list.mutes')->with('success', __('admins.muteUpdate'));
        } catch(\Exception $e) {
            Log::error('mute.update.error: ' . $e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'There was an error while updating the mute.']);
        }
    }
}
