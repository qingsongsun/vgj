<?php
/**
* 自提码的数据层
*/
class ModelAccountPickupQrcode extends Model
{

	public function getPickupQrcodeByCustomerId($customer_id){
		/**
		 * 先获取该用户所有的待自取订单
		 */
		$order_query=$this->db->query("SELECT order_id FROM ".DB_PREFIX."order WHERE order_status_id=17 AND customer_id='".(int)$customer_id."'");

		$orders=array();
		foreach ($order_query->rows as $key => $value) {

			$orders[]=$value['order_id'];
		}
		return $orders;
	}
}