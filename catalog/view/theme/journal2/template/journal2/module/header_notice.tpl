<div class="header-notice <?php echo $text_align; ?>-notice <?php echo implode(' ', $disable_on_classes); ?> <?php echo $float_icon; ?> <?php echo $fullwidth; ?>" id="journal-header-notice-<?php echo $module; ?>" style="<?php echo $css; ?>">
    <?php if ($icon_position === 'left'): ?>
    <?php echo $icon; ?>
    <?php endif; ?>

    <span class="notice-text"><?php echo $text; ?></span>

    <?php if ($icon_position === 'right'): ?>
    <?php echo $icon; ?>
    <?php endif; ?>

    <?php if ($close_button_type === 'icon'): ?>
    <button class="close-notice button x-icon"></button>
    <?php else: ?>
    <button class="close-notice button"><?php echo $close_button_text; ?></button>
    <?php endif; ?>

    <script>
        $('#journal-header-notice-<?php echo $module; ?> .close-notice').click(function () {
            $('#journal-header-notice-<?php echo $module; ?>').slideUp();
            <?php if ($do_not_show_again): ?>
            $.cookie('<?php echo $cookie_name; ?>', true, {
                expires: 365
            });
            <?php endif; ?>
        });

        <?php if ($global_style): ?>
        $('<style><?php echo implode(" ", $global_style); ?></style>').appendTo($('head'));
        <?php endif; ?>
    </script>
</div>