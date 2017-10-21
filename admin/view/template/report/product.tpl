<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="btn-group pull-right">
        <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
        导出&emsp;<i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu dropdown-light pull-right">
            <li><a href="#" class="export-csv" data-table="#report_product">保存为 CSV</a></li>
            <li><a href="#" class="export-txt" data-table="#report_product">保存为 TXT</a></li>
            <li><a href="#" class="export-xml" data-table="#report_product">保存为 XML</a></li>
            <li><a href="#" class="export-sql" data-table="#report_product">保存为 SQL</a></li>
            <li><a href="#" class="export-json" data-table="#report_product">保存为 JSON</a></li>
            <li><a href="#" class="export-excel" data-table="#report_product">保存为 Excel</a></li>
            <li><a href="#" class="export-doc" data-table="#report_product">保存为 Word</a>
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
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <!-- Luke added for creator filter (begin) -->
              <div class="form-group">
                <label class="control-label" for="input-creator"><?php echo $entry_creator; ?></label>
                <input type="text" name="filter_creator" value="<?php echo $filter_creator; ?>" placeholder="<?php echo $entry_creator; ?>" id="input-creator" class="form-control" />
              </div>
              <!-- Luke added for creator filter (end) -->
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>

        <!-- Chart begin -->
        <div class="table-responsive">
          <table class="table table-bordered" id="report_product">
            <thead><tr>
              <td class="text-left"><?php echo $column_creator; ?></td>
              <td class="text-left"><?php echo $column_prod_name; ?></td>
              <td class="text-left"><?php echo $column_prod_model; ?></td>
              <td class="text-left"><?php echo $column_prod_quantity; ?></td>
              <td class="text-left"><?php echo $column_inventory; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
            </tr></thead>
            <tbody>
              <?php if (isset($items) && $items): ?>
                <?php foreach ($items as $item): ?>
                  <tr>
                    <td><?php echo $item['creator']; ?></td>
                    <td><?php echo $item['prod_name']; ?></td>
                    <td><?php echo $item['prod_model']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['inventory']; ?></td>
                    <td><?php echo $item['status']; ?></td>
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
    var url = 'index.php?route=report/product&token=<?php echo $token; ?>';

    var filter_creator = $('input[name=\'filter_creator\']').val();
    if (filter_creator) {
      url += '&filter_creator=' + encodeURIComponent(filter_creator);
    }

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
      url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_model = $('input[name=\'filter_model\']').val();

    if (filter_model) {
      url += '&filter_model=' + encodeURIComponent(filter_model);
    }

    var filter_quantity = $('input[name=\'filter_quantity\']').val();

    if (filter_quantity) {
      url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
      url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
  });

  // Luke added for filtering creator.
  $('input[name=\'filter_creator\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=report/product/autocomplete&token=<?php echo $token; ?>&filter_creator=' +  encodeURIComponent(request),
        dataType: 'json',
      })
      .done(function(data) {
        response($.map(data, function(item) {
          return {
            label: item['creator'],
            value: item['creator']
          }
        }));
      });
    },
    'select': function(item) {
      $('input[name=\'filter_creator\']').val(item['label']);
    }
  });

  $('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=report/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['name'],
              value: item['product_id']
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'filter_name\']').val(item['label']);
    }
  });


  $('input[name=\'filter_model\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=report/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['model'],
              value: item['product_id']
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'filter_model\']').val(item['label']);
    }
  });
});

</script>

<?php echo $footer; ?>
