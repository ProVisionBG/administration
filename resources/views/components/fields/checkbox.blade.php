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
    echo Form::checkbox($name, $options['value'], $options['checked'], $options['attr']);
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

<!--
    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?php if ($options['is_child']): ?>
        <label <?= $options['labelAttrs'] ?>><?= $options['label'] ?></label>
    <?php else: ?>
            <?= Form::label($name, $options['label'], $options['label_attr']) ?>
        <?php endif; ?>
    <?php endif; ?>
        -->

    @include('administration::components.fields.errors')

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
