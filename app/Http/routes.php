<?php

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect'
    ]
], function () {

    Route::group([
        'namespace' => 'ProVision\Administration\Http\Controllers',
        'prefix' => config('provision_administration.url_prefix'),
        'as' => 'provision.administration.'
    ], function () {

        Route::get('/', [
            'as' => 'index',
            'uses' => 'AdministrationController@index'
        ]);

    });

});