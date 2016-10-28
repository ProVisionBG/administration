<?php
namespace ProVision\Administration;

use Illuminate\Support\Facades\Facade;
use ProVision\Administration\Dashboard\DashboardBox;


class Dashboard extends Facade {

    private static $dashboards = [];

    public static function add(DashboardBox $object, $index = 100) {
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