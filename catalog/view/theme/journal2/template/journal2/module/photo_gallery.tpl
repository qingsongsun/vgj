<div class="box journal-gallery <?php echo implode(' ', $disable_on_classes); ?> <?php echo $carousel ? 'journal-carousel' : ''; ?> <?php echo isset($arrows) && $arrows === 'top' ? 'arrows-top' : ''; ?>" id="journal-gallery-<?php echo $module; ?>" style="<?php echo isset($css) ? $css : ''; ?>">
   <div>
    <?php if ($title): ?>
    <div class="box-heading"><?php echo $title; ?></div>
    <?php endif; ?>
    <div class="box-content">
    <?php $index = 0; foreach ($images as $image): ?>
        <div class="gallery-thumb <?php echo !$carousel ? $grid_classes : ''; ?>">
            <a href="<?php echo $image['image']; ?>" style="<?php echo $image_border; ?>; <?php echo !$carousel && $index >= $thumbs_limit ? 'display: none' : ''; ?>" data-thumb="<?php echo $image['thumb']; ?>" class="swipebox" title="<?php echo $image['name']; ?>">
                <div class="item-hover"></div>
                <?php if ($carousel): ?>
                <img class="lazyOwl" width="<?php echo $thumbs_width; ?>" height="<?php echo $thumbs_height; ?>" data-src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['name']; ?>" />
                <?php else: ?>
                <img src="<?php echo $image['thumb']; ?>" width="<?php echo $thumbs_width; ?>" height="<?php echo $thumbs_height; ?>" alt="<?php echo $image['name']; ?>" />
                <?php endif; ?>
            </a>
        </div>
    <?php $index++; endforeach; ?>
    </div>
    <?php if ($carousel): ?>
    <?php
        $grid = Journal2Utils::getItemGrid($items_per_row, $this->journal2->settings->get('site_width', 1024), $this->journal2->settings->get('config_columns_count'));
    $grid = array(
        array(0,    $is_column ? 3 : (int)$grid['xs']),
        array(470,  $is_column ? 3 : (int)$grid['sm']),
        array(760,  $is_column ? 3 : (int)$grid['md']),
        array(980,  $is_column ? 3 : (int)$grid['lg']),
        array(1100, $is_column ? 3 : (int)$grid['xl'])
    );
    ?>
    <script>
        (function () {
            var opts = $.parseJSON('<?php echo json_encode($grid); ?>');

            jQuery("#journal-gallery-<?php echo $module; ?> .box-content").owlCarousel({
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
            $('#journal-gallery-<?php echo $module; ?> .owl-buttons').addClass('side-buttons');
            <?php endif; ?>

            <?php if ($arrows === 'none'): ?>
            $('#journal-gallery-<?php echo $module; ?> .owl-buttons').hide();
            <?php endif; ?>

            <?php if (!$bullets): ?>
            $('#journal-gallery-<?php echo $module; ?> .owl-pagination').hide();
            <?php endif; ?>
        })();
    </script>
    <?php endif; ?>
    <script>
        $(function(){
            $('#journal-gallery-<?php echo $module; ?> .swipebox').swipebox({
                <?php if ($carousel && $autoplay): ?>
                beforeOpen: function (){
                    jQuery("#journal-gallery-<?php echo $module; ?> .box-content").trigger('owl.stop');
                },
                afterClose: function (){
                    jQuery("#journal-gallery-<?php echo $module; ?> .box-content").trigger('owl.play', <?php echo $autoplay; ?>);
                }
                <?php endif; ?>
            });
        });
    </script>
</div>
</div>