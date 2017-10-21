<?php
class ModelReportProduct extends Model {
	public function getProductsViewed($data = array()) {
		$sql = "SELECT pd.name, p.model, p.viewed FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.viewed > 0 ORDER BY p.viewed DESC";

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

	public function getTotalProductViews() {
		$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product");

		return $query->row['total'];
	}

	public function getTotalProductsViewed() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE viewed > 0");

		return $query->row['total'];
	}

	public function reset() {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = '0'");
	}

	public function getPurchased($data = array()) {
		// $sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM((op.total + op.tax) * op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";

		// huwen mod.Fix the bug:the calculation is  wrong
		$sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.total + (op.tax * op.quantity)) AS total FROM ".DB_PREFIX."order_product op LEFT JOIN `".DB_PREFIX."order` o ON (op.order_id = o.order_id)";


		// huwen added for filter
		if (!empty($data['filter_order_status_id'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status_id']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		//
		// if (!empty($data['filter_order_status_id'])) {
		// 	$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		// } else {
		// 	$sql .= " WHERE o.order_status_id > '0'";
		// }

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= " GROUP BY op.product_id ORDER BY total DESC";

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

	// Luke added for showing creator of the product.
	public function getPurchased_withCreator($data = array()) {
		// $sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM((op.total + op.tax) * op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";

		// huwen mod.Fix the bug:the calculation is  wrong
		$sql = "SELECT u.username AS creator, op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.total + (op.tax * op.quantity)) AS total FROM ".DB_PREFIX."order_product op LEFT JOIN `".DB_PREFIX."order` o ON (op.order_id = o.order_id) LEFT JOIN `".DB_PREFIX."product` p ON (op.product_id = p.product_id) LEFT JOIN `".DB_PREFIX."user` u ON (p.creator_id = u.user_id)";


		// huwen added for filter
		if (!empty($data['filter_order_status_id'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status_id']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		//
		// if (!empty($data['filter_order_status_id'])) {
		// 	$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		// } else {
		// 	$sql .= " WHERE o.order_status_id > '0'";
		// }

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= " GROUP BY op.product_id ORDER BY total DESC";

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

	public function getTotalPurchased($data) {
		$sql = "SELECT COUNT(DISTINCT op.product_id) AS total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
// huwen adde for filter
		if (!empty($data['filter_order_status_id'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status_id']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";

			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
// end

		// if (!empty($data['filter_order_status_id'])) {
		// 	$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		// } else {
		// 	$sql .= " WHERE o.order_status_id > '0'";
		// }

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
