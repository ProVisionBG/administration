<?php
namespace ProVision\Administration\Forms;

use ProVision\Administration\Permission;

class RolesForm extends AdminForm {

    public function buildForm() {

        $this->add('name', 'text', [
            'label' => trans('administration::administrators.group_key'),
            'validation_rules' => [
                "required" => true
            ]
        ]);
        $this->add('display_name', 'text', [
            'label' => trans('administration::administrators.group_name'),
            'validation_rules' => [
                "required" => true
            ]
        ]);

        $this->add('permissions', 'choice', [
            'choices' => Permission::lists('display_name', 'id')->toArray(),
            'selected' => (!empty($this->model) ? @$this->model->perms->lists('id','name')->toArray() : null),
            'expanded' => true,
            'multiple' => true,
            'label' => trans('administration::administrators.permissions')
        ]);
        $this->add('footer', 'admin_footer');
        $this->add('send', 'submit', [
            'label' => trans('administration::index.save'),
            'attr' => [
                'name' => 'save'
            ]
        ]);

    }


}