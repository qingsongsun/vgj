<?php
require_once (DIR_WXHELPER);
require_once (DIR_WXCONFIG);
class ControllerProductProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('product/product');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

                $this->load->model('journal2/product');


		$product_info = $this->model_catalog_product->getProduct($product_id);


		if ($product_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);

			$this->document->setTitle($product_info['meta_title']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['heading_title'] = $product_info['name'];

			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];

			if (strpos($this->config->get('config_template'), 'journal2') === 0) {
			    $this->load->model('catalog/manufacturer');
                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
                if ($manufacturer_info && $manufacturer_info['image'] && $this->journal2->settings->get('manufacturer_image', '0') == '1') {
                    $this->journal2->settings->set('manufacturer_image', 'on');
                    $data['manufacturer_image_width'] = $this->journal2->settings->get('manufacturer_image_width', 100);
                    $data['manufacturer_image_height'] = $this->journal2->settings->get('manufacturer_image_height', 100);
                    $data['manufacturer_image'] = Journal2Utils::resizeImage($this->model_tool_image, $manufacturer_info['image'], $data['manufacturer_image_width'], $data['manufacturer_image_height']);
                    switch ($this->journal2->settings->get('manufacturer_image_additional_text', 'none')) {
                        case 'brand':
                            $data['manufacturer_image_name'] = $product_info['manufacturer'];
                            break;
                        case 'custom':
                            $data['manufacturer_image_name'] = $this->journal2->settings->get('manufacturer_image_custom_text');
                            break;
                    }
                }
			}

			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];


                if (true && $product_info['quantity'] <= 0) {
                    $data['stock_status'] = 'outofstock';
                }
                if (true && $product_info['quantity'] > 0) {
                    $data['stock_status'] = 'instock';
                }
                $data['labels'] = $this->model_journal2_product->getLabels($product_info['product_id']);

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			if ($product_info['image']) {
$data['popup_fixed'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$data['popup'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			}

			if ($product_info['image']) {
$data['thumb_fixed'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));

			} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$data['price'] = false;
			}

			if ((float)$product_info['special']) {

                if (strpos($this->config->get('config_template'), 'journal2') === 0 && $this->journal2->settings->get('show_countdown_product_page', 'on') == 'on') {
                    $this->load->model('journal2/product');
                    $date_end = $this->model_journal2_product->getSpecialCountdown($this->request->get['product_id']);
                    if ($date_end === '0000-00-00') {
                        $date_end = false;
                    }
                    $data['date_end'] = $date_end;
                }

				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				$names = array();
				$names = str_replace("（","(", $option['name']);
				$names = str_replace("）", "(", $names);
				$names = str_replace(")", "(", $names);
				$names = str_replace("，", ",", $names);
				$names = explode("(",$names);

				if(count($names)>1){
					$names = explode(",",$names[1]);
				}else{
					$names = $names[0];
				}

				$opts = array();

				$data["values"] = array();

				for( $i=0;$i<count($names);$i++){
					$opts[$i] = array();
				}

				$name_id_price_prefix = array();
				$posible_values = array();
				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] >= 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
						} else {
							$price = false;
						}

						$values = array();
						$values = explode(",",str_replace("，", ",",$option_value['name']));
						//to get all sub-options
						for($i=0;$i<count($names);$i++){
							$flag = 1;
							foreach($opts[$i] as $j){
								if($j == $values[$i]){
									$flag = 0;
								}
							}
							if($flag){
								$opts[$i][] = $values[$i];
							}
						}

						$possible_values[] = str_replace("，", ",",$option_value['name']);

						$name_id_price_prefix[] = str_replace("，", ",",$option_value['name']).",,,".$option_value['product_option_value_id'].",,,".$price.",,,".$option_value['price_prefix'];

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => strpos($this->config->get('config_template'), 'journal2') === 0 ? Journal2Utils::resizeImage($this->model_tool_image, $option_value['image'], $this->journal2->settings->get('product_page_options_push_image_width', 30), $this->journal2->settings->get('product_page_options_push_image_height', 30), 'crop') : $this->model_tool_image->resize($option_value['image'], 50, 50),
							'thumb'			  		  =>  $option_value['image']&&$option_value['image']!="no_image.jpg" ? $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')) : null,
							'popup' 				  =>  $option_value['image']&&$option_value['image']!="no_image.jpg" ? $this->model_tool_image->resize($option_value['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) : null,
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix'],
							// Luke added.
							'has_inventory'						=> ($option_value['quantity'] > 0)?true:false
						);
					}
				}

				//variable opts contains all sub-option values. To ease the usage in tpl file, sub-option values are concatenated in this variable
				$t = array();

				foreach($opts as $opt){
					$t[] = implode(",", $opt);
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required'],
					'names'                => $names,
					'opts'                 => $opts,
					'combined_opts'        => implode(",,",$t),
					'name_id_price_prefix' => $name_id_price_prefix,
					'possible_values'      => isset($possible_values) ? $possible_values : '' // Wenliang modified
				);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

            // Wenliang mod, dont' mess up with guest for logged user
//			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
			if ($this->config->get('config_review_guest')) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			$data['review_user_brought'] = false;
			if ($this->customer->isLogged()) {
				$data['review_user_logged'] = true;
			// Wenliang added, check if the product has been purchased
			// check if current product_id is in the array of purchased product_id list, and the order status must be completed
				// processed -> shipped -> completed, orders under only those status are filtered
			    $query = $this->model_catalog_product->getBroughtProductsByCustomer($this->customer->getId());
				foreach ($query->rows as $row) {
					if ($this->request->get['product_id'] == $row['product_id']) {
						$data['review_user_brought'] = true;
					}
				}
			} else {
				$data['review_user_logged'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);

			$data['rating'] = (int)$product_info['rating'];

			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}


                $date_end = false;
                if (strpos($this->config->get('config_template'), 'journal2') === 0 && $special && $this->journal2->settings->get('show_countdown', 'never') !== 'never') {
                    $this->load->model('journal2/product');
                    $date_end = $this->model_journal2_product->getSpecialCountdown($result['product_id']);
                    if ($date_end === '0000-00-00') {
                        $date_end = false;
                    }
                }


                $additional_images = $this->model_catalog_product->getProductImages($result['product_id']);

                $image2 = false;

                if (count($additional_images) > 0) {
                    $image2 = $this->model_tool_image->resize($additional_images[0]['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                }

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,

                'thumb2'       => $image2,


                'labels'        => $this->model_journal2_product->getLabels($result['product_id']),

					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,

                'date_end'       => $date_end,

					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			if ($this->config->get('config_google_captcha_status')) {
				$this->document->addScript('https://www.google.com/recaptcha/api.js');

				$data['site_key'] = $this->config->get('config_google_captcha_public');
			} else {
				$data['site_key'] = '';
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			// 调起一物一码验证
			// 跳入验证逻辑
			if (isset($this->request->get['verify'])) {

				// 为了让微信能够回到该页面，将参数放置到session中
				$this->session->data['verify']=$this->request->get['verify'];
				$this->session->data['product_id']=$this->request->get['product_id'];

				$this->session->data['redirect'] = $this->url->link('product/product', 'product_id='.$this->request->get['product_id'].'&verify='.$this->request->get['verify'],'', 'SSL');


				// 微信登录
				if (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) {

					if (!$this->customer->isLogged()) {



						// $this->response->redirect($this->url->link('account/wxlogin', '', 'SSL'));
					}
				}

				$data['verify']=$this->request->get['verify'];

				$verify=$this->request->get['verify'];
				$verify=explode("_",$verify);

				$verify_info=$this->model_catalog_product->getVerify(array(
					'sn'=>$verify[0],
					'code'=>$verify[1]
					));

				// 为了让微信能够回到该页面，将参数放置到session中
				$this->session->data['product_verify_id']=$verify_info['product_verify_id'];

				$data['product_verify_id']=$verify_info['product_verify_id'];
				$data['product_id']=$verify_info['product_id'];
				// 显示首次查询的时间
				$data['first_scan_date']=date("Y-m-d h:i:s",$verify_info['first_date']);
				// 判断是否查到该产品
				if (!$verify_info) {
					$data['verify_erro']=true;
				}else{
					// 查到产品
					$data['scan_counter']=(int)$verify_info['scan_counter']+1;
					// 增加验证次数
					$this->model_catalog_product->scanCounterAdd(array(
						'scan_counter'=>$data['scan_counter'],
						'sn'=>$verify[0],
						'code'=>$verify[1]
						));
					// 如果是首次验证 插入时间
					if ($verify_info['scan_counter']==0) {
						$first_date=time();
						$this->model_catalog_product->scanDateUpdate(array(
							'first_date'=>$first_date,
							'product_verify_id'=>$verify_info['product_verify_id']
							));
					}
					// 判断是否被注册过
					$data['registry_status']=$verify_info['registry_status'];

					// 是否是用户注册之后的跳转
					if (isset($this->session->data['register_flag'])) {
						$data['register_flag']=true;
					}else{
						$data['register_flag']=false;
					}

				}

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product_verify.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/product/product_verify.tpl', $data));
				}

			}else if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/product.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/product/product.tpl', $data));
				}
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function review() {
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/review.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/review.tpl', $data));
		}
	}

	public function write() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			/* Wenliang removed
			if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}
			*/
            // Replaced with user identity (phone number or card number with masks ****)
            $this->request->post['name'] = substr_replace($this->customer->getEmail(), "****", 3, 4);

			if ((utf8_strlen($this->request->post['text']) < 3) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			if ($this->config->get('config_google_captcha_status') && empty($json['error'])) {
				if (isset($this->request->post['g-recaptcha-response'])) {
					$recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

					$recaptcha = json_decode($recaptcha, true);

					if (!$recaptcha['success']) {
						$json['error'] = $this->language->get('error_captcha');
					}
				} else {
					$json['error'] = $this->language->get('error_captcha');
				}
			}

			// Wenliang added, for avoiding duplicated comments for one customer
			$this->load->model('catalog/review');
			// Huwen add the product_id as a parameter,perfect the logic for avoiding duplicated comments for one customer
			if ($this->model_catalog_review->hasReviewByCustomerId($this->customer->getId(), $this->request->get['product_id'])) {
				$json['error'] = $this->language->get('error_duplicate_comment');
			}

			if (!isset($json['error'])) {
				//$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
		$this->language->load('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')));

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// public function registry_product(){

	// 	$json=array();

	// 	// 判断是否登录过
	// 	if (!$this->customer->isLogged()) {
	// 	// 未登录则引导用户登录，判断是否为本站的会员
	// 	// 1、如果是微信浏览器打开，则获取openid校验身份：是会员直接登录，不是会员则引导绑定

	// 		// 2、如果是普通浏览器打开，则跳到登录页面
	// 		$json['customer_id']=$this->customer->getId();
	// 		$json['noWx']=true;

	// 	}else{

	// 		$json['customer_id']=$this->customer->getId();
	// 		//注意：产品只能注册一次 判断该产品是否被注册过

	// 		$verify_info=$this->model_catalog_product->getVerifyById($this->request->get['product_verify_id']);

	// 		$json['verify_info']=$verify_info;

	// 		if ($verify_info['registry_status']) {
	// 			$json['registry_status']=true;

	// 		}else{

	// 			//已经登陆过了,且该产品未被注册 直接帮用户注册该产品：注册商品的流程就直接走下单的逻辑

	// 			$order_data=$this->registryOrder($this->request->get['product_id']);

	// 			// 1、写入历史订单，订单状态为已完成，并且返回成功
	// 	    	// 2、修改该产品的相关状态
	// 	        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'],5,"防伪验证专用");
	//         	// 修改注册状态，写入注册者的id
	//         	$this->model_catalog_product->registryUpdate(array(
	//         		'registry_status'=>true,
	//         		'product_verify_id'=>$this->request->get['product_verify_id'],
	//         		'customer_id'=>$this->customer->getid()
	//         		));
	//         	$json['success']=true;

	// 		}
	// 	}


	// 	$this->response->addHeader('application/json');
	// 	$this->response->setOutput(json_encode($json));

	// }

	// public function registryOrder($product_id){

	// 		$product_info=$this->model_catalog_product->getProduct($product_id);

	// 		// 清空购物车
	// 		$this->cart->clear();

	// 		// 加入购物车 数量默认值1 选项待定
	// 		$this->cart->add($product_id);
	// 		// 生成空订单
	// 		$order_data = $this->model_journal2_checkout->getOrder();
	// 		// 记录用户信息
	// 		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
	// 		$order_data['customer_id'] = $this->customer->getId();
	// 		$order_data['customer_group_id'] = $customer_info['customer_group_id'];
 //            $order_data['firstname'] = $customer_info['firstname'];
 //            $order_data['lastname'] = $customer_info['lastname'];
 //            $order_data['email'] = $customer_info['email'];
 //            $order_data['telephone'] = $customer_info['telephone'];
 //            $order_data['fax'] = $customer_info['fax'];
 //            if (Front::$IS_OC2) {
 //                $order_data['custom_field'] = version_compare(VERSION, '2.1', '>=') ? json_decode($customer_info['custom_field'], true) : unserialize($customer_info['custom_field']);
 //            }
 //        	// order totals
	//         $order_data['totals'] = array();
	//         $total = 0;
	//         $taxes = $this->cart->getTaxes();
	//         if (Front::$IS_OC2) {
	//             $this->load->model('extension/extension');
	//             $results = $this->model_extension_extension->getExtensions('total');
	//         } else {
	//             $this->load->model('setting/extension');
	//             $results = $this->model_setting_extension->getExtensions('total');
	//         }
	//         $sort_order = array();
	//         foreach ($results as $key => $value) {
	//             $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
	//         }
	//         array_multisort($sort_order, SORT_ASC, $results);
	//         foreach ($results as $result) {
	//             if ($this->config->get($result['code'] . '_status')) {
	//                 $this->load->model('total/' . $result['code']);

	//                 $this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
	//             }
	//         }
	//         $sort_order = array();
	//         foreach ($order_data['totals'] as $key => $value) {
	//             $sort_order[$key] = $value['sort_order'];
	//         }
	//         array_multisort($sort_order, SORT_ASC, $order_data['totals']);
	//         $order_data['total'] = $total;
	//     	// order products
	//         $order_data['products'] = array();
	//         foreach ($this->cart->getProducts() as $product) {
	//             $option_data = array();
	//             foreach ($product['option'] as $option) {
	//                 $option_data[] = array(
	//                     'product_option_id'       => $option['product_option_id'],
	//                     'product_option_value_id' => $option['product_option_value_id'],
	//                     'option_id'               => $option['option_id'],
	//                     'option_value_id'         => $option['option_value_id'],
	//                     'name'                    => $option['name'],
	//                     'value'                   => Front::$IS_OC2 ? $option['value'] : $option['option_value'],
	//                     'type'                    => $option['type']
	//                 );
	//             }
	//             $order_data['products'][] = array(
	//                 'product_id' => $product['product_id'],
	//                 'name'       => $product['name'],
	//                 'model'      => $product['model'],
	//                 'option'     => $option_data,
	//                 'download'   => $product['download'],
	//                 'quantity'   => $product['quantity'],
	//                 'subtract'   => $product['subtract'],
	//                 'price'      => $product['price'],
	//                 'total'      => $product['total'],
	//                 'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
	//                 'reward'     => $product['reward']
	//             );
	//         }
	//         // 将订单信息写入空订单
	//         $this->model_journal2_checkout->setOrderData($order_data);
	//         $this->model_journal2_checkout->save();
	//         // 将订单返回
	//         return $order_data;

	// }
}
