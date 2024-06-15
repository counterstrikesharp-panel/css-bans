<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('ban_type');
            $table->string('steamid')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('nickname');
            $table->text('comments');
            $table->string('name');
            $table->string('email');
            $table->unsignedBigInteger('server_id');
            $table->string('media_link')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
