<?php
class ModelShippingPickup extends Model {
	function getQuote($address) {
		$this->load->language('shipping/pickup');

		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."zone_to_geo_zone WHERE geo_zone_id = '".(int) $this->config->get('pickup_geo_zone_id')."' AND country_id = '".(int) $address['country_id']."' AND (zone_id = '".(int) $address['zone_id']."' OR zone_id = '0')");

		/**
		 * HuWen added, for updating the shipping method if VIP agent need pick up at store
		 */
		/**
		 * Huwen removed the logic.
		 */
		// if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
		// 	$customer_group_id = $data['customer_group_id'];
		// } else {
		// 	$customer_group_id = $this->config->get('config_customer_group_id');
		// }

		// if (!isset($this->session->data['switched_user'])) {
		// 	return;
		// }

		// if ($customer_group_id != GROUP_NAME_VIP_USER_ID) {
		// 	return;
		// }

		if (!$this->config->get('pickup_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		// $status = true;

		$method_data = array();

// huwen added for pickup 
		$repository_query=$this->db->query("SELECT * FROM " . DB_PREFIX . "repository WHERE 1");
		// print_r($repository_query->rows);
		foreach ($repository_query->rows as $repository) {
			// print_r($repository);
			if ($repository['repository_type_id']==1) {
				$status=true;	
				$repository_name=array();			
				$repository_name[]=$repository['repository_name'];

				foreach ($repository_name as $repository ) {
					if ($status) {
						$quote_data = array();
						$quote_data['pickup'] = array(
							'code'         => 'pickup.pickup',
							'title'        => $this->language->get('text_description'),
							// 'title'=>$repository,
							'cost'         => 0.00,
							'tax_class_id' => 0,
							'text'         => $this->currency->format(0.00)
						);

						$method_data = array(
							'code'       => 'pickup',
							'title'      => $this->language->get('text_title'),
							'quote'      => $quote_data,
							'sort_order' => $this->config->get('pickup_sort_order'),
							'error'      => false
						);											
					}
				}
		    }
		}
		// end
		
		return $method_data;
	}

}