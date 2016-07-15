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

class EntrustPermission {
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
    public function handle($request, Closure $next, $permissions) {
        \Debugbar::info($permissions);
        if ($this->auth->guest() || !$this->auth->user()->can(explode('|', $permissions))) {
            abort(403);
        }

        return $next($request);
    }
}
