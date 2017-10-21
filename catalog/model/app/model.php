<?php
/**
* for model select
*/
class ModelAppModel extends Model
{

	public function getProductIdByModel($model){
		$query=$this->db->query("SELECT product_id FROM ".DB_PREFIX."product WHERE model='".$this->db->escape($model)."'");
		if ($query->num_rows) {
			return $query->row['product_id'];
		}else{
			return false;
		}
	}
}