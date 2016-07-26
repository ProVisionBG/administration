<?php
namespace ProVision\Administration;

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
        return \Menu::get('ProVisionAdministrationMenu');
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

    public static function routePrefix() {
        return \LaravelLocalization::setLocale();
    }

    public static function routeAs() {
        return \Administration::getLanguage() . '.';
    }

}