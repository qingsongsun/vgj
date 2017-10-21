<?php
/**
* 后台防伪验证码管理系统
*/
class ControllerCatalogProductverify extends Controller
{
	private $error=array();

	public function index(){
		$this->load->model('catalog/product_verify');
		$this->load->model('catalog/product');
		$this->load->language('catalog/product');
		if (isset($this->request->post['download'])) {
			$download=$this->request->post['download'];
			$this->getList($download);
		}else{
			$this->getList();
		}
	}
	/**
	 * 根据数量自动生成防伪验证码
	 */
	public function add(){
		include DIR_SYSTEM."pub/phpqrcode/phpqrcode.php";//引入PHP QR库文件，切忌重复多次引入
		$this->load->model('catalog/product_verify');
		$this->load->model('catalog/product');
		$this->load->language('catalog/product');

		$json=array();

		$product_info=$this->model_catalog_product->getProduct($this->request->get['product_id']);

		$last_sn=$this->model_catalog_product_verify->getLastVerifyByPid($this->request->get['product_id'])['sn'];

		for ($i=1; $i <=$this->request->get['quantity']; $i++) {
			$sn=$i+$last_sn;
			$data=array(
				'product_id'=>$this->request->get['product_id'],
				'sn'=>$i+$last_sn,
				'code'=>mt_rand(1,time()),
				'product_option_value_id'=>'',
				'customer_id'=>''
			);

			$product_verify_id=$this->model_catalog_product_verify->add($data);

			$verify=$sn."_".$data['code'];

			$this->getQrcode($product_info['model'],$this->request->get['product_id'],$verify,$data['sn']);
			$qrcode=DIR_APPLICATION.'view/image/uniqueQrcode/'.$product_info['model']."_".$sn.'.png';

			chmod($qrcode,0777);
		}

		$json['model']=$product_info['model'];
		$json['get']=$this->request->get;
		if ($product_verify_id) {
			$this->session->data['success']=$this->language->get('防伪验证码生成成功');
			$json['product_verify_id']=$product_verify_id;
			$json['success']=true;
		}else{
			$this->session->data['error']=$this->language->get('防伪验证码生成失败');
			$json['success']=false;
		}

		$this->response->addHeader('application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function edit(){
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');
		$this->load->model('catalog/product_verify');

		$this->getForm();

	}

	public function delete() {

		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		$this->load->model('catalog/product_verify');

		$this->load->model('catalog/repository');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {

			foreach ($this->request->post['selected'] as $product_verify_id) {

				// 删除 关联删除二维码图片的动作
				$product_model=$this->model_catalog_product->getProductModelById($this->request->get['product_id']);
				$verify_info=$this->model_catalog_product_verify->getVerifyById($product_verify_id);
				$this->model_catalog_product_verify->deleteVerifyById($product_verify_id);
				$qrcode=DIR_APPLICATION.'view/image/uniqueQrcode/'.$product_model."_".$verify_info['sn'].'.png';
				chmod($qrcode,777);
				unlink($qrcode);

			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['product_id'])) {
				$url .= '&product_id='.$this->request->get['product_id'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product_verify')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function getQrcode($product_model,$product_id,$verify,$sn){
		$value=HTTP_CATALOG."index.php?route=product/product&product_id=".$product_id."&verify=".$verify;

		$errorCorrectionLevel = "L";
		$matrixPointSize = "9";
		$margin='2';
		$outfile=DIR_APPLICATION.'view/image/uniqueQrcode/'.$product_model."_".$sn.'.png';
		$saveandprint=true;

		if (!file_exists($outfile)) {
			QRcode::png($value, $outfile, $errorCorrectionLevel, $matrixPointSize,true);
		}else{
			// 如果存在，则先删除以前的，再建新的
			unlink($outfile);
			QRcode::png($value, $outfile, $errorCorrectionLevel, $matrixPointSize,true);
			// return false;
		}
	}

	public function getUrl($product_id,$verify){
		return HTTP_CATALOG."index.php?route=product/product&product_id=".$product_id."&verify=".$verify;
	}

	protected function getList($download=false) {

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';


		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('防伪管理'),
			'href' => $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['delete'] = $this->url->link('catalog/product_verify/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['downloadQrcode'] = $this->url->link('catalog/product_verify/downloadQrcode', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['getQrcodeTicket'] = $this->url->link('catalog/product/getQrcodeTicket', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'sort'            => $sort,
			'order'           => $order,

		);


		$data['download']=$download;
		if (!$download) {
			$filter_data=array(
				'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'           => $this->config->get('config_limit_admin')
			);
		}

		// 只能看这个商品的所有防伪码
		$filter_data['product_id']=$this->request->get['product_id'];

		$this->load->model('tool/image');

		$product_total = $this->model_catalog_product_verify->getTotalProducts($filter_data);
		$results = $this->model_catalog_product_verify->getProducts($filter_data);


		foreach ($results as $result) {

			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}


			$this->load->model('catalog/repository');
			$this->load->model('sale/customer');

			$data['product_id']=$this->request->get['product_id'];
			$data['products'][] = array(
				'product_verify_id' => $result['product_verify_id'],
				'registry_status'=>$result['registry_status'],
				'scan_counter'=>$result['scan_counter'],
				'sn'=>$result['sn'],
				'code'=>$result['code'],
				'customer_phone'=>$this->model_sale_customer->getCustomer($result['customer_id'])['email'],
				'date'=>date('Y-m-d H:i:s',$result['date']),
				'ip'=>$result['ip'],

				'creator' 	 => $result['username'],
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
				'special'    => $special,
				'quantity'   => $result['quantity'],
				'repo_num'	 => $this->model_catalog_repository->getRepository_pd_numbyprodId($result['product_id']),
				'creator_id'=>$result['creator_id'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'       => $this->url->link('catalog/product_verify/edit', 'token=' . $this->session->data['token'] . '&product_verify_id=' . $result['product_verify_id'] . $url, 'SSL'),
				'creat_verify'=>$this->url->link('catalog/product_verify/add', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&quantity='.$result['quantity'] . $url, 'SSL'),
				'verify'=>$this->url->link('catalog/product_verify/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL'),
				'scenarios'   => $scenarios,
				'getQrcodeTicket'=>$this->url->link('catalog/product/getQrcodeTicket','token='.$this->session->data['token'].'&product_id='.$result['product_id'].$url,'SSL')
			);
		}


		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_creator']	= $this->language->get('column_creator');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		// huwen added
		$data['column_repo_num'] = $this->language->get('column_repo_num');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_creator'] = $this->language->get('entry_creator');
		$data['entry_name'] = $this->language->get('entry_name');
		// huwen added for barcode
		$data['entry_model_barcode']=$this->language->get('entry_model_barcode');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_price'] = $this->language->get('entry_price');
		// huwen added
		$data['entry_repo_num'] = $this->language->get('entry_repo_num');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['error'])) {
			$data['error_warning']=$this->session->data['error'];

			unset($this->session->data['error']);
		}else{
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_creator'])) {
			$url .= '&filter_creator'.urlencode(html_entity_decode($this->request->get['filter_creator'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		// huwen added
		if (isset($this->request->get['filter_repo_num'])) {
			$url .= '&filter_repo_num=' . $this->request->get['filter_repo_num'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_price'] = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		// huwen added
		$data['sort_repo_num'] = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . '&sort=p.repo_num' . $url, 'SSL');

		$data['sort_status'] = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_creator'])) {
			$url .= '&filter_creator='.urlencode(html_entity_decode($this->request->get['filter_creator'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}
		// huwen added
		if (isset($this->request->get['filter_repo_num'])) {
			$url .= '&filter_repo_num=' . $this->request->get['filter_repo_num'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_creator'] = $filter_creator;
		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_price'] = $filter_price;
		$data['filter_quantity'] = $filter_quantity;
		// huwen added
		$data['filter_repo_num'] = $filter_repo_num;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		// Wenliang added, for display the scenario list to be deleted.
		$this->load->model('catalog/product');
		$data['scenarios'] = $this->model_catalog_product->getScenarios();

		// Wenliang added, for language displays
		$data['button_set_product_scenario'] = $this->language->get('button_set_product_scenario');
		$data['button_delete_scenario'] = $this->language->get('button_delete_scenario');
		$data['button_add_scenario'] = $this->language->get('button_add_scenario');
		$data['entry_add_scenario'] = $this->language->get('entry_add_scenario');
		$data['entry_scenario'] = $this->language->get('entry_scenario');
		$data['entry_delete_scenario'] = $this->language->get('entry_delete_scenario');
		$data['entry_product_name'] = $this->language->get('entry_product_name');
        $data['entry_set_product_scenario'] = $this->language->get('entry_set_product_scenario');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['downloadQrcodeUrl'] = $this->url->link('catalog/product_verify/getUrls', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->response->setOutput($this->load->view('catalog/product_verify_list.tpl', $data));
	}
	protected function getForm(){
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['product_verify_id']=$this->request->get['product_verify_id'];

		$verify_info=$this->model_catalog_product_verify->getVerifyById($this->request->get['product_verify_id']);

		$data['sn']=$verify_info['sn'];


		$data['model']=$this->model_catalog_product->getProductModelById($verify_info['product_id']);


		// if (isset($this->request->post['num'])) {
		// 	$data['num'] = $this->request->post['num'];
		// } elseif (!empty($product_info)) {
		// 	$data['num'] = $product_info['num'];
		// } else {
		// 	$data['num'] = '';
		// }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['product_id'])) {
			$data['action'] = $this->url->link('catalog/product_verify/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/product_verify/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		// if (isset($this->request->get['product_id'])) {

		// 	$this->load->model('catalog/product');

		// 	$outfile=DIR_APPLICATION.'view/image/uniqueQrcode/'.$this->request->get['product_id'].'.png';

		// 	if ($this->model_catalog_product->getProductModelById($this->request->get['product_id'])) {
		// 		$product_model=$this->model_catalog_product->getProductModelById($this->request->get['product_id']);
		// 		$newname=DIR_APPLICATION.'view/image/uniqueQrcode/'.$product_model."_".$verify.'.png';

		// 		chmod($outfile,777);
		// 		rename($outfile,$newname);
		// 		$data['issetQrcode']=file_exists($newname);
		// 		$data['product_model']=$product_model;
		// 	}else{
		// 		$data['issetQrcode']=file_exists($outfile);
		// 	}
		// 	$data['product_id']=$this->request->get['product_id'];
		// }

		$this->response->setOutput($this->load->view('catalog/product_verify_form.tpl', $data));
	}

	// 导出二维码图片
	// public function downloadQrcode(){
	// 	$this->load->language('catalog/product');
	// 	$this->document->setTitle($this->language->get('heading_title'));
	// 	$this->load->model('catalog/product');

	// 	if (isset($this->request->post['selected'])&&$this->request->post['selected']!=null) {

	// 		$zip_file=DIR_APPLICATION.'view/image/uniqueQrcode/qrcode.zip';
	// 		$zip = new ZipArchive();
	// 		$zip->open($zip_file,ZipArchive::OVERWRITE);
	// 		$zip->addEmptyDir('images');


	// 		foreach ($this->request->post['selected'] as $product_id) {
	// 			$this->load->model('catalog/product');
	// 			$product_model=$this->model_catalog_product->getProductModelById($product_id);
	// 			$qrcode=DIR_APPLICATION.'view/image/uniqueQrcode/'.$product_model.'.png';
	// 			$file_name = $product_model.'.png';
	// 			$file_dir = DIR_APPLICATION.'view/image/uniqueQrcode/';

	// 			$fileData=file_get_contents($file_dir.$file_name);

	// 			$zip->addFromString('images/'.$file_name,$fileData);

	// 		}

	// 		$zip->close();
	// 		$this->download($zip_file);
	// 	}else{
	// 		echo "<script language='javascript'>alert('请选择要导出的二维码')</script>";
	// 		$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] , 'SSL'));
	// 	}

	// }
	// 下载zip功能
	public function download($zip_file){

		if (!file_exists($zip_file)) {
			echo "文件找不到";
			exit;
		} else {
			$file = fopen($zip_file,"r");
			Header("Content-type: application/octet-stream");
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".filesize($zip_file));
			Header("Content-Disposition: attachment; filename=qrcode.zip");
			while (!feof($file)) {
				echo fread($file,filesize($zip_file));
			}
			fclose($file);
			return;
		}
	}

	// 打印二维码小票
	public function getQrcodeTicket(){
		$this->load->model('catalog/product');
		if (isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');
			$product_id=$this->request->get['product_id'];
			$product_model=$this->model_catalog_product->getProductModelById($product_id);
			$product_info=$this->model_catalog_product->getProduct($product_id);

			$data=array(
				'model'=>$product_model,
				'partName'=>$product_info['ticket_partName'],
				'styleCode'=>$product_info['ticket_styleCode'],
				'size'=>$product_info['ticket_size'],
				'color'=>$product_info['ticket_color'],
				'fabric'=>$product_info['ticket_fabric'],
				'rank'=>$product_info['ticket_rank'],
				'address'=>$product_info['ticket_address'],
				'price'=>$this->currency->format($product_info['price'])
				);
		}
		$this->response->setOutput($this->load->view('catalog/qrcode_ticket.tpl', $data));
	}

	public function getHtml(){
		$json=array();

		$json_data=$this->request->post['table'];

		$json_data=html_entity_decode($json_data);

		$json['data']=$json_data;

		$html_name=DIR_APPLICATION.'verify.html';
		// 先清空
		file_put_contents($html_name, '');
		// 再写入
		$result=file_put_contents($html_name,$json_data,0777);

		// 然后下载
		// $this->downloadVerify($html_name);

		$json['result']=$result;

		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function downloadQrcode(){
		// $download=true;
		// $this->getList($download);
		$html_name=DIR_APPLICATION.'verify.html';
		$this->downloadVerify($html_name);
	}

	public function downloadVerify($download_file){

		if (!file_exists($download_file)) {
			echo "文件找不到";
			exit;
		} else {
			$file = fopen($download_file,"r");
			Header("Content-type: application/octet-stream");
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".filesize($download_file));
			Header("Content-Disposition: attachment; filename=verify.html");
			while (!feof($file)) {
				echo fread($file,filesize($download_file));
			}
			fclose($file);
			return;
		}
	}

	public function getUrls(){
		$product_id=$this->request->get['product_id'];
		$this->load->model('catalog/product_verify');
		$verify_arr=$this->model_catalog_product_verify->getVerifyByPid($product_id);
		$data=array();
		foreach ($verify_arr as $verify_info) {
			$verify=$verify_info['sn']."_".$verify_info['code'];
			$data['products'][]=array('url'=>HTTP_CATALOG."index.php?route=product/product&product_id=".$product_id."&verify=".$verify);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('catalog/verify_urls.tpl',$data));

	}

}