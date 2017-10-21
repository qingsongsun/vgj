<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

   <!-- 内容头部 -->
  <div class="page-header">
    <div class="container-fluid">
      <!-- 导出报表的button -->
      <div class="btn-group pull-right">
          <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
              导出<i class="fa faa-angle-down"></i>
          </button>
          <ul class="dropdown-menu dropdown-light pull-right">
            <li><a href="#" class="export-csv" data-table="#report_profit">保存为 CSV</a></li>
            <li><a href="#" class="export-txt" data-table="#report_profit">保存为 TXT</a></li>
            <li><a href="#" class="export-xml" data-table="#report_profit">保存为 XML</a></li>
            <li><a href="#" class="export-sql" data-table="#report_profit">保存为 SQL</a></li>
            <li><a href="#" class="export-json" data-table="#report_profit">保存为 JSON</a></li>
            <li><a href="#" class="export-excel" data-table="#report_profit">保存为 Excel</a></li>
            <li><a href="#" class="export-doc" data-table="#report_profit">保存为 Word</a></li>
          </ul>
      </div>

      <!-- 标题 -->
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href'] ?>"><?php echo $breadcrumb['text'] ?></a></li>
        <?php } ?>
      </ul>

    </div>
  </div>

   <!-- 内容详情部分 -->
  <div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
        </div>
        <div class="panel-body">
            <div class="well">
                      <div class="row">
                        <div class="col-sm-4">


                          <div class="form-group">
                            <label class="control-label" for="input-start-time"><?php echo $entry_start_time; ?></label>
                            <div class="input-group date">
                              <input type="text" name="filter_start_time" value="<?php echo $filter_start_time; ?>" placeholder="<?php echo $entry_start_time; ?>" data-date-format="YYYY-MM-DD" id="input-start-time" class="form-control" />
                              <span class="input-group-btn">
                              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-4">

                          <div class="form-group">
                            <label class="control-label" for="input-end-time"><?php echo $entry_end_time; ?></label>
                            <div class="input-group date">
                              <input type="text" name="filter_end_time" value="<?php echo $filter_end_time; ?>" placeholder="<?php echo $entry_end_time; ?>" data-date-format="YYYY-MM-DD" id="input-end-time" class="form-control" />
                              <span class="input-group-btn">
                              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="form-group">
                          <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                          <input type="text" name="filter_repo_name" value="<?php echo $filter_repo_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                        </div>
                        </div>
                      </div>
            </div>

            <!-- 报表 -->
          <div class="table-responsive">
            <table class="table table-bordered" id="report_profit">
              <thead>
                <tr>
                  <td class="text-left"><?php echo '货号(model)' ;?></td>
                  <td class="text-left"><?php echo '商品名' ;?></td>
                  <td class="text-left"><?php echo '实际售价' ;?></td>
                  <td class="text-left"><?php echo '成本价' ;?></td>
                  <td class="text-left"><?php echo '毛利率' ;?></td>
                  <td class="text-left"><?php echo '销售量' ;?></td>
                  <td class="text-left"><?php echo '库存量' ;?></td>
                  <td class="text-left"><?php echo '销售成本' ;?></td>
                  <td class="text-left"><?php echo '销售档案牌价额' ;?></td>
                  <td class="text-left"><?php echo '销售额' ;?></td>
                  <td class="text-left"><?php echo '库存成本' ;?></td>
                  <td class="text-left"><?php echo '库存牌价额' ;?></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($items) && $items): ?>
                  <?php foreach ($items as $item): ?>
                    <tr>
                      <td><?php echo $item['model']; ?></td>
                      <td><?php echo $item['name']; ?></td>
                      <td><?php echo $item['real_price']; ?></td>
                      <td><?php echo $item['cost_price']; ?></td>
                      <td><?php echo $item['profit'] ?></td>
                      <td><?php echo $item['saled_quantity']; ?></td>
                      <td><?php echo $item['repo_quantity']; ?></td>
                      <td><?php echo $item['saled_cost_total']; ?></td>
                      <td><?php echo $item['sticker_cost_total']; ?></td>
                      <td><?php echo $item['real_saled_total']; ?></td>
                      <td><?php echo $item['repo_cost_total']; ?></td>
                      <td><?php echo $item['repo_sticker_cost_total']; ?></td>
                    </tr>
                  <?php endforeach ?>
                <?php else: ?>
                  <tr><td colspan="10" class="text-center"><?php echo $text_no_results; ?></td></tr>
                <?php endif ?>
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
  $(document).ready(function() {
    Main.init();
    TableExport.init();
  });

  $(document).ready(function() {
  $('#button-filter').on('click', function() {
    var url = 'index.php?route=report/profit&token=<?php echo $token; ?>';

    var filter_repo_name = $('input[name=\'filter_repo_name\']').val();

    if (filter_repo_name) {
      url += '&filter_repo_name=' + encodeURIComponent(filter_repo_name);
    }

    var filter_prod_name = $('input[name=\'filter_prod_name\']').val();

    if (filter_prod_name) {
      url += '&filter_prod_name=' + encodeURIComponent(filter_prod_name);
    }

    var filter_prod_id = $('input[name=\'filter_prod_id\']').val();

    if (filter_prod_id) {
      url += '&filter_prod_id=' + encodeURIComponent(filter_prod_id);
    }

    var filter_start_time = $('input[name=\'filter_start_time\']').val();

    if (filter_start_time) {
      url += '&filter_start_time=' + encodeURIComponent(filter_start_time);
    }

    var filter_end_time = $('input[name=\'filter_end_time\']').val();

    if (filter_end_time) {
      url += '&filter_end_time=' + encodeURIComponent(filter_end_time);
    }

    location = url;
  });

  $('input[name=\'filter_repo_name\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=report/profit/store_product_sale_autocomplete&token=<?php echo $token; ?>&filter_repo_name=' +  encodeURIComponent(request),
        dataType: 'json',
      })
      .done(function(data) {
        response($.map(data, function(item) {
          return {
            label: item,
            value: item
          }
        }));
      });
    },
    'select': function(item) {
      $('input[name=\'filter_repo_name\']').val(item['label']);
    }
  });

  $('input[name=\'filter_prod_name\']').autocomplete({
    'source': function(request, response) {
      if (request.length == 0) {
        $('input[name=\'filter_prod_name\']').val('');
        $('input[name=\'filter_prod_id\']').val('');
      }
      $.ajax({
        url: 'index.php?route=report/profit/inventory_autocomplete&token=<?php echo $token; ?>&filter_prod_name=' +  encodeURIComponent(request),
        dataType: 'json',
      })
      .done(function(data) {
        response($.map(data, function(item) {
          return {
            label: item['pdname'],
            value: item['product_id']
          }
        }));
      });
    },
    'select': function(item) {
      $('input[name=\'filter_prod_name\']').val(item['label']);
      $('input[name=\'filter_prod_id\']').val(item['value']);

    }
  });
});
</script>

<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript">
$('.date').datetimepicker({
  pickTime: false
});
</script>
<?php echo $footer; ?>