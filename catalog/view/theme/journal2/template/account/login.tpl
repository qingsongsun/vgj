<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_top; ?>
      <div class="row login-content">      
        <div class="col-sm-6 right">
          <div class="well">
            <h2 class="secondary-title"><?php echo $text_new_customer; ?></h2>
            <div class="login-wrap">
            <p><strong><?php echo $text_register; ?></strong></p>
            <p><?php echo $text_register_account; ?></p>
            </div>
            <hr/>
            <a href="<?php echo $register; ?>" class="btn btn-primary button"><?php echo $button_continue; ?></a></div>
        </div>
        <div class="col-sm-6 left">
          <div class="well">
            <h2 class="secondary-title"><?php echo $text_returning_customer; ?></h2>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="login-wrap">
                <p><?php echo $text_i_am_returning_customer; ?></p>
              <div class="form-group">
<?php /* Wenliang replaced with phone or card login 
                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
*/ ?>
                <label class="control-label" for="input-phone_or_card"><?php echo $entry_phone_or_card; ?></label>
                <input type="text" name="phone_or_card" value="<?php echo $phone_or_card; ?>" placeholder="<?php echo $entry_phone_or_card; ?>" id="input-phone_or_card" class="form-control" />            
              </div>
              <div class="form-group">
                <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
                </div>
              <hr/>
              <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary button" />
              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
              <?php } ?>
              <!--ishanks added it to allow redirect from the page before login without cutting the previous logic -->
              <?php if (isset($redirect_return)){ ?>
              <input type="hidden" name="redirect_return" value="<?php echo $redirect_return; ?>" />
              <?php } ?>
            </form>
          </div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>