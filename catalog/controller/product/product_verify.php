<?php
/**
* 防伪验证功能 api
*/
class ControllerProductProductverify extends Controller
{
	private $error=array();

	/**
	 * 初始化数据
	 * @param [type] $registry [description]
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');
		$this->load->model('account/customer');
		$this->load->model('checkout/order');
		$this->load->model('journal2/checkout');
	}

	/**
	 * 处理防伪验证的逻辑
	 * @return [type] [description]
	 */
	public function registry_product(){

		$json=array();

		// 判断是否登录过
		if (!$this->customer->isLogged()&&!isset($this->session->data['customer_id'])&&!isset($this->request->get['customer_id'])) {

			// 未登录则引导用户登录，判断是否为本站的会员
			// 判断浏览器
			if (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) {
				// 1、如果是微信浏览器打开，则获取openid校验身份：是会员直接登录，不是会员则引导绑定
				$json['noWx']=false;

			}else{
				// 2、如果是普通浏览器打开，则跳到登录页面
				$json['customer_id']=$this->customer->getId();
				$json['noWx']=true;
			}

		}else{
			if (isset($this->request->get['customer_id'])) {
				$customer_id=$this->request->get['customer_id'];
				$json['customer_id']=$this->request->get['customer_id'];
			}else if (isset($this->session->data['customer_id'])) {
				$json['customer_id']=$this->session->data['customer_id'];
				$customer_id=$this->session->data['customer_id'];
			}else{
				$json['customer_id']=$this->customer->getId();
				$customer_id=$this->customer->getId();
			}
			$json['customer_id']=$this->customer->getId();
			//注意：产品只能注册一次 判断该产品是否被注册过

			$verify_info=$this->model_catalog_product->getVerifyById($this->request->get['product_verify_id']);

			$json['verify_info']=$verify_info;

			if ($verify_info['registry_status']) {
				$json['registry_status']=true;

			}else{

				//已经登陆过了,且该产品未被注册 直接帮用户注册该产品：注册商品的流程就直接走下单的逻辑

				$order_data=$this->registryOrder($this->request->get['product_id'],$this->request->get['customer_id']);

				// 1、写入历史订单，订单状态为已完成，并且返回成功
		    	// 2、修改该产品的相关状态
		        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'],5,"防伪验证专用");
	        	// 修改注册状态，写入注册者的id
	        	$this->model_catalog_product->registryUpdate(array(
	        		'registry_status'=>true,
	        		'product_verify_id'=>$this->request->get['product_verify_id'],
	        		'customer_id'=>$customer_id,
	        		'date'=>time()
	        		));
	        	if ($order_data!='') {
	        		$json['success']=true;
	        	}
			}
		}

		$this->response->addHeader('application/json');
		$this->response->setOutput(json_encode($json));

	}
	/**
	 * 注册产品，自动为用户生成为一个订单
	 * @param  [type] $product_id [description]
	 * @return [type]             [description]
	 */
	public function registryOrder($product_id,$customer_id=null){

			if ($this->customer->isLogged()) {
				$customer_id=$this->customer->getId();
			}

			$product_info=$this->model_catalog_product->getProduct($product_id);

			// 清空购物车
			$this->cart->clear();

			// 加入购物车 数量默认值1 选项待定
			$this->cart->add($product_id);
			// 生成空订单
			$order_data = $this->model_journal2_checkout->getOrder();
			// 记录用户信息
			$customer_info = $this->model_account_customer->getCustomer($customer_id);
			$order_data['customer_id'] = $customer_id;
			$order_data['customer_group_id'] = $customer_info['customer_group_id'];
            $order_data['firstname'] = $customer_info['firstname'];
            $order_data['lastname'] = $customer_info['lastname'];
            $order_data['email'] = $customer_info['email'];
            $order_data['telephone'] = $customer_info['telephone'];
            $order_data['fax'] = $customer_info['fax'];
            if (Front::$IS_OC2) {
                $order_data['custom_field'] = version_compare(VERSION, '2.1', '>=') ? json_decode($customer_info['custom_field'], true) : unserialize($customer_info['custom_field']);
            }
        	// order totals
	        $order_data['totals'] = array();
	        $total = 0;
	        $taxes = $this->cart->getTaxes();
	        if (Front::$IS_OC2) {
	            $this->load->model('extension/extension');
	            $results = $this->model_extension_extension->getExtensions('total');
	        } else {
	            $this->load->model('setting/extension');
	            $results = $this->model_setting_extension->getExtensions('total');
	        }
	        $sort_order = array();
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
	        $order_data['total'] = $total;
	    	// order products
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
	                    'value'                   => Front::$IS_OC2 ? $option['value'] : $option['option_value'],
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
	        // 将订单信息写入空订单
	        $this->model_journal2_checkout->setOrderData($order_data);
	        $this->model_journal2_checkout->save();
	        // 将订单返回
	        return $order_data;

	}

	public function success(){

		$this->load->language('product/product_verify');

		// if ($this->session->data['register_flag']) {
		// 	$defaults=array(
		// 		CURLOPT_HEADER=>0,
		// 		CURLOPT_USERAGENT=>'Mozilla/5.0 (iPhone; CPU iPhone OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/9B176 MicroMessenger/4.3.2',
		// 		CURLOPT_URL=>HTTP_SERVER.'index.php?route=product/product_verify/registry_product&product_verify_id='.$this->session->data['product_verify_id'].'&product_id='.$this->session->data['product_id'].'&verify='.$this->session->data['verify'].'&customer_id='.$customer_id,
		// 		CURLOPT_FRESH_CONNECT=>1,
		// 		CURLOPT_RETURNTRANSFER=>1,
		// 		CURLOPT_FORBID_REUSE=>1,
		// 		CURLOPT_TIMEOUT=>5,
		// 		CURLOPT_SSL_VERIFYPEER=>0,
		// 		CURLOPT_SSL_VERIFYHOST=>0,
		// 		// CURLOPT_POST=>1,
		// 		// CURLOPT_POSTFIELDS=>http_build_query($post_data),
		// 	);
		// 	$ch=curl_init();
		// 	curl_setopt_array($ch,$defaults);

		// 	$result=json_decode(curl_exec($ch),true);
		// 	curl_close($ch);
		// 	$this->log->write($result);
		// 	$this->log->write($result['success']);
		// 	$this->log->write('看看这个有没有啊啊啊啊啊啊');
		// }


		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			// Add to activity log
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'order_id'    => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_account', $activity_data);
			} else {
				$activity_data = array(
					'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
					'order_id' => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_guest', $activity_data);
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
			unset($this->session->data['verify']);
			unset($this->session->data['product_id']);
			unset($this->session->data['product_verify_id']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->session->data['redirect'];

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}
}