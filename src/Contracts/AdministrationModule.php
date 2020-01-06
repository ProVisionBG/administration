<?php

namespace ProVision\Administration\Contracts;

use Lavary\Menu\Builder;

/**
 * Interface AdministrationModule
 */
interface AdministrationModule
{

    /**
     * Init administration menu.
     *
     * @param Builder $moduleMenu
     * @return mixed
     */
    public function administrationMenu(Builder $moduleMenu);
}
