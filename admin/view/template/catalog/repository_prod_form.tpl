<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-repository" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-repository" class="form-horizontal">
          <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_prod_name; ?></label>
                <input type="text" name="filter_prod_name"  placeholder="<?php echo $entry_prod_name; ?>" id="input-name" class="form-control" />
                <input type="hidden" name="filter_prod_id"  placeholder="<?php echo $entry_prod_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_prod_ovdname; ?></label>
                <input type="text" name="filter_prod_ovdname"  placeholder="<?php echo $entry_prod_ovdname; ?>" id="input-model" class="form-control" />
                <input type="hidden" name="filter_prod_ov_id"  placeholder="<?php echo $entry_prod_ovdname; ?>" id="input-model" class="form-control" />
              </div>
             </div>
             <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-num"><?php echo $entry_prod_num; ?></label>
                <input type="text" name="filter_prod_num"  placeholder="<?php echo $entry_prod_num; ?>" id="input-num" class="form-control" />
              </div>
             </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('input[name=\'filter_prod_name\']').autocomplete({
  'source': function(request, response) {
    //alert(request);
    $.ajax({
      url: 'index.php?route=catalog/repository/autocomplete&token=<?php echo $token; ?>&filter_prod_name=' +  encodeURIComponent(request) + '&repository_id=' +'&flag=1',
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['filter_prod_name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_prod_name\']').val(item['label']);
    $('input[name=\'filter_prod_id\']').val(item['value']);
  }
});

$('input[name=\'filter_prod_ovdname\']').autocomplete({
  'source': function(request, response) {
    if($('input[name=\'filter_prod_name\']').val()){
      $.ajax({
        // if($('input[name=\'filter_prod_name\']').val()){
          url: 'index.php?route=catalog/repository/autocomplete&token=<?php echo $token; ?>&filter_prod_ovdname=' +  encodeURIComponent(request) + '&filter_prod_id=' + $('input[name=\'filter_prod_id\']').val() + '&flag=2',
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['filter_prod_ovdname'],
                value: item['product_ov_id']
              }
            }));
          }
      });
    }
    else{
      return {};
    }
  },
  'select': function(item) {
    $('input[name=\'filter_prod_ovdname\']').val(item['label']);
    $('input[name=\'filter_prod_ov_id\']').val(item['value']);

  }
});
//--></script>

<?php echo $footer; ?>

