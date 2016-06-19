<?php

namespace ProVision\Administration\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Request;
use Auth;


class AdministrationController extends Controller {
    public function index() {


        if (!Auth::guard('provision_administration')->check() || !Auth::guard('provision_administration')->user()->hasRole('admin')) {
            return redirect()->route('provision.administration.login');
        }

        return view('administration::index');
    }


    public function getLogin() {
        return view('administration::login');
    }

    public function postLogin() {
        return view('administration::login');
    }
}
