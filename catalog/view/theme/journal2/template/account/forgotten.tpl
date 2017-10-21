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
      <p class="account-text"><?php echo $text_phone; ?></p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        
        <fieldset>
          <h2 class="secondary-title"><?php echo $text_your_phone; ?></h2>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-phone"><?php echo $entry_phone; ?></label>
            <div class="col-sm-10">
              <input type="text" name="phone" value="<?php echo $input_phone; ?>" placeholder="<?php echo $entry_phone; ?>" id="input-phone" class="form-control" />
              <?php if ($error_phone) { ?>
              <div class="text-danger" id="error-phone"><?php echo $error_phone; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>

        <div id="account_type" style="display:none">
        <fieldset>
          <h2 class="secondary-title"><?php echo $text_account_type; ?></h2>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_password_type; ?></label>
            <div class="col-sm-10">
            <?php if ($account_type == "account_non_vip") { ?>
              <label class="radio-inline">
                <input type="radio" name="account_type" value="account_non_vip" checked="checked" />
                <?php echo $text_account_non_vip; ?> </label>
              <label class="radio-inline">
                <input type="radio" name="account_type" value="account_vip"/>
                <?php echo $text_account_vip; ?></label>
            <?php } else { ?>
              <label class="radio-inline">
                <input type="radio" name="account_type" value="account_non_vip" />
                <?php echo $text_account_non_vip; ?> </label>
              <label class="radio-inline">
                <input type="radio" name="account_type" value="account_vip" checked="checked" />
                <?php echo $text_account_vip; ?></label>
            <?php } ?>
            </div>
          </div>
        </fieldset>
        </div>

        <div id="password_type" style="display:none">
        <fieldset>
          <h2 class="secondary-title"><?php echo $text_password_type; ?></h2>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_password_type; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($password_type == "login_password") { ?>
                  <input type="radio" name="password_type" value="login_password" checked="checked" />
                <?php } else { ?>
				  <input type="radio" name="password_type" value="login_password"/>	
				<?php } ?>
                <?php echo $text_login_password; ?> </label>
              <label class="radio-inline">
                <?php if ($password_type == "pay_password") { ?>
                  <input type="radio" name="password_type" value="pay_password" checked="checked" />
                <?php } else { ?>
				  <input type="radio" name="password_type" value="pay_password"/>	
				<?php } ?>
                <?php echo $text_pay_password; ?></label>
              <label class="radio-inline">
                <?php if ($password_type == "both_password") { ?>
                  <input type="radio" name="password_type" value="both_password" checked="checked" />
                <?php } else { ?>
				  <input type="radio" name="password_type" value="both_password"/>	
				<?php } ?>
                <?php echo $text_both_password; ?></label>                
            </div>
          </div>
        </fieldset>
        </div>

        <fieldset>
          <h2 class="secondary-title"><?php echo $text_verification; ?></h2>
           <div  <?php if (!$config_account_reg_vercode) { ?> hidden <?php } ?> class="form-group required">
             <label class="col-sm-2 control-label" for="input-authcode"><?php echo $entry_authcode; ?></label>
             <div class="col-sm-8">
             <input type="text" id="input-authcode" placeholder="<?php echo $entry_authcode; ?>" maxlength="4" style="width: 80%;">
		     <canvas class="authCanvas" id="authCanvas" style="float:right;width:20%;height:33px;padding:3px;">
               Your browser does not support the canvas element.
			 </canvas>             
             </div>
             <div class="text-danger" id="error-authcode" style="display: none;"><?php echo $error_authcode; ?></div>
           </div>                  
           <div <?php if (!$config_account_reg_vercode) { ?> hidden <?php } ?> class="form-group required">
            <label class="col-sm-2 control-label" for="input-vercode"><?php echo $entry_vercode; ?></label>
            <div class="col-sm-8">
              <input type="tel" name="vercode" value="<?php echo $vercode; ?>" placeholder="<?php echo $entry_vercode; ?>" id="input-vercode" class="form-control" />
              <?php if ($error_vercode) { ?>
              <div class="text-danger" id="error-vercode"><?php echo $error_vercode; ?></div>
              <?php } ?>
            </div>
            <input class="col-sm-2" type="button" name="sendvercode" value="<?php echo $entry_sendvercode; ?>" id="sendvercode"/>
          </div>

        </<fieldset>
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
<script type="text/javascript"><!--
checkPhone();
$('input[name=\'phone\']').on('blur', checkPhone);

var g_code = "";

$('#authCanvas').click(function(event,flag){
    $.ajax({
        url: 'index.php?route=account/register/getAuthcode',
        dataType: 'json',
        success:function(json){            
            g_code = json['authcode'];
            if(!flag){
               var ctx = $('#authCanvas').get(0).getContext("2d");    
               ctx.clearRect(0,0,1000,1000);
               ctx.font = "80px Arial";
               ctx.fillText(g_code,50,100);             
            }                          
        }
    });
});

$('#authCanvas').trigger('click',false);


$('input[name=\'sendvercode\']').on('click',function() {
      
      var authcode = $('#input-authcode').val();
      if(authcode != g_code){        
          $('#error-authcode').show().fadeOut(3000); 
          $('#authCanvas').trigger('click',false);        
          return;
      }
              
      phone=$("#input-phone").val();
    //  pattern=/^1[0-9]{10}$/;
      pattern = /^1[34578]\d{9}$/;
      if (!pattern.test(phone) ) {         
          $('#error-phone').show().fadeOut(3000);  
          $('#authCanvas').trigger('click',false);        
      } else {
         $.ajax({
         url: 'index.php?route=account/register/verify&phone=' + phone + '&authcode=' + authcode,
         dataType: 'json',
         success: function(json) {
            if(json['error_authcode']) {
                $('#error-authcode').show().fadeOut(3000);
                $('#authCanvas').trigger('click',false);             
        //     } else if(json['error_phone']){
        //        $('#error-phone').show().fadeOut(3000);
        //       $('#authCanvas').trigger('click',false);
        //    } else  if(json['error_card']){
        //        $('#error-card').show().fadeOut(3000);
        //       $('#authCanvas').trigger('click',false);					
            } else {
               var step = 59;
               $('#sendvercode').val('重新发送60');
	           $("#input-phone").attr("readonly",true);
               var _res = setInterval(function() {   
               $("#sendvercode").attr("disabled", true);//设置disabled属性
               $('#sendvercode').val('重新发送'+step);
               step-=1;
               if(step <= 0){
               $("#sendvercode").removeAttr("disabled"); //移除disabled属性
               $('#sendvercode').val('点击发送验证码');
               $("#input-phone").attr("readonly",false);
               clearInterval(_res);//清除setInterval
               }
            },1000);     
            $('#authCanvas').trigger('click',true);          
           }                   
         },
         error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         }
         });             
       } 
       
       //$('#authCanvas').click();
});

$('input[name=\'account_type\']').on('change',function() {
    if ($('input[name=\'account_type\']:checked').val() == 'account_vip') {
		$("div[id=password_type]").show();
	} else {
		$("div[id=password_type]").hide();
		$('input[name=\'password_type\']:eq(0)').attr('checked',true);
	}
});

function checkPhone() {
	phone=$("#input-phone").val();
    $.ajax({
        url: 'index.php?route=account/forgotten/checkPhone&phone=' + phone,
        dataType: 'json',
        success: function(json) {
		
			if (json['query_phone'] && json['query_email']) {
			// Display the choice
				$("div[id=account_type]").show();
			} else {
				$("div[id=account_type]").hide();
				$('input[name=\'account_type\']:eq(0)').attr('checked',true);
			}
			
			if (json['query_phone']) {  // VIP card user?
			// Display the choice
			//	$("div[id=password_type]").show();
				$('input[name=\'account_type\']').trigger('change');
			} else {
				$("div[id=password_type]").hide();
				$('input[name=\'password_type\']:eq(0)').attr('checked',true);
			}			
			
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }    
    });
  /*  
	if (!pattern.test(phone)) {
	   $("div[id=verification]").hide();
	} else {
	   $("div[id=verification]").show();
	}
	*/
}

//--></script> 
<?php echo $footer; ?>