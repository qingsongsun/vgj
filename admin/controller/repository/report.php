<?php
class ControllerRepositoryReport extends Controller {
  public function inventory() {

    if (isset($this->request->get['filter_repo_name'])) {
        $filter_repo_name = $this->request->get['filter_repo_name'];
    } else {
        $filter_repo_name = null;
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

    if (isset($this->request->get['filter_ovd_name'])) {
        $filter_ovd_name = $this->request->get['filter_ovd_name'];
    } else {
        $filter_ovd_name = null;
    }

    $data['filter_prod_name']   = $filter_prod_name;
    $data['filter_repo_name']   = $filter_repo_name;
    $data['filter_ovd_name']    = $filter_ovd_name;
    $data['filter_prod_id']     = $filter_prod_id;

    $data['token'] = $this->session->data['token'];

    $this->load->language('repository/report_inventory');
    $this->document->setTitle($this->language->get('heading_title'));

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_list'] = $this->language->get('text_list');

    $data['text_repo_type'] = $this->language->get('text_repo_type');
    $data['text_prod_name'] = $this->language->get('text_prod_name');
    $data['text_prod_model'] = $this->language->get('text_prod_model');
    $data['text_repo_name'] = $this->language->get('text_repo_name');
    $data['text_prod_ovdname'] = $this->language->get('text_prod_ovdname');
    $data['text_quantity'] = $this->language->get('text_quantity');
    $data['text_no_result'] = $this->language->get('text_no_results');

    $data['entry_repo_name'] = $this->language->get('entry_repo_name');
    $data['entry_prod_name'] = $this->language->get('entry_prod_name');
    $data['entry_ovd_name']  = $this->language->get('entry_ovd_name');

    $data['button_filter'] = $this->language->get('button_filter');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('repository/report/inventory', 'token=' . $this->session->data['token'], 'SSL')
    );

    $limit = $this->config->get('config_limit_admin');
    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    if (isset($this->request->get['uid'])) {
      $uid = $this->request->get['uid'];
    } else {
      $uid = $this->user->getId();
    }

    // Data provider
    $this->load->model('catalog/repository');

    $filter_data = array(
        'user_id'           => $uid,
        'start'             => ($page-1)*$limit,
        'limit'             => $limit,
        'filter_repo_name'  => $filter_repo_name,
        'filter_prod_id'    => $filter_prod_id,
        'filter_ovd_name'   => $filter_ovd_name
    );

    $data['products'] = $this->model_catalog_repository->getRepository_pdbyUid($filter_data);

    $total = (int)$this->model_catalog_repository->getTotalRepository_pdbyUid($filter_data);

    $url = '';

     if (isset($this->request->get['filter_repo_name'])) {
      $url .= '&filter_repo_name='.urlencode(html_entity_decode($filter_repo_name, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_prod_name'])) {
      $url .= '&filter_prod_name='.urlencode(html_entity_decode($filter_prod_name, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_prod_id'])) {
        $url .= '&filter_prod_id='.urlencode(html_entity_decode($filter_prod_id, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_ovd_name'])) {
      $url .= '&filter_ovd_name='.urlencode(html_entity_decode($filter_ovd_name, ENT_QUOTES, 'UTF-8'));
    }

    $pagination = new Pagination();
    if (isset($total)) {
      $pagination->total = $total;
    } else {
      $pagination->total = 1;
    }
    $pagination->page = $page;
    $pagination->limit = $limit;
    $pagination->url = $this->url->link('repository/report/inventory', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
    $data['pagination'] = $pagination->render();
    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('repository/report_inventory.tpl', $data));
  }

  public function history() {

    if (isset($this->request->get['filter_repo_name'])) {
        $filter_repo_name = $this->request->get['filter_repo_name'];
    } else {
        $filter_repo_name = null;
    }

    if (isset($this->request->get['filter_user_name'])) {
        $filter_user_name = $this->request->get['filter_user_name'];
    } else {
        $filter_user_name = null;
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

    if (isset($this->request->get['filter_ovd_name'])) {
        $filter_ovd_name = $this->request->get['filter_ovd_name'];
    } else {
        $filter_ovd_name = null;
    }

    if (isset($this->request->get['filter_reason'])) {
        $filter_reason = $this->request->get['filter_reason'];
    } else {
        $filter_reason = null;
    }

    $data['filter_repo_name'] = $filter_repo_name;
    $data['filter_user_name'] = $filter_user_name;
    $data['filter_prod_name'] = $filter_prod_name;
    $data['filter_ovd_name'] = $filter_ovd_name;
    $data['filter_reason'] = $filter_reason;
    $data['filter_prod_id'] = $filter_prod_id;

    $data['token'] = $this->session->data['token'];

    $this->load->language('repository/report_history');
    $this->document->setTitle($this->language->get('heading_title'));

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_list'] = $this->language->get('text_list');
    $data['text_inout'] = $this->language->get('text_inout');
    $data['text_time'] = $this->language->get('text_time');
    $data['text_repo_name'] = $this->language->get('text_repo_name');
    $data['text_user_name'] = $this->language->get('text_user_name');
    $data['text_prod_name'] = $this->language->get('text_prod_name');
    $data['text_ovd_name'] = $this->language->get('text_ovd_name');
    $data['text_quantity'] = $this->language->get('text_quantity');
    $data['text_reason'] = $this->language->get('text_reason');
    $data['text_in'] = $this->language->get('text_in');
    $data['text_out'] = $this->language->get('text_out');
    $data['text_trans'] = $this->language->get('text_trans');
    $data['text_type_in'] = $this->language->get('text_type_in');
    $data['text_type_out'] = $this->language->get('text_type_out');
    $data['text_type_order'] = $this->language->get('text_type_order');
    $data['text_no_result'] = $this->language->get('text_no_results');

    $data['entry_repo_name']  = $this->language->get('entry_repo_name');
    $data['entry_user_name']  = $this->language->get('entry_user_name');
    $data['entry_prod_name']  = $this->language->get('entry_prod_name');
    $data['entry_ovd_name']   = $this->language->get('entry_ovd_name');
    $data['entry_reason']     = $this->language->get('entry_reason');

    $data['button_filter'] = $this->language->get('button_filter');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('repository/report/hisotry', 'token=' . $this->session->data['token'], 'SSL')
    );

    $limit = $this->config->get('config_limit_admin');
    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    if (isset($this->request->get['uid'])) {
      $uid = $this->request->get['uid'];
    } else {
      $uid = $this->user->getId();
    }

    // Data provider
    $this->load->model('catalog/repository');

    $filter_data = array(
        'user_id'           => $uid,
        'start'             => ($page-1)*$limit,
        'limit'             => $limit,
        'filter_repo_name'  => $filter_repo_name,
        'filter_prod_id'    => $filter_prod_id,
        'filter_ovd_name'   => $filter_ovd_name,
        'filter_user_name'  => $filter_user_name,
        'filter_reason'     => $filter_reason
    );

    $data['items'] = $this->model_catalog_repository->getRepository_inout($filter_data);

    $total = (int)$this->model_catalog_repository->getTotalRepository_inout($filter_data);
    // $data['items'] = array(
    //   array('repo_opt'=>0, 'timestamp'=>'12:00:00', 'repo_name'=>'闵行', 'user_name'=>'Luke', 'prod_name'=>'围巾', 'ovd_name'=>'红色', 'quantity'=>5, 'type'=>1),
    //   array('repo_opt'=>1, 'timestamp'=>'15:00:00', 'repo_name'=>'徐汇', 'user_name'=>'Zark', 'prod_name'=>'围巾', 'ovd_name'=>'红色', 'quantity'=>5, 'type'=>1),
    //   array('repo_opt'=>0, 'timestamp'=>'16:00:00', 'repo_name'=>'闵行', 'user_name'=>'Luke', 'prod_name'=>'纸杯', 'ovd_name'=>'大号', 'quantity'=>3, 'type'=>0),
    //   array('repo_opt'=>1, 'timestamp'=>'18:00:00', 'repo_name'=>'闵行', 'user_name'=>'Luke', 'prod_name'=>'纸杯', 'ovd_name'=>'大号', 'quantity'=>4, 'type'=>0),
    // );
    // $total = 4;

    $url = '';

    if (isset($this->request->get['filter_repo_name'])) {
       $url .= '&filter_repo_name='.urlencode(html_entity_decode($filter_repo_name, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_user_name'])) {
        $url .= '&filter_user_name='.urlencode(html_entity_decode($filter_user_name, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_prod_name'])) {
        $url .= '&filter_prod_name='.urlencode(html_entity_decode($filter_prod_name, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_prod_id'])) {
        $url .= '&filter_prod_id='.urlencode(html_entity_decode($filter_prod_id, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_ovd_name'])) {
        $url .= '&filter_ovd_name='.urlencode(html_entity_decode($filter_ovd_name, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_reason'])) {
        $url .= '&filter_reason='.urlencode(html_entity_decode($filter_reason, ENT_QUOTES, 'UTF-8'));
    }

    $pagination = new Pagination();
    if (isset($total)) {
      $pagination->total = $total;
    } else {
      $pagination->total = 1;
    }
    $pagination->page = $page;
    $pagination->limit = $limit;
    $pagination->url = $this->url->link('repository/report/history', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
    $data['pagination'] = $pagination->render();
    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');


    $this->response->setOutput($this->load->view('repository/report_history.tpl', $data));
  }

  public function store_sale() {
    if (isset($this->request->get['filter_repo_name'])) {
      $filter_repo_name = $this->request->get['filter_repo_name'];
    } else {
      $filter_repo_name = null;
    }

    if (isset($this->request->get['filter_start_time'])) {
      $filter_start_time = $this->request->get['filter_start_time'];
    } else {
        $filter_start_time = null;
    }

    if (isset($this->request->get['filter_end_time'])) {
      $filter_end_time = $this->request->get['filter_end_time'];
    } else {
      $filter_end_time = null;
    }

    $data['filter_repo_name'] = $filter_repo_name;
    $data['filter_start_time'] = $filter_start_time;
    $data['filter_end_time'] = $filter_end_time;
    $data['token'] = $this->session->data['token'];

    $this->load->language('repository/report_store_sale');
    $this->document->setTitle($this->language->get('heading_title'));

    $data['heading_title']      = $this->language->get('heading_title');
    $data['text_list']          = $this->language->get('text_list');

    $data['text_no_results']    = $this->language->get('text_no_results');
    $data['column_store_name']  = $this->language->get('column_store_name');
    $data['column_start_time']  = $this->language->get('column_start_time');
    $data['column_end_time']    = $this->language->get('column_end_time');
    $data['column_prod_sale_amount'] = $this->language->get('column_prod_sale_amount');
    $data['column_coupon_amount'] = $this->language->get('column_coupon_amount');
    $data['column_actual_amount'] = $this->language->get('column_actual_amount');

    $data['entry_name']         = $this->language->get('entry_name');
    $data['entry_start_time']   = $this->language->get('entry_start_time');
    $data['entry_end_time']     = $this->language->get('entry_end_time');

    $data['button_filter']      = $this->language->get('button_filter');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('repository/report/store_sale', 'token=' . $this->session->data['token'], 'SSL')
    );

    $limit = $this->config->get('config_limit_admin');
    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    if (isset($this->request->get['uid'])) {
      $uid = $this->request->get['uid'];
    } else {
      $uid = $this->user->getId();
    }

    // Data provider.
    $filter_data = array(
        'user_id'           => $uid,
        'start'             => ($page-1)*$limit,
        'limit'             => $limit,
        'filter_repo_name'  => $filter_repo_name,
        'filter_date_start' => $filter_start_time,
        'filter_date_end'   => $filter_end_time
    );
    $this->load->model('report/sale');
    $res = $this->model_report_sale->getOrdersbyUid($filter_data);
    $total = $this->model_report_sale->getTotalOrdersbyUid($filter_data);

    $coupons_total=$this->model_report_sale->getCouponTotal($filter_data);

    $orders=$this->model_report_sale->getOrders($filter_data);


    $url = '';

    if (isset($this->request->get['filter_repo_name'])) {
      $url .= '&filter_repo_name='.urlencode(html_entity_decode($filter_repo_name, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_start_time'])) {
      $url .= '&filter_start_time='.urlencode(html_entity_decode($filter_start_time, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_end_time'])) {
      $url .= '&filter_end_time='.urlencode(html_entity_decode($filter_end_time, ENT_QUOTES, 'UTF-8'));
    }

    $data['items'] = array();

    $this->log->write($coupons_total);
    foreach ($coupons_total as $coupon_total) {
        $coupon_total=$coupon_total['coupon_total'];
        foreach ($res as $item) {
              $data['items'][] = array(
                'store_name'    => $item['repo_name'],
                'prod_total'    => $item['prod_total'],
                'coupon_total'  => $coupon_total,
                'actual_total'  => $item['prod_total']+$coupon_total,
                'store_orders'=>$this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] .'&repo_name='.$item['repo_name'] .'&uid='.$uid. $url, 'SSL')
              );

        }
    }


    $pagination = new Pagination();
    if (isset($total)) {
      $pagination->total = $total;
    } else {
      $pagination->total = 1;
    }
    $pagination->page = $page;
    $pagination->limit = $limit;
    $pagination->url = $this->url->link('repository/report/store_sale', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
    $data['pagination'] = $pagination->render();
    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('repository/report_store_sale.tpl', $data));
  }

  public function store_order_sale(){
    $this->load->language('sale/order');

    $this->document->setTitle($this->language->get('订单报表'));

    $this->load->model('sale/order');
        if (isset($this->request->get['repo_name'])) {
          $filter_repo_name = $this->request->get['repo_name'];
        } else {
          $filter_repo_name = null;
        }

        if (isset($this->request->get['filter_start_time'])) {
          $filter_start_time = $this->request->get['filter_start_time'];
        } else {
            $filter_start_time = null;
        }

        if (isset($this->request->get['filter_end_time'])) {
          $filter_end_time = $this->request->get['filter_end_time'];
        } else {
          $filter_end_time = null;
        }

        $data['filter_repo_name'] = $filter_repo_name;
        $data['filter_start_time'] = $filter_start_time;
        $data['filter_end_time'] = $filter_end_time;

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['repo_name'])) {
            $url .= '&repo_name=' . $this->request->get['repo_name'];
        }

        if (isset($this->request->get['filter_start_time'])) {
            $url .= '&filter_start_time=' . urlencode(html_entity_decode($this->request->get['filter_start_time'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_end_time'])) {
            $url .= '&filter_end_time=' . $this->request->get['filter_end_time'];
        }

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
            'text' => $this->language->get('订单报表'),
            'href' => $this->url->link('repository/report/store_sale', 'token=' . $this->session->data['token'], 'SSL')
        );

        $limit = $this->config->get('config_limit_admin');
        if (isset($this->request->get['page'])) {
          $page = $this->request->get['page'];
        } else {
          $page = 1;
        }

        if (isset($this->request->get['uid'])) {
          $uid = $this->request->get['uid'];
        } else {
          $uid = $this->user->getId();
        }

        $data['orders'] = array();

        $filter_data = array(
            'user_id'           => $uid,
            'filter_repo_name'  => $filter_repo_name,
            'filter_date_start' => $filter_start_time,
            'filter_date_end'   => $filter_end_time,
            'filter_order_id'      => $filter_order_id,
            'filter_customer'      => $filter_customer,
            'filter_order_status'  => $filter_order_status,
            'filter_total'         => $filter_total,
            'filter_date_added'    => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'sort'                 => $sort,
            'order'                => $order,
            'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                => $this->config->get('config_limit_admin')
        );
        $this->load->model('report/sale');

        $order_total=$this->model_report_sale->getTotalOrdersByStore($filter_data);

        $results=$this->model_report_sale->getOrdersByStore($filter_data);

        foreach ($results as $result) {
            $sub_totals=$this->model_sale_order->getOrderTotals($result['order_id']);
            foreach ($sub_totals as $sub_total) {
                if ($sub_total['code']=='sub_total') {
                    $sub_total=$sub_total['value'];
                $data['orders'][] = array(
                    'repo_name'     =>$result['repo_name'],
                    'order_id'      => $result['order_id'],
                    'customer'      => $result['customer'],
                    'status'        => $result['status'],
                    'sub_total'=> $this->currency->format($sub_total,$result['currency_code'],$result['currency_value']),
                    'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                    'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                    'shipping_code' => $result['shipping_code'],
                    'order_comment'=>$this->model_report_sale->getOrderCommentById($result['order_id'])
                );
            }
        }
    }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_subtotal'] = $this->language->get('column_subtotal');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_modified'] = $this->language->get('column_date_modified');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_return_id'] = $this->language->get('entry_return_id');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_modified'] = $this->language->get('entry_date_modified');

        $data['button_invoice_print'] = $this->language->get('button_invoice_print');
        $data['button_shipping_print'] = $this->language->get('button_shipping_print');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_view'] = $this->language->get('button_view');

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
// var_dump($url);exit;
        // $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        // if ($order == 'ASC') {
        //     $url .= '&order=DESC';
        // } else {
        //     $url .= '&order=ASC';
        // }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
        $data['sort_customer'] = $this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');

        $data['sort_subtotal'] = $this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] . '&sort=o.subtotal' . $url, 'SSL');


        $data['sort_total'] = $this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

        // $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('repository/report/store_order_sale', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('repository/report_store_order_sale.tpl', $data));
  }

  public function store_product_sale() {
    if (isset($this->request->get['filter_repo_name'])) {
      $filter_repo_name = $this->request->get['filter_repo_name'];
    } else {
      $filter_repo_name = null;
    }

    if (isset($this->request->get['filter_start_time'])) {
      $filter_start_time = $this->request->get['filter_start_time'];
    } else {
        $filter_start_time = null;
    }

    if (isset($this->request->get['filter_end_time'])) {
      $filter_end_time = $this->request->get['filter_end_time'];
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
    $this->document->setTitle($this->language->get('heading_title'));

    $data['heading_title']      = $this->language->get('heading_title');
    $data['text_list']          = $this->language->get('text_list');

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
        'href' => $this->url->link('repository/report/store_product_sale', 'token=' . $this->session->data['token'], 'SSL')
    );

    $limit = $this->config->get('config_limit_admin');
    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    if (isset($this->request->get['uid'])) {
      $uid = $this->request->get['uid'];
    } else {
      $uid = $this->user->getId();
    }

    // Data provider.
    $filter_data = array(
        'user_id'           => $uid,
        'start'             => ($page-1)*$limit,
        'limit'             => $limit,
        'filter_repo_name'  => $filter_repo_name,
        'filter_prod_id'    => $filter_prod_id,
        'filter_date_start' => $filter_start_time,
        'filter_date_end'   => $filter_end_time
    );
    $this->load->model('report/sale');
    $res = $this->model_report_sale->getOrdersProductsByUid($filter_data);
    $total = $this->model_report_sale->getTotalOrdersProductsByUid($filter_data);

    // $this->log->write($uid);
    // $this->log->write($res);
    $data['items'] = array();
    foreach ($res as $item) {
      $data['items'][] = array(
        'store_name'    => $item['repo_name'],
        'prod_name'     => $item['prod_name'],
        'model'         => $item['model'],
        'ovd_name'      => $item['ovd_name'],
        'start_time'    => $item['date_start'],
        'end_time'      => $item['date_end'],
        'amount'        => $item['total'],
        'quantity'      => $item['quantity']
      );
    }

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
    $pagination->url = $this->url->link('repository/report/store_product_sale', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
    $data['pagination'] = $pagination->render();
    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('repository/report_store_product_sale.tpl', $data));
  }

  public function repo_topo() {

    if (isset($this->request->get['filter_name'])) {
      $filter_name = $this->request->get['filter_name'];
    } else {
      $filter_name = null;
    }

    if (isset($this->request->get['filter_type'])) {
      $filter_type = $this->request->get['filter_type'];
    } else {
      $filter_type = null;
    }


    $data['filter_name'] = $filter_name;
    $data['filter_type'] = $filter_type;
    $data['token'] = $this->session->data['token'];

    $this->load->language('repository/report_repo_topo');
    $this->document->setTitle($this->language->get('heading_title'));

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_list'] = $this->language->get('text_list');

    $data['column_repo_name'] = $this->language->get('column_repo_name');
    $data['column_repo_type'] = $this->language->get('column_repo_type');
    $data['column_namelist'] = $this->language->get('column_namelist');

    $data['text_no_results'] = $this->language->get('text_no_results');

    $data['entry_name']      = $this->language->get('entry_name');
    $data['entry_type']      = $this->language->get('entry_type');
    $data['button_filter']   = $this->language->get('button_filter');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('repository/report/repo_topo', 'token=' . $this->session->data['token'], 'SSL')
    );

    $limit = $this->config->get('config_limit_admin');
    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    if (isset($this->request->get['uid'])) {
      $uid = $this->request->get['uid'];
    } else {
      $uid = $this->user->getId();
    }

    // Data provider
    $filter_data = array(
        'filter_name'   => $filter_name,
        'filter_type'   => $filter_type,
        'start'         => ($page-1)*$limit,
        'limit'         => $limit
    );
    $this->load->model('report/repository');
    $results = $this->model_report_repository->getRepoUser_topo($filter_data);
    $total = $this->model_report_repository->getTotalRepoUser_topo($filter_data);
    $datz['items'] = array();
    foreach ($results as $result) {
        $tmp = '';
        $i = 0;
        foreach ($result['users'] as $user) {
            if ($i) {
                $tmp .= ',&nbsp;';
            }
            $i++;
            $tmp .= $user;
        }
        $data['items'][] = array(
            'repo_name'     => $result['name'],
            'repo_type'     => $result['type'],
            'namelist'      => $tmp
        );
    }

    $url = '';

    if (isset($this->request->get['filter_name'])) {
      $url .= '&filter_name='.urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_type'])) {
      $url .= '&filter_type='.urlencode(html_entity_decode($filter_type, ENT_QUOTES, 'UTF-8'));
    }

    $pagination = new Pagination();
    if (isset($total)) {
      $pagination->total = $total;
    } else {
      $pagination->total = 1;
    }
    $pagination->page = $page;
    $pagination->limit = $limit;
    $pagination->url = $this->url->link('repository/report/repo_topo', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
    $data['pagination'] = $pagination->render();
    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('repository/report_repo_topo.tpl', $data));
  }

  public function trans() {
    $this->load->language('repository/report_trans');
    $this->document->setTitle($this->language->get('heading_title'));

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_list'] = $this->language->get('text_list');

    $data['column_send_time']      = $this->language->get('column_send_time');
    $data['column_send_repo']      = $this->language->get('column_send_repo');
    $data['column_receive_repo']   = $this->language->get('column_receive_repo');
    $data['column_prod_name']      = $this->language->get('column_prod_name');
    $data['column_ovd_name']       = $this->language->get('column_ovd_name');
    $data['column_quantity']       = $this->language->get('column_quantity');

    $data['text_no_results'] = $this->language->get('text_no_results');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('repository/report/trans', 'token=' . $this->session->data['token'], 'SSL')
    );

    $limit = $this->config->get('config_limit_admin');
    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    if (isset($this->request->get['uid'])) {
      $uid = $this->request->get['uid'];
    } else {
      $uid = $this->user->getId();
    }

    // Data provider.
    $this->load->model('report/repository');
    $data['items'] = $this->model_report_repository->getProducts_trans_ongoing(array(
        'start'     => ($page-1)*$limit,
        'limit'     => $limit
    ));
    $total = $this->model_report_repository->getTotalProduct_trans_ongoing();

    $url = '';
    $pagination = new Pagination();
    if (isset($total)) {
      $pagination->total = $total;
    } else {
      $pagination->total = 1;
    }
    $pagination->page = $page;
    $pagination->limit = $limit;
    $pagination->url = $this->url->link('repository/report/trans', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
    $data['pagination'] = $pagination->render();
    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('repository/report_trans.tpl', $data));
  }

  public function store_sale_autocomplete() {
    $json = array();

    if (isset($this->request->get['filter_repo_name'])) {
        $this->load->model('report/sale');
        if (isset($this->request->get['uid'])) {
            $uid = $this->request->get['uid'];
        } else {
            $uid = $this->user->getId();
        }
        $results = $this->model_report_sale->getOrdersbyUid(array(
            'user_id'       => $uid,
            'filter_repo_name'   => $this->request->get['filter_repo_name']
        ));
        // $results = $this->model_report_sale->getOrdersProductsByUid(array(
        //     'user_id'           => $uid,
        //     'filter_repo_name'  => $this->request->get['filter_repo_name']
        // ));

        foreach ($results as $res) {
            $json[] = array('store_name' => $res['repo_name']);
        }
        $json = array_unique($json);
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function repo_topo_autocomplete() {
    $json = array();
    $this->load->model('report/repository');

    if (isset($this->request->get['filter_name'])) {
        $results = $this->model_report_repository->getRepoUser_topo(array(
            'filter_repo_name' => $this->request->get['filter_repo_name']
        ));
        foreach ($results as $res) {
            $json[] = $res['name'];
        }
    }

     if (isset($this->request->get['filter_type'])) {
        $results = $this->model_report_repository->getRepoUser_topo(array(
            'filter_type' => $this->request->get['filter_type']
        ));
        foreach ($results as $res) {
            $json[] = $res['type'];
        }
        $json = array_unique($json);
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function inventory_autocomplete() {
    $json = array();

    $this->load->model('catalog/repository');

    if (isset($this->request->get['uid'])) {
        $uid = $this->request->get['uid'];
    } else {
        $uid = $this->user->getId();
    }

    if (isset($this->request->get['filter_repo_name'])) {
        $results = $this->model_catalog_repository->getRepository_pdbyUid(array(
            'user_id'   => $uid,
            'filter_repo_name'  => $this->request->get['filter_repo_name'],
        ));
        foreach ($results as $res) {
            $json[] = $res['repo_name'];
        }
        $json = array_unique($json);
    }

    if (isset($this->request->get['filter_prod_name'])) {
        $results = $this->model_catalog_repository->getRepository_pdbyUid(array(
            'user_id'   => $uid,
            'filter_prod_name'  => $this->request->get['filter_prod_name']
        ));
        $prod_id_tmp = array();
        foreach ($results as $res) {
            if(!in_array($res['product_id'], $prod_id_tmp)){
                $prod_id_tmp[] = $res['product_id'];
                $json[] = array(
                    'pdname' => $res['prod_name'].' ['.$res['model'].']',
                    'product_id' => $res['product_id']
                );
            }
        }
        //$json = array_unique($json);
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function store_product_sale_autocomplete() {
    $json = array();

    if (isset($this->request->get['filter_repo_name'])) {
      $this->load->model('report/sale');
      if (isset($this->request->get['uid'])) {
          $uid = $this->request->get['uid'];
      } else {
          $uid = $this->user->getId();
      }
      $results = $this->model_report_sale->getOrdersProductsByUid(array(
          'user_id'           => $uid,
          'filter_repo_name'  => $this->request->get['filter_repo_name']
      ));

      foreach ($results as $res) {
          $json[] = $res['repo_name'];
      }
      $json = array_unique($json);
    }

    if (isset($this->request->get['filter_prod_name'])) {
      $this->load->model('report/sale');
      if (isset($this->request->get['uid'])) {
        $uid = $this->request->get['uid'];
      } else {
        $uid = $this->user->getId();
      }
      $results = $this->model_report_sale->getOrdersProductsByUid(array(
        'user_id'           => $uid,
        'filter_prod_name'  => $this->request->get['filter_prod_name']
      ));

      $prod_id_tmp = array();
      foreach ($results as $res) {
          if (!in_array($res['product_id'], $prod_id_tmp)) {
              $prod_id_tmp[] = $res['product_id'];
              $json[] = array(
                  'pdname' => $res['prod_name'].' ['.$res['model'].']',
                  'product_id' => $res['product_id']
              );
          }
      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function history_autocomplete() {
    $json = array();

    $this->load->model('catalog/repository');

    if (isset($this->request->get['uid'])) {
        $uid = $this->request->get['uid'];
    } else {
        $uid = $this->user->getId();
    }

    if (isset($this->request->get['filter_repo_name'])) {
        $results = $this->model_catalog_repository->getRepository_inout(array(
            'user_id' => $uid,
            'filter_repo_name' => $this->request->get['filter_repo_name'],
        ));
        foreach ($results as $res) {
            $json[] = $res['repo_name'];
        }
        $json = array_unique($json);
    }

    if (isset($this->request->get['filter_user_name'])) {
        $results = $this->model_catalog_repository->getRepository_inout(array(
            'user_id' => $uid,
            'filter_user_name' => $this->request->get['filter_user_name'],
        ));
        foreach ($results as $res) {
            $json[] = $res['user_name'];
        }
        $json = array_unique($json);
    }

    if (isset($this->request->get['filter_prod_name'])) {
        $results = $this->model_catalog_repository->getRepository_inout(array(
            'user_id' => $uid,
            'filter_prod_name' => $this->request->get['filter_prod_name'],
        ));
        $prod_id_tmp = array();
        foreach ($results as $res) {
            if (!in_array($res['product_id'], $prod_id_tmp)) {
                $prod_id_tmp[] = $res['product_id'];
                $json[] = array(
                    'pdname' => $res['prod_name'].' ['.$res['model'].']',
                    'product_id' => $res['product_id']
                );
            }
        }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

}
