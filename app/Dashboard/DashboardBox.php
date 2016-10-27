<?php
namespace ProVision\Administration\Dashboard;

abstract class DashboardBox {

    protected $boxClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';

    abstract public function render();

    public function setBoxClass($class) {
        $this->boxClass = $class;
    }
}