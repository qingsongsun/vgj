<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2 class="secondary-title"><?php echo $text_my_account; ?></h2>
      <div class="content my-account">
      <ul class="list-unstyled">
        <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
        <!-- <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li> -->
        <!-- <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li> -->
        <li><a href="<?php echo $mycoupon; ?>"><?php echo '查看我的优惠券'; ?></a></li>
        <!-- <li><a href="<?php echo $pickupQrcode; ?>"><?php echo '查看我的自提码'; ?></a></li> -->
      </ul>
      </div>
      <h2 class="secondary-title"><?php echo $text_my_orders; ?></h2>
      <div class="content my-orders">
      <ul class="list-unstyled">
        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        <!-- <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li> -->
        <?php if ($reward) { ?>
        <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
        <?php } ?>
        <!-- <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li> -->
        <?php if ($show_transaction) { // Wenliang added ?>
			<li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
        <?php } ?>
        <?php if ($show_switch) { // Wenliang added ?>
			<li><a href="<?php echo $switch; ?>"><?php echo $text_switch; ?></a></li>
        <?php } ?>
<?php /* Recurring not needed
        <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
*/ ?>
      </ul>
      </div>
<?php /* Wenliang removed, subscription via email not supported/needed
      <h2 class="secondary-title"><?php echo $text_my_newsletter; ?></h2>
      <div class="content my-newsletter">
      <ul class="list-unstyled">
        <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
      </ul>
      </div>
*/ ?>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>