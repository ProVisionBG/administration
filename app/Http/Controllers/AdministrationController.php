<?php

namespace ProVision\Administration\Http\Controllers;

use App\Http\Request;
use Auth;
use ProVision\Administration\Facades\Administration;


class AdministrationController extends BaseAdministrationController {
    public function index() {

        if (!Auth::guard('provision_administration')->check() || !Auth::guard('provision_administration')->user()->can('administration-access')) {
            return redirect()->route('provision.administration.login');
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
