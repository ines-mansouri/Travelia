<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class IntegrationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bindings for adapters, aggregators, etc. will go here in subsequent phases.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configuration setup or boot actions.
    }
}
