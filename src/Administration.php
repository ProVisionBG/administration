<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * Class Administration
 * @package ProVision\Administration
 */
class Administration
{
    /**
     * Check request URL is in administration.
     *
     * @return boolean
     */
    public function routeInAdministration(): bool
    {
        //ако се ползва laravellocalization => 'hideDefaultLocaleInURL' => false,
        if (!empty(LaravelLocalization::setLocale())) {
            if (!\Request::is(LaravelLocalization::setLocale() . '/' . config('administration.url_prefix') . '*')) {
                return false;
            }
        } else {
            if (!\Request::is(config('administration.url_prefix') . '*')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get route in administration namespace
     *
     * @param string $name
     * @param array  $parameters
     * @return string
     */
    public function route(string $name, array $parameters = []): string
    {
        return route($this->routeName($name), $parameters);
    }

    /**
     * Get route name
     *
     * @param string $name
     * @return string
     */
    public function routeName(string $name): string
    {
        return config('administration.route_name_prefix') . '.' . $name;
    }

    /**
     * Get the auth.
     *
     * @return StatefulGuard
     */
    public function auth(): StatefulGuard
    {
        return $this->guard();
    }

    /**
     * Get the guard.
     *
     * @return StatefulGuard
     */
    public function guard(): StatefulGuard
    {
        return Auth::guard(config('administration.guard_name'));
    }
}
