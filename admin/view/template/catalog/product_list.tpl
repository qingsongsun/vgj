<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>

        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>


        <button type="button" data-toggle="tooltip" title="<?php echo '导出二维码'; ?>" class="btn btn-info" onclick="$('#form-product').attr('action', '<?php echo $downloadQrcode; ?>').submit()"><i class="fa fa-print"></i></button>

        <button type="button" data-toggle="tooltip" title="<?php echo '查看url列表'; ?>" class="btn btn-info" onclick="$('#form-product').attr('action', '<?php echo $downloadQrcodeUrl; ?>').submit()"><i class="fa fa-eye"></i></button>



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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
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
                <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
              </div>
              <div class="form-group">

                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
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

<?php // Wenliang added, for scenario add/delete   ?>
        <!--  <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-scenario-add"><?php echo $entry_add_scenario; ?></label>
                <input type="text" name="scenario_name_add" value="<?php echo ''; ?>" placeholder="<?php echo $entry_scenario; ?>" id="input-scenario-add" class="form-control" />
              </div>
              <button type="button" id="button-scenario-add" class="btn btn-primary pull-right"><?php echo $button_add_scenario; ?></button>
            </div>
            <div class="col-sm-8">
              <div class="form-group" id="input-scenario-delete">
                <label class="control-label"><?php echo $entry_delete_scenario; ?></label>
              <p/>
              <?php foreach ($scenarios as $scenario) { ?>
           <input type="checkbox" name="scenario_name_delete" value="<?php echo $scenario['combination_name'] ?>"><?php echo $scenario['combination_name'] ?>
        <?php } ?>
              </div>
            </div>
            <button type="button" id="button-scenario-delete" class="btn btn-primary pull-right"><?php echo $button_delete_scenario; ?></button>
          </div>
        </div>
 -->
<?php // Wenliang added, for scenario add/delete   ?>
         <!-- <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-product-name"><?php echo $entry_product_name; ?></label>
                <input type="text" name="scenario_product_name" value="<?php echo ''; ?>" placeholder="<?php echo $entry_product_name; ?>" id="input-product-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group" id="input-scenario-change">
                <label class="control-label"><?php echo $entry_set_product_scenario; ?></label>
              <p/>
              <?php foreach ($scenarios as $scenario) { ?>
           <input type="checkbox" name="scenario_name_change" value="<?php echo $scenario['combination_name'] ?>"><?php echo $scenario['combination_name'] ?>
        <?php } ?>
              </div>
            </div>
            <button type="button" id="button-scenario-change" class="btn btn-primary pull-right"><?php echo $button_set_product_scenario; ?></button>
          </div>
        </div>
 -->
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <!-- Luke added for creator (begin) -->
                  <td class="text-left"><?php echo $column_creator; ?></td>
                  <!-- Luke added for creator (end) -->
                  <td class="text-center"><?php echo $column_image; ?></td>
                  <!-- huwen added for qrcode -->
                  <td class="text-center"><?php echo '二维码'; ?></td>
                  <!--  -->
                  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                    <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                    <?php } ?></td>
                    <!-- huwen added for repo_num -->
                    <td class="text-right"><?php if ($sort == 'p.repo_num') { ?>
                    <a href="<?php echo $sort_repo_num; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_repo_num; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_repo_num; ?>"><?php echo $column_repo_num; ?></a>
                    <?php } ?></td>
                    <!-- end -->
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                    <?php // Wenliang added, for scenario name ?>
<!--
                  <td class="text-left"><?php if ($sort == 'sc.name') { ?>
                    <a href="<?php echo $sort_scenario; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_scenario; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $entry_scenario; ?></a>
                    <?php } ?></td>
-->
                  <td class="text-right"><?php echo $column_action; ?></td>
                  <?php if ($VERIFY_SWITCH) { ?>
                    <td class="text-right"><?php echo '防伪验证码'; ?></td>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>

                  <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>

                  <!-- Luke added for creator (begin) -->
                  <td><?php echo $product['creator']; ?></td>
                  <!-- Luke added for creator (end) -->
                  <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                  <!-- huwen added for qrcode -->
                  <td class="text-center"><?php if ($product['image']) { ?>
                  <img src="view/image/qrcode/<?php echo str_replace('+','%20',urlencode($product['model'])); ?>.png" alt="<?php echo $product['name']; ?>" class="img-thumbnail" width="100px"/>
                  <?php } else { ?>
                  <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                  <?php } ?></td>
                  <!--  -->
                  <td class="text-left"><?php echo $product['name']; ?></td>
                  <td class="text-left"><?php echo $product['model']; ?></td>
                  <td class="text-right"><?php if ($product['special']) { ?>
                    <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                    <div class="text-danger"><?php echo $product['special']; ?></div>
                    <?php } else { ?>
                    <?php echo $product['price']; ?>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
                    <!-- huwen added for repo_num -->
                    <td class="text-right"><?php if ($product['repo_num'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['repo_num']; ?></span>
                    <?php } elseif ($product['repo_num'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['repo_num']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['repo_num']; ?></span>
                    <?php } ?></td>
                    <!-- end -->
                  <td class="text-left"><?php echo $product['status']; ?></td>
                  <?php // Wenliang added, for scenario ?>
<!--
                  <td class="text-left"><?php echo $product['scenarios']; ?></td>
-->
                  <!-- huwen added for :打印吊牌 -->
                  <td class="text-right">
                  <!-- 并且对编辑产品增加权限控制：部分角色（例如：仓库管理员）无法编辑产品详情;产品负责人只能编辑自己创建的产品详情 -->
                  <?php if ($user_id==$product['creator_id']||$ADMIN) { ?>
                    <a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  <?php } ?>

                  <a href="<?php echo $product['getQrcodeTicket']; ?>" data-toggle="tooltip" title="<?php echo '打印吊牌'; ?>" class="btn btn-primary"><i class="fa fa-print"></i></a>
                  </td>

                  <?php if ($VERIFY_SWITCH) { ?>
                    <td class="text-right">
                      <button  type="button" style="margin-left: 4px;" onclick="button_verify_add(<?php echo $product['product_id'] ?>);" data-toggle="tooltip"
                      title="<?php echo "生成一物一码" ?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>
                      </button>
                      <!-- <a href="<?php echo $product['creat_verify']; ?>" data-toggle="tooltip" title="<?php echo '生成一物一码'; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a> -->
                      <a href="<?php echo $product['verify']; ?>" data-toggle="tooltip" title="<?php echo '查看一物一码'; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                    </td>
                  <?php } ?>

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
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
  function button_verify_add(product_id){
      var quantity=prompt("请输入数量");
       $.ajax({
          url: 'index.php?route=catalog/product_verify/add&token=<?php echo $token; ?>&quantity='+quantity+'&product_id='+product_id,
          type: 'post',
          dataType: 'json',
          data: {
            'quantity': quantity,
          },
          beforeSend: function() {
            console.log('beforeSend');
            // $('.btn btn-primary pull-right').button('loading');
          },
          complete: function() {
            console.log('complete');
          },
          success: function(json) {
            console.log(json);
            // $('.btn btn-primary pull-right').button('reset');
            if (json['success']) {
              alert('防伪验证码生成成功。');
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
  }


  </script>

  <script type="text/javascript"><!--
$('#button-scenario-add').on('click', function() {
  var url = 'index.php?route=catalog/product/addScenario&token=<?php echo $token; ?>';

  var scenario_name = $('input[name=\'scenario_name_add\']').val();

  if (scenario_name) {
    url += '&scenario_name=' + encodeURIComponent(scenario_name);
  }

  location = url;
});

$('#button-scenario-delete').on('click', function() {
  var url = 'index.php?route=catalog/product/deleteScenario&token=<?php echo $token; ?>';

    var scenario_name = new Array();
    $(':checkbox[name=\'scenario_name_delete\']:checked').each(function(){
       scenario_name.push($(this).val());
    });

  if (scenario_name) {
    url += '&scenario_name=' + encodeURIComponent(scenario_name);
  }

  location = url;
});

$('#button-scenario-change').on('click', function() {
  var url = 'index.php?route=catalog/product/addProductScenario&token=<?php echo $token; ?>';

    var scenario_name = new Array();
    $(':checkbox[name=\'scenario_name_change\']:checked').each(function(){
       scenario_name.push($(this).val());
    });

  if (scenario_name) {
    url += '&scenario_name=' + encodeURIComponent(scenario_name);
  }

  var product_name = $('input[name=\'scenario_product_name\']').val();
  if (product_name) {
    url += '&product_name=' + encodeURIComponent(product_name);
  }

  location = url;
});

//--></script>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

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

  var filter_price = $('input[name=\'filter_price\']').val();

  if (filter_price) {
    url += '&filter_price=' + encodeURIComponent(filter_price);
  }

  var filter_quantity = $('input[name=\'filter_quantity\']').val();

  if (filter_quantity) {
    url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
  }

  var filter_repo_num = $('input[name=\'filter_repo_num\']').val();

  if (filter_repo_num) {
    url += '&filter_repo_num=' + encodeURIComponent(filter_repo_num);
  }

  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  location = url;
});

$('input[name=\'scenario_product_name\']').on('blur', function() {
  var url = 'index.php?route=catalog/product/getProductScenarios&token=<?php echo $token; ?>';

  var product_name = $('input[name=\'scenario_product_name\']').val();

  if (product_name) {
    url += '&product_name=' + encodeURIComponent(product_name);
    $.ajax({
    url: url,
    dataType: 'json',
    success: function(json) {
  //    alert(json['scenarios']);

    //    var obj = document.getElementById("input-scenario-change");

      for (var i = json.length - 1; i >= 0; i--) {
      //  scenario = 'div#input-scenario-change :checkbox[value="' + json[i] + '"]';
          $(':checkbox[value="' + json[i] + '"][name="scenario_name_change"]').prop("checked", true);
      }

  //  $('select[name=\'scenario_name_change\'] option').replaceWith(function(){
  //    return '<option value="test 1" selected="selected">test 1</option><option value="测试" selected="selected">测试</option>';
  //  });

    }
  });
  }


});

/* Not proper to use autocomplete here, conflict with checked refresh
$('input[name=\'scenario_product_name\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
    $('input[name=\'scenario_product_name\']').val(item['label']);
  }
});
*/
//--></script>
  <script type="text/javascript"><!--

// Luke added for filtering creator.
$('input[name=\'filter_creator\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_creator=' +  encodeURIComponent(request),
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
// huwen added for getQrcodeTicket
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

$('input[name=\'filter_name\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
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
//--></script></div>
<?php echo $footer; ?>
