<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="btn-group pull-right">
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
            </li>
        </ul>
      </div>

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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_repo_name" value="<?php echo $filter_repo_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
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
                <label class="control-label" for="input-prod-name"><?php echo $entry_prod_name; ?></label>
                <input type="text" name="filter_prod_name" value="<?php echo $filter_prod_name; ?>" placeholder="<?php echo $entry_prod_name; ?>" id="input-prod-name" class="form-control" />
                <input type="hidden" name="filter_prod_id" value="<?php echo $filter_prod_id; ?>" placeholder="<?php echo $entry_prod_name; ?>" id="input-prod-name" class="form-control" />
              </div>
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
        </div>

        <!-- Chart begin -->
        <div class="table-responsive">
          <table class="table table-bordered" id="report_store_sale">
            <thead><tr>
              <td class="text-left"><?php echo $column_store_name; ?></td>
              <td class="text-left"><?php echo $column_prod_name; ?></td>
              <td class="text-left"><?php echo $column_model; ?></td>
              <td class="text-left"><?php echo $column_ovd_name; ?></td>
              <!-- <td class="text-left"><?php echo $column_start_time; ?></td> -->
              <!-- <td class="text-left"><?php echo $column_end_time; ?></td> -->
              <td class="text-left"><?php echo $column_sale_quantity; ?></td>
              <td class="text-left"><?php echo $column_sale_amount; ?></td>
            </tr></thead>
            <tbody>
              <?php if (isset($items) && $items): ?>
                <?php foreach ($items as $item): ?>
                  <tr>
                    <td><?php echo $item['store_name']; ?></td>
                    <td><?php echo $item['prod_name']; ?></td>
                    <td><?php echo $item['model']; ?></td>
                    <td><?php echo $item['ovd_name']; ?></td>
                    <!-- <td><?php echo $item['start_time']; ?></td> -->
                    <!-- <td><?php echo $item['end_time']; ?></td> -->
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo sprintf("%.2f", $item['amount']); ?></td>
                  </tr>
                <?php endforeach ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-center"><?php echo $text_no_results; ?></td></tr>
              <?php endif ?>
            </tbody>
          </table>
        </div>
        <!-- Chart end -->
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
jQuery(document).ready(function () {
        Main.init();
        TableExport.init();
    });

$(document).ready(function() {
  $('#button-filter').on('click', function() {
    var url = 'index.php?route=repository/report/store_product_sale&token=<?php echo $token; ?>';

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
        url: 'index.php?route=repository/report/store_product_sale_autocomplete&token=<?php echo $token; ?>&filter_repo_name=' +  encodeURIComponent(request),
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
        url: 'index.php?route=repository/report/inventory_autocomplete&token=<?php echo $token; ?>&filter_prod_name=' +  encodeURIComponent(request),
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
