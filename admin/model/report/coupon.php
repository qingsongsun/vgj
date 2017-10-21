<?php
class ModelReportCoupon extends Model {
	public function getCutomerCoupons($data=array()){

		$sql="SELECT c.coupon_id ,c.name ,c.code ,cc.customer_id,c.date_added,cc.coupon_status FROM ".DB_PREFIX."coupon c LEFT JOIN ".DB_PREFIX."customer_coupon cc on c.coupon_id=cc.coupon_id";

		$implode = array();

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "customer_id LIKE " . (int)$data['filter_customer_id'] ;
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY cc.coupon_id ORDER BY customer_id DESC";

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

	public function getTotalCustomers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_coupon";

		$implode = array();

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "customer_id LIKE " . (int)$data['filter_customer_id'] ;
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['email'];
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}
}