<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Facades;

use Illuminate\Support\Facades\Facade;

class Administration extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Administration';
    }
}
