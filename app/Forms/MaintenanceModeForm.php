<?php
namespace ProVision\Administration\Forms;

use File;
use ProVision\Administration\Administration;

class MaintenanceModeForm extends AdminForm {

    public function buildForm() {


        if (!Administration::isInMaintenanceMode()) {
            $this->add('message', 'text', [
                'label' => trans('administration::systems.maintenance-mode-note'),
                'validation_rules' => [
                    "required" => true,
                    "minlength" => 2
                ]
            ]);

            $this->add('footer', 'admin_footer');
            $this->add('send', 'submit', [
                'label' => trans('administration::systems.maintenance-mode-start'),
                'attr' => [
                    'name' => 'start'
                ]
            ]);
        } else {
            $this->add('send', 'submit', [
                'label' => trans('administration::systems.maintenance-mode-stop'),
                'attr' => [
                    'name' => 'stop'
                ]
            ]);
        }


    }


}