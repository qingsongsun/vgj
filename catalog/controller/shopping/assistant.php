<?php
/*
  Wenliang: this is shopping assistant controller
*/
class ControllerShoppingAssistant extends Controller{
	 private $error = array();
     const SEARCH_LIMIT = 3; 
     
     public function index()
     {
     	$this->load->language('shopping/assistant');

		$this->document->setTitle($this->language->get('heading_title'));
        
        if ($this->customer->isLogged()) {
		    $this->load->model('account/customer_group');
		    $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());
		    if ($customer_group_info['name'] == GROUP_NAME_NORMAL_USER) {
		       $this->response->redirect($this->url->link('error/not_found', '', 'SSL'));
		    }
		} else {
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}	

	    $this->load->model('catalog/product');
	//    $this->load->model('catalog/category');
		$this->load->model('tool/image');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	//		$this->response->redirect($this->url->link('shopping/assistant'));
			$valid_input = 1;
		} else {
			$valid_input = 0;
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_assistant'),
			'href' => $this->url->link('shopping/assistant', '', 'SSL')
		);
        
        $url = '';
        
// Language loading		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['bundle_title'] = $this->language->get('bundle_title');
        $data['entry_assistant'] = $this->language->get('text_assistant');
		$data['entry_bundle_quantity'] = $this->language->get('entry_bundle_quantity');
		$data['entry_total_cost'] = $this->language->get('entry_total_cost');
		$data['entry_bundle_cost'] = $this->language->get('entry_bundle_cost');
        $data['entry_scenario_name'] = $this->language->get('entry_scenario_name');
        $data['entry_sub_quantity'] = $this->language->get('entry_sub_quantity');
        $data['entry_search'] = $this->language->get('entry_search');        
        
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_search'] = $this->language->get('text_search');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_sub_category'] = $this->language->get('text_sub_category');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
        
		$data['button_search'] = $this->language->get('button_search');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_cart_bundle'] = $this->language->get('button_cart_bundle');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');       

        $data['compare'] = $this->url->link('product/compare');		

// layout loading
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
// action loading
		$data['action'] = $this->url->link('shopping/assistant', '', 'SSL');
		
		// error loading
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['bundlequantity'])) {
			$data['error_bundlequantity'] = $this->error['bundlequantity'];
		} else {
			$data['error_bundlequantity'] = '';
		}

		if (isset($this->error['totalcost'])) {
			$data['error_totalcost'] = $this->error['totalcost'];
		} else {
			$data['error_totalcost'] = '';
		}

		if (isset($this->error['subquantity'])) {
			$data['error_subquantity'] = $this->error['subquantity'];
		} else {
			$data['error_subquantity'] = '';
		}
		
		if (isset($this->error['scenario'])) {
			$data['error_scenario'] = $this->error['scenario'];
		} else {
			$data['error_scenario'] = '';
		}		
		
// Check and set posted data
// scenario

		if (isset($this->request->post['scenario'])) {
			$scenario = $this->request->post['scenario'];
		} else {
			$scenario = '';
		}

// bundlequantity
		if (isset($this->request->post['bundlequantity'])) {
			$bundlequantity = $this->request->post['bundlequantity'];
		} else {
			$bundlequantity = '';
		}

// totalcost
		if (isset($this->request->post['totalcost'])) {
			$totalcost = $this->request->post['totalcost'];
		} else {
			$totalcost = '';
		}

// subquantity
		if (isset($this->request->post['subquantity'])) {
			$subquantity = $this->request->post['subquantity'];
		} else {
			$subquantity = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}

//	    $data['scenario'] = $scenario;
		$data['totalcost'] = $totalcost;
		$data['bundlequantity'] = $bundlequantity;
		$data['subquantity'] = $subquantity;
        
        $data['products'] = '';
        
        $scenarios = $this->model_catalog_product->getScenarios();
        $data['scenarios'] = $scenarios;
        if (isset($this->request->post['selected_scenario'])) {
			$selected_scenario = $this->request->post['selected_scenario'];
		} else {
			$selected_scenario = '';
		}
        $data['selected_scenario'] = $selected_scenario;
         
	    if ($valid_input) {

		  if ($totalcost)
		/* && isset($this->request->['subquantity']) && isset($this->request->get['bundlequantity']))
		*/
			$filter_data = array(
				'filter_totalcost'         => $totalcost,
				'filter_subquantity'          => $subquantity,
				'filter_bundlequantity'  => $bundlequantity,
		//		'filter_scenario'  => $scenario,
				'filter_name'      => $selected_scenario,
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);		
		// find all products matching the scenario (p2comb.combination_name = scenario (e.g. sport),
		// then find in the candidates where filter_bundlequantity <= p.quantity
		//	$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

// results shall be the final products matching all 4 criteria, that is
//  sum (p(N).price) <= totalcost/bundlequantity && N <= subquantity
// currently, since the limitation is set to 10 for subquantity, so don't bother with Pagination so far.
// results contains a ordered product list (descending in prince/special), and num_row >= subquantity
			$results = $this->model_catalog_product->getProductsScenario($filter_data);
		    
		    
		    // sort the result according to price and special in descending order. 
		    // Already done in sql
	/*
		    usort($result, function($a, $b) {
		    	$a_price = isset($a[special] ? : ;
		    	
		    });
	*/	    
		    $bundle_price = $totalcost / $bundlequantity;

			$bundle = '';
			$bundle_num = 0;
			for($i=0; $i<count($results); $i++) { 
				// if the remaining num of products < subquantity, stop searching
				if ((count($results) -$i) < $subquantity) {
					break;
				}
    			$num_in_bundle = 0;
				$delta_cost = $bundle_price - ($results[$i]['special'] ? $results[$i]['special'] : $results[$i]['price']);
				
				// add it to bundle
			    $bundle[$bundle_num][$num_in_bundle] = $results[$i];
				$num_in_bundle++;
				
				for ($j=$i+1; $j<count($results); $j++) {
					if ($delta_cost >= ($results[$j]['special'] ? $results[$j]['special'] : $results[$j]['price'])) {
						// add it to bundle
						$bundle[$bundle_num][$num_in_bundle] = $results[$j];
				        $num_in_bundle++;
                        $delta_cost -= ($results[$j]['special'] ? $results[$j]['special'] : $results[$j]['price']);			
						// don't allow exceeding subquantity
						// if ($num_in_bundle == $subquantity)
						// stop searching 
                        //		break;
					}
				}
				
				
				if ($num_in_bundle < $subquantity) {
						// clear the bundle[bundle_num]
						array_splice($bundle, $bundle_num);
						continue;
				} 
				
				
				// if enough to show,  stop searching
				// 3 OK as hard limit?
				if (count($bundle) == self::SEARCH_LIMIT)
					break;
					
				$bundle_num ++;
		    }
		    
     //       $data['products']['product_list'] = implode("-", $bundle[0]['product_id']);

        if (!empty($bundle)) {
			$cost = 0;
			for ($i=0; $i < count($bundle); $i++) {
				$data['product_list'][$i] = '';
				$data['bundle_cost'][$i] = 0;
				for ($j=0; $j < count($bundle[$i]); $j++) {
			    	$data['product_list'][$i] .= $bundle[$i][$j]['product_id'];
				    if ($j < count($bundle[$i]) - 1)
				        $data['product_list'][$i] .= '-';
				        
				    if (isset($bundle[$i][$j]['special']))
				      $cost = $bundle[$i][$j]['special'];
				    else
				      $cost = $bundle[$i][$j]['price'];
				    $data['bundle_cost'][$i] += $cost;
				}
			}
		
			
			$i = 0;
			foreach ($bundle as $results) {
			  
			  foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
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
             
				$data['products'][$i][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
				);
		       		
		
			  }  //foreach
			  $i++;	
			} // foreach
	//	$results = $this->model_catalog_product->getProducts($filter_data);
	      } // bundle not empty
	   } // valid_input		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shopping/assistant.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/shopping/assistant.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/shopping/assistant.tpl', $data));
		}
		
	//	$this->response->setOutput($this->load->view('default/template/shopping/assistant.tpl', $data));
     }
     
     public function validate() {
	 	if (!isset($this->request->post['selected_scenario'])) {
			$this->error['scenario'] = $this->language->get('error_scenario');
		}
		     	
	 	if (!is_numeric(trim($this->request->post['totalcost'])) || trim($this->request->post['totalcost']) > 1000000 || trim($this->request->post['totalcost']) < 100 ) {
			$this->error['totalcost'] = $this->language->get('error_totalcost');
		}
		
		if (!is_numeric(trim($this->request->post['subquantity'])) || trim($this->request->post['subquantity']) > 10 || trim($this->request->post['subquantity']) < 1) {
			$this->error['subquantity'] = $this->language->get('error_subquantity');
		}

		if (!is_numeric(trim($this->request->post['bundlequantity'])) || trim($this->request->post['subquantity']) > 10000 || trim($this->request->post['bundlequantity']) < 1) {
			$this->error['bundlequantity'] = $this->language->get('error_bundlequantity');
		}		
		
	 	return !$this->error;
	 }
}
?>