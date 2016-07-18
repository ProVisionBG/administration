<?php
namespace ProVision\Administration\Http\Controllers;

use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilderTrait;


class BaseAdministrationController extends Controller {
    use FormBuilderTrait;

    public function __construct() {
        /*
         * Breadcrumbs::register('home', function($breadcrumbs)
         */
        \Breadcrumbs::register('admin_home', function ($breadcrumbs) {
            $breadcrumbs->push(trans('administration::index.home'), route('provision.administration.index'), ['icon' => 'fa-home']);
        });
    }

}
