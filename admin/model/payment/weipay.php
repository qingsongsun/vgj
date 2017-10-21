<?php

class ModelPaymentWeiPay extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "weipay_order` (
				`weipay_order_id` INT(11) NOT NULL AUTO_INCREMENT,
				`order_id` int(11) NOT NULL,
				`weipay_transaction_id` varchar(255) NOT NULL,
				`weipay_out_trade_no` varchar(64) NOT NULL,
				`date_added` DATETIME NOT NULL,
				`modified` DATETIME NOT NULL,
				`payment_status` ENUM('wait for payment', 'paid') DEFAULT NULL,
				`refund_status` INT(1) DEFAULT NULL,
				`currency_code` CHAR(3) NOT NULL,
				`total` DECIMAL( 10, 2 ) NOT NULL,
				KEY `weipay_transaction_id` (`weipay_transaction_id`),
				PRIMARY KEY `weipay_order_id` (`weipay_order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "weipay_order_transaction` (
			  `weipay_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `weipay_transaction_id` varchar(255) NOT NULL,
			  `weipay_out_trade_no` varchar(64) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('payment', 'refund') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`weipay_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;
			");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "weipay_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "weipay_order_transaction`;");
	}

	public function getOrder($order_id) {

		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weipay_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['weipay_order_id'], $qry->row['currency_code']);
			return $order;
		} else {
			return false;
		}
	}

	public function getTotalReleased($weipay_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "weipay_order_transaction` WHERE `weipay_order_id` = '" . (int)$weipay_order_id . "' AND (`type` = 'payment' OR `type` = 'refund')");

		return (double)$query->row['total'];
	}

	public function refund($weipay_order, $amount) {
		if (!empty($weipay_order) && $weipay_order['refund_status'] != 1) {
			if ($this->config->get('weipay_environment') == 1) {
				$url = 'https://pay.g2a.com/rest/transactions/' . $weipay_order['weipay_transaction_id'];
			} else {
				$url = 'https://www.test.pay.g2a.com/rest/transactions/' . $weipay_order['weipay_transaction_id'];
			}

			$refunded_amount = round($amount, 2);

			$string = $weipay_order['weipay_transaction_id'] . $weipay_order['order_id'] . round($weipay_order['total'], 2) . $refunded_amount . html_entity_decode($this->config->get('weipay_secret'));
			$hash = hash('sha256', $string);

			$fields = array(
				'action' => 'refund',
				'amount' => $refunded_amount,
				'hash' => $hash,
			);

			return $this->sendCurl($url, $fields);
		} else {
			return false;
		}
	}

	public function updateRefundStatus($weipay_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "weipay_order` SET `refund_status` = '" . (int)$status . "' WHERE `weipay_order_id` = '" . (int)$weipay_order_id . "'");
	}

	private function getTransactions($weipay_order_id, $currency_code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weipay_order_transaction` WHERE `weipay_order_id` = '" . (int)$weipay_order_id . "'");

		$transactions = array();
		if ($query->num_rows) {
			foreach ($query->rows as $row) {
				$row['amount'] = $this->currency->format($row['amount'], $currency_code, true, true);
				$transactions[] = $row;
			}
			return $transactions;
		} else {
			return false;
		}
	}

	public function addTransaction($weipay_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "weipay_order_transaction` SET `weipay_order_id` = '" . (int)$weipay_order_id . "',`date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (double)$total . "'");
	}

	public function getTotalRefunded($weipay_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "weipay_order_transaction` WHERE `weipay_order_id` = '" . (int)$weipay_order_id . "' AND 'refund'");

		return (double)$query->row['total'];
	}

	public function sendCurl($url, $fields) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

		$auth_hash = hash('sha256', $this->config->get('weipay_api_hash') . $this->config->get('weipay_username') . html_entity_decode($this->config->get('weipay_secret')));
		$authorization = $this->config->get('weipay_api_hash') . ";" . $auth_hash;
		curl_setopt(
				$curl, CURLOPT_HTTPHEADER, array(
			"Authorization: " . $authorization
				)
		);

		$response = json_decode(curl_exec($curl));

		curl_close($curl);
		if (is_object($response)) {
			return (string)$response->status;
		} else {
			return str_replace('"', "", $response);
		}
	}

	public function logger($message) {
		if ($this->config->get('weipay_debug') == 1) {
			$log = new Log('weipay.log');
			$backtrace = debug_backtrace();
			$log->write('Origin: ' . $backtrace[1]['class'] . '::' . $backtrace[1]['function']);
			$log->write(print_r($message, 1));
		}
	}

}
