<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Forms;

use Kris\LaravelFormBuilder\Form;

class AdminForm extends Form
{
    public function addSeoFields($required = false, $inputs = [])
    {
        if (empty($inputs) || !is_array($inputs)) {
            $inputs = [
                'slug' => [],
                'meta_title' => [
                    'attr' => [
                        'data-maxlength' => 70,
                        'data-minlength' => 35,
                    ]
                ],
                'meta_description' => [
                    'attr' => [
                        'data-maxlength' => 160,
                        'data-minlength' => 80,
                    ]
                ],
                'meta_keywords' => [],
            ];
        }

        foreach ($inputs as $input => $config) {
            $this->add($input, 'text', array_merge([
                'label' => trans('administration::index.' . $input),
                'validation_rules' => [
                    'required' => $required,
                ],
                'translate' => true,
            ], $config));
        }
    }
}
