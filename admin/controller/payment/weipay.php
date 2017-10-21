<?php 
class ControllerPaymentWeipay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/weipay');

		$this->document->settitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('weipay', $this->request->post);

            $this->session->data['complete'] = $this->language->get('text_complete');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

		
		if (isset($this->error['weipay_appid'])) {
			$data['error_appid'] = $this->error['weipay_appid'];
		} else {
			$data['error_appid'] = '';
		}

		if (isset($this->error['weipay_mchid'])) {
			$data['error_mchid'] = $this->error['weipay_mchid'];
		} else {
			$data['error_mchid'] = '';
		}

		if (isset($this->error['weipay_appsecret'])) {
			$data['error_appsecret'] = $this->error['weipay_appsecret'];
		} else {
			$data['error_appsecret'] = '';
		}

		if (isset($this->error['weipay_key'])) {
			$data['error_key'] = $this->error['weipay_key'];
		} else {
			$data['error_key'] = '';
		}
		
   		$data['breadcrumbs']  = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' =>' > '
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/weipay&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' =>' > '
   		);
   		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('weipay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
                $data['weipay_environment_live'] = $this->language->get('weipay_environment_live');
                $data['weipay_environment_test'] = $this->language->get('weipay_environment_test');
                $data['text_storename_order_no'] = $this->language->get('text_storename_order_no');
                $data['text_storename_product_detail'] = $this->language->get('text_storename_product_detail');

		
		$data['entry_order_finish_status'] = $this->language->get('entry_order_finish_status');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');	
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_appid'] = $this->language->get('entry_appid');
		$data['entry_mchid'] = $this->language->get('entry_mchid');
		$data['entry_appsecret'] = $this->language->get('entry_appsecret');
                $data['entry_debug'] = $this->language->get('entry_debug');
                $data['entry_environment'] = $this->language->get('entry_environment');
                $data['entry_productname_format'] = $this->language->get('entry_productname_format');

                $data['help_debug'] = $this->language->get('help_debug');
                $data['help_notice'] = $this->language->get('help_notice');

                $data['tab_settings'] = $this->language->get('tab_settings');
                $data['tab_debug_settings'] = $this->language->get('tab_debug_settings');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
 		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}


		$data['action'] = HTTPS_SERVER . 'index.php?route=payment/weipay&token=' . $this->session->data['token'];
		
		$data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		
		
		if (isset($this->request->post['weipay_appid'])) {
			$data['weipay_appid'] = $this->request->post['weipay_appid'];
		} else {
			$data['weipay_appid'] = $this->config->get('weipay_appid');
		}

		if (isset($this->request->post['weipay_mchid'])) {
			$data['weipay_mchid'] = $this->request->post['weipay_mchid'];
		} else {
			$data['weipay_mchid'] = $this->config->get('weipay_mchid');
		}

		if (isset($this->request->post['weipay_appsecret'])) {
			$data['weipay_appsecret'] = $this->request->post['weipay_appsecret'];
		} else {
			$data['weipay_appsecret'] = $this->config->get('weipay_appsecret');
		}

		if (isset($this->request->post['weipay_key'])) {
			$data['weipay_key'] = $this->request->post['weipay_key'];
		} else {
			$data['weipay_key'] = $this->config->get('weipay_key');
		}
		
		if (isset($this->request->post['weipay_order_status_id'])) {
			$data['weipay_order_status_id'] = $this->request->post['weipay_order_status_id'];
		} else {
			$data['weipay_order_status_id'] = $this->config->get('weipay_order_status_id'); 
		} 

		if (isset($this->request->post['weipay_order_finish_status_id'])) {
			$data['weipay_order_finish_status_id'] = $this->request->post['weipay_order_finish_status_id'];
		} else {
			$data['weipay_order_finish_status_id'] = $this->config->get('weipay_order_finish_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['weipay_status'])) {
			$data['weipay_status'] = $this->request->post['weipay_status'];
		} else {
			$data['weipay_status'] = $this->config->get('weipay_status');
		}
	
                if (isset($this->request->post['weipay_debug'])) {
                    $data['weipay_debug'] = $this->request->post['weipay_debug'];
                } else {
                    $data['weipay_debug'] = $this->config->get('weipay_debug');
                }

                if (isset($this->request->post['weipay_environment'])) {
                        $data['weipay_environment'] = $this->request->post['weipay_environment'];
                } else {
                        $data['weipay_environment'] = $this->config->get('weipay_environment');
                }
		
		if (isset($this->request->post['weipay_productname_format'])) {
                        $data['weipay_productname_format'] = $this->request->post['weipay_productname_format'];
                } else {
                        $data['weipay_productname_format'] = $this->config->get('weipay_productname_format');
                }


	
		if (isset($this->request->post['weipay_sort_order'])) {
			$data['weipay_sort_order'] = $this->request->post['weipay_sort_order'];
		} else {
			$data['weipay_sort_order'] = $this->config->get('weipay_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('payment/weipay.tpl', $data));
	}
    
    public function install() {
        $this->load->model('payment/weipay');
        $this->model_payment_weipay->logger("I'm before install\n");
        $this->model_payment_weipay->install();
        $this->model_payment_weipay->logger("I'm after install\n");
    }
	public function uninstall() {
        $this->load->model('payment/weipay');
        $this->model_payment_weipay->uninstall();
    }


	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/weipay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if (!$this->request->post['weipay_appid']) {
			$this->error['weipay_appid'] = $this->language->get('error_appid');
		}

		if (!$this->request->post['weipay_mchid']) {
			$this->error['weipay_mchid'] = $this->language->get('error_mchid');
		}

		if (!$this->request->post['weipay_appsecret']) {
			$this->error['weipay_appsecret'] = $this->language->get('error_appsecret');
		}

		if (!$this->request->post['weipay_key']) {
			$this->error['weipay_key'] = $this->language->get('weipay_key');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>
