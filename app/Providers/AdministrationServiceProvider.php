<?php

namespace ProVision\Administration\Providers;

use Config;
use Illuminate\Support\ServiceProvider;
use ProVision\Administration\Administration;

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
            __DIR__ . '/../../config/provision_administration.php' => config_path('provision_administration.php'),
            __DIR__ . '/../../config/laravellocalization.php' => config_path('laravellocalization.php'),
            __DIR__ . '/../../config/entrust.php' => config_path('entrust.php'),
            __DIR__ . '/../../config/laravel-menu/settings.php' => config_path('laravel-menu/settings.php'),
            __DIR__ . '/../../config/laravel-menu/views.php' => config_path('laravel-menu/views.php'),
        ], 'config');

        //reset session cookie
        Config::set(['session.cookie' => 'provision_session']);

        //set custom auth provider & guard
        Config::set([
            'auth.guards.provision_administration' => [
                'driver' => 'session',
                'provider' => 'provision_administration',
            ]
        ]);
        Config::set([
            'auth.providers.provision_administration' => [
                'driver' => 'eloquent',
                'model' => \ProVision\Administration\AdminUser::class
            ]
        ]);
        Config::set([
            'auth.passwords.provision_administration' => [
                'provider' => 'provision_administration',
                'email' => 'auth.emails.password',
                'table' => 'password_resets',
                'expire' => 60,
            ]
        ]);

        /*
         * Routes
         */
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/../Http/routes.php';
        }

        /*
         * views
         */
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'administration');
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/provision/administration'),
        ], 'views');


        /*
         * assets
         */
        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path('vendor/provision/administration'),
        ], 'public');

        /*
         * translations
         */
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'administration');
        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/provision/administration'),
        ], 'lang');

        /*
         * database
         */
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations')
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/../../database/seeds/' => database_path('seeds')
        ], 'seeds');

        /*
         * Modules
        $modules = config("provision_administration.modules");
        if (is_array($modules) && count($modules) > 0) {
            while (list(, $module) = each($modules)) {
                // Load the routes for each of the modules
                if (file_exists(base_path() . '/modules/' . $module . '/routes.php')) {
                    include base_path() . '/modules/' . $module . '/routes.php';
                }
                // Load the views
                if (is_dir(base_path() . '/modules/' . $module . '/Views')) {
                    $this->loadViewsFrom(base_path() . '/modules/' . $module . '/Views', $module);
                }
            }
        }*/

        /*
         * init modules
         */
        //Administration::initModules();

        /*
       * Administration menu init
       */
        \Menu::make('ProVisionAdministrationMenu', function ($menu) {
            //main header
            $menu->add(trans('administration::index.main_navigation'), ['nickname' => 'navigation'])->data('header', true)->data('order', 1);

            //home
            $menu->add(trans('administration::index.home'), [
                'route' => 'provision.administration.index',
                'nickname' => 'home'
            ])
                ->data('icon', 'home')
                ->data('order', 2);
            //->active('xxx/');

            //modules
            $menu->add(trans('administration::index.modules'), ['nickname' => 'modules'])->data('header', true)->data('order', 1000);

            //system settings
            $menu->add(trans('administration::index.system-settings'), ['nickname' => 'system-settings'])->data('header', true)->data('order', 10000);
            $menu->add(trans('administration::index.administrators'), ['nickname' => 'administrators'])->data('order', 10001)->data('icon', 'users');
            $menu->add(trans('administration::index.settings'), ['nickname' => 'settings'])->data('order', 10002)->data('icon', 'cogs');

        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/provision_administration.php', 'provision_administration'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/entrust.php', 'entrust'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/laravellocalization.php', 'laravellocalization'
        );


        /*
        * Register the service provider for the dependency.
        */
        $this->app->register(\Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class);
        $this->app->register(\Zizaco\Entrust\EntrustServiceProvider::class);
        $this->app->register(\Caffeinated\Modules\ModulesServiceProvider::class);
        $this->app->register(\Lavary\Menu\ServiceProvider::class);
        $this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);
        $this->app->register(\Distilleries\FormBuilder\FormBuilderServiceProvider::class);
        $this->app->register(\Cviebrock\EloquentSluggable\ServiceProvider::class);
        $this->app->register(\Torann\LaravelMetaTags\MetaTagsServiceProvider::class);
        $this->app->register(\Krucas\Notification\NotificationServiceProvider::class);
        $this->app->register(\Laravel\Socialite\SocialiteServiceProvider::class);
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);
        $this->app->register(\DaveJamesMiller\Breadcrumbs\ServiceProvider::class);
        $this->app->register(\Barryvdh\TranslationManager\ManagerServiceProvider::class);

        /*
         * Create aliases for the dependency.
         */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('LaravelLocalization', \Mcamara\LaravelLocalization\Facades\LaravelLocalization::class);
        $loader->alias('Entrust', \Zizaco\Entrust\EntrustFacade::class);
        $loader->alias('Module', \Caffeinated\Modules\Facades\Module::class);
        $loader->alias('Menu', \Lavary\Menu\Facade::class);
        $loader->alias('Datatables', \Yajra\Datatables\Facades\Datatables::class);
        $loader->alias('FormBuilder', \Distilleries\FormBuilder\Facades\FormBuilder::class);
        $loader->alias('MetaTag', \Torann\LaravelMetaTags\Facades\MetaTag::class);
        $loader->alias('Notification', \Krucas\Notification\Facades\Notification::class);
        $loader->alias('Socialite', \Laravel\Socialite\Facades\Socialite::class);
        $loader->alias('Image', \Intervention\Image\Facades\Image::class);
        $loader->alias('Breadcrumbs', \DaveJamesMiller\Breadcrumbs\Facade::class);

        /*
         * middleware
         */
        $this->app['router']->middleware('localize', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class);
        $this->app['router']->middleware('localizationRedirect', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class);
        $this->app['router']->middleware('localeSessionRedirect', \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class);

        //$this->app['router']->middleware('role', \Zizaco\Entrust\Middleware\EntrustRole::class);
        $this->app['router']->middleware('role', \ProVision\Administration\Http\Middleware\EntrustRole::class);
        $this->app['router']->middleware('permission', \Zizaco\Entrust\Middleware\EntrustPermission::class);
        $this->app['router']->middleware('ability', \Zizaco\Entrust\Middleware\EntrustAbility::class);

        /*
         * Commands
         */
        $this->commands([
            \ProVision\Administration\Console\Commands\CreateAdministrator::class
        ]);

        $this->app['administration'] = $this->app->share(function ($app) {
            return new Administration;
        });


    }

}
