<?php
/**
* Api for PickUp
* 处理自取订单
*/
class ControllerAppPickup extends Controller
{

	/**
	 * 封装接口返回的格式
	 * @param  [type] $flag [description]
	 * @param  [type] $msg  [description]
	 * @return [type]       [description]
	 * flag:success/error
	 * msg:具体的数据
	 */
	private function result($flag,$msg){

		$result=array();
		$result=array(
			"flag"=>$flag,
			"msg"=>$msg
			);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($result));

	}

	/**
	 * 扫用户自提码获取必要信息，并加以校验，提供给客户端使用
	 * @return
	 */
	public function getQrcodeInfo(){

		if (!isset($this->request->get['order_id']) || !isset($this->request->get['token_code']) ) {
			$this->result("ERROR","ERROR_NO_GET");
			return;
		}

		if ($this->checkOrderStatus($this->request->get['order_id']) && $this->checkTokenCode($this->request->get['order_id'],$this->request->get['token_code'])) {
			$json=array();
			$json=array(
				'order_id'=>$this->request->get['order_id'],
				'token_code'=>$this->request->get['token_code']
				);
			$this->result("SUCCESS",$json);
			return;

		}else{
			// $this->result("ERROR","ERROR_DATA_VALIDATE");
			return;
		}
	}

	/**
	 * 用于修改订单状态的接口
	 * @return [type] [description]
	 */
	public function updateOrderStatus(){
		/**
		 * 解析客户端传来的json数据
		 * @var [type]
		 */
		$json_data=json_decode(file_get_contents("php://input"),true);

		/**
		 * 简单校验客户端发送的参数是否存在
		 */
		if (!isset($json_data['order_id']) || !isset($json_data['token_code'])) {
			$this->result("ERROR","ERROR_NO_PARAM");
			return;
		}
		/**
		 * 为了方便迭代，此处不接收客户端的status参数，由服务器指定
		 */
		$this->load->model("checkout/order");
		/**
		 * 1、校验订单状态，只有待自提的才能通过该接口改为已发货
		 * 2、校验验证码，只有验证码通过，才能代表前来自提者的合法性
		 *
		 * 若校验成功
		 * 订单状态改为3，已发货
		 */
		if ($this->checkOrderStatus($json_data['order_id']) && $this->checkTokenCode($json_data['order_id'],$json_data['token_code'])) {

			$this->model_checkout_order->addOrderHistory($json_data['order_id'],3,"自取订单成功，订单状态自动改为已发货");
			$this->result("SUCCESS","SUCCESS_PICK_UP");
			return;

		}else{
			// $this->result("ERROR","ERROR_DATA_VALIDATE");
			return;
		}
	}

	/**
	 * 校验订单状态
	 * 待自取的状态为17，若订单不为待自提则校验不通过
	 * @param  [type] $order_id   [description]
	 * @param  [type] $token_code [description]
	 * @return [type]             [description]
	 */
	private function checkOrderStatus($order_id){
		$this->load->model("checkout/order");

		$order_status_id=$this->model_checkout_order->getOrderStatus($order_id);

		if ($order_status_id!=17) {
			$this->result("ERROR","ERROR_ORDER_STATUS_ID");
			return false;
		}else{
			return true;
		}
	}


	/**
	 * 校验随机码
	 * @param  [type] $order_id   [description]
	 * @param  [type] $token_code [description]
	 * @return [type]             [description]
	 */
	private function checkTokenCode($order_id,$token_code){

		$this->load->model('app/pickup_order');

		$real_token_code=$this->model_app_pickup_order->getTokenCode($order_id);

		if ($real_token_code==$token_code) {
			return true;
		}else{
			$this->result("ERROR","ERROR_TOKEN_CODE");
			return false;
		}

	}




}