<?php
class ModelPaymentWeiPay extends Model {

	protected $values = array();

	public function getMethod($address, $total) {
		$this->load->language('payment/weipay');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('weipay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('weipay_total') > 0 && $this->config->get('weipay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('weipay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		// Wenliang added, don't allow weipay for VIP user
		$this->load->model('account/customer_group');
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());
		if ($customer_group_info['name'] == GROUP_NAME_VIP_USER) {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code' => 'weipay',
				'title' => $this->language->get('text_title'),
				'terms' => '',
				'sort_order' => $this->config->get('weipay_sort_order')
				);
		}

		return $method_data;
	}

	public function addWeiOrder($order_info,$weipay_out_trade_no) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "weipay_order` SET `order_id` = '" . (int)$order_info['order_id'] . "', `weipay_out_trade_no` = '".$weipay_out_trade_no . "', `date_added` = now(), `modified` = now(), `currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', `total` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");
	}

	public function updateOrder($weipay_out_trade_no, $weipay_transaction_id, $payment_status,$amount) {
		$this->db->query("UPDATE `" . DB_PREFIX . "weipay_order` SET `weipay_transaction_id` = '" . $this->db->escape($weipay_transaction_id) . "', `payment_status` = '".$payment_status . "', `modified` = now() WHERE `weipay_out_trade_no` = '" . $weipay_out_trade_no . "'");

		$this->addTransaction($weipay_transaction_id, "payment", $weipay_out_trade_no,$amount);

	}

	public function addTransaction($weipay_transaction_id, $type, $weipay_out_trade_no,$amount) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "weipay_order_transaction` SET `weipay_transaction_id` = '" . $weipay_transaction_id . "', `weipay_out_trade_no` = '".$weipay_out_trade_no. "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . number_format($amount,2,'.','') . "'");
	}

	public function getWeiOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weipay_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			return $qry->row;
		} else {
			return false;
		}
	}

	public function updateOrderstatus($weipay_out_trade_no, $payment_status) {
		$this->load->model('checkout/order');
		$qry = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "weipay_order` WHERE `weipay_out_trade_no` = '" . $weipay_out_trade_no . "' LIMIT 1");

		if ($qry->num_rows) {
			//$qryorder = $this->db->query("UPDATE `" . DB_PREFIX . "order` SET 'order_status_id` = '" .(int)$payment_status . "'WHERE `order_id` = '" .$qry->row['order_id']);
			$qryorder = $this->db->query("SELECT order_status_id FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$qry->row['order_id']. "' LIMIT 1");
			if ($qryorder->num_rows && ($qryorder->row['order_status_id'] != $payment_status)) {
				$this->model_checkout_order->addOrderHistory($qry->row['order_id'], $payment_status);
			}
		} else {
			return false;
		}

	}

	public function getpaymentstatus($weipay_out_trade_no) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weipay_order` WHERE `weipay_out_trade_no` = '" . $this->db->escape($weipay_out_trade_no) . "' LIMIT 1");

		if ($qry->num_rows) {
			if($qry->row['payment_status'] == "paid") {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

	public function getorderstatus($order_id){
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weipay_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
		if ($qry->num_rows) {
			if($qry->row['payment_status'] == "paid") {
				$this->log->write('已经支付过了');
				return true;
			} else {
				$this->log->write('还没支付过');
				return false;
			}
		} else {
			return false;
		}
	}

/*	public function sendCurl($url, $fields) {
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
		$response = curl_exec($curl);

		curl_close($curl);

		return json_decode($response);
	}
*/

	public function checknotifyxml($xml,$key) {
		if(!$xml){
			$this->logger("No xml content");
		}
		libxml_disable_entity_loader(true);
		$this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

		if ($this->values['return_code'] == "SUCCESS") {
			$selfsign = $this->MakeSign($key);
			if ($this->values['sign'] == $selfsign) {


				$this->updateOrder($this->values['out_trade_no'], $this->values['transaction_id'], 'paid', $this->values['total_fee']);


				/**
				 * 此方法会被调用多次，需要校验订单状态，保证订单状态只会被修改一次
				 */

				// if (!isset($this->session->data['updateOrderstatus_flag'])) {



				$this->load->model('checkout/order');
				$qry = $this->db->query("SELECT order_id FROM `".DB_PREFIX."weipay_order` WHERE `weipay_out_trade_no` = '".$this->values['out_trade_no']."' LIMIT 1");
				$this->load->model('account/order');
				$shipping_code = $this->model_account_order->getOrderShippingcode($qry->row['order_id']);
				$order_status_id=$this->model_account_order->getOrderStatusIdByOrderId($qry->row['order_id']);

				$this->log->write('看看订单状态吧');
				$this->log->write($order_status_id);

					/**
					 * 只有订单状态为待付款状态才能去走下面的逻辑 order_status_id==2
					 */
					if ($order_status_id==2) {

						if ($shipping_code['shipping_code'] == "pickup.pickup") {
						/**
			             * 用户选择到店自提，并且付款成功之后，生成token_code，用于用户自提时候的校验
			             */
						$token_code=md5(mt_rand());

						$this->log->write('此处被调用了');
						$this->load->model('app/pickup_order');

						$this->log->write("看看这些参数");
						$this->log->write($qry->row['order_id']);
						$this->log->write($token_code);

						$this->model_app_pickup_order->addTokenCode(array(
							"order_id"=>$qry->row['order_id'],
							"token_code"=>$token_code
							));

						$this->updateOrderstatus($this->values['out_trade_no'], 17);
						} else {
							$this->updateOrderstatus($this->values['out_trade_no'], $this->config->get('weipay_order_status_id'));
						}
					}
				// end


				$this->logger("out_trade_no:".$this->values['out_trade_no']."\r\ntransaction_id:".$this->values['transaction_id']);
				$this->logger("sign checked:\r\n".$this->values['sign']."\r\n".$selfsign);
				$this->values['return_code'] = "SUCCESS";
				$this->values['return_msg']  = "OK";
				$this->values['sign']        = $this->MakeSign($key);
				return $this->ToXml();
			} else {
				$this->logger("sign checked not pass:\r\n".$this->values['sign']."\r\n".$selfsign);
			}
		}
	}

	public function MakeSign($key)
	{
		//签名步骤一：按字典序排序参数
		ksort($this->values);
		$string = $this->ToUrlParams();
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".$key;
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}

	/**
	 * 格式化参数格式化成url参数
	 */
	public function ToUrlParams()
	{
		$buff = "";
		foreach ($this->values as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}

		$buff = trim($buff, "&");
		return $buff;
	}

	/**
	 * 输出xml字符
	 * @throws WxPayException
	**/
	public function ToXml()
	{
		if(!is_array($this->values)
			|| count($this->values) <= 0)
		{
			throw new WxPayException("数组数据异常！");
		}

		$xml = "<xml>";
		foreach ($this->values as $key=>$val)
		{
			if (is_numeric($val)){
				$xml.="<".$key.">".$val."</".$key.">";
			}else{
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$xml.="</xml>";
		$this->logger($xml);
		return $xml;
	}

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
    	if(!$xml){
    		throw new WxPayException("xml数据异常！");
    	}
        //将XML转为array
        //禁止引用外部xml实体
    	libxml_disable_entity_loader(true);
    	$this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    	return $this->values;
    }

    public function logger($message) {
    	if ($this->config->get('weipay_debug') == 1) {
    		$log = new Log('weipay.log');
    		$backtrace = debug_backtrace();
    		$log->write('Origin: ' . $backtrace[0]['file']);
    		$log->write($backtrace[1]['class'] . '::' . $backtrace[1]['function']  . '::' . $backtrace[0]['line']);
    		$log->write(print_r($message, 1));
    	}
    }
}
