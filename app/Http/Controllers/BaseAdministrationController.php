<?php
namespace ProVision\Administration\Http\Controllers;

use App\Http\Controllers\Controller;


class BaseAdministrationController extends Controller {
    public function __construct() {
        /*
         * Breadcrumbs::register('home', function($breadcrumbs)
         */
        \Breadcrumbs::register('admin_home', function ($breadcrumbs) {
            $breadcrumbs->push(trans('administration::index.home'), route('provision.administration.index'), ['icon' => 'fa-home']);
        });
    }

}
