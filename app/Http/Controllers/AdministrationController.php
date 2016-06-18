<?php

namespace ProVision\Administration\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Request;

class AdministrationController extends Controller {
    public function index() {
        return view('administration::login');
    }
}
