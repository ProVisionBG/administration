<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?= Form::label($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

    @if($showField)
        <?php
        $emptyVal = $options['empty_value'] ? ['' => $options['empty_value']] : null;

        if (empty($options['attr']['id'])) {
            $options['attr']['id'] = str_random(20);
        }
        ?>
        <?= Form::select($name, (array)$emptyVal + $options['choices'], $options['selected'], $options['attr']) ?>
        @include('administration::components.fields.help_block')
        @push('js_scripts')
        <script type="text/javascript">
            /**
             @todo: Да се подават опции от form builder!
             */
            var options = {
                placeholder: '{{$options['empty_value']}}',
                allowClear: true,
            };
            if (!$('#<?=$options['attr']['id'];?>').data('select2')) {
                $('#<?=$options['attr']['id'];?>').select2(options);
            }

        </script>
        @endpush
    @endif

    @include('administration::components.fields.errors')

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
