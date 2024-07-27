<?php

namespace App\Helpers;


use App\Models\ModuleServerSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ModuleHelper
{
    public static function useConnection($moduleName, $serverId = null): void
    {
        if(empty($serverId) && empty(Session::get($moduleName.'_server'))){
            $setting = ModuleServerSetting::where('module_name', $moduleName)->where('active', 1)->first();
            Session::put($moduleName.'_server', $setting->id);
        } else {
            if(!empty($serverId)){
                $serverId = Crypt::decrypt($serverId);
                Session::put($moduleName.'_server',$serverId);
            }
            $setting = ModuleServerSetting::where('module_name', $moduleName)
                ->where('active', 1)
                ->where('id', Session::get($moduleName.'_server'))
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

        DB::setDefaultConnection('mysqlranks');
    }
}
