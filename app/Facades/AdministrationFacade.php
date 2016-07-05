<?php
namespace ProVision\Administration\Facades;

use Illuminate\Support\Facades\Facade;
use LaravelLocalization;
use ProVision\Administration\StaticBlock;

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
        return LaravelLocalization::setLocale();
    }

    public static function getStaticBlock($key) {
        $block = StaticBlock::where('key', $key)->first();
        if ($block) {
            return $block->text;
        }
        \Debugbar::error('static block not found: ' . $key);
    }

}