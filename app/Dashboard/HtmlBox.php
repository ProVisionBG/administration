<?php

namespace ProVision\Administration\Dashboard;


class HtmlBox extends DashboardBox {

    private $html = '';

    public function setHtml($code) {
        $this->html = $code;
    }

    public function render() {
        return '<div class="' . $this->boxClass . '">' . $this->html . '</div>';
    }
}