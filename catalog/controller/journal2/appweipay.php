<?php
require_once (DIR_WXHELPER);
require_once (DIR_WXCONFIG);

class ControllerJournal2Appweipay extends Controller
{
	private $json_data=array();
	private $json=array();

	public function checkout() {
		$this->json_data = json_decode(file_get_contents("php://input"), true);
		$this->session->data['order_id']=$this->json_data['order_id'];
		$this->load->model('checkout/order');
		$this->load->model('account/order');
		$this->load->model('payment/weipay');
		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 2);
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->load->model('extension/extension');
		$results    = $this->model_extension_extension->getExtensions('total');
		$order_data = array();
		$total      = 0;
		$items      = array();
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

		$currency_code = 'CNY';
		$item_name     = $this->config->get('config_title');

		$total = $order_info['total'];

		$currency_value = $this->currency->getValue($currency_code);
		$amount         = $total*$currency_value;
		$amount         = number_format($amount, 2, '.', '');
		$amount         = $amount*100;//单位换算为分
		if ($this->config->get('weipay_environment') == 0) {
			$amount = 1;
		}

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
			$unifiedOrder->setParameter("body", $order_productname);//商品描述

			$timeStamp                           = time();
			// 小程序支付需要的参数之一
			$this->session->data['timeStamp']=$timeStamp;
			$order_id                            = $this->session->data['order_id'];
			$out_trade_no                        = $this->config->get('weipay_appid')."$timeStamp" ."$order_id";

			// 服务商的子商户参数
			if (isset(WxPayConf_pub::$sub_mch_id)&&isset(WxPayConf_pub::$sub_appid)) {
				$unifiedOrder->setParameter("sub_mch_id",WxPayConf_pub::$sub_mch_id);
				$unifiedOrder->setParameter("sub_appid",WxPayConf_pub::$sub_appid);
			}

			$this->session->data["out_trade_no"] = $out_trade_no;
			$unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
			$unifiedOrder->setParameter("total_fee", "$amount");//总金额
			$unifiedOrder->setParameter("notify_url", HTTP_SERVER."payment/weipay/notify");//通知地址
			$unifiedOrder->setParameter("trade_type", "NATIVE");//交易类型

			$unifiedOrderResult = $unifiedOrder->getResult();

			// $this->session->data['unifiedOrder']=$unifiedOrder;
			// $this->session->data['unifiedOrderResult']=$unifiedOrderResult;

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
			}
			$this->session->data["code_url"] = $code_url;

			$this->model_payment_weipay->addWeiOrder($order_info, $out_trade_no);
			$this->getQrcode($this->session->data['out_trade_no']);
			// $this->qrscan($this->session->data['order_id']);

	}

	public function getQrcode($out_trade_no){

		include DIR_SYSTEM."pub/phpqrcode/phpqrcode.php";//引入PHP QR库文件

		$value=$this->session->data['code_url'];
		$errorCorrectionLevel = "L";
		$matrixPointSize = "9";
		$margin='2';
		$outfile=DIR_SYSTEM.'weipayqrcode/'.$out_trade_no.'.png';
		// $outfile=DIR_CACHE.$out_trade_no.'.png';
		$saveandprint=true;
		// $stamp=
		if (!file_exists($outfile)) {
			QRcode::png($value, $outfile, $errorCorrectionLevel, $matrixPointSize,true);
			chmod($outfile,0777);
			$this->json['qrcode']=HTTP_SERVER.'system/weipayqrcode/'.$out_trade_no.'.png';
			$this->json['qrcode_wx']=HTTPS_SERVER.'system/weipayqrcode/'.$out_trade_no.'.png';
			// $this->json['qrcode']=HTTP_SERVER.'system/cache/'.$out_trade_no.'.png';
			$this->json['out_trade_no']=$out_trade_no;
			$this->session->data['out_trade_no']=$out_trade_no;

			// 为微信小程序准备的参数
			// $unifiedOrder=$this->session->data['unifiedOrder'];
			// $this->json['unifiedOrder']=$this->session->data['unifiedOrder'];
			// $this->json['timeStamp']=$this->session->data['timeStamp'];
			// $this->json['unifiedOrderResult']=$this->session->data['unifiedOrderResult'];

			$this->response->addHeader('Content-Type:application/json');
        	$this->response->setOutput(json_encode($this->json));
		}else{
			return false;
		}
	}

	public function statusquery() {
		$this->load->model('payment/weipay');
		$this->response->addHeader('HTTP/1.1 200 OK');
		$this->response->addHeader('Content-Type: application/json');
		if ($this->model_payment_weipay->getpaymentstatus($this->session->data['out_trade_no'])) {
			$json['payment_status'] = "1";
		} else {
			$json['payment_status'] = "0";
		}
		$this->response->setOutput(json_encode($json));
	}

	public function notify() {
		$this->load->model('payment/weipay');
		$GLOBALS["HTTP_RAW_POST_DATA"] = file_get_contents("php://input", "r");
		$xml                           = $GLOBALS['HTTP_RAW_POST_DATA'];
		$retrunstring = $this->model_payment_weipay->checknotifyxml($xml, $this->config->get('weipay_key'));
		echo $returnstring;
	}

	// 提供给app scan支付状态的入口
	public function success(){
        $this->json_data = json_decode(file_get_contents("php://input"), true);
        $this->load->model('payment/weipay');
		// $this->response->addHeader('HTTP/1.1 200 OK');
		// if (isset($this->json_data['shipping_method'])) {
		// 	$this->session->data['shipping_method']=$this->json_data['shipping_method'];
		// }

		if ($this->model_payment_weipay->getpaymentstatus($this->json_data['out_trade_no'])) {
			// 到店自提支付成功才开始扣减实体店的物理库存
			$this->load->model('catalog/repository');
			foreach ($this->cart->getProducts() as $product) {
				 if (isset($this->session->data['shipping_method'])&&$this->session->data['shipping_method']=='pickup.pickup') {
				 	 foreach ($product['option'] as $option) {
                        $product['product_option_value_id']=$option['product_option_value_id'];
                    }
				    $filter_repository_data=array(
				        'product_option_value_id'=>$product['product_option_value_id'],
				        'product_id'=> $product['product_id'],
				        'repository_id'=>$this->session->data['repository_id'],
				        'quantity'=>$product['quantity'],
				        'user_id'=>$this->json_data['user_id']
				        );
				    if ($this->model_catalog_repository->delRepository_pd_order($filter_repository_data)) {
				        $this->model_catalog_repository->addOrderProductRepository($filter_repository_data);
				        $this->json['repository_del']=true;
				    }else{
				        $this->json['repository_del']=false;
				    }
				}
			}
			$this->json['success'] = 1;

			// 删除二维码
			$qrcode_file=DIR_SYSTEM.'weipayqrcode/'.$this->json_data['out_trade_no'].'.png';

			if (unlink($qrcode_file)) {
				$this->log->write('嘿嘿,删除成功');
			}else{
				$this->log->write('2222，删除没成功');
			}

			$this->cart->clear();
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
		} else {

			// 注意：这里不是支付失败，是支付还未成功
			$this->json['success'] = 0;

			// // 支付失败也要删除二维码
			// $qrcode_file=DIR_SYSTEM.'weipayqrcode/'.$this->json_data['out_trade_no'].'.png';
			// if (unlink($qrcode_file)) {
			// 	$this->log->write('嘿嘿,删除成功');
			// }else{
			// 	$this->log->write('2222，删除没成功');
			// }
			// $this->cart->clear();
			// unset($this->session->data['shipping_method']);
			// unset($this->session->data['shipping_methods']);
			// unset($this->session->data['payment_method']);
			// unset($this->session->data['payment_methods']);
			// unset($this->session->data['guest']);
			// unset($this->session->data['comment']);
			// unset($this->session->data['order_id']);
			// unset($this->session->data['coupon']);
			// unset($this->session->data['reward']);
			// unset($this->session->data['voucher']);
			// unset($this->session->data['vouchers']);
		}
        $this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));
    }
}

