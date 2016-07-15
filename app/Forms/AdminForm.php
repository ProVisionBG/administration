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

    public function addSeoFields($required = false, $inputs = false) {

        if (!is_array($inputs)) {
            $inputs = [
                'slug',
                'meta_title',
                'meta_description',
                'meta_keywords'
            ];
        }

        foreach ($inputs as $input) {
            $this->add($input, 'text', [
                'label' => trans('administration::index.' . $input),
                'validation_rules' => [
                    "required" => $required
                ],
                'translate' => true
            ]);
        }

    }


}