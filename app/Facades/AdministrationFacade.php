<?php
namespace ProVision\Administration\Facades;

use Illuminate\Support\Facades\Facade;

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
}