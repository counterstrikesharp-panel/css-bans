<?php

namespace App\Http\Controllers\VIP;

use App\Helpers\CommonHelper;
use App\Helpers\PermissionsHelper;
use App\Http\Controllers\Controller;
use App\Models\VIP\VIP;
use App\Models\VIP\VIPServer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VIPController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Extract parameters sent by DataTables
            $start = $request->input('start');
            $length = $request->input('length');
            $searchValue = $request->input('search.value');
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Start building the query
            $query = VIP::query();

            // Apply search filter
            if (!empty($searchValue)) {
                $query->where(function ($query) use ($searchValue) {
                    if(is_numeric($searchValue)){
                        $searchValue = $this->convertSteamID64ToAccountId(trim($searchValue));
                    }
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('account_id', 'like', '%' . $searchValue . '%');
                });
            }

            // Apply sorting
            if ($orderColumn !== null) {
                $query->orderBy($request->input('columns.' . $orderColumn . '.data'), $orderDirection);
            }

            // Paginate the results
            $vips = $query->offset($start)->limit($length)->get();
            // Get total count for pagination
            $totalVips = VIP::count();

            $formattedData = [];
            $siteDir = env('VITE_SITE_DIR');
            // Format each VIP record
            foreach ($vips as $vip) {
                $editAction = '';
                $deleteAction = '';
                $response = [];
                if ($vip->account_id) {
                    $steamProfileId = bcadd($vip->account_id, '76561197960265728'); // 0xFFFFFFFF is 4294967295 in decimal
                    if ($steamProfileId) {
                        $vip->player_steamid = $steamProfileId;
                        $response = CommonHelper::steamProfile($vip);
                        $steamProfileLink = "https://steamcommunity.com/profiles/{$steamProfileId}";
                    }
                }
                // Create edit action link
                if (PermissionsHelper::isSuperAdmin()) {
                    $editAction = '<a href="' . $siteDir . '/vip/' . $vip->account_id . '/edit" class="btn btn-warning">' . __('Edit') . '</a>';
                }

                // Create delete action link
                if (PermissionsHelper::isSuperAdmin()) {
                    $deleteAction = '<form action="' . $siteDir . '/vip/' . $vip->account_id . '" method="POST" style="display:inline;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                                        <button type="submit" class="btn btn-danger">' . __('Delete') . '</button>
                                    </form>';
                }
                if(!empty($response))
                    $profileName = isset($response['response']['players'][0]['personaname']) ? $response['response']['players'][0]['personaname'] : $vip->name;
                else
                    $profileName = $vip->name;
                $serverName = $vip->server->serverIp . ':' . $vip->server->port;

                $formattedData[] = [
                    'id' => $vip->id,
                    'name' => $vip->name,
                    'player_nick' => $vip->name,
                    'sid' => $serverName,
                    'group' => $vip->group,
                    'expires' => empty($vip->expires) ? "<h6><span class='badge badge-primary'>" . __('Never Expires') . "</span></h6>" : Carbon::createFromTimestamp($vip->expires)->toDateTimeString(),
                    'action' => $editAction . ' ' . $deleteAction,
                    'steam_profile' => $steamProfileLink ? "<a href='$steamProfileLink' target='_blank'>$profileName</a>" : '',
                    'avatar' => !empty($response['response']['players'][0]['avatar']) ? $response['response']['players'][0]['avatar'] : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg' ,
                    "last_seen" => Carbon::parse($vip->lastvisit)->diffForHumans()
                ];
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalVips,
                "recordsFiltered" => !empty($searchValue) ? count($formattedData) : $totalVips ,
                'data' => $formattedData
            ]);
        } else {

            return view('admin.vip.list');
        }
    }

    public function create()
    {
        $servers = VIPServer::all();
        return view('admin.vip.create', compact('servers'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if(isset($data['permanent'])) {
            $data['expires'] = 0;
        } else {
            $data['expires'] = Carbon::parse($data['expires'])->timestamp;
        }
        $data['account_id'] = $this->convertSteamID64ToAccountId($data['account_id']);
        $data['lastvisit'] = Carbon::now()->timestamp;
        $vip = new VIP($data);
        $vip->save();
        return redirect()->route('vip.index')->with('success', __('admins.vipAddedSuccessfully'));;
    }
    private function convertSteamID64ToAccountId($steamID64)
    {
        // Convert SteamID64 to Account ID
        return bcsub($steamID64, '76561197960265728');
    }
    public function edit($id)
    {
        $vip = VIP::findOrFail($id);
        $vip->ends = Carbon::parse($vip->expires)->toDateString();
        $servers = VIPServer::all();
        return view('admin.vip.edit', compact('vip', 'servers'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if(isset($data['permanent'])) {
            $data['expires'] = 0;
        } else {
            $data['expires'] = Carbon::parse($data['expires'])->timestamp;
        }
        $vip = VIP::findOrFail($id);
        $vip->update($data);
        return redirect()->route('vip.index')->with('success', __('admins.vipAddedSuccessfully'));
    }

    public function destroy($id)
    {
        try {
            $vip = VIP::findOrFail($id);
            $vip->delete();
            return redirect()->route('vip.index')->with('success', __('admins.VipDelete'));
        } catch(\Exception $e){
            Log::error('vip.delete'. $e->getMessage());
            return redirect()->route('vip.index')->with('error', __('admins.VipDeleteError'));

        }
    }
}
