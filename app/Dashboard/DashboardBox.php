<?php
namespace ProVision\Administration\Dashboard;

abstract class DashboardBox {

    protected $boxClass = 'col-md-3';

    abstract public function render();

    public function setBoxClass($class) {
        $this->boxClass = $class;
    }
}