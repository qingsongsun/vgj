<?php echo $header; ?>
<script type="text/javascript" src="catalog/view/theme/journal2/js/sketchpad.js"></script>
<script type="text/javascript" src="catalog/view/theme/journal2/js/sketchpad.min.js"></script>

<style>
      .sketchpad {
        background: #FFF;
        width: 1000px;
        height: 800px;
        border-radius: 2px;

        -webkit-box-shadow: 2px 2px 5px 0px rgba(50, 50, 50, 0.75);
        -moz-box-shadow: 2px 2px 5px 0px rgba(50, 50, 50, 0.75);
        box-shadow: 2px 2px 5px 0px rgba(50, 50, 50, 0.75);
      }
      </style>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?php echo $breadcrumb['href']; ?>" itemprop="url"><span itemprop="title"><?php echo $breadcrumb['text']; ?></span></a></li>
    <?php } ?>
  </ul>
  <?php echo $column_left; ?><?php echo $column_right; ?>
  <div class="row">
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="product-page-content" itemscope itemtype="http://schema.org/Product">
      <?php if ($this->journal2->settings->get('product_page_title_position', 'top') === 'top'): ?>
      <h1 class="heading-title" itemprop="name"><?php echo $heading_title; ?></h1>
      <?php endif; ?>
      <?php echo $content_top; ?>
      <div class="row product-info <?php echo $this->journal2->settings->get('split_ratio'); ?>">
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-8'; ?>
        <?php } ?>
        <div class="left">
          <?php if ($thumb) { ?>
          <div class="image">
            <?php if (isset($labels) && is_array($labels)): ?>
            <?php foreach ($labels as $label => $name): ?>
            <?php if ($label === 'outofstock'): ?>
            <img class="outofstock" <?php echo Journal2Utils::getRibbonSize($this->journal2->settings->get('out_of_stock_ribbon_size')); ?> style="z-index: 100000; position: absolute; top: 0; left: 0" src="<?php echo Journal2Utils::generateRibbon($name, $this->journal2->settings->get('out_of_stock_ribbon_size'), $this->journal2->settings->get('out_of_stock_font_color'), $this->journal2->settings->get('out_of_stock_bg')); ?>" alt="" />
            <?php else: ?>
              <span class="label-<?php echo $label; ?>"><b><?php echo $name; ?></b></span>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php endif; ?>
            <!-- 图片的位置 -->
            <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" data-largeimg="<?php echo $popup; ?>" itemprop="image"  /></a>

          </div>
          <?php if($this->journal2->settings->get('product_page_gallery')): ?>
          <div class="gallery-text"><span><?php echo $this->journal2->settings->get('product_page_gallery_text'); ?></span></div>
          <!-- huwen added -->
          <!-- <div class="gallery-text"><span>批注图片</span></div> -->


          <?php endif; ?>

          <?php } ?>
          <?php if ($images) { ?>
          <div id="product-gallery" class="image-additional <?php echo $this->journal2->settings->get('product_page_gallery_carousel') ? 'journal-carousel' : 'image-additional-grid'; ?>">
            <?php if ($thumb) { ?>
            <a href="<?php echo isset($popup_fixed) ? $popup_fixed : $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo isset($thumb_fixed) ? $thumb_fixed : $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
            <?php foreach ($images as $image) { ?>
            <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" itemprop="image" /></a>
            <?php } ?>
            <!-- ishanks changed it to add option pictures-->
            <?php if ($options) { ?>
            <?php foreach ($options as $option) { ?>
            <?php foreach ($option['product_option_value'] as $option_value) { ?>
            <?php if($option_value['thumb']) { ?>
            <a style="display:none" href="<?php echo $option_value['popup']; ?>" title="<?php echo $heading_title; ?>" id="img<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" itemprop="icmage"/></a>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </div>


          <?php if ($this->journal2->settings->get('product_page_gallery_carousel')): ?>
          <script>
            (function () {
              var opts = {
                itemsCustom:[
                  [0, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                  [470, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                  [760, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                  [980, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                  [1100, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)]
                  ],
                navigation:true,
                scrollPerPage:true,
                navigationText : false,
                stopOnHover: true,
                cssAnimation: false,
                paginationSpeed: <?php echo (int)$this->journal2->settings->get('product_page_gallery_carousel_transition_speed', 400) ?>,
                margin:parseInt('<?php echo $this->journal2->settings->get('product_page_additional_spacing', 12) ?>', 10)
              };
            <?php if (!$this->journal2->settings->get('product_page_gallery_carousel_autoplay')): ?>
            opts.autoPlay = false;
            <?php else: ?>
            opts.autoPlay = parseInt('<?php echo $this->journal2->settings->get('product_page_gallery_carousel_transition_delay', 1000); ?>', 10);
            <?php endif; ?>
            <?php if ($this->journal2->settings->get('product_page_gallery_carousel_pause_on_hover')): ?>
            opts.stopOnHover = true;
            <?php endif; ?>
            <?php if (!$this->journal2->settings->get('product_page_gallery_carousel_touch_drag')): ?>
            opts.touchDrag = false;
            <?php endif; ?>
            jQuery("#product-gallery").owlCarousel(opts);
            <?php if ($this->journal2->settings->get('product_page_gallery_carousel_arrows') == 'hover' || $this->journal2->settings->get('product_page_gallery_carousel_arrows') == 'always'): ?>
            $('#product-gallery .owl-buttons').addClass('side-buttons');
            <?php endif; ?>
            })();
          </script>
          <?php endif; ?>
          <?php } ?>
          <?php foreach ($this->journal2->settings->get('additional_product_description_image', array()) as $tab): ?>
          <div class="journal-custom-tab">
            <?php if ($tab['has_icon']): ?>
            <div class="block-icon block-icon-left" style="<?php echo $tab['icon_css']; ?>"><?php echo $tab['icon']; ?></div>
            <?php endif; ?>
            <?php if ($tab['name']): ?>
            <h3><?php echo $tab['name']; ?></h3>
            <?php endif; ?>
            <?php echo $tab['content']; ?>
          </div>
          <?php endforeach; ?>
          <!-- huwen added -->
          <div style="display: none;">
           <div style="text-align: center">
              <canvas class="sketchpad" id="sketchpad"><canvas>
            </div>
            <div style="text-align: center">
              <button onclick="undo()">撤销</button>
              <button onclick="redo()">重来</button>
              <input id="color-picker" type="color">
              <input id="size-picker" type="range" min="1" max="50">
              <button onclick="animateSketchpad()">动画</button>
            </div>

            <div style="text-align: center; margin: 12px 0 12px">
              <button id="recover-button" onclick="recover()">recover</button>
            </div>
            <div style="text-align: center">
              <canvas class="sketchpad" id="other-sketchpad"><canvas>
            </div>
            </div>


          <div class="image-gallery" style="display: none !important;">
            <?php if ($thumb) { ?>
            <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="swipebox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>

            <?php } ?>
            <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
            <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="swipebox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
            <?php } ?>
	    <!-- ishanks added it for option pictures -->
            <?php if ($options) { ?>
            <?php foreach ($options as $option) { ?>
            <?php foreach ($option['product_option_value'] as $option_value) { ?>
            <?php if($option_value['thumb']) { ?>
            <a href="<?php echo $option_value['popup']; ?>" title="<?php echo $heading_title; ?>" class="swipebox" id="<?php echo $option_value['option_value_id']; ?>" ><img src="<?php echo $option_value['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </div>
          <?php if ($this->journal2->settings->get('share_buttons_status') && (!Journal2Cache::$mobile_detect->isMobile() || (Journal2Cache::$mobile_detect->isMobile() && !$this->journal2->settings->get('share_buttons_disable_on_mobile', 1))) && $this->journal2->settings->get('share_buttons_position') === 'left' && count($this->journal2->settings->get('config_share_buttons', array()))): ?>
          <div class="social share-this <?php echo $this->journal2->settings->get('share_buttons_disable_on_mobile', 1) ? 'hide-on-mobile' : ''; ?>">
            <div class="social-loaded">
              <script type="text/javascript">var switchTo5x=true;</script>
              <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
              <script type="text/javascript">stLight.options({publisher: "<?php echo $this->journal2->settings->get('share_buttons_account_key'); ?>", doNotHash: true, doNotCopy: true, hashAddressBar: false});</script>
              <?php foreach ($this->journal2->settings->get('config_share_buttons', array()) as $item): ?>
              <span class="<?php echo $item['class'] . $this->journal2->settings->get('share_buttons_style'); ?>" displayText="<?php echo $this->journal2->settings->get('share_buttons_style') ? $item['name'] : ''; ?>"></span>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
          <div class="product-tabs">
            <?php if ($this->journal2->settings->get('share_buttons_status') && (!Journal2Cache::$mobile_detect->isMobile() || (Journal2Cache::$mobile_detect->isMobile() && !$this->journal2->settings->get('share_buttons_disable_on_mobile', 1))) && $this->journal2->settings->get('share_buttons_position') === 'bottom' && count($this->journal2->settings->get('config_share_buttons', array()))): ?>
            <div class="social share-this <?php echo $this->journal2->settings->get('share_buttons_disable_on_mobile', 1) ? 'hide-on-mobile' : ''; ?>">
              <div class="social-loaded">
                <script type="text/javascript">var switchTo5x=true;</script>
                <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
                <script type="text/javascript">stLight.options({publisher: "<?php echo $this->journal2->settings->get('share_buttons_account_key'); ?>", doNotHash: true, doNotCopy: true, hashAddressBar: false});</script>
                <?php foreach ($this->journal2->settings->get('config_share_buttons', array()) as $item): ?>
                <span class="<?php echo $item['class'] . $this->journal2->settings->get('share_buttons_style'); ?>" displayText="<?php echo $this->journal2->settings->get('share_buttons_style') ? $item['name'] : ''; ?>"></span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
          <ul id="tabs" class="nav nav-tabs htabs">
            <?php $is_active = true; ?>
            <?php if (!$this->journal2->settings->get('hide_product_description')) { ?>
            <li <?php if ($is_active) { echo 'class="active"'; $is_active = false; } ;?>><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
            <?php } ?>
            <?php if ($attribute_groups) { ?>
            <li <?php if ($is_active) { echo 'class="active"'; $is_active = false; } ;?>><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
            <?php } ?>
            <?php if ($review_status) { ?>
            <li <?php if ($is_active) { echo 'class="active"'; $is_active = false; } ;?>><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
            <?php } ?>
            <?php $index = 0; foreach ($this->journal2->settings->get('additional_product_tabs', array()) as $tab): $index++; ?>
            <li <?php if ($is_active) { echo 'class="active"'; $is_active = false; } ;?>><a href="#additional-product-tab-<?php echo $index; ?>" data-toggle="tab"><?php echo $tab['name']; ?></a></li>
            <?php endforeach; ?>
          </ul>
          <div class="tabs-content">
            <?php $is_active = true; ?>
            <?php if (!$this->journal2->settings->get('hide_product_description')) { ?>
            <div class="tab-pane tab-content <?php if ($is_active) { echo 'active'; $is_active = false; } ;?>" id="tab-description"><?php echo $description; ?></div>
            <?php } ?>
            <?php if ($attribute_groups) { ?>
            <div class="tab-pane tab-content <?php if ($is_active) { echo 'active'; $is_active = false; } ;?>" id="tab-specification">
              <table class="table table-bordered attribute">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                  <tr>
                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                  <tr>
                    <td><?php echo $attribute['name']; ?></td>
                    <td><?php echo $attribute['text']; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <?php } ?>
              </table>
            </div>
            <?php } ?>
            <?php if ($review_status) { ?>
            <div class="tab-pane tab-content <?php if ($is_active) { echo 'active'; $is_active = false; } ;?>" id="tab-review" <?php if ($rating): ?>itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"<?php endif; ?>>
                <?php if ($rating): ?>
                <meta itemprop="ratingValue" content="<?php echo $rating; ?>" />
                <meta itemprop="reviewCount" content="<?php echo $this->journal2->settings->get('product_num_reviews'); ?>" />
                <meta itemprop="bestRating" content="5" />
                <meta itemprop="worstRating" content="1" />
                <?php endif; ?>
              <form class="form-horizontal" id="form-review">
                <div id="review"></div>

                <?php // Wenliang modified, only review_user_brought can see all
                if ($review_guest || $review_user_brought) { ?>
                <h2 id="review-title"><?php echo $text_write; ?></h2>
                <?php /*
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                    <input type="text" name="name" value="" id="input-name" class="form-control" />
                  </div>
                </div>
                */ ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <div class="help-block"><?php echo $text_note; ?></div>
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label"><?php echo $entry_rating; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="rating" value="1" />
                    &nbsp;
                    <input type="radio" name="rating" value="2" />
                    &nbsp;
                    <input type="radio" name="rating" value="3" />
                    &nbsp;
                    <input type="radio" name="rating" value="4" />
                    &nbsp;
                    <input type="radio" name="rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
                </div>
                <br/>
                <?php if (version_compare(VERSION, '2.0.2', '<')): ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
                    <input type="text" name="captcha" value="" id="input-captcha" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12"> <img src="index.php?route=tool/captcha" alt="" id="captcha" /> </div>
                </div>
                <?php elseif (version_compare(VERSION, '2.1', '<')): ?>
                <?php if ($site_key) { ?>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
                    </div>
                  </div>
                <?php } ?>
                <?php else: ?>
                <?php echo $captcha; ?>
                <?php endif; ?>
                <div class="buttons">
                  <div class="pull-right">
                    <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary button"><?php echo $button_continue; ?></button>
                  </div>
                </div>
                <?php } else if (!$review_guest && !$review_user_logged) { // Wenliang added for guest ?>
                <h2 id="review-title"><?php echo $text_write; ?></h2>
                <?php echo $text_login; ?>
                <?php }?>
              </form>
            </div>
            <?php } ?>
            <?php $index = 0; foreach ($this->journal2->settings->get('additional_product_tabs', array()) as $tab): $index++; ?>
              <div id="additional-product-tab-<?php echo $index; ?>" class="tab-pane tab-content journal-custom-tab <?php if ($is_active) { echo 'active'; $is_active = false; } ;?>"><?php echo $tab['content']; ?></div>
            <?php endforeach; ?>
          </div>
          </div>
        </div>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="right">
          <?php if ($this->journal2->settings->get('product_page_title_position', 'top') === 'right'): ?>
          <h1 class="heading-title" itemprop="name"><?php echo $heading_title; ?></h1>
          <?php endif; ?>
          <div id="product" class="product-options">
            <?php foreach ($this->journal2->settings->get('additional_product_description_top', array()) as $tab): ?>
            <div class="journal-custom-tab">
              <?php if ($tab['has_icon']): ?>
              <div class="block-icon block-icon-left" style="<?php echo $tab['icon_css']; ?>"><?php echo $tab['icon']; ?></div>
              <?php endif; ?>
              <?php if ($tab['name']): ?>
              <h3><?php echo $tab['name']; ?></h3>
              <?php endif; ?>
              <?php echo $tab['content']; ?>
            </div>
            <?php endforeach; ?>
          <ul class="list-unstyled description">
            <?php if($this->journal2->settings->get('product_views')): ?>
              <!-- huwen added for verify -->
              <?php if ($verify!='') { ?>
                <?php if ($verify_erro) { ?>
                  <li class="product-views-count"><?php echo '本商品有可能为假冒伪劣产品！'; ?></li>
                <?php }else{ ?>
                  <li class="product-views-count"><?php echo '本商品被验证的次数'; ?>: <?php echo $scan_counter; ?></li>
                <?php } ?>
              <?php }else{ ?>
              <li class="product-views-count"><?php echo $this->journal2->settings->get('product_page_options_views_text'); ?>: <?php echo $this->journal2->settings->get('product_views'); ?></li>
            <?php } ?>

            <?php endif; ?>
            <?php if($this->journal2->settings->get('manufacturer_image') == 'on'): ?>
            <li class="brand-logo">
                <a href="<?php echo $manufacturers; ?>" class="brand-image">
                    <img src="<?php echo $manufacturer_image; ?>" width="<?php echo $manufacturer_image_width; ?>" height="<?php echo $manufacturer_image_height; ?>" alt="<?php echo $manufacturer; ?>" />
                </a>
                <?php if(isset($manufacturer_image_name) && $manufacturer_image_name): ?>
                <a href="<?php echo $manufacturers; ?>" class="brand-logo-text">
                    <?php echo $manufacturer_image_name; ?>
                </a>
                <?php endif; ?>
            </li>
            <?php else: ?>
            <?php if ($manufacturer) { ?>
            <li class="p-brand"><?php echo $text_manufacturer; ?> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
            <?php } ?>
            <?php endif; ?>
            <li class="p-model"><?php echo $text_model; ?> <span class="p-model" itemprop="model"><?php echo $model; ?></span></li>
            <?php if ($reward) { ?>
            <li class="p-rewards"><?php echo $text_reward; ?> <span class="p-rewards"><?php echo $reward; ?></span></li>
            <?php } ?>
            <li class="p-stock"><?php echo $text_stock; ?> <span class="journal-stock <?php echo isset($stock_status) ? $stock_status : ''; ?>"><?php echo $stock; ?></span></li>
          </ul>
          <?php if($this->journal2->settings->get('product_sold')): ?>
          <div class="product-sold-count-text"><?php echo $this->journal2->settings->get('product_sold'); ?></div>
          <?php endif; ?>
          <?php if (isset($date_end) && $date_end && $this->journal2->settings->get('show_countdown_product_page', 'on') == 'on'): ?>
          <div class="countdown-wrapper"><div class="expire-text"><?php echo $this->journal2->settings->get('countdown_product_page_title'); ?></div><div class="countdown"></div></div>
          <script>Journal.countdown($('.right .countdown'), '<?php echo $date_end; ?>');</script>
          <?php endif; ?>
          <?php if ($price) { ?>
          <ul class="list-unstyled price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <meta itemprop="itemCondition" content="http://schema.org/NewCondition" />
            <meta itemprop="priceCurrency" content="<?php echo $this->journal2->settings->get('product_price_currency'); ?>" />
            <?php if ($this->journal2->settings->get('product_in_stock') === 'yes'): ?>
            <link itemprop="availability"  href="http://schema.org/InStock" />
            <?php endif; ?>
            <?php if (!$special) { ?>
            <li class="product-price" itemprop="price"><?php echo $price; ?></li>
            <?php } else { ?>
            <li class="price-old"><?php echo $price; ?></li>
            <li class="price-new" itemprop="price"><?php echo $special; ?></li>
            <?php } ?>
            <?php if ($tax) { ?>
            <li class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></li>
            <?php } ?>
            <?php if ($points) { ?>
            <li class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></li>
            <?php } ?>
            <?php if ($discounts) { ?>
            <?php foreach ($discounts as $discount) { ?>
            <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
            <?php } ?>
            <?php } ?>
          </ul>
          <?php } ?>
          <?php if ($options) { ?>
            <div class="options <?php echo $this->journal2->settings->get('product_page_options_push_classes'); ?>">
            <h3><?php echo $text_option; ?></h3>
            <?php foreach ($options as $option) { ?>
            <?php if ($option['type'] == 'select') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?> option-<?php echo $option['type']; ?> mult-option">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <?php if ($option_value['has_inventory']): ?>
                <option value="<?php echo $option_value['product_option_value_id']; ?>">
                <?php else: ?>
                <option value="<?php echo $option_value['product_option_value_id']; ?>" disabled="disabled" >
                <?php endif ?>
                <?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                <?php } ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'radio') { ?>
            <script>
				function new_onclick($i, $j){
					//$i represents the ith sub option and $j represnets the jth value of the $ith option of the clicked option
					//in the following code, the loop variables i and the j are usuasily represents the current jth value of the ith option in the loop
					var product_option_id = <?php echo $option['product_option_id'] ?>;
					if(!document.getElementById(product_option_id+' '+$i+' '+$j).disabled){
						var option = "<?php echo implode(',,',$option['possible_values']) ?>";
						//get the set of the sub options
						option = option.split(",,");
						for(var i=0;i<option.length;i++){
 							option[i] = option[i].split(",");
						}
						//the the origin name of the option_values
						var opt = "<?php echo $option['combined_opts']; ?>";
						opt = opt.split(",,");
						for(var i=0;i<opt.length;i++){
							opt[i] = opt[i].split(",");
						}
						//change the statue of the clicked option
  						for(var j=0;j<opt[$i].length;j++){
  							if(j == $j) {
  								if(document.getElementById(product_option_id+' '+$i+' '+j+'_').name == "selected"){
									document.getElementById(product_option_id+' '+$i+' '+j+'_').name = "unselected";
								}else{
									document.getElementById(product_option_id+' '+$i+' '+j+'_').name = "selected";
								}
  							}
  							else {
  								document.getElementById(product_option_id+' '+$i+' '+j+'_').name = "unselected";
  							}
						}
						var checked =  new Array(opt.length);
						//get all the checked option
						for(var i=0;i<opt.length;i++){
							for(var j=0;j<opt[i].length;j++){
								if(document.getElementById(product_option_id+' '+i+' '+j+'_').name == "selected"){
									checked[i] = opt[i][j];
								}
							}
						}

						all_checked = 1
						for(var i=0;i<opt.length;i++){
							if(!checked[i]){
								all_checked=0;
							}
						}
						//if all the option are selected refresh the price
						var product_option_value_id;
						if(all_checked){
							var name_id_price_prefixs = "<?php echo str_replace( '，' , ',' , implode(',,,,',$option['name_id_price_prefix'])) ?>";
							var name_id_price_prefixs = name_id_price_prefixs.split(",,,,");
							for(var i=0;i<name_id_price_prefixs.length;++i){
								name_id_price_prefixs[i]=name_id_price_prefixs[i].split(",,,");
							}

							var string_price = "<?php echo $price ?>";
							var currency = string_price.substring(0, 1);
							var	price = parseFloat(string_price.substring(1,string_price.length));
							var optionname = checked.join(",");
							var option_price;
							var price_prefix;
							for(var i=0;i<name_id_price_prefixs.length ;i++){
								if(optionname == name_id_price_prefixs[i][0]){
									product_option_value_id = parseInt(name_id_price_prefixs[i][1]);
									//option_price = name_id_price_prefixs[i][2];
									//price_prefix = name_id_price_prefixs[i][3];
									document.getElementById("hidden"+product_option_id).value=product_option_value_id;
									if(document.getElementById("img"+product_option_value_id)) document.getElementById("img"+product_option_value_id).click();
									break;
								}
							}
						}
						else{
							document.getElementById("hidden"+product_option_id).value="";
						}

						//to change the choose_able options
						for(var i=0;i<opt.length;i++){
							for(var j=0; j<opt[i].length;j++){
								//check the ith option's jth radio
								var flag = 0;
								//to find the proper option which satisfies the condition.
								for(var k=0;k<option.length;k++){
									var temp = 1;
									for(var l=0;l<checked.length;l++){
										//when the option_id is the same, the value should be the same and when the option_id is not the same, it should be the same as the selected option or this option is not selected
										if((checked[l] && l!=i && option[k][l]!=checked[l]) || (l==i && opt[i][j]!=option[k][i])){
											temp = 0;
											break;
										}
									}
									flag += temp;
								}

								if(!flag){
									document.getElementById(product_option_id+' '+i+' '+j).disabled=true;
									document.getElementById(product_option_id+' '+i+' '+j+'_').style.display="none";
								}else{
									document.getElementById(product_option_id+' '+i+' '+j).disabled=false;
									document.getElementById(product_option_id+' '+i+' '+j+'_').style.display="";
								}
							}
						}
					}
				}
			</script>
			<?php if(count($option['names'])>1){ ?>
            <?php for($i=0;$i<count($option['names']);$i++){ ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?> option-<?php echo $option['type']; ?> multi-option">
              <label class="control-label"><?php echo $option['names'][$i]; ?></label>
              <div id="input-option<?php echo $option['product_option_id'].'_'.$i; ?>">
                <?php for($j=0;$j<count($option['opts'][$i]);$j++) { ?>
                <div class="radio">
                  <label>
                    <input type="radio" checked=false name="not_in_use_option[<?php echo $option['product_option_id']; ?>].[<?php echo $i ?>]" id="<?php echo $option['product_option_id'].' '.$i.' '.$j ?>" value="<?php echo $option['opts'][$i][$j]; ?>" onclick="<?php echo "new_onclick($i,$j)" ?>" />
                    <?php echo $option['opts'][$i][$j]; ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="hidden<?php echo $option['product_option_id']; ?>" />
			<?php }else{ ?>
			<div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?> option-<?php echo $option['type']; ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>

            <?php } ?>

            <?php if ($option['type'] == 'checkbox') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?> option-<?php echo $option['type']; ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'image') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?> option-<?php echo $option['type']; ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'text') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'textarea') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'file') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block btn-upload"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
              <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'date') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group date">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'datetime') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group datetime">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'time') { ?>
            <div class="option form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group time">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php } ?>
            </div>
            <script>Journal.enableSelectOptionAsButtonsList();</script>
            <?php } ?>
            <?php if ($recurrings) { ?>
            <hr>
            <h3><?php echo $text_payment_recurring ?></h3>
            <div class="form-group required">
              <select name="recurring_id" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($recurrings as $recurring) { ?>
                <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
                <?php } ?>
              </select>
              <div class="help-block" id="recurring-description"></div>
            </div>
          <?php } ?>
            <div class="form-group cart <?php echo isset($labels) && is_array($labels) && isset($labels['outofstock']) ? 'outofstock' : ''; ?>">
              <div>
              <?php if($this->journal2->settings->get('hide_add_to_cart_button')): ?>
              <?php foreach ($this->journal2->settings->get('additional_product_enquiry', array()) as $tab): ?>
              <div><?php echo $tab['content']; ?></div>
              <?php endforeach; ?>
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <?php else: ?>
                <span class="qty">
              <label class="control-label text-qty" for="input-quantity"><?php echo $entry_qty; ?></label>
              <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" data-min-value="<?php echo $minimum; ?>" id="input-quantity" class="form-control" />
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <script>
                /* quantity buttons */
                var $input = $('.cart input[name="quantity"]');
                function up() {
                  var val = parseInt($input.val(), 10) + 1 || parseInt($input.attr('data-min-value'), 10);
                  $input.val(val);
                }
                function down() {
                  var val = parseInt($input.val(), 10) - 1 || 0;
                  var min = parseInt($input.attr('data-min-value'), 10) || 1;
                  $input.val(Math.max(val, min));
                }
                $('<a href="javascript:;" class="journal-stepper">-</a>').insertBefore($input).click(down);
                $('<a href="javascript:;" class="journal-stepper">+</a>').insertAfter($input).click(up);
                $input.keydown(function (e) {
                  if (e.which === 38) {
                    up();
                    return false;
                  }
                  if (e.which === 40) {
                    down();
                    return false;
                  }
                });
              </script>
              </span>
                <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="button"><span class="button-cart-text"><?php echo $button_cart; ?></span></button>
                <?php endif; ?>
              </div>
            </div>
            <?php if ($minimum > 1) { ?>
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
            <?php } ?>
            <div class="wishlist-compare">
              <span class="links">
                  <a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>
                  <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a>
              </span>
            </div>
          <?php if ($review_status) { ?>
          <div class="rating">
            <p>
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($rating < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } ?>
              <?php } ?>
              <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a></p>
          </div>
          <?php } ?>
            <?php if ($this->journal2->settings->get('share_buttons_status') && (!Journal2Cache::$mobile_detect->isMobile() || (Journal2Cache::$mobile_detect->isMobile() && !$this->journal2->settings->get('share_buttons_disable_on_mobile', 1))) && $this->journal2->settings->get('share_buttons_position') === 'right' && count($this->journal2->settings->get('config_share_buttons', array()))): ?>
            <div class="social share-this <?php echo $this->journal2->settings->get('share_buttons_disable_on_mobile', 1) ? 'hide-on-mobile' : ''; ?>">
              <div class="social-loaded">
                <script type="text/javascript">var switchTo5x=true;</script>
                <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
                <script type="text/javascript">stLight.options({publisher: "<?php echo $this->journal2->settings->get('share_buttons_account_key'); ?>", doNotHash: true, doNotCopy: true, hashAddressBar: false});</script>
                <?php foreach ($this->journal2->settings->get('config_share_buttons', array()) as $item): ?>
                <span class="<?php echo $item['class'] . $this->journal2->settings->get('share_buttons_style'); ?>" displayText="<?php echo $this->journal2->settings->get('share_buttons_style') ? $item['name'] : ''; ?>"></span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
            <?php foreach ($this->journal2->settings->get('additional_product_description_bottom', array()) as $tab): ?>
            <div class="journal-custom-tab">
              <?php if ($tab['has_icon']): ?>
              <div class="block-icon block-icon-left" style="<?php echo $tab['icon_css']; ?>"><?php echo $tab['icon']; ?></div>
              <?php endif; ?>
              <?php if ($tab['name']): ?>
              <h3><?php echo $tab['name']; ?></h3>
              <?php endif; ?>
              <?php echo $tab['content']; ?>
            </div>
            <?php endforeach; ?>
           </div>
          </div>
        </div>
      <?php if ($tags) { ?>
      <p class="tags"><b><?php echo $text_tags; ?></b>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php if ($products && $this->journal2->settings->get('related_products_status')) { ?>
      <div class="box related-products <?php echo $this->journal2->settings->get('related_products_carousel') ? 'journal-carousel' : ''; ?>">
        <div>
          <div class="box-heading"><?php echo $text_related; ?></div>
          <div class="box-product">
            <?php foreach ($products as $product) { ?>
            <div class="product-grid-item <?php echo $this->journal2->settings->get('related_products_grid_classes'); ?> display-<?php echo $this->journal2->settings->get('product_grid_wishlist_icon_display'); ?> <?php echo $this->journal2->settings->get('product_grid_button_block_button'); ?>">
              <div class="product-thumb product-wrapper <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>">
                <div class="image">
                  <a href="<?php echo $product['href']; ?>" <?php if(isset($product['thumb2']) && $product['thumb2']): ?> class="has-second-image" style="background: url('<?php echo $product['thumb2']; ?>') no-repeat;" <?php endif; ?>>
                  <img class="lazy first-image" src="<?php echo $this->journal2->settings->get('product_dummy_image'); ?>" data-src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                  </a>
                  <?php if (isset($product['labels']) && is_array($product['labels'])): ?>
                  <?php foreach ($product['labels'] as $label => $name): ?>
                  <?php if ($label === 'outofstock'): ?>
                  <img class="outofstock" <?php echo Journal2Utils::getRibbonSize($this->journal2->settings->get('out_of_stock_ribbon_size')); ?> style="position: absolute; top: 0; left: 0" src="<?php echo Journal2Utils::generateRibbon($name, $this->journal2->settings->get('out_of_stock_ribbon_size'), $this->journal2->settings->get('out_of_stock_font_color'), $this->journal2->settings->get('out_of_stock_bg')); ?>" alt="" />
                  <?php else: ?>
                  <span class="label-<?php echo $label; ?>"><b><?php echo $name; ?></b></span>
                  <?php endif; ?>
                  <?php endforeach; ?>
                  <?php endif; ?>
                  <?php if($this->journal2->settings->get('product_grid_wishlist_icon_position') === 'image' && $this->journal2->settings->get('product_grid_wishlist_icon_display', '') === 'icon'): ?>
                  <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_wishlist; ?>"><i class="wishlist-icon"></i><span class="button-wishlist-text"><?php echo $button_wishlist;?></span></a></div>
                  <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_compare; ?>"><i class="compare-icon"></i><span class="button-compare-text"><?php echo $button_compare;?></span></a></div>
                  <?php endif; ?>
                </div>
                <div class="product-details">
                  <div class="caption">
                    <h4 class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                    <p class="description"><?php echo $product['description']; ?></p>
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
                    <?php if ($product['price']) { ?>
                    <p class="price">
                      <?php if (!$product['special']) { ?>
                      <?php echo $product['price']; ?>
                      <?php } else { ?>
                      <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new" <?php echo isset($product['date_end']) && $product['date_end'] ? "data-end-date='{$product['date_end']}'" : ""; ?>><?php echo $product['special']; ?></span>
                      <?php } ?>
                      <?php if ($product['tax']) { ?>
                      <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                      <?php } ?>
                    </p>
                    <?php } ?>
                  </div>
                  <div class="button-group">
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
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php /* enable countdown */ ?>
      <?php if ($this->journal2->settings->get('show_countdown', 'never') !== 'never'): ?>
      <script>
        $('.related-products .product-grid-item > div').each(function () {
          var $new = $(this).find('.price-new');
          if ($new.length && $new.attr('data-end-date')) {
            $(this).find('.image').append('<div class="countdown"></div>');
          }
          Journal.countdown($(this).find('.countdown'), $new.attr('data-end-date'));
        });
      </script>
      <?php endif; ?>
      <?php if ($this->journal2->settings->get('related_products_carousel')): ?>
      <?php
        $grid = Journal2Utils::getItemGrid($this->journal2->settings->get('related_products_items_per_row'), $this->journal2->settings->get('site_width', 1024), $this->journal2->settings->get('config_columns_count'));
      $grid = array(
      array(0, (int)$grid['xs']),
      array(470, (int)$grid['sm']),
      array(760, (int)$grid['md']),
      array(980, (int)$grid['lg']),
      array(1100, (int)$grid['xl'])
      );
      ?>
      <script>
        (function () {
          var opts = {
            itemsCustom: $.parseJSON('<?php echo json_encode($grid); ?>'),
            navigation:true,
            scrollPerPage:true,
            navigationText : false,
            paginationSpeed:parseInt('<?php echo $this->journal2->settings->get('related_products_carousel_transition_speed', 400); ?>', 10),
          margin:15
        }
        <?php if (!$this->journal2->settings->get('related_products_carousel_autoplay')): ?>
        opts.autoPlay = false;
        <?php else: ?>
        opts.autoPlay = parseInt('<?php echo $this->journal2->settings->get('related_products_carousel_transition_delay', 1000); ?>', 10);
        <?php endif; ?>
        <?php if ($this->journal2->settings->get('related_products_carousel_pause_on_hover')): ?>
        opts.stopOnHover = true;
        <?php endif; ?>
        <?php if (!$this->journal2->settings->get('related_products_carousel_touch_drag')): ?>
        opts.touchDrag = false;
        <?php endif; ?>
        jQuery(".related-products .box-product").owlCarousel(opts);
        <?php if ($this->journal2->settings->get('related_products_carousel_arrows') === 'side'): ?>
        $('.related-products .box-product .owl-buttons').addClass('side-buttons');
        <?php endif; ?>

        <?php if ($this->journal2->settings->get('related_products_carousel_arrows') === 'none'): ?>
        $('.related-products .box-product .owl-buttons').hide();
        <?php endif; ?>

        <?php if (!$this->journal2->settings->get('related_products_carousel_bullets')): ?>
        $('.related-products .box-product .owl-pagination').hide();
        <?php endif; ?>
        })();
      </script>
      <?php endif; ?>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));
						if(element.attr('id')){
							if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
							} else {
								element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');}
						}else{

							var opts = "";
							<?php $temp_recorder_for_opts = array() ;?>
							<?php foreach($options as $option){ ?>
							<?php $temp_recorder_for_opts[] = $option['product_option_id'].',,,'.$option['name'].',,,'.$option['combined_opts']; ?>
							<?php } ?>
							opts = "<?php echo implode(',,,,',$temp_recorder_for_opts) ?>";
							opts = opts.split(',,,,');
							var all_opt = "";
							var all_name = "";

							for(j=0;j<opts.length;j++){
								opts[j] = opts[j].split(',,,');
								if(i==opts[j][0]){
									all_name = opts[j][1];
									all_opt = opts[j][2];
									break;
								}
							}
							//alert(all_opt);
							//alert(all_name);

							var side_words = json['error']['option'][i].split(all_name);
							all_opt = all_opt.replace("，",",");
							all_opt = all_opt.split(',,');
							for(j=0;j<all_opt.length;j++){
								all_opt[j] = all_opt[j].split(',');
							}
							//all_name sample: ProductOptionName(SubOption1 name, SubOption2 name, ...)
							all_name = all_name.replace("（","(");
							all_name = all_name.replace("）","(");
							all_name = all_name.replace(")","(");
							all_name = all_name.replace("，",",");
							all_name = all_name.split('(');

							for(j=0;j<all_name.length;j++){
								all_name[j] = all_name[j].split(',');
							}

							for(j=0;j<all_opt.length;j++){
								var need_to_warn = 1;
								for(k=0;k<all_opt[j].length;k++){
									//alert(i+' '+j+' '+k+'_');
									//alert(document.getElementById(i+' '+j+' '+k+'_').class);
									if(document.getElementById(i+' '+j+' '+k+'_').name=="selected"){
										need_to_warn=0;
										break;
									}
								}

								if(need_to_warn){
									var element = $('#input-option' + i.replace('_', '-')+'_'+j);
									element.after('<div class="text-danger">' + side_words.join(all_name[1][j])+ '</div>');
								}
							}
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
                if (!Journal.showNotification(json['success'], json['image'], true)) {
                    $('.breadcrumb').after('<div class="alert alert-success success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

				$('#cart-total').html(json['total']);

          if (Journal.scrollToTop) {
              $('html, body').animate({ scrollTop: 0 }, 'slow');
          }

				$('#cart ul').load('index.php?route=common/cart/info ul li');
			}
		},
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
    <?php if (version_compare(VERSION, '2.0.2', '<')): ?>
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
    <?php else: ?>
    data: $("#form-review").serialize(),
    <?php endif; ?>
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
      <?php if (version_compare(VERSION, '2.0.2', '<')): ?>
			$('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
			$('input[name=\'captcha\']').val('');
      <?php endif; ?>
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#review').after('<div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				$('#review').after('<div class="alert alert-success success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
        <?php if (version_compare(VERSION, '2.0.2', '<')): ?>
				$('input[name=\'captcha\']').val('');
        <?php endif; ?>
			}
		}
	});
});

$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
//--></script>
<script type="text/javascript">
    var sketchpad;
        $(document).ready(function() {
          sketchpad = new Sketchpad({
            element: '#sketchpad',
            width: 1000,
            height: 800,
            bgpic:'<?php echo $thumb; ?>'
          });
          $('#color-picker').change(color);
          $('#color-picker').val('#000');
          $('#size-picker').change(size);
          $('#size-picker').val(1);
        });

        function undo() {
          sketchpad.undo();
        }
        function redo() {
          sketchpad.redo();
        }
        function color(event) {
          sketchpad.color = $(event.target).val();
        }
        function size(event) {
          sketchpad.penSize = $(event.target).val();
        }
        function animateSketchpad() {
          sketchpad.animate(10);
        }

        function recover(event) {
          var settings = sketchpad.toObject();
          var json=sketchpad.toJSON();
          console.log(json);
          settings.element = '#other-sketchpad';
          var otherSketchpad = new Sketchpad(settings);
          $('#recover-button').hide();
        }
  </script>


<?php echo $footer; ?>
