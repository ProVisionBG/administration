<?php

namespace ProVision\Administration\Http\Controllers\Systems;

use File;
use Kris\LaravelFormBuilder\FormBuilder;
use ProVision\Administration\Forms\MaintenanceModeForm;
use ProVision\Administration\Http\Controllers\BaseAdministrationController;
use Request;

class MaintenanceModeController extends BaseAdministrationController {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormBuilder $formBuilder) {

        $form = $formBuilder->create(MaintenanceModeForm::class, [
            'method' => 'POST',
            'url' => route('provision.administration.systems.maintenance-mode-update'),
            'role' => 'form',
            'id' => 'formID',
            //'model' => $user
        ]);

        \Administration::setTitle(trans('administration::systems.maintenance-mode'));

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::systems.maintenance-mode'), route('provision.administration.systems.maintenance-mode'));
        });

        return view('administration::empty-form', compact('form'));
    }

    public function update() {
        $request = \Request::all();

        if (isset($request['start'])) {
            File::put(storage_path('/framework/down-provision-administration'), json_encode([
                'time' => time(),
                'retry' => false,
                'message' => $request['message']
            ]));

            return \Redirect::route('provision.administration.systems.maintenance-mode')
                ->withInput()
                ->withErrors(['Сайта е спрян!']);
        } else {
            if (File::exists(storage_path('/framework/down-provision-administration'))) {
                File::delete(storage_path('/framework/down-provision-administration'));
            }
            return \Redirect::route('provision.administration.systems.maintenance-mode')
                ->withInput()
                ->withErrors(['Сайта е пуснат!']);
        }

        //dd($request);

    }


}
