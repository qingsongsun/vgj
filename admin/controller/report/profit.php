<?php
/**
* 利润报表
*/
class ControllerReportProfit extends Controller
{

	public function index()
	{
		/**
		 * 页面数据初始化
		 */
		$repo_title='';		//用于存储报表标题的容器

		if (isset($this->request->get['filter_repo_name'])) {

			$filter_repo_name = $this->request->get['filter_repo_name'];
			$repo_title.=$filter_repo_name;


		} else {
	      $filter_repo_name = null;

	    }

	    if (isset($this->request->get['filter_start_time'])) {
	      $filter_start_time = $this->request->get['filter_start_time'];
	      $repo_title.="(".$filter_start_time." 至 ";
	    } else {
	        $filter_start_time = null;
	    }

	    if (isset($this->request->get['filter_end_time'])) {
	      $filter_end_time = $this->request->get['filter_end_time'];
	      $repo_title.=$filter_end_time.")";
	    } else {
	      $filter_end_time = null;
	    }

	    if (isset($this->request->get['filter_prod_name'])) {
	        $filter_prod_name = $this->request->get['filter_prod_name'];
	    } else {
	        $filter_prod_name = null;
	    }

	    if (isset($this->request->get['filter_prod_id'])) {
	        $filter_prod_id = $this->request->get['filter_prod_id'];
	    } else {
	        $filter_prod_id = null;
	    }

	    $data['filter_repo_name'] = $filter_repo_name;
	    $data['filter_start_time'] = $filter_start_time;
	    $data['filter_end_time'] = $filter_end_time;
	    $data['filter_prod_name'] = $filter_prod_name;
	    $data['filter_prod_id'] = $filter_prod_id;
	    $data['token'] = $this->session->data['token'];

	    $this->load->language('repository/report_store_sale');
	    if ($repo_title!='') {
	    	$this->document->setTitle($this->language->get($repo_title.' 销售报表'));
		    $data['heading_title']      = $this->language->get('销售报表');
		    $data['text_list']          = $this->language->get($repo_title.' 销售报表');
	    }else{
	    	$this->document->setTitle($this->language->get('所有店铺销售报表'));
		    $data['heading_title']      = $this->language->get('销售报表');
		    $data['text_list']          = $this->language->get('所有店铺销售报表');
	    }



	    $data['text_no_results']    = $this->language->get('text_no_results');
	    $data['column_store_name']  = $this->language->get('column_store_name');
	    $data['column_start_time']  = $this->language->get('column_start_time');
	    $data['column_end_time']    = $this->language->get('column_end_time');
	    $data['column_sale_amount'] = $this->language->get('column_sale_amount');
	    $data['column_prod_name']   = $this->language->get('column_prod_name');
	    $data['column_model']       = $this->language->get('column_model');
	    $data['column_ovd_name']    = $this->language->get('column_ovd_name');
	    $data['column_sale_quantity'] = $this->language->get('column_sale_quantity');

	    $data['entry_name']         = $this->language->get('entry_name');
	    $data['entry_start_time']   = $this->language->get('entry_start_time');
	    $data['entry_end_time']     = $this->language->get('entry_end_time');
	    $data['entry_prod_name']    = $this->language->get('entry_prod_name');
	    $data['entry_ovd_name']     = $this->language->get('entry_ovd_name');

	    $data['button_filter']      = $this->language->get('button_filter');

		$data['breadcrumbs'] = array();
	    $data['breadcrumbs'][] = array(
	        'text' => $this->language->get('text_home'),
	        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
	    );
	    $data['breadcrumbs'][] = array(
	        'text' => $this->language->get('heading_title'),
	        'href' => $this->url->link('report/profit', 'token=' . $this->session->data['token'], 'SSL')
	    );

	    $limit = $this->config->get('config_limit_admin');
	    if (isset($this->request->get['page'])) {
	      $page = $this->request->get['page'];
	    } else {
	      $page = 1;
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



	    /**
	     * 开始处理报表的逻辑
	     */

	    if (isset($this->request->get['uid'])) {
	      $uid = $this->request->get['uid'];
	    } else {
	      $uid = $this->user->getId();
	    }

	    $filter_data = array(
	        'user_id'           => $uid,
	        'start'             => ($page-1)*$limit,
	        'limit'             => $limit,
	        'filter_repo_name'  => $filter_repo_name,
	        'filter_prod_id'    => $filter_prod_id,
	        'filter_date_start' => $filter_start_time,
	        'filter_date_end'   => $filter_end_time
	    );

	    $this->load->model('report/profit');

	    $products=$this->model_report_profit->getProducts($filter_data);
	    $total=$this->model_report_profit->getTotalProducts($filter_data);

	    /**
	     * 刷数据
	     */
	    $data['items'] = array();
	    foreach ($products as $item) {
	      $data['items'][] = array(
	        'model'         => $item['model'],
	        'name'	=>$item['name'],
	        'cost_price'      => $this->currency->format($item['cost_price']),
	        'real_price'=>$this->currency->format($item['real_price']),
	        'profit'=>(number_format($item['profit'],4)*100)."%",
	        'saled_quantity'=>$item['saled_num'],
	        'repo_quantity'=>$item['repo_num'],
	        'saled_cost_total'=>$this->currency->format($item['saled_cost_total']),
	        'sticker_cost_total'=>$this->currency->format($item['sticket_total']),
	        'real_saled_total'=>$this->currency->format($item['real_saled_total']),
	        'repo_cost_total'=>$this->currency->format($item['repo_cost_total']),
	        'repo_sticker_cost_total'=>$this->currency->format($item['repo_sticker_cost_total']),
	        'start_time'    => $item['date_start'],
	        'end_time'      => $item['date_end'],
	      );
	    }

	    /**
	     * filter and page
	     */
	    $url = '';

	    if (isset($this->request->get['repo_name'])) {
	      $url .= '&repo_name='.urlencode(html_entity_decode($repo_name, ENT_QUOTES, 'UTF-8'));
	    }

	    if (isset($this->request->get['uid'])) {
	      $url .= '&uid='.urlencode(html_entity_decode($uid, ENT_QUOTES, 'UTF-8'));
	    }


	    if (isset($this->request->get['filter_repo_name'])) {
	      $url .= '&filter_repo_name='.urlencode(html_entity_decode($filter_repo_name, ENT_QUOTES, 'UTF-8'));
	    }

	    if (isset($this->request->get['filter_start_time'])) {
	      $url .= '&filter_start_time='.urlencode(html_entity_decode($filter_start_time, ENT_QUOTES, 'UTF-8'));
	    }

	    if (isset($this->request->get['filter_end_time'])) {
	      $url .= '&filter_end_time='.urlencode(html_entity_decode($filter_end_time, ENT_QUOTES, 'UTF-8'));
	    }

	    if (isset($this->request->get['filter_prod_name'])) {
	      $url .= '&filter_prod_name='.urlencode(html_entity_decode($filter_prod_name, ENT_QUOTES, 'UTF-8'));
	    }

	    if (isset($this->request->get['filter_prod_id'])) {
	        $url .= '&filter_prod_id='.urlencode(html_entity_decode($filter_prod_id, ENT_QUOTES, 'UTF-8'));
	    }

	    $pagination = new Pagination();
	    if (isset($total)) {
	      $pagination->total = $total;
	    } else {
	      $pagination->total = 1;
	    }
	    $pagination->page = $page;
	    $pagination->limit = $limit;
	    $pagination->url = $this->url->link('report/profit', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
	    $data['pagination'] = $pagination->render();
	    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

	    $data['header'] = $this->load->controller('common/header');
	    $data['column_left'] = $this->load->controller('common/column_left');
	    $data['footer'] = $this->load->controller('common/footer');

	    $this->response->setOutput($this->load->view('report/profit.tpl',$data));

	}
}