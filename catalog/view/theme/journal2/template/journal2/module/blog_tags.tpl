<div class="box side-blog side-posts side-blog-tags" id="journal-blog-tags-<?php echo $module; ?>">
    <div class="box-heading"><?php echo $heading_title; ?></div>
    <div class="box-tag box-post">
        <div>
            <?php foreach ($tags as $tag): ?>
            <a href="<?php echo $tag['href']; ?>"><?php echo $tag['name']; ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>