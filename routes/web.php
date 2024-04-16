<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BansController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MutesController;
use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['checkSetup'])->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/mutes', [DashboardController::class, 'getMutes']);
    Route::get('/bans', [DashboardController::class, 'getBans']);
    Route::get('/servers', [ServerController::class, 'getAllServerInfo']);
    Route::get('/auth/steam', [LoginController::class, 'redirectToSteam']);
    Route::get('/auth/steam/callback', [LoginController::class, 'handleSteamCallback']);
    Route::get('/auth/logout', function() {
        Auth::logout();
        return redirect()->route('home');
    });
    Route::prefix('list')->group(function () {
        Route::get('bans', [BansController::class, 'bans'])->name('list.bans');
        Route::post('bans', [BansController::class, 'getBansList']);
        Route::get('mutes', [MutesController::class, 'mutes'])->name('list.mutes');;
        Route::post('mutes', [MutesController::class, 'getMutesList']);
        Route::get('admins', [AdminController::class, 'admins'])->name('admins.list')->middleware('superadmin');
        Route::post('admins', [AdminController::class, 'getAdminsList'])->middleware('superadmin');;
    });

    Route::prefix('admin')->group(function () {
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create')->middleware('superadmin');
        Route::post('/store', [AdminController::class, 'store'])->name('admin.store')->middleware('superadmin');
        Route::get('/edit/{player_steam}/{server_id}', [AdminController::class, 'edit'])->name('admin.edit')->middleware('superadmin');
        Route::post('/update/{player_steam}', [AdminController::class, 'update'])->name('admin.update')->middleware('superadmin');
        Route::get('/delete/{player_steam}', [AdminController::class, 'showDeleteForm'])->name('admin.showDeleteForm')->middleware('superadmin');
        Route::post('/delete/{player_steam}', [AdminController::class, 'delete'])->name('admin.delete')->middleware('superadmin');
    });

    Route::prefix('players')->group(function () {
        Route::post('ban', [BansController::class, 'store'])->name('ban.store')->middleware('permission.ban');
        Route::post('mute', [MutesController::class, 'store'])->name('mute.store')->middleware('permission.ban');
        Route::put('{player_steam_id}/unban', [BansController::class, 'unban'])->middleware('permission.unban');
        Route::put('{player_steam_id}/unmute', [MutesController::class, 'unmute'])->middleware('permission.unmute');
        Route::post('action', [ServerController::class, 'serverPlayerAction'])->name('player.action');
    });

    Route::get('/ban/add', [BansController::class, 'create'])->middleware('permission.ban');
    Route::get('/mute/add', [MutesController::class, 'create'])->middleware('permission.mute');

    Route::group(['prefix' => 'servers'], function () {
        Route::get('/{server_id}/players', [ServerController::class, 'getPlayers']);
    });
});
/**
 * Setup
 */
Route::get('/requirement', function () {
    if (env('SETUP') === 'true') {
        return redirect('/');
    }
    return view('requirement');
})->name('requirement');
Route::get('/setup', function () {
    if (env('SETUP') === 'true') {
        return redirect('/');
    }
    return view('setup');
});

Route::post('/setup', [ServerController::class, 'setup']);
