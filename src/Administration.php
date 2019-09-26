<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;

/**
 * Class Administration
 * @package ProVision\Administration
 */
class Administration
{
    /**
     * Get route in administration namespace
     *
     * @param string  $name
     * @param array   $parameters
     * @param boolean $absolute
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
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    public function guard(): StatefulGuard
    {
        return Auth::guard(config('administration.guard_name'));
    }
}
