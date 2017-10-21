<?php
class ControllerCatalogProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		$this->getList();
	}


	public function getProductScenarios() {
	    $json = array();
	    $this->load->model('catalog/product');

		$scenario_result = $this->model_catalog_product->getScenariosByProductName($this->request->get['product_name']);


		$i = 0;
		if ($scenario_result->num_rows) {
			foreach ($scenario_result->rows as $scenario) {
				$json[$i] = $scenario['combination_name'];
				$i++;
			}
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addProductScenario() {
		if (isset($this->request->get['scenario_name']) && isset($this->request->get['product_name'])) {
			$this->load->model('catalog/product');

			$product = $this->request->get['product_name'];
			$product_id = 0;
			// product exist?
			$result = $this->model_catalog_product->getProductIdByName($product);
			if ($result->num_rows != 0) {
				$product_id = $result->row['product_id'];
			}
			if ($product_id) {
				$scenarios = explode(",", $this->request->get['scenario_name']);
			    foreach ($scenarios as $scenario) {
				    $this->model_catalog_product->addProductScenario($product_id, $scenario);
			    }
			}
		}
		$this->index();
	}


	public function addScenario() {
		if (isset($this->request->get['scenario_name'])) {
			$this->load->model('catalog/product');
			$this->model_catalog_product->addScenario($this->request->get['scenario_name']);

		}
		$this->index();
	}


	public function deleteScenario() {
		if (isset($this->request->get['scenario_name'])) {
			$this->load->model('catalog/product');
			$scenarios = explode(",", $this->request->get['scenario_name']);
			foreach ($scenarios as $scenario) {

				$this->model_catalog_product->deleteScenario($scenario);
			}

		}
		$this->index();
	}

	public function add() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//Zark added for the creator
			$prod_info = $this->request->post;
			$prod_info['creator_id'] = $this->user->getId();
			$last_product_id=$this->model_catalog_product->addProduct($prod_info);
			$this->log->write($last_product_id);
			// huwen added for qrcode  根据model来生成二维码
			// $last_product_id=$this->model_catalog_product->getLastProductId();
			$this->getQrcode($this->request->post['model'],$last_product_id);
			$qrcode=DIR_APPLICATION.'view/image/qrcode/'.$this->request->post['model'].'.png';
			chmod($qrcode,0777);

			// 如果meta_title为空则等于商品名称
			if (empty($this->request->post['product_description'][2]['meta_title'])) {
				$this->request->post['product_description'][2]['meta_title']=$this->request->post['product_description'][2]['name'];

			}
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

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
			// huwen added ,for repo_num
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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'ssl'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// 如果meta_title为空则等于商品名称
			if (empty($this->request->post['product_description'][2]['meta_title'])) {
				$this->request->post['product_description'][2]['meta_title']=$this->request->post['product_description'][2]['name'];

			}
			// 修改商品要关联修改二维码图片的名称 huwen mark
			$this->load->model('catalog/product');

			$product_model=$this->model_catalog_product->getProductModelById($this->request->get['product_id']);

			$oldname=DIR_APPLICATION.'view/image/qrcode/'.$product_model.'.png';
			$newname=DIR_APPLICATION.'view/image/qrcode/'.$this->request->post['model'].'.png';
			chmod($oldname,777);
			rename($oldname,$newname);

			$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);


			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');
		//Zark added for product-delete protection
		$this->load->model('catalog/repository');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				// 删除 关联删除二维码图片的动作huwen mark
				$this->load->model('catalog/product');
				$product_model=$this->model_catalog_product->getProductModelById($product_id);
				$qrcode=DIR_APPLICATION.'view/image/qrcode/'.$product_model.'.png';
				chmod($qrcode,777);
				unlink($qrcode);

				if($this->model_catalog_repository->getRepository_pd_numbyprodId($product_id) == 0){
					$this->model_catalog_product->deleteProduct($product_id);
				}
			}


			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {

		if (VERIFY_SWITCH=='ON') {
			$data['VERIFY_SWITCH']=true;
		}else{
			$data['VERIFY_SWITCH']=false;
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_creator'])) {
			$filter_creator = $this->request->get['filter_creator'];
		} else {
			$filter_creator = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		// huwen added
		if (isset($this->request->get['filter_repo_num'])) {
			$filter_repo_num = $this->request->get['filter_repo_num'];
		} else {
			$filter_repo_num = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['downloadQrcode'] = $this->url->link('catalog/product/downloadQrcode', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['downloadQrcodeUrl'] = $this->url->link('catalog/product/downloadQrcodeUrl', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['getQrcodeTicket'] = $this->url->link('catalog/product/getQrcodeTicket', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			// huwen added
			'filter_repo_num' => $filter_repo_num,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		// 考虑用户的角色，如果为 boss 则列出所有商品
	    // 如果为产品负责人，则列出其由其创建的商品
	    // 暂时关闭该功能，将所有人列入可以查看所有商品的集合里，并增加限制部分角色不能编辑详情的功能。只有创建者才可以编辑该产品详情(admin除外)
	    $ADMIN_GROUP = array(1, 2,3,4,5,6);
	    $PROD_MANAGER_GROUP_ID = 3;

	    // admin 为超级用户，放开所有权限
	    if ($this->user->getId()==1) {
	    	$data['ADMIN']=true;
	    }
	    $data['user_id']=$this->user->getId();

	    if (in_array($this->user->getGroupId(), $ADMIN_GROUP)) {
	    	if (isset($filter_creator)&&!empty($filter_creator)) {
	    		$filter_data['filter_creator'] = $filter_creator;
	    	}
				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

				$results = $this->model_catalog_product->getProducts($filter_data);

		} else if ($this->user->getGroupId() == $PROD_MANAGER_GROUP_ID) {
			if (isset($filter_creator)&&!empty($filter_creator)) {
				if ($filter_creator == $this->user->getUserName()) {
					$filter_data['filter_creator'] = $filter_creator;
				} else {
					$filter_data['filter_creator'] = '=======';
				}
			}
			if (isset($this->request->get['uid'])) {
				$filter_data['uid'] = $this->request->get['uid'];
			} else {
				$filter_data['uid'] = $this->user->getId();
			}
			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);

		}

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}


			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $product_special['price'];

					break;
				}
			}

			// Wenliang added, for getting the product scenarios
			$scenario_result = $this->model_catalog_product->getProductScenarios($result['product_id']);

			$scenarios = '';
			if ($scenario_result->num_rows) {
				foreach ($scenario_result->rows as $scenario)
				  $scenarios .= $scenario['combination_name'] . '，';
				$scenarios = rtrim($scenarios,'，');
			} else {
				$scenarios = '';
			}

			$this->load->model('catalog/repository');

			$data['products'][] = array(
				'creator' 	 => $result['username'],
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
				'special'    => $special,
				'quantity'   => $result['quantity'],
				// huwen added
				'repo_num'	 => $this->model_catalog_repository->getRepository_pd_numbyprodId($result['product_id']),
				'creator_id'=>$result['creator_id'],//用来判断是否是该登录者创建的产品
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL'),
				'creat_verify'=>$this->url->link('catalog/product_verify/add', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&quantity='.$result['quantity'] . $url, 'SSL'),
				'verify'=>$this->url->link('catalog/product_verify', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL'),
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
		} else {
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

		$data['sort_name'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_price'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		// huwen added
		$data['sort_repo_num'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.repo_num' . $url, 'SSL');

		$data['sort_status'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

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

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

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

		$this->response->setOutput($this->load->view('catalog/product_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		// huwen added for barcode
		$data['entry_model_barcode']=$this->language->get('entry_model_barcode');
		$data['entry_barcode']=$this->language->get('entry_barcode');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_upc'] = $this->language->get('entry_upc');
		$data['entry_ean'] = $this->language->get('entry_ean');
		$data['entry_jan'] = $this->language->get('entry_jan');
		$data['entry_isbn'] = $this->language->get('entry_isbn');
		$data['entry_mpn'] = $this->language->get('entry_mpn');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_minimum'] = $this->language->get('entry_minimum');
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_date_available'] = $this->language->get('entry_date_available');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		// huwen added
		$data['entry_repo_num'] = $this->language->get('entry_repo_num');

		$data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_points'] = $this->language->get('entry_points');
		$data['entry_option_points'] = $this->language->get('entry_option_points');
		$data['entry_subtract'] = $this->language->get('entry_subtract');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_weight'] = $this->language->get('entry_weight');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_download'] = $this->language->get('entry_download');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_filter'] = $this->language->get('entry_filter');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_attribute'] = $this->language->get('entry_attribute');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_option_value'] = $this->language->get('entry_option_value');
		$data['entry_required'] = $this->language->get('entry_required');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_recurring'] = $this->language->get('entry_recurring');

		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_sku'] = $this->language->get('help_sku');
		$data['help_upc'] = $this->language->get('help_upc');
		$data['help_ean'] = $this->language->get('help_ean');
		$data['help_jan'] = $this->language->get('help_jan');
		$data['help_isbn'] = $this->language->get('help_isbn');
		$data['help_mpn'] = $this->language->get('help_mpn');
		$data['help_minimum'] = $this->language->get('help_minimum');
		$data['help_manufacturer'] = $this->language->get('help_manufacturer');
		$data['help_stock_status'] = $this->language->get('help_stock_status');
		$data['help_points'] = $this->language->get('help_points');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_filter'] = $this->language->get('help_filter');
		$data['help_download'] = $this->language->get('help_download');
		$data['help_related'] = $this->language->get('help_related');
		$data['help_tag'] = $this->language->get('help_tag');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_attribute_add'] = $this->language->get('button_attribute_add');
		$data['button_option_add'] = $this->language->get('button_option_add');
		$data['button_option_value_add'] = $this->language->get('button_option_value_add');
		$data['button_discount_add'] = $this->language->get('button_discount_add');
		$data['button_special_add'] = $this->language->get('button_special_add');
		$data['button_image_add'] = $this->language->get('button_image_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_recurring_add'] = $this->language->get('button_recurring_add');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_attribute'] = $this->language->get('tab_attribute');
		$data['tab_option'] = $this->language->get('tab_option');
		$data['tab_recurring'] = $this->language->get('tab_recurring');
		$data['tab_discount'] = $this->language->get('tab_discount');
		$data['tab_special'] = $this->language->get('tab_special');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_reward'] = $this->language->get('tab_reward');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_openbay'] = $this->language->get('tab_openbay');

		// huwen added for page diy
		$user_group_id=$this->user->getGroupId();
		$data['user_group_id']=$user_group_id;

		$this->load->model('user/user_group');

		$user_group_info = $this->model_user_user_group->getUserGroup($user_group_id);

		foreach ($user_group_info['permission']['diy'] as $key => $value) {
			$data[$value]=true;
		}

		// end

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		if (isset($this->error['date_available'])) {
			$data['error_date_available'] = $this->error['date_available'];
		} else {
			$data['error_date_available'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['product_id'])) {
			$data['action'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['product_description'])) {
			$data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
		} else {
			$data['product_description'] = array();
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$data['image'] = $product_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);

		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
		}

		// 为打印的小票提供信息输入
		//
		if (isset($this->request->post['ticket_partName'])) {
			$data['ticket_partName'] = $this->request->post['ticket_partName'];
		} elseif (!empty($product_info)) {
			$data['ticket_partName'] = $product_info['ticket_partName'];
		} else {
			$data['ticket_partName'] = '';
		}

		if (isset($this->request->post['ticket_styleCode'])) {
			$data['ticket_styleCode'] = $this->request->post['ticket_styleCode'];
		} elseif (!empty($product_info)) {
			$data['ticket_styleCode'] = $product_info['ticket_styleCode'];
		} else {
			$data['ticket_styleCode'] = '';
		}

		if (isset($this->request->post['ticket_size'])) {
			$data['ticket_size'] = $this->request->post['ticket_size'];
		} elseif (!empty($product_info)) {
			$data['ticket_size'] = $product_info['ticket_size'];
		} else {
			$data['ticket_size'] = '';
		}

		if (isset($this->request->post['ticket_color'])) {
			$data['ticket_color'] = $this->request->post['ticket_color'];
		} elseif (!empty($product_info)) {
			$data['ticket_color'] = $product_info['ticket_color'];
		} else {
			$data['ticket_color'] = '';
		}

		if (isset($this->request->post['ticket_fabric'])) {
			$data['ticket_fabric'] = $this->request->post['ticket_fabric'];
		} elseif (!empty($product_info)) {
			$data['ticket_fabric'] = $product_info['ticket_fabric'];
		} else {
			$data['ticket_fabric'] = '';
		}

		if (isset($this->request->post['ticket_rank'])) {
			$data['ticket_rank'] = $this->request->post['ticket_rank'];
		} elseif (!empty($product_info)) {
			$data['ticket_rank'] = $product_info['ticket_rank'];
		} else {
			$data['ticket_rank'] = '';
		}

		if (isset($this->request->post['ticket_address'])) {
			$data['ticket_address'] = $this->request->post['ticket_address'];
		} elseif (!empty($product_info)) {
			$data['ticket_address'] = $product_info['ticket_address'];
		} else {
			$data['ticket_address'] = '';
		}
		//


		if (isset($this->request->post['sku'])) {
			$data['sku'] = $this->request->post['sku'];
		} elseif (!empty($product_info)) {
			$data['sku'] = $product_info['sku'];
		} else {
			$data['sku'] = '';
		}

		if (isset($this->request->post['upc'])) {
			$data['upc'] = $this->request->post['upc'];
		} elseif (!empty($product_info)) {
			$data['upc'] = $product_info['upc'];
		} else {
			$data['upc'] = '';
		}

		if (isset($this->request->post['ean'])) {
			$data['ean'] = $this->request->post['ean'];
		} elseif (!empty($product_info)) {
			$data['ean'] = $product_info['ean'];
		} else {
			$data['ean'] = '';
		}

		if (isset($this->request->post['jan'])) {
			$data['jan'] = $this->request->post['jan'];
		} elseif (!empty($product_info)) {
			$data['jan'] = $product_info['jan'];
		} else {
			$data['jan'] = '';
		}

		if (isset($this->request->post['isbn'])) {
			$data['isbn'] = $this->request->post['isbn'];
		} elseif (!empty($product_info)) {
			$data['isbn'] = $product_info['isbn'];
		} else {
			$data['isbn'] = '';
		}

		if (isset($this->request->post['mpn'])) {
			$data['mpn'] = $this->request->post['mpn'];
		} elseif (!empty($product_info)) {
			$data['mpn'] = $product_info['mpn'];
		} else {
			$data['mpn'] = '';
		}

		if (isset($this->request->post['location'])) {
			$data['location'] = $this->request->post['location'];
		} elseif (!empty($product_info)) {
			$data['location'] = $product_info['location'];
		} else {
			$data['location'] = '';
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['product_store'])) {
			$data['product_store'] = $this->request->post['product_store'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
		} else {
			$data['product_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($product_info)) {
			$data['keyword'] = $product_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['shipping'])) {
			$data['shipping'] = $this->request->post['shipping'];
		} elseif (!empty($product_info)) {
			$data['shipping'] = $product_info['shipping'];
		} else {
			$data['shipping'] = 1;
		}

		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$data['price'] = $product_info['price'];
		} else {
			$data['price'] = '';
		}

		// cost_price
		$this->log->write('ceshiyixia');
		$this->log->write($this->request->post['cost_price']);
		if (isset($this->request->post['cost_price'])) {
			$data['cost_price'] = $this->request->post['cost_price'];
		} elseif (!empty($product_info)) {
			$data['cost_price'] = $product_info['cost_price'];
		} else {
			$data['cost_price'] = '';
		}


		$this->load->model('catalog/recurring');

		$data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

		if (isset($this->request->post['product_recurrings'])) {
			$data['product_recurrings'] = $this->request->post['product_recurrings'];
		} elseif (!empty($product_info)) {
			$data['product_recurrings'] = $this->model_catalog_product->getRecurrings($product_info['product_id']);
		} else {
			$data['product_recurrings'] = array();
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['tax_class_id'])) {
			$data['tax_class_id'] = $this->request->post['tax_class_id'];
		} elseif (!empty($product_info)) {
			$data['tax_class_id'] = $product_info['tax_class_id'];
		} else {
			$data['tax_class_id'] = 0;
		}

		if (isset($this->request->post['date_available'])) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
		} else {
			$data['date_available'] = date('Y-m-d');
		}

		// huwen mod for quantity=0
		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($product_info)) {
			$data['quantity'] = $product_info['quantity'];
		} else {
			$data['quantity'] = 0;
		}

		if (isset($this->request->post['minimum'])) {
			$data['minimum'] = $this->request->post['minimum'];
		} elseif (!empty($product_info)) {
			$data['minimum'] = $product_info['minimum'];
		} else {
			$data['minimum'] = 1;
		}

		if (isset($this->request->post['subtract'])) {
			$data['subtract'] = $this->request->post['subtract'];
		} elseif (!empty($product_info)) {
			$data['subtract'] = $product_info['subtract'];
		} else {
			$data['subtract'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		$this->load->model('localisation/stock_status');

		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['stock_status_id'])) {
			$data['stock_status_id'] = $this->request->post['stock_status_id'];
		} elseif (!empty($product_info)) {
			$data['stock_status_id'] = $product_info['stock_status_id'];
		} else {
			$data['stock_status_id'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($product_info)) {
			$data['status'] = $product_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['weight'])) {
			$data['weight'] = $this->request->post['weight'];
		} elseif (!empty($product_info)) {
			$data['weight'] = $product_info['weight'];
		} else {
			$data['weight'] = '';
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['weight_class_id'])) {
			$data['weight_class_id'] = $this->request->post['weight_class_id'];
		} elseif (!empty($product_info)) {
			$data['weight_class_id'] = $product_info['weight_class_id'];
		} else {
			$data['weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		if (isset($this->request->post['length'])) {
			$data['length'] = $this->request->post['length'];
		} elseif (!empty($product_info)) {
			$data['length'] = $product_info['length'];
		} else {
			$data['length'] = '';
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($product_info)) {
			$data['width'] = $product_info['width'];
		} else {
			$data['width'] = '';
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($product_info)) {
			$data['height'] = $product_info['height'];
		} else {
			$data['height'] = '';
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['length_class_id'])) {
			$data['length_class_id'] = $this->request->post['length_class_id'];
		} elseif (!empty($product_info)) {
			$data['length_class_id'] = $product_info['length_class_id'];
		} else {
			$data['length_class_id'] = $this->config->get('config_length_class_id');
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->post['manufacturer_id'])) {
			$data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($product_info)) {
			$data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
			$data['manufacturer_id'] = 0;
		}

		if (isset($this->request->post['manufacturer'])) {
			$data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($product_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

			if ($manufacturer_info) {
				$data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$data['manufacturer'] = '';
			}
		} else {
			$data['manufacturer'] = '';
		}

		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {
			$categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$categories = array();
		}

		$data['product_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		// Filters
		$this->load->model('catalog/filter');

		if (isset($this->request->post['product_filter'])) {
			$filters = $this->request->post['product_filter'];
		} elseif (isset($this->request->get['product_id'])) {
			$filters = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
		} else {
			$filters = array();
		}

		$data['product_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$data['product_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}

		// Attributes
		$this->load->model('catalog/attribute');

		if (isset($this->request->post['product_attribute'])) {
			$product_attributes = $this->request->post['product_attribute'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
		} else {
			$product_attributes = array();
		}

		$data['product_attributes'] = array();

		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

			if ($attribute_info) {
				$data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
				);
			}
		}

		// Options
		$this->load->model('catalog/option');

		if (isset($this->request->post['product_option'])) {
			$product_options = $this->request->post['product_option'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
		} else {
			$product_options = array();
		}

		$data['product_options'] = array();
		$this->load->model('catalog/repository'); // Luke add: to obtain the product amount in each repos.
		$repo_names = array();

		if ($product_options) {
			foreach ($product_options as $product_option) {
				$product_option_value_data = array();

				$poid = $product_option['product_option_id'];

				if (isset($product_option['product_option_value'])) {
					foreach ($product_option['product_option_value'] as $product_option_value) {
						// Luke add: to obtain the product amount in each repos (begin)
						$filter_data = array(
							'product_option_value_id' => $product_option_value['product_option_value_id']
							);
						$result = $this->model_catalog_repository->getRepository_pdbyprodId($filter_data);

						foreach ($result as $name => $amt) {
							if (!in_array($name, $repo_names)) {
								$repo_names[] = $name;
							}
						}
						// Luke add: to obtain the product amount in each repos (end)

						$product_option_value_data[] = array(
							'product_option_value_id' => $product_option_value['product_option_value_id'],
							'option_value_id'         => $product_option_value['option_value_id'],
							'quantity'                => $product_option_value['quantity'],
							'subtract'                => $product_option_value['subtract'],

							'price'                   => $product_option_value['price'],
							'price_prefix'            => $product_option_value['price_prefix'],
							'points'                  => $product_option_value['points'],
							'points_prefix'           => $product_option_value['points_prefix'],
							'weight'                  => $product_option_value['weight'],
							'weight_prefix'           => $product_option_value['weight_prefix'],
							// huwen added for barcode
							'barcode'=>$product_option_value['barcode'],
							'cost_price'=>$product_option_value['cost_price'],
							// Luke added for repository amount
							'amt_in_repos'			  => $result
						);
					}
				}

				$data['product_options'][] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'value'                => isset($product_option['value']) ? $product_option['value'] : '',
					'required'             => $product_option['required']
				);
			}
		} else if (isset($this->request->get['product_id'])) {
			$filter_data = array(
				'product_id' => $this->request->get['product_id']
			);
			$result = $this->model_catalog_repository->getRepository_pdbyprodId($filter_data);
			foreach ($result as $name => $amt) {
				if (!in_array($name, $repo_names)) {
					$repo_names[] = $name;
				}
			}
			$data['product_amount'] = $result;
		}


		$data['repo_names'] = $repo_names;

		$data['option_values'] = array();

		foreach ($data['product_options'] as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (!isset($data['option_values'][$product_option['option_id']])) {
					$data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
				}
			}
		}

		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['product_discount'])) {
			$product_discounts = $this->request->post['product_discount'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
		} else {
			$product_discounts = array();
		}

		$data['product_discounts'] = array();

		foreach ($product_discounts as $product_discount) {
			$data['product_discounts'][] = array(
				'customer_group_id' => $product_discount['customer_group_id'],
				'quantity'          => $product_discount['quantity'],
				'priority'          => $product_discount['priority'],
				'price'             => $product_discount['price'],
				'date_start'        => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
				'date_end'          => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
			);
		}

		if (isset($this->request->post['product_special'])) {
			$product_specials = $this->request->post['product_special'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_specials = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
		} else {
			$product_specials = array();
		}

		$data['product_specials'] = array();

		foreach ($product_specials as $product_special) {
			$data['product_specials'][] = array(
				'customer_group_id' => $product_special['customer_group_id'],
				'priority'          => $product_special['priority'],
				'price'             => $product_special['price'],
				'date_start'        => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
				'date_end'          => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] :  ''
			);
		}

		// Images
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}

		$data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if (is_file(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
				$thumb = $product_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		// Downloads
		$this->load->model('catalog/download');

		if (isset($this->request->post['product_download'])) {
			$product_downloads = $this->request->post['product_download'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_downloads = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$product_downloads = array();
		}

		$data['product_downloads'] = array();

		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);

			if ($download_info) {
				$data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}

		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} elseif (isset($this->request->get['product_id'])) {
			$products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
		} else {
			$products = array();
		}

		$data['product_relateds'] = array();

		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);

			if ($related_info) {
				$data['product_relateds'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

		if (isset($this->request->post['points'])) {
			$data['points'] = $this->request->post['points'];
		} elseif (!empty($product_info)) {
			$data['points'] = $product_info['points'];
		} else {
			$data['points'] = '';
		}

		if (isset($this->request->post['product_reward'])) {
			$data['product_reward'] = $this->request->post['product_reward'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
		} else {
			$data['product_reward'] = array();
		}

		if (isset($this->request->post['product_layout'])) {
			$data['product_layout'] = $this->request->post['product_layout'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->get['product_id']);
		} else {
			$data['product_layout'] = array();
		}

		// Luke added: tab of repository (begin)
		$data['tab_repo'] = $this->language->get('tab_repo');

		// Luke added: tab of repository (end)

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// huwen added for qrcode huwen mark
		if (isset($this->request->get['product_id'])) {

			$this->load->model('catalog/product');

			$outfile=DIR_APPLICATION.'view/image/qrcode/'.$this->request->get['product_id'].'.png';

			if ($this->model_catalog_product->getProductModelById($this->request->get['product_id'])) {
				$product_model=$this->model_catalog_product->getProductModelById($this->request->get['product_id']);
				$newname=DIR_APPLICATION.'view/image/qrcode/'.$product_model.'.png';

				chmod($outfile,777);
				rename($outfile,$newname);
				$data['issetQrcode']=file_exists($newname);
				$data['product_model']=$product_model;
			}else{
				$data['issetQrcode']=file_exists($outfile);
			}

			$data['product_id']=$this->request->get['product_id'];

		}


		$this->response->setOutput($this->load->view('catalog/product_form.tpl', $data));
	}
	// public function renameQrcode($oldname,$newname){
	// 	chmod($oldname);
	// 	rename($oldname,$newname);
	// }
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			// if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
			// 	$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			// }
		}

		$this->load->model('catalog/product');

		// 增加特殊符号的正则校验
		// if(!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$this->request->post['model'])){
		// 	$this->error['model']="请不要使用特殊字符";
		// }

		if ($this->request->get['route']!='catalog/product/edit') {
			if ($this->model_catalog_product->getProductIdByModel($this->request->post['model'])) {
				$this->error['model']='该商品编号已被使用';
			}
		}elseif ($this->request->get['route']=='catalog/product/edit') {
			if ($this->model_catalog_product->getProductIdByModel($this->request->post['model'])) {
				$this_product_id=$this->model_catalog_product->getProductIdByModel($this->request->post['model']);
				if ($this_product_id!=$this->request->get['product_id']) {
					$this->error['model']='该商品编号已被使用';
				}
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['product_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = '相关数据未填写，或者填写有误';
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		// Luke added for creator.
		// 考虑用户的角色，如果为 boss 则列出所有商品
	    // 如果为产品负责人，则列出其由其创建的商品
	    $ADMIN_GROUP = array(1, 2);
	    $PROD_MANAGER_GROUP_ID = 3;

		if (isset($this->request->get['filter_creator'])) {
    	if (in_array($this->user->getGroupId(), $ADMIN_GROUP)) {
				$this->load->model('catalog/product');
				$json = $this->model_catalog_product->getCreators($this->request->get['filter_creator']);
			} else if ($this->user->getGroupId() == $PROD_MANAGER_GROUP_ID) {
				$json[] = array('creator' => $this->user->getUserName());
			}
		}

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// huwen added for qrcode
	public function getQrcode($product_model,$product_id){

		include DIR_SYSTEM."pub/phpqrcode/phpqrcode.php";//引入PHP QR库文件

		$value=HTTP_CATALOG."index.php?route=product/product&product_id=".$product_id;
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

	public function getQrcodeUrl($product_model,$product_id){
		return HTTP_CATALOG."index.php?route=product/product&product_id=".$product_id;
	}

	/**
	 * for product_urls
	 * @return [type] [description]
	 */
	public function downloadQrcodeUrl(){
		$this->load->language('catalog/product');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/product');

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

		$data=array();

		$products=$this->model_catalog_product->getProducts();

		foreach ($products as $product) {
			$product_id=$product['product_id'];
			$product_model=$product['model'];
			$data["products"][]=array(
				'model'=>$product_model,
				"url"=>HTTP_CATALOG."index.php?route=product/product&product_id=".$product_id,
				);
		}



		// if (isset($this->request->post['selected'])&&$this->request->post['selected']!=null) {


		// 	foreach ($this->request->post['selected'] as $product_id) {

		// 		$product_model=$this->model_catalog_product->getProductModelById($product_id);

		// 		$data["products"][]=array(
		// 			'model'=>$product_model,
		// 			"url"=>HTTP_CATALOG."index.php?route=product/product&product_id=".$product_id,
		// 			);
		// 	}

		// }

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

		$this->response->setOutput($this->load->view("catalog/urls.tpl",$data));

	}

	// 导出二维码图片
	public function downloadQrcode(){
		$this->load->language('catalog/product');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/product');

		if (isset($this->request->post['selected'])&&$this->request->post['selected']!=null) {

			$zip_file=DIR_APPLICATION.'view/image/qrcode/qrcode.zip';
			$zip = new ZipArchive();
			$zip->open($zip_file,ZipArchive::OVERWRITE);
			$zip->addEmptyDir('images');


			foreach ($this->request->post['selected'] as $product_id) {
				$this->load->model('catalog/product');
				$product_model=$this->model_catalog_product->getProductModelById($product_id);
				$qrcode=DIR_APPLICATION.'view/image/qrcode/'.$product_model.'.png';
				$file_name = $product_model.'.png';
				$file_dir = DIR_APPLICATION.'view/image/qrcode/';

				$fileData=file_get_contents($file_dir.$file_name);

				$zip->addFromString('images/'.$file_name,$fileData);

			}

			$zip->close();
			$this->download($zip_file);
		}else{
			echo "<script language='javascript'>alert('请选择要导出的二维码')</script>";
			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] , 'SSL'));
		}

	}
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
}
