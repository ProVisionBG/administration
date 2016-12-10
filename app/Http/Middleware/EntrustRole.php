<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Middleware;

/*
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class EntrustRole
{
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = Auth::guard('provision_administration');
        // dd(Auth::guard('provision_administration')->user()->hasRole('admin'));
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param  $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {

        /*
         * Guest admin user
         */
        if ($roles == 'guest') {
            if (! $this->auth->guest()) {
                return redirect()->route('provision.administration.index');
            } else {
                return $next($request);
            }
        }

        if ($this->auth->guest() || ! $this->auth->user()->hasRole(explode('|', $roles))) {
            if ($request->is('*/'.config('provision_administration.url_prefix')) || $request->is(config('provision_administration.url_prefix').'*')) {
                return redirect()->route('provision.administration.login');
            } else {
                abort(403);
            }
        }

        return $next($request);
    }
}
