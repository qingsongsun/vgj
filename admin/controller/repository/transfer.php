<?php
class ControllerRepositoryTransfer extends Controller {
  public function get_repos() {
    if (!isset($this->request->get['uid'])) {
      $this->error('ERROR_NO_UID');
      return;
    }

    $this->load->model('catalog/repository');
    $results = $this->model_catalog_repository->getRepositorybyUid(array('user_id' => $this->request->get['uid']));

    $json = array();
    foreach ($results as $res) {
      $json[] = array(
        'repository_id' => $res['repository_id'],
        'repository_name' => $res['repository_name']
      );
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function select_repo() {
    if (!isset($this->request->get['repository_id'])) {
      $this->error('ERROR_NO_REPO_ID');
      return;
    }
    $this->load->model('catalog/repository');
    $json = $this->model_catalog_repository->getRepo_activity_idByrepoId($this->request->get['repository_id']);
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function auto_select_repo(){
    $json_data=json_decode(file_get_contents("php://input"),true);
    $this->load->model('catalog/repository');
    $json = $this->model_catalog_repository->getRepo_activity_idAuto(array(
        'product_id'=>$json_data['product_id'],
        'repository_id'=>$json_data['repository_id'],
        'product_num'=>$json_data['product_num'],
        'product_option_value_id'=>$json_data['product_option_value_id'],
        'send_repository_id'=>$json_data['send_repository_id'],
      ));
    if (!$json) {
      $json['error']=true;
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function select_id() {
    if (!isset($this->request->get['repo_activity_id'])) {
      $this->error('ERROR_NO_REPO_ACT_ID');
      return;
    }
    $this->load->model('catalog/repository');
    $results = $this->model_catalog_repository->getProdsbyRepo_activity_id($this->request->get['repo_activity_id']);

    $json = array();
    $this->load->model('catalog/product');
    foreach ($results as $res) {
      $json[] = array(
        'product_name'          => $res['product_name'],
        'product_id'            => $res['product_id'],
        'product_option_value'  => array(
          'product_option_value_id' => $res['send_prod_option_value_id'],
          'option_value_name'       => $res['option_value_name'],
        ),
        'quantity'              => $res['send_quantity']
      );
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function finish() {
    if (!isset($this->request->get['uid'])) {
      $this->error('ERROR_NO_UID');
      return;
    }

    if (!isset($this->request->get['repo_activity_id'])) {
      $this->error("ERROR_NO_REPO_ACT_ID");
      return;
    }

    $this->load->model('catalog/repository');
    $res = $this->model_catalog_repository->getRepository_trans_onging_byRepoActivityId($this->request->get['repo_activity_id']);
    if (!isset($res) || !$res) {
      $this->error("ERROR_NO_RECORD_FOUNDED");
      return;
    }
    $this->model_catalog_repository->addRepository_pd_byId(array(
      'repository_id'           => $res['receive_repository_id'],
      'product_id'              => $res['product_id'],
      'product_option_value_id' => $res['send_prod_option_value_id'],
      'product_num'             => $res['send_quantity'],
      'repo_activity_id'        => $this->request->get['repo_activity_id'],
      'user_id'                 => $this->request->get['uid']
    ));

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode(array('success' => true)));
  }

  public function abnormal() {
    if (!isset($this->request->get['uid'])) {
      $this->error("ERROR_NO_UID");
      return;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
      $this->error('ERROR_NO_PRODUCTS_UPLOADED');
      return;
    }
    if (!isset($input['error_reason'])) {
      $this->error("ERROR_NO_ERROR_REASON");
      return;
    }

    $this->load->model('catalog/repository');

    $res = $this->model_catalog_repository->getRepository_trans_onging_byRepoActivityId($input['repo_activity_id']);
    if (!isset($res) || !empty($res)) {
      $this->error("ERROR_NO_RECORD_FOUNDED");
      return;
    }
    $this->model_catalog_repository->addRepository_pd_byId(array(
      'repository_id'           => $res['repository_id'],
      'product_id'              => $input['product_id'],
      'product_option_value_id' => $input['product_option_value_id'],
      'product_num'             => $input['quantity'],
      'repo_activity_id'        => $input['repo_activity_id'],
      'user_id'                 => $this->request->get['uid']
    ));

    $this->model_catalog_repository->addTransErrorReason(array(
      'repo_activity_id'  => $input['repo_activity_id'],
      'error_reason'      => $input['error_reason']
    ));

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode(array('success' => true)));
  }

  public function out() {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
      $this->error('EERROR_NO_PRODUCTS_UPLOADED');
      return;
    }
    if (!isset($this->request->get['uid'])) {
      $this->error('ERROR_NO_UID');
      return;
    }

    $this->load->model('catalog/repository');

    $result = $this->model_catalog_repository->addRepository_pd_byId(array(
      'repository_id'           => $input['send_repo_id'],
      'product_id'              => $input['product_id'],
      'product_option_value_id' => $input['product_option_value_id'],
      'product_num'             => -1*(int)$input['quantity'],
      'receive_repository_id'   => $input['receive_repo_id'],
      'user_id'                 => $this->request->get['uid']
    ));

    if (isset($result) && $result != -1) {
      $json = array('repo_activity_id'=>$result);
    } else {
      $this->error('ERROR_RECORD_CREATED_FAILURE');
      return;
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  private function error($reason) {
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode(array('error'=>$reason)));
  }
}
