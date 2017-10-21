<?php
class ControllerAccountPassword extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/password', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		if (isset($this->session->data['switched_user'])) { // not allowed for switched user
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}
		
		$this->load->model('account/customer');
		
		$this->load->language('account/password');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('account/customer_group');
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());
		//for only VIP customer can select the password_type
		$data['user_group'] = $customer_group_info['name'];

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            if ($this->request->post['password_type'] == "login_password") {
			    $this->model_account_customer->editPassword($this->customer->getEmail(), $this->request->post['password']);
		    } else {
				$this->model_account_customer->editPaymentPassword($this->customer->getId(), $this->request->post['password']);
			}

			
			$this->session->data['success'] = $this->language->get('text_success');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('password', $activity_data);

			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}
		
		
		if (isset($this->request->post['password_type'])) {
			$data['password_type'] = $this->request->post['password_type'];
		} else {
			$data['password_type'] = "login_password";
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/password', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_password'] = $this->language->get('text_password');

		// Wenliang added, for old password
		$data['text_pay_password']   = $this->language->get('text_pay_password');
		$data['text_login_password'] = $this->language->get('text_login_password');
		$data['entry_old_password']  = $this->language->get('entry_old_password');
		//      	$data['entry_old_pay_password'] = "输入旧密码";
		//     		$data['entry_pay_password'] = $this->language->get('entry_password');
		//      	$data['entry_pay_confirm'] = $this->language->get('entry_confirm');
		$data['entry_password']      = $this->language->get('entry_password');
		$data['entry_confirm']       = $this->language->get('entry_confirm');
		$data['text_password_type']  = $this->language->get('text_password_type');
		$data['entry_password_type'] = $this->language->get('entry_password_type');
		$data['text_forgotten']      = $this->language->get('text_forgotten');
		$data['forgotten']           = $this->url->link('account/forgotten', '', 'SSL');

		if (isset($this->error['old_password'])) {
			$data['error_old_password'] = $this->error['old_password'];
		} else {
			$data['error_old_password'] = '';
		}
		if (isset($this->request->post['old_password'])) {
			$data['old_password'] = $this->request->post['old_password'];
		} else {
			$data['old_password'] = '';
		}
/*		
		if (isset($this->error['old_pay_password'])) {
			$data['error_old_pay_password'] = $this->error['old_pay_password'];
		} else {
			$data['error_old_pay_password'] = '';
		}
		if (isset($this->request->post['old_pay_password'])) {
			$data['old_pay_password'] = $this->request->post['old_pay_password'];
		} else {
			$data['old_pay_password'] = '';
		}		
		if (isset($this->request->post['pay_confirm'])) {
			$data['pay_confirm'] = $this->request->post['pay_confirm'];
		} else {
			$data['pay_confirm'] = '';
		}
		if (isset($this->request->post['pay_password'])) {
			$data['pay_password'] = $this->request->post['pay_password'];
		} else {
			$data['pay_password'] = '';
		}
*/

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');



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
		
		if (isset($this->error['pay_password'])) {
			$data['error_pay_password'] = $this->error['pay_password'];
		} else {
			$data['error_pay_password'] = '';
		}

		if (isset($this->error['pay_confirm'])) {
			$data['error_pay_confirm'] = $this->error['pay_confirm'];
		} else {
			$data['error_pay_confirm'] = '';
		}		

		$data['action'] = $this->url->link('account/password', '', 'SSL');

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

		$data['back'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/password.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/password.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/password.tpl', $data));
		}
	}

	protected function validate() {	
        if (empty($this->request->post['old_password'])) {
            $this->error['old_password'] =  $this->language->get('error_old_password');
        } else {
			if ($this->request->post['password_type'] == "login_password") {
			    if (!$this->customer->hasPassword($this->request->post['old_password'])) {
	                $this->error['old_password'] =  $this->language->get('error_old_password');				
				}	
			} else {
			    if (!$this->customer->hasPaymentPassword($this->request->post['old_password'])) {
					$this->error['old_password'] =  $this->language->get('error_old_password');
				}				
			}
		}
        
        if (empty($this->request->post['password'])) {
            $this->error['password'] =  $this->language->get('error_password');
        } else {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}		
		}

        if (empty($this->request->post['confirm'])) {
            $this->error['confirm'] = $this->language->get('error_confirm_empty');
        } else {
		    if ($this->request->post['confirm'] != $this->request->post['password']) {
			    $this->error['confirm'] = $this->language->get('error_confirm_inconsistent');
		    }				
		}
		
		return !$this->error;
	}
}