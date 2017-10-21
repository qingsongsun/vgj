<?php if (!isset($redirect)) { ?>
<div class="table-responsive checkout-product">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left name"><?php echo $column_name; ?></td>
<?php /* Wenliang removed model info
        <td class="text-left model"><?php echo $column_model; ?></td>
*/ ?>
        <td class="text-right quantity"><?php echo $column_quantity; ?></td>
        <td class="text-right price"><?php echo $column_price; ?></td>
        <td class="text-right total"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="text-left name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?>
          <?php if($product['recurring']) { ?>
          <br />
          <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
          <?php } ?></td>
<?php /* Wenliang removed model info
        <td class="text-left model"><?php echo $product['model']; ?></td>
*/ ?>
        <td class="text-right quantity"><?php echo $product['quantity']; ?></td>
        <td class="text-right price"><?php echo $product['price']; ?></td>
        <td class="text-right total"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="text-left"><?php echo $voucher['description']; ?></td>
        <td class="text-left"></td>
        <td class="text-right">1</td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
        <td class="text-right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<?php echo str_replace("btn btn-primary", "btn btn-primary button", $payment); ?>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
