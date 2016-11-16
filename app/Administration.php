<?php
namespace ProVision\Administration;

use File;
use Illuminate\Support\Facades\Facade;
use LaravelLocalization;

class Administration extends Facade {

    /*
     * inited modules container
     */
    private static $modules;

    /*
     * Current module name
     */
    private static $currentModuleTitle = 'Enter module name here!';

    /*
     * Current module sub name
     */
    private static $currentModuleSubTitle = '';

    /**
     * Set current module name for administration titles
     * @param $name
     */
    public static function setTitle($name) {
        Administration::$currentModuleTitle = $name;
    }

    /*
     * Get current module name
     */
    public static function getTitle() {
        return Administration::$currentModuleTitle;
    }

    /**
     * Set current module sub name for administration titles
     * @param $name
     */
    public static function setSubTitle($name) {
        Administration::$currentModuleSubTitle = $name;
    }

    /*
    * Get current module sub name
    */
    public static function getSubTitle() {
        return Administration::$currentModuleSubTitle;
    }

    /*
     * get all language codes
     */
    public static function getLanguages() {
        return LaravelLocalization::getSupportedLocales();
    }

    /*
     * get current language code
     */
    public static function getLanguage() {
        $locale = LaravelLocalization::setLocale();
        if (!empty($locale)) {
            return $locale;
        } else {
            return \App::getLocale();
        }
    }

    /*
     * get static block for blade templates
     */
    public static function getStaticBlock($key) {
        $block = StaticBlock::where('key', $key)->first();
        if ($block) {
            return $block->text;
        }
        \Debugbar::error('static block not found: ' . $key);
    }

    /*
     * Get module order index
     */
    public static function getModuleOrderIndex($module) {
        $module = \Module::where('slug', $module);
        if (!$module) {
            return false;
        }
        return $module['order'];
    }

    /*
     * get Administration menu instance
     */
    public static function getMenuInstance() {
        $menu = \Menu::get('ProVisionAdministrationMenu');
        if (empty($menu)) {
            $menu = \Menu::make('ProVisionAdministrationMenu', []);
        }
        return $menu;
    }

    /**
     * Is in
     * @return mixed
     */
    public static function isInMaintenanceMode() {
        return File::exists(storage_path('/framework/down-provision-administration'));
    }

    /*
     * check request URL is in administration
     */
    public static function routeInAdministration() {
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

        return true;
    }

    /*
     * Web site prefix in route
     */
    public static function routePrefix() {
        return \LaravelLocalization::setLocale();
    }

    /*
     * Administration prefix in route
     */
    public static function routeAdministrationPrefix() {
        if (!empty(\LaravelLocalization::setLocale())) {
            return \LaravelLocalization::setLocale() . '/' . config('provision_administration.url_prefix');
        } else {
            return config('provision_administration.url_prefix');
        }

    }

    /*
     * default middleware for route
     */
    public static function routeMiddleware($middleware = []) {
        $default = [
            'web',
            'localeSessionRedirect',
            'localizationRedirect',
        ];
        return array_merge($default, $middleware);
    }

    /*
     * Administration AS in route
     */
    public static function routeAdministrationAs() {
        return \Administration::getLanguage() . '.';
    }

    /*
     * Адреси за администраторските route
     */
    public static function routeAdministrationName($name) {
        if (!empty(\LaravelLocalization::setLocale())) {
            return \LaravelLocalization::setLocale() . '.' . $name;
        } else {
            return $name;
        }
    }

    /*
     * Route is in Administration  Dashboard?
     */
    public static function isDashboard() {
        if (!empty(\LaravelLocalization::setLocale())) {
            if (\Request::is(\LaravelLocalization::setLocale() . '/' . config('provision_administration.url_prefix'))) {
                return true;
            }
        } else {
            if (\Request::is(config('provision_administration.url_prefix'))) {
                return true;
            }
        }

        return false;
    }

}