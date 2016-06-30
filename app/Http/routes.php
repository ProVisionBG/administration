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
            'middleware' => ['role:guest'],
            'uses' => 'AdministrationController@getLogin'
        ]);

        Route::post('login', [
            'as' => 'login_post',
            'middleware' => ['role:guest'],
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
                    Auth::guard('provision_administration')->logout();
                    return Redirect::route('provision.administration.login');
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

            Route::resource('administartors', 'AdministratorsController', [
                'names' => [
                    'index' => 'administrators.index',
                    'edit' => 'administrators.edit',
                    'create' => 'administrators.create',
                    'store' => 'administrators.store',
                    'update' => 'administrators.update',
                    'destroy' => 'administrators.destroy'
                ]
            ]);

            Route::resource('administrators-roles', 'AdministratorsRolesController', [
                'names' => [
                    'index' => 'administrators-roles.index',
                    'edit' => 'administrators-roles.edit',
                    'create' => 'administrators-roles.create',
                    'store' => 'administrators-roles.store',
                    'update' => 'administrators-roles.update',
                    'destroy' => 'administrators-roles.destroy'
                ]
            ]);

            Route::group([
                'as' => 'systems.',
                'prefix' => 'systems'
            ], function () {
                Route::get('roles-repair', [
                    'as' => 'roles-repair',
                    'uses' => 'Systems\RolesRepairController@index'
                ]);
            });


        });

    });

});