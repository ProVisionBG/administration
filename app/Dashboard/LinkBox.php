<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Dashboard;

class LinkBox extends DashboardBox
{
    private $boxBackgroundClass = 'bg-aqua';
    private $iconClass = 'fa-cogs';
    private $title = 'Link box';
    private $value = '90';
    private $link = '#';
    private $linkAttrbutes = [
        'class' => 'small-box-footer',
    ];
    private $linkText = 'More info';

    /**
     * Set box background class.
     *
     * @param $class
     */
    public function setBoxBackgroundClass($class)
    {
        $this->boxBackgroundClass = $class;
    }

    /**
     * Set box icon class (font awesome).
     * @param $class
     */
    public function setIconClass($class)
    {
        $this->iconClass = $class;
    }

    /**
     * Set box title.
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set box count/value.
     *
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Set box bottom link.
     *
     * @param $text
     * @param $url
     * @param array $attributes
     */
    public function setLink($text, $url, $attributes = [])
    {
        $this->link = $url;
        $this->linkText = $text;
        if (is_array($attributes)) {
            $this->linkAttrbutes = array_merge($this->linkAttrbutes, $attributes);
        }
    }

    /**
     * Render box.
     *
     * @return string
     */
    public function render()
    {
        $linkAttributes = '';
        foreach ($this->linkAttrbutes as $attr => $val) {
            $linkAttributes .= ' '.$attr.'="'.$val.'"';
        }

        return '<div class="'.$this->boxClass.'">
            <div class="small-box '.$this->boxBackgroundClass.'">
            <div class="inner">
              <h3>'.$this->value.'</h3>

              <p>'.$this->title.'</p>
            </div>
            <div class="icon">
              <i class="fa '.$this->iconClass.'"></i>
            </div>
            <a href="'.$this->link.'" '.$linkAttributes.'>'.$this->linkText.' <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>';
    }
}
