<?php
namespace ProVision\Administration\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class CKEditor extends FormField {

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true) {

        /*
         * add ckeditor class
         */
        if (empty($options['attr'])) {
            $options['attr'] = [];
        }
        if (empty($options['attr']['class'])) {
            $options['attr']['class'] = 'form-control ';
        }
        $options['attr']['class'] .= ' provision-ckeditor';

        return parent::render($options, $showLabel, $showField, $showError);
    }

    protected function getTemplate() {
        return 'ckeditor';
    }
}