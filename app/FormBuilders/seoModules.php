<?php
namespace ProVision\Administration\FormBuilders;

use Distilleries\FormBuilder\FormValidator;

class SeoModules extends FormValidator {
    public function seoModule($lang, $translation) {
        $this->add($lang . '[meta_title]', 'text', [
            'label' => _('Meta Title'),
            'default_value' => @$translation['meta_title']
        ]);

        $this->add($lang . '[meta_description]', 'text', [
            'label' => _('Meta Description'),
            'default_value' => @$translation['meta_description']
        ]);

        $this->add($lang . '[meta_keywords]', 'text', [
            'label' => _('Meta Keyword'),
            'default_value' => @$translation['meta_keywords']
        ]);
    }
}