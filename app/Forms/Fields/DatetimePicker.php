<?php
namespace ProVision\Administration\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class DatetimePicker extends FormField {
    protected function getTemplate() {
        return 'datetime_picker';
    }
}