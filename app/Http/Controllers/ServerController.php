<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionsHelper;
use App\Models\SaAdmin;
use App\Models\SaAdminsFlags;
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

            if (!$this->isPortOpen($serverIp, $serverPort)) {
                Log::error('rcon.servers.list Port Blocked! Unable to read data from port!');
                $formattedServer = [
                    'id' => $server->id,
                    'name' => $server->hostname,
                    'ip' => $serverIp,
                    'port' => $serverPort,
                    'players' => '0',
                    'map' => '<h6><span class="badge badge-danger">Unable To Connect</span></h6>',
                    'connect_button' => '<h6><span class="badge badge-danger">Unable To Connect</span></h6>'
                ];
                $formattedServers[] = $formattedServer;
                continue;
            }

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
            } catch (\Exception $e) {
                Log::error('rcon.servers.list.error'. $e->getMessage());
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

    private function isPortOpen($ip, $port, $timeout = 1) {
        $fp = @fsockopen($ip, $port, $errno, $errstr, $timeout);
        if (!in_array($errno, [SOCKET_ETIMEDOUT,SOCKET_EHOSTUNREACH,SOCKET_ENETUNREACH]) && stripos(strtolower($errstr), 'failed') === false) {
            $fp ? fclose($fp) : '';
            return true;
        } else {
            return false;
        }
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
        return view('admin.servers.players', compact('players', 'server'));
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
            'RCON_PASSWORD'=> 'required|string'
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
                if($key !== 'STEAM_ID_64' && $key !== 'APP_NAME')
                    $envContent .= "$key=$value\n";
                if($key == 'APP_NAME')
                    $envContent .= "$key='$value'\n";
            }
            $envContent .= "\nSETUP=true";
            $envContent .= "\nASSET_URL=".$request->input('APP_URL');
            File::put(base_path('.env'), $envContent);
            foreach(SaServer::all() as $server) {
                $admin = new SaAdmin();
                $admin->player_steamid = $request->input('STEAM_ID_64');
                $admin->player_name = 'Admin';
                $admin->immunity = 1;
                $admin->server_id = $server->id;
                $admin->ends = Carbon::now()->addYears(5)->format(('Y-m-d'));
                $admin->created = now();
                $admin->save();

                $adminFlag = new SaAdminsFlags();
                $adminFlag->admin_id= $admin->id;
                $adminFlag->flag = '@css/root';
                $adminFlag->save();
            }
            return redirect()->route('home')->with('success', 'Environment variables updated successfully. Database connection established. Tables imported.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Setup failed: ' . $e->getMessage()]);
        }
    }

    public function serverPlayerAction(Request $request) {
        $requestType = $request->input('action');
        $playerName = $request->input('name');
        $serverId = $request->input('serverId');
        $duration = '1440'; // 1 day
        switch ($requestType){
            case "ban":
                if(PermissionsHelper::hasUnBanPermission())
                    return $this-> executeCommand('css_ban '.$playerName.' 1440', $serverId);
                break;
            case "kick":
                if(PermissionsHelper::hasKickPermission())
                    return $this->executeCommand('css_kick '.$playerName.' 1440', $serverId);
                break;
            case "mute":
                if(PermissionsHelper::hasMutePermission())
                    return $this->executeCommand('css_mute ' . $playerName.' 1440', $serverId);
                break;
            default: abort(403);
        }
    }

    private function executeCommand(string $command, string $serverId)
    {
        $server = SaServer::where('id', $serverId)->first();
        list($serverIp, $serverPort) = explode(":", $server->address);

        try {
            $rcon = app(RconService::class);
            $rcon->connect($serverIp, $serverPort);
            $rcon->setRconPassword(env('RCON_PASSWORD'));
            $output = $rcon->rcon($command);
            $rcon->disconnect();
            $pattern = "/Target [a-zA-Z]+ not found\./";
            if (preg_match($pattern, $output)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Player not found.'
                ],500);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => "Command Executed Successfully."
                ]);
            }
        } catch(\Exception $e){
            Log::error('rcon.players.error ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while executing the command.'
            ], 500);
        }
    }
}
