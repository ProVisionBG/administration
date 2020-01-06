<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Facades;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Facade;
use Lavary\Menu\Builder;
use Lavary\Menu\Menu as LavaryMenu;
use Nwidart\Modules\Laravel\Module;
use Spatie\Menu\Menu;

/**
 * Class MenuFacade
 * @package ProVision\Administration\Facades
 * @method static void setModuleMenu(Module $module, Menu $menu)
 * @method static Builder getModulesMenu()
 */
class MenuFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'administration-menu';
    }
}
