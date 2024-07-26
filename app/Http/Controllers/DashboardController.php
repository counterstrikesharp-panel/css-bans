<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\ModuleHelper;
use App\Helpers\PermissionsHelper;
use App\Models\K4Ranks\Ranks;
use App\Models\SaAdmin;
use App\Models\SaBan;
use App\Models\SaMute;
use App\Models\SaServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function check() {
        return view('requirement');
    }

    public function home()
    {
        $updates = [];
        $activeBans = null;
        $activeMutes = null;
        $totalBans = SaBan::count();
        $totalServers = SaServer::count();
        $totalMutes = SaMute::count();
        $totalAdmins = SaAdmin::distinct('player_steamid')->count();
        $totalActiveBans =  SaBan::where('status', 'ACTIVE')->count();
        $totalActiveMutes =  SaMute::where('status', 'ACTIVE')->count();
        if(auth()->check()){
            $activeBans = SaBan::where('player_steamid', Auth::user()->steam_id)->where('status', 'ACTIVE')->count();
            $activeMutes = SaMute::where('player_steamid', Auth::user()->steam_id)->where('status', 'ACTIVE')->count();
        }
        if(PermissionsHelper::isSuperAdmin()) {
            $updates = $this->checkUpdates();
        }
        $topPlayersData = [];
        if(env('RANKS') == 'Enabled') {
            $topPlayersData = $this->getTop5Players();
        }
        return view('admin.dashboard',
            compact(
                'totalBans',
                'totalServers',
                'totalMutes',
                'totalAdmins',
                'updates',
                'topPlayersData',
                'activeBans',
                'activeMutes',
                'totalActiveBans',
                'totalActiveMutes'
            )
        );
    }

    public function getMutes()
    {
        $recentMutes = SaMute::orderBy('created', 'desc')->take(5)->get();
        foreach($recentMutes as $mute){
            $mute->ends = $mute->duration == 0 ? "<h6><span class='badge badge-danger'>" . __('dashboard.permanent') . "</span></h6>" : $mute->ends;
        }
        return response()->json($recentMutes);
    }

    public function getBans()
    {
        $recentBans = SaBan::orderBy('created', 'desc')->take(5)->get();
        foreach($recentBans as $ban){
            $ban->ends = $ban->duration == 0 ? "<h6><span class='badge badge-danger'>" . __('dashboard.permanent') . "</span></h6>" : $ban->ends;
        }
        return response()->json($recentBans);
    }

    /**
     * @return array
     */
    private function checkUpdates()
    {
        $appVersion = config('app.version');
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

    public function getTop5Players()
    {
        // 'points' in the Ranks table represents CS Rating
        ModuleHelper::useConnection('Ranks');
        $topPlayers = Ranks::with('k4stats')
            ->orderBy('points', 'desc')
            ->take(5)
            ->get();

        foreach($topPlayers as $player) {
            $player->player_steamid = $player->steam_id;
            $response = CommonHelper::steamProfile($player);
            $player->avatar = !empty($response['response']['players'][0]['avatar']) ? $response['response']['players'][0]['avatar'] : 'https://mdbootstrap.com/img/Photos/Avatars/img(32).jpg' ;
            $player->ratingImage = CommonHelper::getCSRatingImage($player->points);
            $player->rank = CommonHelper::getCSRankImage($player->rank);
        }
        $totalPlayers = Ranks::count();
        return ['topPlayers' => $topPlayers, 'totalPlayers' => $totalPlayers];
    }
}
