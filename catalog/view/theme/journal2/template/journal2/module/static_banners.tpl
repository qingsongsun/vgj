<div class="box <?php echo $carousel ? 'journal-carousel' : ''; ?> static-banners <?php echo implode(' ', $disable_on_classes); ?> <?php echo isset($gutter_on_class) ? $gutter_on_class : ''; ?>" style="<?php echo isset($css) ? $css : ''; ?>" id="static-banners-<?php echo $module; ?>" >
    <div>
    <?php if ($title): ?>
        <div class="box-heading"><?php echo $title; ?></div>
    <?php endif; ?>
    <div class="box-content <?php echo $carousel ? 'tab-content owl-carousel' : ''; ?>">
        <?php foreach ($sections as $section): ?>
        <div class="static-banner <?php echo $grid_classes; ?>">
            <?php if ($section['link']): ?>
            <a href="<?php echo $section['link']; ?>" <?php echo $section['target']; ?>> <span class="banner-overlay" style="<?php echo $image_border; ?>; <?php if ($bgcolor): ?> background-color: <?php echo $bgcolor; ?> <?php endif; ?>"><?php echo $icon; ?></span><img style="<?php echo $image_border; ?>" src="<?php echo $section['image']; ?>" width="<?php echo $section['image_width']; ?>" height="<?php echo $section['image_height']; ?>" alt="<?php echo $section['image_title']; ?>" /></a>
            <?php else: ?>
            <img style="<?php echo $image_border; ?>" src="<?php echo $section['image']; ?>" alt="<?php echo $section['image_title']; ?>" width="<?php echo $section['image_width']; ?>" height="<?php echo $section['image_height']; ?>" />
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if ($carousel): ?>
    <script>
        (function(){
            var opts = $.parseJSON('<?php echo json_encode($grid); ?>');

            jQuery("#static-banners-<?php echo $module; ?> .owl-carousel").owlCarousel({
                lazyLoad: true,
                itemsCustom: opts,
                autoPlay: <?php echo $autoplay ? $autoplay : 'false'; ?>,
                touchDrag: <?php echo $touch_drag ? 'true' : 'false'; ?>,
                stopOnHover: <?php echo $pause_on_hover ? 'true' : 'false'; ?>,
                items: 15,
                navigation: true,
                scrollPerPage: true,
                navigationText: false,
                paginationSpeed: <?php echo $slide_speed; ?>,
                margin: 20
            });

            <?php if (!$arrows): ?>
            $('#carousel-<?php echo $module; ?> .owl-buttons').hide();
            <?php endif; ?>

            <?php if (!$bullets): ?>
            $('#carousel-<?php echo $module; ?> .owl-pagination').hide();
            <?php endif; ?>
        })();
    </script>
    <?php endif; ?>
    </div>
</div>