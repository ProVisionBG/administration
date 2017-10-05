<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

return [

    'version' => '0.4.0',
    //@todo: да го махна от тук!

    /*
     * Адрес към администрацията
     */
    'url_prefix' => 'admin',

    /*
     * Administration guard name
     */
    'guard' => 'provision_administration',

    /*
     * префикс за командите с artisan
     */
    'command_prefix' => 'admin',

    /*
     * Image sizes
     *
     * 'key' =>[
     *     'mode' => 'resize | fit', //required - http://image.intervention.io/api/resize - http://image.intervention.io/api/fit
     *     'width' => 100,
     *     'height' => 100,
     *     'aspectRatio' => true,
     *     'upsize' => true
     * ]
     */
    'image_sizes' => [
        'A' => [
            'mode' => 'resize',
            'width' => 200,
            'height' => 200,
        ],
    ],

    /*
     * Additional packages
     */
    'packages' => [
        'log-viewer' => true,
        //LogViewer: https://github.com/ARCANEDEV/LogViewer
    ],

    /**
     * Disable administration exception handler
     */
    'disable_administration_exception_handler' => false,
];
