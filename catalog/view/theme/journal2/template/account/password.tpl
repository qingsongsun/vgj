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
      <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">


      <!--  huwen added ,for only VIP customer can select the password_type-->
      <?php if ($user_group == GROUP_NAME_VIP_USER) { ?>
        <fieldset>
          <h2 class="secondary-title"><?php echo $text_password_type; ?></h2>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_password_type; ?></label>
            <div class="col-sm-10">
              <?php if ($password_type == "login_password") { ?>
	              <label class="radio-inline">
	                <input type="radio" name="password_type" value="login_password" checked="checked" />
	                <?php echo $text_login_password; ?> </label>
	              <label class="radio-inline">
	                <input type="radio" name="password_type" value="pay_password" />
	                <?php echo $text_pay_password; ?></label>
	          <?php } else { ?>
	              <label class="radio-inline">
	                <input type="radio" name="password_type" value="login_password" />
	                <?php echo $text_login_password; ?> </label>
	              <label class="radio-inline">
	                <input type="radio" name="password_type" value="pay_password" checked="checked" />
	                <?php echo $text_pay_password; ?></label>
               <?php } ?>   			 
            </div>
          </div>
        </fieldset>     
        <?php }else{ ?>
            <input type="hidden" name="password_type" value="<?php echo $password_type; ?>" /> 
     	<?php } ?>
     	
        <fieldset>
          <h2 class="secondary-title"><?php echo $text_password; ?></h2>       
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-old_password"><?php echo $entry_old_password; // Wenliang added, for old password check?></label>
            <div class="col-sm-10">
              <input type="password" name="old_password" value="<?php echo $old_password; ?>" placeholder="<?php echo $entry_old_password; ?>" id="input-old_password" class="form-control" />
              <?php if ($error_old_password) { ?>
              <div class="text-danger"><?php echo $error_old_password; ?></div>
              <?php } ?>
            </div>
          </div>
<div class="form-group">
          <label class="col-sm-2 control-label"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></label>
</div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>

                
        <div class="buttons">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default button"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary button" />
          </div>
        </div>
      </form>
      </div>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?> 