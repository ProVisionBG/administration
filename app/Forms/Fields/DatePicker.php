<?php
namespace ProVision\Administration\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class DatePicker extends FormField {
    protected function getTemplate() {
        return 'date_picker';
    }
}