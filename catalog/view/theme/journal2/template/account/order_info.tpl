<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <h1 class="heading-title"><?php echo $heading_title; ?></h1>
      <?php echo $content_top; ?>
      <table class="table table-bordered table-hover list">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_order_detail; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;"><?php if ($invoice_no) { ?>
              <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
              <?php } ?>
              <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
              <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
            <td class="text-left"><?php if ($payment_method) { ?>
              <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
              <?php } ?>
              <?php if ($shipping_method) { ?>
              <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
              <?php } ?></td>
          </tr>
        </tbody>
      </table>
      <table class="table table-bordered table-hover list">
        <thead>
          <tr>
<?php /* Wenliang removed payment address
            <td class="text-left" style="width: 50%;"><?php echo $text_payment_address; ?></td>
*/ ?>
            <?php if ($shipping_address) { ?>
            <td class="text-left"><?php echo $text_shipping_address; ?></td>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
<?php /* Wenliang removed payment address
            <td class="text-left"><?php echo $payment_address; ?></td>
*/ ?>
            <?php if ($shipping_address) { ?>
            <td class="text-left"><?php echo $shipping_address; ?></td>
            <?php } ?>
          </tr>
        </tbody>
      </table>
      <div class="table-responsive">
        <table class="table table-bordered table-hover list">
          <thead>
            <tr>
            <!-- Huwen added for image in order_info -->
              <td class="text-left"><?php echo $column_image;?></td>
              <td class="text-left"><?php echo $column_name; ?></td>
<?php /* Wenliang removed model info
              <td class="text-left"><?php echo $column_model; ?></td>
*/ ?>
              <td class="text-right"><?php echo $column_quantity; ?></td>
              <td class="text-right"><?php echo $column_price; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <?php if ($products) { ?>
              <td style="width: 20px;"></td>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="text-left">
                <img src="<?php echo $product['image'];?>" >
              </td>
             <td class="text-left" >
                <!--Huwen add it ,for customer can link to the description of a given product in the order page  -->
                <a href="<?php echo $product['href']; ?>" >
                   <?php echo $product['name']; ?> 
                   
                </a>
                
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?>
             </td>
<?php /* Wenliang removed model info
              <td class="text-left"><?php echo $product['model']; ?></td>
*/ ?>
              <td class="text-right"><?php echo $product['quantity']; ?></td>
              <td class="text-right"><?php echo $product['price']; ?></td>
              <td class="text-right"><?php echo $product['total']; ?></td>
              <td class="text-right" style="white-space: nowrap;"><?php if ($product['reorder']) { ?>
                <a href="<?php echo $product['reorder']; ?>" data-toggle="tooltip" title="<?php echo $button_reorder; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></a>
                <?php } ?>
                <a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a></td>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td class="text-left"><?php echo $voucher['description']; ?></td>
              <td class="text-left"></td>
              <td class="text-right">1</td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              <?php if ($products) { ?>
              <td></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <?php foreach ($totals as $total) { ?>
            <tr>
<?php /* Wenliang mod, due to removed model
              <td colspan="3"></td>   
*/ ?>         
              <td colspan="2"></td>
              <td class="text-right"><b><?php echo $total['title']; ?></b></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
              <?php if ($products) { ?>
              <td></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tfoot>
        </table>
      </div>
      <?php if ($comment) { ?>
      <table class="table table-bordered table-hover list">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $comment; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
      <?php if ($histories) { ?>
      <h3><?php echo $text_history; ?></h3>
      <div class="table-responsive">
        <table class="table table-bordered table-hover list">
          <thead>
          <tr>
            <td class="text-left"><?php echo $column_date_added; ?></td>
            <td class="text-left"><?php echo $column_status; ?></td>
            <td class="text-left"><?php echo $column_comment; ?></td>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($histories as $history) { ?>
          <tr>
            <td class="text-left"><?php echo $history['date_added']; ?></td>
            <td class="text-left"><?php echo $history['status']; ?></td>
            <td class="text-left"><?php echo $history['comment']; ?></td>
          </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
      <?php } ?>

<!-- Huwen added for shipping-->
<table class="table table-bordered table-hover list">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_shipping_info; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;"><?php if ($invoice_no) { ?>
              <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
              <?php } ?>
              <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
              <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
              <b><?php echo $text_shipping_name; ?></b><?php echo $shipping_name; ?><br />
              <b><?php echo $text_shipping_id; ?></b><?php echo $shipping_id; ?><br />                            
          <!--   <p>
            <select name="com" id="com" style="display: none">
              <option  value="<?php echo $shipping_name_value;?>"><?php echo $shipping_name;?></option>              
            </select>
            </p>
              <p><input style="display: none" type="text" name="sn" id="sn" value="<?php echo $shipping_id ;?>"></p>
              <p><input  type="button" name="chaxun" value="查询物流"></p>
               <div id="msg"></div> -->
            </td>
            
          </tr>
        </tbody>
      </table>



<!--Huwen added ,for shipping search  -->
<table class="table table-bordered table-hover list">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_shipping_search; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;">  
            <p>
            <select name="com" id="com" style="display: none">
              <option  value="<?php echo $shipping_name_value;?>"><?php echo $shipping_name;?></option>            
            </select>
            </p>
              <p><input style="display: none" type="text" name="sn" id="sn" value="<?php echo $shipping_id ;?>"></p>
              <p><input  type="button" name="chaxun" value="查询物流"></p>
               <div id="msg"></div>
            </td>
            
          </tr>
        </tbody>
      </table>



      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary button"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>


<!-- Huwen added the ajax for shipping search -->
<script>
  var btn=document.getElementsByName('chaxun')[0];
  btn.onclick=function(){
    var com=document.getElementById('com').value;
    var sn=document.getElementById('sn').value;
    var msg=document.getElementById('msg');

    var url='system/pub/kd.php?key=ff4735a30a7a4e5a8637146fd0e7cec9&ord&order='+sn+'&id='+com+'&show=html'; 
    
    xhr=new XMLHttpRequest();
    xhr.onreadystatechange=function(){
      if (this.readyState==4) {
        
        if (this.responseText=="缺少必传参数（id,order,key）") {
          msg.innerHTML="：( 该单号暂无物流进展，请稍后再试，或检查公司和单号是否有误。";
        }else{
          msg.innerHTML=this.responseText;
        }
            
      }
    } 
    xhr.open('get',url,true);
    xhr.send(null);
  }

</script>
<!-- Huwen end -->