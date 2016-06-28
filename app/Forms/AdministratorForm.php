<?php
namespace ProVision\Administration\Forms;

use ProVision\Administration\AdminPermission;
use ProVision\Administration\AdminRole;

class AdministratorForm extends AdminForm {

    public function buildForm() {

        $this->add('name', 'text', [
            'label' => trans('administration::administrators.name'),
            'rules' => 'required|min:5'
        ])
            ->add('email', 'text', [
                'label' => trans('administration::administrators.email'),
                'rules' => 'required|email'
            ])
            ->add('password', 'password', [
                'value' => '',
                'label' => trans('administration::administrators.password'),
                'attr' => [
                    "autocomplete" => "off"
                ]
            ])
            ->add('password_confirmation', 'password', [
                'value' => '',
                'label' => trans('administration::administrators.password_confirm'),
                'attr' => [
                    "autocomplete" => "off"
                ]
            ])
            ->add('roles', 'choice', [
                'choices' => AdminRole::lists('display_name', 'name')->toArray(),
                'selected' => [
                    'en',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => trans('administration::administrators.groups')
            ])
            ->add('permissions', 'choice', [
                'choices' => AdminPermission::lists('display_name', 'name')->toArray(),
                'selected' => [
                    'en',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => trans('administration::administrators.permissions')
            ])
            ->add('send', 'submit', [
                'label' => trans('administration::index.save'),
                'attr' => [
                    'name' => 'save'
                ],
            ], false, true);

    }


}