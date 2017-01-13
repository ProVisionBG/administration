<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers;

use Arcanedev\LogViewer\Facades\LogViewer;
use Auth;
use ProVision\Administration\Facades\Administration;

class AdministrationController extends BaseAdministrationController
{
    public function index()
    {
        if (!Auth::guard(config('provision_administration.guard'))->check()) {
            return redirect()->route('provision.administration.login');
        }

        if (!Auth::guard(config('provision_administration.guard'))->user()->can('administration-access')) {
            Auth::guard(config('provision_administration.guard'))->logout();

            return response()->view('administration::errors.403', ['permission' => 'administration-access'], 403);
        }

        Administration::setTitle(trans('administration::index.dashboard'));

        if (Administration::isInMaintenanceMode()) {
            $box = new \ProVision\Administration\Dashboard\HtmlBox();
            $box->setBoxClass('col-xs-12');
            $box->setHtml('<div class="callout callout-danger">
                <h4><i class="icon fa fa-ban"></i> Сайта е спрян!</h4>
                <p>Може да активирате сайта от <a href="' . route('provision.administration.systems.maintenance-mode') . '">тук</a>.</p>
              </div>');
            \Dashboard::add($box, 0);
        }

        /*
         * load dashboard of modules
         */
        $modules = \ProVision\Administration\Administration::getModules();
        if ($modules) {
            foreach ($modules as $module) {
                if (method_exists($module, 'dashboard')) {
                    $module->dashboard($module);
                }
            }
        }

        /*
         * total errors - log viewer
         */
        if (config('provision_administration.packages.log-viewer')) {
            $totalErrorCounts = LogViewer::total('error');

            $box = new \ProVision\Administration\Dashboard\LinkBox();
            $box->setTitle('Errors');
            $box->setValue($totalErrorCounts);
            if ($totalErrorCounts > 0) {
                $box->setBoxBackgroundClass('bg-red');
            } else {
                $box->setBoxBackgroundClass('bg-green');
            }
            $box->setIconClass('fa-bug');
            $box->setLink('View all errors', '/' . config('log-viewer.route.attributes.prefix'), ['target' => '_blank']);
            \Dashboard::add($box);
        }


        return view('administration::index');
    }
}
