<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php if (!$is_add && $is_manager) { ?>
        <a href="<?php echo $add_prod; ?>" data-toggle="tooltip" title="<?php echo $button_add_prod; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <?php } ?>
        <?php if ($is_manager || $is_add) { ?>
        <button type="submit" form="form-repository-prod" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <?php } ?>
        <!--<?php if (!$is_add && $is_manager) { ?>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-repository-prod-delete').submit() : false;"><i class="fa fa-trash-o"></i></button>
        <?php } ?>-->
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
        <form action="<?php echo $delete_action; ?>" method="post" enctype="multipart/form-data" id="form-repository-prod-delete" class="form-horizontal"></form>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-repository-prod" class="form-horizontal">


          <ul class="nav nav-tabs">
            <?php if (!$is_add): ?>
            <li class="active"><a href="#tab-product" data-toggle="tab"><?php echo $tab_repo_product; ?></a></li>
            <?php endif ?>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <?php if (!$is_add): ?>
              <li><a href="#tab-user" data-toggle="tab"><?php echo $tab_user; ?></a></li>
            <?php endif ?>
          </ul>
          <div class="tab-content">
            <?php if (!$is_add): ?>
            <div class="tab-pane active in " id="tab-product">
              <!-- Zark added begin -->
              <?php if (!$is_add): ?>
              <div class="well">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label" for="input-name"><?php echo $entry_prod_name; ?></label>
                      <input type="text" name="filter_prod_name" placeholder="<?php echo $entry_prod_name; ?>" id="input-name" class="form-control" value="<?php echo $filter_prod_name; ?>"/>
                      <input type="hidden" name="filter_prod_id"  placeholder="<?php echo $entry_prod_name; ?>" id="input-name" class="form-control" value="<?php echo $filter_prod_id; ?>"/>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label" for="input-model"><?php echo $entry_prod_ovdname; ?></label>
                      <input type="text" name="filter_prod_ovdname" placeholder="<?php echo $entry_prod_ovdname; ?>" id="input-model" class="form-control" value="<?php echo $filter_prod_ovdname; ?>"/>
                    </div>
                  </div>
                  <div class="col-sm-4" >
                    <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                  </div>
                </div>
              </div>
              <?php endif ?>
              <!-- Zark added end -->

              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead><tr>
                    <?php if ($is_manager): ?>
                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                    <?php endif ?>
                    <td class="text-left">
                      <?php if ($sort == 'pdname') { ?>
                        <a href="<?php echo $sort_prod_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_prod_name; ?></a>
                      <?php } else { ?>
                        <a href="<?php echo $sort_prod_name; ?>"><?php echo $column_prod_name; ?></a>
                      <?php } ?>
                    </td>
                    <td class="text-left">
                      <?php if ($sort == 'model') { ?>
                        <a href="<?php echo $sort_prod_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_prod_model ?></a>
                      <?php } else { ?>
                        <a href="<?php echo $sort_prod_model; ?>"><?php echo $column_prod_model ?></a>
                      <?php } ?>
                    </td>
                    <td class="text-left">
                      <?php echo $column_prod_ovdname; ?>
                    </td>
                    <td class="text-right">
                      <?php if ($sort == 'product_num') { ?>
                        <a href="<?php echo $sort_prod_num; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_prod_num; ?></a>
                      <?php } else { ?>
                        <a href="<?php echo $sort_prod_num; ?>"><?php echo $column_prod_num; ?></a>
                      <?php } ?>
                    </td>
                    <?php if ($is_manager): ?>
                    <td class="text-right" style="width: 140px">
                      <?php echo $column_prod_edit; ?>
                    </td>
                    <?php endif ?>
                  </tr></thead>
                  <tbody>
                    <?php $row = 0; ?>
                    <?php if (isset($products) && $products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <?php if ($is_manager): ?>
                        <td class="text-center">
                          <?php if (in_array($product['prod_id'], $selected)) { ?>
                          <input type="checkbox" name="selected[<?php echo $product['prod_ovdname']; ?>]" value="<?php echo $product['prod_id']; ?>" checked="checked" form="form-repository-prod-delete" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[<?php echo $product['prod_ovdname']; ?>]" value="<?php echo $product['prod_id']; ?>" form="form-repository-prod-delete" />
                          <?php } ?>
                        </td>
                        <?php endif ?>
                        <td class="text-left"><?php echo $product['prod_name'] ?></td>
                        <td class="text-left"><?php echo $product['prod_model']; ?></td>
                        <td class="text-left"><?php echo $product['prod_ovdname']; ?></td>
                        <td class="text-right"><?php echo $product['prod_num']; ?></td>
                        <?php if ($is_manager): ?>
                        <td class="text-right">
                          <a class="btn btn-danger" onclick="decValue('#input-delta-<?php echo $row; ?>')" style="float: left; width: 35px; height: 35px"><i class="fa fa-minus"></i></a>
                          <input type="text" name="product_num_delta[<?php echo $row; ?>]" id="input-delta-<?php echo $row; ?>" class="form-control" value='0' style="float: left; width: 50px;" />
                          <input type="hidden" name="product_id[<?php echo $row; ?>]" value=<?php echo $product['prod_id']; ?>>
                          <input type="hidden" name="product_ovd_name[<?php echo $row; ?>]" value=<?php echo $product['prod_ovdname']; ?>>
                          <a class="btn btn-primary" onclick="incValue('#input-delta-<?php echo $row; ?>')" style="float: left; width: 35px; height: 35px;"><i class="fa fa-plus"></i></a>
                        </td>
                        <?php endif ?>
                      </tr>
                      <?php $row++; ?>
                      <?php } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <?php if (isset($products) && $products) { ?>
              <div class="row">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
              </div>
              <?php } ?>
            </div>
            <?php endif ?>

            <?php if ($is_add): ?>
            <div class="tab-pane active in" id="tab-data">
            <?php else: ?>
            <div class="tab-pane fade" id="tab-data">
            <?php endif ?>
                <div class="form-group">
                  <label for="input-id" class="col-sm-2 control-label"><?php echo $entry_id; ?>：</label>
                  <div class="col-sm-10">
                    <input type="text" name="repository_id" value="<?php echo $repository_id; ?>" id="input-id" class="form-control" readonly="readonly"/>
                  </div>
                </div>
		            <!-- huwen added for pickup -->
                <div class="form-group required" >
                <label class="col-sm-2 control-label" for="input-repository_type_id"><?php echo $entry_type; ?>：</label>
                <div class="col-sm-10">
                <?php if ($is_manager): ?>
                  <select name="repository_type_id" id="input-id" class="form-control">
                    <?php foreach ($repository_types_name as $repository_type_name) { ?>
                      <?php if ($repository_type_name['name']==$repository_type) { ?>
                        <option value="<?php echo $repository_type_name['name']; ?>" selected="selected"><?php echo $repository_type_name['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $repository_type_name['name']; ?>" ><?php echo $repository_type_name['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                    <!--  -->
                  </select>
                <?php else: ?>
                  <select name="repository_type_id" id="input-id" class="form-control" disabled="disabled">
                    <?php foreach ($repository_types_name as $repository_type_name) { ?>
                      <?php if ($repository_type_name['name']==$repository_type) { ?>
                        <option value="<?php echo $repository_type_name['name']; ?>" selected="selected"><?php echo $repository_type_name['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $repository_type_name['name']; ?>" ><?php echo $repository_type_name['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                    <!--  -->
                  </select>
                <?php endif ?>
                </div>
              </div>
                <!-- end -->

                <div class="form-group required">
                  <label for="input-name" class="col-sm-2 control-label"><?php echo $entry_name; ?>：</label>
                  <div class="col-sm-10">
                    <?php if ($is_manager || $is_add): ?>
                    <?php if ($repository_name) { ?>
                    <input type="text" name="repository_name" value="<?php echo $repository_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                    <?php } else { ?>
                    <input type="text" name="repository_name" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control"/>
                    <?php } ?>
                    <?php else: ?>
                    <input type="text" name="repository_name" value="<?php echo $repository_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" disabled="disabled" />
                    <?php endif ?>
                  </div>
                </div>
            </div>

            <?php $user_row = 0; ?>
            <?php if (!$is_add): ?>
            <div class="tab-pane fade" id="tab-user">
              <div class="table-responsive col-sm-6">
                <table class="table table-bordered table-hover" id="users-table">
                  <thead><tr>
                    <td class="text-left"><?php echo $column_user_name; ?></td>
                    <?php if ($is_manager): ?>
                    <td></td>
                    <?php endif ?>
                  </tr></thead>
                  <tbody>
                    <?php if (isset($users)): ?>
                    <?php foreach ($users as $user): ?>
                      <tr id="users-<?php echo $user_row; ?>">
                        <td class="class-left"><?php echo $user['username']; ?></td>
                        <?php if ($is_manager): ?>
                        <td class="text-center"><button type="button" onclick="$('#users-<?php echo $user_row; ?>').remove();"" data-toggle="tooltip" data-original-title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        <?php endif ?>
                        <input type="hidden" name="users[]" value="<?php echo $user['user_id']; ?>">
                      </tr>
                      <?php $user_row++; ?>
                    <?php endforeach ?>
                    <?php endif ?>
                  </tbody>
                  <?php if ($is_manager): ?>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td class="text-center"><button type="button" onclick="addUser()" data-toggle="tooltip" data-original-title="添加" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                  <?php endif ?>
                </table>
              </div>
            </div>
            <?php endif ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
function incValue(option_name) {
  $(option_name).val(parseInt($(option_name).val()) + 1);
  return;
}

function decValue(option_name) {
  var a = parseInt($(option_name).parent().parent().children()[4].innerHTML);
  var b = parseInt($(option_name).val());
  if (a + b >= 1) {
    $(option_name).val(b-1);
  }
  return;
}

var user_row = <?php echo $user_row; ?>;
function addUser() {
  user_row++;
  html = '<tr id="users-'+user_row+'">';
  html += '<td class="text-left">';
  html += '<select name="users[]" form="form-repository-prod">';
  <?php if (isset($all_users)): ?>
  <?php foreach ($all_users as $user): ?>
    html += '<option value="<?php echo $user['user_id'] ?>"><?php echo $user['user_name']; ?></option>';
  <?php endforeach ?>
  <?php endif ?>
  html += '</select>';
  html += '</td>';
  html += '<td class="text-center"><button type="button" onclick="$(\'#users-'+user_row+'\').remove();"" data-toggle="tooltip" data-original-title="移除" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td></tr>';
  $('#users-table tbody').append(html);
}
</script>

  <script type="text/javascript"><!--

$('#button-filter').on('click', function() {
  var url = 'index.php?route=catalog/repository/filter&token=<?php echo $token; ?>';

  var filter_prod_name = $('input[name=\'filter_prod_name\']').val();

  if (filter_prod_name) {
    url += '&filter_prod_name=' + encodeURIComponent(filter_prod_name);
  }

  var filter_prod_id = $('input[name=\'filter_prod_id\']').val();

  if (filter_prod_id) {
    url += '&filter_prod_id=' + encodeURIComponent(filter_prod_id);
  }

  var filter_prod_ovdname = $('input[name=\'filter_prod_ovdname\']').val();

  if (filter_prod_ovdname) {
    url += '&filter_prod_ovdname=' + encodeURIComponent(filter_prod_ovdname);
  }

  var repository_id = $('input[name=\'repository_id\']').val();
  if (repository_id) {
    url += '&repository_id=' + encodeURIComponent(repository_id);
  }

  location = url;
});

$('input[name=\'filter_prod_name\']').autocomplete({
  'source': function(request, response) {
    if (request.length == 0) {
      $('input[name=\'filter_prod_name\']').val('');
      $('input[name=\'filter_prod_id\']').val('');
    }
    $.ajax({
      url: 'index.php?route=catalog/repository/autocomplete&token=<?php echo $token; ?>&filter_prod_name=' +  encodeURIComponent(request) + '&flag=1',
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
  }
});



//--></script>

<?php echo $footer; ?>
