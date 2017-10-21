<div class="multi-modules-wrapper <?php echo $disable_mobile; ?>" style="<?php echo isset($css) ? $css : ''; ?>" id="multi-module-<?php echo $module; ?>">
    <div class="box multi-modules" style="<?php echo $module_spacing ? 'margin-right: -' . $module_spacing : ''; ?>">
        <?php foreach ($columns as $column): ?>
        <div class="multi-modules-column <?php echo $column['classes']; ?>" style="<?php echo $module_spacing ? 'padding-right: ' . $module_spacing . ';' : ''; ?>">
            <?php echo $column['content']; ?>
        </div>
        <?php endforeach; ?>
        <div class="clearfix"> </div>
    </div>
</div>

