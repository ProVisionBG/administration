<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Models;

use ProVision\Administration\AdminModel;

class Settings extends AdminModel {
    public $module = 'settings';

    protected $fillable = [
        'key',
        'value'
    ];

    public function translate($locale) {
        $eloquent = new Settings();
        foreach ($this->getAttributes() as $localeKey => $attribute) {
            $e = explode('.', $localeKey);
            if ($e[0] == $locale) {
                $eKey = $e[1];
                $eloquent->$eKey = $attribute;
            }

        }
        return $eloquent;
    }
}
