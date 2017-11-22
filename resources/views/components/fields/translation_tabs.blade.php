<?php
if ($translationOpened === false) {
?>
<div class="col-xs-12">
    <div class="nav-tabs-custom nav-tabs-languages">
        <ul class="nav nav-tabs">
            <?php
            foreach (Administration::getTranslatableLocales() as $lang) {
                echo '<li>
                    <a href="#' . $lang . $translationTabSuffix . '" data-toggle="tab" aria-expanded="false"><span class="lang-sm" lang="' . $lang . '"></span> ' . $lang . '</a>
                  </li>';
            }
            ?>
        </ul>
        <?php
        } else{
        ?>
        <div class="tab-content">
            <?php
            foreach (Administration::getTranslatableLocales() as $lang) {
                echo '<div class="tab-pane" id="' . $lang . $translationTabSuffix . '">
            <div class="row">';
                foreach ($translationContainer as $field) {

                    if (empty($field->getOptions()['original_name'])) {
                        $field->setOptions(['original_name' => $field->getName()]);
                    }

                    //reset name with language prefix
                    $field->setName($lang . '[' . $field->getOptions()['original_name'] . ']');
                    $field->setOptions([
                        'label_attr' => [
                            'class' => $field->getOptions()['label_attr']['class'] .= ' lang-sm',
                            'lang' => $lang
                        ]
                    ]);

                    /*
                     * set translation value
                     */
                    $fieldName = $field->getOptions()['original_name'];
                    $field->setOptions([
                        'value' => ($form->getModel() && $form->getModel()->translate($lang) ? $form->getModel()->translate($lang)->$fieldName : '')
                    ]);

                    echo $field->render();
                }
                echo '</div></div>';
            }
            ?>
        </div>
    </div>
</div>
<?php
}
?>
