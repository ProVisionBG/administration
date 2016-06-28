<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>

    <?php
    if (empty($options['attr']['class']) || $options['attr']['class'] == 'form-control') {
        $options['attr']['class'] = 'btn btn-primary';
    }
    ?>

    <?= Form::button($options['label'], $options['attr']) ?>
    <?php include 'help_block.php' ?>

    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
