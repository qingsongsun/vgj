<?php
class ControllerReportCoupon extends Controller {
	public function index() {
		$this->load->language('report/sale_coupon');
		$this->load->model('report/coupon');

		$this->document->setTitle('优惠券统计报表');

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
			$filter_customer_id=$this->model_report_coupon->getCustomerByEmail($filter_email)['customer_id'];
		} else {
			$filter_email = null;
			$filter_customer_id=null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('report/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/coupon');

		$data['coupons'] = array();

		$filter_data = array(
			'filter_customer_id'		=>$filter_customer_id,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$coupon_total = $this->model_report_coupon->getTotalCustomers($filter_data);

		$results = $this->model_report_coupon->getCutomerCoupons($filter_data);

		foreach ($results as $result) {
			if ($result['customer_id']!='') {
				$data['coupons'][] = array(
					'name'   => $result['name'],
					'code'   => $result['code'],
					'customer' => $this->model_report_coupon->getCustomer($result['customer_id']),
					'date_added'  => $result['date_added'],
					'coupon_status'=>$result['coupon_status'],
					'edit'   => $this->url->link('marketing/coupon/edit', 'token=' . $this->session->data['token'] . '&coupon_id=' . $result['coupon_id'] . $url, 'SSL')
				);
			}
		}

		$data['heading_title'] = '优惠券统计报表';

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_code'] = $this->language->get('column_code');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/coupon', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($coupon_total - $this->config->get('config_limit_admin'))) ? $coupon_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $coupon_total, ceil($coupon_total / $this->config->get('config_limit_admin')));

		$data['filter_email'] = $filter_email;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/coupon.tpl', $data));
	}
}