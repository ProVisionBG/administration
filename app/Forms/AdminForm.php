<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Forms;

use Kris\LaravelFormBuilder\Form;

class AdminForm extends Form
{

    /**
     * Базови SEO Inputs
     * @var array
     */
    private $seoInputs = [
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

    public function addSeoFields($required = false, $inputs = [])
    {
        if (empty($inputs) || !is_array($inputs)) {
            $inputs = $this->seoInputs;
        }

        foreach ($inputs as $input => $config) {

            $default = [
                'label' => trans('administration::index.' . $input),
                'validation_rules' => [
                    'required' => $required,
                ],
                'translate' => true,
            ];

            if (is_array($config)) {
                $default = array_merge($default, $config);
            }

            $this->add($input, 'text', $default);
        }
    }

    /**
     * Маха SEO Input поле
     * @param $key
     */
    public function removeSeoInput($key)
    {
        if (isset($this->seoInputs[$key])) {
            unset($this->seoInputs[$key]);
        }
    }

    /**
     * Добавя SEO Input поле
     * @param $key
     * @param $config
     */
    public function addSeoInput($key, $config)
    {
        $this->seoInputs[$key] = $config;
    }
}
