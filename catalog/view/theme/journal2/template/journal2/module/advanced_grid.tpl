<div class="multi-modules-wrapper <?php echo isset($gutter_on_class) ? $gutter_on_class : ''; ?> <?php echo implode(' ', $disable_on_classes); ?>" style="<?php echo isset($css) ? $css : ''; ?>" id="multi-module-<?php echo $module; ?>">
    <div class="box multi-modules" style="<?php echo $module_spacing ? 'margin-right: -' . $module_spacing : ''; ?>">
        <?php foreach ($columns as $column): ?>
        <div class="multi-modules-column <?php echo $column['classes']; ?>" style="height: <?php echo $height; ?>px; <?php echo $module_spacing ? 'padding-right: ' . $module_spacing : ''; ?>">
            <?php $row_index = 0; foreach ($column['modules'] as $m): ?>
            <?php if ($row_index === count($column['modules']) - 1): ?>
            <div class="multi-modules-row <?php echo implode(' ', $m['class']); ?>" data-ratio="<?php echo $m['height']; ?>" style="height: <?php echo round($m['height'] * $height / 100 - $module_spacing); ?>px">
            <?php echo $m['content']; ?>
            </div>
            <?php else: ?>
            <div class="multi-modules-row <?php echo implode(' ', $m['class']); ?>" data-ratio="<?php echo $m['height']; ?>" style="height: <?php echo round($m['height'] * $height / 100 - $module_spacing); ?>px; <?php echo $module_spacing ? 'margin-bottom: ' . $module_spacing : ''; ?>">
                <?php echo $m['content']; ?>
            </div>
            <?php endif; ?>
            <?php $row_index++; endforeach; ?>
        </div>
        <?php endforeach; ?>
        <div class="clearfix"> </div>
    </div>

    <script>
        (function () {
            var $wrapper = $('#multi-module-<?php echo $module; ?>');
            var $columns = $('#multi-module-<?php echo $module; ?> .multi-modules-column');
            <?php if ($is_top_bottom): ?>
            var orig_width = parseInt('<?php echo $this->journal2->settings->get("site_width"); ?>', 10);
            <?php else: ?>
            var orig_width = parseInt('<?php echo $this->journal2->settings->get("site_width") - 240 * $this->journal2->settings->get("config_columns_count"); ?>', 10);
            <?php endif; ?>
            var orig_height = parseInt('<?php echo $height; ?>', 10);
            var module_spacing = parseInt('<?php echo $module_spacing; ?>', 10);

            var ratio = orig_height * 1.0 / orig_width;

            var wrapper_resize = function  () {
                $wrapper.find('.container-dimensions').remove();
                var height = Math.round(($wrapper.width()) * ratio);
                $columns.each(function () {
                    $(this).height(height);
                });
                $columns.each(function () {
                    var $column = $(this);
                    var $rows = $column.find('.multi-modules-row');
                    $rows.each(function () {
                      $(this).height(Math.round(($column.height() - module_spacing * $rows.length) * $(this).attr('data-ratio') / 100));
                        <?php if ($grid_dimensions && $this->journal2->html_classes->hasClass('is-admin')): ?>
                            $(this).append('<span class="container-dimensions">' + $(this).width() + 'x' + $(this).height() + '</span>');
                        <?php endif; ?>
                    });
                });
            };
            $(window).resize(wrapper_resize);
            wrapper_resize();
        })();
    </script>
</div>
