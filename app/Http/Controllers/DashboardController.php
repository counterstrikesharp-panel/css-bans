<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionsHelper;
use App\Models\SaAdmin;
use App\Models\SaBan;
use App\Models\SaMute;
use App\Models\SaServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function check() {
        return view('requirement');
    }

    public function home()
    {
        $updates = [];
        $totalBans = SaBan::count();
        $totalServers = SaServer::count();
        $totalMutes = SaMute::count();
        $totalAdmins = SaAdmin::distinct('player_steamid')->count();
        if(PermissionsHelper::isSuperAdmin()) {
            $updates = $this->checkUpdates();
        }
        return view('admin.dashboard', compact('totalBans', 'totalServers', 'totalMutes', 'totalAdmins', 'updates'));
    }

    public function getMutes()
    {
        $recentMutes = SaMute::orderBy('created', 'desc')->take(5)->get();
        return response()->json($recentMutes);
    }

    public function getBans()
    {
        $recentBans = SaBan::orderBy('created', 'desc')->take(5)->get();
        return response()->json($recentBans);
    }

    /**
     * @return array
     */
    private function checkUpdates()
    {
        $appVersion = env('APP_VERSION');
        $response = Http::get('https://css-bans-updates.matchclub.xyz/api/version');
        $updates = [];
        if ($response->successful()) {
            $data = $response->json();
            $latestVersion = $data['release_notes']['version'];
            $releaseNotes = $data['release_notes']['html'];

            if ($appVersion !== $latestVersion) {
                $updates = [
                    'version' => $latestVersion,
                    'notes' => $releaseNotes
                ];
            }
        }
        return $updates;
    }
}
