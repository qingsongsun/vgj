<?php foreach ($modules as $module) { ?>
<?php $css = $this->journal2->settings->get('module_' . $module['type']. '_' . $module['module_id']); ?>
<?php $class = $this->journal2->settings->get('module_' . $module['type']. '_' . $module['module_id'] . '_classes'); ?>
<div class="<?php echo $module['type']; ?> <?php echo $class; ?>" style="<?php echo $css; ?>"><?php echo $module['module']; ?></div>
<?php } ?>