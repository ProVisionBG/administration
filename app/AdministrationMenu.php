<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Lavary\Menu\Menu;

class AdministrationMenu
{

    /**
     * Menu instance
     *
     * @var Menu
     */
    protected $menu;

    /*
     *
     */
    protected $last;

    /**
     * Initializing the menu builder
     */
    public function __construct()
    {
        $menu = new Menu();
        $this->menu = $menu->make('ProVisionAdministrationMenu', function () {
        });

        /*
        * MAIN
        */
        $this->menu->add(trans('administration::index.main_navigation'), ['nickname' => 'navigation'])
            ->data('header', true)
            ->data('order', 1);

        //home
        $this->menu->add(trans('administration::index.home'), [
            'route' => 'provision.administration.index',
            'nickname' => 'home',
        ])
            ->data('icon', 'home')
            ->data('order', 2);

        /*
         * MODULES
         */
        $this->menu->add(trans('administration::index.modules'), ['nickname' => 'modules'])->data('header', true)->data('order', 1000);

        /*
         * SYSTEM
         */
        $this->menu->add(trans('administration::index.system-settings'), ['nickname' => 'system-settings'])
            ->data('header', true)
            ->data('order', 10000);
    }

    /**
     * @param $title
     * @param array $options
     */
    public function addSystem($title, $options = [], $callback = false)
    {
        $this->repairOptions($options);
        $this->last = $this->menu->add($title, $options)->data('order', 1000001);
        $this->setData($this->last, $options);

        if (is_callable($callback)) {
            // Registering the items
            call_user_func($callback, $this);
        }

        return $this;
    }

    /**
     * Set default options
     *
     * @param $options
     * @return array
     */
    private function repairOptions(&$options)
    {

        $defaultOptions = [
            'icon' => 'caret-right'
        ];

        /*
         * Слагане на иконка на база пътя
         */
        if (!empty($options['route']) && empty($options['icon'])) {
            $routeArray = explode('.', $options['route']);
            $end = end($routeArray);
            if (!empty($end)) {
                switch ($end) {
                    case 'index':
                        $defaultOptions['icon'] = 'list';
                        break;
                    case 'create':
                        $defaultOptions['icon'] = 'plus';
                        break;
                }
            }
        }

        $options = array_merge($defaultOptions, $options);

        return $options;
    }

    /**
     * @param $options
     */
    private function setData($item, $options)
    {
        foreach ($options as $key => $option) {
            if ($key == 'target') {
                $item->link->attr($key, $option);
            } else {
                $item->data($key, $option);
            }
        }
    }

    /**
     * Add Module
     * @param $title
     * @param array $options
     * @return $this
     */
    public function addModule($title, $options = [], $callback = false)
    {
        $this->repairOptions($options);
        $this->last = $this->menu->add($title, $options)->data('order', 1001);
        $this->setData($this->last, $options);

        if (is_callable($callback)) {
            //dd($callback);
            call_user_func($callback, $this);
        }

        return $this;
    }

    /**
     * @param $title
     * @param array $options
     * @return $this
     */
    public function addItem($title, $options = [], $callback = false)
    {
        $this->repairOptions($options);
        $lastItem = $this->last->add($title, $options);
        $this->setData($lastItem, $options);

        if (is_callable($callback)) {
            // Registering the items
            $this->last = $lastItem;
            call_user_func($callback, $this);

        }

        return $this;
    }

    public function addSetting($title, $options = [])
    {
        //return $this->menuSettings->add($title, $options);
    }

    public function get()
    {
//        dd($this->menu->items->merge($this->menuModules->items));
        //$this->menu->items->merge($this->menuModules->items);
        //dd($this->menu);
        return $this->menu->sortBy('order', 'asc')->roots();
    }

    /**
     * Подреждане на менюто при boot
     * @param $order
     */
    public function setLastOrder($order)
    {
        $this->last->data('order', $order);
    }

}
