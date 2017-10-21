<?php
/**
 * Multi-repository organization
 * Created by Luke
 * On 2016/10/16
 */
class ControllerCatalogRepository extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/repository');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/repository');
        $this->getList();
    }

    public function add_prod() {
        $this->load->language('catalog/repository');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/repository');
        $this->load->model('catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateProdForm()) {

            if (empty($this->request->post['filter_prod_id'])) {
                if($this->model_catalog_product->getProductIdByName($this->request->post['filter_prod_name'])){
                    $product_id=$this->model_catalog_product->getProductIdByName($this->request->post['filter_prod_name']);
                }else if ($this->model_catalog_product->getProductIdByModel($this->request->post['filter_prod_name'])) {
                    $product_id=$this->model_catalog_product->getProductIdByModel($this->request->post['filter_prod_name']);
                }else{
                    $this->error['warning']='很抱歉，该产品不存在';
                }
            }else{
                $product_id=$this->request->post['filter_prod_id'];
            }

            $filter_data = array(
                'repository_id' => $this->request->get['repository_id'],
                'product_id' => $product_id,
                'product_option_value_id' => $this->request->post['filter_prod_ov_id'],
                'filter_prod_num' => $this->request->post['filter_prod_num']
                );
            $this->log->write($filter_data);
            $this->model_catalog_repository->createRepository_pd_byId($filter_data);

            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->url->link('catalog/repository/edit', 'token=' . $this->session->data['token'].$url.'&repository_id='.$this->request->get['repository_id'], 'SSL'));
        }
        $this->getProdForm();
    }

    public function add() {

        $this->load->language('catalog/repository');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/repository');

//////////////////////////////////////////////////////////////////////////////////////////
        // $data = array(
        //     //'repo_activity_id' => 12,
        //     'repository_id' => 2,
        //     'product_id' => 285,
        //     'product_ovdname' => '金钞',
        //     'product_num' => 1,
        //     'user_id' => 3,
        //     // 'receive_repository_id' => 2
        //     );
        // $this->model_catalog_repository->addRepository_pd($data);
        //$user_id = 3;
        //$this->model_catalog_repository->getRepository_pdbyUid($user_id);
        //$add_user = array(
        //    'repository_id' => 1,
        //    'user_id'       => 2,
        //    'repository_opt' => 'add 5 into repository 1'
        //    );
        //$this->log->write('xxxxxxxxxxxxxxxxxxx');
        //$this->model_catalog_repository->addRepository_user($add_user);
        //$this->model_catalog_repository->delRepository_user($add_user);
        //$this->log->write($this->model_catalog_repository->getRepositorybyUid($add_user));
        //$this->log->write($this->model_catalog_repository->getTotalRepositorybyUid($add_user));
        //$this->log->write($this->model_catalog_repository->getUserbyrepoId($add_user));
        //$this->log->write($this->model_catalog_repository->getTotalUserbyrepoId($add_user));
        //$this->model_catalog_repository->updateLastOpt($add_user);
        //$this->log->write($this->model_catalog_repository->getLastOpt($add_user));
        //$this->log->write('xxxxxxxxxxxxxxxxxxx');
        //$this->model_catalog_repository->addRepository_opt($add_user);
        //$this->log->write($this->model_catalog_repository->getRepository_opt_hostory($add_user));
////////////////////////////////////////////////////////////////////////////////////////////////




        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            // var_dump($this->request->post);exit();
            // $this->log->write("Comes a post");
            // $this->log->write($this->request->post);

            $this->request->post['repository_type_id']=$this->model_catalog_repository->getRepository_type_id($this->request->post['repository_type_id']);

            $this->model_catalog_repository->addRepository($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->url->link('catalog/repository', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('catalog/repository');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/repository');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            // var_dump($this->request->post['repository_type_id']);exit();
            // var_dump($this->model_catalog_repository->getRepository_type_id($this->request->post['repository_type_id']));exit();
            $filter_data = array(
                'repository_id' => $this->request->post['repository_id'],
                'repository_name' => $this->request->post['repository_name'],
                // huwen added for pickup
                'repository_type_id'=>$this->model_catalog_repository->getRepository_type_id($this->request->post['repository_type_id'])
                );
            $this->model_catalog_repository->editRepository($filter_data);

            // Users management.
            // $this->log->write($this->request->post['users']);
            if (isset($this->request->post['users']) && isset($this->request->get['repository_id'])) {
                $this->model_catalog_repository->delRepository_user(array('repository_id' => $this->request->get['repository_id']));
                foreach ($this->request->post['users'] as $user_id) {
                    $this->model_catalog_repository->addRepository_user(array(
                        'repository_id' => $this->request->get['repository_id'],
                        'user_id' => $user_id
                    ));
                }
            } else if (isset($this->request->get['repository_id'])) {
                $this->model_catalog_repository->delRepository_user(array('repository_id' => $this->request->get['repository_id']));
            }

            // $this->log->write($this->request->post['users']);
            $flag = true;
            foreach ($this->request->post['product_num_delta'] as $row => $delta) {
                $delta = (int)$delta;
                if (!$delta) continue;
                $filter_data = array(
                    'repository_id' => $this->request->post['repository_id'],
                    'product_id' => $this->request->post['product_id'][$row],
                    'product_ovdname'  => $this->request->post['product_ovd_name'][$row],
                    'product_num' => $delta,
                    'user_id' => $this->user->getId()
                    );
                if ($this->model_catalog_repository->addRepository_pd($filter_data) == -1) {
                    $flag = false;
                }
            }
            $this->model_catalog_repository->clearRepository_pd();
            if ($flag) {
                $this->session->data['success'] = $this->language->get('text_success');
            } else {
                $this->error['warning'] = $this->language->get('text_prod_error');
                $this->getList();
                return;
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

            $this->response->redirect($this->url->link('catalog/repository', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/repository');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/repository');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $repository_id) {
                $this->model_catalog_repository->delRepository(array('repository_id' => $repository_id));
            }

            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->url->link('catalog/repository', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function delete_prod() {
        $this->load->language('catalog/repository');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/repository');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_ovdname => $product_id) {
                $filter_data = array(
                    'repository_id' => $this->request->get['repository_id'],
                    'product_id' => $product_id,
                    'product_ovdname' => $product_ovdname
                    );
                // $this->log->write($filter_data);
                $this->model_catalog_repository->delRepository_pd($filter_data);
            }

            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->url->link('catalog/repository', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function repair() {
        $this->load->language('catalog/repository');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/repository');

        if ($this->validateRepair()) {
            // $this->model_catalog_repository->repairCategories();

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/repository', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    public function filter(){
        $this->load->language('catalog/repository');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/repository');
        $this->getForm();
    }

    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'repository_id';
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/repository', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['add'] = $this->url->link('catalog/repository/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('catalog/repository/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['repair'] = $this->url->link('catalog/repository/repair', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['repositories'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $repository_total = $this->model_catalog_repository->getTotalRepositories();

        $results = $this->model_catalog_repository->getRepositories($filter_data);

        foreach ($results as $result) {
            $data['repositories'][] = array(
                'repository_id' => $result['repository_id'],
                'name'        => $result['repository_name'],
                'edit'        => $this->url->link('catalog/repository/edit', 'token=' . $this->session->data['token'] . '&repository_id=' . $result['repository_id'] . $url, 'SSL'),
                'delete'      => $this->url->link('catalog/repository/delete', 'token=' . $this->session->data['token'] . '&repository_id=' . $result['repository_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_id'] = $this->language->get('column_id');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_rebuild'] = $this->language->get('button_rebuild');


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

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_id'] = $this->url->link('catalog/repository', 'token='.$this->session->data['token'].'&sort=repository_id'.$url, 'SSL');
        $data['sort_name'] = $this->url->link('catalog/repository', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $repository_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/repository', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($repository_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($repository_total - $this->config->get('config_limit_admin'))) ? $repository_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $repository_total, ceil($repository_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['is_manager'] = $this->model_catalog_repository->isValidAdmin(array('user_group_id' => $this->user->getGroupId()));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/repository_list.tpl', $data));
    }

    protected function getForm() {
        //Zark added
        if (isset($this->request->get['filter_prod_name'])) {
            $filter_prod_name = $this->request->get['filter_prod_name'];
        } else {
            $filter_prod_name = null;
        }

        if (isset($this->request->get['filter_prod_ovdname'])) {
            $filter_prod_ovdname = $this->request->get['filter_prod_ovdname'];
        } else {
            $filter_prod_ovdname = null;
        }


        if (isset($this->request->get['filter_prod_id'])) {
            $filter_prod_id = $this->request->get['filter_prod_id'];
        } else {
            $filter_prod_id = null;
        }


        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'product_id';
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

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['repository_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_confirm'] = $this->language->get('text_confirm');

        // huwen added
        $data['entry_type']=$this->language->get('entry_type');
        $data['entry_id'] = $this->language->get('entry_id');
        $data['entry_name'] = $this->language->get('entry_name');

        $data['button_add_prod'] = $this->language->get('button_add_prod');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_delete'] = $this->language->get('button_delete');

        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_repo_product'] = $this->language->get('tab_repo_product');
        $data['tab_user'] = $this->language->get('tab_user');

        $data['column_prod_id'] = $this->language->get('column_prod_id');
        $data['column_prod_model'] = $this->language->get('column_prod_model');
        $data['column_prod_name'] = $this->language->get('column_prod_name');
        $data['column_prod_ovdname'] = $this->language->get('column_prod_ovdname');
        $data['column_prod_num'] = $this->language->get('column_prod_num');
        $data['column_prod_edit'] = $this->language->get('column_prod_edit');
        $data['column_user_name'] = $this->language->get('column_user_name');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['entry_prod_name'] = $this->language->get('entry_prod_name');
        $data['entry_prod_ovdname'] = $this->language->get('entry_prod_ovdname');

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

        $url = '';

        //Zark added
        // if (isset($this->request->get['filter_prod_name'])) {
        //     $url .= '&filter_prod_name=' . urlencode(html_entity_decode($this->request->get['filter_prod_name'], ENT_QUOTES, 'UTF-8'));
        // }

        // if (isset($this->request->get['filter_prod_ovdname'])) {
        //     $url .= '&filter_prod_ovdname=' . urlencode(html_entity_decode($this->request->get['filter_prod_ovdname'], ENT_QUOTES, 'UTF-8'));
        // }
        // //

        // if (isset($this->request->get['sort'])) {
        //     $url .= '&sort=' . $this->request->get['sort'];
        // }

        // if (isset($this->request->get['order'])) {
        //     $url .= '&order=' . $this->request->get['order'];
        // }

        // if (isset($this->request->get['page'])) {
        //     $url .= '&page=' . $this->request->get['page'];
        // }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/repository', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['repository_id'])) {
            $data['action'] = $this->url->link('catalog/repository/add', 'token='.$this->session->data['token'].$url, 'SSL');
            $data['is_add'] = true;
        } else {
            $data['action'] = $this->url->link('catalog/repository/edit', 'token='.$this->session->data['token'].'&repository_id='.$this->request->get['repository_id'].$url, 'SSL');
            $data['is_add'] = false;
        }

        if (isset($this->request->get['repository_id'])) {
            $data['delete_action'] = $this->url->link('catalog/repository/delete_prod', 'token='.$this->session->data['token'].'&repository_id='.$this->request->get['repository_id'].$url, 'SSL');
            $data['add_prod'] = $this->url->link('catalog/repository/add_prod', 'token='.$this->session->data['token'].$url.'&repository_id='.$this->request->get['repository_id'], 'SSL');
        } else {
            $data['delete_action'] = '';
        }

        $data['cancel'] = $this->url->link('catalog/repository', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->get['repository_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $repository_info = $this->model_catalog_repository->getRepositorybyId($this->request->get['repository_id']);
        }

        $data['token'] = $this->session->data['token'];
        $data['repository_types_name']=$this->model_catalog_repository->getRepository_types_name();
        // 基本信息标签
        if (isset($this->request->get['repository_id'])) {
            $repository_info = $this->model_catalog_repository->getRepositoryById($this->request->get['repository_id']);
            $data['repository_name'] = $repository_info['repository_name'];
            $data['repository_id'] = $repository_info['repository_id'];
            // huwen added for pick up
            $this->load->model('catalog/repository');

            // $data['repository_type']=$this->model_catalog_repository->getRepository_type_name($repository_info['repository_type_id']);


            $repository_type_id=$this->model_catalog_repository->getRepository_type_name_byRepo_id($repository_info['repository_id']);
            $data['repository_type']=$this->model_catalog_repository->getRepository_type_name($repository_info['repository_type_id']);

            // var_dump($repository_type_id);exit();
            // var_dump($data['repository_types_name']);exit();


        } else {
            $data['repository_name'] = '';
            $data['repository_id'] = $this->model_catalog_repository->getNewrepositoryId();
        }

        // 商品清单标签
        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['filter_prod_name'])) {
            $url .= '&filter_prod_name=' . urlencode(html_entity_decode($this->request->get['filter_prod_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_prod_ovdname'])) {
            $url .= '&filter_prod_ovdname=' . urlencode(html_entity_decode($this->request->get['filter_prod_ovdname'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_prod_id'])) {
            $url .= '&filter_prod_id='.urlencode(html_entity_decode($this->request->get['filter_prod_id'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (!isset($this->request->get['repository_id'])) {
            $data['sort_prod_name'] = '';
            $data['sort_prod_model'] = '';
            $data['sort_prod_num'] = '';
        } else {
            $url .= '&repository_id='.$this->request->get['repository_id'];
            $data['sort_prod_name'] = $this->url->link('catalog/repository/edit', '&token='.$this->session->data['token'].$url.'&sort=pdname', 'SSL');
            $data['sort_prod_model'] = $this->url->link('catalog/repository/edit', '&token='.$this->session->data['token'].$url.'&sort=model', 'SSL');
            $data['sort_prod_num'] = $this->url->link('catalog/repository/edit', '&token='.$this->session->data['token'].$url.'&sort=product_num', 'SSL');
        }

        $data['order'] = $order;
        $data['sort'] = $sort;

        if (isset($this->request->get['repository_id'])) {
            $filter_data = array(
                'repository_id' => $this->request->get['repository_id'],
                'filter_prod_id' => $filter_prod_id,
                'filter_prod_ovdname' => $filter_prod_ovdname,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                'limit' => $this->config->get('config_limit_admin')
                );

            $results = $this->model_catalog_repository->getRepository_pdbyrepoId($filter_data);

            $data['products'] = array();
            foreach ($results as $result) {
                $data['products'][] = array(
                    'prod_id'    => $result['product_id'],
                    'prod_model' => $result['model'],
                    'prod_name' => $result['pdname'],
                    'prod_ovdname' => $result['ovdname'],
                    'prod_num'  => $result['product_num']
                    );
            }
            if(isset($this->request->get['filter_prod_ovdname']) || isset($this->request->get['filter_prod_id']))
                $total = $this->model_catalog_repository->getTotalRepositort_pdbyfilter($filter_data);
            else
                $total = $this->model_catalog_repository->getTotalRepository_pdbyrepoId($filter_data);
            $pagination = new Pagination();
            if (isset($total)) {
                $pagination->total = $total;
            } else {
                $pagination->total = 1;
            }
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_limit_admin');
            $url = '';
            $url .= '&repository_id='.$this->request->get['repository_id'];

            if (isset($this->request->get['filter_prod_name'])) {
                $url .= '&filter_prod_name=' . urlencode(html_entity_decode($this->request->get['filter_prod_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_prod_ovdname'])) {
                $url .= '&filter_prod_ovdname=' . urlencode(html_entity_decode($this->request->get['filter_prod_ovdname'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_prod_id'])) {
                $url .= '&filter_prod_id='.urlencode(html_entity_decode($this->request->get['filter_prod_id'], ENT_QUOTES, 'UTF-8'));
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

            $pagination->url = $this->url->link('catalog/repository/edit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
            $data['pagination'] = $pagination->render();
            $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));
        }

        // 用户管理标签
        if (isset($this->request->get['repository_id'])) {
            $data['users'] = $this->model_catalog_repository->getUserbyrepoId(array('repository_id' => $this->request->get['repository_id']));
            $this->load->model('user/user');
            $results = $this->model_user_user->getUsers();
            $data['all_users'] = array();
            foreach ($results as $result) {
                $data['all_users'][] = array(
                    'user_id' => $result['user_id'],
                    'user_name' => $result['username']
                );
            }
        }

        $data['filter_prod_name'] = $filter_prod_name;
        $data['filter_prod_ovdname'] = $filter_prod_ovdname;
        $data['filter_prod_id'] = $filter_prod_id;
        // $this->log->write($this->user->getId());
        $data['is_manager'] = $this->model_catalog_repository->isValidAdmin(array('user_group_id'=>$this->user->getGroupId()));
        // $this->log->write($data['is_manager']);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/repository_form.tpl', $data));
    }

    protected function getProdForm() {

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = $this->language->get('text_prod_add');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_prod_id'] = $this->language->get('entry_prod_id');
        $data['entry_prod_num'] = $this->language->get('entry_prod_num');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['entry_prod_name'] = $this->language->get('entry_prod_name');
        $data['entry_prod_ovdname'] = $this->language->get('entry_prod_ovdname');
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
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

        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/repository', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['action'] = $this->url->link('catalog/repository/add_prod', 'token='.$this->session->data['token'].'&repository_id='.$this->request->get['repository_id'].$url, 'SSL');
        $data['repository_id'] = $this->request->get['repository_id'];

        $data['add_prod'] = $this->url->link('catalog/repository/add_prod', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['cancel'] = $this->url->link('catalog/repository/edit', 'token=' . $this->session->data['token'].'&repository_id='.$this->request->get['repository_id'].$url, 'SSL');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/repository_prod_form.tpl', $data));
    }

    // 检测提交的表格是否合法, 是则返回 true.
    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/repository')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (utf8_strlen($this->request->post['repository_name']) < 3 || utf8_strlen($this->request->post['repository_name']) > 255) {
            $this->error['warning'] = $this->language->get('error_name');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateProdForm() {
        if (!$this->user->hasPermission('modify', 'catalog/repository')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // $this->log->write(substr(explode('[', $this->request->post['filter_prod_name'])[0], 0, -1));


        if (empty($this->request->post['filter_prod_id'])) {
            if($this->model_catalog_product->getProductIdByName($this->request->post['filter_prod_name'])){
                $product_id=$this->model_catalog_product->getProductIdByName($this->request->post['filter_prod_name']);
            }else if ($this->model_catalog_product->getProductIdByModel($this->request->post['filter_prod_name'])) {
                $product_id=$this->model_catalog_product->getProductIdByModel($this->request->post['filter_prod_name']);
            }else{
                $this->error['warning']='很抱歉，该产品不存在';
            }

        }else{
            $product_id=$this->request->post['filter_prod_id'];
        }



        $filter_data = array(
            'repository_id' => $this->request->get['repository_id'],
            'filter_prod_id' => $product_id,
            'filter_prod_ovdname' => $this->request->post['filter_prod_ovdname'],
            'filter_prod_num'  => $this->request->post['filter_prod_num']
            );

        $res = $this->model_catalog_repository->isValidProd_id($filter_data);
        $this->log->write($res);
        // p($res);exit();
        if (in_array($res['result_code'], array(1,2,3,4))) {
            $this->error['warning'] = $this->language->get('reslut_code_value_'.(int)$res['result_code']);
        }

        return !$this->error;
    }

    // 检测是否具有删除权限, 是则返回 true.
    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/repository')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    // 检测是否具有重建权限, 是则返回 true.
    protected function validateRepair() {
        if (!$this->user->hasPermission('modify', 'catalog/repository')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();
        // $this->log->write($this->request->get);
        if (isset($this->request->get['filter_prod_name']) || isset($this->request->get['filter_prod_ovdname'])) {
            $this->load->model('catalog/repository');
            //$this->load->model('catalog/option');
            //$this->log->write($this->request->get);
            //$repository_id = $this->request->get['repository_id'];

            if (isset($this->request->get['filter_prod_name'])) {
                $filter_prod_name = $this->request->get['filter_prod_name'];
            } else {
                $filter_prod_name = '';
            }

            if (isset($this->request->get['filter_prod_ovdname'])) {
                $filter_prod_ovdname = $this->request->get['filter_prod_ovdname'];
            } else {
                $filter_prod_ovdname = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                //'repository_id'     => $repository_id,
                'filter_prod_name'  => $filter_prod_name,
                'filter_prod_ovdname' => $filter_prod_ovdname,
                'start'        => 0,
                'limit'        => $limit
            );

            // $this->log->write('Filter info begin ===========');
            // $this->log->write($filter_data);
            // $this->log->write('Filter info end ===========');

            $flag = $this->request->get['flag'];
            $results = $this->model_catalog_repository->getRepository_pdnames($filter_data);

            // $this->log->write('Filter result begin ===========');
            // $this->log->write($results);
            // $this->log->write('Filter result end ===========');

            if($flag == 1){
                $pd_name = array();
                foreach ($results as $result) {

                    if(!in_array($result['pdname'], $pd_name)){
                        $pd_name[] = $result['pdname'];
                        $json[] = array(
                            'filter_prod_name'       => strip_tags(html_entity_decode($result['pdname'].' ['.$result['model'].']', ENT_QUOTES, 'UTF-8')),
                            'product_id'   =>  $result['product_id']
                        );
                    }
                }
            } else if($flag == 2){
                $product_id = $this->request->get['filter_prod_id'];
                $results = $this->model_catalog_repository->getRepository_ovd_names($product_id);
                foreach ($results as $result) {
                    $json[] = array(
                        'filter_prod_ovdname'       => strip_tags(html_entity_decode($result['ovdname'], ENT_QUOTES, 'UTF-8')),
                        'product_ov_id'   =>  $result['product_option_value_id']
                    );
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}

?>
