<?php

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'web',
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

        Route::get('login', [
            'as' => 'login',
            'middleware' => ['guest'],
            'uses' => 'AdministrationController@getLogin'
        ]);

        Route::post('login', [
            'as' => 'login_post',
            'middleware' => ['guest'],
            'uses' => 'Auth\AuthController@postLogin'
        ]);

        Route::group([
            'middleware' => [
                'role:admin'
            ]
        ], function () {

            Route::get('logout', [
                'as' => 'logout',
                function () {
                    Auth::logout();
                    return Redirect::route_pro('admin.login');
                }
            ]);
            /*
            Route::get('media/{module}/{id}', [
                'as' => '.media',
                'uses' => 'AdminController@media'
            ]);

            Route::post('media/{module}/{id}', [
                'as' => '.media.store',
                'uses' => 'AdminController@mediaStore'
            ]);

            Route::DELETE('mediaDestroy/{id}', [
                'as' => '.media.destroy',
                'uses' => 'AdminController@mediaDestroy'
            ]);

            Route::put('mediaOrders/{imageId}', [
                'as' => '.media.orders',
                'uses' => 'AdminController@imagesOrder'
            ]);
            */
            //Route::get('/', 'AdministrationController@index');
        });

    });

});