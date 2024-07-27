<?php

namespace App\Http\Controllers\Rcon;

use App\Http\Controllers\Controller;
use App\Models\Rcon\Rcon;
use App\Models\SaServer;
use App\Services\RconService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class RconController extends Controller
{
    public function index(Request $request, $serverId=null)
    {
        $servers = SaServer::all();
        if(empty($serverId)){
            $serverId = SaServer::first()?->id;
        }
        return view('admin.rcon.rcon', compact('servers','serverId'));
    }

    public function execute(Request $request, $serverId) {
        $validated = $request->validate([
            'command' => 'required',
            'password' => 'required',
        ]);
        try {
            $rcon = app(RconService::class);
            $server = SaServer::where('id', $serverId)->first();
            list($serverIp, $serverPort) = explode(":", $server->address);
            $rcon->connect($serverIp, $serverPort);
            $rcon->setRconPassword($validated['password']);
            $output = $rcon->rcon($validated['command']);
            $rcon->disconnect();
            $rconServer = new Rcon();
            if($server->rcon()->doesntExist()){
                $rconServer->server_id = $server->id;
                $rconServer->password = Crypt::encrypt($validated['password']);
                $rconServer->save();
            }
            return redirect()->route('rcon')->with(
                [
                    'success' => __('admins.rconStatusSuccess'),
                    'data' => $output,
                    'serverId' => $serverId
                ]
            );
        } catch(\Exception $e){
            Log::error('rcon.execute.error ' . $e->getMessage());
            return redirect()->route('rcon')->with(
                [
                    'error' => __('admins.rconStatusFailed'),
                    'data' => $e->getMessage(),
                    'serverId' => $serverId
                ]
            );
        }
    }

}
