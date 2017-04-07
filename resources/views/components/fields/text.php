<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
        <?= Form::label($name, $options['label'], $options['label_attr']) ?>
    <?php endif; ?>

    <?php if ($showField): ?>
        <?= Form::input($type, $name, $options['value'], $options['attr']) ?>

        <?php
        if ($type == 'file') {
            if (!empty($options['value'])) {
                if (empty($options['path'])) {
                    echo '<p class="help-block">Attached file: ' . $options['value'] . '</p>';
                } else {
                    echo '<p class="help-block">Attached file: <a target="_blank" href="' . $options['path'] . '/' . $options['value'] . '">' . $options['value'] . '</a></p>';
                }
            }

        }
        ?>

        <?php include 'help_block.php' ?>
    <?php endif; ?>

    <?php include 'errors.php' ?>

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
