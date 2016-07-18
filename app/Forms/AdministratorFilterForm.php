<?php
namespace ProVision\Administration\Forms;

class AdministratorFilterForm extends AdminForm {

    public function buildForm() {

        $this->add('name', 'text', [
            'label' => trans('administration::administrators.name')
        ]);

        $this->add('email', 'text', [
            'label' => trans('administration::administrators.email')
        ]);

        $this->add('deleted', 'checkbox', [
            'label' => trans('administration::administrators.only-deleted')
        ]);

        $this->add('all-users', 'checkbox', [
            'label' => trans('administration::administrators.all-users')
        ]);

    }


}