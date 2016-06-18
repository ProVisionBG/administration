<?php
Route::group([
    'namespace' => 'ProVision\Administration\Controllers',
    'prefix' => config('provision_administration.url_prefix'),
    'as' => 'provision.administration.'
], function () {

    Route::get('/', [
        'as' => 'index',
        'uses' => 'AdministrationController@index'
    ]);

});