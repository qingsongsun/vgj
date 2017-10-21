<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php echo $column_right; ?> 
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?> search-page">
      <h1 class="heading-title"><?php echo $heading_title; ?></h1>
      <?php echo $content_top; ?>
      <h2><?php echo $entry_search; ?></h2>
 <?php /*
      <div class="row content">
        <div class="col-sm-4">
          <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control" />
        </div>
        <div class="col-sm-3 s-cat">
          <select name="category_id" class="form-control">
            <option value="0"><?php echo $text_category; ?></option>
            <?php foreach ($categories as $category_1) { ?>
            <?php if ($category_1['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <?php if ($category_2['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_2['children'] as $category_3) { ?>
            <?php if ($category_3['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-3 s-check">
          <label class="checkbox-inline">
            <?php if ($sub_category) { ?>
            <input type="checkbox" name="sub_category" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="sub_category" value="1" />
            <?php } ?>
            <?php echo $text_sub_category; ?></label>
        </div>
        <div class="col-sm-3 s-check">
          <label class="checkbox-inline">
            <?php if ($description) { ?>
            <input type="checkbox" name="description" value="1" id="description" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="description" value="1" id="description" />
            <?php } ?>
            <?php echo $entry_description; ?></label>
        </div>	
      </div>
*/ ?>
     <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>     
        <div class="form-group required">
          <label class="col-sm-2 control-label"><?php echo $entry_scenario_name; ?></label>
              <?php foreach ($scenarios as $scenario) { ?>
                 <?php if ($selected_scenario) { ?>
				 	<?php if ($selected_scenario == $scenario['combination_name']) { ?>
						<input type="radio" name=selected_scenario value="<?php echo $scenario['combination_name']; ?>" checked="checked"><?php echo $scenario['combination_name']; ?>
					<?php } else { ?>
					    <input type="radio" name=selected_scenario value="<?php echo $scenario['combination_name']; ?>"><?php echo $scenario['combination_name']; ?>
					<?php } ?>
				 <?php } else { ?>
                     <input type="radio" name=selected_scenario value="<?php echo $scenario['combination_name']; ?>"><?php echo $scenario['combination_name']; ?>				 	   
                 <?php } ?>
              <?php } ?>
              <?php if ($error_scenario) { ?>
              <p/><div class="text-danger"><?php echo $error_scenario; ?></div>
              <?php } ?>              
        </div>          
     
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-totalcost"><?php echo $entry_total_cost; ?></label>
            <div class="col-sm-4">
              <input type="text" name="totalcost" value="<?php echo $totalcost; ?>" placeholder="<?php echo $entry_total_cost; ?>" id="input-totalcost" class="form-control" />
              <?php if ($error_totalcost) { ?>
              <div class="text-danger"><?php echo $error_totalcost; ?></div>
              <?php } ?>
            </div>
          </div>
       
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bundlequantity"><?php echo $entry_bundle_quantity; ?></label>
            <div class="col-sm-4">
              <input type="text" name="bundlequantity" value="<?php echo $bundlequantity; ?>" placeholder="<?php echo $entry_bundle_quantity; ?>" id="input-bundlequantity" class="form-control" />
              <?php if ($error_bundlequantity) { ?>
              <div class="text-danger"><?php echo $error_bundlequantity; ?></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-subquantity"><?php echo $entry_sub_quantity; ?></label>
            <div class="col-sm-4">
              <input type="text" name="subquantity" value="<?php echo $subquantity; ?>"  placeholder="<?php echo $entry_sub_quantity; ?>" id="input-subquantity" class="form-control" />
              <?php if ($error_subquantity) { ?>
              <div class="text-danger"><?php echo $error_subquantity; ?></div>
              <?php } ?>
            </div>
          </div>
      </fieldset>		  
      <div class="buttons">
        <div class="right">
          <input type="submit" value="<?php echo $button_search; ?>" class="btn btn-primary button" />
        </div>
      </div>
	  
	  </form>
      
      <h2><?php echo $text_search; ?></h2>	  
      <?php if ($products) { ?>
      <div class="product-filter">
        <div class="display">
          <a onclick="Journal.gridView()" class="grid-view"><?php echo $this->journal2->settings->get("category_grid_view_icon", $button_grid); ?></a>
          <a onclick="Journal.listView()" class="list-view"><?php echo $this->journal2->settings->get("category_list_view_icon", $button_list); ?></a>
        </div>
        <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div>
      </div>
 
 	<?php $i_outer = 0; foreach ($products as $key => $products_inner) { ?>	  
	<legend><?php $key++; echo $bundle_title.' '.$key; ?></legend>
      <div class="row main-products product-list" data-grid-classes="<?php echo $this->journal2->settings->get('product_grid_classes'); ?> display-<?php echo $this->journal2->settings->get('product_grid_wishlist_icon_display'); ?> <?php echo $this->journal2->settings->get('product_grid_button_block_button'); ?>">
        <?php  foreach ($products_inner as $product) { ?> 
        <div class="product-list-item xs-100 sm-100 md-100 lg-100 xl-100">
          <div class="product-thumb <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>">
            <div class="image">
              <a href="<?php echo $product['href']; ?>" <?php if(isset($product['thumb2']) && $product['thumb2']): ?> class="has-second-image" style="background: url('<?php echo $product['thumb2']; ?>') no-repeat;" <?php endif; ?>>
                  <img class="lazy first-image" width="<?php echo $this->journal2->settings->get('config_image_width'); ?>" height="<?php echo $this->journal2->settings->get('config_image_height'); ?>" src="<?php echo $this->journal2->settings->get('product_dummy_image'); ?>" data-src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
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
        <?php } // the inner foreach ?> 
      </div>
	    <fieldset>
        <label class="col-sm-6 control-label"><?php echo $entry_bundle_cost, $bundle_cost[$i_outer]; ?></label>
        <label class="col-sm-6 control-label"><?php echo $entry_total_cost, $bundle_cost[$i_outer]*$bundlequantity; ?></label>
        <div class="button-group">
          <div class="cart_bundle">
                  <a onclick="addBundleToCart('<?php echo $product_list[$i_outer]; ?>', '<?php echo $bundlequantity; ?>');" class="button hint--top" data-hint="<?php echo $button_cart_bundle; ?>"><i class="button-left-icon"></i><span class="button-cart-text"><?php echo $button_cart_bundle; ?></span><i class="button-right-icon"></i></a>
          </div>
        </div>            
        </fieldset>
	        <br />
      <?php $i_outer++;} //the outer foreach ?>   
  
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>     
      <?php echo $content_bottom; ?></div> 

</div>
    <script>Journal.applyView('<?php echo $this->journal2->settings->get("product_view", "grid"); ?>');</script>
    <?php if ($this->journal2->settings->get('show_countdown', 'never') !== 'never'): ?>
    <script>Journal.enableCountdown();</script>
    <?php endif; ?>
<?php echo $footer; ?>

