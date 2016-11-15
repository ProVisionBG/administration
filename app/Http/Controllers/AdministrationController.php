<?php

namespace ProVision\Administration\Http\Controllers;

use App\Http\Request;
use Auth;
use ProVision\Administration\Facades\Administration;


class AdministrationController extends BaseAdministrationController {
    public function index() {

        if (!Auth::guard('provision_administration')->check()) {
            return redirect()->route('provision.administration.login');
        }

        if (!Auth::guard('provision_administration')->user()->can('administration-access')) {
            Auth::guard('provision_administration')->logout();
            return response()->view("administration::errors.403", ['permission' => 'administration-access'], 403);
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

        return view('administration::index');
    }

}
