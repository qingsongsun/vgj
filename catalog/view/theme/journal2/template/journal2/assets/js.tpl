Journal.notificationTimer = parseInt('<?php echo $this->journal2->settings->get('notification_hide'); ?>', 10);

Journal.quickviewText = '<?php echo $this->journal2->settings->get('quickview_button_text'); ?>';

Journal.scrollToTop = parseInt('<?php echo $this->journal2->settings->get('scroll_to_top', '1'); ?>', 10);

<?php if ($this->journal2->settings->get('sticky_header_style', 'default') === 'full'): ?>
Journal.stickyTopSpacing = 0;
<?php endif; ?>

<?php if ($this->journal2->settings->get('sticky_header_style', 'default') === 'menu'): ?>
Journal.stickyTopSpacing = 120;
<?php endif; ?>

Journal.BASE_HREF = 'url(' + $('base').attr('href') + ')';

$(document).ready(function () {

Journal.productPage();

<?php if($this->journal2->settings->get('product_page_tabs_position', 'on') === 'off'): ?>
$('.product-tabs').insertAfter('.product-info');
<?php endif; ?>

<?php if($this->journal2->settings->get('product_page_auto_update_price', '1') === '1'): ?>
Journal.enableProductOptions();
Journal.updatePrice = true;
<?php endif; ?>

<?php if (!Journal2Cache::$mobile_detect->isMobile()): ?>
Journal.enableSideBlocks();
<?php endif; ?>

<?php /* enable search autocomplete */ ?>
<?php if (!$this->journal2->html_classes->hasClass('mobile') && !$this->journal2->html_classes->hasClass('tablet') && $this->journal2->settings->get('search_autocomplete') === '1'):?>
Journal.searchAutoComplete();
<?php endif; ?>

<?php /* enable sticky header */ ?>
<?php if (!$this->journal2->html_classes->hasClass('mobile') && !$this->journal2->html_classes->hasClass('tablet') && $this->journal2->settings->get('sticky_header') === '1'):?>
Journal.enableStickyHeader();
<?php endif; ?>

<?php /* enable quickview */ ?>
<?php if ($this->journal2->settings->get('quickview_status') == '1' && !Journal2Cache::$mobile_detect->isMobile() && !Journal2Cache::$mobile_detect->isTablet() && !$this->journal2->html_classes->hasClass("ie8")): ?>
Journal.enableQuickView();
Journal.quickViewStatus = true;
<?php else: ?>
Journal.quickViewStatus = false;
<?php endif; ?>

<?php /* enable cloudzoom */ ?>
<?php if ($this->journal2->settings->get('product_page_cloud_zoom') == '1' && !Journal2Cache::$mobile_detect->isMobile() && !Journal2Cache::$mobile_detect->isTablet()): ?>
if ($('html').hasClass('product-page') || $('html').hasClass('quickview')) {
    Journal.enableCloudZoom('<?php echo $this->journal2->settings->get('product_page_cloud_zoom_inner', '0') === '0' ? 'standard' : 'inner' ?>');
}
<?php endif; ?>

<?php /* enable product page gallery */ ?>
<?php if ($this->journal2->settings->get('product_page_gallery') === '1'):?>
Journal.productPageGallery();
<?php else: ?>
$('.product-info .image a').css('cursor','default').click(function(){
    return false;
});
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_wishlist_icon_position', 'button') === 'image'): ?>
$('.product-list-item .image .wishlist:hidden, .product-list-item .image .compare:hidden, .product-grid-item .product-details .wishlist:hidden, .product-grid-item .product-details .compare:hidden').remove();
<?php endif; ?>

<?php if (!$this->journal2->html_classes->hasClass('mobile') && $this->journal2->settings->get('scroll_top') == '1'): ?>
$(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
            $('.scroll-top').fadeIn(200);
        } else {
            $('.scroll-top').fadeOut(200);
    }
});

$('.scroll-top').click(function () {
    $('html, body').animate({scrollTop: 0}, 700);
});
<?php endif; ?>

$('#top-modules > .hide-on-mobile').parent().addClass('hide-on-mobile');
$('#bottom-modules > .hide-on-mobile').parent().addClass('hide-on-mobile');

$('#top-modules .gutter-on').parent().addClass('gutter');
$('#bottom-modules .gutter-on').parent().addClass('gutter');

$(window).resize();

Journal.init();
});

/* Custom JS */
<?php echo $this->journal2->settings->get('custom_js'); ?>