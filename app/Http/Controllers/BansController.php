<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\PermissionsHelper;
use App\Models\SaBan;
use App\Models\SaServer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use function Laravel\Prompts\error;

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
        $query = SaBan::query()->whereNotNull('server_id');

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
        $totalBans = SaBan::whereNotNull('server_id')->count();

        $formattedData = [];
        $siteDir = env('VITE_SITE_DIR');
        // Format each ban record
        foreach ($bans as $ban) {
            $editAction = '';
            $unbanAction = '';
            if(!empty($ban->server)) {
                if (PermissionsHelper::hasBanPermission($ban->server_id) || PermissionsHelper::hasWebBanEditPermissions($ban->server_id)) {
                    $editAction = "<a href='$siteDir/ban/edit/{$ban->id}' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>";
                }
                if (empty($ban->player_steamid))
                    $ban->player_steamid = $ban->id;
                if (PermissionsHelper::hasUnBanPermission($ban->server_id) && $ban->status == 'ACTIVE') {
                    $unbanAction = "<button class='btn btn-success btn-sm unban-btn' data-player-steamid='{$ban->player_steamid}'><i class='fas fa-ban'></i></button>";
                }
            }
            $response = CommonHelper::steamProfile($ban);
            $formattedData[] = [
                "id" => $ban->id,
                "player_steamid" => $ban->player_steamid,
                "player_name" => $ban->player_name,
                "player_ip" => (PermissionsHelper::isSuperAdmin()) ? $ban->player_ip : '*****',
                'avatar' => !empty($response['response']['players'][0]['avatar']) ? $response['response']['players'][0]['avatar'] : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg' ,
                "admin_steamid" => $ban->admin_steamid,
                "admin_name" => $ban->admin_name,
                "reason" => $ban->reason,
                "duration" => $ban->duration == 0 && $ban->status != 'UNBANNED' ? "<h6><span class='badge badge-danger'>" . __('dashboard.permanent') . "</span></h6>" : CommonHelper::minutesToTime($ban->duration),
                "ends" => $ban->ends,
                "created" => $ban->created,
                "server_id" => $ban->server?->hostname,
                'action' => $unbanAction." ".$editAction,
                "status" => $ban->status == 'ACTIVE' ? "<h6><span class='badge badge-success'>" . __('dashboard.active') . "</span></h6>" : ($ban->status == 'UNBANNED' ? "<h6><span class='badge badge-primary'>" . __('dashboard.unbanned') . "</span></h6>" : "<h6><span class='badge badge-danger'>" . __('dashboard.expired') . "</span></h6>"),
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

    public function unban(Request $request, $dataId)
    {
        $bans = SaBan::where('player_steamid', $dataId)
            ->orWhere('id', $dataId)
            ->get();

        try {
            // Start a database transaction
            DB::beginTransaction();

            foreach ($bans as $ban) {
                // Update each ban record to mark it as unbanned
                $ban->status = 'UNBANNED';
                $ban->ends = now();
                $ban->save();
                CommonHelper::sendActionLog('unban', $ban->id);
            }

            // If all unbans are successful, commit the transaction
            DB::commit();
            return response()->json(['success' => true, 'message' => __('admins.bansSuccess')]);
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
            'player_steam_id' => 'required_without:player_ip|nullable|numeric|digits:17',
            'player_ip' => 'required_without:player_steam_id|nullable|ip',
            'reason' => 'required',
            'duration' => 'required_without:permanent',
            'server_ids' => 'required|array',
            'player_name' => 'required_without:player_steam_id|nullable|string',
        ]);

        try {
            $steamId = 0;
            if(!empty($validatedData['player_steam_id'])) {
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
            } else {
                $profileName = $validatedData['player_name'];
            }
            DB::beginTransaction();
            $bansAdded = false;
            $playerIp = 0;
            if(!empty($validatedData['player_ip'])){
                $playerIp = $validatedData['player_ip'];
            }
            if(in_array('all', $validatedData['server_ids'])) {
                $validatedData['server_ids'] = SaServer::all()->pluck('id')->toArray();
            }
            foreach ($validatedData['server_ids'] as $serverId) {
                $existingBan = SaBan::where(function ($query) use ($steamId, $playerIp, $serverId) {
                    $query->where('player_steamid', $steamId)
                        ->when(!empty($playerIp), function ($query) use ($playerIp) {
                            return $query->orWhere('player_ip', $playerIp);
                        });
                    })
                    ->where('server_id', $serverId)
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($existingBan) {
                    continue;
                }
                $minutesDifference = 0;
                if(isset($validatedData['duration'])) {
                    $carbonTimestamp = Carbon::parse($validatedData['duration']);
                    $minutesDifference = $carbonTimestamp->diffInMinutes(Carbon::now());
                }
                $saban = new SaBan();
                $saban->player_steamid = $validatedData['player_steam_id'];
                $saban->reason = $validatedData['reason'];
                $saban->player_name = $profileName;
                $saban->duration = $minutesDifference;
                $saban->server_id = $serverId;
                $saban->player_ip = $playerIp;
                $saban->admin_name = auth()->user()->name;
                $saban->admin_steamid = auth()->user()->steam_id;
                $saban->ends = !empty($minutesDifference) ? CommonHelper::formatDate($validatedData['duration']): Carbon::now();
                $saban->save();
                $bansAdded = true;
                CommonHelper::sendActionLog('ban', $saban->id);
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            Log::error('ban.error: ' . $e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'There was an error while adding the ban.']);
        }
        if (!$bansAdded) {
            return Redirect::back()->withErrors(['msg' => __('admins.banexists')]);
        }

        return redirect()->route('list.bans')->with('success', __('admins.bandAddedSuccess'));
    }

    public function edit($id)
    {
        $ban = SaBan::findOrFail($id);
        $servers = SaServer::all();
        return view('admin.bans.edit', ['ban' => $ban, 'servers' => $servers]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'player_steam_id' => 'required_without:player_ip|nullable|numeric|digits:17',
            'player_ip' => 'required_without:player_steam_id|nullable|ip',
            'reason' => 'required',
            'duration' => 'required_without:permanent',
            'player_name' => 'required_without:player_steam_id|nullable|string',
        ]);

        try {
            $ban = SaBan::findOrFail($id);
            $ban->player_steamid = $validatedData['player_steam_id'];
            $ban->player_ip = $validatedData['player_ip'];
            $ban->player_name = $validatedData['player_name'];
            $ban->reason = $validatedData['reason'];
            $minutesDifference = 0;
            $ban->duration = $minutesDifference;
            if(!$request->has('permanent')) {
                $carbonTimestamp = Carbon::parse($validatedData['duration']);
                $minutesDifference = $carbonTimestamp->diffInMinutes(Carbon::now());
                $ban->duration = $minutesDifference;
                $ban->ends = CommonHelper::formatDate(Carbon::parse($validatedData['duration']));
            }
            $ban->status = 'ACTIVE';
            $ban->save();
            CommonHelper::sendActionLog('ban', $ban->id);
            return redirect()->route('list.bans')->with('success', __('admins.banUpdateSuccess'));
        } catch(\Exception $e) {
            Log::error('ban.update.error: ' . $e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'There was an error while updating the ban.']);
        }
    }
}
