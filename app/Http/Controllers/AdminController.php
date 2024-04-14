<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionsHelper;
use App\Http\Requests\StoreAdminRequest;
use App\Models\Permission;
use App\Models\SaAdmin;
use App\Models\SaServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function admins()
    {
        return view('admin.admins.list');
    }

    public function create()
    {
        $permissions = Permission::all();
        $servers = SaServer::all();
        return view('admin.admins.create', compact('permissions', 'servers'));
    }

    public function store(StoreAdminRequest $request)
    {
        $validatedData = $request->validated();
        try {
            foreach ($validatedData['server_ids'] as $server_id) {
                foreach ($validatedData['permissions'] as $permissionId) {
                    $permission = Permission::find($permissionId);
                    $admin = new SaAdmin();
                    $admin->player_steamid = $validatedData['steam_id'];
                    $admin->player_name = $validatedData['player_name'];
                    $admin->flags = $permission->permission;
                    $admin->immunity = 1;
                    $admin->server_id = $server_id;
                    $admin->ends = $validatedData['ends'];
                    $admin->created = now();
                    $admin->save();
                }
            }
            return redirect()->route('admins.list')->with('success', 'Admin created successfully.');
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['msg' => 'There was an error saving the admin: ' . $e->getMessage()]);
        }
    }

    public function getAdminsList(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumnName = $request->input("columns.$orderColumnIndex.data", 'steamid');

        $query = SaAdmin::query();

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('player_name', 'like', "%{$searchValue}%")
                    ->orWhere('player_steamid', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = $query->distinct()->count('player_steamid');
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $admins = $query->select(
            'player_steamid',
            'player_name',
            'sa_admins.id',
            DB::raw('GROUP_CONCAT(flags SEPARATOR ", ") as flags'),
            DB::raw('GROUP_CONCAT(DISTINCT CONCAT(sa_servers.id, ") ", sa_servers.hostname) SEPARATOR ", ") as hostnames'),
            'created',
            'ends',
            'server_id'
        )
            ->join('sa_servers', 'sa_admins.server_id', '=', 'sa_servers.id')
            ->groupBy('player_steamid')
            ->orderBy($orderColumnName, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();
        // Format each ban record
        $formattedData = [];
        foreach ($admins as $admin) {
            $formattedData[] = [
                "id" => $admin->id,
                "player_steamid" => $admin->player_steamid,
                "player_name" => $admin->player_name,
                "ends" => $admin->ends,
                "created" => $admin->created,
                "flags" => $admin->flags,
                "hostnames" => $admin->hostnames,
                'actions' => PermissionsHelper::isSuperAdmin() ? "<a href='/admin/edit/{$admin->player_steamid}/{$admin->server_id}' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a><a href='/admin/delete/$admin->player_steamid' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a>" : "",
            ];
        }
        $response = [
            'draw' => intval($request->input('draw', 0)),
            'recordsTotal' => $recordsTotal,
            "recordsFiltered" => !empty($searchValue) ? count($formattedData) : $recordsTotal ,
            'data' => $formattedData
        ];

        return response()->json($response);
    }

    public function edit($player_steam, $server_id)
    {
        $admin = SaAdmin::with('permissions')
            ->where('player_steamid', $player_steam)
            ->where('server_id', $server_id)
            ->get();
        $permissions = Permission::all();
        $servers = SaServer::all();
        $adminPermissions = $admin->pluck('permissions.permission')->toArray();
        return view('admin.admins.edit', compact('admin', 'permissions', 'adminPermissions', 'servers'));
    }

    public function update(Request $request, $player_steam)
    {
        // Validate the submitted permissions
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,permission',
            'ends' => 'required|date|after:today',
            'server_id' => 'exists:sa_servers,id',
        ]);

        $admin = SaAdmin::with('permissions')
            ->where('player_steamid',$player_steam)
            ->where('server_id',  $validated['server_id'])
            ->get();
        $submittedPermissions = $validated['permissions'];

        // Fetch current permissions from the database
        $currentPermissions = $admin->pluck('permissions.permission')->toArray();

        // Determine permissions to add and delete
        $permissionsToAdd = array_diff($submittedPermissions, $currentPermissions);
        $permissionsToDelete = array_diff($currentPermissions, $submittedPermissions);

        // Handle permissions to add
        foreach ($permissionsToAdd as $permissionName) {
            $saAdmin = new SaAdmin();
            $saAdmin->player_steamid = $admin->first()->player_steamid;
            $saAdmin->player_name = $admin->first()->player_name;
            $saAdmin->flags = $permissionName;
            $saAdmin->immunity = 1;
            $saAdmin->server_id = $admin->first()->server_id;
            $saAdmin->ends = $admin->first()->ends;
            $saAdmin->created = now();
            $saAdmin->save();
        }

        // Handle permissions to delete
        SaAdmin::whereIn('flags', $permissionsToDelete)
            ->where('player_steamid', $player_steam)
            ->where('server_id', $validated['server_id'])
            ->delete();

        // update new expiry
        SaAdmin::where('player_steamid', $player_steam)
            ->where('server_id', $validated['server_id'])
            ->update([
              'ends' => $validated['ends']
            ]);
        return redirect()->route('admins.list')->with('success', 'Admin updated successfully.');
    }

    public function showDeleteForm($player_steam)
    {
        $admin = SaAdmin::where('player_steamid', $player_steam)->firstOrFail();
        $servers = SaServer::all();
        return view('admin.admins.delete', compact('admin', 'servers'));
    }

    public function delete(Request $request, $player_steam)
    {
        $validated = $request->validate([
            'server_ids' => 'required|array',
            'server_ids.*' => 'exists:sa_servers,id',
        ]);
        $serverIds = $validated['server_ids'];
        SaAdmin::where('player_steamid', $player_steam)
            ->whereIn('server_id', $serverIds)
            ->where('flags', '<>', '@css/root')
            ->delete();

        return redirect()->route('admins.list')->with('success', 'Admin deleted successfully.');
    }
}
