<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?= Form::label($name, $options['label'], $options['label_attr']) ?>

    @if(!empty($options['model']) && \Administration::isLoadedModule('visual-builder'))
        <a href="{{\Administration::route('visual_builder.index',['id'=> $options['model']->id ,'field'=>$name, 'model'=>get_class($options['model'])])}} "
           class="btn btn-xs btn-primary pull-right">Edit with
            VisualBuilder</a>
    @endif

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

