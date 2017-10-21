<?php
class ModelPaymentAlipayDirect extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/alipay_direct');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('pp_standard_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('alipay_direct_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('alipay_direct_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$currencies = array(
			'CNY',
		);

		if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
			$status = false;
		}

		// Wenliang added, don't allow alipay for VIP user
		$this->load->model('account/customer_group');
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());		
		if ($customer_group_info['name'] == GROUP_NAME_VIP_USER) {
			$status = false;		    	
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'alipay_direct',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('alipay_direct_sort_order')
			);
		}

		return $method_data;
	}
}