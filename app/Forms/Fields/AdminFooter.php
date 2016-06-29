<?php
namespace ProVision\Administration\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class AdminFooter extends FormField {

    protected function getTemplate() {
        return 'footer';
    }
    
}