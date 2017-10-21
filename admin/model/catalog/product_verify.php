<?php
/**
* 验证系统
*/
class ModelCatalogProductverify extends Model
{
	/**
	 * 添加加验证码功能
	 * @param [type] $data [description]
	 */
	public function add($data){
		$this->event->trigger('pre.admin.product_verify.add', $data);

		$sql="INSERT INTO ". DB_PREFIX. "product_verify SET product_id='".(int)$data['product_id']."', sn='".(int)$data['sn']."',product_option_value_id='".$data['product_option_value_id']."',scan_counter=0,registry_status=false,code='".$data['code']."'";

		$this->db->query($sql);

		$product_verify_id=$this->db->getLastId();

		$this->cache->delete('product_verify');
		$this->event->trigger('post.admin.product_verify.add', $product_verify_id);

		return $product_verify_id;
	}
	/**
	 * 获取该验证码相关的产品信息
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function getProducts($data){

		$sql="SELECT pv.*,p.*,pd.*,c.*
		FROM ". DB_PREFIX ."product_verify pv
			LEFT JOIN " . DB_PREFIX ."product p ON (pv.product_id=p.product_id)
			LEFT JOIN ".DB_PREFIX."product_description pd ON (pv.product_id=pd.product_id)
			LEFT JOIN ".DB_PREFIX."customer c ON (c.customer_id=pv.customer_id)
				WHERE pd.language_id='" .(int)$this->config->get('config_language_id')."'";

		$sql .= " AND pv.product_id= '".(int)$data['product_id']."'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY pv.product_verify_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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
	/**
	 * 分页用
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function getTotalProducts($data = array()) {
		$sql="SELECT COUNT(DISTINCT pv.product_verify_id) AS total FROM ". DB_PREFIX ."product_verify pv LEFT JOIN " . DB_PREFIX ."product p ON (pv.product_id=p.product_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (pv.product_id=pd.product_id) WHERE pd.language_id='" .(int)$this->config->get('config_language_id')."'";

		$sql .= " AND pv.product_id= '".(int)$data['product_id']."'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	/**
	 * 精确获取某一个验证码
	 * @param  [type] $product_verify_id [description]
	 * @return [type]                    [description]
	 */
	public function getVerifyById($product_verify_id){
		$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_verify WHERE product_verify_id='".(int)$product_verify_id."'");
		if ($query->num_rows) {
			return $query->row;
		}else{
			return false;
		}
	}

	/**
	 * 获取一类产品的验证码
	 * @param  [type] $product_id [description]
	 * @return [type]             [description]
	 */
	public function getVerifyByPid($product_id){
		$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_verify WHERE product_id='".(int)$product_id."'");
		if ($query->num_rows) {
			return $query->rows;
		}else{
			return false;
		}
	}
	/**
	 * 获取最后一个防伪验证码
	 * @param  [type] $product_id [description]
	 * @return [type]             [description]
	 */
	public function getLastVerifyByPid($product_id){
		$query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_verify WHERE product_id='".(int)$product_id."' ORDER BY sn DESC");

		if ($query->num_rows) {

			return $query->row;
		}else{
			return false;
		}
	}

	public function deleteVerifyById($product_verify_id){
		$this->event->trigger('pre.admin.product_verify.delete', $product_verify_id);

		$this->db->query("DELETE FROM ".DB_PREFIX."product_verify WHERE product_verify_id='".(int)$product_verify_id."'");

		$this->cache->delete('product_verify');

		$this->event->trigger('post.admin.product_verify.delete', $product_verify_id);

	}

}