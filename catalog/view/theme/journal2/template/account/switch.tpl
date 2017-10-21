<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <h1 class="heading-title"><?php echo $heading_title; ?></h1>
      <?php echo $content_top; ?>


    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
     
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-vipcard"><?php echo $entry_vipcard; ?></label>
            <div class="col-sm-10">
              <input type="text" name="vipcard" value="<?php echo $vipcard; ?>" placeholder="<?php echo $entry_vipcard; ?>" id="input-vipcard" class="form-control" />
              <?php if ($error_vipcard) { ?>
              <div class="text-danger"><?php echo $error_vipcard; ?></div>
              <?php } ?>      
            </div>
          </div>
        </fieldset>
      <div class="buttons clearfix">
     <?php // Wenliang added, for switch user ?>     
        <div class="pull-right">
         <input type="submit" value="<?php echo $button_switch_user; ?>" class="btn btn-primary" />
        </div>
       
      </div>
    </form>     

      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>