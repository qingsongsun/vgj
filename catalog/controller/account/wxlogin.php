<?php
require_once (DIR_WXHELPER);
require_once (DIR_WXCONFIG);
class ControllerAccountWxlogin extends Controller {
	public function index(){

		//使用jsapi接口
		$jsApi = new JsApi_pub();
		//通过code获得openid
		if (!isset($_GET['code'])) {
			//触发微信返回code码
			$url = $jsApi->createOauthUrlForCode(DIR_WXCODE);
			Header("Location:$url");
			exit;
		} else {
			//获取code码，以获取openid
			$code = $_GET['code'];
			$jsApi->setCode($code);
			$openid = $jsApi->getOpenId();
		}
		$this->session->data['openid']=$openid;
		$this->load->model('app/address');

		$customer_id=$this->model_app_address->getCustomerIdByOpenid($openid);

		if ($customer_id) {
			$this->session->data['customer_id']=$customer_id;

			if (isset($this->session->data['verify'])) {

				$verify_url='';
				$verify_url.='&product_id='.$this->session->data['product_id'];
				$verify_url.='&verify='.$this->session->data['verify'];
				$this->session->data['register_flag']=true;
				$this->response->redirect($this->url->link('product/product','openid='.$openid.$verify_url, 'SSL'));
			}else{
				$this->response->redirect($this->url->link('account/account','openid='.$openid, 'SSL'));
			}

		}else{
			if (isset($this->session->data['verify'])) {
				$verify_url='';
				$verify_url.='&product_id='.$this->session->data['product_id'];
				$verify_url.='&verify='.$this->session->data['verify'];

				$this->response->redirect($this->url->link('account/wxbind','openid='.$openid.$verify_url, 'SSL'));
			}else{
				$this->response->redirect($this->url->link('account/wxbind','openid='.$openid, 'SSL'));
			}
		}
	}
}
