<?php
class ModelAppAddress extends Model {
	public function getAddresses($username){

		$customer_query=$this->db->query("SELECT customer_id FROM ".DB_PREFIX."customer WHERE telephone='".(int)$username."'");

		if ($customer_query->num_rows) {
			$customer_id=$customer_query->row['customer_id'];

		}else{
			$customer_id='';
		}

		$address_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_query->row['customer_id'] . "'");

		foreach ($query->rows as $result) {

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];

			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';

			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data[] = array(
				'address_id'     => $result['address_id'],
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'postcode'       => $result['postcode'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($result['custom_field'])

			);
		}
		if (count($address_data)==0) {
			$address_data='';
		}
		return $address_data;
	}

	public function getAddressById($address_id,$customer_id){
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$customer_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($address_query->row['custom_field'])
			);

			return $address_data;
		} else {
			return false;
		}

	}

	public function getCustomerIdByOpenid($openid){
		if (empty($openid)) {
			return false;
		}else{
			$query=$this->db->query("SELECT customer_id FROM ".DB_PREFIX."customer WHERE openid='".$this->db->escape($openid)."'");

			return $query->row['customer_id'];
		}
	}

	public function updateOpenidForCustomer($username,$openid){
		$customer_id=$this->getCustomerId($username);

		$this->db->query("UPDATE ".DB_PREFIX."customer SET openid='".$this->db->escape($openid)."' WHERE customer_id='".(int)$customer_id."'");
	}

	public function updateOpenidByCustomerId($customer_id,$openid){
		$this->db->query("UPDATE ".DB_PREFIX."customer SET openid='".$this->db->escape($openid)."' WHERE customer_id='".(int)$customer_id."'");
	}

	public function getCustomerId($username){

		$customer_query=$this->db->query("SELECT customer_id FROM ".DB_PREFIX."customer WHERE telephone='".(int)$username."'");

		if ($customer_query->num_rows) {
			$customer_id=$customer_query->row['customer_id'];

		}else{
			// 用户不存在，则自动为用户建立一个账号，默认的初始密码为123456
			$customer_id=$this->addCustomerForApp(array(
				'email'=>$username,
				'phone_or_card'=>$username,
				'password'=>'123456'
				));
			// 自动发券
			$this->load->model('app/coupon');
			$coupon=json_decode(COUPON_REG,true);
			$coupon=$coupon[0];
			$this->log->write($coupon['coupon_id']);
			if ($coupon['switch']=='on') {
				$coupon_info=$this->model_app_coupon->getCouponForCustomer($coupon['coupon_id']);
				if ($coupon_info) {
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
			}
		}


		return $customer_id;
	}

	public function addCustomerForApp($data) {
		$this->event->trigger('pre.customer.add', $data);

		$customer_group_id=1;

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

            $query = $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', email = '" . $this->db->escape($data['email']) . "', telephone = " . $data['phone_or_card'] . ", custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		    $customer_id = $this->db->getLastId();

        // insert the initial payment password
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_payment SET customer_id = '" . (int)$customer_id ."', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', pay_password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', date_added = NOW()");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->load->language('mail/customer');

		$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

		$message = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject($subject);
		$mail->setText($message);
		// $mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->event->trigger('post.customer.add', $customer_id);

		return $customer_id;
	}

}