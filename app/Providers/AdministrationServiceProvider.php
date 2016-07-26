<?php

namespace ProVision\Administration\Providers;

use App\Http\Middleware\EncryptCookies;
use Config;
use Form;
use Illuminate\Support\ServiceProvider;
use ProVision\Administration\Administration;
use ProVision\Administration\Facades\StaticBlockFacade;

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
            __DIR__ . '/../../config/laravel-form-builder.php' => config_path('laravel-form-builder.php'),
            __DIR__ . '/../../config/laravel-menu/settings.php' => config_path('laravel-menu/settings.php'),
            __DIR__ . '/../../config/laravel-menu/views.php' => config_path('laravel-menu/views.php'),
        ], 'config');

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
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'administration');
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

        $this->adminBoot();
    }

    private function adminBoot() {

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
        Config::set('laravel-form-builder.custom_fields.ckeditor', \ProVision\Administration\Forms\Fields\CKEditor::class);
        Config::set('laravel-form-builder.custom_fields.address_picker', \ProVision\Administration\Forms\Fields\AddressPicker::class);

        /*
         * Administration listing short buttons
         */
        Form::component('adminDeleteButton', 'administration::components.form.admin_delete_button', [
            'name',
            'href'
        ]);
        Form::component('adminEditButton', 'administration::components.form.admin_edit_button', [
            'name',
            'href'
        ]);
        Form::component('adminMediaButton', 'administration::components.form.admin_media_button', [
            'model'
        ]);


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

//            var_dump( 'provision.administration.' . \LaravelLocalization::setLocale() . '.admin.administartors.index');
//            dd(\Route::getRoutes());

            //system settings
            $menu->add(trans('administration::index.system-settings'), ['nickname' => 'system-settings'])->data('header', true)->data('order', 10000);

            /*
             * Administrators
             */
            $administratorsMenu = $menu->add(trans('administration::administrators.administrators'), [
                'nickname' => 'administrators.menu.item',
            ])->data('order', 10001)->data('icon', 'users');
            $administratorsMenu->add(trans('administration::administrators.create_administrator'), [
                'nickname' => 'administrators.create',
                'route' => 'provision.administration.administrators.create'
            ])->data('icon', 'plus')->data('order', 1);
            $administratorsMenu->add(trans('administration::index.view_all'), [
                'nickname' => 'administrators',
                'route' => 'provision.administration.administrators.index'
            ])->data('icon', 'list')->data('order', 2);


            /*
             * Administrators/Roles
             */
            $rolesMenu = $administratorsMenu->add(trans('administration::administrators.groups'), [
                'nickname' => 'administrators.groups',
                'route' => 'provision.administration.administrators-roles.index'
            ])->data('icon', 'users')->data('order', 3);
            $rolesMenu->add(trans('administration::index.view_all'), [
                'nickname' => 'administrators.group_add',
                'route' => 'provision.administration.administrators-roles.index'
            ])->data('icon', 'list')->data('order', 1);
            $rolesMenu->add(trans('administration::index.add'), [
                'nickname' => 'administrators.group_add',
                'route' => 'provision.administration.administrators-roles.create'
            ])->data('icon', 'plus')->data('order', 2);

            /*
             * Static blocks
             */
            $staticMenu = $menu->add(trans('administration::static_blocks.name'), [
                'nickname' => 'administrators.static_blocks',
                'route' => 'provision.administration.static-blocks.index'
            ])->data('icon', 'th-large')->data('order', 10002);
            $staticMenu->add(trans('administration::index.view_all'), [
                'nickname' => 'administrators.group_add',
                'route' => 'provision.administration.static-blocks.index'
            ])->data('icon', 'list')->data('order', 1);
            $staticMenu->add(trans('administration::index.add'), [
                'nickname' => 'administrators.group_add',
                'route' => 'provision.administration.static-blocks.create'
            ])->data('icon', 'plus')->data('order', 2);

            /*
             * Settings
             */
            $menu->add(trans('administration::settings.title'), [
                'nickname' => 'settings',
                'route' => 'provision.administration.settings.index'
            ])->data('order', 10002)->data('icon', 'sliders');


            /*
             * System
             */
            $systemMenu = $menu->add(trans('administration::systems.title'), ['nickname' => 'system'])->data('order', 10003)->data('icon', 'cogs');
            $systemMenu->add(trans('administration::systems.roles-repair'), [
                'nickname' => 'system-roles-repair',
                'route' => 'provision.administration.systems.roles-repair'
            ])->data('icon', 'user-secret');

            $menu->add(trans('administration::index.translates'), ['nickname' => 'translates'])->data('order', 10004)->data('icon', 'globe');

        });
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


        /*
        * Register the service provider for the dependency.
        */
        $this->app->register(\Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class);
        $this->app->register(\Zizaco\Entrust\EntrustServiceProvider::class);
        $this->app->register(\Caffeinated\Modules\ModulesServiceProvider::class);
        $this->app->register(\Lavary\Menu\ServiceProvider::class);
        $this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);
        $this->app->register(\Kris\LaravelFormBuilder\FormBuilderServiceProvider::class);
        $this->app->register(\Cviebrock\EloquentSluggable\ServiceProvider::class);
        $this->app->register(\Torann\LaravelMetaTags\MetaTagsServiceProvider::class);
        $this->app->register(\Dimsav\Translatable\TranslatableServiceProvider::class);
        //$this->app->register(\Krucas\Notification\NotificationServiceProvider::class);
        //$this->app->register(\Laravel\Socialite\SocialiteServiceProvider::class);
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);
        $this->app->register(\DaveJamesMiller\Breadcrumbs\ServiceProvider::class);
        //$this->app->register(\Barryvdh\TranslationManager\ManagerServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);

        /*
         * Create aliases for the dependency.
         */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Administration', \ProVision\Administration\Facades\Administration::class);
        $loader->alias('LaravelLocalization', \Mcamara\LaravelLocalization\Facades\LaravelLocalization::class);
        $loader->alias('Entrust', \Zizaco\Entrust\EntrustFacade::class);
        $loader->alias('Module', \Caffeinated\Modules\Facades\Module::class);
        $loader->alias('Menu', \Lavary\Menu\Facade::class);
        $loader->alias('Datatables', \Yajra\Datatables\Facades\Datatables::class);
        $loader->alias('FormBuilder', \Kris\LaravelFormBuilder\Facades\FormBuilder::class);
        $loader->alias('MetaTag', \Torann\LaravelMetaTags\Facades\MetaTag::class);
        //$loader->alias('Notification', \Krucas\Notification\Facades\Notification::class);
        //$loader->alias('Socialite', \Laravel\Socialite\Facades\Socialite::class);
        $loader->alias('Image', \Intervention\Image\Facades\Image::class);
        $loader->alias('Breadcrumbs', \DaveJamesMiller\Breadcrumbs\Facade::class);
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('Html', \Collective\Html\HtmlFacade::class);

        /*
         * middleware
         */
        $this->app['router']->middleware('localize', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class);
        $this->app['router']->middleware('localizationRedirect', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class);
        $this->app['router']->middleware('localeSessionRedirect', \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class);

        //$this->app['router']->middleware('role', \Zizaco\Entrust\Middleware\EntrustRole::class);
        $this->app['router']->middleware('role', \ProVision\Administration\Http\Middleware\EntrustRole::class);
        $this->app['router']->middleware('permission', \ProVision\Administration\Http\Middleware\EntrustPermission::class);
        $this->app['router']->middleware('ability', \Zizaco\Entrust\Middleware\EntrustAbility::class);

        //automatic check permissions to modules
        $this->app['router']->pushMiddlewareToGroup('web', \ProVision\Administration\Http\Middleware\EntrustAuto::class);

        /*
         * Commands
         */
        $this->commands([
            \ProVision\Administration\Console\Commands\CreateAdministrator::class,
            \ProVision\Administration\Console\Commands\Migrate::class,
            \ProVision\Administration\Console\Commands\MediaResize::class,
            //\ProVision\Administration\Console\Commands\MigrateRollback::class
        ]);

        $this->app['administration'] = $this->app->share(function ($app) {
            return new Administration;
        });

        /*
         * disable cookies encryption for administration cookies
         */
        $this->app->resolving(EncryptCookies::class, function ($object) {
            //collapse navigation
            $object->disableFor('administration-navigation-collapsed');
        });

    }

}
