/* Control Panel Settings */
<?php if (is_array($selectors)): foreach ($selectors as $selector): ?>
<?php if (count($selector['properties'])): ?>
<?php echo $selector['selector'] . "{" . implode($selector['properties'], ';') . "}" . PHP_EOL; ?>
<?php endif; ?>
<?php endforeach; endif; ?>


/* Swipebox Loader */
<?php if ($this->journal2->settings->get('ajax_loader')): ?>
#swipebox-slider .slide {
  background-image: url('image/<?php echo $this->journal2->settings->get('ajax_loader'); ?>');
}
.mfp-iframe-scaler iframe{
  background-image: url('image/<?php echo $this->journal2->settings->get('ajax_loader'); ?>');
  background-repeat: no-repeat;
  background-position: center;
}
.social{
  background-image: url('image/<?php echo $this->journal2->settings->get('ajax_loader'); ?>');
}
<?php endif; ?>


<?php if ($this->journal2->settings->get('leading_element')): ?>
.home-page #container:before{
  content: url('image/<?php echo $this->journal2->settings->get('leading_element'); ?>');
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_page_qty_status', 'on') === 'off'): ?>
.product-info .right .cart div .qty{
display:none;
}
.product-info .right .cart div .button{
width:100%;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('hide_cart', 'off') === 'on'): ?>
/*Hide Add to cart*/
.journal-cart{
    display:none;
}
<?php endif; ?>

/* Site width */
#container, #header, #footer, .bottom-footer > div, .bottom-footer.boxed-bar {
   max-width: <?php echo $this->journal2->settings->get('site_width', 1024); ?>px;
}


.extended-container:before{
  display:<?php echo $this->journal2->settings->get('breadcrumb_status'); ?>;
}

<?php if($this->journal2->settings->get('product_page_options_sold_count_position', 'none') === 'inline-block'): ?>
.product-sold-count.ps-left{
    display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_page_title_overflow', 'on') === 'off'): ?>
.product-page .heading-title{
    white-space:normal;
    height:auto;
    min-height:40px;
    line-height:100%;
    padding-bottom:12px;
    padding-top:12px;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('category_page_title_overflow', 'on') === 'off'): ?>
.category-page .heading-title{
    white-space:normal;
    height:auto;
    min-height:40px;
    line-height:100%;
    padding-bottom:12px;
    padding-top:12px;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('responsive_design') === '1'): ?>
@media only screen and (max-width:<?php echo $this->journal2->settings->get('site_width') + 20;?>px) {
 .journal-header-center .journal-search, .journal-header-center .journal-links{
    padding-left: 15px;
  }
 .journal-header-center .journal-cart, .journal-header-center .journal-secondary{
    padding-right: 15px;
  }
}
@media only screen and (max-width:760px) {
 .journal-header-center .journal-search, .journal-header-center .journal-links{
    padding-left: 0;
  }
 .journal-header-center .journal-cart, .journal-header-center .journal-secondary{
    padding-right: 0;
  }
}
<?php endif; ?>


<?php if($this->journal2->settings->get('product_compare_link_status', 'on') === 'off'): ?>
.product-filter .product-compare{
    display:none;
}
<?php endif; ?>



<?php if($this->journal2->settings->get('hide_category_image', '1') === '0'): ?>
.category-info .image{
display:none;
}
<?php endif; ?>

/*Notification Position*/
<?php if($this->journal2->settings->get('notification_position', 'top-right') === 'top-left'): ?>
.ui-pnotify{
  left:20px;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('notification_position', 'top-right') === 'top-center'): ?>
.ui-pnotify{
    left:50%;
    margin-left:-150px;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('notification_position', 'top-right') === 'top-right'): ?>
.ui-pnotify{
right:20px;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('notification_position', 'top-right') === 'center'): ?>
.ui-pnotify{
        left:50%;
        top:50% !important;
        margin-left:-150px;
        margin-top:-60px;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('notification_shadow', '0') === '1'): ?>
.ui-pnotify{
        box-shadow:0px 1px 12px rgba(0, 0, 0, 0.2);
}
<?php endif; ?>



<?php if($this->journal2->settings->get('notification_show_close', 'hover') === 'hover'): ?>
.ui-pnotify:hover .ui-pnotify-closer{
opacity:1;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('notification_show_close', 'hover') === 'always'): ?>
.ui-pnotify-closer{
opacity:1;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('notification_show_close', 'hover') === 'never'): ?>
.ui-pnotify-closer:hover{
display:none;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('breadcrumb_align', 'left') === 'left'): ?>
.breadcrumb{
        text-align:left;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('breadcrumb_align', 'left') === 'center'): ?>
.breadcrumb{
        text-align:center
}
<?php endif; ?>

<?php if($this->journal2->settings->get('breadcrumb_align', 'left') === 'right'): ?>
.breadcrumb{
text-align:right;
}
<?php endif; ?>


/* Product Grid */

<?php if($this->journal2->settings->get('product_grid_shadow', 'never') === 'hover'): ?>
.product-wrapper:hover{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_shadow', 'never') === 'always'): ?>
.product-wrapper{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_shadow', 'never') === 'never'): ?>
.product-wrapper{
  box-shadow: none;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('cs_product_grid_shadow', 'never') === 'hover'): ?>
.custom-sections.section-product .product-wrapper:hover{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('cs_product_grid_shadow', 'never') === 'always'): ?>
.custom-sections.section-product .product-wrapper{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('cs_product_grid_shadow', 'never') === 'never'): ?>
.custom-sections.section-product .product-wrapper{
  box-shadow: none;
}
<?php endif; ?>



<?php if($this->journal2->settings->get('carousel_product_grid_shadow', 'never') === 'hover'): ?>
.journal-carousel.carousel-product .product-wrapper:hover{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('carousel_product_grid_shadow', 'never') === 'always'): ?>
.journal-carousel.carousel-product .product-wrapper{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('carousel_product_grid_shadow', 'never') === 'never'): ?>
.journal-carousel.carousel-product .product-wrapper{
  box-shadow: none;
}
<?php endif; ?>



<?php if($this->journal2->settings->get('carousel_brand_product_grid_shadow', 'never') === 'hover'): ?>
.journal-carousel.carousel-brand .product-wrapper:hover{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('carousel_brand_product_grid_shadow', 'never') === 'always'): ?>
.journal-carousel.carousel-brand .product-wrapper{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('carousel_brand_product_grid_shadow', 'never') === 'never'): ?>
.journal-carousel.carousel-brand .product-wrapper{
  box-shadow: none;
}
<?php endif; ?>



<?php if($this->journal2->settings->get('product_grid_latest_label_status', 'block') === 'none'): ?>
.product-grid-item .image .label-latest + .label-sale{
  top: 5px;
  margin-top: 0;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('product_grid_button_icon_display', 'text') === 'text'): ?>
.product-grid-item .cart .button-left-icon:before{
display:none;
}
<?php endif; ?>

.enquiry-button .button i:before{
  color:<?php echo $this->journal2->settings->get('product_grid_button_icon:color');?>;
}

footer .contacts > div > span:hover a i{
  background-color:<?php echo $this->journal2->settings->get('contacts_icon_bg_hover');?>;
}

<?php if($this->journal2->settings->get('product_grid_button_icon_display', 'text') === 'icon'): ?>
.product-grid-item .cart .button-left-icon:before{
 float:none;
}
.product-grid-item .cart .button-right-icon:before{
 margin-left:0;
}
.product-grid-item .cart .button-cart-text{
  display:none;
}
.product-grid-item .cart .button[data-hint]:after,
.product-grid-item .cart .hint--top:before{
  display:block;
}
.product-grid-item .cart .hint--top:before{
  border-top-color: <?php echo $this->journal2->settings->get('product_grid_button_tooltip_bg_color');?>;
}
.product-grid-item .cart .hint--right:before{
  border-right-color: <?php echo $this->journal2->settings->get('product_grid_button_tooltip_bg_color');?>;
}
.product-grid-item .cart .hint--left:before{
  border-left-color: <?php echo $this->journal2->settings->get('product_grid_button_tooltip_bg_color');?>;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_button_icon_display', 'text') === 'both'): ?>
.product-grid-item .cart .button-left-icon:before{
  margin-right: 6px;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_button_icon_position', 'left') === 'left'): ?>
.product-grid-item .cart .button-right-icon{
display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_button_icon_position', 'left') === 'right'): ?>
.product-grid-item .cart .button-left-icon{
 display:none;
}
.button-right-icon:before{
  margin-left: 6px;
}
<?php endif; ?>



/* BLOG */

<?php if($this->journal2->settings->get('post_grid_button_icon_position', 'left') === 'right' || $this->journal2->settings->get('posts_grid_button_icon_position', 'left') === 'right'): ?>
.post-button-left-icon{
 display:none;
}
.post-button-right-icon{
 display:inline;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('post_grid_shadow', 'never') === 'hover'): ?>
.post-wrapper:hover{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20) !important;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('post_grid_shadow', 'never') === 'always'): ?>
.post-wrapper{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20) !important;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('post_grid_shadow', 'never') === 'never'): ?>
.post-wrapper{
  box-shadow: none !important;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('posts_grid_shadow', 'never') === 'hover'): ?>
.box.post-module .post-wrapper:hover{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('posts_grid_shadow', 'never') === 'always'): ?>
.box.post-module .post-wrapper{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('posts_grid_shadow', 'never') === 'never'): ?>
.box.post-module .post-wrapper, .box.post-module .post-wrapper:hover{
  box-shadow: none;
}
<?php endif; ?>



<?php if($this->journal2->settings->get('post_list_shadow', 'never') === 'hover'): ?>
.blog-list-view .post-wrapper:hover{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20) !important;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('post_list_shadow', 'never') === 'always'): ?>
.blog-list-view .post-wrapper{
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.20) !important;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('post_list_shadow', 'never') === 'never'): ?>
.blog-list-view.posts .post-wrapper{
  box-shadow: none !important;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('product_grid_details_tip', 'always') === 'hover'): ?>
.product-details:before {
  visibility:hidden;
  opacity:0;
  transition: all 0.2s;
}
.product-grid-item:hover .product-details:before {
  visibility:visible;
  opacity:1;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_details_tip', 'always') === 'never'): ?>
.product-details:before {
  display:none;
}
<?php endif; ?>



/* Product Grid Quickview*/

<?php if($this->journal2->settings->get('product_grid_quickview_status', 'hover') === 'hover'): ?>
.product-grid-item:hover .quickview-button {
    opacity: 1;
    visibility: visible;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_quickview_status', 'hover') === 'always'): ?>
.product-grid-item .quickview-button {
  opacity: 1;
  visibility: visible;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_quickview_status', 'hover') === 'never'): ?>
.product-grid-item .quickview-button {
  display:none;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('product_grid_quickview_button_icon_position', 'left') === 'left'): ?>
.product-grid-item .quickview-button .button-right-icon{
display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_quickview_button_icon_position', 'left') === 'right'): ?>
.product-grid-item .quickview-button .button-left-icon{
 display:none;
}
.button-right-icon:before{
  margin-left: 6px;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_quickview_button_icon_display', 'text') === 'text'): ?>
.product-grid-item .quickview-button .button-left-icon:before,
.product-grid-item .quickview-button .button-right-icon:before{
display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_quickview_button_icon_display', 'text') === 'icon'): ?>
.product-grid-item .quickview-button .button-left-icon:before{
 float:none;
}
.product-grid-item .quickview-button .button-right-icon:before{
 margin-left:0;
}
.product-grid-item .quickview-button .button-cart-text{
  display:none;
}
.product-grid-item .quickview-button [data-hint]:after,
.product-grid-item .quickview-button .hint--top:before{
  display:block;
}
.product-grid-item .quickview-button .hint--top:before{
  border-top-color: <?php echo $this->journal2->settings->get('product_grid_quickview_button_tooltip_bg_color');?>;
}
.product-grid-item .quickview-button .hint--right:before{
  border-right-color: <?php echo $this->journal2->settings->get('product_grid_quickview_button_tooltip_bg_color');?>;
}
.product-grid-item .quickview-button .hint--left:before{
  border-left-color: <?php echo $this->journal2->settings->get('product_grid_quickview_button_tooltip_bg_color');?>;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_quickview_button_icon_display', 'text') === 'both'): ?>
.product-grid-item .quickview-button .button-left-icon:before{
  margin-right: 6px;
}
<?php endif; ?>




/* Product Grid Wishlist/Compare */

<?php if($this->journal2->settings->get('product_grid_wishlist_icon_display', 'text') === 'text'): ?>
.product-grid-item .wishlist a i,
.product-grid-item .compare a i{
  display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_wishlist_icon_display', 'text') === 'icon'): ?>
.product-grid-item .button-wishlist-text,
.product-grid-item .button-compare-text{
   display: none;
}
.product-grid-item .wishlist [data-hint]:after,
.product-grid-item .wishlist .hint--top:before,
.product-grid-item .compare [data-hint]:after,
.product-grid-item .compare .hint--top:before{
  display:block;
}

.product-grid-item .wishlist .hint--top:before,
.product-grid-item .compare .hint--top:before{
  border-top-color: <?php echo $this->journal2->settings->get('product_grid_wishlist_icon_tip_bg');?>;
}
.product-grid-item .wishlist .hint--right:before,
.product-grid-item .compare .hint--right:before{
  border-right-color: <?php echo $this->journal2->settings->get('product_grid_wishlist_icon_tip_bg');?>;
}
.product-grid-item .wishlist .hint--left:before,
.product-grid-item .compare .hint--left:before{
  border-left-color: <?php echo $this->journal2->settings->get('product_grid_wishlist_icon_tip_bg');?>;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_wishlist_icon_display', 'text') === 'both'): ?>
.product-grid-item .wishlist a i,
.product-grid-item .compare a i{
  border:0;
  background-color:transparent !important;
  padding:0 5px;
}
.product-grid-item .wishlist a i:before,
.product-grid-item .compare a i:before{
  line-height:100%;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('product_grid_wishlist_icon_position', 'button') === 'image' && $this->journal2->settings->get('product_grid_wishlist_icon_display', 'icon') === 'icon'): ?>
.product-grid-item .product-details .wishlist,
.product-grid-item .product-details .compare,
.product-list-item .image .wishlist,
.product-list-item .image .compare,
.product-grid-item .image .button-wishlist-text,
.product-grid-item .image .button-compare-text{
 display:none !important;
}
<?php endif; ?>



<?php if($this->journal2->settings->get('product_grid_wishlist_icon_on_hover', 'on') == 'on' && $this->journal2->settings->get('product_grid_wishlist_icon_position', 'button') == 'image' && $this->journal2->settings->get('product_grid_wishlist_icon_display', '') == 'icon'): ?>
.product-grid-item .image .wishlist,
.product-grid-item .image .compare{
  visibility:hidden;
  opacity:0;
}
.product-grid-item:hover .image .wishlist,
.product-grid-item:hover .image .compare{
  visibility:visible;
  opacity:1;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('product_list_button_icon_display', 'icon') === 'icon'): ?>
.product-list-item .cart .button{
  width:<?php echo $this->journal2->settings->get('product_list_button_width_px');?>px;
  height:<?php echo $this->journal2->settings->get('product_list_button_height_px');?>px;
  line-height:<?php echo $this->journal2->settings->get('product_list_button_height_px');?>px;
  padding:0;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_quickview_button_icon_display', 'icon') === 'icon'): ?>
.product-list-item .quickview-button .button{
  width:<?php echo $this->journal2->settings->get('product_list_quickview_button_width_px');?>px;
  height:<?php echo $this->journal2->settings->get('product_list_quickview_button_height_px');?>px;
  line-height:<?php echo $this->journal2->settings->get('product_list_quickview_button_height_px');?>px;
  padding:0;
}
<?php endif; ?>



<?php if($this->journal2->settings->get('product_grid_button_icon_display', 'icon') === 'icon'): ?>
.product-grid-item .cart .button{
  width:<?php echo $this->journal2->settings->get('product_grid_button_width_px');?>px;
  height:<?php echo $this->journal2->settings->get('product_grid_button_height_px');?>px;
  line-height:<?php echo $this->journal2->settings->get('product_grid_button_height_px');?>px;
  padding:0;
}
.product-grid-item .cart{
  height:<?php echo $this->journal2->settings->get('product_grid_button_height_px');?>px;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_quickview_button_icon_display', 'icon') === 'icon'): ?>
.product-grid-item .quickview-button .button{
  width:<?php echo $this->journal2->settings->get('product_grid_quickview_button_width_px');?>px;
  height:<?php echo $this->journal2->settings->get('product_grid_quickview_button_height_px');?>px;
  line-height:<?php echo $this->journal2->settings->get('product_grid_quickview_button_height_px');?>px;
  padding:0;
}
<?php endif; ?>


/* Product List */

<?php if($this->journal2->settings->get('product_list_shadow', 'never') === 'hover'): ?>
.product-list-item:hover{
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_shadow', 'never') === 'always'): ?>
.product-list-item{
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_shadow', 'never') === 'never'): ?>
.product-list-item{
  box-shadow: none;
}
<?php endif; ?>



<?php if($this->journal2->settings->get('product_list_button_icon_display', 'text') === 'text'): ?>
.product-list-item .cart .button-left-icon:before{
display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_button_icon_display', 'text') === 'icon'): ?>
.product-list-item .cart .button-left-icon:before{
 float:none;
}
.product-list-item .cart .button-right-icon:before{
 margin-left:0;
}
.product-list-item .cart .button-cart-text{
  display:none;
}
.product-list-item .cart .button[data-hint]:after,
.product-list-item .cart .hint--top:before{
  display:block;
}
.product-list-item .cart .hint--top:before{
  border-top-color: <?php echo $this->journal2->settings->get('product_list_button_tooltip_bg_color');?>;
}
.product-list-item .cart .hint--right:before{
  border-right-color: <?php echo $this->journal2->settings->get('product_list_button_tooltip_bg_color');?>;
}
.product-list-item .cart .hint--left:before{
  border-left-color: <?php echo $this->journal2->settings->get('product_list_button_tooltip_bg_color');?>;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_button_icon_display', 'text') === 'both'): ?>
.product-list-item .cart .button-left-icon:before{
  margin-right: 8px;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_button_icon_position', 'left') === 'left'): ?>
.product-list-item .cart .button-right-icon{
display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_button_icon_position', 'left') === 'right'): ?>
.product-list-item .cart .button-left-icon{
 display:none;
}
.button-right-icon:before{
  margin-left: 8px;
}
<?php endif; ?>




/* Product List Quickview*/
<?php if($this->journal2->settings->get('product_list_quickview_status', 'hover') === 'hover'): ?>
.product-list-item:hover .quickview-button {
    opacity: 1;
    visibility: visible;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_quickview_status', 'hover') === 'always'): ?>
.product-list-item .quickview-button {
  opacity: 1;
  visibility: visible;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_quickview_status', 'hover') === 'never'): ?>
.product-list-item .quickview-button {
  display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_quickview_button_icon_position', 'left') === 'left'): ?>
.product-list-item .quickview-button .button-right-icon{
display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_quickview_button_icon_position', 'left') === 'right'): ?>
.product-list-item .quickview-button .button-left-icon{
 display:none;
}
.button-right-icon:before{
  margin-left: 8px;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_quickview_button_icon_display', 'text') === 'text'): ?>
.product-list-item .quickview-button .button-left-icon:before{
display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_quickview_button_icon_display', 'text') === 'icon'): ?>
.product-list-item .quickview-button .button-left-icon:before{
 float:none;
}
.product-list-item .quickview-button .button-right-icon:before{
 margin-left:0;
}
.product-list-item .quickview-button .button-cart-text{
  display:none;
}
.product-list-item .quickview-button .button[data-hint]:after,
.product-list-item .quickview-button .hint--top:before{
  display:block;
}
.product-list-item .quickview-button .hint--top:before{
  border-top-color: <?php echo $this->journal2->settings->get('product_list_quickview_button_tooltip_bg_color');?>;
}
.product-list-item .quickview-button .hint--right:before{
  border-right-color: <?php echo $this->journal2->settings->get('product_list_quickview_button_tooltip_bg_color');?>;
}
.product-list-item .quickview-button .hint--left:before{
  border-left-color: <?php echo $this->journal2->settings->get('product_list_quickview_button_tooltip_bg_color');?>;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_quickview_button_icon_display', 'text') === 'both'): ?>
.product-list-item .quickview-button .button-left-icon:before{
  margin-right: 8px;
}
<?php endif; ?>


/* Product Page */

.product-info .left .image-additional-grid a{
  width: <?php echo 100 / (float)$this->journal2->settings->get('product_page_additional_width', 5) ?>%;
}

.product-info .left .image-additional{
  margin-right: -<?php echo $this->journal2->settings->get('product_page_additional_spacing')+ 4;?>px;
}

.product-info .gallery-text{
  padding-top: <?php echo $this->journal2->settings->get('product_page_additional_spacing')-4;?>px;
}

<?php if($this->journal2->settings->get('product_page_button_icon_position', 'left') === 'left'): ?>
#button-cart .button-cart-text:after{
display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_page_button_icon_position', 'left') === 'right'): ?>
#button-cart .button-cart-text:before{
 display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_page_gallery_carousel_arrows', 'hover') === 'hover'): ?>
#product-gallery .side-buttons{
 display:none;
}
#product-gallery:hover .side-buttons{
 display:block;
}
<?php endif; ?>


.checkout-content .buttons{
  border-radius:0;
}

.compare-info td{
  border-right-style:<?php echo $this->journal2->settings->get('shopping_divider_style'); ?>;
}
table.list{
  border-bottom-style:<?php echo $this->journal2->settings->get('shopping_divider_style'); ?>;
  border-left-style:<?php echo $this->journal2->settings->get('shopping_divider_style'); ?>;
}
table.list td{
  border-right-style:<?php echo $this->journal2->settings->get('shopping_divider_style'); ?>;
  border-top-style:<?php echo $this->journal2->settings->get('shopping_divider_style'); ?>;
}



/* Product Labels*/

.label-latest + .label-sale{
  top: <?php echo $this->journal2->settings->get('label_latest_height'); ?>px;
}

<?php if($this->journal2->settings->get('label_latest_status', 'always') === 'hover'): ?>
.label-latest{
  visibility:hidden;
  opacity:0;
}
.product-wrapper:hover .label-latest, .product-list-item:hover .label-latest, .product-info .image:hover .label-latest{
  visibility:visible;
  opacity:1;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('label_latest_status', 'always') === 'never'): ?>
  .label-latest{
  display:none !important;
  }
<?php endif; ?>
<?php if($this->journal2->settings->get('label_special_status', 'always') === 'hover'): ?>
.label-sale{
  visibility:hidden;
  opacity:0;
}
.product-wrapper:hover .label-sale, .product-list-item:hover .label-sale, .product-info .image:hover .label-sale{
  visibility:visible;
  opacity:1;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('out_of_stock_status', 'always') === 'hover'): ?>
img.outofstock{
  visibility:hidden;
  opacity:0;
}
.product-wrapper:hover img.outofstock, .product-list-item:hover img.outofstock, .product-info .image:hover img.outofstock{
  visibility:visible;
  opacity:1;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('out_of_stock_status', 'always') === 'never'): ?>
img.outofstock{
  display:none !important;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_list_latest_label_status', 'block') === 'none'): ?>
.product-list-item .image .label-latest + .label-sale{
  top: 5px;
  margin-top: 0;
}
<?php endif; ?>

#more-details.hint--top:before{
  border-top-color: <?php echo $this->journal2->settings->get('quickview_more_details_tip_bg');?>;
}

<?php if($this->journal2->settings->get('extended_layout_top_spacing', 'on') === 'off'): ?>

  .extended-layout #column-left,
  .extended-layout #column-right{
    padding:0;
  }
  .extended-layout #column-left + #content{
    padding: 0 0 0 20px;
  }
  .extended-layout #column-right + #content{
    padding: 0 20px 0 0;
  }
  .extended-layout #column-left + #column-right + #content{
    padding: 0 20px 0 20px;
  }
  .extended-layout #content{
    padding: 0;
  }

<?php endif; ?>

<?php if($this->journal2->settings->get('extended_layout_side_spacing', 'on') === 'off'): ?>
.extended-layout .side-column .box{
    margin-bottom: 0;
  }
<?php endif; ?>

.boxed-header header{
  max-width:<?php echo $this->journal2->settings->get('site_width');?>px;
}
.boxed-header .super-menu > li:first-of-type{
  border-left:0;
}
.boxed-header .super-menu > li:last-of-type{
  border-right:0;
}

.boxed-header .is-sticky header{
  left:50%;
  margin-left:-<?php echo $this->journal2->settings->get('site_width') / 2; ?>px;
}

.boxed-header .journal-header-center .journal-links{
  padding-left: 10px;
}
.boxed-header .journal-header-center .journal-search{
  padding-left: 20px;
}
.boxed-header .journal-header-center .journal-secondary{
  padding-right: 10px;
}
.boxed-header .journal-header-center .journal-cart{
  padding-right: 20px;
}
@media only screen and (max-width: 760px) {
  .boxed-header .journal-header-center .journal-search,
  .boxed-header .journal-header-center .journal-links{
    padding-left: 0;
  }
  .boxed-header .journal-header-center .journal-cart,
  .boxed-header .journal-header-center .journal-secondary{
    padding-right: 0;
  }
}

@media only screen and (max-width: <?php echo $this->journal2->settings->get('site_width') + 15;?>px) {
.boxed-header .is-sticky header{
  left:0;
  margin-left:0;
}
.boxed-header body{
  padding:0;
}
.fullwidth-footer .columns{
    padding-left: 15px;
  }
  .copyright{
    padding-left: 15px;
  }
  <?php if($this->journal2->settings->get('mega_header_align', 'center') === 'left'): ?>
  .journal-header-mega #logo a, .breadcrumb{
      padding-left:15px;
  }
<?php endif; ?>
}


<?php if($this->journal2->settings->get('top_menu_shadow', 'none') === 'none'): ?>
header .links > a{
  border-bottom-color:#e4e4e4;
}
<?php endif; ?>

.journal-header-default .links .no-link,
.journal-header-menu .links .no-link{
    border-color:<?php echo $this->journal2->settings->get('top_menu_border_color');?>
}

.journal-header-center #cart .content:before,
.oc2 #cart .content .cart-wrapper:before{
  color:<?php echo $this->journal2->settings->get('cart_content_bg');?>
}

.journal-header-center .autocomplete2-suggestions:before{
  color:<?php echo $this->journal2->settings->get('autosuggest_bg');?>
}

<?php if($this->journal2->settings->get('top_menu_icon_display', 'inline') === 'block'): ?>
header .links > a i, .links .no-link i{
  display:block;
}
header .top-menu-link{
  position:relative;
  top:-3px;
}
<?php endif; ?>

.journal-language .dropdown-menu:before,
.journal-currency .dropdown-menu:before{
  color:<?php echo $this->journal2->settings->get('lang_drop_bg');?>
}

.journal-header-center .journal-language form > div,
.journal-header-center .journal-currency form > div{
  border-left-style:<?php echo $this->journal2->settings->get('lang_divider_type');?>;
  border-right-style:<?php echo $this->journal2->settings->get('lang_divider_type');?>;
}

#search ::-webkit-input-placeholder {
  color:<?php echo $this->journal2->settings->get('search_placeholder_color');?>;
  font-family: inherit;
}
#search :-moz-placeholder {
  color:<?php echo $this->journal2->settings->get('search_placeholder_color');?>;
  font-family: inherit;
}
#search ::-moz-placeholder {
  color:<?php echo $this->journal2->settings->get('search_placeholder_color');?>;
  font-family: inherit;
}
#search :-ms-input-placeholder {
  color:<?php echo $this->journal2->settings->get('search_placeholder_color');?>;
  font-family: inherit;
}

.button-search{
    border-right-style:<?php echo $this->journal2->settings->get('search_divider_type');?>;
}
.button-search{
    border-right-color:<?php echo $this->journal2->settings->get('search_divider');?>;
}
header .journal-login{
    border-bottom-color:<?php echo $this->journal2->settings->get('search_divider');?>;
}


.super-menu > li:last-of-type{
    border-right-color:<?php echo $this->journal2->settings->get('menu_divider');?>;
    border-right-style:<?php echo $this->journal2->settings->get('menu_divider_type');?>;
}

@media only screen and (max-width: 760px) {
  .journal-header-center #search input,
  .journal-header-center .button-search{
      border-radius:0;
  }
  .journal-header-center #search input{
      background-color:<?php echo $this->journal2->settings->get('search_bg_mobile');?>;
  }
  .journal-header-center #cart{
      background-color:<?php echo $this->journal2->settings->get('cart_heading_bg_mobile');?>;
  }

  header .journal-login{
      border-bottom-style:<?php echo $this->journal2->settings->get('search_divider_type');?>;
  }

  .journal-menu .mobile-menu > li{
    border-bottom-color:<?php echo $this->journal2->settings->get('menu_divider');?>;
    border-bottom-style:<?php echo $this->journal2->settings->get('menu_divider_type');?>;
  }
}

.inline-button .product-details{
  padding-bottom:0;
}
<?php if($this->journal2->settings->get('mobile_menu_cart_display', 'on') === 'off' ): ?>
@media only screen and (max-width: 470px) {
.journal-menu .mobile-trigger {
width: 50%;
margin-top: -40px;
z-index: 999;
background-color:transparent;
}
.journal-header-center .journal-cart{
  z-index: 2;
}
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_button_block_button', 'block-button') === 'inline-button' && $this->journal2->settings->get('catalog_grid_cart', 'block') === 'block' ): ?>
  .product-grid-item .cart{
  display:inline-block !important;
  }
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_main_menu_cart', 'block') === 'none' ): ?>
  .mega-menu .product-grid-item .cart{
  display:none !important;
  }
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_cart', 'block') === 'none'): ?>
  .product-grid-item .cart{
    display:none !important;
  }
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_product_page_cart', 'block') === 'none'): ?>
  .quickview .product-info .right .cart{
    display:table;
  }
  .product-info .right .cart div .qty,
  .product-info .right .cart div #button-cart{
  display:none;
  }
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_cs_cart', 'block') === 'none'): ?>
  .custom-sections .product-grid-item .product-details .cart{
    display:none !important;
  }
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_side_carousel_cart', 'block') === 'none'): ?>
  .side-column .product-grid-item .product-details .cart{
    display:none !important;
  }
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_carousel_cart', 'block') === 'none'): ?>
  #content .journal-carousel .product-grid-item .product-details .cart,
  #top-modules .journal-carousel .product-grid-item .product-details .cart,
  #bottom-modules .journal-carousel .product-grid-item .product-details .cart{
    display:none !important;
  }
<?php endif; ?>

<?php if($this->journal2->settings->get('product_page_title_status', '1') === '0'): ?>
  .product-info .right .options h3{
    display:none;
  }
  .product-info .right .option-image:first-of-type{
    margin-top:0;
  }
<?php endif; ?>

.product-info .right .options.push-1 .option-image li.selected span img{
  border-color:<?php echo $this->journal2->settings->get('product_page_options_push_image_border_hover');?>;
}

.mega-menu-categories .mega-menu-item,
.mega-menu-brands .mega-menu-item,
.mega-menu-html .mega-menu-item,
#header .mega-menu .product-grid-item{
  margin-bottom:<?php echo $this->journal2->settings->get('menu_categories_item_margin');?>px;
}
.mega-menu > div{
  margin-bottom:-<?php echo $this->journal2->settings->get('menu_categories_item_margin');?>px;
  margin-right:-<?php echo $this->journal2->settings->get('menu_categories_item_margin');?>px;
}

.mega-menu .mega-menu-column:last-of-type > div{
   margin-right:-<?php echo $this->journal2->settings->get('menu_categories_item_margin');?>px;
}

.mega-menu-column > div > h3, .mega-menu .mega-menu-column .menu-cms-block{
  margin-right:<?php echo $this->journal2->settings->get('menu_categories_item_margin');?>px;
}
.mega-menu .mega-menu-column:last-of-type > h3,
.mega-menu .mega-menu-column:last-of-type > div > h3,
.mega-menu .mega-menu-column:last-of-type > .menu-cms-block,
.mega-menu .mega-menu-column.mega-menu-html-block > div{
  margin-right:0;
}

@media only screen and (max-width: 760px) {
  .mega-menu .mega-menu-column > div{
   margin-right:-<?php echo $this->journal2->settings->get('menu_categories_item_margin');?>px;
  }
}

.journal-sf .sf-image .box-content ul{
  margin-bottom:-<?php echo $this->journal2->settings->get('filter_image_section_margin_bottom');?>px;
  margin-right:-<?php echo $this->journal2->settings->get('filter_image_section_margin_right');?>px;
}


<?php if($this->journal2->settings->get('one_page_coupon_status', 'on') === 'off' && $this->journal2->settings->get('one_page_voucher_status', 'on') === 'off'): ?>
.journal-checkout .coupon-voucher{
  display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('one_page_coupon_status', 'on') === 'off'): ?>
.journal-checkout .coupon-voucher .checkout-coupon{
  display:none;
}
.journal-checkout .coupon-voucher .checkout-voucher{
  width:100%;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('one_page_voucher_status', 'on') === 'off'): ?>
.journal-checkout .coupon-voucher .checkout-coupon{
 width:100%;
 padding-right:0;
}
.journal-checkout .coupon-voucher .checkout-voucher{
  display:none;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('catalog_product_page_wishlist', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_product_page_compare', 'inline-block') === 'none'): ?>
.product-info .right .wishlist-compare{
  display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_name', 'table') === 'none' || $this->journal2->settings->get('catalog_grid_carousel_name', 'table') === 'none' || $this->journal2->settings->get('catalog_grid_side_carousel_name', 'table') === 'none' || $this->journal2->settings->get('catalog_grid_cs_name', 'table') === 'none' || $this->journal2->settings->get('catalog_grid_main_menu_name', 'table') === 'none'): ?>
.product-details{
 padding-top:13px;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_cart', 'block') === 'none' || $this->journal2->settings->get('catalog_grid_carousel_cart', 'block') === 'none' || $this->journal2->settings->get('catalog_grid_side_carousel_cart', 'block') === 'none' || $this->journal2->settings->get('catalog_grid_cs_cart', 'block') === 'none' || $this->journal2->settings->get('catalog_grid_main_menu_cart', 'block') === 'none'): ?>
.product-grid-item .price + hr,
.product-grid-item .price + .rating + hr{
  display:block;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('catalog_grid_cart', 'block') === 'none' && $this->journal2->settings->get('catalog_grid_wishlist', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_compare', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_price', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_name', 'table') === 'none'): ?>
.product-details{
  display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_carousel_cart', 'block') === 'none' && $this->journal2->settings->get('catalog_grid_carousel_wishlist', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_carousel_compare', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_carousel_price', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_carousel_name', 'table') === 'none'): ?>
.product-details{
  display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_side_carousel_cart', 'block') === 'none' && $this->journal2->settings->get('catalog_grid_side_carousel_wishlist', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_side_carousel_compare', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_side_carousel_price', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_side_carousel_name', 'table') === 'none'): ?>
.product-details{
  display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_cs_cart', 'block') === 'none' && $this->journal2->settings->get('catalog_grid_cs_wishlist', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_cs_compare', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_cs_price', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_cs_name', 'table') === 'none'): ?>
.product-details{
  display:none;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('catalog_grid_main_menu_cart', 'block') === 'none' && $this->journal2->settings->get('catalog_grid_main_menu_wishlist', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_main_menu_compare', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_main_menu_price', 'inline-block') === 'none' && $this->journal2->settings->get('catalog_grid_main_menu_name', 'table') === 'none'): ?>
.product-details{
  display:none;
}
<?php endif; ?>


<?php if($this->journal2->settings->get('contacts_display', 'off') === 'on'): ?>
  footer .contacts{
  text-align:center;
  }
  footer .contacts-left{
    float: none;
  }
  footer .contacts-right{
    display: none;
  }
<?php endif; ?>

.has-cta .rotator-tex{
  line-height:<?php echo $this->journal2->settings->get('cta_button_height');?>px;
}

footer .contacts .hint--top:before{
  border-top-color: <?php echo $this->journal2->settings->get('footer_tooltip_bg_color');?>;
}


.side-column .box-category,
.side-column .box-content,
.side-column .box-content > div,
.side-column .box-content > ul > li:last-of-type,
.side-column .oc-module .product-grid-item:last-of-type{
  border-bottom-left-radius: inherit;
  border-bottom-right-radius: inherit;
  border-radius:inherit;
}


.journal-sf ul li label:hover{
  color:<?php echo $this->journal2->settings->get('filter_active_text');?>;
}
.sf-icon:before{
  border-top-color:<?php echo $this->journal2->settings->get('reset_tip_bg');?>;
}

/*
.journal-sf ul li label:hover img{
  border-color:<?php echo $this->journal2->settings->get('filter_active_border');?>;
} */

.sf-price .value:after{
  border-bottom-color:<?php echo $this->journal2->settings->get('filter_price_tip');?>;
}

.mobile-trigger{
background-color:<?php echo $this->journal2->settings->get('main_menu_bg_color');?>;
}


.journal-header-default .links > a,
.journal-header-menu .links > a{
  border-bottom-color: transparent;
}

@media only screen and (max-width: 760px) {
.journal-header-default .links > a,
.journal-header-menu .links > a{
  border-bottom-color: <?php echo $this->journal2->settings->get('top_menu_border_color');?>;
}
}
.nav-numbers a:hover,
.nav-numbers li.active a{
  -webkit-backface-visibility: hidden;
  -webkit-transform: scale(<?php echo $this->journal2->settings->get('text_rotator_bullet_scale');?>);
  -moz-transform: scale(<?php echo $this->journal2->settings->get('text_rotator_bullet_scale');?>);
  -ms-transform: scale(<?php echo $this->journal2->settings->get('text_rotator_bullet_scale');?>);
  transform: scale(<?php echo $this->journal2->settings->get('text_rotator_bullet_scale');?>);
}
.headline-mode .nav-numbers a:hover,
.headline-mode .nav-numbers li.active a{
  -webkit-backface-visibility: hidden;
  -webkit-transform: scale(<?php echo $this->journal2->settings->get('headline_bullet_scale');?>);
  -moz-transform: scale(<?php echo $this->journal2->settings->get('headline_bullet_scale');?>);
  -ms-transform: scale(<?php echo $this->journal2->settings->get('headline_bullet_scale');?>);
  transform: scale(<?php echo $this->journal2->settings->get('headline_bullet_scale');?>);
}
.tp-bullets.simplebullets.round .bullet.selected,
.tp-bullets.simplebullets.round .bullet:hover,
.journal-simple-slider .owl-controls .owl-page.active span,
.journal-simple-slider .owl-controls.clickable .owl-page:hover span{
  -webkit-backface-visibility: hidden;
  -webkit-transform: scale(<?php echo $this->journal2->settings->get('slider_bullet_scale');?>);
  -moz-transform: scale(<?php echo $this->journal2->settings->get('slider_bullet_scale');?>);
  -ms-transform: scale(<?php echo $this->journal2->settings->get('slider_bullet_scale');?>);
  transform: scale(<?php echo $this->journal2->settings->get('slider_bullet_scale');?>);
}

.owl-controls .owl-page.active span,
.owl-controls.clickable .owl-page:hover span{
  -webkit-transform: scale(<?php echo $this->journal2->settings->get('carousel_bullet_scale');?>);
  -moz-transform: scale(<?php echo $this->journal2->settings->get('carousel_bullet_scale');?>);
  -ms-transform: scale(<?php echo $this->journal2->settings->get('carousel_bullet_scale');?>);
  transform: scale(<?php echo $this->journal2->settings->get('carousel_bullet_scale');?>);
}

@media only screen and (max-width: <?php echo $this->journal2->settings->get('site_width') + 15;?>px) {

.bottom-footer.fullwidth-bar .copyright{
  padding-left: 15px;
}
.bottom-footer.fullwidth-bar .payments{
  padding-right: 15px;
}
.extended-layout #column-left{
  padding:20px 0 0 20px;
}
.extended-layout #column-right{
  padding:20px 20px 0 0;
}
.extended-layout #content,
.extended-layout #column-left + #content,
.extended-layout #column-right + #content,
.extended-layout #column-left + #column-right + #content{
  padding:20px 20px 0 20px;
}
.journal-simple-slider{
  height: auto !important;
}
.extended-layout #column-left{
  width:240px;
}

.extended-layout #column-right{
  width:240px;
}
.extended-layout #column-left + #content{
  margin-left:240px;
}
.extended-layout #column-right + #content{
  margin-right:240px;
}
.extended-layout #column-left + #column-right + #content{
margin-left:240px;
margin-right:240px;
}
}

@media only screen and (max-width: 980px) {
.journal-header-default .mega-menu,
.journal-header-menu .mega-menu{
    width: 100%;
  }
}

@media only screen and (max-width: 760px) {
  .extended-layout #column-left + #content,
  .extended-layout #column-right + #content,
  .extended-layout #column-left + #column-right + #content{
    margin-left:0;
    margin-right:0;
  }
   .journal-header-center .journal-secondary{
  background-color:<?php echo $this->journal2->settings->get('top_bar_bg_color');?>;
}

}

.mega-menu{
  max-width:<?php echo $this->journal2->settings->get('site_width');?>px;
  /* margin-top:<?php echo 0 + $this->journal2->settings->get('main_menu_border:border-bottom-width');?>px; */
}

/*
.mega-menu > div > div:first-child .wishlist .hint--top:after{
  left:53px;
}
*/


<?php if($this->journal2->settings->get('header_height', 'normal') === 'medium'): ?>

.journal-header-center .j-100, .journal-header-center #logo a{
height:130px;
}
.journal-header-center #logo a img{
max-height:130px;
}
.journal-header-center .journal-search,
.journal-header-center .journal-cart{
top:45px;
}
.journal-header-center .journal-menu-bg{
top:170px;
}
.journal-desktop.header-center .sticky-wrapper{
max-height:210px;
}
@media only screen and (max-width: 760px) {
.journal-header-center .j-100, .journal-header-center #logo a{
height:100px;
}
.journal-header-center .journal-search,
.journal-header-center .journal-cart{
top:0;
}
.journal-header-center .journal-menu-bg{
top:220px;
}
.journal-header-center #logo a img{
max-height:100px;
}
}
<?php endif; ?>

<?php if($this->journal2->settings->get('header_height', 'normal') === 'tall'): ?>

.journal-header-center .j-100, .journal-header-center #logo a{
height:150px;
}
.journal-header-center #logo a img{
max-height:150px;
}
.journal-header-center .journal-search,
.journal-header-center .journal-cart{
top:55px;
}
.journal-header-center .journal-menu-bg{
top:190px;
}
.journal-desktop.header-center .sticky-wrapper{
max-height:230px;
}
@media only screen and (max-width: 760px) {
.journal-header-center .j-100, .journal-header-center #logo a{
height:100px;
}
.journal-header-center .journal-search,
.journal-header-center .journal-cart{
top:0;
}
.journal-header-center .journal-menu-bg{
top:220px;
}
.journal-header-center #logo a img{
max-height:100px;
}
}
<?php endif; ?>
<?php if($this->journal2->settings->get('header_height', 'normal') === 'taller'): ?>

.journal-header-center .j-100, .journal-header-center #logo a{
height:170px;
}
.journal-header-center #logo a img{
max-height:170px;
}
.journal-header-center .journal-search,
.journal-header-center .journal-cart{
top:65px;
}
.journal-header-center .journal-menu-bg{
top:210px;
}
.journal-desktop.header-center .sticky-wrapper{
max-height:250px;
}
@media only screen and (max-width: 760px) {
.journal-header-center .j-100, .journal-header-center #logo a{
height:100px;
}
.journal-header-center .journal-search,
.journal-header-center .journal-cart{
top:0;
}
.journal-header-center .journal-menu-bg{
top:220px;
}
.journal-header-center #logo a img{
max-height:100px;
}
}
<?php endif; ?>


<?php if($this->journal2->settings->get('side_modules_margin', 'on') === 'off'): ?>
.side-column .box{
  margin-bottom:0;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('extended_layout', '0') === '1'): ?>
.extended-container #container{
  background-color:transparent;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('product_grid_price_full', 'inline-block') === 'block'): ?>
.product-grid-item .price{
  width:100%;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('filter_image_size', 'medium') === 'tiny'): ?>
.product-grid-item .price{
  width:100%;
}
<?php endif; ?>


.product-grid-item.display-icon .wishlist-icon:before,
.product-grid-item.display-icon .compare-icon:before{
  line-height:<?php echo $this->journal2->settings->get('product_grid_wishlist_icon_bg_height');?>px;
}

.journal-header-center #cart .heading i{
  height:<?php echo 40 - $this->journal2->settings->get('cart_heading_border:border-width');?>px;
}

.journal-desktop .menu-floated .float-left{
  border-right-style:<?php echo $this->journal2->settings->get('menu_divider_type');?>;
}

.column.products > h3{
  margin-bottom:<?php echo 12 - $this->journal2->settings->get('footer_product_padding');?>px;
}
.column.products{
  padding-bottom:<?php echo 12 - $this->journal2->settings->get('footer_product_padding');?>px;
}

.side-column .journal-gallery .box-heading{
  margin-bottom:<?php echo 10 - $this->journal2->settings->get('side_gallery_padding');?>px;
}

.side-column .box-category > ul li ul li a{
  padding-left: <?php echo (int)$this->journal2->settings->get('side_category_link_padding_left') + (int)$this->journal2->settings->get('side_category_sub_left_padding');?>px;
}
.side-column .box-category > ul li ul li ul li a{
  padding-left: <?php echo (int)$this->journal2->settings->get('side_category_link_padding_left') + 2 * (int)$this->journal2->settings->get('side_category_sub_left_padding');?>px;
}
.side-column .box-category > ul li ul li ul li ul li a{
  padding-left: <?php echo (int)$this->journal2->settings->get('side_category_link_padding_left') + 3 * (int)$this->journal2->settings->get('side_category_sub_left_padding');?>px;
}
.side-column .box-category > ul li ul li ul li ul li ul li a{
  padding-left: <?php echo (int)$this->journal2->settings->get('side_category_link_padding_left') + 4 * (int)$this->journal2->settings->get('side_category_sub_left_padding');?>px;
}
.side-column .box-category > ul li ul li ul li ul li ul li ul li a{
  padding-left: <?php echo (int)$this->journal2->settings->get('side_category_link_padding_left') + 5 * (int)$this->journal2->settings->get('side_category_sub_left_padding');?>px;
}

@media only screen and (max-width: <?php echo $this->journal2->settings->get('site_width') + 15;?>px) {
  .breadcrumb{
    padding-left:10px;
  }
}

<?php if($this->journal2->settings->get('cs_fullwidth_divider', 'off') === 'on'): ?>
.custom-sections .box-heading.box-sections{
    border-left-width:1px;
    border-right-width:1px;
}
<?php endif; ?>

.custom-sections .box-heading.box-sections{
    border-left-style:<?php echo $this->journal2->settings->get('cs_title_divider_type');?>;
}
<?php if($this->journal2->settings->get('carousel_product_shadow_mask', 'off') === 'on'):?>

#top-modules .carousel-product > div,
#bottom-modules .carousel-product > div,
.side-column .carousel-product > div,
#top-modules .carousel-category > div,
#bottom-modules .carousel-category > div,
.side-column .carousel-category > div,
#top-modules .journal-carousel.post-module > div,
#bottom-modules .journal-carousel.post-module > div{
    margin-left:-11px;
    margin-right:-11px;
    padding:0 11px;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('carousel_brand_shadow_mask', 'off') === 'on'): ?>
#top-modules .carousel-brand > div,
#bottom-modules .carousel-brand > div,
.side-column .carousel-brand > div{
    margin-left:-10px;
    margin-right:-10px;
    padding:0 10px;
  }
<?php endif; ?>


<?php if($this->journal2->settings->get('post_title_overflow', 'on') === 'off'): ?>
.blog-post .heading-title{
  white-space:normal;
  height:auto;
  line-height:22px;
  padding:8px 0;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('module_title_overflow', 'on') === 'off'): ?>
.post-module .box-heading{
  white-space:normal;
  height:auto;
  line-height:22px;
  padding-top:8px;
  padding-bottom:8px;
}
<?php endif; ?>

.posts.blog-list-view .post-item-details{
    width: <?php echo 100 - $this->journal2->settings->get('post_list_image_width'); ?>%;
}


.side-column .box.cms-blocks .box-heading{
  margin-bottom:<?php echo $this->journal2->settings->get('side_cms_margin');?>px;
}

@media only screen and (min-width: <?php echo $this->journal2->settings->get('site_width') + 15;?>px) {
  .safari5 #footer,
  .safari5.boxed-header header{
     width: <?php echo $this->journal2->settings->get('site_width', 1024); ?>px;
  }
}

@media only screen and (max-width: <?php echo $this->journal2->settings->get('site_width') + 15;?>px) {
  .tp-banner-container{
    height:auto !important;
  }
}

@media only screen and (max-width: <?php echo $this->journal2->settings->get('site_width') + 15;?>px) {
#top-modules>div, #bottom-modules>div{
  padding-left:20px;
  padding-right:20px;
}
.checkout-page #content {
  padding-left: 20px;
  padding-right: 20px;
}
}


<?php if($this->journal2->settings->get('show_countdown', 'never') === 'always'): ?>
.product-grid-item .countdown,
.product-list-item .countdown {
    opacity: 1;
    visibility: visible;
}
<?php endif; ?>
<?php if($this->journal2->settings->get('show_countdown', 'never') === 'hover'): ?>
.product-grid-item:hover .countdown,
.product-list-item:hover .countdown {
    opacity: 1;
    visibility: visible;
}
<?php endif; ?>

<?php if($this->journal2->settings->get('show_countdown', 'never') === 'hidden'): ?>
.product-grid-item .countdown,
.product-list-item .countdown {
  display:none;
}
<?php endif; ?>


<?php if ($this->journal2->settings->get('product_page_cloud_zoom_inner') === '0'): ?>
.zm-viewer{
  display:none;
  border-left:1px solid white;
}
<?php endif; ?>

.option li.hint--top:before{
  border-top-color:<?php echo $this->journal2->settings->get('product_page_push_tooltip_bg'); ?>;
}


/* Custom CSS */
<?php echo $this->journal2->settings->get('custom_css'); ?>

