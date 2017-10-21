
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>微信安全支付</title>

  <script type="text/javascript">

    //调用微信JS api 支付
    function jsApiCall()
    {
      WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
        <?php echo $jsApiParameters; ?>,
        function(res){
          // alert(res.err_msg);
          // WeixinJSBridge.log(res.err_msg);
          // alert(res.err_code+res.err_desc+res.err_msg);
           // if(res.err_msg == "get_brand_wcpay_request：ok" ) {
           //  window.location.href = "<?php echo $redirect;?>";
           // }

           switch (res.err_msg){
              case 'get_brand_wcpay_request:cancel':
                alert('支付取消，可在历史订单中继续支付');
                 window.location.href="<?php echo $cancel_redirect;?>";
              case 'get_brand_wcpay_request:fail':
                alert('支付失败，请检查相关支付配置');
                 window.location.href="<?php echo $cancel_redirect;?>";
              case 'get_brand_wcpay_request:ok':

  	             window.location.href="<?php echo $redirect;?>";
              // default:
              // alert('default');
                 // window.location.href="<?php echo $redirect;?>";
           }
        }
      );
    }

    function callpay()
    {
      if (typeof WeixinJSBridge == "undefined"){

        // alert('WeixinJSBridge undefined');

          if( document.addEventListener ){
              document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
          }else if (document.attachEvent){
              document.attachEvent('WeixinJSBridgeReady', jsApiCall);
              document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
          }
      }else{
          jsApiCall();
      }
    }
  </script>
  <script type="text/javascript">
      window.onload=function(){
        callpay();
      }
  </script>
</head>
<body>
  <!-- </br></br></br></br>
  <div align="center">
    <button style="width:210px; height:30px; background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >点击即可支付</button>
  </div>

</body>
</html>
