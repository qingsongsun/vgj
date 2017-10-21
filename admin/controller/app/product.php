<?php
/**
* app创建商品的接口
*/
class ControllerAppProduct extends Controller{
	private $error     = array();
	private $json_data = array();
	private $json      = array();

	public function index(){
		$this->json_data=json_decode(file_get_contents("php://input"),true);

		if ($this->user->isLogged()) {

		}

		if (isset($this->json_data) && $this->validate()) {
			$this->add();
			// $this->json=$this->json_data;
			$this->response->addHeader('Content-Type:application/json');
			$this->response->setOutput(json_encode($this->json));
		}
	}

	protected function validate() {
		if (!isset($this->json_data['username']) || !isset($this->json_data['password']) || !$this->user->login($this->json_data['username'], $this->json_data['password'])) {

			$this->json['error_login']='0';
		}

		return !$this->error;
	}

	public function add(){
		$this->load->language('catalog/product');
		$this->load->model('catalog/product');
		if (isset($this->json_data)&&$this->validateData($this->json_data)) {
			$prod_info['creator_id'] = $this->user->getId();
			$prod_info['product_description'][$this->config->get('config_language_id')]=array(
				'name'=>$this->json_data['product_name']
				);
			$prod_info['model']=$this->json_data['product_model'];
			$prod_info['price']=$this->json_data['product_price'];

			// 默认第一张图为主图
			$prod_info['image']=$this->json_data['image_path'][0];


			// 删掉第一个元素，并且构造下辅图数组
			array_shift($this->json_data['image_path']);
			foreach ($this->json_data['image_path'] as $product_image) {
				$prod_info['product_image'][]=array(
					'image'=>$product_image,
					'sort_order'=>''
					);
			}

			// 默认值
			$prod_info['subtract']='1';
			$prod_info['shipping']='1';
			$prod_info['product_store']=array(
				'0'=>'0'
				);
			$prod_info['status']='1';
			$last_product_id=$this->model_catalog_product->addProduct($prod_info);
			$this->log->write('看看这个product_id');
			$this->log->write($last_product_id);
			// $last_product_id=$this->model_catalog_product->getLastProductId();

			$this->getQrcode($this->json_data['product_model'],$last_product_id);
			$qrcode=DIR_APPLICATION.'view/image/qrcode/'.$this->request->post['model'].'.png';
			chmod($qrcode,777);

			$this->json['prod_info']=$prod_info;
			$this->json['json_data']=$this->json_data;
			$this->json['product_id']=$last_product_id;
		}
			$this->response->addHeader('Content-Type:application/json');
			$this->response->setOutput(json_encode($this->json));
	}

	// huwen added for qrcode
	public function getQrcode($product_model,$product_id){

		include DIR_SYSTEM."pub/phpqrcode/phpqrcode.php";//引入PHP QR库文件

		// $this->load->model('catalog/product');
		// $product_model=$this->model_catalog_product->getProductModelById($product_id);

		$value=HTTP_CATALOG."index.php?route=product/product&product_id=".$product_id;
		$this->log->write($value);
		$this->log->write('看看路径');
		$errorCorrectionLevel = "L";
		$matrixPointSize = "9";
		$margin='2';
		$outfile=DIR_APPLICATION.'view/image/qrcode/'.$product_model.'.png';
		$saveandprint=true;

		if (!file_exists($outfile)) {
			QRcode::png($value, $outfile, $errorCorrectionLevel, $matrixPointSize,true);
		}else{
			return false;
		}
	}

	public function validateData($data){
		$this->load->model('catalog/product');
		if ($this->model_catalog_product->getProductIdByModel($data['product_model'])) {
			$this->json['model_repeat']=true;
			$this->json['product_id']=null;
			return false;
		}else{
			$this->json['model_repeat']=false;
			return true;
		}
	}
}