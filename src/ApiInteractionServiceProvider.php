<?php

namespace MarksIhor\ApiInteraction;

use Illuminate\Support\ServiceProvider;

/**
 * Class ApiInteractionServiceProvider.
 */
class ApiInteractionServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        // Config
        $this->publishes([
            __DIR__ . '/config/' => config_path()
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/config/laravel_api_interaction.php', 'laravel_api_interaction');

        // Migrations
        $this->publishes([
            __DIR__ . '/migrations/' => database_path('migrations'),
        ], 'migrations');
    }
}
