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
        <div class="col-sm-6 left">
          <div class="well">
            <h2 class="secondary-title"><?php echo '会员绑定'; ?></h2>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="login-wrap">
                <p><?php echo '请输入您的手机号以便绑定成为本站会员'; ?></p>
                  <div class="form-group">

                    <label class="control-label" for="input-phone_or_card"><?php echo $entry_phone_or_card; ?></label>
                    <input type="text" name="phone_or_card" value="<?php echo $phone_or_card; ?>" placeholder="<?php echo $entry_phone_or_card; ?>" id="input-phone_or_card" class="form-control" />
                  </div>
                  <div class="form-group">
                  <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                  <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                  <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
                    <div>
                      <a href="<?php echo $register; ?>"><?php echo '没有账号，点击创建账号'; ?></a>
                    </div>
                  </div>

                </div>
              <hr/>
              <input type="submit" value="<?php echo '绑定'; ?>" class="btn btn-primary button" />

              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
              <?php } ?>
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