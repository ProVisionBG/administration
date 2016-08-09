<?php
namespace ProVision\Administration\Dashboard;

abstract class DashboardBox {

    protected $boxClass = 'col-lg-3 col-xs-6';

    abstract public function render();

    public function setBoxClass($class) {
        $this->boxClass = $class;
    }
}