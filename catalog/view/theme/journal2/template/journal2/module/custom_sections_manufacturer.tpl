<div class="box custom-sections section-brand <?php echo implode(' ', $disable_on_classes); ?> <?php echo $single_class; ?> <?php echo $show_title_class; ?> <?php echo isset($gutter_on_class) ? $gutter_on_class : ''; ?>" id="cs-<?php echo $module; ?>" style="<?php echo isset($css) ? $css : ''; ?>">
    <?php if ($show_title): ?>
    <div class="box-heading box-sections box-block">
        <ul>
            <?php foreach ($sections as $section): ?>
            <?php if ($section['is_link']): ?>
            <li><a href="<?php echo $section['url']; ?>" <?php echo $section['target']; ?>><?php echo $section['section_name']; ?></a></li>
            <?php else: ?>
            <?php if (!count($section['items'])) continue; ?>
            <li><a href="javascript:;" data-option-value="section-<?php echo $section['section_class']; ?>"><?php echo $section['section_name']; ?></a></li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <div class="box-content">
        <div class="product-grid">
            <?php foreach ($items as $item) { ?>
            <div class="product-grid-item isotope-element <?php echo implode(' ', $item['section_class']); ?> <?php echo $grid_classes; ?>">
                <div class="product-wrapper" data-respond="start: 150px; end: 300px; interval: 20px;" style="<?php echo $image_bgcolor; ?>">
                    <?php if (isset($item['thumb'])) { ?>
                    <div class="image">
                        <a href="<?php echo $item['href']; ?>" style="<?php echo $image_border_css; ?>">
                            <img class="first-image" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" src="<?php echo $dummy_image; ?>"  data-src="<?php echo $item['thumb']; ?>" title="<?php echo $item['name']; ?>" alt="<?php echo $item['name']; ?>" />
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ($brand_name): ?>
                    <div class="product-details">
                        <div class="name"><a href="<?php echo $item['href']; ?>"><?php echo $item['name']; ?></a></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <script>
        (function(){
            var $isotope = $('#cs-<?php echo $module; ?> .product-grid');
            var $filters = $('#cs-<?php echo $module; ?> .box-heading a[data-option-value]');

            <?php if (!$this->journal2->html_classes->hasClass('mobile') && !$this->journal2->html_classes->hasClass('tablet')): ?>
            $isotope.each(function () {
                Journal.equalHeight($(this).find('.product-grid-item'), '.name');
            });
            <?php endif; ?>

            var $images = $('#cs-<?php echo $module; ?> .image img');
            $images.lazy();

            $isotope.imagesLoaded(function () {
                $isotope.isotope({
                    itemSelector: '.isotope-element',
                    layoutMode: 'sloppyMasonry'
                });
            });

            $filters.click(function () {
                var $this = $(this);
                if ($this.hasClass('selected')) {
                    return false;
                }
                $filters.removeClass('selected');
                $this.addClass('selected');
                $isotope.isotope({
                    filter: '.' + $this.attr('data-option-value')
                })
            });

            var default_section = '<?php echo $default_section; ?>';
            if (default_section !== '') {
                $('#cs-<?php echo $module; ?> .box-heading a[data-option-value="section-' + default_section + '"]').click();
            } else {
                $('#cs-<?php echo $module; ?> .box-heading a').first().click();
            }
        }());
    </script>
</div>
