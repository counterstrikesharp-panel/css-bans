<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\ModuleHelper;
use App\Helpers\PermissionsHelper;
use App\Models\K4Ranks\ZenithPlayerStorage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToSteam()
    {
        return Socialite::driver('steam')->redirect();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleSteamCallback()
    {
        try {
            $steamUser = Socialite::driver('steam')->user();

            if ($steamUser) {
                $user = User::where('steam_id', $steamUser->getId())->first();
                if (empty($user)) {
                    // Steam ID doesn't exist, create a new user record
                    $user = new User();
                    $user->name = $steamUser->getNickname();
                    $user->steam_id = $steamUser->getId();
                    $user->avatar = $steamUser->getAvatar();
                    $user->save();
                } else {
                    // update latest details
                    $user->name = $steamUser->getNickname();
                    $user->avatar = $steamUser->getAvatar();
                    $user->save();
                }
                Auth::login($user);
                try {
                    if(env('RANKS') == 'Enabled') {
                        ModuleHelper::useConnection('Ranks');
                        $player = ZenithPlayerStorage::where('steam_id', $steamUser->getId())->first();
                        if ($player) {
                            $playerRank = $player['K4-Zenith-Ranks.storage'];
                            $points = $playerRank['Points'] ?? 0;
                            $rank = $playerRank['Rank'] ?? 'N/A';

                            // Fetch rank and rating images using the same logic
                            $rankImage = CommonHelper::getCSRankImage($rank);
                            $ratingImage = CommonHelper::getCSRatingImage($points);

                            // Store the rank and rank image in the session
                            session([
                                'rank' => $rank,
                                'rank_image' => $rankImage,
                                'rating_image' => $ratingImage,
                            ]);
                        }
                    }
                } catch(\Exception $e) {
                    Log::error('auth.rank.cache'. $e->getMessage());
                }
                return redirect()->route('home')->with('success', __('admins.steamAuthSuccess'));;
            } else {
                return redirect()->route('home')->with('error', __('admins.steamAuthError'));
            }
        } catch (\Exception $e) {
            Log::error('auth.error'. $e->getMessage());
            return redirect()->route('home')->with('error', __('admins.errorSteam'));
        }
    }
}
