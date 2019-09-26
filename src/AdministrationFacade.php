<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration;

use Illuminate\Support\Facades\Facade;

/**
 * Class AdministrationFacade
 * @package ProVision\Administration
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
