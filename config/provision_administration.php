<?php

return [

    'version' => '0.0.1',

    /*
     * Адрес към администрацията
     */
    'url_prefix' => 'admin',

    /*
     * префикс за командите с artisan
     */
    'command_prefix' => 'admin',

    /*
     * Settings controller
     */
    'settings_controller' => '\ProVision\Administration\Http\Controllers\SettingsController'
];