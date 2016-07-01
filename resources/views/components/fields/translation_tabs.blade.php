<?php
if ($translationOpened === false) {
?>

<div class="nav-tabs-custom nav-tabs-languages">
    <ul class="nav nav-tabs">
        <?php
        foreach (Administration::getLanguages() as $key => $lang) {
            echo '<li class="' . (Administration::getLanguage() == $key ? 'active' : '') . '">
                    <a href="#' . $key . '" data-toggle="tab" aria-expanded="false"><span class="lang-sm" lang="' . $key . '"></span> ' . $lang['name'] . '</a>
                   </li>';
        }
        ?>
    </ul>
    <?php
    } else{
    ?>
    <div class="tab-content">
        <?php
        foreach (Administration::getLanguages() as $key => $lang) {
            echo '<div class="tab-pane ' . (Administration::getLanguage() == $key ? 'active' : '') . '" id="' . $key . '">';
            foreach ($translationContainer as $field) {

                if (empty($field->getOptions()['original_name'])) {
                    $field->setOptions(['original_name' => $field->getName()]);
                }

                //reset name with language prefix
                $field->setName($key . '[' . $field->getOptions()['original_name'] . ']');
                $field->setOptions([
                        'label_attr' => [
                                'class' => $field->getOptions()['label_attr']['class'] .= ' lang-sm',
                                'lang' => $key
                        ]
                ]);


                echo $field->render();
            }
            echo '</div>';
        }
        ?>
    </div>
</div>
<?php
}
?>
