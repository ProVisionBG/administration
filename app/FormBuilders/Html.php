<?php namespace Modules\Admin\FormBuilder;

use Kris\LaravelFormBuilder\Fields\FormField;

class Html extends FormField {

    protected function getTemplate()
    {
        return 'html';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['somedata'] = 'This is some data for view';

        return parent::render($options, $showLabel, $showField, $showError);
    }
}