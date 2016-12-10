<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Dashboard;

class InfoBox extends DashboardBox
{
    private $iconBoxBackgroundClass = 'bg-aqua';
    private $iconClass = 'fa-cogs';
    private $title = 'Info box';
    private $value = '90%';

    public function setIconBoxBackgroundClass($class)
    {
        $this->iconBoxBackgroundClass = $class;
    }

    public function setIconClass($class)
    {
        $this->iconClass = $class;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function render()
    {
        return '<div class="'.$this->boxClass.'">
            <div class="info-box">
                <span class="info-box-icon '.$this->iconBoxBackgroundClass.'"><i class="fa '.$this->iconClass.'"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text">'.$this->title.'</span>
                  <span class="info-box-number">'.$this->value.'</span>
                </div>
              </div>
          </div>';
    }
}
