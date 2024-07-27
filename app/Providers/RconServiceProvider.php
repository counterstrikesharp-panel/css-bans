<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RconServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Bind RconService to the service container
        $this->app->singleton(RconService::class, function ($app) {
            return new RconService();
        });
    }
}
