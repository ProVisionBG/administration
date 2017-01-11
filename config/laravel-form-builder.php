<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

return [
    'defaults'      => [
        'wrapper_class'       => 'form-group col-sm-12',
        'wrapper_error_class' => 'has-error',
        'label_class'         => 'control-label',
        'field_class'         => 'form-control',
        'help_block_class'    => 'help-block',
        'error_class'         => 'text-danger',
        'required_class'      => 'required',
    ],
    // Templates
    'form'          => 'form',
    'text'          => 'text',
    'textarea'      => 'textarea',
    'button'        => 'button',
    'radio'         => 'radio',
    'checkbox'      => 'checkbox',
    'select'        => 'select',
    'choice'        => 'choice',
    'repeated'      => 'repeated',
    'child_form'    => 'child_form',
    'collection'    => 'collection',
    'static'        => 'static',

    // Remove the laravel-form-builder:: prefix above when using template_prefix
    'template_prefix'   => 'laravel-form-builder::',

    'default_namespace' => '',

    'custom_fields' => [
//        'datetime' => App\Forms\Fields\Datetime::class
    ],
];
