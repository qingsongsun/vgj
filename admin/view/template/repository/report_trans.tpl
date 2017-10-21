<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="btn-group pull-right">
        <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
        导出&emsp;<i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu dropdown-light pull-right">
            <li><a href="#" class="export-csv" data-table="#report_trans">保存为 CSV</a></li>
            <li><a href="#" class="export-txt" data-table="#report_trans">保存为 TXT</a></li>
            <li><a href="#" class="export-xml" data-table="#report_trans">保存为 XML</a></li>
            <li><a href="#" class="export-sql" data-table="#report_trans">保存为 SQL</a></li>
            <li><a href="#" class="export-json" data-table="#report_trans">保存为 JSON</a></li>
            <li><a href="#" class="export-excel" data-table="#report_trans">保存为 Excel</a></li>
            <li><a href="#" class="export-doc" data-table="#report_trans">保存为 Word</a>
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
        <!-- Chart begin -->
        <div class="table-responsive">
          <table class="table table-bordered" id="report_trans">
            <thead><tr>
              <td class="text-left"><?php echo $column_send_time; ?></td>
              <td class="text-left"><?php echo $column_send_repo; ?></td>
              <td class="text-left"><?php echo $column_receive_repo; ?></td>
              <td class="text-left"><?php echo $column_prod_name; ?></td>
              <td class="text-left"><?php echo $column_ovd_name; ?></td>
              <td class="text-left"><?php echo $column_quantity; ?></td>
            </tr></thead>
            <tbody>
              <?php if (isset($items) && $items): ?>
                <?php foreach ($items as $item): ?>
                  <tr>
                    <td><?php echo $item['send_timestamp']; ?></td>
                    <td><?php echo $item['send_repo']; ?></td>
                    <td><?php echo $item['receive_repo']; ?></td>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['ovd_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
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
</script>

<?php echo $footer; ?>
