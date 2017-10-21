<?php 
/**
* appalipay
*/
class ControllerJournal2Appalipay extends Controller
{
	
	private $json_data=array();
	private $json=array();


	public function index() {
		
		$this->json_data=json_decode(file_get_contents("php://input"),true);

		$this->load->helper('alipay_dt_core');
		$this->load->helper('alipay_dt_md');
		
		$this->language->load('payment/alipay_direct');

		$data['button_confirm'] = $this->language->get('button_confirm');
		
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']	=	$this->config->get('alipay_direct_partner');

		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']		=	$this->config->get('alipay_direct_security_code');
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = getcwd().'\\cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = HTTPS_SERVER;
		
		$this->load->model('checkout/order');

		$order_id = $this->session->data['order_id'];

		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		
		$item_name = $this->config->get('config_name');
		$first_name = $order_info['payment_firstname'];
		$last_name = $order_info['payment_lastname'];
		
		// Totals
		//get shipping fee
		$this->load->model('account/order');

		$shipping_cost = 0;

		$totals = $this->model_account_order->getOrderTotals($order_id);

		foreach ($totals as $total) {
			
			if($total['title'] == 'shipping') {
				$shipping_cost = $total['value'];
			}
			
		}
		
		//支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = HTTPS_SERVER.'catalog/controller/payment/alipay_direct_callback.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = $this->url->link('checkout/success');
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //卖家支付宝帐户
        $seller_email = $this->config->get('alipay_direct_seller_email');
        //必填

        //商户订单号
        $out_trade_no = $this->session->data['order_id'];
		$timeStamp = time();
        $out_trade_no = $timeStamp . "alipay". $this->session->data['order_id'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $item_name . ' ' . $this->language->get('text_order') .' '. $order_id;
        //必填

        //付款金额
        $amount = $order_info['total'];
		
		$currency_value = $this->currency->getValue('CNY');
		$price = $amount * $currency_value;
		$price = number_format($price,2,'.','');
		
		$total_fee = $price;
		
        //必填
        $body =  $this->language->get('text_owner') . ' ' . $first_name .' '. $last_name;
        //商品展示地址
        $show_url = $this->url->link('common/home', '', 'SSL');
        //需以http://开头的完整路径，如：http://www.商户网站.com/myorder.html
		
		//防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1


		
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "create_direct_pay_by_user",
				"partner" => trim($alipay_config['partner']),
				"payment_type"	=> $payment_type,
				"notify_url"	=> $notify_url,
				"return_url"	=> $return_url,
				"seller_email"	=> $seller_email,
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"total_fee"	=> $total_fee,
				"body"	=> $body,
				"show_url"	=> $show_url,
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		//print_r($parameter);exit;
		
		$this->load->library('alipaydtsubmit');
		
		$this->alipaydtsubmit = new Alipaydtsubmit($alipay_config);
		
		$sResult=$this->alipaydtsubmit->buildRequestHttp($parameter);
		
		
		$this->data['html_text'] = $this->alipaydtsubmit->buildRequestForm($parameter,"get", $this->language->get('button_confirm'));

		$this->json['html_text'] = $this->alipaydtsubmit->buildRequestForm($parameter,"get", $this->language->get('button_confirm'));
		// var_dump($data['html_text']);
		
		// $this->response->addHeader('Content-Type:application/json');
		// $this->response->setOutput(json_encode($this->json));
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/alipay_direct.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/alipay_direct.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/alipay_direct.tpl', $data);
		}
		
	}
	
	public function callback() {
		
		$this->load->helper('alipay_dt_core');
		$this->load->helper('alipay_dt_md');	
		
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']	=	$this->config->get('alipay_direct_partner');

		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']		=	$this->config->get('alipay_direct_security_code');
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = getcwd().'\\cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = HTTPS_SERVER;
		
		$log = $this->config->get('alipay_direct_log');
		
		if($log) {
			$this->log->write('Alipay_Direct :: One: ');
		}
		
		$this->load->library('alipaydtnotify');
		
		//计算得出通知验证结果
		$alipayNotify = new Alipaydtnotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		if($log) {
			$this->log->write('Alipay_Direct :: Two: ' . $verify_result);
		}
		
		
		
		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代
		
			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
			//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//商户订单号
		
			$out_trade_no = $this->request->post['out_trade_no'];
			$order_id_fake =$this->request->post['out_trade_no']; 
                        $order_id = end(explode('alipay', $order_id_fake));
			//$order_id   = $out_trade_no; 
		
			//支付宝交易号
		
			$trade_no = $this->request->post['trade_no'];
		
			//交易状态
			$trade_status = $this->request->post['trade_status'];
			
			$order_status_id = $this->config->get('config_order_status_id');
			
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			if($log) {
				$this->log->write('Alipay_Direct :: Three: ');
			}
			
			if ($order_info) {
				
				if($log) {
					$this->log->write('Alipay_Direct :: Four: ');
				}
				
			
				if($_POST['trade_status'] == 'TRADE_FINISHED') {
				//注意：
					//该种交易状态只在两种情况下出现
					//1、开通了普通即时到账，买家付款成功后。
					//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
				
					if($log) {
						$this->log->write('Alipay_Direct :: Five: ');
					}

					// huwen added
					$this->load->model('account/order');
					$shipping_code = $this->model_account_order->getOrderShippingcode($order_id);

					if ($shipping_code['shipping_code'] == "pickup.pickup") {
						$order_status_id = "17";
					} else {
						$order_status_id = $this->config->get('alipay_direct_trade_finished_status_id');
					}
					

					if (!$order_info['order_status_id']) {
						
						$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
						
					} else {
						
						$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
						
					}
				
					//判断该笔订单是否在商户网站中已经做过处理
						//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
						//如果有做过处理，不执行商户的业务程序
						
					echo "success";		//请不要修改或删除
			
					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
				} else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				//注意：
					//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
					
					if($log) {
						$this->log->write('Alipay_Direct :: Six: ');
					}
					// huwen added
					$this->load->model('account/order');
					$shipping_code = $this->model_account_order->getOrderShippingcode($order_id);

					if ($shipping_code['shipping_code'] == "pickup.pickup") {
						$order_status_id = "17";
					} else {
						$order_status_id = $this->config->get('alipay_direct_trade_finished_status_id');
					}
					// $order_status_id = $this->config->get('alipay_direct_trade_success_status_id');

					if (!$order_info['order_status_id']) {
						
						$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
						
					} else {
						
						$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
						
					}
						
					echo "success";		//请不要修改或删除
			
					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
				}
			
				
			}else{
				
				if($log) {
					$this->log->write('Alipay_Direct :: Seven: ');
				}
				
				//无此订单
				echo "fail";
				
			}
			
		} else {
			
			if($log) {
				$this->log->write('Alipay_Direct :: Eight: ');
			}
			
			//验证失败
			echo "fail";
		
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
		
		
		
		
	}

}