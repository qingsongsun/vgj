<?php
class ControllerReportProduct extends Controller {
  public function index() {
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

    if (isset($this->request->get['filter_quantity'])) {
      $filter_quantity = $this->request->get['filter_quantity'];
    } else {
      $filter_quantity = null;
    }

    if (isset($this->request->get['filter_status'])) {
      $filter_status = $this->request->get['filter_status'];
    } else {
      $filter_status = null;
    }

    $this->load->language('catalog/product');
    $this->load->language('report/product');
    $this->document->setTitle($this->language->get('heading_title'));

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_list'] = $this->language->get('text_list');

    $data['column_creator'] = $this->language->get('column_creator');
    $data['column_prod_name'] = $this->language->get('column_prod_name');
    $data['column_prod_model'] = $this->language->get('column_prod_model');
    $data['column_prod_quantity'] = $this->language->get('column_prod_quantity');
    $data['column_status'] = $this->language->get('column_status');
    $data['column_inventory'] = $this->language->get('column_inventory');
    $data['text_no_results'] = $this->language->get('text_no_results');

    $data['entry_name'] = $this->language->get('entry_name');
    $data['entry_model'] = $this->language->get('entry_model');
    $data['entry_quantity'] = $this->language->get('entry_quantity');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_creator'] = $this->language->get('entry_creator');
    $data['button_filter'] = $this->language->get('button_filter');

    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');

    $data['token'] = $this->session->data['token'];

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );
    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('report/product', 'token=' . $this->session->data['token'], 'SSL')
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

    if (isset($this->request->get['uid'])) {
      $uid = $this->request->get['uid'];
    } else {
      $uid = $this->user->getId();
    }

    // 考虑用户的角色，如果为 boss 则列出所有商品
    // 如果为产品负责人，则列出其由其创建的商品
    $ADMIN_GROUP = array(1, 2);
    $PROD_MANAGER_GROUP_ID = 3;

    $filter_data = array(
      'filter_name'   => $filter_name,
      'filter_model'    => $filter_model,
      'filter_quantity' => $filter_quantity,
      'filter_status'   => $filter_status,
      'filter_creator'  => $filter_creator,
      'sort'            => $sort,
      'order'           => $order,
      'start'           => ($page - 1) * $limit,
      'limit'           => $limit
    );

    if (in_array($this->user->getGroupId(), $ADMIN_GROUP)) {
      $this->load->model('catalog/product');
      $this->load->model('catalog/repository');
      $total = $this->model_catalog_product->getTotalProducts($filter_data);
      $results = $this->model_catalog_product->getProducts($filter_data);

      $data['items'] = array();
      foreach ($results as $result) {
        $data['items'][] = array(
          'creator'     => $result['username'],
          'prod_name'   => $result['name'],
          'prod_model'  => $result['model'],
          'quantity'    => $result['quantity'],
          'status'      => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
          'inventory'   => $this->model_catalog_repository->getRepository_pd_numbyprodId($result['product_id'])
        );
      }
    } else if ($this->user->getGroupId() == $PROD_MANAGER_GROUP_ID) {
      $this->load->model('catalog/product');

      $filter_data['uid'] = $uid;

      $total = $this->model_catalog_product->getTotalProducts($filter_data);
      $results = $this->model_catalog_product->getProducts($filter_data);

      $data['items'] = array();
      foreach ($results as $result) {
        $data['items'][] = array(
          'creator'     => $result['username'],
          'prod_name'   => $result['name'],
          'prod_model'  => $result['model'],
          'quantity'    => $result['quantity'],
          'status'      => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
        );
      }
    }

    $data['filter_creator'] = $filter_creator;
    $data['filter_name'] = $filter_name;
    $data['filter_model'] = $filter_model;
    $data['filter_quantity'] = $filter_quantity;
    $data['filter_status'] = $filter_status;

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

    if (isset($this->request->get['filter_quantity'])) {
      $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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
    if (isset($total)) {
      $pagination->total = $total;
    } else {
      $total = 1;
      $pagination->total = 1;
    }
    $pagination->page = $page;
    $pagination->limit = $limit;
    $pagination->url = $this->url->link('report/product', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
    $data['pagination'] = $pagination->render();
    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('report/product.tpl', $data));
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
}
