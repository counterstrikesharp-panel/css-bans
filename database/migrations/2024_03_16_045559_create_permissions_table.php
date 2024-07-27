<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('permission');
            $table->string('description');
            $table->timestamps();
        });

        // Insert default permission values
        $permissions = [
            ['permission' => '@css/reservation', 'description' => 'Reserved slot access.'],
            ['permission' => '@css/generic', 'description' => 'Generic admin.'],
            ['permission' => '@css/kick', 'description' => 'Kick other players.'],
            ['permission' => '@css/ban', 'description' => 'Ban other players.'],
            ['permission' => '@css/unban', 'description' => 'Remove bans.'],
            ['permission' => '@css/vip', 'description' => 'General VIP status.'],
            ['permission' => '@css/slay', 'description' => 'Slay/harm other players.'],
            ['permission' => '@css/changemap', 'description' => 'Change the map or major gameplay features.'],
            ['permission' => '@css/cvar', 'description' => 'Change most cvars.'],
            ['permission' => '@css/config', 'description' => 'Execute config files.'],
            ['permission' => '@css/chat', 'description' => 'Special chat privileges.'],
            ['permission' => '@css/vote', 'description' => 'Start or create votes.'],
            ['permission' => '@css/password', 'description' => 'Set a password on the server.'],
            ['permission' => '@css/rcon', 'description' => 'Use RCON commands.'],
            ['permission' => '@css/cheats', 'description' => 'Change sv_cheats or use cheating commands.'],
            ['permission' => '@css/root', 'description' => 'Magically enables all flags and ignores immunity values.'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert($permission);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
