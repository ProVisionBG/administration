<?php

namespace ProVision\Administration\Dashboard;


class LinkBox extends DashboardBox {

    private $boxBackgroundClass = 'bg-aqua';
    private $iconClass = 'fa-cogs';
    private $title = 'Info box';
    private $value = '90%';
    private $link = '#';
    private $linkText = 'More info';

    public function setBoxBackgroundClass($class) {
        $this->boxBackgroundClass = $class;
    }

    public function setIconClass($class) {
        $this->iconClass = $class;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function setLink($value) {
        $this->link = $value;
    }

    public function setLinkText($value) {
        $this->linkText = $value;
    }

    public function render() {
        return '<div class="' . $this->boxClass . '">
            <div class="small-box ' . $this->boxBackgroundClass . '">
            <div class="inner">
              <h3>' . $this->value . '</h3>

              <p>' . $this->title . '</p>
            </div>
            <div class="icon">
              <i class="fa ' . $this->iconClass . '"></i>
            </div>
            <a href="' . $this->link . '" class="small-box-footer">' . $this->linkText . ' <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>';
    }
}