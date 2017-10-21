<?php

?><?php
class ControllerAccountSwitch extends Controller {
	private $error = array();
	
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/transaction', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		if (isset($this->session->data['switched_user'])) { // not allowed for switched user
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}

		$this->load->language('account/switch');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('account/customer_group');
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());
		
		if ($customer_group_info['name'] != GROUP_NAME_VIP_AGENT) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

        // Wenliang added, for switch user test        
        $card_number = '';
        $result = '';
       
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForSwitchUser($result, $card_number)) {
			  $this->switchUser($result);
		}
        $data['action'] = $this->url->link('account/switch', '', 'SSL');
        $data['button_switch_user'] = $this->language->get('button_switch_user');
        $data['entry_vipcard'] = $this->language->get('entry_vipcard');
        $data['vipcard'] = $card_number;
              
		if (isset($this->error['vipcard'])) {
			$data['error_vipcard'] = $this->error['vipcard'];
		} else {
			$data['error_vipcard'] = '';
		}
        
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_switch'),
			'href' => $this->url->link('account/switch', '', 'SSL')
		);

		$this->load->model('account/transaction');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/switch.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/switch.tpl', $data));
		} else {
	//		$this->response->setOutput($this->load->view('default/template/account/switch.tpl', $data));
		}
	}
	
	// Wenliang added, for VIP card association validation, not used for this release
    public function validate(&$result, &$card_number) {
		$card_number = trim($this->request->post['vipcard']);
		if (empty($card_number)) {
			return 0;
		}
		
	 	if (strlen($card_number) > 32) {
			$this->error['vipcard'] = $this->language->get('error_vipcard_not_found');
	    } else {
	    	$result = $this->model_account_transaction->getVipCard($card_number);
	    	if (empty($result->row)) {
			  	// if card_number doesn't match any of the existing one
			  	$this->error['vipcard'] = $this->language->get('error_vipcard_not_found');
			  	
		    } 
		    
		    if ($result->row['status_used'] == 1) {
			  	// if card_number match one but is already used
			  	$this->error['vipcard'] = $this->language->get('error_vipcard_already_added');
		    }
		}
	    return !$this->error;
	}

    // Wenliang added, for switching VIP card validation 		
	public function validateForSwitchUser(&$result, &$card_number) {
		$card_number = trim($this->request->post['vipcard']);
	 	
	 	if (empty($card_number) || strlen($card_number) > 32) {
			$this->error['vipcard'] = $this->language->get('error_vipcard_not_found');
	    } else {
	    //	$result = $this->model_account_transaction->getVipCard($card_number);
	    	
	        $sql = "SELECT DISTINCT * ";
        
		    $sql .= " FROM " . DB_PREFIX . "vip_card vc WHERE vc.card_number = '" . $card_number . "'";
	        $result = $this->db->query($sql);
	    	
	    	if (empty($result->row)) {
			  	// if card_number doesn't match any of the existing one
			  	$this->error['vipcard'] = $this->language->get('error_vipcard_not_found');
			  	
		    } else if ($result->row['status_used'] == 0) {
			  	// if card_number match one but is not activated yet
			  	$this->error['vipcard'] = $this->language->get('error_vipcard_not_activated');
		    }
		}
		return !$this->error;
	}	
		
	// Wenliang added, for VIP card switch 
	public function switchUser($result) {
		$json = array();
  
        $customer_id = $result->row['customer_id'];

		$this->load->model('sale/customer');

		$customer_info = $this->model_sale_customer->getCustomer($customer_id);

		if ($customer_info) {
			$token = md5(mt_rand());

			$this->model_sale_customer->editToken($customer_id, $token);

            $store_id = $result->row['store_id'];
			
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($store_id);
			
			$this->session->data['switched_user'] = TRUE;

			if ($store_info) {
				$this->response->redirect($store_info['url'] . 'index.php?route=account/login&token=' . $token);
			} else {
	//			$this->response->redirect(HTTP_CATALOG . 'index.php?route=account/login&token=' . $token);
	            $this->response->redirect('index.php?route=account/login&token=' . $token);
			}
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_not_found'] = $this->language->get('text_not_found');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
		}
	}
}