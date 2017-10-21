<?php
class ModelTotalShipping extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {

// var_dump($this->cart->hasShipping());exit();
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {

			// Wenliang added, for exempting shipping price
			// if ($total >= NO_DELIVERY_FEE_THRESHOLD) {
   //              $this->session->data['shipping_method']['cost'] = 0;
			// }

			$total_data[] = array(
				'code'       => 'shipping',
				'title'      => $this->session->data['shipping_method']['title'],
				'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => $this->config->get('shipping_sort_order')
			);

			if ($this->session->data['shipping_method']['tax_class_id']) {
				$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total += $this->session->data['shipping_method']['cost'];
		}
	}
}