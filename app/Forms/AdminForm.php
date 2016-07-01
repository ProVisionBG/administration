<?php
namespace ProVision\Administration\Forms;

use Kris\LaravelFormBuilder\Form;

class AdminForm extends Form {
    public function __construct() {
        // $this->addCustomField('admin_text', \ProVision\Administration\Forms\Fields\AdminText::class);
    }

    public function getLanguages() {
        $allLang = [];
        foreach (\LaravelLocalization::getSupportedLocales() as $key => $lang) {
            $allLang[$key] = $lang['name'];
        }
        return $allLang;
    }

    public function addSeoFields($lang = false, $required = false) {

        $inputs = [
            'meta_title',
            'meta_description',
            'meta_keywords'
        ];

        foreach ($inputs as $input) {
            $metaInputKey = $input;
            if ($lang !== false) {
                $metaInputKey = $lang . '[' . $input . ']';
            }

            $this->add($metaInputKey, 'text', [
                'label' => trans('administration::index.' . $input),
                'validation_rules' => [
                    "required" => $required
                ]
            ]);
        }


    }
}