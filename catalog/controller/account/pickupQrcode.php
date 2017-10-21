<?php
/**
* 展示自提码的页面
*/
class ControllerAccountPickupQrcode extends Controller
{

	public function index(){

		// if (!$this->customer->isLogged()) {
		// 	$this->session->data['redirect'] = $this->url->link('account/mycoupon', '', 'SSL');

		// 	$this->response->redirect($this->url->link('account/wxlogin', '', 'SSL'));
		// }

		if (isset($this->session->data['switched_user'])) {
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}

		$this->document->setTitle($this->language->get('自提码'));

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
			'text' => $this->language->get('text_reward'),
			'href' => $this->url->link('account/reward', '', 'SSL')
			);

		$this->load->model('account/pickupQrcode');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_points'] = $this->language->get('column_points');

		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['coupon']=array();

		$filter_data = array(
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
			);


		$results=$this->model_account_pickupQrcode->getPickupQrcodeByCustomerId($this->customer->getId());

		$data['orders']=$results;


		$pagination = new Pagination();
		$pagination->total = $reward_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/my_coupon', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/pickup_qrcode.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/pickup_qrcode.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/pickup_qrcode.tpl', $data));
		}
	}
}