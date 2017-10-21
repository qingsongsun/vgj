<div class="box journal-carousel carousel-product <?php echo implode(' ', $disable_on_classes); ?> <?php echo $show_title_class; ?> <?php echo isset($gutter_on_class) ? $gutter_on_class : ''; ?>" id="carousel-<?php echo $module; ?>" style="<?php echo isset($css) ? $css : ''; ?>">
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
        <?php foreach ($section['items'] as $product) { ?>
        <div class="product-grid-item display-<?php echo $this->journal2->settings->get('product_grid_wishlist_icon_display');?> <?php echo $this->journal2->settings->get('product_grid_button_block_button');?> <?php echo $product['classes']; ?>">
            <div class="product-wrapper <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>" style="<?php echo $image_bgcolor; ?>">
                <?php if (isset($product['thumb'])) { ?>
                <div class="image">
                    <a href="<?php echo $product['href']; ?>" <?php if(isset($product['thumb2']) && $product['thumb2']): ?> class="has-second-image" style="<?php echo $image_border_css; ?>;background: url('<?php echo $product['thumb2']; ?>') no-repeat;" <?php else: echo $image_border_css; endif; ?>>
                        <img class="lazyOwl first-image" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" src="<?php echo $dummy_image; ?>" data-src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                    </a>
                    <?php foreach ($product['labels'] as $label => $name): ?>
                    <?php if ($label === 'outofstock'): ?>
                    <img class="outofstock" <?php echo Journal2Utils::getRibbonSize($this->journal2->settings->get('out_of_stock_ribbon_size')); ?> style="position: absolute; top: 0; left: 0" src="<?php echo Journal2Utils::generateRibbon($name, $this->journal2->settings->get('out_of_stock_ribbon_size'), $this->journal2->settings->get('out_of_stock_font_color'), $this->journal2->settings->get('out_of_stock_bg')); ?>" alt="" />
                    <?php else: ?>
                    <span class="label-<?php echo $label; ?>"><b><?php echo $name; ?></b></span>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if($this->journal2->settings->get('product_grid_wishlist_icon_position') === 'image' && $this->journal2->settings->get('product_grid_wishlist_icon_display', '') === 'icon'): ?>
                        <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_wishlist; ?>"><i class="wishlist-icon"></i><span class="button-wishlist-text"><?php echo $button_wishlist;?></span></a></div>
                        <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_compare; ?>"><i class="compare-icon"></i><span class="button-compare-text"><?php echo $button_compare;?></span></a></div>
                    <?php endif; ?>
                </div>
                <?php } ?>
                <div class="product-details">
                    <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                    <div class="description"><?php echo $product['description']; ?></div>
                    <?php if ($product['price']) { ?>
                    <div class="price">
                        <?php if (!$product['special']) { ?>
                        <?php echo $product['price']; ?>
                        <?php } else { ?>
                        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new" <?php echo isset($product['date_end']) && $product['date_end'] ? "data-end-date='{$product['date_end']}'" : ""; ?>><?php echo $product['special']; ?></span>
                        <?php } ?>
                        <?php if ($product['tax']) { ?>
                        <hr>
                        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <?php if ($product['rating']) { ?>
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if ($product['rating'] < $i) { ?>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <hr>
                    <?php if (Journal2Utils::isEnquiryProduct($this, $product['product_id'])): ?>
                    <div class="cart enquiry-button">
                        <a href="javascript:Journal.openPopup('<?php echo $this->journal2->settings->get('enquiry_popup_code'); ?>', '<?php echo $product['product_id']; ?>');" data-clk="addToCart('<?php echo $product['product_id']; ?>');" class="button hint--top" data-hint="<?php echo $this->journal2->settings->get('enquiry_button_text'); ?>"><?php echo $this->journal2->settings->get('enquiry_button_icon') . '<span class="button-cart-text">' . $this->journal2->settings->get('enquiry_button_text') . '</span>'; ?></a>
                    </div>
                    <?php else: ?>
                    <div class="cart <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>">
                        <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button hint--top" data-hint="<?php echo $button_cart; ?>"><i class="button-left-icon"></i><span class="button-cart-text"><?php echo $button_cart; ?></span><i class="button-right-icon"></i></a>
                    </div>
                    <?php endif; ?>
                    <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_wishlist; ?>"><i class="wishlist-icon"></i><span class="button-wishlist-text"><?php echo $button_wishlist;?></span></a></div>
                    <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_compare; ?>"><i class="compare-icon"></i><span class="button-compare-text"><?php echo $button_compare;?></span></a></div>
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
            Journal.equalHeight($('#carousel-<?php echo $module; ?> .product-grid-item'), '.description');
            <?php endif; ?>

            var default_section = '<?php echo $default_section; ?>';
            if (default_section) {
                $('#carousel-<?php echo $module; ?> .htabs a.atab[href="#carousel-<?php echo $module; ?>-' + default_section + '"]').click();
            } else {
                $('#carousel-<?php echo $module; ?> .htabs a.atab').first().click();
            }

            <?php /* enable countdown */ ?>
            <?php if ($this->journal2->settings->get('show_countdown', 'never') !== 'never'): ?>
            $('#carousel-<?php echo $module; ?> .product-grid-item > div').each(function () {
                var $new = $(this).find('.price-new');
                if ($new.length && $new.attr('data-end-date')) {
                    $(this).find('.image').append('<div class="countdown"></div>');
                }
                Journal.countdown($(this).find('.countdown'), $new.attr('data-end-date'));
            });
            <?php endif; ?>
        })();
    </script>
    </div>
</div>
