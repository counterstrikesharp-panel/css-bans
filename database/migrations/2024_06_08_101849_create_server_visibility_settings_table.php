<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerVisibilitySettingsTable extends Migration
{
    public function up()
    {
        Schema::create('server_visibility_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('server_id');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('server_visibility_settings');
    }
}
