<?php namespace ProVision\Administration\Http\Middleware;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class EntrustAuto {
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth) {
        $this->auth = Auth::guard('provision_administration');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param  $permissions
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $permission = str_ireplace([
            'provision.administration.'
        ], [
            ''
        ], \Route::currentRouteName());

        /*
         * repair permission for module
         */
        $permission = preg_replace('/^([a-z]{2}\.' . config('provision_administration.url_prefix') . '\.)/simx', '', $permission);

        if ($this->auth->guest() || !$this->auth->user()->can($permission)) {
            //abort(403, 'Require permission: ' . $permission);
            return response()->view("administration::errors.403", ['permission' => $permission], 403);
        }

        return $next($request);
    }
}
