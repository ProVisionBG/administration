<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($showField): ?>

    <?php
    if (!empty($options['attr']['class']) && $options['attr']['class'] == 'form-control') {
        $options['attr']['class'] = '';
    }
    $options['attr']['id'] = str_random(20);

    /*
     * Автоматично чекване
     */

    if (!isset($options['checked']) || is_array($options['checked'])) {
        //for translates
        if (!empty($options['translate'])) {
            if ($options['value'] === '') {
                $options['checked'] = (empty($options['default_checked']) ? false : $options['default_checked']);
            } elseif ($options['value'] == true) {
                $options['checked'] = true;
            } elseif ($options['value'] == false) {
                $options['checked'] = false;
            }
        }
    }

    echo Form::checkbox($name, $options['value'], (boolean)$options['checked'], $options['attr']);
    ?>

    @push('js_scripts')
    <script>
        $("#<?= $options['attr']['id'];?>").bootstrapSwitch({
            size: 'small',
            labelText: '{{$options['label']}}',
            onText: '{{trans('administration::index.yes')}}',
            offText: '{{trans('administration::index.no')}}',
            offColor: 'danger'
        });
    </script>
    @endpush

    @include('administration::components.fields.help_block')
    <?php endif; ?>

    @include('administration::components.fields.errors')

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
