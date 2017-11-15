<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Providers;

use App\Http\Middleware\EncryptCookies;
use Caffeinated\Modules\Facades\Module;
use Form;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ProVision\Administration\Administration;
use ProVision\Administration\Exceptions\Handler;
use ProVision\Administration\Forms\Fields\NewBox;
use ProVision\Administration\Http\Middleware\HttpsProtocol;
use ProVision\Administration\Http\Middleware\NonWww;

class AdministrationServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        /*
         * MySQL fix for utf8mb4 collation
         */
        Schema::defaultStringLength(191);

        if (config('app.debug') === true) {
            /*
             * Enable query logging
             */
            DB::enableQueryLog();
        }

        /*
       * config
       */
        $this->publishes([
            __DIR__ . '/../../config/provision_administration.php' => config_path('provision_administration.php'),
            __DIR__ . '/../../config/laravellocalization.php' => config_path('laravellocalization.php'),
            __DIR__ . '/../../config/entrust.php' => config_path('entrust.php'),
            __DIR__ . '/../../config/laravel-form-builder.php' => config_path('laravel-form-builder.php'),
            __DIR__ . '/../../config/laravel-menu/settings.php' => config_path('laravel-menu/settings.php'),
            __DIR__ . '/../../config/laravel-menu/views.php' => config_path('laravel-menu/views.php'),
        ], 'config');

        //set custom auth provider & guard
        Config::set([
            'auth.guards.' . config('provision_administration.guard') => [
                'driver' => 'session',
                'provider' => config('provision_administration.guard'),
            ],
        ]);
        Config::set([
            'auth.providers.' . config('provision_administration.guard') => [
                'driver' => 'eloquent',
                'model' => \ProVision\Administration\AdminUser::class,
            ],
        ]);
        Config::set([
            'auth.passwords.' . config('provision_administration.guard') => [
                'provider' => config('provision_administration.guard'),
                'email' => 'auth.emails.password',
                'table' => 'password_resets',
                'expire' => 60,
            ],
        ]);

        /*
         * LogViewer settings
         */
        if (config('provision_administration.packages.log-viewer')) {

            //check app settings
            if (config('app.log') != 'daily') {
                die('config/app.php => log != daily');
            }

            //set middleware
            Config::set('log-viewer.route.attributes.middleware', [
                'web',
                'permission:administrators.index',
            ]);

            //set url
            Config::set('log-viewer.route.attributes.prefix', Administration::getLanguage() . '/' . Config::get('provision_administration.url_prefix') . '/log-viewer');
        }

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
        /*
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/provision/administration'),
        ], 'views');
        */

        /*
         * assets
         */
        $this->publishes([
            __DIR__ . '/../../public/assets' => public_path('vendor/provision/administration'),
        ], 'public');

        /*
         * translations
         */
        //$this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'administration');
        $this->loadTranslationsFrom(resource_path('lang/vendor/provision/administration'), 'administration');
        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/provision/administration'),
        ], 'lang');

        /*
         * database

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations')
        ], 'migrations');


        $this->publishes([
            __DIR__ . '/../../database/seeds/' => database_path('seeds')
        ], 'seeds');
        */

        $this->publicBoot();
        $this->adminBoot();
        $this->modulesBoot();
    }

    private function publicBoot() {
        /*
          * LogViewer settings
          */
        if (config('provision_administration.packages.log-viewer')) {

            //check app settings
            if (!App::runningInConsole() && config('app.log') != 'daily') {
                die('config/app.php => log != daily');
            }

            //set middleware
            Config::set('log-viewer.route.attributes.middleware', [
                'web',
                'permission:administrators.index',
            ]);

            //set url
            Config::set('log-viewer.route.attributes.prefix', Administration::getLanguage() . '/' . Config::get('provision_administration.url_prefix') . '/log-viewer');
        }
    }

    private function adminBoot() {

        if (!\ProVision\Administration\Facades\Administration::routeInAdministration()) {
            return;
        }

        /*
         * set breadcrumbs view
         */
        Config::set('breadcrumbs.view', 'administration::partials.breadcrumbs');

        //ако се ползва laravellocalization => 'hideDefaultLocaleInURL' => false,
        if (!empty(\LaravelLocalization::setLocale())) {
            if (!\Request::is(\LaravelLocalization::setLocale() . '/' . config('provision_administration.url_prefix') . '*')) {
                return false;
            }
        } else {
            if (!\Request::is(config('provision_administration.url_prefix') . '*')) {
                return false;
            }
        }

        /*
       * add custom Form fields
       */
        Config::set('laravel-form-builder.template_prefix', 'administration::components.fields.');
        Config::set('laravel-form-builder.custom_fields.admin_footer', \ProVision\Administration\Forms\Fields\AdminFooter::class);
        Config::set('laravel-form-builder.custom_fields.editor', \ProVision\Administration\Forms\Fields\Editor::class);
        Config::set('laravel-form-builder.custom_fields.address_picker', \ProVision\Administration\Forms\Fields\AddressPicker::class);
        Config::set('laravel-form-builder.custom_fields.date_picker', \ProVision\Administration\Forms\Fields\DatePicker::class);
        Config::set('laravel-form-builder.custom_fields.datetime_picker', \ProVision\Administration\Forms\Fields\DatetimePicker::class);
        Config::set('laravel-form-builder.custom_fields.new_box', NewBox::class);

        /*
         * Administration listing short buttons
         */
        Form::component('adminDeleteButton', 'administration::components.form.admin_delete_button', [
            'name',
            'href',
        ]);
        Form::component('adminRestoreButton', 'administration::components.form.admin_restore_button', [
            'name',
            'href',
        ]);
        Form::component('adminSwitchButton', 'administration::components.form.admin_switch_button', [
            'name',
            'model',
        ]);
        Form::component('adminEditButton', 'administration::components.form.admin_edit_button', [
            'name',
            'href',
        ]);
        Form::component('adminLinkButton', 'administration::components.form.admin_link_button', [
            'name',
            'href',
            'attributes'
        ]);
        Form::component('adminMediaButton', 'administration::components.form.admin_media_button', [
            'model',
        ]);
        Form::component('adminOrderButton', 'administration::components.form.admin_order_button', [
            'model',
        ]);

        /*
        * Administration menu init
        */
        //administrators
        \AdministrationMenu::addSystem(trans('administration::administrators.administrators'), [
            'icon' => 'user-circle-o'
        ], function ($menu) {
            $menu->addItem(trans('administration::administrators.create_administrator'), [
                'route' => 'provision.administration.administrators.create',
            ])
                ->addItem(trans('administration::index.view_all'), [
                    'route' => 'provision.administration.administrators.index',
                ])
                //groups
                ->addItem(trans('administration::administrators.groups'), [], function ($groupsMenu) {
                    $groupsMenu->addItem(trans('administration::index.view_all'), [
                        'route' => 'provision.administration.administrators-roles.index',
                    ])->addItem(trans('administration::index.add'), [
                        'route' => 'provision.administration.administrators-roles.create'
                    ]);
                });
        });

        //static blocks
        \AdministrationMenu::addSystem(trans('administration::static_blocks.name'), [
            'icon' => 'th-large'
        ], function ($menu) {
            $menu->addItem(trans('administration::index.add'), [
                'route' => 'provision.administration.static-blocks.create'
            ])
                ->addItem(trans('administration::index.view_all'), [
                    'route' => 'provision.administration.static-blocks.index',
                ]);
        });

        //system settings
        \AdministrationMenu::addSystem(trans('administration::settings.title'), [
            'icon' => 'sliders',
            'route' => 'provision.administration.settings.index'
        ]);

        \AdministrationMenu::addSystem(trans('administration::systems.title'), [
            'icon' => 'cogs'
        ], function ($menu) {
            $menu->addItem(trans('administration::systems.roles-repair'), [
                'route' => 'provision.administration.systems.roles-repair',
                'icon' => 'user-secret'
            ])
                ->addItem(trans('administration::systems.maintenance-mode'), [
                    'route' => 'provision.administration.systems.maintenance-mode',
                    'icon' => 'hand-paper-o'
                ]);

            // System -> System -> Log Viewer
            if (config('provision_administration.packages.log-viewer', false)) {
                $menu->addItem(trans('administration::systems.log-viewer'), [
                    'url' => config('log-viewer.route.attributes.prefix'),
                    'icon' => 'bug',
                    'target' => '_blank'
                ]);
            }
        });
    }

    private function modulesBoot() {

        /*
         * load user installed modules
         */
        foreach (Module::all() as $module) {
            $adminInitClass = module_class($module['slug'], 'Administration');

            if (class_exists($adminInitClass)) {
                //load module translations @todo: да се помисли НЕ Е ДОБРЕ ТУК!
                $this->loadTranslationsFrom(app_path('Modules/' . $module['basename'] . '/Resources/Lang'), $module['slug']);

                //boot module /Administration.php
                Administration::bootModule($module, $adminInitClass);
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

        //reset session cookie
        Config::set(['session.cookie' => 'provision_session']);

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/provision_administration.php', 'provision_administration'
        );

        /*
         * lib configs
         */
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/entrust.php', 'entrust'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/laravellocalization.php', 'laravellocalization'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/laravel-form-builder.php', 'laravel-form-builder'
        );

        /**
         * Exception handler
         */
        if (!config('provision_administration.disable_administration_exception_handler', false)) {
            $this->app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, Handler::class);
        }

        /*
         * middleware
         */
        $this->addAliasMiddleware('localize', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class);
        $this->addAliasMiddleware('localizationRedirect', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class);
        $this->addAliasMiddleware('localeSessionRedirect', \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class);

        //$this->addAliasMiddleware('role', \Zizaco\Entrust\Middleware\EntrustRole::class);
        $this->addAliasMiddleware('role', \ProVision\Administration\Http\Middleware\EntrustRole::class);
        $this->addAliasMiddleware('permission', \ProVision\Administration\Http\Middleware\EntrustPermission::class);
        $this->addAliasMiddleware('ability', \Zizaco\Entrust\Middleware\EntrustAbility::class);

        //automatic check permissions to modules
        $this->app['router']->pushMiddlewareToGroup('web', \ProVision\Administration\Http\Middleware\EntrustAuto::class);
        //check Maintenance Mode
        $this->app['router']->pushMiddlewareToGroup('web', \ProVision\Administration\Http\Middleware\CheckForMaintenanceMode::class);
        $this->app['router']->pushMiddlewareToGroup('api', \ProVision\Administration\Http\Middleware\CheckForMaintenanceMode::class);
        //SSL redirect
        $this->app['router']->pushMiddlewareToGroup('web', HttpsProtocol::class);
        $this->app['router']->pushMiddlewareToGroup('api', HttpsProtocol::class);
        //non-WWW redirect
        $this->app['router']->pushMiddlewareToGroup('web', NonWww::class);

        /*
         * Commands
         */
        $this->commands([
            \ProVision\Administration\Console\Commands\CreateAdministrator::class,
            \ProVision\Administration\Console\Commands\Migrate::class,
            //\ProVision\Administration\Console\Commands\MigrateRollback::class
        ]);

        $this->app->singleton('Administration', function ($app) {
            return new Administration;
        });

        $this->app->bind('AdministrationMenu', function () {
            return new \ProVision\Administration\AdministrationMenu;
        });

        $this->app->bind('Settings', function () {
            return new \ProVision\Administration\Settings;
        });


        /*
         * disable cookies encryption for administration cookies
         */
        $this->app->resolving(EncryptCookies::class, function ($object) {
            //collapse navigation
            $object->disableFor('administration-navigation-collapsed');
        });

    }

    /**
     * Backward compatibility with 5.3 ->middleware ->aliasMiddleware
     *
     * @param $name
     * @param $class
     */
    protected function addAliasMiddleware($name, $class) {
        $app = app();

        if (version_compare($app::VERSION, '5.4', '<')) {
            $this->app['router']->middleware($name, $class);
        } else {
            $this->app['router']->aliasMiddleware($name, $class);
        }
    }
}
