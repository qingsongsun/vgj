<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="btn-group pull-right">
        <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
        导出&emsp;<i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu dropdown-light pull-right">
            <li><a href="#" class="export-csv" data-table="#report_inventory">保存为 CSV</a></li>
            <li><a href="#" class="export-txt" data-table="#report_inventory">保存为 TXT</a></li>
            <li><a href="#" class="export-xml" data-table="#report_inventory">保存为 XML</a></li>
            <li><a href="#" class="export-sql" data-table="#report_inventory">保存为 SQL</a></li>
            <li><a href="#" class="export-json" data-table="#report_inventory">保存为 JSON</a></li>
            <li><a href="#" class="export-excel" data-table="#report_inventory">保存为 Excel</a></li>
            <li><a href="#" class="export-doc" data-table="#report_inventory">保存为 Word</a>
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
                <label class="control-label" for="input-repo-name"><?php echo $entry_repo_name; ?></label>
                <input type="text" name="filter_repo_name" value="<?php echo $filter_repo_name; ?>" placeholder="<?php echo $entry_repo_name; ?>" id="input-repo-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-prod-name"><?php echo $entry_prod_name; ?></label>
                <input type="text" name="filter_prod_name" value="<?php echo $filter_prod_name; ?>" placeholder="<?php echo $entry_prod_name; ?>" id="input-prod-name" class="form-control" />
                <input type="hidden" name="filter_prod_id" value="<?php echo $filter_prod_id; ?>" placeholder="<?php echo $entry_prod_name; ?>" id="input-prod-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <!-- <label class="control-label" for="input-ovd-name"><?php echo $entry_ovd_name; ?></label>
                <input type="text" name="filter_ovd_name" value="<?php echo $filter_ovd_name; ?>" placeholder="<?php echo $entry_ovd_name; ?>" id="input-ovd-name" class="form-control" /> -->
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>

        <!-- Chart begin -->
        <div class="table-responsive">
          <table class="table table-bordered" id="report_inventory">
            <thead><tr>
              <td class="text-left"><?php echo $text_repo_name; ?></td>
              <td class="text-left"><?php echo $text_repo_type; ?></td>
              <td class="text-left"><?php echo $text_prod_name; ?></td>
              <td class="text-left"><?php echo $text_prod_model; ?></td>
              <td class="text-left"><?php echo $text_prod_ovdname; ?></td>
              <td class="text-left"><?php echo $text_quantity; ?></td>
            </tr></thead>
            <tbody>
              <?php if (isset($products) && $products): ?>
                <?php foreach ($products as $product): ?>
                    <?php if ($product['prod_name']==null): ?>
                      <?php continue; ?>
                    <?php endif; ?>
                  <tr>
                    <td><?php echo $product['repo_name']; ?></td>
                    <td><?php echo $product['repo_type']; ?></td>
                    <td><?php echo $product['prod_name']; ?></td>
                    <td><?php echo $product['model']; ?></td>
                    <td><?php echo $product['prod_ovdname']; ?></td>
                    <td><?php echo $product['quantity']; ?></td>
                  </tr>
                <?php endforeach ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-center"><?php echo $text_no_result; ?></td></tr>
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
    var url = 'index.php?route=repository/report/inventory&token=<?php echo $token; ?>';

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

    var filter_ovd_name = $('input[name=\'filter_ovd_name\']').val();

    if (filter_ovd_name) {
      url += '&filter_ovd_name=' + encodeURIComponent(filter_ovd_name);
    }

    location = url;
  });

  $('input[name=\'filter_repo_name\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=repository/report/inventory_autocomplete&token=<?php echo $token; ?>&filter_repo_name=' +  encodeURIComponent(request),
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

<?php echo $footer; ?>
