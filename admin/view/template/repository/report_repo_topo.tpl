<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="btn-group pull-right">
        <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
        导出&emsp;<i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu dropdown-light pull-right">
            <li><a href="#" class="export-csv" data-table="#report_repo_topo1">保存为 CSV</a></li>
            <li><a href="#" class="export-txt" data-table="#report_repo_topo1">保存为 TXT</a></li>
            <li><a href="#" class="export-xml" data-table="#report_repo_topo1">保存为 XML</a></li>
            <li><a href="#" class="export-sql" data-table="#report_repo_topo1">保存为 SQL</a></li>
            <li><a href="#" class="export-json" data-table="#report_repo_topo1">保存为 JSON</a></li>
            <li><a href="#" class="export-excel" data-table="#report_repo_topo1">保存为 Excel</a></li>
            <li><a href="#" class="export-doc" data-table="#report_repo_topo1">保存为 Word</a>
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
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-type"><?php echo $entry_type; ?></label>
                <input type="text" name="filter_type" value="<?php echo $filter_type; ?>" placeholder="<?php echo $entry_type; ?>" id="input-type" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group"></div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>

        <!-- Chart begin -->
        <div class="table-responsive">
          <table class="table table-bordered" id="report_repo_topo1">
            <thead><tr>
              <td class="text-left"><?php echo $column_repo_name; ?></td>
              <td class="text-left"><?php echo $column_repo_type; ?></td>
              <td class="text-left"><?php echo $column_namelist; ?></td>
            </tr></thead>
            <tbody>
              <?php if (isset($items) && $items): ?>
                <?php foreach ($items as $item): ?>
                  <tr>
                    <td><?php echo $item['repo_name']; ?></td>
                    <td><?php echo $item['repo_type']; ?></td>
                    <td><?php echo $item['namelist']; ?></td>
                  </tr>
                <?php endforeach ?>
              <?php else: ?>
                <tr><td colspan="4" class="text-center"><?php echo $text_no_results; ?></td></tr>
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
    var url = 'index.php?route=repository/report/repo_topo&token=<?php echo $token; ?>';

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
      url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_type = $('input[name=\'filter_type\']').val();

    if (filter_type) {
      url += '&filter_type=' + encodeURIComponent(filter_type);
    }

    location = url;
  });

  $('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=repository/report/repo_topo_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
      $('input[name=\'filter_name\']').val(item['label']);
    }
  });

  $('input[name=\'filter_type\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=repository/report/repo_topo_autocomplete&token=<?php echo $token; ?>&filter_type=' +  encodeURIComponent(request),
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
      $('input[name=\'filter_type\']').val(item['label']);
    }
  });
});

</script>

<?php echo $footer; ?>
