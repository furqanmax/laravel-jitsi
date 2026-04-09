<?php

namespace VcMeet\Jitsi;

use Illuminate\Support\ServiceProvider;

class JitsiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/meeting.php', 'meeting');
        
        // Bind any interfaces to concrete implementations if needed in the future
        // $this->app->bind(MeetingInterface::class, MeetingService::class);
    }

    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-jitsi');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-jitsi');

        // Publish files when running in console
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__.'/../config/meeting.php' => config_path('meeting.php'),
            ], 'laravel-jitsi-config');

            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-jitsi'),
            ], 'laravel-jitsi-views');

            // Publish migrations
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'laravel-jitsi-migrations');

            // Publish assets (if any)
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-jitsi'),
            ], 'laravel-jitsi-assets');
        }
    }
}
