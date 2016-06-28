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
    private static $currentModuleName = 'Enter module name here!';

    /*
     * Current module sub name
     */
    private static $currentModuleSubName = '';

    /**
     * Set current module name for administration titles
     * @param $name
     */
    public static function setModuleName($name) {
        Administration::$currentModuleName = $name;
    }

    /*
     * Get current module name
     */
    public static function getModuleName() {
        return Administration::$currentModuleName;
    }

    /**
     * Set current module sub name for administration titles
     * @param $name
     */
    public static function setModuleSubName($name) {
        Administration::$currentModuleSubName = $name;
    }

    /*
        * Get current module sub name
        */
    public static function getModuleSubName() {
        return Administration::$currentModuleSubName;
    }
}