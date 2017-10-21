<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">

      <!-- <div class="btn-group pull-right" style="margin-left: 4px">
        <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
        导出&emsp;<i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu dropdown-light pull-right">
            <li><a href="#" class="export-csv" data-table="#report_store_sale">保存为 CSV</a></li>
            <li><a href="#" class="export-txt" data-table="#report_store_sale">保存为 TXT</a></li>
            <li><a href="#" class="export-xml" data-table="#report_store_sale">保存为 XML</a></li>
            <li><a href="#" class="export-sql" data-table="#report_store_sale">保存为 SQL</a></li>
            <li><a href="#" class="export-json" data-table="#report_store_sale">保存为 JSON</a></li>
            <li><a href="#" class="export-excel" data-table="#report_store_sale">保存为 Excel</a></li>
            <li><a href="#" class="export-doc" data-table="#report_store_sale">保存为 Word</a>
            <li><a href="#" class="export-png" data-table="#report_store_sale">保存为 pdf</a>
            </li>
        </ul>
      </div> -->

      <div class="pull-right">

        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>

        <button id="download_html" type="button" data-toggle="tooltip" title="<?php echo '导出二维码'; ?>" class="btn btn-info" onclick=""><i class="fa fa-print"></i></button>

         <button type="button" data-toggle="tooltip" title="<?php echo '查看url列表'; ?>" class="btn btn-info" onclick="$('#form-product').attr('action', '<?php echo $downloadQrcodeUrl; ?>').submit()"><i class="fa fa-eye"></i></button>

      </div>
      <h1><?php echo '防伪验证码'; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo '防伪验证码'; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="report_store_sale">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-center"><?php echo '二维码'; ?></td>
                  <td class="text-center"><?php echo '扫描次数'; ?></td>
                  <td class="text-center"><?php echo '序列号'; ?></td>
                  <td class="text-center"><?php echo '产品注册状态'; ?></td>
<!--
                  <td class="text-center"><?php echo $column_action; ?></td>
-->
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($product['product_verify_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_verify_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_verify_id']; ?>" />
                    <?php } ?></td>
                   <td class="text-center"><img src="view/image/uniqueQrcode/<?php echo $product['model']."_".$product['sn'].'.png?'.time(); ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" width="100px"/></td>
                    <td class="text-center"><?php echo $product['scan_counter']; ?></td>
                    <td class="text-center"><?php echo $product['sn']; ?></td>
                    <?php if ($product['registry_status']) { ?>
                      <td class="text-center"><?php echo '已被 '.$product['customer_phone'].' 用户于 '.$product['date'].' 注册，IP:'.$product['ip']; ?></td>
                    <?php }else{ ?>
                      <td class="text-center"><?php echo '尚未被注册'; ?></td>
                    <?php } ?>
<!--
                  <td class="text-center">
                    <a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  </td>
-->
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <?php if (!$download) { ?>
          <div class="row">
            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <!-- <div id="export_html">

  </div> -->

<script type="text/javascript" src="view/javascript/jquery/tableExport/plugins/select2/select2.min.js"></script>
<script src="view/javascript/jquery/tableExport/plugins/tableExport/tableExport.js"></script>
<script src="view/javascript/jquery/tableExport/plugins/tableExport/jquery.base64.js"></script>
<script src="view/javascript/jquery/tableExport/plugins/tableExport/html2canvas.js"></script>
<script src="view/javascript/jquery/tableExport/plugins/tableExport/jspdf/libs/sprintf.js"></script>
<script src="view/javascript/jquery/tableExport/plugins/tableExport/jspdf/jspdf.js"></script>
<script src="view/javascript/jquery/tableExport/plugins/tableExport/jspdf/libs/base64.js"></script>
<script src="view/javascript/jquery/tableExport/js/table-export.js"></script>
<script src="view/javascript/jquery/tableExport/js/main.js"></script>

<script type="text/javascript">
  jQuery(document).ready(function () {
        Main.init();
        TableExport.init();
    });
</script>

<script type="text/javascript">
  $('#download_html').on('click',function(){
    $.ajax({
      url:'index.php?route=catalog/product_verify&product_id=<?php echo $product_id ?>&token=<?php echo $token ?>',
      type:'post',
      dataType:'html',
      data:{
        'download':true
      },
      beforeSend:function(){
        $('#download_html').button('loading');
      },
      success:function(html){

        $.ajax({
          url: 'index.php?route=catalog/product_verify/getHtml&token=<?php echo $token ?>',
          type: 'post',
          dataType: 'json',
          data: {
            table: html,
            test:'111'
          },
          success:function(json){
            $('#download_html').button('reset');
            location='index.php?route=catalog/product_verify/downloadQrcode&token=<?php echo $token ?>';
          }
        });

      }
    });

  });
</script>



 <script type="text/javascript">

  $('input[name^=\'selected\']').on('change', function() {

    $('#button-shipping, #button-getQrcodeTicket').prop('disabled', false);

    var selected = $('input[name^=\'selected\']:checked');

    if (selected.length) {
      $('#button-getQrcodeTicket').prop('disabled', false);
    }
    for (i = 0; i < selected.length; i++) {
      if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
        $('#button-shipping').prop('disabled', false);

        break;
      }
    }
  });
 </script>
<?php echo $footer; ?>
