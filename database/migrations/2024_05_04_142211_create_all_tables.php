<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sa_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('immunity')->default('0');
        });
        // Create sa_admins table
        Schema::create('sa_admins', function (Blueprint $table) {
            $table->id();
            $table->string('player_name')->nullable();
            $table->string('player_steamid');
            $table->text('flags')->nullable();
            $table->string('immunity')->default('0');
            $table->timestamp('ends')->nullable();
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('group_id')->nullable(); // Update data type to unsignedBigInteger
            $table->integer('server_id')->nullable();
            $table->foreign('group_id')->references('id')->on('sa_groups')->onDelete('set null');
            $table->index('group_id');
        });

        // Create sa_admins_flags table
        Schema::create('sa_admins_flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('flag');
            $table->foreign('admin_id')->references('id')->on('sa_admins')->onDelete('cascade');
            $table->index('admin_id');
        });

        // Create sa_unbans table
        Schema::create('sa_unbans', function (Blueprint $table) {
            $table->id();
            $table->integer('ban_id');
            $table->integer('admin_id')->default('0');
            $table->string('reason')->default('Unknown');
            $table->timestamp('date')->useCurrent();
        });
        // Create sa_bans table
        Schema::create('sa_bans', function (Blueprint $table) {
            $table->id();
            $table->string('player_name')->nullable();
            $table->string('player_steamid')->nullable();
            $table->string('player_ip')->nullable();
            $table->string('admin_steamid');
            $table->string('admin_name');
            $table->string('reason');
            $table->integer('duration');
            $table->timestamp('ends')->useCurrent();
            $table->timestamp('created')->useCurrent();
            $table->integer('server_id')->nullable();
            $table->unsignedBigInteger('unban_id')->nullable();
            $table->enum('status', ['ACTIVE', 'UNBANNED', 'EXPIRED', ''])->default('ACTIVE');
            $table->foreign('unban_id')->references('id')->on('sa_unbans')->onDelete('cascade');
            $table->index('unban_id');
        });

        // Create sa_groups_flags table
        Schema::create('sa_groups_flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->string('flag');
            $table->foreign('group_id')->references('id')->on('sa_groups')->onDelete('cascade');
            $table->index('group_id');
        });

        // Create sa_groups_servers table
        Schema::create('sa_groups_servers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->integer('server_id');
            $table->foreign('group_id')->references('id')->on('sa_groups')->onDelete('cascade');
            $table->index('group_id');
        });

        // Create sa_migrations table
        Schema::create('sa_migrations', function (Blueprint $table) {
            $table->id();
            $table->string('version');
        });
        // Create sa_unmutes table
        Schema::create('sa_unmutes', function (Blueprint $table) {
            $table->id();
            $table->integer('mute_id');
            $table->integer('admin_id')->default('0');
            $table->string('reason')->default('Unknown');
            $table->timestamp('date')->useCurrent();
        });

        // Create sa_mutes table
        Schema::create('sa_mutes', function (Blueprint $table) {
            $table->id();
            $table->string('player_name')->nullable();
            $table->string('player_steamid');
            $table->string('admin_steamid');
            $table->string('admin_name');
            $table->string('reason');
            $table->integer('duration');
            $table->timestamp('ends')->useCurrent();
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('server_id')->nullable();
            $table->unsignedBigInteger('unmute_id')->nullable();
            $table->enum('type', ['GAG', 'MUTE', 'SILENCE', ''])->default('GAG');
            $table->enum('status', ['ACTIVE', 'UNMUTED', 'EXPIRED', ''])->default('ACTIVE');
            $table->foreign('unmute_id')->references('id')->on('sa_unmutes')->onDelete('cascade');
            $table->index('unmute_id');
        });

        // Create sa_servers table
        Schema::create('sa_servers', function (Blueprint $table) {
            $table->id();
            $table->string('hostname')->nullable();
            $table->string('address');
            $table->unique('address');
        });

        Schema::create('k4ranks', function (Blueprint $table) {
            $table->string('steam_id', 32)->primary();
            $table->string('name', 255);
            $table->dateTime('lastseen');
            $table->string('rank', 255);
            $table->integer('points')->default(0);
        });

        Schema::create('k4stats', function (Blueprint $table) {
            $table->string('steam_id', 32)->primary();
            $table->string('name', 255);
            $table->dateTime('lastseen');
            $table->integer('kills')->default(0);
            $table->integer('firstblood')->default(0);
            $table->integer('deaths')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('shoots')->default(0);
            $table->integer('hits_taken')->default(0);
            $table->integer('hits_given')->default(0);
            $table->integer('headshots')->default(0);
            $table->integer('chest_hits')->default(0);
            $table->integer('stomach_hits')->default(0);
            $table->integer('left_arm_hits')->default(0);
            $table->integer('right_arm_hits')->default(0);
            $table->integer('left_leg_hits')->default(0);
            $table->integer('right_leg_hits')->default(0);
            $table->integer('neck_hits')->default(0);
            $table->integer('unused_hits')->default(0);
            $table->integer('gear_hits')->default(0);
            $table->integer('special_hits')->default(0);
            $table->integer('grenades')->default(0);
            $table->integer('mvp')->default(0);
            $table->integer('round_win')->default(0);
            $table->integer('round_lose')->default(0);
            $table->integer('game_win')->default(0);
            $table->integer('game_lose')->default(0);
            $table->integer('rounds_overall')->default(0);
            $table->integer('rounds_ct')->default(0);
            $table->integer('rounds_t')->default(0);
            $table->integer('bomb_planted')->default(0);
            $table->integer('bomb_defused')->default(0);
            $table->integer('hostage_rescued')->default(0);
            $table->integer('hostage_killed')->default(0);
            $table->integer('noscope_kill')->default(0);
            $table->integer('penetrated_kill')->default(0);
            $table->integer('thrusmoke_kill')->default(0);
            $table->integer('flashed_kill')->default(0);
            $table->integer('dominated_kill')->default(0);
            $table->integer('revenge_kill')->default(0);
            $table->integer('assist_flash')->default(0);
        });

        Schema::create('k4times', function (Blueprint $table) {
            $table->string('steam_id', 32)->primary();
            $table->string('name', 255);
            $table->dateTime('lastseen');
            $table->integer('all')->default(0);
            $table->integer('ct')->default(0);
            $table->integer('t')->default(0);
            $table->integer('spec')->default(0);
            $table->integer('dead')->default(0);
            $table->integer('alive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop all the tables created by this migration
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('migrations');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('sa_admins');
        Schema::dropIfExists('sa_admins_flags');
        Schema::dropIfExists('sa_bans');
        Schema::dropIfExists('sa_groups');
        Schema::dropIfExists('sa_groups_flags');
        Schema::dropIfExists('sa_groups_servers');
        Schema::dropIfExists('sa_migrations');
        Schema::dropIfExists('sa_mutes');
        Schema::dropIfExists('sa_servers');
        Schema::dropIfExists('sa_unbans');
        Schema::dropIfExists('sa_unmutes');
        Schema::dropIfExists('k4ranks');
        Schema::dropIfExists('k4stats');
        Schema::dropIfExists('k4times');
    }
}
