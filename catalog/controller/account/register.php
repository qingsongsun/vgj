<?php
class ControllerAccountRegister extends Controller {
	private $error = array();
	private $login_type = '';
	private $credit_amount = 0;

    private function hex2bin($h) {
            if (!is_string($h)) return null;
              $r='';
              for ($a=0; $a<strlen($h); $a+=2) { $r.=chr(hexdec($h{$a}.$h{($a+1)})); }
                return $r;
    }

    private function phoneOrCard() {
		$input = trim($this->request->post['phone_or_card']);
		if (preg_match("/^1[34578]\d{9}$/", $input)) {
		    return "phone";
		} else {
			return "card";
		}
	}

	private function getImplicitCustomerGroupId() {
		$group_id = NULL;
		if ($this->login_type == 'card') {
			$group_id = $this->model_account_customer->getCustomerGroupIdByName(GROUP_NAME_VIP_USER);
		}

		return $group_id;
	}

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->load->language('account/register');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// Wenliang added, for registration simplification, fill dummy data when addCustomer

		//	$this->request->post['firstname'] = trim($this->request->post['phone_or_card']);
		//	$this->request->post['lastname'] = "JDWW";
			$this->request->post['email'] = trim($this->request->post['phone_or_card']); // . "@jdww.com";
		//	$this->request->post['country_id'] = 44;  // <-- China, may need to be changed when all other countries are deleted.
		//	$this->session->data['login_type'] = $this->login_type; // save it to session

			$customer_id = $this->model_account_customer->addCustomerSimple($this->request->post, $this->login_type, $this->credit_amount, $this->getImplicitCustomerGroupId());

			// Clear any previous login attempts for unregistered accounts.
			$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

			$this->customer->login($this->request->post['email'], $this->request->post['password']);

			unset($this->session->data['guest']);

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $customer_id,
				'name'        => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
			);

			$this->model_account_activity->addActivity('register', $activity_data);

			// 自动发券
			$this->load->model('app/coupon');
			$coupon=json_decode(COUPON_REG,true);
			$coupon=$coupon[0];
			$this->log->write($coupon['coupon_id']);
			if ($coupon['switch']=='on') {
				$coupon_info=$this->model_app_coupon->getCouponForCustomer($coupon['coupon_id']);
				$coupon_id=$this->model_app_coupon->dupilcateCoupon(array(
                            'name'=>$coupon_info['name'],
                            'code'=>mt_rand(100000,999999),
                            'discount'=>$coupon_info['discount'],
                            'type'=>$coupon_info['type'],
                            'total'=>$coupon_info['total'],
                            'logged'=>$coupon_info['logged'],
                            'shipping'=>$coupon_info['shipping'],
                            'date_start'=>$coupon_info['date_start'],
                            'date_end'=>$coupon_info['date_end'],
                            'uses_total'=>$coupon_info['uses_total'],
                            'uses_customer'=>$coupon_info['uses_customer'],
                            'status'=>$coupon_info['status']
                            ));
				$coupon_last=$this->model_app_coupon->getCouponForCustomer($coupon_id);

	            $this->load->model('account/mycoupon');

	            $this->model_account_mycoupon->addCustomerCoupon(array(
	                'coupon_id'=>$coupon_id,
	                'customer_id'=>$customer_id,
	                'coupon_code'=>$coupon_last['code'],
	                'coupon_description'=>$coupon_last['name']
	                ));
			}

			if (isset($this->session->data['verify'])) {

				$this->session->data['customer_id']=$customer_id;
				$this->session->data['register_flag']=true;

				$this->response->redirect($this->url->link('product/product', 'product_id='.$this->session->data['product_id'].'&verify='.$this->session->data['verify'],'', 'SSL'));


			}else{
				$this->response->redirect($this->url->link('account/success'));
			}

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
			'text' => $this->language->get('text_register'),
			'href' => $this->url->link('account/register', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['text_your_address'] = $this->language->get('text_your_address');
		$data['text_your_password'] = $this->language->get('text_your_password');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_phone_or_card'] = $this->language->get('entry_phone_or_card');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_newsletter'] = $this->language->get('entry_newsletter');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_confirm'] = $this->language->get('entry_confirm');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['phone_or_card'])) {
			$data['error_phone_or_card'] = $this->error['phone_or_card'];
		} else {
			$data['error_phone_or_card'] = '';
		}
/*
		if (isset($this->error['address_1'])) {
			$data['error_address_1'] = $this->error['address_1'];
		} else {
			$data['error_address_1'] = '';
		}

		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}

		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}
*/
		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		$data['action'] = $this->url->link('account/register', '', 'SSL');

		$data['customer_groups'] = array();

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

        $group_id = $this->getImplicitCustomerGroupId();
        if ($group_id) {
		    $this->request->post['customer_group_id'] = $group_id;
		}

		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['phone_or_card'])) {
			$data['phone_or_card'] = $this->request->post['phone_or_card'];
		} else {
			$data['phone_or_card'] = '';
		}
/*
		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} else {
			$data['fax'] = '';
		}

		if (isset($this->request->post['company'])) {
			$data['company'] = $this->request->post['company'];
		} else {
			$data['company'] = '';
		}

		if (isset($this->request->post['address_1'])) {
			$data['address_1'] = $this->request->post['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
			$data['address_2'] = $this->request->post['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
			$data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['shipping_address']['postcode'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}
*/
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		if (isset($this->request->post['custom_field'])) {
			if (isset($this->request->post['custom_field']['account'])) {
				$account_custom_field = $this->request->post['custom_field']['account'];
			} else {
				$account_custom_field = array();
			}

			if (isset($this->request->post['custom_field']['address'])) {
				$address_custom_field = $this->request->post['custom_field']['address'];
			} else {
				$address_custom_field = array();
			}

			$data['register_custom_field'] = $account_custom_field + $address_custom_field;
		} else {
			$data['register_custom_field'] = array();
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}
/*
		if (isset($this->request->post['newsletter'])) {
			$data['newsletter'] = $this->request->post['newsletter'];
		} else {
			$data['newsletter'] = '';
		}
*/
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->request->post['agree'])) {
			$data['agree'] = $this->request->post['agree'];
		} else {
			$data['agree'] = true;      // default is 'agree'
		}


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

        // additional verification field
        $data['config_account_reg_vercode'] = 1; // $this->config->get('config_account_reg_vercode');
        $data['entry_vercode'] = $this->language->get('entry_vercode');
        $data['entry_authcode'] = $this->language->get('entry_authcode');
        $data['entry_sendvercode'] = $this->language->get('entry_sendvercode');
        $data['error_authcode'] = $this->language->get('error_authcode');
        $data['error_phone'] = $this->language->get('error_phone');
        $data['text_verification'] = $this->language->get('text_verification');
  //      $data['error_card'] = "VIP卡注册无需校验码";

        if (isset($this->error['vercode'])) {
            $data['error_vercode'] = $this->error['vercode'];
        } else {
            $data['error_vercode'] = '';
        }
        if (isset($this->request->post['vercode'])) {
            $data['vercode'] = $this->request->post['vercode'];
        } else {
            $data['vercode'] = '';
        }

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/register.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/register.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/register.tpl', $data));
		}
	}

	public function validate() {
/* Wenliang removed, for simplified registration

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}
*/

		if ((utf8_strlen($this->request->post['phone_or_card']) < 3) || (utf8_strlen($this->request->post['phone_or_card']) > 32)) {
			$this->error['phone_or_card'] = $this->language->get('error_phone_or_card');
		}

/* Wenliang removed, for simplified registration

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			$this->error['address_1'] = $this->language->get('error_address_1');
		}

		if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
			$this->error['city'] = $this->language->get('error_city');
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '') {
			$this->error['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
			$this->error['zone'] = $this->language->get('error_zone');
		}
*/

		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 25)) {
			$this->error['password'] = $this->language->get('error_password');
		}


		// If the input is card number, check if password is provisioned in database
		if ($this->phoneOrCard() == 'card') {
			$query = $this->model_account_customer->getVipInfoByCardNumber($this->request->post['phone_or_card']);

		    if ($query->num_rows !== 0) {
		    	if ($query->row['card_password'] !== sha1(hex2bin(sha1($this->request->post['password'])))) {
		    	    $this->error['password'] = $this->language->get('error_card_password');
		        } else if ($query->row['status_used'] == 1) {
				    $this->error['warning'] = $this->language->get('error_card_exists');
			    }
			    $this->credit_amount = $query->row['credit_amount'];
			} else {
				$this->error['phone_or_card'] =  $this->language->get('error_phone_or_card');
			}
		    $this->login_type = 'card';


		} else {
			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['phone_or_card'])) {
		    	$this->error['warning'] = $this->language->get('error_phone_exists');
		    }
		    $this->login_type = 'phone';

			if (empty($this->request->post['vercode']) || $this->session->data['vercode'] != $this->request->post['vercode']) {
				$this->error['vercode'] = $this->language->get('error_vercode');
			}
			$this->credit_amount = 0; // not used for phone user
		}

		if ($this->login_type == 'phone') {
			if ($this->request->post['confirm'] != $this->request->post['password']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		$group_id = $this->getImplicitCustomerGroupId();
		if ($group_id) {
			$this->request->post['customer_group_id'] = $group_id;
		}

		// Customer Group
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else if ($group_id) {
			$customer_group_id = $group_id;
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$custom_field['required']=false;
			// if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
			// 	$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			// }
		}

		// Agree to terms
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		return !$this->error;
	}

	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				// 'required'        => $custom_field['required']
				'required'        => false
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

   function sendsms($data, $target) {
      $url_info = parse_url($target);
      $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
      $httpheader .= "Host:" . $url_info['host'] . "\r\n";
      $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
      $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
      $httpheader .= "Connection:close\r\n\r\n";
      $httpheader .= $data;

      $fd = fsockopen($url_info['host'], 80);
      fwrite($fd, $httpheader);
      $gets = "";
      while(!feof($fd)) {
         $gets .= fread($fd, 128);
      }
      fclose($fd);
      return $gets;
   }

   public function verify() {
      $json = array();

      if(!isset($this->request->get['authcode'])||($this->request->get['authcode'] != $this->session->data['authcode'])){
          $json['error_authcode'] =  true;
      } else {
          $this->session->data['vercode'] = rand(1234, 9999);
          $json['code']= $this->session->data['vercode'];
          $target = "http://sms.106jiekou.com/utf8/sms.aspx";
          $content= sprintf($this->language->get('text_sms_verification'), $json['code']);
          // "您的验证码是：". $json['code'] . "。如需帮助请联系客服。";
//	 $code=$json['code'];
//	 error_log("code=$content@\n",3,"/no-same.com/public_html/system/logs/sms_debug.log");
          $post_data = "account=cting&password=22089808&mobile=". trim($this->request->get['phone']) . "&content=".rawurlencode($content);
          $gets = $this->sendsms($post_data, $target);

          $json['result'] = $gets;
      }

      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
   }

   public function getAuthcode(){
       $json = array();
       $codeLength = 4;

       $this->session->data['authcode'] = $this->randomkeys($codeLength);
       $json['authcode'] = $this->session->data['authcode'];
       $this->response->addHeader('Content-Type: application/json');
       $this->response->setOutput(json_encode($json));
   }

   function randomkeys($length)
   {
   	  $key = '';
      $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
      for($i=0;$i<$length;$i++)
      {
          $key .= $pattern{mt_rand(0,35)};
      }
      return $key;
   }
}