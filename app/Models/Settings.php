<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Models;

use ProVision\Administration\AdminModel;

class Settings extends AdminModel
{
    public $module = 'settings';

    protected $fillable = [
        'key',
        'value'
    ];

    public function translate($locale)
    {
        $eloquent = new Settings();
        foreach ($this->getAttributes() as $k => $attribute) {
            $localeKey = $k;
            if (substr($localeKey, 0, 2) . '.' == $locale . '.') {
                $localeKey = substr($localeKey, 3);
                $eloquent->$localeKey = $attribute;
            }

        }
        return $eloquent;
    }
}
