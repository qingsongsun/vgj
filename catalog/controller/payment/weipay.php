<?php
require_once (DIR_WXHELPER);
require_once (DIR_WXCONFIG);

class ControllerPaymentWeiPay extends Controller {
	public function index() {

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['action'] = $this->url->link('payment/weipay/confirm_order', '', 'SSL');
		$this->load->model('payment/weipay');
		// $this->model_payment_weipay->logger($this->session->data['order_id']);
		//Following two have no impact on the source, this will be added in the checkout.php
		//$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		//$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');

		if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/weipay_ordered.tpl')) {
			// $this->model_payment_weipay->logger(DIR_TEMPLATE);
			return $this->load->view($this->config->get('config_template').'/template/payment/weipay_ordered.tpl', $data);
		} else {
			// $this->model_payment_weipay->logger(DIR_TEMPLATE);
			return $this->load->view('default/template/payment/weipay_ordered.tpl', $data);
		}
	}

	public function confirm_order() {
		$this->load->language('payment/weipay');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['header'] = $this->load->controller('common/header');

		$url = $this->url->link('payment/weipay/checkout', '', 'SSL');

		$this->load->model('payment/weipay');
		Header("Location: $url");

		if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/weipay.tpl')) {
			return $this->response->setOutput($this->config->get('config_template').'/template/payment/weipay.tpl', $data);
		} else {
			return $this->response->setOutput($this->load->view('default/template/payment/weipay.tpl', $data));
		}
	}

	public function checkout() {
		$this->load->model('checkout/order');
		$this->load->model('account/order');
		$this->load->model('payment/weipay');

		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 2);
		//fix a bug :the cart be cleared when re-payment
		$this->cart->clear();

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->load->model('extension/extension');
		$results    = $this->model_extension_extension->getExtensions('total');
		$order_data = array();
		$total      = 0;
		$items      = array();
		$taxes      = $this->cart->getTaxes();

		$i = 0;
		foreach ($results as $result) {
			if ($this->config->get($result['code'].'_status')) {
				$this->load->model('total/'.$result['code']);

				$this->{'model_total_'.$result['code']}->getTotal($order_data['totals'], $total, $taxes);

				if (isset($order_data['totals'][$i])) {
					if (strstr(strtolower($order_data['totals'][$i]['code']), 'total') === false) {
						$item         = new stdClass();
						$item->sku    = $order_data['totals'][$i]['code'];
						$item->name   = $order_data['totals'][$i]['title'];
						$item->amount = $order_data['totals'][$i]['value'];
						$item->qty    = 1;
						$items[]      = $item;
					}
					$i++;
				}
			}
		}
		//Prepare the total charge
		$currency_code = 'CNY';
		$item_name     = $this->config->get('config_title');//待确认mwb

		$total = $order_info['total'];


		// if ($total==0) {

		// 	$this->load->model('account/order');
		// 	$shipping_code = $this->model_account_order->getOrderShippingcode($this->session->data['order_id']);

		// 	if ($shipping_code['shipping_code'] == "pickup.pickup") {

		// 		$reward_order_status=17;
		// 		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $reward_order_status);

		// 	}else{

		// 		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'],$this->config->get('weipay_order_status_id'));

		// 	}

		// 	$this->response->redirect($this->url->link('checkout/success'));
		// }

		$currency_value = $this->currency->getValue($currency_code);
		$amount         = $total*$currency_value;
		$amount         = number_format($amount, 2, '.', '');
		$amount         = $amount*100;//单位换算为分
		if ($this->config->get('weipay_environment') == 0) {
			$amount = 1;
		}

		$this->model_payment_weipay->logger(var_export($order_info,true));

		//check this
		$ordered_products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);
		$product_detail   = "";
		foreach ($ordered_products as $product) {
			$item           = new stdClass();
			$item->sku      = $product['product_id'];
			$item->name     = $product['name'];
			$product_detail = $product_detail.$product['name'].",";
			$item->amount   = $product['price']*$product['quantity'];
			$item->qty      = $product['quantity'];
			$items[]        = $item;
		}

$this->log->write('此时还未开始支付');
$this->log->write($amount);

		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		if (strpos($user_agent, 'MicroMessenger') === false) {
			//echo "HTTP/1.1 401 Unauthorized";
			$unifiedOrder = new UnifiedOrder_pub();

			WxPayConf_pub::$APPID     = $this->config->get('weipay_appid');
			WxPayConf_pub::$MCHID     = $this->config->get('weipay_mchid');
			WxPayConf_pub::$KEY       = $this->config->get('weipay_key');
			WxPayConf_pub::$APPSECRET = $this->config->get('weipay_appsecret');


			//$unifiedOrder->setParameter("openid","$openid");//商品描述
			if ($this->config->get('weipay_productname_format') == 1) {
				$order_productname = $order_info['store_name'].":".substr(substr($product_detail, 0, -1), 0, 100)."...";
			} else if ($this->config->get('weipay_productname_format') == 0) {
				$order_productname = $order_info['store_name']."订单号:".$this->session->data['order_id'];
			}

			if (isset(WxPayConf_pub::$sub_mch_id)&&isset(WxPayConf_pub::$sub_appid)) {
				$unifiedOrder->setParameter("sub_mch_id",WxPayConf_pub::$sub_mch_id);
				$unifiedOrder->setParameter("sub_appid",WxPayConf_pub::$sub_appid);
			}

			$unifiedOrder->setParameter("body", $order_productname);//商品描述

			$timeStamp                           = time();
			$order_id                            = $this->session->data['order_id'];
			$out_trade_no                        = $this->config->get('weipay_appid')."$timeStamp" ."$order_id";
			$this->session->data["out_trade_no"] = $out_trade_no;
			$unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
			$unifiedOrder->setParameter("total_fee", "$amount");//总金额
			$unifiedOrder->setParameter("notify_url", HTTP_SERVER."payment/weipay/notify");//通知地址

			$unifiedOrder->setParameter("trade_type", "NATIVE");//交易类型 native

			$unifiedOrderResult = $unifiedOrder->getResult();


			if ($unifiedOrderResult["return_code"] == "FAIL") {
				echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
			} elseif ($unifiedOrderResult["result_code"] == "FAIL") {
				echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
				echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
			} elseif ($unifiedOrderResult["code_url"] != NULL) {
				//从统一支付接口获取到code_url
				$code_url = $unifiedOrderResult["code_url"];
			}

			$this->session->data["code_url"] = $code_url;
			$this->model_payment_weipay->addWeiOrder($order_info, $out_trade_no);
			$this->response->redirect($this->url->link('payment/weipay/qrscan', 'order_id='.$this->session->data['order_id']));

		} else {

			/**
			 * 微信jsapi支付
			 * @var string
			 */
			$log_names = "wx_debug.log";//log文件路径
			$logs_     = new Log($log_names);
			$logs_->write("......checkout : index : function begin ......");
			$this->load->language('payment/weipay');

			//开始支付
			//使用jsapi接口

			$jsApi = new JsApi_pub();
			if (!isset($_GET['code'])) {
				//触发微信返回code码
				$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
				Header("Location: $url");
			} else {
				//获取code码，以获取openid
				$code = $_GET['code'];
				$jsApi->setCode($code);
				$openid = $jsApi->getOpenId();
			}

			//=========步骤2：使用统一支付接口，获取prepay_id============
			//使用统一支付接口
			$unifiedOrder             = new UnifiedOrder_pub();
			WxPayConf_pub::$APPID     = $this->config->get('weipay_appid');
			WxPayConf_pub::$MCHID     = $this->config->get('weipay_mchid');
			WxPayConf_pub::$KEY       = $this->config->get('weipay_key');
			WxPayConf_pub::$APPSECRET = $this->config->get('weipay_appsecret');


			$unifiedOrder->setParameter("openid", "$openid");//商品描述
			if ($this->config->get('weipay_productname_format') == 1) {
				$order_productname = $order_info['store_name'].":".substr(substr($product_detail, 0, -1), 0, 100)."...";
			} else if ($this->config->get('weipay_productname_format') == 0) {
				$order_productname = $order_info['store_name']."订单号:".$this->session->data['order_id'];
			}
			$unifiedOrder->setParameter("body", $order_productname);//商品描述

			$timeStamp                           = time();
			$order_id                            = $order_info['order_id'];
			$out_trade_no                        = $this->config->get('weipay_appid')."$timeStamp" ."$order_id";
			$this->session->data["out_trade_no"] = $out_trade_no;
			$unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
			$unifiedOrder->setParameter("total_fee", "$amount");//总金额
			$unifiedOrder->setParameter("notify_url", HTTP_SERVER."payment/weipay/notify");//通知地址
			$unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型

			//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
			//$unifiedOrder->setParameter("device_info","XXXX");//设备号
			//$unifiedOrder->setParameter("attach","XXXX");//附加数据
			//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
			//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
			//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
			//$unifiedOrder->setParameter("openid","XXXX");//用户标识
			//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
			//获取统一支付接口结果
			$unifiedOrderResult = $unifiedOrder->getResult();
			// $unifiedOrderXml = $unifiedOrder->createXml();

			// $prepay_id = $unifiedOrder->getPrepayId();	//此方法没获取到prepay_id
			$prepay_id=$unifiedOrderResult['prepay_id'];

			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
$this->log->write('此时即将开始支付');
$this->log->write($amount);
			$data['jsApiParameters'] = $jsApiParameters;
			$data['redirect']        = $this->url->link('checkout/success');
			$data['cancel_redirect'] = $this->url->link('account/order');

			$this->model_payment_weipay->addWeiOrder($order_info, $out_trade_no);

			if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/weipay_checkout.tpl')) {
				return $this->response->setOutput($this->config->get('config_template').'/template/payment/weipay_checkout.tpl', $data);
			} else {
				return $this->response->setOutput($this->load->view('default/template/payment/weipay_checkout.tpl', $data));
			}
		}
	}

	// public function getJsApiPara($unifiedOrderResult){
	// 	if (!array_key_exists('appid',$unifiedOrderResult)||!array_key_exists('prepay_id',$unifiedOrderResult)||!$unifiedOrderResult['prepay_id']=='') {
	// 		throw new Exception('参数错误');
	// 	}
	// }

	public function payment() {

		$this->load->model('checkout/order');
		$this->load->model('account/order');
		$this->load->model('payment/weipay');

		/**
		 * 此处修复BUG：获取openID跳转导致ORDER_ID丢失
		 * 解决方案：存入session
		 */
		if (isset($this->session->data['order_id'])) {
			$order_id=$this->session->data['order_id'];
		}else{
			$this->session->data['order_id']=$this->request->get['order_id'];
			$order_id   = $this->request->get['order_id'];
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);
		//Prepare the total charge
		$currency_code = 'CNY';
		$item_name     = $this->config->get('config_title');//待确认mwb

		$total = $order_info['total'];

		// if ($total==0) {

		// 	$this->load->model('account/order');
		// 	$shipping_code = $this->model_account_order->getOrderShippingcode($this->session->data['order_id']);

		// 	if ($shipping_code['shipping_code'] == "pickup.pickup") {

		// 		$reward_order_status=17;
		// 		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $reward_order_status);

		// 	}else{

		// 		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'],$this->config->get('weipay_order_status_id'));

		// 	}

		// 	$this->response->redirect($this->url->link('checkout/success'));
		// }

		$currency_value = $this->currency->getValue($currency_code);
		$amount         = $total*$currency_value;
		$amount         = number_format($amount, 2, '.', '');
		$amount         = $amount*100;//单位换算为分
		if ($this->config->get('weipay_environment') == 0) {
			$amount = 1;
		}

		//check this
		$ordered_products = $this->model_account_order->getOrderProducts($order_id);
		$product_detail   = "";
		foreach ($ordered_products as $product) {
			$item      = new stdClass();
			$item->sku = $product['product_id'];

			$item->name     = $product['name'];
			$product_detail = $product_detail.$product['name'].",";
			$item->amount   = $product['price']*$product['quantity'];
			$item->qty      = $product['quantity'];
			$items[]        = $item;
		}
		$this->log->write('此时还未开始支付');
		$this->log->write($amount);

		/**
		 * 继续支付关闭了jsapi,如需打开，去掉下一条注释即可
		 */
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		if (strpos($user_agent, 'MicroMessenger') === false) {

			//echo "HTTP/1.1 401 Unauthorized";
			$unifiedOrder = new UnifiedOrder_pub();

			WxPayConf_pub::$APPID     = $this->config->get('weipay_appid');
			WxPayConf_pub::$MCHID     = $this->config->get('weipay_mchid');
			WxPayConf_pub::$KEY       = $this->config->get('weipay_key');
			WxPayConf_pub::$APPSECRET = $this->config->get('weipay_appsecret');

			if ($this->config->get('weipay_productname_format') == 1) {
				$order_productname = $order_info['store_name'].":".substr(substr($product_detail, 0, -1), 0, 100)."...";
			} else if ($this->config->get('weipay_productname_format') == 0) {
				$order_productname = $order_info['store_name']."订单号:".$this->session->data['order_id'];
			}
			$unifiedOrder->setParameter("body", $order_productname);//商品描述

			$timeStamp                           = time();
			$order_id                            = $this->session->data['order_id'];
			$out_trade_no                        = $this->config->get('weipay_appid')."$timeStamp" ."$order_id";
			$this->session->data["out_trade_no"] = $out_trade_no;

			if (isset(WxPayConf_pub::$sub_mch_id)&&isset(WxPayConf_pub::$sub_appid)) {
				$unifiedOrder->setParameter("sub_mch_id",WxPayConf_pub::$sub_mch_id);
				$unifiedOrder->setParameter("sub_appid",WxPayConf_pub::$sub_appid);
			}

			$unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
			$unifiedOrder->setParameter("total_fee", "$amount");//总金额
			$unifiedOrder->setParameter("notify_url", HTTP_SERVER."payment/weipay/notify");//通知地址
			// $this->model_payment_weipay->logger($this->url->link('payment/weipay/notify'));
			$unifiedOrder->setParameter("trade_type", "NATIVE");//交易类型

			$unifiedOrderResult = $unifiedOrder->getResult();

			//商户根据实际情况设置相应的处理流程
			if ($unifiedOrderResult["return_code"] == "FAIL") {
				//商户自行增加处理流程
				echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
			} elseif ($unifiedOrderResult["result_code"] == "FAIL") {
				//商户自行增加处理流程
				echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
				echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
			} elseif ($unifiedOrderResult["code_url"] != NULL) {
				//从统一支付接口获取到code_url
				$code_url = $unifiedOrderResult["code_url"];
				//商户自行增加处理流程
				//......
			}
			$this->session->data["code_url"] = $code_url;
			$this->model_payment_weipay->addWeiOrder($order_info, $out_trade_no);
			$this->response->redirect($this->url->link('payment/weipay/qrscan', 'order_id='.$order_info['order_id']));


		} else {
			/**
			 * 微信jsapi支付
			 * @var string
			 */
			$log_names = "wx_debug.log";//log文件路径
			$logs_     = new Log($log_names);
			$logs_->write("......checkout : index : function begin ......");
			$this->load->language('payment/weipay');

			//开始支付
			//使用jsapi接口

			$jsApi = new JsApi_pub();
			if (!isset($_GET['code'])) {
				//触发微信返回code码
				$url = $jsApi->createOauthUrlForCode('http://club.vgaijia.com/payment/weipay/payment');
				Header("Location: $url");
			} else {
				//获取code码，以获取openid
				$code = $_GET['code'];
				$jsApi->setCode($code);
				$openid = $jsApi->getOpenId();
			}

			//=========步骤2：使用统一支付接口，获取prepay_id============
			//使用统一支付接口
			$unifiedOrder             = new UnifiedOrder_pub();
			WxPayConf_pub::$APPID     = $this->config->get('weipay_appid');
			WxPayConf_pub::$MCHID     = $this->config->get('weipay_mchid');
			WxPayConf_pub::$KEY       = $this->config->get('weipay_key');
			WxPayConf_pub::$APPSECRET = $this->config->get('weipay_appsecret');


			$unifiedOrder->setParameter("openid", "$openid");//商品描述
			if ($this->config->get('weipay_productname_format') == 1) {
				$order_productname = $order_info['store_name'].":".substr(substr($product_detail, 0, -1), 0, 100)."...";
			} else if ($this->config->get('weipay_productname_format') == 0) {
				$order_productname = $order_info['store_name']."订单号:".$this->session->data['order_id'];
			}
			$unifiedOrder->setParameter("body", $order_productname);//商品描述

			$timeStamp                           = time();
			$order_id                            = $order_info['order_id'];
			$out_trade_no                        = $this->config->get('weipay_appid')."$timeStamp" ."$order_id";
			$this->session->data["out_trade_no"] = $out_trade_no;
			$unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
			$unifiedOrder->setParameter("total_fee", "$amount");//总金额
			$unifiedOrder->setParameter("notify_url", HTTP_SERVER."payment/weipay/notify");//通知地址
			$unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型

			//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
			//$unifiedOrder->setParameter("device_info","XXXX");//设备号
			//$unifiedOrder->setParameter("attach","XXXX");//附加数据
			//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
			//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
			//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
			//$unifiedOrder->setParameter("openid","XXXX");//用户标识
			//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
			//获取统一支付接口结果
			$unifiedOrderResult = $unifiedOrder->getResult();

			$this->log->write('此时即将开始支付');
			$this->log->write($amount);

			// $unifiedOrderXml = $unifiedOrder->createXml();

			// $prepay_id = $unifiedOrder->getPrepayId();	//此方法没获取到prepay_id
			$prepay_id=$unifiedOrderResult['prepay_id'];


			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();


			$data['jsApiParameters'] = $jsApiParameters;
			$data['redirect']        = $this->url->link('checkout/success');
			$data['cancel_redirect'] = $this->url->link('account/order');

			$this->model_payment_weipay->addWeiOrder($order_info, $out_trade_no);

			if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/weipay_checkout.tpl')) {
				return $this->response->setOutput($this->config->get('config_template').'/template/payment/weipay_checkout.tpl', $data);
			} else {
				return $this->response->setOutput($this->load->view('default/template/payment/weipay_checkout.tpl', $data));
			}
		}
	}

	public function qrscan() {
		$this->load->model('payment/weipay');
		// $this->model_payment_weipay->logger("Entry weipay qrscan checkout");
		//$data['action'] = $this->url->link('payment/weipay/checkout', '', 'SSL');
		$data['code_url']            = $this->session->data["code_url"];
		$data['redirect_order']      = $this->url->link('account/order');
		$data['statusquery']         = $this->url->link('payment/weipay/statusquery');
		$data['statusquery_success'] = $this->url->link('payment/weipay/success&order_id='.$this->request->get['order_id']);
		// $this->model_payment_weipay->logger("Mid weipay qrscan checkout".$data['code_url']);

		// Wenliang added for cleanup
		// $this->cart->clear();
		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['guest']);
		unset($this->session->data['comment']);
		unset($this->session->data['order_id']);
		unset($this->session->data['coupon']);
		unset($this->session->data['reward']);
		unset($this->session->data['voucher']);
		unset($this->session->data['vouchers']);

		if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/payment/weipayqrscan.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template').'/template/payment/weipayqrscan.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/weipayqrscan.tpl', $data));
		}

		// $this->model_payment_weipay->logger("Leave weipay qrscan checkout");
	}

	public function success() {
		// $order_id = $this->session->data['order_id'];
		// $order_id = $this->request->get['order_id'];
		// if (isset($this->request->post['transaction_id'])) {
		// 	$weipay_transaction_id = $this->request->post['transaction_id'];
		// } elseif (isset($this->request->get['transaction_id'])) {
		// 	$weipay_transaction_id = $this->request->get['transaction_id'];
		// } else {
		// 	$weipay_transaction_id = $this->session->data['out_trade_no'];
		// }

		// $this->load->model('checkout/order');

		// $order_info = $this->model_checkout_order->getOrder($order_id);

		// if ($order_info) {
		// 	$this->load->model('payment/weipay');
		// 	$weipay_order_info = $this->model_payment_weipay->getWeiOrder($order_id);

		// 	//$this->model_payment_weipay->updateOrder($weipay_order_info['weipay_order_id'], $weipay_transaction_id, 'payment', $order_info);

		// 	$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('weipay_order_status_id'));
		// }

		$this->response->redirect($this->url->link('checkout/success'));
	}

	public function notify() {
		$this->load->model('payment/weipay');
		$this->model_payment_weipay->logger("paid 0.01 CNY");
		$GLOBALS["HTTP_RAW_POST_DATA"] = file_get_contents("php://input", "r");
		$xml                           = $GLOBALS['HTTP_RAW_POST_DATA'];
		$this->model_payment_weipay->logger($xml);
		$retrunstring = $this->model_payment_weipay->checknotifyxml($xml, $this->config->get('weipay_key'));
		echo $returnstring;
		$this->model_payment_weipay->logger(var_export($this->request,true));
		// $this->response->redirect($this->url->link('checkout/success'));
	}

	public function test() {
		$this->load->model('payment/weipay');
		// $this->model_payment_weipay->logger("entry test");

		$xml = <<<xml
<xml><appid><![CDATA[wx04737d72f6f17e4a]]></appid>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[N]]></is_subscribe>
<mch_id><![CDATA[1274296701]]></mch_id>
<nonce_str><![CDATA[17g45vqjwbsae2ak1gitkgy7weel6sfr]]></nonce_str>
<openid><![CDATA[oAAAkuCwAj-boq6-8z4rCiW2JyH4]]></openid>
<out_trade_no><![CDATA[wx04737d72f6f17e4a146945046535]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[CC32BDE3674B53D5D3F44217EDB9FB7B]]></sign>
<time_end><![CDATA[20160725204721]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[NATIVE]]></trade_type>
<transaction_id><![CDATA[4001432001201607259770034187]]></transaction_id>
</xml>
xml

		;

		// $this->model_payment_weipay->logger($xml);
		$this->model_payment_weipay->checknotifyxml($xml, $this->config->get('weipay_key'));

	}

	public function statusquery() {
		$this->load->model('payment/weipay');
		// $this->model_payment_weipay->logger(date('Y-m-d G:i:s')." status_query:".$this->session->data['out_trade_no']);
		$this->response->addHeader('HTTP/1.1 200 OK');
		$this->response->addHeader('Content-Type: application/json');
		if ($this->model_payment_weipay->getpaymentstatus($this->session->data['out_trade_no'])) {
			$json['payment_status'] = "1";
		} else {
			$json['payment_status'] = "0";
		}
		$this->response->setOutput(json_encode($json));
	}

}
?>