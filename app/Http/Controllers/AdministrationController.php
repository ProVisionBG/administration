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

        return view('administration::index');
    }


    public function getLogin() {
        return view('administration::login');
    }

    public function postLogin() {
        return view('administration::login');
    }
}
