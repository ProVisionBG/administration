<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Forms;

class SettingsForm extends AdminForm
{
    public function buildForm()
    {
        $this->removeSeoInput('slug');
        $this->addSeoFields();

        $this->add('html_minify', 'checkbox', [
            'label' => 'HTML Minify',
            'help_block' => [
                'text' => 'Дали да минифицира HTML кода'
            ]
        ]);

        $this->add('ssl_enable', 'checkbox', [
            'label' => 'Redirect to SSL (https://)',
            'help_block' => [
                'text' => 'Only in production!'
            ]
        ]);

        $this->add('non_www_enable', 'checkbox', [
            'label' => 'Redirecto to non-WWW',
            'help_block' => [
                'text' => 'Only in production!'
            ]
        ]);

        $this->add('google_map_api_key', 'text', [
            'label' => 'Google maps API Key',
            'help_block' => [
                'text' => '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" title="Google Maps JavaScript API Documentation">Google Maps JavaScript API Documentation</a>'
            ]
        ]);

        /*
         * load settings of modules
         */
        $modules = \ProVision\Administration\Administration::getModules();
        if ($modules) {
            foreach ($modules as $moduleArray) {
                $module = new $moduleArray['administrationClass'];

                /*
                 * Колко полета има до момента добавени?
                 */
                $currentFieldsCount = count($this->getFields());

                //run module settings
                $module->settings($moduleArray, $this);

                /**
                 * Ако има новодобавени полета показваме хедъра
                 */
                if (count($this->getFields()) > $currentFieldsCount) {
                    $this->addAfter(array_keys($this->getFields())[$currentFieldsCount - 1], 'module_static_' . str_slug($moduleArray['name']), 'new_box', [
                        'title' => 'Module ' . $moduleArray['name']
                    ]);
                }
            }
        }


        $this->add('footer', 'admin_footer');
        $this->add('send', 'submit', [
            'label' => trans('administration::index.save'),
            'attr' => [
                'name' => 'save',
            ],
        ]);
    }
}
