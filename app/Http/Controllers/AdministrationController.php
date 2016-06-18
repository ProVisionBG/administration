<?php

namespace ProVision\Administration\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Request;


class AdministrationController extends Controller {
    public function index() {

        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('administration::login');
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
