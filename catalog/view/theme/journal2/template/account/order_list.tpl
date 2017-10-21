<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?> order-list">
      <h1 class="heading-title"><?php echo $heading_title; ?></h1>
      <?php echo $content_top; ?>
      <?php if ($orders) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover list">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_order_id; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <!-- <td class="text-right"><?php echo $column_product; ?></td> -->
              <!-- <td class="text-left"><?php echo $column_customer; ?></td> -->
              <!-- <td class="text-right"><?php echo $column_total; ?></td> -->
              <td><?php echo "查看/支付"; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-right">#<?php echo $order['order_id']; ?></td>
              <td class="text-left"><?php echo $order['status']; ?></td>
              <td class="text-left"><?php echo $order['date_added']; ?></td>
              <!-- <td class="text-right"><?php echo $order['products']; ?></td> -->
              <!-- <td class="text-left"><?php echo $order['name']; ?></td> -->
              <!-- <td class="text-right"><?php echo $order['total']; ?></td> -->
              <td class="text-right">
              	<a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
              	<?php if(strcmp($order['status'], $wait_for_payment_status) == 0) { ?><a href="<?php echo $order['payment_href']; ?>" data-toggle="tooltip" title="<?php echo $button_pay; ?>" class="btn btn-info"><i class="fa fa-money"></i></a><?php }?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary button"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>