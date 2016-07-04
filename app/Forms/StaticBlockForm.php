<?php
namespace ProVision\Administration\Forms;

class StaticBlockForm extends AdminForm {

    public function buildForm() {

        $this->add('key', 'text', [
            'label' => trans('administration::static_blocks.key'),
            'validation_rules' => [
                "required" => true,
                "minlength" => 2
            ]
        ]);

        $this->add('text', 'ckeditor', [
            'label' => trans('administration::static_blocks.text'),
            'validation_rules' => [
            ],
            'translate' => true
        ]);

        $this->add('visible', 'checkbox', [
            'label' => trans('administration::static_blocks.visible')
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