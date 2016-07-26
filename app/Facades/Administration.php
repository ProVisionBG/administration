<?php

namespace ProVision\Administration\Facades;

use Illuminate\Support\Facades\Facade;

class Administration extends Facade {
    protected static function getFacadeAccessor() {
        return 'administration';
    }
}
