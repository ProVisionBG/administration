<?php
namespace ProVision\Administration\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class AddressPicker extends FormField {
    protected function getTemplate() {
        return 'address_picker';
    }

    protected function getDefaults() {
        return [
            'default_value' => [
                'lat' => 42.69770819999999,
                'lng' => 23.321867500000053,
                'street' => '',
                'city' => '',
                'country' => '',
                'state' => '',
                'default' => '',
            ]
        ];
    }
}