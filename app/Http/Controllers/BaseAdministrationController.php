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
         * Boot modules menus with session access!
         * @todo: да се помисли дали е добре да е тук?!
         */
        $this->middleware(function ($request, $next) {
            $modules = \ProVision\Administration\Administration::getModules();
            if ($modules) {
                foreach ($modules as $moduleArray) {
                    $module = new $moduleArray['administrationClass'];
                    if (method_exists($module, 'menu')) {
                        $module->menu($moduleArray);
                        //set order
                        if (!empty($moduleArray['order'])) {
                            \AdministrationMenu::setLastOrder($moduleArray['order']);
                        }
                    }
                }
            }
            return $next($request);
        });


    }
}
