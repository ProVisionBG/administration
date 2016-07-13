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
             * Administrators
             */
            Route::resource('administartors', 'Administrators\AdministratorsController', [
                'names' => [
                    'index' => 'administrators.index',
                    'edit' => 'administrators.edit',
                    'create' => 'administrators.create',
                    'store' => 'administrators.store',
                    'update' => 'administrators.update',
                    'destroy' => 'administrators.destroy'
                ]
            ]);

            /*
             * Administrator roles
             */
            Route::resource('administrators-roles', 'Administrators\AdministratorsRolesController', [
                'names' => [
                    'index' => 'administrators-roles.index',
                    'edit' => 'administrators-roles.edit',
                    'create' => 'administrators-roles.create',
                    'store' => 'administrators-roles.store',
                    'update' => 'administrators-roles.update',
                    'destroy' => 'administrators-roles.destroy'
                ]
            ]);

            /*
            * Static blocks
            */
            Route::resource('static-blocks', 'StaticBlocks\StaticBlocksController', [
                'names' => [
                    'index' => 'static-blocks.index',
                    'edit' => 'static-blocks.edit',
                    'create' => 'static-blocks.create',
                    'store' => 'static-blocks.store',
                    'update' => 'static-blocks.update',
                    'destroy' => 'static-blocks.destroy'
                ]
            ]);

            /*
             * Settings
             */
            Route::resource('settings', \Config::get('provision_administration.settings_controller'), [
                'names' => [
                    'index' => 'settings.index',
                    // 'edit' => 'administrators-roles.edit',
                    // 'create' => 'administrators-roles.create',
                    // 'store' => 'administrators-roles.store',
                    'update' => 'settings.update',
                    // 'destroy' => 'administrators-roles.destroy'
                ],
                'only' => [
                    'index',
                    'update'
                ]
            ]);

            /*
             * Systems
             */
            Route::group([
                'as' => 'systems.',
                'prefix' => 'systems'
            ], function () {
                Route::get('roles-repair', [
                    'as' => 'roles-repair',
                    'uses' => 'Systems\RolesRepairController@index'
                ]);
            });

            /*
             * Media Manager
             */
            Route::resource('media', 'MediaController', [
                'names' => [
                    'index' => 'media.index',
                    //                    'edit' => 'media.edit',
                    //                    'create' => 'media.create',
                    'store' => 'media.store',
                    'update' => 'media.update',
                    'destroy' => 'media.destroy',
                ]
            ]);


        });

    });

});