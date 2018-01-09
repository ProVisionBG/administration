<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
        <?= Form::label($name, $options['label'], $options['label_attr']) ?>
        <div class="clearfix"></div>
    <?php endif; ?>

    <?php if ($showField): ?>
        <?php foreach ((array)$options['children'] as $child): ?>
            <?php if ($options['translate']) {
                $child->setName($name . '[]');
            } ?>
            <?php echo !empty($options['wrapper_children']) ? '<div class="wrapper_children ' . $options['wrapper_children'] . '">' : ''; ?>
            <?= $child->render(['selected' => $options['selected']], true, true, false) ?>
            <?php echo !empty($options['wrapper_children']) && $options['wrapper_children'] !== false ? '</div>' : ''; ?>
        <?php endforeach; ?>

        <?php include 'help_block.php' ?>

    <?php endif; ?>


    <?php include 'errors.php' ?>

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
