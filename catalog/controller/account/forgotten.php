<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();
    private $query_email = 0;
    private $query_phone = 0;
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
   	
	public function index() {
	/*  Wenliang removed, allow retrieving password even user is logged.
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}
*/
/*		if (isset($this->session->data['switched_user'])) { // not allowed for switched user
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}		
*/
		$this->load->language('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');
        
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->language('mail/forgotten');

			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 6);    // password length = 6
            $customer_id = 0;

            $phone = trim($this->request->post['phone']);
			
			if ($this->query_email == 1 && $this->query_phone == 1) { 
			// a user has both vip and non-vip account, check which one is selected and decide email field
			    if ($this->request->post['account_type'] == "account_vip") {
				    $result = $this->model_account_customer->getCustomerByPhone($phone);
				    $customer_id = $result['customer_id'];
				    $this->request->post['email'] = $result['email'];  // This is actually the VIP card no.
				} else {
				    $result = $this->model_account_customer->getCustomerByEmail($phone);
				    $customer_id = $result['customer_id'];						
				    $this->request->post['email'] = $phone;
				}				
			} else if ($this->query_email == 1) {
			// a non vip user
				    $result = $this->model_account_customer->getCustomerByEmail($phone);
				    $customer_id = $result['customer_id'];
				    $this->request->post['email'] = $phone;				
			} else {
			// a vip user
				    $result = $this->model_account_customer->getCustomerByPhone($phone);
				    $customer_id = $result['customer_id'];	
				    $this->request->post['email'] = $result['email'];  // This is actually the VIP card no.	
			}
			            
            switch ($this->request->post['password_type']) {
				case 'login_password':
				    $this->model_account_customer->editPassword($this->request->post['email'], $password);
				    break;
				case 'pay_password': 
				    $this->model_account_customer->editPaymentPassword($customer_id, $password);
				    break;
				case 'both_password':
        			$this->model_account_customer->editPassword($this->request->post['email'], $password);
		        	$this->model_account_customer->editPaymentPassword($customer_id, $password);			
		        	break;	
				default:
			}
			
			// Wenliang removed, we don't send email but send sms instead
            $target = "http://sms.106jiekou.com/utf8/sms.aspx";
            $content= sprintf($this->language->get('text_sms_reset'), $phone, $password);
            // "尊敬的会员：" .  . " 您已经成功修改密码，您的新密码：" . $password . " 请牢记新密码！";
            
//	 $code=$json['code'];
//	 error_log("code=$content@\n",3,"/no-same.com/public_html/system/logs/sms_debug.log");
            $post_data = "account=cting&password=22089808&mobile=". $phone . "&content=".rawurlencode($content);
            $gets = $this->sendsms($post_data, $target);
/*
			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;


			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
*/

			$this->session->data['success'] = $this->language->get('text_success');

			// Add to activity log
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		if (isset($this->request->post['password_type'])) {
			$data['password_type'] = $this->request->post['password_type'];
		} else {
			$data['password_type'] = "login_password";
		}
		
		if (isset($this->request->post['phone'])) {
			$data['input_phone'] = $this->request->post['phone'];
		} else {
			$data['input_phone'] = '';
		}
		
		if (isset($this->request->post['account_type'])) {
			$data['account_type'] = $this->request->post['account_type'];
		} else {
			$data['account_type'] = 'account_non_vip';
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
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('account/forgotten', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_phone'] = $this->language->get('text_your_phone');
		$data['text_phone'] = $this->language->get('text_phone');

		$data['entry_phone'] = $this->language->get('entry_phone');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['phone'])) {
			$data['error_phone'] = $this->error['phone'];
		} else {
			$data['error_phone'] = '';
		}

		$data['action'] = $this->url->link('account/forgotten', '', 'SSL');

		$data['back'] = $this->url->link('account/login', '', 'SSL');

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
        
        $data['text_verification'] = $this->language->get('text_verification');
        $data['text_password_type'] = $this->language->get('text_password_type');
        $data['entry_password_type'] = $this->language->get('entry_password_type');	
        $data['text_pay_password'] = $this->language->get('text_pay_password');
        $data['text_both_password'] = $this->language->get('text_both_password');
        $data['text_login_password'] = $this->language->get('text_login_password');
        $data['text_account_type'] = $this->language->get('text_account_type');
        $data['text_account_vip'] = $this->language->get('text_account_vip');
        $data['text_account_non_vip'] = $this->language->get('text_account_non_vip');
         
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/forgotten.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/forgotten.tpl', $data));
		}
	}

	protected function validate() {
		
	
		if (!isset($this->request->post['phone']) || !preg_match("/^1[34578]\d{9}$/", trim($this->request->post['phone']))) {
			$this->error['phone'] = $this->language->get('error_phone_format');
		} else {
			if ($this->model_account_customer->getTotalCustomersByEmail(trim($this->request->post['phone']))) {
				$this->query_email = 1;
			}
			
			if ($this->model_account_customer->getTotalCustomersByPhone(trim($this->request->post['phone']))) {
				$this->query_phone = 1;
			} 
			
			if (!$this->query_email && !$this->query_phone) {
				$this->error['phone'] = $this->language->get('error_phone_not_found');
			}			
		}

		if (empty($this->request->post['vercode']) || $this->session->data['vercode'] != $this->request->post['vercode']) {
			$this->error['vercode'] = $this->language->get('error_vercode');
		}
		
		return !$this->error;
	}
	
	// Wenliang added, for finding whether the same phone number is registered for both non-VIP user and associated with VIP user
	public function checkPhone() {
		$json = array();
		$json['query_email'] = 0;
		$json['query_phone'] = 0;
		
		if (!empty($this->request->get['phone'])) {
            $this->load->model('account/customer');
			if ($this->model_account_customer->getTotalCustomersByEmail(trim($this->request->get['phone']))) {
				$json['query_email'] = 1;
			} 
			
			if ($this->model_account_customer->getTotalCustomersByPhone(trim($this->request->get['phone']))) {
				$json['query_phone'] = 1;
			}
		} 
		        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}