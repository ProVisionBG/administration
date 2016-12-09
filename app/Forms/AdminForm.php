<?php
namespace ProVision\Administration\Forms;

use Kris\LaravelFormBuilder\Form;

class AdminForm extends Form {

    public function addSeoFields($required = false, $inputs = []) {

        if (empty($inputs) || !is_array($inputs)) {
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