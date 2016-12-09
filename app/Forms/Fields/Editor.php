<?php
namespace ProVision\Administration\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class Editor extends FormField {

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
        $options['attr']['class'] .= ' provision-editor';

        return parent::render($options, $showLabel, $showField, $showError);
    }

    protected function getTemplate() {
        return 'ckeditor';
    }
}