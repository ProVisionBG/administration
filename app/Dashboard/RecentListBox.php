<?php

namespace ProVision\Administration\Dashboard;


class RecentListBox extends DashboardBox {

    private $boxBackgroundClass = 'box-default';
    private $title = 'Recent list box';
    private $rowsContainer = [];
    private $notFoundRowsText = 'Data is empty';
    private $footerButton = '';

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setIconBoxBackgroundClass($class) {
        $this->boxBackgroundClass = $class;
    }

    public function setNotFoundRowsText($text) {
        $this->notFoundRowsText = $text;
    }

    public function addItem($title, $href = false, $desc = false, $tag = false, $image = false) {
        $this->rowsContainer[] = [
            'title' => $title,
            'href' => $href,
            'desc' => $desc,
            'tag' => $tag,
            'image' => $image
        ];
        return true;
    }

    public function setFooterButton($text, $href) {
        $this->footerButton = '<div class="box-footer text-center"><a href="' . $href . '" class="uppercase">' . $text . '</a></div>';
    }

    public function render() {
        return '<div class="' . $this->boxClass . '">
        <div class="box ' . $this->boxBackgroundClass . '">
            <div class="box-header with-border">
              <h3 class="box-title">' . $this->title . '</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="box-body">
              ' . $this->generateRows() . '
            </div>
            
              ' . $this->footerButton . '
            
            </div>
          </div>';
    }

    private function generateRows() {
        if (empty($this->rowsContainer)) {
            return $this->notFoundRowsText;
        }

        /*
         <ul class="products-list product-list-in-box">
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Samsung TV
                      <span class="label label-warning pull-right">$1800</span></a>
                        <span class="product-description">
                          Samsung 32" 1080p 60Hz LED Smart HDTV.
                        </span>
                  </div>
                </li>
              </ul>
         */

        $html = '<ul class="products-list product-list-in-box">';
        foreach ($this->rowsContainer as $item) {
            $html .= '
                <li class="item">
                  ' . ($item['image'] ? '<div class="product-img"><img src="' . $item['image'] . '" alt="' . $item['title'] . '"> </div>' : '') . '
                  <div class="product-info" ' . ($item['image'] ? '' : 'style="margin-left:0;"') . '>
                    <a href="' . ($item['href'] ? $item['href'] : 'javascript:void(0)') . '" class="product-title">
                    ' . $item['title'] . '
                      
                      ' . ($item['tag'] ? '<span class="label label-warning pull-right">' . $item['tag'] . '</span></a>' : '') . '
                        
                        <span class="product-description">
                          ' . $item['desc'] . '
                        </span>
                  </div>
                </li>';
        }
        return $html . '</ul>';
    }
}