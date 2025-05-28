<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('admin_name');
            $table->string('admin_steam_id');
            $table->string('action');
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('target_type')->nullable();
            $table->string('target_name')->nullable();
            $table->string('target_steam_id')->nullable();
            $table->text('details')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
