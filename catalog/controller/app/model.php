<?php
class ControllerAppModel extends Controller {
	private $error     = array();
	private $json_data = array();
	private $json      = array();

	public function index() {
		$this->json_data = json_decode(file_get_contents("php://input"), true);
		if (isset($this->json_data['model'])) {

			$this->load->model('app/model');

			if ($this->model_app_model->getProductIdByModel($this->json_data['model'])) {
				$this->json['product_id']=$this->model_app_model->getProductIdByModel($this->json_data['model']);
			}else{
				$this->json['product_id']=null;
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($this->json));
		}
	}

}