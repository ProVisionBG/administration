<?php
namespace ProVision\Administration;

use Illuminate\Support\Facades\Facade;
use ProVision\Administration\Dashboard\DashboardBox;


class Dashboard extends Facade {

    private static $dashboards = [];

    public static function add(DashboardBox $object) {
        static::$dashboards[] = $object;
    }

    public static function render() {
        if (empty(static::$dashboards)) {
            return false;
        }

        $html = '';
        foreach (static::$dashboards as $v) {
            $html .= $v->render();
        }

        return $html;
    }

}