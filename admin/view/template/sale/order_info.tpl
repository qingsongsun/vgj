<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $invoice; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></a> <a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></a> <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>
          <!-- <li><a href="#tab-payment" data-toggle="tab"><?php //echo $tab_payment; ?></a></li> -->
          <?php if ($shipping_method) { ?>
          <li><a href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>
          <?php } ?>
          <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>
          <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
          <?php if ($payment_action) { ?>
          <li><a href="#tab-action" data-toggle="tab"><?php echo $tab_action; ?></a></li>
          <?php } ?>
          <?php if ($frauds) { ?>
          <?php foreach ($frauds as $fraud) { ?>
          <li><a href="#tab-<?php echo $fraud['code']; ?>" data-toggle="tab"><?php echo $fraud['title']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-order">
            <table class="table table-bordered">
              <tr>
                <td><?php echo $text_order_id; ?></td>
                <td>#<?php echo $order_id; ?></td>
              </tr>
              <!-- huwen added for user_checkout -->
              <tr>
                <td><?php echo '店员为用户买单：'; ?></td>
                <td><?php echo $user_checkout; ?></td>
              </tr>


              <tr>
              <?php if ($user_checkout=="是") { ?>
                <td><?php echo '店员名称：'; ?></td>
                <td><?php echo $user; ?></td>
                <?php } ?>
              </tr>


              <tr>
                <td><?php echo $text_invoice_no; ?></td>
                <td><?php if ($invoice_no) { ?>
                  <?php echo $invoice_no; ?>
                  <?php } else { ?>
                  <button id="button-invoice" class="btn btn-success btn-xs"><i class="fa fa-cog"></i> <?php echo $button_generate; ?></button>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_store_name; ?></td>
                <td><?php echo $store_name; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_store_url; ?></td>
                <td><a href="<?php echo $store_url; ?>" target="_blank"><?php echo $store_url; ?></a></td>
              </tr>
              <?php if ($customer) { ?>
              <tr>
                <td><?php echo $text_customer; ?></td>
                <td><a href="<?php echo $customer; ?>" target="_blank"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
              </tr>
              <?php } else { ?>
              <tr>
                <td><?php echo $text_customer; ?></td>
                <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
              </tr>
              <?php } ?>
              <?php if ($customer_group) { ?>
              <tr>
                <td><?php echo $text_customer_group; ?></td>
                <td><?php echo $customer_group; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_email; ?></td>
                <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
              </tr>
              <tr>
                <td><?php echo $text_telephone; ?></td>
                <td><?php echo $telephone; ?></td>
              </tr>
              <?php if ($fax) { ?>
              <tr>
                <td><?php echo $text_fax; ?></td>
                <td><?php echo $fax; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($account_custom_fields as $custom_field) { ?>
              <tr>
                <td><?php echo $custom_field['name']; ?>:</td>
                <td><?php echo $custom_field['value']; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_total; ?></td>
                <td><?php echo $total; ?></td>
              </tr>
              <?php if ($customer && $reward) { ?>
              <tr>
                <td><?php echo $text_reward; ?></td>
                <td><?php echo $reward; ?>
                  <?php if (!$reward_total) { ?>
                  <button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>
                  <?php } else { ?>
                  <button id="button-reward-remove" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_reward_remove; ?></button>
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <?php if ($order_status) { ?>
              <tr>
                <td><?php echo $text_order_status; ?></td>
                <td id="order-status"><?php echo $order_status; ?></td>
              </tr>
              <?php } ?>
              <?php if ($comment) { ?>
              <tr>
                <td><?php echo $text_comment; ?></td>
                <td><?php echo $comment; ?></td>
              </tr>
              <?php } ?>
              <?php if ($affiliate) { ?>
              <tr>
                <td><?php echo $text_affiliate; ?></td>
                <td><a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a></td>
              </tr>
              <tr>
                <td><?php echo $text_commission; ?></td>
                <td><?php echo $commission; ?>
                  <?php if (!$commission_total) { ?>
                  <button id="button-commission-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_commission_add; ?></button>
                  <?php } else { ?>
                  <button id="button-commission-remove" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_remove; ?></button>
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip) { ?>
              <tr>
                <td><?php echo $text_ip; ?></td>
                <td><?php echo $ip; ?></td>
              </tr>
              <?php } ?>
              <?php if ($forwarded_ip) { ?>
              <tr>
                <td><?php echo $text_forwarded_ip; ?></td>
                <td><?php echo $forwarded_ip; ?></td>
              </tr>
              <?php } ?>
              <?php if ($user_agent) { ?>
              <tr>
                <td><?php echo $text_user_agent; ?></td>
                <td><?php echo $user_agent; ?></td>
              </tr>
              <?php } ?>
              <?php if ($accept_language) { ?>
              <tr>
                <td><?php echo $text_accept_language; ?></td>
                <td><?php echo $accept_language; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_date_added; ?></td>
                <td><?php echo $date_added; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_date_modified; ?></td>
                <td><?php echo $date_modified; ?></td>
              </tr>
            </table>
          </div>
          <div class="tab-pane" id="tab-payment">
            <table class="table table-bordered">
              <tr>
                <td><?php echo $text_firstname; ?></td>
                <td><?php echo $payment_firstname; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_lastname; ?></td>
                <td><?php echo $payment_lastname; ?></td>
              </tr>
              <?php if ($payment_company) { ?>
              <tr>
                <td><?php echo $text_company; ?></td>
                <td><?php echo $payment_company; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_address_1; ?></td>
                <td><?php echo $payment_address_1; ?></td>
              </tr>
              <?php if ($payment_address_2) { ?>
              <tr>
                <td><?php echo $text_address_2; ?></td>
                <td><?php echo $payment_address_2; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_city; ?></td>
                <td><?php echo $payment_city; ?></td>
              </tr>
              <?php if ($payment_postcode) { ?>
              <tr>
                <td><?php echo $text_postcode; ?></td>
                <td><?php echo $payment_postcode; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_zone; ?></td>
                <td><?php echo $payment_zone; ?></td>
              </tr>
              <?php if ($payment_zone_code) { ?>
              <tr>
                <td><?php echo $text_zone_code; ?></td>
                <td><?php echo $payment_zone_code; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_country; ?></td>
                <td><?php echo $payment_country; ?></td>
              </tr>
              <?php foreach ($payment_custom_fields as $custom_field) { ?>
              <tr data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                <td><?php echo $custom_field['name']; ?>:</td>
                <td><?php echo $custom_field['value']; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_payment_method; ?></td>
                <td><?php echo $payment_method; ?></td>
              </tr>
            </table>
          </div>
          <?php if ($shipping_method) { ?>
          <div class="tab-pane" id="tab-shipping">
            <table class="table table-bordered">
              <tr>
                <td><?php echo $text_firstname; ?></td>
                <td><?php echo $shipping_firstname; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_lastname; ?></td>
                <td><?php echo $shipping_lastname; ?></td>
              </tr>
              <?php if ($shipping_company) { ?>
              <tr>
                <td><?php echo $text_company; ?></td>
                <td><?php echo $shipping_company; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_address_1; ?></td>
                <td><?php echo $shipping_address_1; ?></td>
              </tr>
              <?php if ($shipping_address_2) { ?>
              <tr>
                <td><?php echo $text_address_2; ?></td>
                <td><?php echo $shipping_address_2; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_city; ?></td>
                <td><?php echo $shipping_city; ?></td>
              </tr>
              <?php if ($shipping_postcode) { ?>
              <tr>
                <td><?php echo $text_postcode; ?></td>
                <td><?php echo $shipping_postcode; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_zone; ?></td>
                <td><?php echo $shipping_zone; ?></td>
              </tr>
              <?php if ($shipping_zone_code) { ?>
              <tr>
                <td><?php echo $text_zone_code; ?></td>
                <td><?php echo $shipping_zone_code; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_country; ?></td>
                <td><?php echo $shipping_country; ?></td>
              </tr>
              <?php foreach ($shipping_custom_fields as $custom_field) { ?>
              <tr data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                <td><?php echo $custom_field['name']; ?>:</td>
                <td><?php echo $custom_field['value']; ?></td>
              </tr>
              <?php } ?>
              <?php if ($shipping_method) { ?>
              <tr>
                <td><?php echo $text_shipping_method; ?></td>
                <td><?php echo $shipping_method; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
          <?php } ?>
          <div class="tab-pane" id="tab-product">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <!-- ishanks added the first column -->
                  <td class="text-left"><?php echo $column_image; ?></td>
                  <td class="text-left"><?php echo $column_product; ?></td>
                  <!-- huwen added the barcode column -->
                  <td class="text-left"><?php echo $column_barcode; ?></td>
                  <td class="text-left" style="display: none;"><?php echo $column_model; ?></td>
                  <td class="text-right"><?php echo $column_quantity; ?></td>
                  <td class="text-right"><?php echo $column_price; ?></td>
                  <td class="text-right"><?php echo $column_total; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-left">
                  <?php $flag = 1 ?>
                  <?php foreach ($product['option'] as $option) { ?>
                    <?php if ($option['type'] != 'file') { ?>
                		<?php if($option['image']){ ?>
                				<img src="<?php echo $option['image'];?>" href="<?php echo $option['href']; ?>" />
                		<?php $flag=0 ?>
                		<?php } ?>
                    <?php } ?>
                    <?php } ?>
                    <?php if ($flag){ ?>
                  	<img src="<?php echo $product['image']; ?>" href="<?php echo $product['href']; ?>" />
                  	<?php } ?>
                  </td>
                  <td class="text-left">
                  	<a href="<?php echo $product['href']; ?>" >
                  	<?php echo $product['name']; ?></a>
                    <?php foreach ($product['option'] as $option) { ?>
                    <br />
                    <?php if ($option['type'] != 'file') { ?>
                    &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                    <?php } else { ?>
                    &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                    <?php } ?>
                    <?php } ?></td>
                   <!-- huwen added  -->
                  <td class="text-left"><?php echo $barcode; ?></td>
                  <td class="text-left" style="display: none;"><?php echo $product['model']; ?></td>
                  <td class="text-right"><?php echo $product['quantity']; ?></td>
                  <td class="text-right"><?php echo $product['price']; ?></td>
                  <td class="text-right"><?php echo $product['total']; ?></td>
                </tr>
                <?php } ?>
                <?php foreach ($vouchers as $voucher) { ?>
                <tr>
                  <td class="text-left"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>
                  <td class="text-left"></td>
                  <td class="text-right">1</td>
                  <td class="text-right"><?php echo $voucher['amount']; ?></td>
                  <td class="text-right"><?php echo $voucher['amount']; ?></td>
                </tr>
                <?php } ?>
                <?php foreach ($totals as $total) { ?>
                <tr>
                  <td colspan="5" class="text-right"><?php echo $total['title']; ?>:</td>
                  <td class="text-right"><?php echo $total['text']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
            <br />
            <fieldset>
              <legend><?php echo $text_history; ?></legend>
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                  <div class="col-sm-10">
                    <select name="order_status_id" id="input-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_statuses) { ?>
                      <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                      <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group" id="notify">
                  <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
                  <div class="col-sm-10">
                    <input type="checkbox" name="notify" value="1" id="input-notify" />
                  </div>
                </div>

                <!-- Luke added begin -->
                <div class="form-group" id="repository" style="display: none;">
                  <label class="col-sm-2 control-label"><?php echo $entry_order_repository; ?></label>
                  <div class="col-sm-10">
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover" id="order_repo_table">
                        <thead><tr>
                          <td class="text-left"><?php echo $column_product; ?></td>
                          <td class="text-center" style="width: 5em;"><?php echo $column_quantity; ?></td>
                          <td class="text-center" style="width: 7em;"><?php echo $column_is_deliver; ?></td>
                          <td class="text-right"><?php echo $column_repository; ?></td>
                        </tr></thead>
                        <tbody>
                          <?php $is_deliver = true; ?>
                          <?php foreach ($products as $product): ?>
                            <?php $left_quantity = $product['quantity']; ?>
                            <?php foreach ($repositories[$product['order_product_id']] as $repo): ?>
                              <?php $left_quantity -= $repo['quantity']; ?>
                              <tr>
                                <td class="text-left">
                                  <a href="<?php echo $product['href']; ?>" >
                                  <?php echo $product['name']; ?></a>
                                  <?php foreach ($product['option'] as $option) { ?>
                                  <br />
                                  <?php if ($option['type'] != 'file') { ?>
                                  &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                  <?php } else { ?>
                                  &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                                  <?php } ?>
                                  <?php } ?>
                                </td>
                                <td class="text-center"><?php echo $repo['quantity']; ?></td>
                                <td class="text-center">是</td>
                                <td class="text-right"><?php echo $repo['name']; ?></td>
                              </tr>
                            <?php endforeach ?>
                            <?php if ($left_quantity): ?>
                              <?php $is_deliver = false; ?>
                              <tr>
                                <td class="text-left">
                                  <a href="<?php echo $product['href']; ?>" >
                                  <?php echo $product['name']; ?></a>
                                  <?php foreach ($product['option'] as $option) { ?>
                                  <br />
                                  <?php if ($option['type'] != 'file') { ?>
                                  &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                  <?php } else { ?>
                                  &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                                  <?php } ?>
                                  <?php } ?>
                                </td>
                                <td class="text-center"><?php echo $left_quantity; ?></td>
                                <td class="text-center">否</td>
                                <td class="text-right">
                                  <select id="prod_repo-<?php echo $product['order_product_id'] ?>">
                                    <?php foreach ($available_repos[$product['order_product_id']] as $repo): ?>
                                      <option value="<?php echo $repo['id']; ?>"><?php echo $repo['name']; ?></option>
                                    <?php endforeach ?>
                                    <option value=""><?php echo $text_no_repo_selected; ?></option>
                                  </select>
                                </td>
                              </tr>
                            <?php endif ?>
                          <?php endforeach ?>
                        </tbody>
                        <tfoot><tr>
                          <td colspan="3"></td>
                          <td><button type="button" id="button-deliver" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary pull-right"><i class="fa fa-truck"></i> <?php echo $button_deliver; ?></button></td>
                        </tr></tfoot>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- Luke added end -->

                <div class="form-group" id="comment">
                  <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                  <div class="col-sm-10">
                    <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
                  </div>
                </div>
              </form>
              <div class="text-right">
                  <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
              </div>
              <!--ishanks added it-->
              <script>
                var value = $('#input-order-status option:selected').val();
                if(value==3){
                  html = "";
                  html += '<div class="form-group" id="shipping">';
                  html += '<label class="col-sm-2 control-label" for="input-shipping-company"><?php echo $entry_shipping_company; ?></label>';
                  html += '<div class="col-sm-3">';
                  html += '<select name="shipping_company" id="input-shipping-company" class="form-control">';
                  html += "<?php foreach($shipping_company_list as $name => $pingyin){ ?>";
                  html += "<option value='<?php echo $name?>' ><?php echo $name; ?></option>"
                  html += "<?php } ?>"
                  html += '</select>'
                  html += '</div>';
                  html += '<label class="col-sm-2 control-label" for="input-shipping-id"><?php echo $entry_shipping_id; ?></label>';
                  html += '<div class="col-sm-5">';
                  html += '<textarea name="shipping" rows="1" id="input-shipping-id" class="form-control" ></textarea>';
                  html += '</div>';
                  html += '</div>';

                  // Luke added begin
                  // for order product repository setting
                  $('#repository').css({
                    'display': 'block',
                  });
                  <?php if (!$is_deliver): ?>
                    $('#button-history').prop('disabled', 'true');
                  <?php endif ?>
                  // Luke added end

                  $('#notify').after(html);
                  //$('#input-notify').setAttribute('checked',true);
                }else{
                  if($('#shipping')){
                    $('#shipping').remove();
                  }
                }
              </script>
            </fieldset>
          </div>
          <?php if ($payment_action) { ?>
          <div class="tab-pane" id="tab-action"><?php echo $payment_action; ?></div>
          <?php } ?>
          <?php if ($frauds) { ?>
          <?php foreach ($frauds as $fraud) { ?>
          <div class="tab-pane" id="tab-<?php echo $fraud['code']; ?>">
          <?php echo $fraud['content']; ?>
          </div>
          <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$(document).delegate('#button-invoice', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-invoice').button('loading');
		},
		complete: function() {
			$('#button-invoice').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#tab-order').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['invoice_no']) {
				$('#button-invoice').replaceWith(json['invoice_no']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-add').button('loading');
		},
		complete: function() {
			$('#button-reward-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-reward-add').replaceWith('<button id="button-reward-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_reward_remove; ?></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-remove', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-remove').button('loading');
		},
		complete: function() {
			$('#button-reward-remove').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-reward-remove').replaceWith('<button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-add').button('loading');
		},
		complete: function() {
			$('#button-commission-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-commission-add').replaceWith('<button id="button-commission-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_remove; ?></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-remove', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-remove').button('loading');

		},
		complete: function() {
			$('#button-commission-remove').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-commission-remove').replaceWith('<button id="button-commission-add" class="btn btn-success btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_add; ?></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

//ishanks added it
$('#input-order-status').on('change', function(){
	var value = $('#input-order-status option:selected').val();
  if(value==3){
		html = "";
		html += '<div class="form-group" id="shipping">';
		html += '<label class="col-sm-2 control-label" for="input-shipping-company"><?php echo $entry_shipping_company; ?></label>';
		html += '<div class="col-sm-3">';
		html += '<select name="shipping_company" id="input-shipping-company" class="form-control">';
		html += "<?php foreach($shipping_company_list as $name => $pingyin){ ?>";
		html += "<option value='<?php echo $name?>' ><?php echo $name; ?></option>"
		html += "<?php } ?>"
		html += '</select>'
		html += '</div>';
		html += '<label class="col-sm-2 control-label" for="input-shipping-id"><?php echo $entry_shipping_id; ?></label>';
		html += '<div class="col-sm-5">';
		html += '<textarea name="shipping" rows="1" id="input-shipping-id" class="form-control" ></textarea>';
		html += '</div>';
		html += '</div>';
		html += '</div>';

    // Luke added begin
    $('#repository').css({
      'display': 'block'
    });
    // Luke added end

		$('#notify').after(html);
		//$('#input-notify').setAttribute('checked',true);

    <?php if (!$is_deliver): ?>
      $('#button-history').prop('disabled', 'true');
    <?php endif ?>

	}else{
    // Luke added begin
    $('#repository').css({
      'display': 'none'
    });
    // Luke added end
		if($('#shipping')){
			$('#shipping').remove();
		}

    $("#button-history").removeAttr('disabled');
	}
});

$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#history').load(this.href);
});

$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

$('#button-deliver').on('click', function() {
  var json_data = [];
  <?php foreach ($products as $product): ?>
    var item = {};
    item['order_product_id'] = <?php echo $product['order_product_id']; ?>;
    item['repository_id'] = $('#prod_repo-<?php echo $product['order_product_id']; ?>').val();
    item['quantity'] = <?php echo $product['quantity']; ?>;
    json_data.push(item);
  <?php endforeach ?>
  $.ajax({
    url: 'index.php?route=sale/order/order_repo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
    type: 'POST',
    dataType: 'json',
    data: JSON.stringify(json_data),
    beforeSend: function() {
      $('#button-deliver').button('loading');
    },
  })
  .done(function(data) {
    for (var id in data) {
      if (data[id]) {
        var obj = $('#prod_repo-'+id).parent();
        var repo_name = $('#prod_repo-'+id+' option:selected')[0].innerHTML;
        $('#prod_repo-'+id).remove();
        obj[0].innerHTML = repo_name;
        obj.parent().children()[2].innerHTML = '是';
      }
    }
    var objs = $('#order_repo_table tbody').children();
    var flag = true;
    for (var i = 0; i < objs.length; ++i) {
      if (objs[i].children[2].innerHTML == '否') {
        flag = false;
        break;
      }
    }
    if (flag) {
      $('#button-history').removeAttr('disabled');
    }
    // console.log("success");
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    $('#button-deliver').button('reset');
    // console.log("complete");
  });

});

$('#button-history').on('click', function() {
	//ishanks added it
	$('#warning_shipping_id').remove();

	var element = document.getElementById("input-shipping-id");
	if(element&&!element.value){
		$("#input-shipping-id").after('<div class="text-danger" id="warning_shipping_id">"<?php echo $warning_shipping_id; ?>"</div>');
		return 0;
	}
	if(typeof verifyStatusChange == 'function'){
		if(verifyStatusChange() == false){
			return false;
		}else{
			addOrderInfo();
    	}
    }else{
    	addOrderInfo();
    }
	$.ajax({
		url: 'index.php?route=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val())+ ($('textarea[name=\'shipping\']').val() ? ('&shipping=' + encodeURIComponent($('textarea[name=\'shipping\']').val()) + '&shipping_company=' + encodeURIComponent($("#input-shipping-company option:selected").val())):''),
		beforeSend: function() {
			$('#button-history').button('loading');
		},
		complete: function() {
			$('#button-history').button('reset');
		},
		success: function(json) {
      console.log(json)
			$('.alert').remove();


			if (json['error']) {
				$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

				$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('textarea[name=\'comment\']').val('');

				$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

function changeStatus(){
  var status_id = $('select[name="order_status_id"]').val();

  $('#openbay-info').remove();

  $.ajax({
    url: 'index.php?route=extension/openbay/getorderinfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id='+status_id,
    dataType: 'html',
    success: function(html) {
      $('#history').after(html);
    }
  });
}

function addOrderInfo(){
  var status_id = $('select[name="order_status_id"]').val();

  $.ajax({
    url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id='+status_id,
    type: 'post',
    dataType: 'html',
    data: $(".openbay-data").serialize()
  });
}

$(document).ready(function() {
  changeStatus();
});

$('select[name="order_status_id"]').change(function(){ changeStatus(); });
//--></script>

<script type="text/javascript"><!--
// Sort the custom fields
$('#tab-payment tr[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-payment tr').length) {
		$('#tab-payment tr').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-payment tr').length) {
		$('#tab-payment tr:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-payment tr').length) {
		$('#tab-payment tr:first').before(this);
	}
});

$('#tab-shipping tr[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-shipping tr').length) {
		$('#tab-shipping tr').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-shipping tr').length) {
		$('#tab-shipping tr:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-shipping tr').length) {
		$('#tab-shipping tr:first').before(this);
	}
});
//--></script></div>
<?php echo $footer; ?>
