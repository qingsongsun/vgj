<?php echo $header; ?>
<div id="container" class="container j-container success-page">
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

    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1 class="heading-title"><?php echo $heading_title; ?>:<?php echo "验证结果"; ?></h1>

          <?php if ($verify_erro) { ?>
            <li class="product-views-count"><?php echo '经查询，本商品有可能为假冒伪劣产品，请注意。'; ?></li>
          <?php }else{ ?>
            <?php if ($scan_counter==1) { ?>
              <li class="product-views-count"><?php echo '恭喜您，该商品为正品。您可以通过点击下方按钮，注册该产品'; ?></li>
            <?php }else{ ?>
              <li class="product-views-count"><?php echo '本商品防伪码被扫描查询的次数'; ?>: <?php echo $scan_counter; ?></li>
              <li class="product-views-count"><?php echo "首次查询时间为"; ?><?php echo $first_scan_date ?></li>
              <li class="product-views-count"><?php echo "为防止假冒，防伪码只能有效查询一次，如果您首次查询的时间与上述时间不符，请当心产品是假冒产品。"; ?></li>
              <?php if ($registry_status) { ?>
                <li class="product-views-count"><?php echo '该商品已经被注册过了。'; ?></li>
              <?php } ?>
            <?php } ?>
          <?php } ?>

      <div class="buttons">
        <input type="hidden" name="register_flag" value="<?php echo $register_flag;?>">
      <?php if (!$registry_status) { ?>
        <div class="pull-right"><button id="button-registry-product"  class="btn btn-primary button">注册产品</button></div>
      <?php } ?>
      </div>



      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
/**
 * 定义注册产品button点击方法
 * @param  {[type]} )           {                  alert(11);    var node [description]
 * @param  {[type]} beforeSend: function()     {                                        $(node).button('loading');        } [description]
 * @param  {[type]} complete:   function()     {                                        $(node).button('reset');          } [description]
 * @param  {[type]} success:    function(json) {                                        console.log(json);                                           if (json['customer_id'] [description]
 * @return {[type]}             [description]
 */
  $('button[id^=\'button-registry-product\']').on('click',function() {
    var node = this;

    timer = setInterval(function() {
    if ($('#form-upload input[name=\'file\']').val() != '') {
      clearInterval(timer);

      $.ajax({
        url: "index.php?route=product/product_verify/registry_product&product_verify_id=<?php echo $product_verify_id; ?>&product_id=<?php echo $product_id ?>&verify=<?php echo $verify ?>",
        type: 'post',
        dataType: 'json',
        data:{

        },
        beforeSend: function() {
          $(node).button('loading');
        },
        complete: function() {
          $(node).button('reset');
        },
        success: function(json) {
          console.log(json);
          // 未登录,且不在微信浏览器，则跳转到登录的页面,携带flag方便后续逻辑处理
          if (json['customer_id']==null) {
            if (json['noWx']) {
              location="index.php?route=account/login&verify_flag=1";
            }else{
              // 微信端则异步请求微信自动登录流程
              location="index.php?route=account/wxlogin";
            }
          }

          if (json['success']) {
            location="index.php?route=product/product_verify/success";
          }

          if (json['registry_status']) {
            alert('很抱歉，该产品已经被注册了。');
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
    }, 500);
  });
  /**
   * 根据用户账号注册返回的flag放入隐藏表单域中，来激活注册产品的点击事件
   * @type {[type]}
   */
  var result=$('.buttons input[name=\'register_flag\']').val();

  if (result) {
    $('button[id^=\'button-registry-product\']').trigger('click');
  }
</script>