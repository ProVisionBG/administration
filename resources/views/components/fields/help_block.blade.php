<?php if ($options['help_block']['text'] && !$options['is_child']): ?>
<<?= $options['help_block']['tag'] ?> <? if (!empty($options['help_block']['attr'])) {
    foreach ($options['help_block']['attr'] as $atr => $val) {
        echo ' ' . $atr . '=' . $val;
    }
}?>>
<?= $options['help_block']['text'] ?>
</<?= $options['help_block']['tag'] ?>>
<?php endif; ?>
