<?php foreach ($items as $item): ?>
<?php if ($item['href']): ?>
<a href="<?php echo $item['href']; ?>" <?php echo $item['class']; ?><?php echo $item['target']; ?>><?php echo $item['icon_left']; ?><span class="top-menu-link"><?php echo $item['name']; ?></span><?php echo $item['icon_right']; ?></a>
<?php else: ?>
<span <?php echo $item['class']; ?><?php echo $item['target']; ?>><?php echo $item['icon_left']; ?><?php echo $item['name']; ?><?php echo $item['icon_right']; ?></span>
<?php endif; ?>
<?php endforeach; ?>