<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration;

use Illuminate\Support\ServiceProvider;
use Lavary\Menu\Builder;
use Lavary\Menu\Facade as LaravyMenuFacade;
use Lavary\Menu\Item;
use Nwidart\Modules\Laravel\Module;
use ProVision\Administration\Contracts\AdministrationModule;
use ProVision\Administration\Facades\AdministrationFacade;

/**
 * Class Menu
 * @package ProVision\Administration
 */
class Menu
{
    /**
     * Modules menu container
     * @var Builder
     */
    private $modulesMenu;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        /*
         * check
         */
        if (!AdministrationFacade::routeInAdministration()) {
            dd('Menu init in non admin route');
        }

        $this->modulesMenu = LaravyMenuFacade::make('modulesMenu', function (Builder $menu) {

            $menu->raw(trans('administration::base.modules_menu_title'))
                ->attr([
                    'class' => 'nav-header'
                ])
                ->id('modulesMenu');
        });

        $this->systemMenu = LaravyMenuFacade::make('systemMenu', function (Builder $menu) {

            $menu->raw(trans('administration::base.system_menu_title'))
                ->attr([
                    'class' => 'nav-header'
                ])
                ->id('systemMenu');
        });
    }

    /**
     * Get module menu
     * @return Builder
     */
    public function getModulesMenu(): Builder
    {
        $this->loadModules();
        return $this->modulesMenu;
    }

    /**
     * @return void
     */
    public function loadModules()
    {
        /** @var array $modules */
        $modules = \Nwidart\Modules\Facades\Module::allEnabled();

        /** @var Module $module */
        foreach ($modules as $module) {
            $provider = $this->getModuleProvider($module);

            if ($provider && $provider instanceof AdministrationModule) {
                $this->modulesMenu
                    ->group([], function (Builder $menu) use ($provider) {
                        $provider->administrationMenu($menu);
                    });
            }
        }
    }

    /**
     * @param Module $module
     * @return ServiceProvider|null|AdministrationModule
     */
    private function getModuleProvider(Module $module)
    {
        return app()->getProvider(
            config('modules.namespace') . '\\'
            . $module->getName() . '\Providers\\'
            . $module->getName() . 'ServiceProvider'
        );
    }

    /**
     * Get system menu
     * @return Builder
     */
    public function getSystemMenu(): Builder
    {
        return $this->systemMenu;
    }

    /**
     * @param Item $item
     * @return mixed
     */
    public function getItemIcon(Item $item)
    {
        if ($item->data('icon')) {
            return $item->data('icon');
        }

        return 'fa-exclamation-triangle text-danger';
    }
}
