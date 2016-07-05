<?php if ($showStart) { ?>

<?php
$boxType = 'primary';
if (!empty($form->getData('type'))) {
    $boxType = $form->getData('type');
}

$formID = '';
if (!empty($formOptions['id'])) {
    $formID = $formOptions['id'];
} else {
    $formID = 'form-' . str_random(7);
    $formOptions['id'] = $formID;
}
?>

<div class="box box-<?= $boxType; ?>">

    @if (!empty($form->getData('title')))
        <div class="box-header with-border">
            <h3 class="box-title"><?= $form->getData('title'); ?></h3>
        </div>
    @endif

    {{Form::open($formOptions)}}
    <div class="box-body">
        <?php } ?>

        <?php if ($showFields) {
        $translationContainer = []; //translate fields container
        $translationTabSuffix=''; //suffix

        $translationOpened = false;

        foreach ($fields as $field) {
            if (!in_array($field->getName(), $exclude)) {

                /*
                 * set required flag
                 */
                if (!empty($field->getOptions()['validation_rules']['required']) && $field->getOptions()['validation_rules']['required'] == true) {
                    $field->setOptions(['rules' => 'required']);
                }

                /*
                 * check translation state is continues
                 */
                if (empty($field->getOptions()['translate'])) {
                    $field->setOptions(['translate' => false]);
                }
                if ($field->getOptions()['translate'] !== $translationOpened) {
                /*
                 * show translation tabs
                 */
                if ($translationOpened === false && count($translationContainer)==0) {
                    //open translations
                    $translationTabSuffix=str_random(7);
                    ?>
                    @include('administration::components.fields.translation_tabs')
                    <?php
                } else {
                    //close translations & render fields
                    ?>
                    @include('administration::components.fields.translation_tabs')
                    <?php
                    $translationContainer = [];
                    $translationTabSuffix='';
                }
                    $translationOpened = !$translationOpened;
                }

                if ($translationOpened) {
                    $translationContainer[] = $field;
                } else {
                    $translationOpened = false;
                    echo $field->render();
                }
            }
        }
        }

        ?>

        @if ($showEnd)
    </div>
    <?= Form::close() ?>
</div>

@push('bottom_js')
<script>
    $(function () {
        $("#<?=$formID;?>").validate({
            rules: {
                <?php if ($showFields) {
                        foreach ($fields as $field) {
                        if (!in_array($field->getName(), $exclude) && !empty($field->getOptions()['validation_rules'])) { ?>
                "<?= $field->getName() ?>":
                <?=json_encode($field->getOptions()['validation_rules']);?>,
                <?php }
                }
                } ?>
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                //element.parent().removeClass('has-success').addClass('has-error');
                error.insertAfter(element).addClass('help-block');
            },
            success: function (label) {
                label.parent().removeClass('has-error').addClass('has-success');
            },
            highlight: function (element, errorClass) {
                $(element).parent().removeClass('has-success').addClass('has-error');
            }
        });
    });
</script>
@endpush

@endif
