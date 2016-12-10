<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Forms;

use ProVision\Administration\Permission;

class RolesForm extends AdminForm
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('administration::administrators.group_key'),
            'validation_rules' => [
                'required' => true,
            ],
        ]);
        $this->add('display_name', 'text', [
            'label' => trans('administration::administrators.group_name'),
            'validation_rules' => [
                'required' => true,
            ],
        ]);

        $this->add('permissions', 'choice', [
            'choices' => Permission::pluck('display_name', 'id')->toArray(),
            'selected' => (! empty($this->model) ? @$this->model->perms->pluck('id', 'name')->toArray() : null),
            'expanded' => true,
            'multiple' => true,
            'wrapper_children' => 'col-lg-3 col-md-4 col-sm-6 col-xs-12',
            'label' => trans('administration::administrators.permissions'),
        ]);

        $this->add('footer', 'admin_footer');
        $this->add('send', 'submit', [
            'label' => trans('administration::index.save'),
            'attr' => [
                'name' => 'save',
            ],
        ]);
    }
}
