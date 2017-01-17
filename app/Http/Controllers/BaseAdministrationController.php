<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers;

use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class BaseAdministrationController extends Controller
{
    use FormBuilderTrait;

    public function __construct()
    {
        /*
         * Breadcrumbs::register('home', function($breadcrumbs)
         */
        if (!\App::runningInConsole()) {
            \Breadcrumbs::register('admin_home', function ($breadcrumbs) {
                $breadcrumbs->push(trans('administration::index.home'), route('provision.administration.index'), ['icon' => 'fa-home']);
            });
        }

        /*
         * load menu of modules
         */
        $modules = \ProVision\Administration\Administration::getModules();
        if ($modules) {
            foreach ($modules as $moduleArray) {
                $module = new $moduleArray['administrationClass'];
                if (method_exists($module, 'menu')) {
                    $module->menu($moduleArray);
                }
            }
        }


    }
}
