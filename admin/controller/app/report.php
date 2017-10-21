<?php
/**
* app报表展示
*/
class ControllerAppReport extends Controller{
	private $error     = array();
	private $json_data = array();
	private $json      = array();

	public function index(){
		$this->json_data=json_decode(file_get_contents("php://input"),true);

		if (isset($this->json_data) && $this->validate()) {
			$group_id=$this->user->getGroupId();
			// 仓库管理员
			if ($group_id==4) {
				$repository_report=array(
					'repository/report/repo_topo,仓库拓扑',
					'repository/report/inventory,仓库库存',
					'repository/report/history,出入库信息',
					'repository/report/trans,转库途中表',
					);

			}elseif ($group_id==5) {
				$repository_report=array(
					'repository/report/store_sale,店铺销售',
					'repository/report/inventory,仓库库存',
					'repository/report/history,出入库信息',
					'sale/customer,客户列表'
					);
			}elseif($group_id==2){
				$repository_report=array(
					'repository/report/store_sale,店铺销售',
					'repository/report/inventory,仓库库存',
					'repository/report/history,出入库信息',
					'sale/customer,客户列表'
					);
			}

			$this->json['report']=$repository_report;
			$this->response->addHeader('Content-Type:application/json');
			$this->response->setOutput(json_encode($this->json));
		}
	}

	protected function validate() {
		if (!isset($this->json_data['username']) || !isset($this->json_data['password']) || !$this->user->login($this->json_data['username'], $this->json_data['password'])) {

			$this->json['error_login']='0';
		}

		return !$this->error;
	}
}