<?php
namespace ProVision\Administration;

use Illuminate\Support\Facades\Facade;
use ProVision\Administration\Dashboard\DashboardBox;


class Dashboard extends Facade {

    private static $dashboards = [];

    public static function add(DashboardBox $object, $index = false) {
        if (count(static::$dashboards) == 0) {
            $index = 100;
        }

        if (!$index) {
            dd(max(array_keys(static::$dashboards)));
        }

        static::$dashboards[$index] = $object;
    }

    public static function render() {
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