<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use ProVision\Administration\Facades\AdministrationFacade;
use ProVision\Administration\Http\Controllers\Controller;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->redirectTo = AdministrationFacade::route('dashboard');
        $this->middleware('admin_guest:' . config('administration.guard_name'))->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return Response
     */
    public function showLoginForm()
    {
        return view('administration::auth.login');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     * @return boolean
     */
    protected function attemptLogin(Request $request)
    {
        $attempt = $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );

        if (!$attempt) {
            return $attempt;
        }

        if (!AdministrationFacade::auth()->user()->can(AdministrationFacade::routeName('dashboard'))) {
            AdministrationFacade::auth()->logout();
            return false;
        }

        return true;
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return AdministrationFacade::guard();
    }
}
