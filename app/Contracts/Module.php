<?php
namespace ProVision\Administration\Contracts;


interface Module {

    /**
     * Init Dashboard boxes
     *
     * @param $module
     * @return mixed
     */
    public function dashboard($module);

    /**
     * Init administration routes
     *
     * @param $module
     * @return mixed
     */
    public function routes($module);

    /**
     * Init administration menu
     *
     * @param $module
     * @return mixed
     */
    public function menu($module);
}