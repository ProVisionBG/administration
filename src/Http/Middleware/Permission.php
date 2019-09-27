<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use ProVision\Administration\AdministrationFacade as Administration;
use Route;

class Permission
{
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = Administration::auth();
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param  $permissions
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
         * check request is in administration
         */
        if (!Administration::routeInAdministration()) {
            return $next($request);
        }

        $permission = Route::currentRouteName();

        /*
         * check permission exist in db
         */
        $permissionObject = \Spatie\Permission\Models\Permission::where('name', $permission)
            ->where('guard_name', config('administration.guard_name'))
            ->first();

        if (!$permissionObject) {
            /*
             * Несъществува такъв пермишън...
             */
            \Debugbar::info('Permission not found: ' . $permission);

            return $next($request);
        }

        \Debugbar::info('Auto check permission: ' . $permission);

        if ($this->auth->guest() || !$this->auth->user()->can($permission)) {
            \Debugbar::error('Permission error: ' . $permission);
            return response()->view('administration::errors.403', ['permission' => $permission], 403);
        }

        return $next($request);
    }
}
