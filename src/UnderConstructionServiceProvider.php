<?php

namespace LarsJanssen\UnderConstruction;

use Illuminate\Support\ServiceProvider;

class UnderConstructionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/under-construction.php' => config_path('under-construction.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/under-construction.php', 'under-construction');
    }
}