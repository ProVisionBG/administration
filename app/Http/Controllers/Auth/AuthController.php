<?php

namespace ProVision\Administration\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        //set admin default url
        //$this->redirectTo = config('provision_administration.url_prefix');
        $this->redirectTo = route('provision.administration.index');
    }

    protected function guard() {
        return Auth::guard('provision_administration');
    }
}
