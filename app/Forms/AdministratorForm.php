<?php
namespace ProVision\Administration\Forms;

use ProVision\Administration\Role;

class AdministratorForm extends AdminForm {

    public function buildForm() {

        $this->add('name', 'text', [
            'label' => trans('administration::administrators.name'),
            'validation_rules' => [
                "required" => true,
                "minlength" => 2
            ]
        ]);

        $this->add('email', 'text', [
            'label' => trans('administration::administrators.email'),
            'validation_rules' => [
                "required" => true,
                "email" => true
            ]
        ]);

        $passwordValidatorRules = [
            "required" => true,
            "minlength" => 5
        ];
        if (!empty($this->model)) {
            $passwordValidatorRules['required'] = false;
        }

        $this->add('password', 'password', [
            'label' => trans('administration::administrators.password'),
            'value' => '',
            'attr' => [
                "autocomplete" => "off"
            ],
            'validation_rules' => $passwordValidatorRules
        ]);

        $confirmPasswordValidatorRules = [
            "required" => true,
            "minlength" => 5,
            "equalTo" => "#password"
        ];
        if (!empty($this->model)) {
            $confirmPasswordValidatorRules['required'] = false;
        }

        $this->add('password_confirmation', 'password', [
            'label' => trans('administration::administrators.password_confirm'),
            'validation_rules' => $confirmPasswordValidatorRules,
            'attr' => [
                "autocomplete" => "off"
            ]
        ]);
        $this->add('roles', 'choice', [
            'choices' => Role::pluck('display_name', 'id')->toArray(),
            'selected' => (!empty($this->model) ? @$this->model->roles->pluck('id')->toArray() : null),
            'expanded' => true,
            'multiple' => true,
            'label' => trans('administration::administrators.groups')
        ]);
//        $this->add('permissions', 'choice', [
//            'choices' => Permission::lists('display_name', 'name')->toArray(),
//            'selected' => (!empty($this->model) ? @$this->model->perms->lists('id')->toArray() : null),
//            'expanded' => true,
//            'multiple' => true,
//            'label' => trans('administration::administrators.permissions')
//        ]);
        $this->add('footer', 'admin_footer');
        $this->add('send', 'submit', [
            'label' => trans('administration::index.save'),
            'attr' => [
                'name' => 'save'
            ]
        ]);

    }


}