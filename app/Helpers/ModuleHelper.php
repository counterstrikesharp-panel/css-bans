<?php

namespace App\Helpers;


use App\Models\ModuleServerSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ModuleHelper
{
    public static function useConnection($moduleName, $serverId = null): void
    {
        try {
            if (empty($serverId) && empty(Session::get($moduleName . '_server'))) {
                $setting = ModuleServerSetting::where('module_name', $moduleName)->where('active', 1)->first();
                Session::put($moduleName . '_server', $setting->id);
            } else {
                if (!empty($serverId)) {
                    $serverId = Crypt::decrypt($serverId);
                    Session::put($moduleName . '_server', $serverId);
                }
                $setting = ModuleServerSetting::where('module_name', $moduleName)
                    ->where('active', 1)
                    ->where('id', Session::get($moduleName . '_server'))
                    ->first();
            }
            Config::set('database.connections.mysqlranks', [
                'driver' => 'mysql',
                'host' => $setting->db_host,
                'database' => $setting->db_name,
                'username' => $setting->db_user,
                'password' => $setting->db_pass,
                'port' => $setting->port,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]);
        } catch (\Exception $e) {
            Log::error('module.error ' . $e->getMessage());
            echo '<div style="color: red; font-weight: bold;">Module DB settings not found. To fix this, go to .env file search RANKS, disable the ranks module, then go to Settings tab > Modules and add new DB settings, and re-enable the module.</div>';
            die();
        }

        DB::setDefaultConnection('mysqlranks');
    }
}
