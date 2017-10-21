<?php
class ModelAccountMycoupon extends Model {

	public function getCouponsByCustomer($customer_id) {

		$sql="SELECT coupon_code,name as coupon_description,coupon_status FROM ".DB_PREFIX."customer_coupon as cc LEFT JOIN ".DB_PREFIX."coupon as c on cc.coupon_id=c.coupon_id WHERE customer_id= '".(int)$customer_id."'";

		$sort_data = array(
			'coupon_id',
			'customer_id',
			'coupon_status',
			'coupon_code'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY customer_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function addCustomerCoupon($data){
		// $this->log->write('开始插入优惠券到用户账号里');
		// $this->log->write($data);
		// $this->log->write($data['coupon_id']);
		// var_dump($data);
		$this->db->query("INSERT  INTO ". DB_PREFIX ."customer_coupon SET coupon_id='".(int)$data['coupon_id']."',customer_id='".(int)$data['customer_id']."',coupon_status='未使用',coupon_code='".$this->db->escape($data['coupon_code'])."',coupon_description='".$this->db->escape($data['coupon_description'])."'");
	}

	public function updateCouponStatus($coupon_id){

		$this->db->query("UPDATE ".DB_PREFIX."customer_coupon SET coupon_status='已使用' WHERE coupon_id='".(int)$coupon_id."'");
	}
	public function recoverCouponStatus($coupon_id){
		$this->db->query("UPDATE ".DB_PREFIX."customer_coupon SET coupon_status='未使用' WHERE coupon_id='".(int)$coupon_id."'");
	}

	public function getCoupon($data) {

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_coupon WHERE code = '" . $this->db->escape($data['coupon_code']) . "' AND customer_id='".(int)$data['customer_id']."'");

		return $query->row;
	}
}