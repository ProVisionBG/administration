<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Illuminate\Support\Facades\Facade;
use ProVision\Administration\Dashboard\DashboardBox;

class Dashboard extends Facade
{
    private static $dashboards = [];

    public static function add(DashboardBox $object, $index = false)
    {
        if (count(static::$dashboards) == 0) {
            $index = 100;
        }

        if ($index == false) {
            $index = max(array_keys(static::$dashboards)) + 1;
        }

        if (isset(static::$dashboards[$index])) {
            die('Box index duplicate: '.$index);
        }

        static::$dashboards[$index] = $object;
    }

    public static function render()
    {
        if (empty(static::$dashboards)) {
            return false;
        }

        ksort(static::$dashboards);

        $html = '';
        foreach (static::$dashboards as $v) {
            $html .= $v->render();
        }

        return $html;
    }
}
