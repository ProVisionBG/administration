<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    @if(@$options['stop_switch']===true)
        {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
    @endif

    <?php if ($showField): ?>

    <?php
    if (!empty($options['attr']['class']) && $options['attr']['class'] == 'form-control') {
        $options['attr']['class'] = '';
    }
    $options['attr']['id'] = str_random(20);

    /*
     * Автоматично чекване
     */
    if (isset($options['translate']) && $options['translate']) {
        if ($options['value'] === '') {
            $options['checked'] = (!isset($options['default_checked']) ? true : $options['default_checked']);
        } elseif ($options['value'] == true) {
            $options['checked'] = true;
        } elseif ($options['value'] == false) {
            $options['checked'] = false;
        }
    }

    $options['value'] = (isset($options['default_value']) ? $options['default_value'] : $options['value']);

    /*
     * Чекбокс от choice с translate=>true
     */
    if (is_array($options['value']) || empty($options['value'])) {
        $options['value'] = str_ireplace($options['original_name'] . '_', '', $options['label_attr']['for']);

        /*
         * Автоматичен избор за choice с translate
         */
        if (is_array($options['selected']) && isset($options['selected'][$options['value']])) {
            if ($options['value'] == $options['selected'][$options['value']]) {
                $options['checked'] = true;
            } else {
                $options['checked'] = false;
            }
        }

    }

    /**
     * Името на полето да е от тип name[value] а не name[]
     */
    if (substr($name, -2, 2) == '[]') {
        $name = substr($name, 0, -2) . '[' . $options['value'] . ']';
    }

    echo Form::hidden($name, 0); //сетва стойност за неизбрано поле
    echo Form::checkbox($name, $options['value'], (boolean)$options['checked'], $options['attr']);
    ?>

    @if(@$options['stop_switch']!==true)
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
    @endif

    @include('administration::components.fields.help_block')
    <?php endif; ?>

    @include('administration::components.fields.errors')

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
