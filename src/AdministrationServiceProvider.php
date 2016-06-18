<?php

namespace ProVision\Administration;

use Illuminate\Support\ServiceProvider;

class AdministrationServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        /*
        * config
        */
        $this->publishes([
            __DIR__ . '/config/provision_administration.php' => config_path('provision_administration.php'),
        ]);

        /*
         * Routes
         */
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/routes.php';
        }

        /*
         * views
         */
        $this->loadViewsFrom(__DIR__ . '/Views', 'administration');
        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/provision/administration'),
        ]);


        /*
         * assets
         */
        $this->publishes([
            __DIR__ . '/Assets' => public_path('provision/administration'),
        ], 'public');

        /*
         * translations
         */
        $this->loadTranslationsFrom(__DIR__ . '/Translations', 'administration');
        $this->publishes([
            __DIR__ . '/Translations' => resource_path('lang/provision/administration'),
        ]);

        /*
         * database
         */
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

        $this->mergeConfigFrom(
            __DIR__ . '/config/provision_administration.php', 'provision_administration'
        );

        $this->app['administration'] = $this->app->share(function ($app) {
            return new Administration;
        });
    }
}
