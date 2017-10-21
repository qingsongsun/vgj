<?php
class ControllerApiOrder extends Controller {
	public function add() {
		$this->load->language('api/order');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Customer
			if (!isset($this->session->data['customer'])) {
				$json['error'] = $this->language->get('error_customer');
			}

			// Payment Address
			if (!isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_payment_address');
			}

			// Payment Method
			if (!isset($this->session->data['payment_method'])) {
				$json['error'] = $this->language->get('error_payment_method');
			}

			// Shipping
			if ($this->cart->hasShipping()) {
				// Shipping Address
				if (!isset($this->session->data['shipping_address'])) {
					$json['error'] = $this->language->get('error_shipping_address');
				}

				// Shipping Method
				if (!isset($this->request->post['shipping_method'])) {
					$json['error'] = $this->language->get('error_shipping_method');
				}
			} else {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Cart
			if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
				$json['error'] = $this->language->get('error_stock');
			}

			// Validate minimum quantity requirements.
			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

					break;
				}
			}

			if (!$json) {
				$order_data = array();

				// Store Details
				$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
				$order_data['store_id'] = $this->config->get('config_store_id');
				$order_data['store_name'] = $this->config->get('config_name');
				$order_data['store_url'] = $this->config->get('config_url');

				// Customer Details
				$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
				$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['customer']['firstname'];
				$order_data['lastname'] = $this->session->data['customer']['lastname'];
				$order_data['email'] = $this->session->data['customer']['email'];
				$order_data['telephone'] = $this->session->data['customer']['telephone'];
				$order_data['fax'] = $this->session->data['customer']['fax'];
				$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

				// Payment Details
				$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
				$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
				$order_data['payment_company'] = $this->session->data['payment_address']['company'];
				$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
				$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
				$order_data['payment_city'] = $this->session->data['payment_address']['city'];
				$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
				$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
				$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
				$order_data['payment_country'] = $this->session->data['payment_address']['country'];
				$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
				$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
				$order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

				if (isset($this->session->data['payment_method']['title'])) {
					$order_data['payment_method'] = $this->session->data['payment_method']['title'];
				} else {
					$order_data['payment_method'] = '';
				}

				if (isset($this->session->data['payment_method']['code'])) {
					$order_data['payment_code'] = $this->session->data['payment_method']['code'];
				} else {
					$order_data['payment_code'] = '';
				}

				// Shipping Details
				if ($this->cart->hasShipping()) {
					$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
					$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
					$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
					$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
					$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
					$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
					$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
					$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
					$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
					$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
					$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
					$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
					$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

					if (isset($this->session->data['shipping_method']['title'])) {
						$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
					} else {
						$order_data['shipping_method'] = '';
					}

					if (isset($this->session->data['shipping_method']['code'])) {
						$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
					} else {
						$order_data['shipping_code'] = '';
					}
				} else {
					$order_data['shipping_firstname'] = '';
					$order_data['shipping_lastname'] = '';
					$order_data['shipping_company'] = '';
					$order_data['shipping_address_1'] = '';
					$order_data['shipping_address_2'] = '';
					$order_data['shipping_city'] = '';
					$order_data['shipping_postcode'] = '';
					$order_data['shipping_zone'] = '';
					$order_data['shipping_zone_id'] = '';
					$order_data['shipping_country'] = '';
					$order_data['shipping_country_id'] = '';
					$order_data['shipping_address_format'] = '';
					$order_data['shipping_custom_field'] = array();
					$order_data['shipping_method'] = '';
					$order_data['shipping_code'] = '';
				}

				// Products
				$order_data['products'] = array();

				foreach ($this->cart->getProducts() as $product) {
					$option_data = array();

					foreach ($product['option'] as $option) {
						$option_data[] = array(
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'option_id'               => $option['option_id'],
							'option_value_id'         => $option['option_value_id'],
							'name'                    => $option['name'],
							'value'                   => $option['value'],
							'type'                    => $option['type']
						);
					}

					$order_data['products'][] = array(
						'product_id' => $product['product_id'],
						'name'       => $product['name'],
						'model'      => $product['model'],
						'option'     => $option_data,
						'download'   => $product['download'],
						'quantity'   => $product['quantity'],
						'subtract'   => $product['subtract'],
						'price'      => $product['price'],
						'total'      => $product['total'],
						'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
						'reward'     => $product['reward']
					);
				}

				// Gift Voucher
				$order_data['vouchers'] = array();

				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $voucher) {
						$order_data['vouchers'][] = array(
							'description'      => $voucher['description'],
							'code'             => substr(md5(mt_rand()), 0, 10),
							'to_name'          => $voucher['to_name'],
							'to_email'         => $voucher['to_email'],
							'from_name'        => $voucher['from_name'],
							'from_email'       => $voucher['from_email'],
							'voucher_theme_id' => $voucher['voucher_theme_id'],
							'message'          => $voucher['message'],
							'amount'           => $voucher['amount']
						);
					}
				}

				// Order Totals
				$this->load->model('extension/extension');

				$order_data['totals'] = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();

				$sort_order = array();

				$results = $this->model_extension_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);

						$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
					}
				}

				$sort_order = array();

				foreach ($order_data['totals'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $order_data['totals']);

				if (isset($this->request->post['comment'])) {
					$order_data['comment'] = $this->request->post['comment'];
				} else {
					$order_data['comment'] = '';
				}

				$order_data['total'] = $total;

				if (isset($this->request->post['affiliate_id'])) {
					$subtotal = $this->cart->getSubTotal();

					// Affiliate
					$this->load->model('affiliate/affiliate');

					$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

					if ($affiliate_info) {
						$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
						$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
					} else {
						$order_data['affiliate_id'] = 0;
						$order_data['commission'] = 0;
					}

					// Marketing
					$order_data['marketing_id'] = 0;
					$order_data['tracking'] = '';
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
					$order_data['marketing_id'] = 0;
					$order_data['tracking'] = '';
				}

				$order_data['language_id'] = $this->config->get('config_language_id');
				$order_data['currency_id'] = $this->currency->getId();
				$order_data['currency_code'] = $this->currency->getCode();
				$order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
				$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

				if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
					$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
				} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
					$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
				} else {
					$order_data['forwarded_ip'] = '';
				}

				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
				} else {
					$order_data['user_agent'] = '';
				}

				if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
					$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
				} else {
					$order_data['accept_language'] = '';
				}

				$this->load->model('checkout/order');

				$json['order_id'] = $this->model_checkout_order->addOrder($order_data);

				// Set the order history
				if (isset($this->request->post['order_status_id'])) {
					$order_status_id = $this->request->post['order_status_id'];
				} else {
					$order_status_id = $this->config->get('config_order_status_id');
				}

				$this->model_checkout_order->addOrderHistory($json['order_id'], $order_status_id);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$this->load->language('api/order');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/order');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info) {
				// Customer
				if (!isset($this->session->data['customer'])) {
					$json['error'] = $this->language->get('error_customer');
				}

				// Payment Address
				if (!isset($this->session->data['payment_address'])) {
					$json['error'] = $this->language->get('error_payment_address');
				}

				// Payment Method
				if (!isset($this->session->data['payment_method'])) {
					$json['error'] = $this->language->get('error_payment_method');
				}

				// Shipping
				if ($this->cart->hasShipping()) {
					// Shipping Address
					if (!isset($this->session->data['shipping_address'])) {
						$json['error'] = $this->language->get('error_shipping_address');
					}

					// Shipping Method
					if (!isset($this->request->post['shipping_method'])) {
						$json['error'] = $this->language->get('error_shipping_method');
					}
				} else {
					unset($this->session->data['shipping_address']);
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}

				// Cart
				if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
					$json['error'] = $this->language->get('error_stock');
				}

				// Validate minimum quantity requirements.
				$products = $this->cart->getProducts();

				foreach ($products as $product) {
					$product_total = 0;

					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}

					if ($product['minimum'] > $product_total) {
						$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

						break;
					}
				}

				if (!$json) {
					$order_data = array();

					// Store Details
					$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
					$order_data['store_id'] = $this->config->get('config_store_id');
					$order_data['store_name'] = $this->config->get('config_name');
					$order_data['store_url'] = $this->config->get('config_url');

					// Customer Details
					$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
					$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
					$order_data['firstname'] = $this->session->data['customer']['firstname'];
					$order_data['lastname'] = $this->session->data['customer']['lastname'];
					$order_data['email'] = $this->session->data['customer']['email'];
					$order_data['telephone'] = $this->session->data['customer']['telephone'];
					$order_data['fax'] = $this->session->data['customer']['fax'];
					$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

					// Payment Details
					$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
					$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
					$order_data['payment_company'] = $this->session->data['payment_address']['company'];
					$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
					$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
					$order_data['payment_city'] = $this->session->data['payment_address']['city'];
					$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
					$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
					$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
					$order_data['payment_country'] = $this->session->data['payment_address']['country'];
					$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
					$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
					$order_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];

					if (isset($this->session->data['payment_method']['title'])) {
						$order_data['payment_method'] = $this->session->data['payment_method']['title'];
					} else {
						$order_data['payment_method'] = '';
					}

					if (isset($this->session->data['payment_method']['code'])) {
						$order_data['payment_code'] = $this->session->data['payment_method']['code'];
					} else {
						$order_data['payment_code'] = '';
					}

					// Shipping Details
					if ($this->cart->hasShipping()) {
						$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
						$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
						$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
						$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
						$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
						$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
						$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
						$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
						$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
						$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
						$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
						$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
						$order_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'];

						if (isset($this->session->data['shipping_method']['title'])) {
							$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
						} else {
							$order_data['shipping_method'] = '';
						}

						if (isset($this->session->data['shipping_method']['code'])) {
							$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
						} else {
							$order_data['shipping_code'] = '';
						}
					} else {
						$order_data['shipping_firstname'] = '';
						$order_data['shipping_lastname'] = '';
						$order_data['shipping_company'] = '';
						$order_data['shipping_address_1'] = '';
						$order_data['shipping_address_2'] = '';
						$order_data['shipping_city'] = '';
						$order_data['shipping_postcode'] = '';
						$order_data['shipping_zone'] = '';
						$order_data['shipping_zone_id'] = '';
						$order_data['shipping_country'] = '';
						$order_data['shipping_country_id'] = '';
						$order_data['shipping_address_format'] = '';
						$order_data['shipping_custom_field'] = array();
						$order_data['shipping_method'] = '';
						$order_data['shipping_code'] = '';
					}

					// Products
					$order_data['products'] = array();

					foreach ($this->cart->getProducts() as $product) {
						$option_data = array();

						foreach ($product['option'] as $option) {
							$option_data[] = array(
								'product_option_id'       => $option['product_option_id'],
								'product_option_value_id' => $option['product_option_value_id'],
								'option_id'               => $option['option_id'],
								'option_value_id'         => $option['option_value_id'],
								'name'                    => $option['name'],
								'value'                   => $option['value'],
								'type'                    => $option['type']
							);
						}

						$order_data['products'][] = array(
							'product_id' => $product['product_id'],
							'name'       => $product['name'],
							'model'      => $product['model'],
							'option'     => $option_data,
							'download'   => $product['download'],
							'quantity'   => $product['quantity'],
							'subtract'   => $product['subtract'],
							'price'      => $product['price'],
							'total'      => $product['total'],
							'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
							'reward'     => $product['reward']
						);
					}

					// Gift Voucher
					$order_data['vouchers'] = array();

					if (!empty($this->session->data['vouchers'])) {
						foreach ($this->session->data['vouchers'] as $voucher) {
							$order_data['vouchers'][] = array(
								'description'      => $voucher['description'],
								'code'             => substr(md5(mt_rand()), 0, 10),
								'to_name'          => $voucher['to_name'],
								'to_email'         => $voucher['to_email'],
								'from_name'        => $voucher['from_name'],
								'from_email'       => $voucher['from_email'],
								'voucher_theme_id' => $voucher['voucher_theme_id'],
								'message'          => $voucher['message'],
								'amount'           => $voucher['amount']
							);
						}
					}

					// Order Totals
					$this->load->model('extension/extension');

					$order_data['totals'] = array();
					$total = 0;
					$taxes = $this->cart->getTaxes();

					$sort_order = array();

					$results = $this->model_extension_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);

							$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
						}
					}

					$sort_order = array();

					foreach ($order_data['totals'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $order_data['totals']);

					if (isset($this->request->post['comment'])) {
						$order_data['comment'] = $this->request->post['comment'];
					} else {
						$order_data['comment'] = '';
					}

					$order_data['total'] = $total;

					if (isset($this->request->post['affiliate_id'])) {
						$subtotal = $this->cart->getSubTotal();

						// Affiliate
						$this->load->model('affiliate/affiliate');

						$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

						if ($affiliate_info) {
							$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
							$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
						} else {
							$order_data['affiliate_id'] = 0;
							$order_data['commission'] = 0;
						}
					} else {
						$order_data['affiliate_id'] = 0;
						$order_data['commission'] = 0;
					}

					$this->model_checkout_order->editOrder($order_id, $order_data);

					// Set the order history
					if (isset($this->request->post['order_status_id'])) {
						$order_status_id = $this->request->post['order_status_id'];
					} else {
						$order_status_id = $this->config->get('config_order_status_id');
					}

					$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);

					$json['success'] = $this->language->get('text_success');
				}
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('api/order');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/order');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info) {
				$this->model_checkout_order->deleteOrder($order_id);

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	//ishanks added this array and function zh_to_py
	private $pylist = array(
        'a'=>-20319,
        'ai'=>-20317,
        'an'=>-20304,
        'ang'=>-20295,
        'ao'=>-20292,
        'ba'=>-20283,
        'bai'=>-20265,
        'ban'=>-20257,
        'bang'=>-20242,
        'bao'=>-20230,
        'bei'=>-20051,
        'ben'=>-20036,
        'beng'=>-20032,
        'bi'=>-20026,
        'bian'=>-20002,
        'biao'=>-19990,
        'bie'=>-19986,
        'bin'=>-19982,
        'bing'=>-19976,
        'bo'=>-19805,
        'bu'=>-19784,
        'ca'=>-19775,
        'cai'=>-19774,
        'can'=>-19763,
        'cang'=>-19756,
        'cao'=>-19751,
        'ce'=>-19746,
        'ceng'=>-19741,
        'cha'=>-19739,
        'chai'=>-19728,
        'chan'=>-19725,
        'chang'=>-19715,
        'chao'=>-19540,
        'che'=>-19531,
        'chen'=>-19525,
        'cheng'=>-19515,
        'chi'=>-19500,
        'chong'=>-19484,
        'chou'=>-19479,
        'chu'=>-19467,
        'chuai'=>-19289,
        'chuan'=>-19288,
        'chuang'=>-19281,
        'chui'=>-19275,
        'chun'=>-19270,
        'chuo'=>-19263,
        'ci'=>-19261,
        'cong'=>-19249,
        'cou'=>-19243,
        'cu'=>-19242,
        'cuan'=>-19238,
        'cui'=>-19235,
        'cun'=>-19227,
        'cuo'=>-19224,
        'da'=>-19218,
        'dai'=>-19212,
        'dan'=>-19038,
        'dang'=>-19023,
        'dao'=>-19018,
        'de'=>-19006,
        'deng'=>-19003,
        'di'=>-18996,
        'dian'=>-18977,
        'diao'=>-18961,
        'die'=>-18952,
        'ding'=>-18783,
        'diu'=>-18774,
        'dong'=>-18773,
        'dou'=>-18763,
        'du'=>-18756,
        'duan'=>-18741,
        'dui'=>-18735,
        'dun'=>-18731,
        'duo'=>-18722,
        'e'=>-18710,
        'en'=>-18697,
        'er'=>-18696,
        'fa'=>-18526,
        'fan'=>-18518,
        'fang'=>-18501,
        'fei'=>-18490,
        'fen'=>-18478,
        'feng'=>-18463,
        'fo'=>-18448,
        'fou'=>-18447,
        'fu'=>-18446,
        'ga'=>-18239,
        'gai'=>-18237,
        'gan'=>-18231,
        'gang'=>-18220,
        'gao'=>-18211,
        'ge'=>-18201,
        'gei'=>-18184,
        'gen'=>-18183,
        'geng'=>-18181,
        'gong'=>-18012,
        'gou'=>-17997,
        'gu'=>-17988,
        'gua'=>-17970,
        'guai'=>-17964,
        'guan'=>-17961,
        'guang'=>-17950,
        'gui'=>-17947,
        'gun'=>-17931,
        'guo'=>-17928,
        'ha'=>-17922,
        'hai'=>-17759,
        'han'=>-17752,
        'hang'=>-17733,
        'hao'=>-17730,
        'he'=>-17721,
        'hei'=>-17703,
        'hen'=>-17701,
        'heng'=>-17697,
        'hong'=>-17692,
        'hou'=>-17683,
        'hu'=>-17676,
        'hua'=>-17496,
        'huai'=>-17487,
        'huan'=>-17482,
        'huang'=>-17468,
        'hui'=>-17454,
        'hun'=>-17433,
        'huo'=>-17427,
        'ji'=>-17417,
        'jia'=>-17202,
        'jian'=>-17185,
        'jiang'=>-16983,
        'jiao'=>-16970,
        'jie'=>-16942,
        'jin'=>-16915,
        'jing'=>-16733,
        'jiong'=>-16708,
        'jiu'=>-16706,
        'ju'=>-16689,
        'juan'=>-16664,
        'jue'=>-16657,
        'jun'=>-16647,
        'ka'=>-16474,
        'kai'=>-16470,
        'kan'=>-16465,
        'kang'=>-16459,
        'kao'=>-16452,
        'ke'=>-16448,
        'ken'=>-16433,
        'keng'=>-16429,
        'kong'=>-16427,
        'kou'=>-16423,
        'ku'=>-16419,
        'kua'=>-16412,
        'kuai'=>-16407,
        'kuan'=>-16403,
        'kuang'=>-16401,
        'kui'=>-16393,
        'kun'=>-16220,
        'kuo'=>-16216,
        'la'=>-16212,
        'lai'=>-16205,
        'lan'=>-16202,
        'lang'=>-16187,
        'lao'=>-16180,
        'le'=>-16171,
        'lei'=>-16169,
        'leng'=>-16158,
        'li'=>-16155,
        'lia'=>-15959,
        'lian'=>-15958,
        'liang'=>-15944,
        'liao'=>-15933,
        'lie'=>-15920,
        'lin'=>-15915,
        'ling'=>-15903,
        'liu'=>-15889,
        'long'=>-15878,
        'lou'=>-15707,
        'lu'=>-15701,
        'lv'=>-15681,
        'luan'=>-15667,
        'lue'=>-15661,
        'lun'=>-15659,
        'luo'=>-15652,
        'ma'=>-15640,
        'mai'=>-15631,
        'man'=>-15625,
        'mang'=>-15454,
        'mao'=>-15448,
        'me'=>-15436,
        'mei'=>-15435,
        'men'=>-15419,
        'meng'=>-15416,
        'mi'=>-15408,
        'mian'=>-15394,
        'miao'=>-15385,
        'mie'=>-15377,
        'min'=>-15375,
        'ming'=>-15369,
        'miu'=>-15363,
        'mo'=>-15362,
        'mou'=>-15183,
        'mu'=>-15180,
        'na'=>-15165,
        'nai'=>-15158,
        'nan'=>-15153,
        'nang'=>-15150,
        'nao'=>-15149,
        'ne'=>-15144,
        'nei'=>-15143,
        'nen'=>-15141,
        'neng'=>-15140,
        'ni'=>-15139,
        'nian'=>-15128,
        'niang'=>-15121,
        'niao'=>-15119,
        'nie'=>-15117,
        'nin'=>-15110,
        'ning'=>-15109,
        'niu'=>-14941,
        'nong'=>-14937,
        'nu'=>-14933,
        'nv'=>-14930,
        'nuan'=>-14929,
        'nue'=>-14928,
        'nuo'=>-14926,
        'o'=>-14922,
        'ou'=>-14921,
        'pa'=>-14914,
        'pai'=>-14908,
        'pan'=>-14902,
        'pang'=>-14894,
        'pao'=>-14889,
        'pei'=>-14882,
        'pen'=>-14873,
        'peng'=>-14871,
        'pi'=>-14857,
        'pian'=>-14678,
        'piao'=>-14674,
        'pie'=>-14670,
        'pin'=>-14668,
        'ping'=>-14663,
        'po'=>-14654,
        'pu'=>-14645,
        'qi'=>-14630,
        'qia'=>-14594,
        'qian'=>-14429,
        'qiang'=>-14407,
        'qiao'=>-14399,
        'qie'=>-14384,
        'qin'=>-14379,
        'qing'=>-14368,
        'qiong'=>-14355,
        'qiu'=>-14353,
        'qu'=>-14345,
        'quan'=>-14170,
        'que'=>-14159,
        'qun'=>-14151,
        'ran'=>-14149,
        'rang'=>-14145,
        'rao'=>-14140,
        're'=>-14137,
        'ren'=>-14135,
        'reng'=>-14125,
        'ri'=>-14123,
        'rong'=>-14122,
        'rou'=>-14112,
        'ru'=>-14109,
        'ruan'=>-14099,
        'rui'=>-14097,
        'run'=>-14094,
        'ruo'=>-14092,
        'sa'=>-14090,
        'sai'=>-14087,
        'san'=>-14083,
        'sang'=>-13917,
        'sao'=>-13914,
        'se'=>-13910,
        'sen'=>-13907,
        'seng'=>-13906,
        'sha'=>-13905,
        'shai'=>-13896,
        'shan'=>-13894,
        'shang'=>-13878,
        'shao'=>-13870,
        'she'=>-13859,
        'shen'=>-13847,
        'sheng'=>-13831,
        'shi'=>-13658,
        'shou'=>-13611,
        'shu'=>-13601,
        'shua'=>-13406,
        'shuai'=>-13404,
        'shuan'=>-13400,
        'shuang'=>-13398,
        'shui'=>-13395,
        'shun'=>-13391,
        'shuo'=>-13387,
        'si'=>-13383,
        'song'=>-13367,
        'sou'=>-13359,
        'su'=>-13356,
        'suan'=>-13343,
        'sui'=>-13340,
        'sun'=>-13329,
        'suo'=>-13326,
        'ta'=>-13318,
        'tai'=>-13147,
        'tan'=>-13138,
        'tang'=>-13120,
        'tao'=>-13107,
        'te'=>-13096,
        'teng'=>-13095,
        'ti'=>-13091,
        'tian'=>-13076,
        'tiao'=>-13068,
        'tie'=>-13063,
        'ting'=>-13060,
        'tong'=>-12888,
        'tou'=>-12875,
        'tu'=>-12871,
        'tuan'=>-12860,
        'tui'=>-12858,
        'tun'=>-12852,
        'tuo'=>-12849,
        'wa'=>-12838,
        'wai'=>-12831,
        'wan'=>-12829,
        'wang'=>-12812,
        'wei'=>-12802,
        'wen'=>-12607,
        'weng'=>-12597,
        'wo'=>-12594,
        'wu'=>-12585,
        'xi'=>-12556,
        'xia'=>-12359,
        'xian'=>-12346,
        'xiang'=>-12320,
        'xiao'=>-12300,
        'xie'=>-12120,
        'xin'=>-12099,
        'xing'=>-12089,
        'xiong'=>-12074,
        'xiu'=>-12067,
        'xu'=>-12058,
        'xuan'=>-12039,
        'xue'=>-11867,
        'xun'=>-11861,
        'ya'=>-11847,
        'yan'=>-11831,
        'yang'=>-11798,
        'yao'=>-11781,
        'ye'=>-11604,
        'yi'=>-11589,
        'yin'=>-11536,
        'ying'=>-11358,
        'yo'=>-11340,
        'yong'=>-11339,
        'you'=>-11324,
        'yu'=>-11303,
        'yuan'=>-11097,
        'yue'=>-11077,
        'yun'=>-11067,
        'za'=>-11055,
        'zai'=>-11052,
        'zan'=>-11045,
        'zang'=>-11041,
        'zao'=>-11038,
        'ze'=>-11024,
        'zei'=>-11020,
        'zen'=>-11019,
        'zeng'=>-11018,
        'zha'=>-11014,
        'zhai'=>-10838,
        'zhan'=>-10832,
        'zhang'=>-10815,
        'zhao'=>-10800,
        'zhe'=>-10790,
        'zhen'=>-10780,
        'zheng'=>-10764,
        'zhi'=>-10587,
        'zhong'=>-10544,
        'zhou'=>-10533,
        'zhu'=>-10519,
        'zhua'=>-10331,
        'zhuai'=>-10329,
        'zhuan'=>-10328,
        'zhuang'=>-10322,
        'zhui'=>-10315,
        'zhun'=>-10309,
        'zhuo'=>-10307,
        'zi'=>-10296,
        'zong'=>-10281,
        'zou'=>-10274,
        'zu'=>-10270,
        'zuan'=>-10262,
        'zui'=>-10260,
        'zun'=>-10256,
        'zuo'=>-10254
    );

	private function zh_to_py($num, $blank = '') {
       if($num>0 && $num<160 ) {
           return chr($num);
       } elseif ($num<-20319||$num>-10247) {
           return $blank;
       } else {
           foreach ($this->pylist as $py => $code) {
              if($code > $num) break;
              $result = $py;
           }
           return $result;
       }
    }

	public function zh_to_pys($chinese, $delimiter = '', $first=0){
		$chinese = iconv("UTF-8","GBK",$chinese);
		$result = array();
		for($i=0; $i<strlen($chinese); $i++) {
			$p = ord(substr($chinese,$i,1));
			if($p>160) {
				$q = ord(substr($chinese,++$i,1));
				$p = $p*256 + $q - 65536;
			}
			$result[] = $this->zh_to_py($p);
			if ($first) {
				return $result[0];
			}
		}
		return implode($delimiter, $result);
	}


	public function history() {
		$this->load->language('api/order');

		$json = array();
$this->log->write('看看api的请求');
$this->log->write($this->session->data['api_id']);
		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'order_status_id',
				'notify',
				'append',
				'comment'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			$this->load->model('checkout/order');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info) {
				//ishanks added it
				if(isset($this->request->post['shipping'])&&$this->request->post['shipping']&&$this->request->post['shipping']!="undefined"){
					$shipping_data = array();
					$shipping_data['shipping'] = $this->request->post['shipping'];
					$shipping_data['shipping_company'] = isset($this->request->post['shipping_company'])? $this->request->post['shipping_company'] : '';
					$shipping_data['shipping_company_pingyin'] = isset($this->request->post['shipping_company'])? $this->zh_to_pys($this->request->post['shipping_company']) : '';
					$this->model_checkout_order->addOrderHistoryShipping($order_id, $this->request->post['order_status_id'], $this->request->post['comment'].'Modified By '.$this->request->post['user'], $this->request->post['notify'],$shipping_data);
				}else{
					$this->model_checkout_order->addOrderHistory($order_id, $this->request->post['order_status_id'], $this->request->post['comment'].'Modified By '.$this->request->post['user'], $this->request->post['notify']);
				}
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}