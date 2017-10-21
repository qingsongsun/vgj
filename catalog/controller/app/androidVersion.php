<?php
/**
* 安卓版本控制
*/
class ControllerAppAndroidversion extends Controller
{
	private $json_data=array();
	private $json=array();


	public function get(){
		$this->json_data = json_decode(file_get_contents("php://input"), true);


		$json['versionName']='1.1.1.6';
		$json['versionCode']=3;

		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($json));
	}


}