<?php

namespace App\Http\Controllers;

use App\Models\SaAdmin;
use App\Models\SaServer;
use App\Services\RconService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ServerController extends Controller
{
    /**
     * @param RconService $rcon
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllServerInfo(RconService $rcon)
    {
        $servers = SaServer::all();
        $formattedServers = [];

        foreach ($servers as $server) {
            list($serverIp, $serverPort) = explode(":", $server->address);
            // Fetch server information using the RconService
            try {
                $rcon->connect($serverIp, $serverPort);
                $serverInfo = $rcon->getInfo();
                $formattedServer = [
                    'id' => $server->id,
                    'name' => $server->hostname,
                    'ip' => $serverIp,
                    'port' => $serverPort,
                    'players' => $serverInfo['Players'] . "/" . $serverInfo['MaxPlayers'],
                    'map' => $serverInfo['Map'],
                    'connect_button' => '<a class="btn btn-success" href="steam://connect/' . $serverIp . ':' . $serverPort . '">Connect</a>',
                ];
                $rcon->disconnect();
            } catch(\Exception) {
                $formattedServer = [
                    'id' => $server->id,
                    'name' => $server->hostname,
                    'ip' => $serverIp,
                    'port' => $serverPort,
                    'players' => '0',
                    'map' => '<h6><span class="badge badge-danger">Offline</span></h6>',
                    'connect_button' => '<h6><span class="badge badge-danger">Offline</span></h6>'
                ];
            }

            $formattedServers[] = $formattedServer;
        }

        return response()->json($formattedServers);
    }

    /**
     * @param Request $request
     * @param $serverId
     * @param RconService $rcon
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function getPlayers(Request $request, $serverId, RconService $rcon) {
        $players = [];
        $server = SaServer::where('id', $serverId)->first();
        list($serverIp, $serverPort) = explode(":", $server->address);
        try {
            $rcon->connect($serverIp, $serverPort);
            $players = $rcon->getPlayers();
            $rcon->disconnect();
        } catch(\Exception $e){
            Log::error('rcon.players.error'.$e->getMessage());
        }
        return view('admin.servers.players', compact('players'));
    }


    public function setup(Request $request)
    {
        $request->validate([
            'APP_URL' => 'required|url',
            'APP_NAME' => 'required|string',
            'DB_HOST' => 'required|string',
            'DB_DATABASE' => 'required|string',
            'DB_USERNAME' => 'required|string',
            'DB_PASSWORD' => 'required|string',
            'STEAM_CLIENT_SECRET' => 'required|string',
            'STEAM_ID_64' => 'required|string|digits:17',
        ]);

        try {
            config([
                'database.connections.mysql.host' => $request->DB_HOST,
                'database.connections.mysql.database' => $request->DB_DATABASE,
                'database.connections.mysql.username' => $request->DB_USERNAME,
                'database.connections.mysql.password' => $request->DB_PASSWORD,
            ]);

            DB::connection()->getPdo();

            $sqlFilePath = storage_path('app/cssbans.sql');
            $sql = file_get_contents($sqlFilePath);
            DB::unprepared($sql);
            $envContent = File::get(base_path('.env'));
            foreach ($request->all() as $key => $value) {
                if($key !== 'STEAM_ID_64')
                $envContent .= "$key=$value\n";
            }
            $envContent .= "\nSETUP=true";
            File::put(base_path('.env'), $envContent);
            foreach(SaServer::all() as $server) {
                $admin = new SaAdmin();
                $admin->player_steamid = $request->input('STEAM_ID_64');
                $admin->player_name = 'Admin';
                $admin->flags = '@css/root';
                $admin->immunity = 1;
                $admin->server_id = $server->id;
                $admin->ends = Carbon::now()->addYears(5)->format(('Y-m-d'));
                $admin->created = now();
                $admin->save();
            }
            return redirect('/')->with('success', 'Environment variables updated successfully. Database connection established. Tables imported.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Setup failed: ' . $e->getMessage()]);
        }
    }
}
