<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use ProVision\Administration\Administration;
use ProVision\Administration\Console\Commands\AdministratorCreateCommand;
use ProVision\Administration\Console\Commands\InstallCommand;
use ProVision\Administration\Console\Commands\PermissionsCommand;
use ProVision\Administration\Console\Commands\SetupCommand;
use ProVision\Administration\Exceptions\Handler;
use ProVision\Administration\Http\Middleware\Permission;
use ProVision\Administration\Middleware\Authenticate;
use ProVision\Administration\Middleware\RedirectIfAuthenticated;

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
                __DIR__ . '/../../public' => public_path('vendor/provision/administration'),
            ], 'assets');

            // Publishing the translation files.
            $this->publishes([
                __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/provision/administration'),
            ], 'lang');

            // Registering package commands.
            $this->commands([
                AdministratorCreateCommand::class,
                SetupCommand::class,
                PermissionsCommand::class
            ]);
        }


        /*
         * Attach middleware
         */
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('admin_auth', Authenticate::class);
        $router->aliasMiddleware('admin_guest', RedirectIfAuthenticated::class);
        $router->aliasMiddleware('admin_permission', Permission::class);
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'administration');
        $this->mergeConfigFrom(__DIR__ . '/../../config/auth.php', 'auth');

        // Register the main class to use with the facade
        $this->app->singleton('administration', function () {
            return new Administration;
        });

        /*
         * Exception handler
         */
        if (!config('administration.disable_administration_exception_handler', false)) {
            $this->app->singleton(ExceptionHandler::class, Handler::class);
        }
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param mixed $path
     * @param mixed $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key): void
    {
        if (!$this->app->configurationIsCached()) {
            $this->app['config']->set($key, $this->mergeConfig(require $path, $this->app['config']->get($key, [])));
        }
    }

    /**
     * Merges the configs together and takes multi-dimensional arrays into account.
     *
     * @param array $original
     * @param array $merging
     * @return array
     */
    protected function mergeConfig(array $original, array $merging)
    {
        $array = array_merge($original, $merging);
        foreach ($original as $key => $value) {
            if (!is_array($value)) {
                continue;
            }
            if (!Arr::exists($merging, $key)) {
                continue;
            }
            if (is_numeric($key)) {
                continue;
            }
            $array[$key] = $this->mergeConfig($value, $merging[$key]);
        }
        return $array;
    }
}
