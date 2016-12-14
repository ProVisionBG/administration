<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
        <?= Form::label($name, $options['label'], $options['label_attr']) ?>
    <?php endif; ?>

    <?php if ($showField): ?>
    {{Form::textarea($name, $options['value'], $options['attr'])}}

    @include('administration::components.fields.help_block')
    <?php endif; ?>

    @include('administration::components.fields.errors')

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
