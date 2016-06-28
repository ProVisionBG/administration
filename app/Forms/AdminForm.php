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
}