<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class AdminFooter extends FormField
{
    protected function getTemplate()
    {
        return 'footer';
    }
}
