<?php
/**
* 处理微信code，获取session_key
*/
class ControllerAppWxsession extends Controller
{

	private $json_data=array();
	private $json=array();


	public function index(){
		$this->json_data=json_decode(file_get_contents("php://input"),true);

		$url='https://api.weixin.qq.com/sns/jscode2session?appid='.$this->json_data['appid'].'&secret='.$this->json_data['secret'].'&js_code='.$this->json_data['jscode'].'&grant_type=authorization_code';

		header('Location:'.$url);exit();
	}

	public function setSession(){
		$this->json_data=json_decode(file_get_contents("php://input"),true);
		// $redis=new Redis();
		// $redis->connect('127.0.0.1',6379);
		// $redis->set($this->json_data['openid'],$this->json_data['session_key'].$this->json_data['openid']);

		$this->json['rd_session']=$this->json_data['openid'];

		$this->session->data['test']='heihei';

		$this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));
	}

	// public function testRedis(){

	// 	$redis=new Redis();
	// 	$redis->connect('127.0.0.1',6379);
	// 	$redis->set('test','111');
	// 	echo $redis->get('test');
	// }

	public function getSession(){

		$this->json_data=json_decode(file_get_contents("php://input"),true);

		// session_id($this->request->get['session_id']);
		// $this->json['heihei']=!session_id();

		// 客户端第二次握手的时候，url带着这个session_id,服务器接收到这个session_id并且与redis中的比对
		// $redis=new Redis();
		// $redis->connect('127.0.0.1',6379);

		$this->json['test']=$this->session->data['test'];
		$this->json['test2']=$this->session->data['test2'];

		$this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));
	}

	public function testSession(){
		session_id($this->request->get['session_id']);


		$this->json['test']=$this->session->data['test'];
		$this->json['test2']=$this->session->data['test2'];

		$this->json['session']=$this->session->getId();

		$this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));
	}

	public function getSessionId(){
		// 客户端第一次访问服务器，服务器返回一个session_id给客户端,并且存入redis
		$json['heihei']=!session_id();
		$json['session_id']=$this->session->getId();

		// $redis=new Redis();
		// $redis->connect('127.0.0.1',6379);
		// $redis->set('session_id',$this->session->getId());

		$this->session->data['test']='heihei';
		$this->session->data['test2']='heihei2';
		$this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($json));
	}


}