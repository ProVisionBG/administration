<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */
/**
 * System status for remote checking
 */
//Route::get('/system-status', function () {
//    return response()->json([
//        'data' => [
//            'error_logs' => \Arcanedev\LogViewer\Facades\LogViewer::tree()
//        ]
//    ]);
//});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => Administration::routeMiddleware(),
], function () {
    Route::group([
        'namespace' => 'ProVision\Administration\Http\Controllers',
        'prefix' => config('provision_administration.url_prefix'),
        'as' => 'provision.administration.',
    ], function () {
        Route::get('/', [
            'as' => 'index',
            'uses' => 'AdministrationController@index',
        ]);

        // Authentication Routes...
        Route::get('login', [
            'as' => 'login',
            'uses' => 'Auth\LoginController@showLoginForm',
        ]);

        Route::post('login', [
            'as' => 'login_post',
            'uses' => 'Auth\LoginController@login',
        ]);

        Route::post('logout', 'Auth\LoginController@logout')->name('logout');

        // Registration Routes...
        //Route::get('register', 'Auth\RegisterController@showRegistrationForm');
        //Route::post('register', 'Auth\RegisterController@register');

        // Password Reset Routes...
        Route::get('password/reset', [
            'as' => 'password_reset',
            'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm',
        ]);

        Route::post('password/email', [
            'as' => 'password_email_post',
            'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail',
        ]);

        Route::get('password/reset/{token}', [
            'as' => 'password_reset_token',
            'uses' => 'Auth\ResetPasswordController@showResetForm',
        ]);

        Route::post('password/reset', [
            'as' => 'password_reset_post',
            'uses' => 'Auth\ResetPasswordController@reset',
        ]);

        Route::group([
            'middleware' => [
                'permission:administration-access',
            ],
        ], function () {
            /*
             * Administrators
             */
            Route::resource('administartors', 'Administrators\AdministratorsController', [
                'names' => [
                    'index' => 'administrators.index',
                    'edit' => 'administrators.edit',
                    'show' => 'administrators.edit',
                    'create' => 'administrators.create',
                    'store' => 'administrators.store',
                    'update' => 'administrators.update',
                    'destroy' => 'administrators.destroy',
                ],
            ]);

            /*
             * Administrator roles
             */
            Route::resource('administrators-roles', 'Administrators\AdministratorsRolesController', [
                'names' => [
                    'index' => 'administrators-roles.index',
                    'edit' => 'administrators-roles.edit',
                    'show' => 'administrators-roles.show',
                    'create' => 'administrators-roles.create',
                    'store' => 'administrators-roles.store',
                    'update' => 'administrators-roles.update',
                    'destroy' => 'administrators-roles.destroy',
                ],
            ]);

            /*
            * Static blocks
            */
            Route::resource('static-blocks', 'StaticBlocks\StaticBlocksController', [
                'names' => [
                    'index' => 'static-blocks.index',
                    'edit' => 'static-blocks.edit',
                    'show' => 'static-blocks.show',
                    'create' => 'static-blocks.create',
                    'store' => 'static-blocks.store',
                    'update' => 'static-blocks.update',
                    'destroy' => 'static-blocks.destroy',
                ],
            ]);

            /*
             * Settings
             */
            Route::resource('settings', 'SettingsController', [
                'namespace' => '',
                'as' => 'settings',
                'names' => [
                    'index' => 'settings.index',
                    // 'edit' => 'administrators-roles.edit',
                    // 'create' => 'administrators-roles.create',
                    'store' => 'settings.store',
                    //'update' => 'settings.update',
                    // 'destroy' => 'administrators-roles.destroy'
                ],
                'only' => [
                    'index',
                    'store',
                ],
            ]);

            /*
             * Systems
             */
            Route::group([
                'as' => 'systems.',
                'prefix' => 'systems',
            ], function () {
                Route::get('roles-repair', [
                    'as' => 'roles-repair',
                    'uses' => 'Systems\RolesRepairController@index',
                ]);

                Route::get('maintenance-mode', [
                    'as' => 'maintenance-mode',
                    'uses' => 'Systems\MaintenanceModeController@index',
                ]);

                Route::post('maintenance-mode-update', [
                    'as' => 'maintenance-mode-update',
                    'uses' => 'Systems\MaintenanceModeController@update',
                ]);
            });

            /*
             * Media Manager
             */
            Route::group([
                'as' => 'ajax.',
                'prefix' => 'ajax',
            ], function () {
                Route::post('save-order', [
                    'as' => 'save-order',
                    'uses' => 'AjaxController@saveOrder',
                ]);

                Route::post('save-switch', [
                    'as' => 'save-switch',
                    'uses' => 'AjaxController@saveQuickSwitch',
                ]);
            });

            /*
             * TinyMCE proxy
             */
            Route::get('tinymce/proxy', [
                'as' => 'tinymce.proxy',
                'uses' => 'TinyMCEController@proxy'
            ]);

            Route::post('tinymce/upload', [
                'as' => 'tinymce.upload',
                'uses' => 'TinyMCEController@upload'
            ]);
        });
    });
});
