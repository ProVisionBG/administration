<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Providers;

use Illuminate\Support\ServiceProvider;
use ProVision\Administration\Administration;

class AdministrationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'administration');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'administration');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/config.php' => config_path('administration.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/provision/administration'),
            ], 'views');

            // Publishing assets.
            $this->publishes([
                __DIR__ . '/../../resources/assets' => public_path('vendor/provision/administration'),
            ], 'assets');

            // Publishing the translation files.
            $this->publishes([
                __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/provision/administration'),
            ], 'lang');

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'administration');

        // Register the main class to use with the facade
        $this->app->singleton('administration', function () {
            return new Administration;
        });
    }
}
