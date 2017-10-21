<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="btn-group pull-right">
          <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
          导出<i class="fa fa-angle-down"></i>
          </button>
          <ul class="dropdown-menu dropdown-light pull-right">
              <li><a href="#" class="export-csv" data-table="#report_coupon">保存为 CSV</a></li>
              <li><a href="#" class="export-txt" data-table="#report_coupon">保存为 TXT</a></li>
              <li><a href="#" class="export-xml" data-table="#report_coupon">保存为 XML</a></li>
              <li><a href="#" class="export-sql" data-table="#report_coupon">保存为 SQL</a></li>
              <li><a href="#" class="export-json" data-table="#report_coupon">保存为 JSON</a></li>
              <li><a href="#" class="export-excel" data-table="#report_coupon">保存为 Excel</a></li>
              <li><a href="#" class="export-doc" data-table="#report_coupon">保存为 Word</a>
              </li>
          </ul>
        </div>

      <h1><?php echo '优惠券报表'; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo '优惠券报表'; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo '优惠券列表'; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">

            <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="input-email"><?php echo '客户手机号'; ?></label>
                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo '客户手机号'; ?>" id="input-email" class="form-control" />
              </div>

              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>

          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered" id="report_coupon">
            <thead>
              <tr>
                <td class="text-right"><?php echo '客户手机号'; ?></td>
                <td class="text-left"><?php echo '优惠券名称'; ?></td>
                <td class="text-left"><?php echo '优惠券代码'; ?></td>
                <td class="text-right"><?php echo '添加日期'; ?></td>
                <td class="text-right"><?php echo '使用状况'; ?></td>
                <td class="text-right"><?php echo '优惠券详情'; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($coupons) { ?>
              <?php foreach ($coupons as $coupon) { ?>
              <tr>
                <td class="text-right"><?php echo $coupon['customer']; ?></td>
                <td class="text-left"><?php echo $coupon['name']; ?></td>
                <td class="text-left"><?php echo $coupon['code']; ?></td>
                <td class="text-right"><?php echo $coupon['date_added']; ?></td>
                <td class="text-right"><?php echo $coupon['coupon_status']; ?></td>
                <td class="text-right"><a href="<?php echo $coupon['edit']; ?>" data-toggle="tooltip" title="<?php echo '查看'; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>

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

  $('#button-filter').on('click', function() {
  	url = 'index.php?route=report/coupon&token=<?php echo $token; ?>';

  	var filter_email = $('input[name=\'filter_email\']').val();

    if (filter_email) {
      url += '&filter_email=' + encodeURIComponent(filter_email);
    }

  	location = url;
  });

$('input[name=\'filter_email\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['email'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_email\']').val(item['label']);
  }
});
</script>

<script type="text/javascript">
$('.date').datetimepicker({
	pickTime: false
});
</script>
<?php echo $footer; ?>
