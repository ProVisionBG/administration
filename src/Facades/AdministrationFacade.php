<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Facades;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Facade;

/**
 * Class AdministrationFacade
 * @package ProVision\Administration
 * @method static boolean routeInAdministration()
 * @method static StatefulGuard auth()
 * @method static string routeName(string $string)
 * @method static string route(string $name, array $parameters = [])
 * @method static StatefulGuard guard()
 */
class AdministrationFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'administration';
    }
}
