<?php
/**
* 利润表的数据层
*/
class ModelReportProfit extends Model
{

	/**
	 * 需要的数据：
	 * 1、model
	 * 2、价格：挂牌价，成本价格，实际价格（优惠之后的价格）
	 * 3、数量：库存数量，销售数量
	 *
	 * 需要计算的数据：
	 * 1、毛利率：（实际价格-成本价格）*销售数量／实际价格*销售数量
	 * 2、销售成本：成本价格*销售数量
	 * 3、库存成本：成本价格*库存数量
	 * 4、销售档案牌价额：挂牌价格*销售量
	 * 5、库存牌价额：挂牌价*库存数量
	 *
	 *
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function getProducts($data=array()){

		$complete_status = $this->config->get('config_complete_status');
	    $complete_status_str = '(';
	    foreach ($complete_status as $i => $item) {
	      if ($i > 0) {
	        $complete_status_str .= ',';
	      }
	      $complete_status_str .= $item;
	    }
	    $complete_status_str .= ')';


		$order_sql="SELECT
		ot1.`value` /(ot1.`value` -ot2.`value`) AS discount_rate,
		op.*,
		r.repository_name
		      FROM ".DB_PREFIX."order o
			      LEFT JOIN ".DB_PREFIX."order_product op on(op.order_id= o.order_id)
			      LEFT JOIN ".DB_PREFIX."product p on(op.product_id= p.product_id)
			      LEFT JOIN ".DB_PREFIX."order_total ot1 on(ot1.order_id= op.order_id
			       and ot1.code= 'total')
			      LEFT JOIN `".DB_PREFIX."order_total` ot2 on(ot2.order_id= op.order_id
			       and ot2.code= 'coupon')
			      LEFT JOIN ".DB_PREFIX."order_product_repository opr on(opr.order_product_id=op.order_product_id)
			      LEFT JOIN ".DB_PREFIX."repository r on(r.repository_id=opr.repository_id)
		     WHERE o.order_status_id IN ".$complete_status_str;

	    /**
	     * 增加时间filter
	     */
        if (isset($data['filter_date_start']) && !empty($data['filter_date_start'])) {
			$order_sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_date_end']) && !empty($data['filter_date_end'])) {
			$order_sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (isset($data['filter_repo_name']) && !empty($data['filter_repo_name'])) {
			$repository_query=$this->db->query("SELECT repository_id FROM ".DB_PREFIX."repository WHERE repository_name='".$this->db->escape($data['filter_repo_name'])."'");
			$repository_id=$repository_query->row['repository_id'];
			$order_sql.=" AND opr.repository_id= '" .$repository_id."'";
		}


        $product_sql="SELECT
	       p.product_id,
	       rate.repository_name,
	       pd.name,
	       p.model,
	       p.cost_price,
	       (((SUM(rate.quantity*(rate.price * rate.discount_rate))) /(SUM(rate.quantity))) -cost_price) /((SUM(rate.quantity*(rate.price * rate.discount_rate))) /(SUM(rate.quantity))) AS profit,
	       SUM(rate.quantity) AS saled_num,
	       SUM(rp.product_num) AS repo_num,
	       p.cost_price*SUM(rate.quantity) AS saled_cost_total,
	       p.price*SUM(rate.quantity) AS sticket_total,
	       ((SUM(rate.quantity*(rate.price * rate.discount_rate))) /(SUM(rate.quantity))) *SUM(rate.quantity) AS real_saled_total,
	       p.cost_price*SUM(rp.product_num) AS repo_cost_total,
	       (SUM(rate.quantity*(rate.price * rate.discount_rate))) /(SUM(rate.quantity)) AS real_price,
	       rate.quantity*(rate.price * rate.discount_rate) AS real_saled,
	       SUM(rate.quantity*(rate.price * rate.discount_rate)) AS real_saled_total,
	       p.price*SUM(rp.product_num) AS repo_sticker_cost_total
			  FROM ".DB_PREFIX."product p

			  LEFT JOIN ".DB_PREFIX."product_description pd ON(p.product_id= pd.product_id)
			  LEFT JOIN ".DB_PREFIX."repository_pd rp on(rp.product_id= p.product_id)

			  LEFT JOIN(

			    ".$order_sql."

			   ) AS rate on(rate.product_id= p.product_id)";

		if (isset($data['filter_repo_name']) && !empty($data['filter_repo_name'])) {
			$repository_query=$this->db->query("SELECT repository_id FROM ".DB_PREFIX."repository WHERE repository_name='".$this->db->escape($data['filter_repo_name'])."'");
			$repository_id=$repository_query->row['repository_id'];

			$product_sql.=" WHERE rp.repository_id= '" .$repository_id."'";
		}

		$product_sql.=" GROUP BY p.product_id ORDER BY SUM(rate.quantity) DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$product_sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}


		$query = $this->db->query($product_sql);

		return $query->rows;

	}

	public function getTotalProducts($data=array()){
		$sql="SELECT COUNT(*) AS total FROM ".DB_PREFIX."product";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}



}