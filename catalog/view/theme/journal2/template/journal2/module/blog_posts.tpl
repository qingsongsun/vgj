<div class="box post-module <?php echo $heading_title ? '' : 'no-heading'; ?> <?php echo implode(' ', $disable_on_classes); ?> <?php echo $carousel ? 'journal-carousel' : ''; ?> <?php echo isset($gutter_on_class) ? $gutter_on_class : ''; ?> <?php echo isset($arrows) && $arrows === 'top' ? 'arrows-top' : ''; ?>" id="journal-blog-posts-<?php echo $module; ?>" style="<?php echo isset($css) ? $css : ''; ?>">
    <div>
        <?php if ($heading_title): ?>
        <div class="box-heading"><?php echo $heading_title; ?></div>
        <?php endif; ?>
        <div class="box-post box-content posts <?php echo $display === 'list' ? 'blog-list-view' : ''; ?>">
        <?php foreach ($posts as $post): ?>
        <div class="<?php echo !$carousel ? $grid_classes : ''; ?>">
            <div class="post-wrapper">
                <?php if ($post['image']): ?>
                <?php if ($carousel): ?>
                <a class="post-image" href="<?php echo $post['href']; ?>"><img class="lazyOwl" data-src="<?php echo $post['image']; ?>" alt="<?php echo $post['name']; ?>" /></a>
                <?php else: ?>
                <a class="post-image" href="<?php echo $post['href']; ?>"><img src="<?php echo $post['image']; ?>" alt="<?php echo $post['name']; ?>"/></a>
                <?php endif; ?>
                <?php endif; ?>
                <div class="post-item-details" style="text-align:<?php echo $content_align; ?>">
                    <h2><a href="<?php echo $post['href']; ?>"><?php echo $post['name']; ?></a></h2>
                    <div class="comment-date">
                        <span class="p-author"><?php echo $post['author']; ?></span>
                        <span class="p-date"><?php echo $post['date']; ?></span>
                        <span class="p-comment"><?php echo $post['comments']; ?></span>
                    </div>
                    <?php if ($post['description'] !== FALSE): ?>
                    <div class="post-text"><span><?php echo $post['description']; ?></span></div>
                    <?php endif; ?>
                    <a class="post-view-more button" href="<?php echo $post['href']; ?>"><i class="post-button-left-icon"></i><?php echo $this->journal2->settings->get('blog_button_read_more', 'Read More'); ?><i class="post-button-right-icon"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php if ($carousel): ?>
<script>
    (function () {
        var opts = $.parseJSON('<?php echo json_encode($grid); ?>');

        jQuery("#journal-blog-posts-<?php echo $module; ?> .posts").owlCarousel({
            itemsCustom:opts,
            lazyLoad: true,
            autoPlay: <?php echo $autoplay ? $autoplay : 'false'; ?>,
            touchDrag: <?php echo $touch_drag ? 'true' : 'false'; ?>,
            stopOnHover: <?php echo $pause_on_hover ? 'true' : 'false'; ?>,
            navigation:true,
                scrollPerPage:true,
                navigationText : false,
            paginationSpeed: <?php echo $slide_speed; ?>,
        margin:15
    });
    <?php if ($arrows === 'side'): ?>
    $('#journal-blog-posts-<?php echo $module; ?> .owl-buttons').addClass('side-buttons');
    <?php endif; ?>

    <?php if ($arrows === 'none'): ?>
    $('#journal-blog-posts-<?php echo $module; ?> .owl-buttons').hide();
    <?php endif; ?>

    <?php if (!$bullets): ?>
    $('#journal-blog-posts-<?php echo $module; ?> .owl-pagination').hide();
    <?php endif; ?>
    })();
</script>
<?php endif; ?>
<script>
    Journal.equalHeight($("#journal-blog-posts-<?php echo $module; ?> .post-wrapper"), '.post-item-details h2 a');
    <?php if ($show_description): ?>
    Journal.equalHeight($("#journal-blog-posts-<?php echo $module; ?> .post-wrapper"), '.post-text span');
    <?php endif; ?>
</script>