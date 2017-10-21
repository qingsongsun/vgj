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
    <div id="content" class="<?php echo $class; ?>">
      <h1 class="heading-title"><?php echo '我的优惠券'; ?></h1>
      <?php echo $content_top; ?>
      <div class="content">
      <!-- <p><?php echo '我的优惠券总数'; ?> <b><?php echo $total; ?></b>.</p><br/> -->
      <div class="table-responsive">
        <table class="table table-bordered table-hover list">
          <thead>
            <tr>
              <td class="text-left"><?php echo '优惠码'; ?></td>
              <td class="text-right"><?php echo '使用说明'; ?></td>
              <td class="text-left"><?php echo '优惠券状态'; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($coupons) { ?>
            <?php foreach ($coupons  as $coupon) { ?>
            <tr>
              <td class="text-left"><?php echo $coupon['coupon_code']; ?></td>
              <td class="text-left"><textarea style="border:none;" readonly="readonly"><?php echo $coupon['coupon_description']; ?></textarea></td>
              <td class="text-left"><?php echo $coupon['coupon_status']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="3"><?php echo '您现在还没获取到优惠券'; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
        </div>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>