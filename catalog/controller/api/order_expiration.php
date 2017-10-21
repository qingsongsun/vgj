<?php
class ControllerApiOrderExpiration extends Controller {
	public function index() {
		
//		$json = array();
        
        // check magic string to avoid attack
        if (!isset($this->request->get['magic_key']) || $this->request->get['magic_key'] != 'ygtr486DOX') return;
        		
		// First of all, find all orders with order creation date >= 24h
		$orders = $this->getOrdersByTime(86400);
		
		// for test purpose, use short time 250s
		// $orders = $this->getOrdersByTime(250);
		
		if ($orders->num_rows == 0) return;
		
		$this->load->model('checkout/order');
		// set all expired orders 		
		foreach ($orders->rows as $order) {
		    $this->model_checkout_order->addOrderHistory($order['order_id'], 14, '订单逾期未支付'); // 14 = Expired
		}

//		$this->response->addHeader('Content-Type: application/json');
//		$this->response->setOutput(json_encode($json));
	}

    // get all valid customer orders in processing status
	public function getOrdersByTime($time_offset_seconds) {
		$sql = "SELECT * FROM " . DB_PREFIX . "order o WHERE unix_timestamp(NOW()) - unix_timestamp(o.date_added) > '" . $time_offset_seconds . "' AND o.customer_id != 0 AND o.order_status_id = 2";

		return $this->db->query($sql);
	}
}