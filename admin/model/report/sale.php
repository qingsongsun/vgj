<?php
class ModelReportSale extends Model {
	public function getTotalSales($data = array()) {
		// huwen mod
		$sql = "SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '3' or order_status_id='5'";

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByCountry() {
		$query = $this->db->query("SELECT COUNT(*) AS total, SUM(o.total) AS amount, c.iso_code_2 FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "country` c ON (o.payment_country_id = c.country_id) WHERE o.order_status_id > '0' GROUP BY o.payment_country_id");

		return $query->rows;
	}

	public function getTotalOrdersByDay() {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = array();

		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByWeek() {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = array();

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$order_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = array(
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByMonth() {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = array();

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$order_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = array(
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByYear() {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = array(
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $order_data;
	}


	public function getTotalOrdersbyUid($data = array()) {
		$complete_status = $this->config->get('config_complete_status');
		$complete_status_str = '(';
		foreach ($complete_status as $i => $item) {
			if ($i > 0) {
				$complete_status_str .= ',';
			}
			$complete_status_str .= $item;
		}
		$complete_status_str .= ')';
		$sql = "SELECT
		 COUNT(DISTINCT order_selec.repository_id) total
		 FROM
		 ((SELECT o1.order_id, r.repository_id FROM ".DB_PREFIX."repository_user ru
		 LEFT JOIN ".DB_PREFIX."repository r
		 ON(ru.repository_id = r.repository_id)
		 LEFT JOIN ".DB_PREFIX."repository_user ru1
		 ON(ru.repository_id=ru1.repository_id)
		 LEFT JOIN ".DB_PREFIX."order o1
		 ON(ru1.user_id = o1.user_id)
		 WHERE ru.user_id = '".(int)$data['user_id']."'
		 AND r.repository_type_id = 1
		 AND o1.order_status_id IN ".$complete_status_str.")
		 UNION (SELECT rth.order_id, r_.repository_id FROM ".DB_PREFIX."repository_user ru_
		 LEFT JOIN ".DB_PREFIX."repository r_
		 ON(ru_.repository_id=r_.repository_id)
		 LEFT JOIN ".DB_PREFIX."repository_trans_history rth
		 ON(r_.repository_id=rth.send_repository_id)
		 LEFT JOIN ".DB_PREFIX."order o2
		 ON(rth.order_id = o2.order_id)
		 WHERE ru_.user_id= '".(int)$data['user_id']."'
		 AND rth.for_order = 1
		 AND r_.repository_type_id = 1
		 AND o2.order_status_id IN ".$complete_status_str.")) AS order_selec

		 LEFT JOIN ".DB_PREFIX."order o
		 ON(order_selec.order_id = o.order_id)
		 LEFT JOIN ".DB_PREFIX."repository r1
		 ON(order_selec.repository_id=r1.repository_id)";

		if (isset($data['filter_date_start']) && !empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_date_end']) && !empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if(isset($data['filter_name']) && !empty($data['filter_name'])){
			$sql .= " AND r1.repository_name = '" . $this->db->escape($data['filter_name']) . "'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getOrdersByStore($data=array()){

		$complete_status = $this->config->get('config_complete_status');
	    $complete_status_str = '(';
	    foreach ($complete_status as $i => $item) {
	      if ($i > 0) {
	        $complete_status_str .= ',';
	      }
	      $complete_status_str .= $item;
	    }
	    $complete_status_str .= ')';
		$sql = "SELECT
		DISTINCT(o.order_id),
		r1.repository_name AS repo_name,
	 	o.comment,
	 	CONCAT(o.firstname, ' ',o.lastname ) AS customer,
	 	(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status,
	 	o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified
		FROM (
		(SELECT o1.order_id, r.repository_id FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id)
		LEFT JOIN ".DB_PREFIX."repository_user ru1 ON(ru.repository_id=ru1.repository_id)
		LEFT JOIN ".DB_PREFIX."order o1 ON(ru1.user_id = o1.user_id)
		WHERE ru.user_id = '".(int)$data['user_id']."' AND r.repository_type_id = 1 AND o1.order_status_id IN ".$complete_status_str.")
		UNION
		(SELECT rth.order_id, r_.repository_id FROM ".DB_PREFIX."repository_user ru_ LEFT JOIN ".DB_PREFIX."repository r_ ON(ru_.repository_id=r_.repository_id)
		LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(r_.repository_id=rth.send_repository_id)
		LEFT JOIN ".DB_PREFIX."order o2 ON (rth.order_id = o2.order_id)
		WHERE ru_.user_id= '".(int)$data['user_id']."' AND rth.for_order = 1 AND r_.repository_type_id = 1 AND o2.order_status_id IN ".$complete_status_str.")
		)
		AS order_selec
		LEFT JOIN ".DB_PREFIX."order o ON(order_selec.order_id = o.order_id)

		LEFT JOIN ".DB_PREFIX."repository r1 ON(order_selec.repository_id=r1.repository_id)
		LEFT JOIN ".DB_PREFIX."order_product op ON(order_selec.order_id = op.order_id)
		LEFT JOIN ".DB_PREFIX."order_option oo ON(op.order_product_id = oo.order_product_id)
		LEFT JOIN ".DB_PREFIX."order_total ot ON (o.order_id = ot.order_id AND ot.code = 'coupon')";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		if(isset($data['filter_repo_name']) && !empty($data['filter_repo_name'])){
			$sql .= " AND r1.repository_name LIKE '" . $this->db->escape($data['filter_repo_name']) . "%'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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
	public function getTotalOrdersByStore($data = array()) {
		$complete_status = $this->config->get('config_complete_status');
	    $complete_status_str = '(';
	    foreach ($complete_status as $i => $item) {
	      if ($i > 0) {
	        $complete_status_str .= ',';
	      }
	      $complete_status_str .= $item;
	    }
	    $complete_status_str .= ')';
		$sql = "SELECT
		COUNT(*) as total
		FROM (
		(SELECT o1.order_id, r.repository_id FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id)
		LEFT JOIN ".DB_PREFIX."repository_user ru1 ON(ru.repository_id=ru1.repository_id)
		LEFT JOIN ".DB_PREFIX."order o1 ON(ru1.user_id = o1.user_id)
		WHERE ru.user_id = '".(int)$data['user_id']."' AND r.repository_type_id = 1 AND o1.order_status_id IN ".$complete_status_str.")
		UNION
		(SELECT rth.order_id, r_.repository_id FROM ".DB_PREFIX."repository_user ru_ LEFT JOIN ".DB_PREFIX."repository r_ ON(ru_.repository_id=r_.repository_id)
		LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(r_.repository_id=rth.send_repository_id)
		LEFT JOIN ".DB_PREFIX."order o2 ON (rth.order_id = o2.order_id)
		WHERE ru_.user_id= '".(int)$data['user_id']."' AND rth.for_order = 1 AND r_.repository_type_id = 1 AND o2.order_status_id IN ".$complete_status_str.")
		)
		AS order_selec
		LEFT JOIN ".DB_PREFIX."order o ON(order_selec.order_id = o.order_id)

		LEFT JOIN ".DB_PREFIX."repository r1 ON(order_selec.repository_id=r1.repository_id)
		LEFT JOIN ".DB_PREFIX."order_product op ON(order_selec.order_id = op.order_id)
		LEFT JOIN ".DB_PREFIX."order_option oo ON(op.order_product_id = oo.order_product_id)
		LEFT JOIN ".DB_PREFIX."order_total ot ON (o.order_id = ot.order_id AND ot.code = 'coupon')";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		if(isset($data['filter_repo_name']) && !empty($data['filter_repo_name'])){
			$sql .= " AND r1.repository_name LIKE '" . $this->db->escape($data['filter_repo_name']) . "%'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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

		return $query->row['total'];
	}
	public function getOrderCommentById($order_id){

		$query=$this->db->query("SELECT comment FROM ".DB_PREFIX."order_history WHERE order_id='".(int)$order_id."'");
		if ($query->num_rows) {
			return end($query->rows)['comment'];
		}else{
			return false;
		}
	}

	public function getCouponTotal($data=array()){
		$complete_status = $this->config->get('config_complete_status');
	    $complete_status_str = '(';
	    foreach ($complete_status as $i => $item) {
	      if ($i > 0) {
	        $complete_status_str .= ',';
	      }
	      $complete_status_str .= $item;
	    }
	    $complete_status_str .= ')';

	    $sql="SELECT
			DISTINCT(ot.order_total_id),
			ot.value AS total

			FROM (

			(SELECT o1.order_id, r.repository_id FROM ".DB_PREFIX."repository_user ru
				LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id)
			 	LEFT JOIN ".DB_PREFIX."repository_user ru1 ON(ru.repository_id=ru1.repository_id)
			 	LEFT JOIN ".DB_PREFIX."order o1 ON(ru1.user_id = o1.user_id)
			 		WHERE ru.user_id = '".(int)$data['user_id']."'
			 		AND r.repository_type_id = 1
			 		AND o1.order_status_id IN ".$complete_status_str.")

			 UNION

			 (SELECT rth.order_id, r_.repository_id FROM ".DB_PREFIX."repository_user ru_
			 	LEFT JOIN ".DB_PREFIX."repository r_ ON(ru_.repository_id=r_.repository_id)
			 	LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(r_.repository_id=rth.send_repository_id)
			 	LEFT JOIN ".DB_PREFIX."order o2 ON (rth.order_id = o2.order_id)
			 		WHERE ru_.user_id= '".(int)$data['user_id']."'
			 		AND rth.for_order = 1
			 		AND r_.repository_type_id = 1
			 		AND o2.order_status_id IN ".$complete_status_str."))
			 		AS order_selec

		 		LEFT JOIN ".DB_PREFIX."order o ON(order_selec.order_id = o.order_id)
		 		LEFT JOIN ".DB_PREFIX."repository r1 ON(order_selec.repository_id=r1.repository_id)
		 		LEFT JOIN ".DB_PREFIX."order_product op ON(order_selec.order_id = op.order_id)
		 		LEFT JOIN ".DB_PREFIX."order_option oo ON(op.order_product_id = oo.order_product_id)
		 		LEFT JOIN ".DB_PREFIX."order_total ot ON (o.order_id = ot.order_id AND ot.code = 'coupon')";



		if (isset($data['filter_order_status_id']) && !empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (isset($data['filter_date_start']) && !empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_date_end']) && !empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if(isset($data['filter_repo_name']) && !empty($data['filter_repo_name'])){
			$sql .= " AND r1.repository_name LIKE '" . $this->db->escape($data['filter_repo_name']) . "%'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		// $sql .= " GROUP BY order_selec.repository_id";

		$sql .= " ORDER BY o.date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$sql = "SELECT SUM(total) AS coupon_total FROM (".$sql.") AS coupon_select";

		$this->log->write($sql);
		$query = $this->db->query($sql);

		return $query->rows;
	}
	/**
	 * @param 	required: user_id
	 *         	option: filter_order_status_id,
	 *         	filter_date_start,
	 *         	filter_repo_name
	 *          filter_date_end, filter_group, start, limit
	 * @return 	name, date_start, date_end, total
	 */

	public function getOrdersbyUid($data = array()) {
		$complete_status = $this->config->get('config_complete_status');
	    $complete_status_str = '(';
	    foreach ($complete_status as $i => $item) {
	      if ($i > 0) {
	        $complete_status_str .= ',';
	      }
	      $complete_status_str .= $item;
	    }
	    $complete_status_str .= ')';

		$sql = "SELECT
		IFNULL(SUM(op.total), 0) AS prod_total,
		IFNULL(SUM(ot.value), 0) AS coupon_total,
		IFNULL(SUM(op.total), 0)+IFNULL(SUM(ot.value), 0) AS actual_total,
		r1.repository_name AS repo_name
			FROM (
			(SELECT o1.order_id, r.repository_id FROM ".DB_PREFIX."repository_user ru
			LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id)
			LEFT JOIN ".DB_PREFIX."repository_user ru1 ON(ru.repository_id=ru1.repository_id)
			LEFT JOIN ".DB_PREFIX."order o1 ON(ru1.user_id = o1.user_id)
		WHERE ru.user_id = '".(int)$data['user_id']."'
			AND r.repository_type_id = 1
			AND o1.order_status_id IN ".$complete_status_str.")

			 UNION (SELECT rth.order_id, r_.repository_id FROM ".DB_PREFIX."repository_user ru_
			LEFT JOIN ".DB_PREFIX."repository r_ ON(ru_.repository_id=r_.repository_id)
			LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(r_.repository_id=rth.send_repository_id)
			LEFT JOIN ".DB_PREFIX."order o2 ON (rth.order_id = o2.order_id) WHERE ru_.user_id= '".(int)$data['user_id']."' AND rth.for_order = 1 AND r_.repository_type_id = 1 AND o2.order_status_id IN ".$complete_status_str.")) AS order_selec
			LEFT JOIN ".DB_PREFIX."order o ON(order_selec.order_id = o.order_id)
			LEFT JOIN ".DB_PREFIX."repository r1 ON(order_selec.repository_id=r1.repository_id)
			LEFT JOIN ".DB_PREFIX."order_product op ON(order_selec.order_id = op.order_id)
			LEFT JOIN ".DB_PREFIX."order_option oo ON(op.order_product_id = oo.order_product_id)
			LEFT JOIN ".DB_PREFIX."order_total ot ON (o.order_id = ot.order_id AND ot.code = 'coupon')";



		if (isset($data['filter_order_status_id']) && !empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (isset($data['filter_date_start']) && !empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_date_end']) && !empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if(isset($data['filter_repo_name']) && !empty($data['filter_repo_name'])){
			$sql .= " AND r1.repository_name LIKE '" . $this->db->escape($data['filter_repo_name']) . "%'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		$sql .= " GROUP BY order_selec.repository_id";
		// switch($group) {
		// 	case 'day';
		// 		$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
		// 		break;
		// 	default:
		// 	case 'week':
		// 		$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added)";
		// 		break;
		// 	case 'month':
		// 		$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
		// 		break;
		// 	case 'year':
		// 		$sql .= " GROUP BY YEAR(o.date_added)";
		// 		break;
		// }

		$sql .= " ORDER BY o.date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		// $mylog = new Log('db.log');
		// $mylog->write($sql);

		$query = $this->db->query($sql);
		return $query->rows;
	}

	/**
	 * Luke created to generate the store_sale report.
	 * Sum over each product's option value.
	 * @param  array $data
	 *         	required: user_id(int)
	 *         	optional: filter_order_status_id,
	 *         						filter_date_start, filter_date_end,
	 *         						filter_repo_name,
	 *         						start, limit
	 * @return array product_id, product_option_value_id,
	 *               prod_name, model, ovd_name,
	 *               date_start, date_end, total,
	 *               repo_name
	 */
	public function getOrdersProductsByUid($data) {
		$complete_status = $this->config->get('config_complete_status');
    $complete_status_str = '(';
    foreach ($complete_status as $i => $item) {
      if ($i > 0) {
        $complete_status_str .= ',';
      }
      $complete_status_str .= $item;
    }
    $complete_status_str .= ')';
		$sql = "SELECT op.product_id, oo.product_option_value_id, op.name AS prod_name, op.model AS model, oo.value AS ovd_name, MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, SUM(op.total) AS total, SUM(op.quantity) AS quantity,r1.repository_name AS repo_name FROM ((SELECT o1.order_id, r.repository_id FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id) LEFT JOIN ".DB_PREFIX."repository_user ru1 ON(ru.repository_id=ru1.repository_id) LEFT JOIN ".DB_PREFIX."order o1 ON(ru1.user_id = o1.user_id) WHERE ru.user_id = '".(int)$data['user_id']."' AND r.repository_type_id = 1 AND o1.order_status_id IN ".$complete_status_str.") UNION (SELECT rth.order_id, r_.repository_id FROM ".DB_PREFIX."repository_user ru_ LEFT JOIN ".DB_PREFIX."repository r_ ON(ru_.repository_id=r_.repository_id) LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(r_.repository_id=rth.send_repository_id) LEFT JOIN ".DB_PREFIX."order o2 ON (rth.order_id = o2.order_id) WHERE ru_.user_id= '".(int)$data['user_id']."' AND rth.for_order = 1 AND r_.repository_type_id = 1 AND o2.order_status_id IN ".$complete_status_str.")) AS order_selec LEFT JOIN ".DB_PREFIX."order o ON(order_selec.order_id = o.order_id) LEFT JOIN ".DB_PREFIX."repository r1 ON(order_selec.repository_id=r1.repository_id) LEFT JOIN ".DB_PREFIX."order_product op ON(order_selec.order_id = op.order_id) LEFT JOIN ".DB_PREFIX."order_option oo ON(op.order_product_id = oo.order_product_id)";

		if (isset($data['filter_order_status_id']) && !empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (isset($data['filter_date_start']) && !empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_date_end']) && !empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (isset($data['filter_repo_name']) && !empty($data['filter_repo_name'])){
			$sql .= " AND r1.repository_name LIKE '" . $this->db->escape($data['filter_repo_name']) . "%'";
		}

		if (isset($data['filter_prod_id']) && !empty($data['filter_prod_id'])) {
			$sql .= " AND op.product_id = '".(int)$data['filter_prod_id']."'";
		}

		$sql .= " GROUP BY op.product_id, oo.product_option_value_id";
		$sql .= " ORDER BY r1.repository_name, op.name, op.model";
		// $sql .= " ORDER BY op.product_id, oo.product_option_value_id";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$mylog = new Log('db.log');
		$mylog->write($sql);

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Luke created to get the the total number of items.
	 * Refer to getOrdersProductsByUid().
	 */
	public function getTotalOrdersProductsByUid($data) {
		$complete_status = $this->config->get('config_complete_status');
	    $complete_status_str = '(';
	    foreach ($complete_status as $i => $item) {
	      if ($i > 0) {
	        $complete_status_str .= ',';
	      }
	      $complete_status_str .= $item;
	    }
    	$complete_status_str .= ')';
		$sql = "SELECT COUNT(*) AS total FROM (SELECT op.product_id, oo.product_option_value_id, op.name AS prod_name, op.model AS model, oo.value AS ovd_name, MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, SUM(op.total) AS total, r1.repository_name AS repo_name FROM ((SELECT o1.order_id, r.repository_id FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id) LEFT JOIN ".DB_PREFIX."repository_user ru1 ON(ru.repository_id=ru1.repository_id) LEFT JOIN ".DB_PREFIX."order o1 ON(ru1.user_id = o1.user_id) WHERE ru.user_id = '".(int)$data['user_id']."' AND r.repository_type_id = 1 AND o1.order_status_id IN ".$complete_status_str.") UNION (SELECT rth.order_id, r_.repository_id FROM ".DB_PREFIX."repository_user ru_ LEFT JOIN ".DB_PREFIX."repository r_ ON(ru_.repository_id=r_.repository_id) LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(r_.repository_id=rth.send_repository_id) LEFT JOIN ".DB_PREFIX."order o2 ON (rth.order_id = o2.order_id) WHERE ru_.user_id= '".(int)$data['user_id']."' AND rth.for_order = 1 AND r_.repository_type_id = 1 AND o2.order_status_id IN ".$complete_status_str.")) AS order_selec LEFT JOIN ".DB_PREFIX."order o ON(order_selec.order_id = o.order_id) LEFT JOIN ".DB_PREFIX."repository r1 ON(order_selec.repository_id=r1.repository_id) LEFT JOIN ".DB_PREFIX."order_product op ON(order_selec.order_id = op.order_id) LEFT JOIN ".DB_PREFIX."order_option oo ON(op.order_product_id = oo.order_product_id)";

		if (isset($data['filter_order_status_id']) && !empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (isset($data['filter_date_start']) && !empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_date_end']) && !empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (isset($data['filter_repo_name']) && !empty($data['filter_repo_name'])){
			$sql .= " AND r1.repository_name LIKE '" . $this->db->escape($data['filter_repo_name']) . "%'";
		}

		if (isset($data['filter_prod_id']) && !empty($data['filter_prod_id'])) {
			$sql .= " AND op.product_id = '".(int)$data['filter_prod_id']."'";
		}

		$sql .= " GROUP BY op.product_id, oo.product_option_value_id";

		$sql .= ") AS itable";

		$query = $this->db->query($sql);
		return $query->row['total'];
	}


	public function getOrders($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . "order` o";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added)";
				break;
		}

		$sql .= " ORDER BY o.date_added DESC";

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

	public function getTotalOrders($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added), DAY(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), WEEK(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTaxes($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
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

	public function getTotalTaxes($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getShipping($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
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

	public function getTotalShipping($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
