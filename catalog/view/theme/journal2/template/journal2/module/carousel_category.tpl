<div class="box journal-carousel carousel-category <?php echo implode(' ', $disable_on_classes); ?> <?php echo $show_title_class; ?> <?php echo isset($gutter_on_class) ? $gutter_on_class : ''; ?>" id="carousel-<?php echo $module; ?>" style="<?php echo isset($css) ? $css : ''; ?>">
    <div>
    <?php if ($show_title): ?>
    <div class="htabs box-heading <?php echo $single_class; ?>">
        <?php $index=0; foreach ($sections as $section): ?>
        <?php if ($section['is_link']): ?>
        <a href="<?php echo $section['url']; ?>" <?php echo $section['target']; ?>><?php echo $section['section_name']; ?></a>
        <?php else: ?>
        <?php if (!count($section['items'])) continue; ?>
        <a href="#carousel-<?php echo $module; ?>-<?php echo $index; ?>" class="atab"><?php echo $section['section_name']; ?></a>
        <?php endif; ?>
        <?php $index++; endforeach; ?>
    </div>
    <?php endif; ?>
    <?php $index=0; foreach ($sections as $section): ?>
    <?php if (!count($section['items'])) continue; ?>
    <div id="carousel-<?php echo $module; ?>-<?php echo $index; ?>" class="owl-carousel tab-content box-content">
        <?php foreach ($section['items'] as $item) { ?>
        <div class="product-grid-item isotope-element <?php echo implode(' ', $item['section_class']); ?>">
            <div class="product-wrapper" data-respond="start: 150px; end: 300px; interval: 20px;" style="<?php echo $image_bgcolor; ?>">
                <?php if (isset($item['thumb'])) { ?>
                <div class="image">
                    <a href="<?php echo $item['href']; ?>" style="<?php echo $image_border_css; ?>">
                        <img class="lazyOwl first-image" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" src="<?php echo $dummy_image; ?>" data-src="<?php echo $item['thumb']; ?>" title="<?php echo $item['name']; ?>" alt="<?php echo $item['name']; ?>" />
                    </a>
                </div>
                <?php } ?>
                <div class="product-details">
                    <div class="name"><a href="<?php echo $item['href']; ?>"><?php echo $item['name']; ?></a></div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php $index++; endforeach; ?>

    <script>
        (function(){
            var opts = $.parseJSON('<?php echo json_encode($grid); ?>');

            jQuery("#carousel-<?php echo $module; ?> .owl-carousel").owlCarousel({
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

            <?php if ($arrows === 'side'): ?>
            $('#carousel-<?php echo $module; ?> .owl-buttons').addClass('side-buttons');
            <?php endif; ?>

            <?php if ($arrows === 'none'): ?>
            $('#carousel-<?php echo $module; ?> .owl-buttons').hide();
            <?php endif; ?>

            <?php if (!$bullets): ?>
            $('#carousel-<?php echo $module; ?> .owl-pagination').hide();
            <?php endif; ?>

            $('#carousel-<?php echo $module; ?> .htabs a.atab').tabs();

            <?php if ($autoplay): ?>
            $('#carousel-<?php echo $module; ?> .htabs a.atab').click(function () {
                var current = $(this).attr('href');
                $('#carousel-<?php echo $module; ?> .htabs a.atab').each(function () {
                    var href = $(this).attr('href');
                    if (current === href) {
                        jQuery(href).data('owlCarousel').play();
                    } else {
                        jQuery(href).data('owlCarousel').stop();
                    }
                });
            });
            <?php endif; ?>

            <?php if (!$this->journal2->html_classes->hasClass('mobile') && !$this->journal2->html_classes->hasClass('tablet')): ?>
            Journal.equalHeight($('#carousel-<?php echo $module; ?> .product-grid-item'), '.name');
            <?php endif; ?>

            var default_section = '<?php echo $default_section; ?>';
            if (default_section) {
                $('#carousel-<?php echo $module; ?> .htabs a.atab[href="#carousel-<?php echo $module; ?>-' + default_section + '"]').click();
            } else {
                $('#carousel-<?php echo $module; ?> .htabs a.atab').first().click();
            }
        })();
    </script>
  </div>
</div>
