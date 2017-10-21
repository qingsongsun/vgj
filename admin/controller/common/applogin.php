
<?php
class ControllerCommonApplogin extends Controller {
	private $error     = array();
	private $json_data = array();
	private $json      = array();

	public function index() {
		$this->load->model('user/user');

		$this->json_data = json_decode(file_get_contents("php://input"), true);

		if ($this->user->isLogged() ) {

		}

		// 获取openid，有则免登录，无则绑定

		if (isset($this->json_data['openid'])) {
			if ($this->model_user_user->getUserIdByOpenid($this->json_data['openid'])) {
				$user_id=$this->model_user_user->getUserIdByOpenid($this->json_data['openid']);
				$this->session->data['user_id']=$user_id;
			}
		}

		if (ONLY_SCAN_MEMBER=='ON') {
			$this->json['only_scan_member']=true;
		}

		if (isset($this->json_data) && $this->validate()) {
			$this->json['login']=true;

			$this->load->model('user/user');
			$this->load->model('catalog/product');

			$this->json['user_id']=$this->user->getId();
			$this->json['session_id']=$this->session->getId();
			$this->session->data['user_id']=$this->user->getId();

			$this->json['product_option_value']=array();

			if (isset($this->json_data['product_id'])) {
				$this->json['product_option_value']=$this->model_catalog_product->getProductOptions($this->json_data['product_id']);
				$this->json['product_name']=$this->model_catalog_product->getProductDescriptions($this->json_data['product_id']);
			}

			// 给app刷仓库列表，只有user有权限的仓库才会返回
			$this->load->model('catalog/repository');
			$this->json['repository']=$this->model_catalog_repository->getRepositoriesByUser(array(
				'user_id'=>$this->user->getId(),
				));
			$this->json['all_repository']=$this->model_catalog_repository->getRepositories();

			$this->json['last_operation_list']='';
			$this->json['message_id']=$this->json_data['message_id'];

			// 第一次登陆的时候绑定openid
			if (isset($this->json_data['openid'])) {
				$this->model_user_user->bindOpenid($this->json_data['openid'],$this->user->getId());
			}

			// if (INSTRUMENT_SWITCH=='ON') {
			// 	/**
			// 	 * 记录员工号
			// 	 */

			// 	if (isset($this->json_data['staff_code'])) {
			// 		$this->session->data['staff_code']=$this->json_data['staff_code'];
			// 	}
			// }

			$this->response->addHeader('Content-Type:application/json');
			$this->response->setOutput(json_encode($this->json));

		}
	}

	/**
	 * 入库
	 */
	public function addProductRepository(){

		$this->json_data = json_decode(file_get_contents("php://input"),true);


		$this->load->model('catalog/repository');

		if (isset($this->json_data)) {
			$this->model_catalog_repository->createRepository_pd_byId(array(
				'product_id'=>$this->json_data['product_id'],
				'product_option_value_id'=>$this->json_data['product_option_value_id'],
				'repository_id'=>$this->json_data['repository_id'],
				'product_num'=>0
				));

			// if (INSTRUMENT_SWITCH=='ON') {
			// 	if ($this->model_catalog_repository->addRepository_pd_byId(array(
			// 		'user_id'=>$this->user->getId(),
			// 		'product_option_value_id'=>$this->json_data['product_option_value_id'],
			// 		'repository_id' => $this->json_data['repository_id'],
			// 		'product_id' => $this->json_data['product_id'],
			// 		'product_num' => $this->json_data['product_num'],
			// 		'transfer_error'=>$this->json_data['transfer_error'],
			// 		'staff_code'=>$this->session->data['staff_code']
			// 		))=='-1') {
			// 		$this->json['num_error']=true;
			// 	}
			// }else{
				if ($this->model_catalog_repository->addRepository_pd_byId(array(
					'user_id'=>$this->user->getId(),
					'product_option_value_id'=>$this->json_data['product_option_value_id'],
					'repository_id' => $this->json_data['repository_id'],
					'product_id' => $this->json_data['product_id'],
					'product_num' => $this->json_data['product_num'],
					'transfer_error'=>$this->json_data['transfer_error'],
					))=='-1') {
					$this->json['num_error']=true;
				}
			// }
			$this->model_catalog_repository->clearRepository_pd();
			$this->json['result']=0;
		}


		/**
		 * 记录员工号细化到具体的仪器
		 */

		// if (isset($this->session->data['staff_code'])) {

		// }

		//

		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($this->json));


	}

	public function login(){
		$this->json_data = json_decode(file_get_contents("php://input"),true);

		//	通过openid获取user_id，之后产生token
		$this->load->model('user/user');
		if (isset($this->json_data['openid'])&&$this->json_data['openid']!='') {

			if ($this->model_user_user->getUserIdByOpenid($this->json_data['openid'])) {
				$user_id=$this->model_user_user->getUserIdByOpenid($this->json_data['openid']);

				$this->session->data['user_id']=$user_id;
				$this->session->data['token']=md5(mt_rand());
				$this->json['token']=$this->session->data['token'];
				$this->json['user_id']=$user_id;
			}else{
				$this->json['error']=true;
			}
		}else{
			// 通过账号密码登录之后产生token
			if (isset($this->json_data)&&$this->validate()) {
				$this->session->data['token']=md5(mt_rand());
				$this->json['user_id']=$this->user->getId();
				$this->json['token']=$this->session->data['token'];
			}
		}
		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($this->json));
	}

	public function appEditPassword(){
		$this->load->model('user/user');

		$this->model_user_user->editPassword($_POST['user_id'], $_POST['new_password']);
		$this->json['result']=0;

		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($this->json));
	}

	public function getProduct(){
		$this->json_data = json_decode(file_get_contents("php://input"),true);
		$this->session->data['user_id']=$this->user->getId();
		if (isset($this->json_data) && $this->validate()) {
			$this->session->data['user_id']=$this->user->getId();
		}

		$this->load->model('catalog/product');
		$this->load->model('catalog/repository');

		$this->json['product_name']=$this->model_catalog_product->getProductDescriptions($this->json_data['product_id']);

		$repositories=$this->model_catalog_repository->getRepositorybyUid($this->session->data);
		$this->json['repositories']=$repositories;

		foreach ($repositories as $repository_info) {

			$this->json['repository']=$repository_info;

			$repository_id=$repository_info['repository_id'];
			$this->session->data['repository_id']=$repository_id;

			$data=array(
				'repository_id'=>$repository_id,
				'product_id'=>$this->json_data['product_id'],
				);

			$this->json['product_option_value']=$this->model_catalog_product->getProductOptionsForApp($data);

			if (empty($this->json['product_option_value'])) {
				$this->json['has_option']=false;
				$this->json['product']=$this->model_catalog_product->getProductForApp($data);
				$this->json['products'][$repository_id]=$this->model_catalog_product->getProductForApp($data);

			}else{
				$this->json['has_option']=true;
			}
		}
		$this->json['product_customer']=$this->model_catalog_product->getProductForCustomer($this->json_data['product_id']);
		$this->json['pov_wx']=$this->model_catalog_product->getProductOptions($this->json_data['product_id']);
		$this->json['all_repository']=$this->model_catalog_repository->getRepositories();
		$this->json['switch_user']=$this->session->data['switch_user'];

		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($this->json));
	}

	public function appCheckout(){
		$this->json_data = json_decode(file_get_contents("php://input"),true);

		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($this->json));
	}


	protected function validate() {
		if (!isset($this->json_data['username']) || !isset($this->json_data['password']) || !$this->user->login($this->json_data['username'], $this->json_data['password'])) {

			$this->json['login']='false';
		}else{
			return !$this->error;
		}
	}

	public function bindOpenid(){
		$this->json_data=json_decode(file_get_contents("php://input"),true);
		$this->load->model('user/user');

		if (isset($this->json_data['openid'])) {

			if ($this->model_user_user->getUserIdByOpenid($this->json_data['openid'])) {
				$user_id=$this->model_user_user->getUserIdByOpenid($this->json_data['openid']);
				$this->session->data['user_id']=$user_id;
				$this->json['login']=true;
			}else{
				$this->model_user_user->bindOpenid($this->json_data['openid'],$this->user->getId());
			}

		}else{
			$this->json['success']=true;

		}
		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($this->json));
	}

	public function unbindOpenid(){
		$this->json_data=json_decode(file_get_contents("php://input"),true);
		$this->load->model('user/user');

		$this->model_user_user->unbindOpenid($this->user->getId());

		$this->json['webstore_success']=true;
		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($this->json));
	}


}