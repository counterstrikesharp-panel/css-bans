<?php

use App\Http\Controllers\Appeal\AppealController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogViewerController;
use App\Http\Controllers\Rcon\RconController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\VIP\VIPController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BansController;
use App\Http\Controllers\K4Ranks\RanksController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MutesController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DemosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
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
        Route::get('admins', [AdminController::class, 'admins'])->name('admins.list')->middleware('admin');
        Route::post('admins', [AdminController::class, 'getAdminsList'])->middleware('admin');
        Route::get('/groups', [AdminController::class, 'groups'])->name('groups.list')->middleware('superadmin');
        Route::post('/groups', [AdminController::class, 'getGroupsList'])->middleware('superadmin');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create')->middleware('admin');
        Route::post('/store', [AdminController::class, 'store'])->name('admin.store')->middleware('admin');
        Route::get('/edit/{player_steam}/{server_id}', [AdminController::class, 'editAdmin'])->name('admin.edit')->middleware('admin');
        Route::post('/update/{player_steam}', [AdminController::class, 'updateAdmin'])->name('admin.update')->middleware('admin');
        Route::get('/delete/{player_steam}', [AdminController::class, 'showDeleteForm'])->name('admin.showDeleteForm')->middleware('admin');
        Route::post('/delete/{player_steam}', [AdminController::class, 'delete'])->name('admin.delete')->middleware('admin');
        Route::get('/groups/edit/{player_steam}/{server_id}', [AdminController::class, 'editAdminGroup'])->name('admin.group.edit')->middleware('superadmin');
        Route::post('/groups/update/{player_steam}', [AdminController::class, 'updateAdminGroup'])->name('admin.groups.update')->middleware('superadmin');

    });
    Route::prefix('group')->group(function () {
        Route::get('/create', [AdminController::class, 'createGroup'])->name('group.create')->middleware('superadmin');
        Route::post('/store', [AdminController::class, 'storeGroup'])->name('group.store')->middleware('superadmin');
        Route::get('/edit/{group_id}', [AdminController::class, 'editGroup'])->name('group.edit')->middleware('superadmin');
        Route::put('/update/{id}', [AdminController::class, 'updateGroup'])->name('group.update')->middleware('superadmin');
        Route::get('/delete/{id}', [AdminController::class, 'showGroupDeleteForm'])->name('group.deleteForm')->middleware('superadmin');
        Route::post('/delete/{id}', [AdminController::class, 'deleteGroup'])->name('group.delete')->middleware('superadmin');
    });
    Route::prefix('players')->group(function () {
        Route::post('ban', [BansController::class, 'store'])->name('ban.store')->middleware('permission.ban');
        Route::post('mute', [MutesController::class, 'store'])->name('mute.store')->middleware('permission.ban');
        Route::put('{player_steam_id}/unban', [BansController::class, 'unban'])->middleware('permission.unban');
        Route::put('{player_steam_id}/unmute', [MutesController::class, 'unmute'])->middleware('permission.unmute');
        Route::post('action', [ServerController::class, 'serverPlayerAction'])->name('player.action');
        Route::put('ban/{id}', [BansController::class, 'update'])->name('ban.update')->middleware('permission.ban');
        Route::put('mute/{id}', [MutesController::class, 'update'])->name('mute.update')->middleware('permission.mute');

    });

    Route::get('/ban/add', [BansController::class, 'create'])->middleware('permission.ban');
    Route::get('/ban/edit/{id}', [BansController::class, 'edit'])->middleware('permission.ban');
    Route::get('/mute/add', [MutesController::class, 'create'])->middleware('permission.mute');
    Route::get('/mute/edit/{id}', [MutesController::class, 'edit'])->middleware('permission.mute');

    Route::group(['prefix' => 'servers'], function () {
        Route::get('/{server_id}/players', [ServerController::class, 'getPlayers']);
    });

    Route::get('/demos', [DemosController::class, 'index'])->name('demo.index')->middleware('permission.ban');

    /**
     * Ranks Module
     */
    Route::group(['prefix' => 'list'], function () {
        Route::get('/ranks', [RanksController::class, 'index']);
        Route::post('/ranks', [RanksController::class, 'getPlayersList']);
        Route::get('/playtime', [RanksController::class, 'playtime']);
        Route::post('/playtime', [RanksController::class, 'getPlaytimeList']);
    });
    Route::get('/ranks/profile/{steam_id}/{server_id}', [RanksController::class, 'viewProfile'])->name('ranks.profile');
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
    if (env('SETUP') === true) {
        return redirect('/');
    }
    return view('setup');
});

Route::post('/setup', [ServerController::class, 'setup']);
Route::get('/logs', [LogViewerController::class, 'show'])->middleware('superadmin')->name('log-viewer');
Route::get('/rcon/{server_id?}', [RconController::class, 'index'])->middleware('superadmin')->name('rcon');
Route::post('/rcon/{server_id}', [RconController::class, 'execute'])->middleware('superadmin')->name('rcon.execute');

if(env('VIP') == 'Enabled') {
    Route::resource('vip', VIPController::class);
    Route::post('vip/list', 'VIPController@getVIPsList')->name('vip.list');
}

use App\Http\Controllers\WeaponSkinController;

Route::get('/weapons/skins', [WeaponSkinController::class, 'index'])->name('weapons.skins.index');
Route::post('/weapons/skins/apply', [WeaponSkinController::class, 'applySkin'])->name('weapons.skins.apply')->middleware('auth');
Route::get('/weapons/load/{type}', [WeaponSkinController::class, 'load'])->name('weapons.load')->middleware('auth');

Route::get('/weapons/loadGloves/{type}', [WeaponSkinController::class, 'loadGloves'])->middleware('auth');
Route::post('/weapons/agents/apply', [WeaponSkinController::class, 'applyAgent'])->name('weapons.agents.apply')->middleware('auth');
Route::post('/weapons/gloves/apply', [WeaponSkinController::class, 'applyGlove'])->name('weapons.gloves.apply')->middleware('auth');
Route::post('/weapons/music/apply', [WeaponSkinController::class, 'applyMusic'])->name('weapons.music.apply')->middleware('auth');
Route::post('/weapons/pins/apply', [WeaponSkinController::class, 'applyPin'])->name('weapons.pin.apply')->middleware('auth');

Route::get('/weapons/knives', [WeaponSkinController::class, 'knives'])->name('weapons.knives');
Route::post('/weapons/knives/apply', [WeaponSkinController::class, 'applyKnife'])->name('weapons.knives.apply');
Route::get('/weapons/loadKnives/{type}', [WeaponSkinController::class, 'loadKnives'])->name('weapons.loadKnives');

Route::get('/agents/skins', [WeaponSkinController::class, 'agents'])->name('agents')->middleware('auth');
Route::get('/gloves/skins', [WeaponSkinController::class, 'gloves'])->name('gloves')->middleware('auth');
Route::get('/music/kits', [WeaponSkinController::class, 'music'])->name('music')->middleware('auth');
Route::get('/pins/pin', [WeaponSkinController::class, 'pin'])->name('pin')->middleware('auth');

Route::get('/weapons/stickers', function() {
    $stickers = json_decode(File::get(resource_path('json/stickers.json')), true);
    return response()->json($stickers);
});

Route::get('/weapons/keychains', function() {
    $keychains = json_decode(File::get(resource_path('json/keychains.json')), true);
    return response()->json($keychains);
});

use App\Http\Controllers\SettingsController;

Route::middleware(['superadmin'])->group(function () {
    Route::get('/settings/servers', [ServerController::class, 'showServerSettings'])->name('settings.servers');
    Route::post('/settings/servers/update', [ServerController::class, 'updateServerSettings'])->name('settings.servers.update');
    Route::post('/settings/servers/sync', [ServerController::class, 'syncNewServers'])->name('settings.servers.sync');
    Route::get('/settings', [SettingsController::class, 'showSettings'])->name('settings.show');
    Route::post('/settings', [SettingsController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/test-email', [SettingsController::class, 'sendTestEmail'])->name('settings.test-email');
});

if(env('APPEALS') == 'Enabled') {
    Route::get('/appeals', [AppealController::class, 'list'])->name('appeals.list');
    Route::get('/appeals/create', [AppealController::class, 'create'])->name('appeals.create');
    Route::post('appeals', [AppealController::class, 'store'])->name('appeals.store');
    Route::get('/appeals/{id}', [AppealController::class, 'view'])->name('appeals.show');
    Route::put('/appeals/{id}/status', [AppealController::class, 'updateStatus'])->name('appeals.updateStatus');
}

if(env('REPORTS') == 'Enabled') {
    Route::prefix('reports')->group(function () {
        Route::get('create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('store', [ReportController::class, 'store'])->name('reports.store');
        Route::get('list', [ReportController::class, 'list'])->name('reports.list');
        Route::get('show/{id}', [ReportController::class, 'show'])->name('reports.show');
        Route::delete('destroy/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');
    });
}


use App\Http\Controllers\ModuleServerSettingsController;

Route::get('/modules', [ModuleServerSettingsController::class, 'index'])->name('module-server-settings.index')->middleware('superadmin');
Route::get('/modules/create', [ModuleServerSettingsController::class, 'create'])->name('module-server-settings.create')->middleware('superadmin');
Route::post('/modules', [ModuleServerSettingsController::class, 'store'])->name('module-server-settings.store')->middleware('superadmin');
Route::get('/modules/{id}/edit', [ModuleServerSettingsController::class, 'edit'])->name('module-server-settings.edit')->middleware('superadmin');
Route::put('/modules/{id}', [ModuleServerSettingsController::class, 'update'])->name('module-server-settings.update')->middleware('superadmin');
Route::delete('/modules/{id}', [ModuleServerSettingsController::class, 'destroy'])->name('module-server-settings.destroy')->middleware('superadmin');

Route::get('/clear-cache', function () {
    Cache::flush();
    return response()->json(['message' => 'Cache cleared successfully.']);
})->name('cache.clear')->middleware('superadmin');


Route::prefix('stats')->group(function () {
    Route::get('/player_count', [ServerController::class, 'trackServerPlayerCounts']);
});

// Admin logs routes
Route::middleware('superadmin')->group(function () {
    Route::get('/admin/logs', [App\Http\Controllers\AdminLogController::class, 'index'])->name('admin.logs');
    Route::get('/admin/logs/data', [App\Http\Controllers\AdminLogController::class, 'getLogsList'])->name('admin.logs.data');
    Route::get('/admin/logs/filters', [App\Http\Controllers\AdminLogController::class, 'getFilters'])->name('admin.logs.filters');
    Route::get('/admin/logs/details/{id}', [App\Http\Controllers\AdminLogController::class, 'showDetails'])->name('admin.logs.details');
});
