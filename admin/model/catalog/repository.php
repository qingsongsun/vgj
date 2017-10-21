<?php
/**
* multi-repository organization model
*/
class ModelCatalogRepository extends Model
{
    //输入 repository_name, repository_address,longitude_1,longitude_2,latitude_1,latitude_2
    //输出
    public function addRepository($data){
        $this->event->trigger('pre.admin.repository.add',$data);
        $Repository_id = $this->db->query("SELECT MAX(repository_id) FROM ".DB_PREFIX."repository WHERE 1");
        $repository_id = $Repository_id->row['MAX(repository_id)'];
        //$this->log->write($repository);
        $repository_id += 1;
    // huwen mod for repo_type
        $sql = "INSERT INTO " . DB_PREFIX . "repository SET repository_id = '" . (int)$repository_id . "', repository_name = '" . $this->db->escape($data['repository_name']) . "',repository_type_id='".$this->db->escape($data['repository_type_id'])."'";

        if(isset($data['repository_address'])){
            $sql .= ",repository_address = '" . $this->db->escape($data['repository_address']) . "'";
        }
        if(isset($data['longitude_1'])){
            $sql .= ",longitude_1 = '" . $this->db->escape($data['longitude_1']) . "'";
        }
        if(isset($data['longitude_2'])){
            $sql .= ",longitude_2 = '" . $this->db->escape($data['longitude_2']) . "'";
        }
        if(isset($data['latitude_1'])){
            $sql .= ",latitude_1 = '" . $this->db->escape($data['latitude_1']) . "'";
        }
        if(isset($data['latitude_2'])){
            $sql .= ",latitude_2 = '" . $this->db->escape($data['latitude_2']) . "'";
        }
        $this->db->query($sql);

        // $insert = "SELECT p.product_id, pov.product_option_value_id, p.quantity p_num, pov.quantity pov_num FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_option_value pov ON(p.product_id = pov.product_id)";
        // $results = $this->db->query($insert);
        // $this->log->write($results);
        // foreach ($results->rows as $result) {
        //     if(isset($result['product_option_value_id'])){
        //         $this->db->query("INSERT INTO ".DB_PREFIX."repository_pd SET product_id ='".(int)$result['product_id']."', repository_id = '1', product_option_value_id = '".(int)$result['product_option_value_id']."', product_num = '".(int)$result['pov_num']."'");
        //     } else{
        //         $this->db->query("INSERT INTO ".DB_PREFIX."repository_pd SET product_id ='".(int)$result['product_id']."', repository_id = '1', product_num = '".(int)$result['p_num']."'");
        //     }
        // }

        $this->event->trigger('post.admin.repository.add',$repositpry_id);
    }


    //输入
    //输出 新加的repository_id
    public function getNewrepositoryId(){
        $Repository_id = $this->db->query("SELECT MAX(repository_id) FROM ".DB_PREFIX."repository WHERE 1");
        $repository_id = $Repository_id->row['MAX(repository_id)'];
        return $repository_id+1;
    }


    //输入 repository_id
    //输出
    public function delRepository($data){
        $this->event->trigger('pre.admin.repository.add',$data);
        $this->db->query("DELETE FROM ". DB_PREFIX . "repository WHERE repository_id = ".(int)$data['repository_id']);
        $this->db->query("DELETE FROM ". DB_PREFIX . "repository_pd WHERE repository_id = ".(int)$data['repository_id'] );
        $this->event->trigger('post.admin.repository.add',$data['repositpry_id']);
    }


    //输入 repository_id
    //输出 对应的repository信息
    public function getRepositorybyId($repository_id){
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "repository WHERE repository_id = '" . (int)$repository_id . "'");
        return $query->row;
    }

    /**
     *
     */
    public function getRepositoriesByUser($data){
         $sql = "SELECT r.repository_id, r.repository_name FROM " . DB_PREFIX . "repository r LEFT JOIN ".DB_PREFIX."repository_user ru on(r.repository_id=ru.repository_id) WHERE ru.user_id='".(int)$data['user_id']."'";

        $sort_data = array(
            'repository_id',
            'repository_name'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY r.repository_id";
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
    //输入
    //输出 所有repository数量
    public function getTotalRepositories(){
        $sql = "SELECT COUNT(*) total FROM " . DB_PREFIX . "repository WHERE 1";
        $query = $this->db->query($sql);
        return $query->row['total'];
    }


    //输入 (可选)sort、start、limit等信息
    //输出 所有repository信息
    public function getRepositories($data){
        $sql = "SELECT repository_id, repository_name FROM " . DB_PREFIX . "repository WHERE 1";

        $sort_data = array(
            'repository_id',
            'repository_name'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY repository_id";
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


    //输入 product_id, order_product_id, quantity
    //输出 repository_id, repository_name
    public function getRepository_quantitybyId($data){

        $product_option_value_id = $this->db->query("SELECT product_option_value_id FROM ".DB_PREFIX."order_option WHERE order_product_id = '".(int)$data['order_product_id']."'");
        if ($product_option_value_id->num_rows==0) {
            unset($product_option_value_id);
        }
        $sql = "SELECT rp.repository_id, r.repository_name FROM ".DB_PREFIX."repository r LEFT JOIN ".DB_PREFIX."repository_pd rp ON(r.repository_id = rp.repository_id) WHERE ";
        if(isset($product_option_value_id)){
            $sql .= "rp.product_option_value_id = '".(int)$product_option_value_id->row['product_option_value_id']."'";
        } else{
            $sql .= "rp.product_id = '".(int)$data['product_id']."'";
        }
        $sql .= " AND rp.product_num >= '".(int)$data['quantity']."'";

        $query = $this->db->query($sql);
        $results = array();
        foreach ($query->rows as $item) {
            $results[] = array('id' => $item['repository_id'], 'name' => $item['repository_name']);
        }
        return $results;
    }

    //输入 (必须) repository_id,repository_name,
    //     (可选) repository_address,longitude_1,longitude_2,latitude_1,latitude_2
    //输出
    public function editRepository($data){
        $this->event->trigger('pre.admin.repository.edit',$data);
        // huwen mod for pickup
        $sql = "UPDATE " . DB_PREFIX . "repository SET repository_name = '".$this->db->escape($data['repository_name']) ."',repository_type_id='".$this->db->escape($data['repository_type_id'])."'";

        if(isset($data['repository_address'])){
            $sql .= ",repository_address = '" . $this->db->escape($data['repository_address']) . "'";
        }
        if(isset($data['longitude_1'])){
            $sql .= ",longitude_1 = '" . $this->db->escape($data['longitude_1']) . "'";
        }
        if(isset($data['longitude_2'])){
            $sql .= ",longitude_2 = '" . $this->db->escape($data['longitude_2']) . "'";
        }
        if(isset($data['latitude_1'])){
            $sql .= ",latitude_1 = '" . $this->db->escape($data['latitude_1']) . "'";
        }
        if(isset($data['latitude_2'])){
            $sql .= ",latitude_2 = '" . $this->db->escape($data['latitude_2']) . "'";
        }

        $sql .= "WHERE repository_id = '".(int)$data['repository_id']."'";

        $query = $this->db->query($sql);
        $this->event->trigger('post.admin.repository.edit',$data['repositpry_id']);
    }


    //输入 (必须) filter_prod_name, filter_prod_ovdname,repository_id,
    //     (可选) product_num
    //输出
    public function createRepository_pd($data){
        $this->event->trigger('pre.admin.repositpry_pd.edit',$data);
        // $this->log->write($data);

        if($data['filter_prod_ovdname']){
            $select = "SELECT p.product_id, pov.product_option_value_id FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_option_value pov ON(p.product_id = pov.product_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE pd.name = '".$this->db->escape($data['filter_prod_name'])."' AND ovd.name = '".$this->db->escape($data['filter_prod_ovdname'])."' AND pov.subtract = '1'";
        } else {
            $select = "SELECT p.product_id, pov.product_option_value_id FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_option_value pov ON(p.product_id = pov.product_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE pd.name = '".$this->db->escape($data['filter_prod_name'])."'";
        }
        $results = $this->db->query($select);
        $repo_in = array(
            'repository_id' => $data['repository_id'],
            'product_id' => $results->row['product_id'],
            'user_id' => $this->user->getId()
            );
        // $this->log->write($results);
        foreach ($results->rows as $result) {
            if(isset($result['product_option_value_id']) && isset($result['product_id'])){
                $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_pd WHERE product_option_value_id = '".(int)$result['product_option_value_id']."'");
                if($tmp->num_rows > 0){
                    return;
                }
                $repo_in['prod_option_value_id'] = $result['product_option_value_id'];
                $this->db->query("UPDATE ".DB_PREFIX."product_option_value SET quantity = quantity + '".(int)$data['filter_prod_num']."' WHERE product_option_value_id = '".(int)$result['product_option_value_id']."'");
                $sql = "INSERT INTO " . DB_PREFIX . "repository_pd SET repository_id = '" . (int)$data['repository_id'] . "', product_option_value_id = '" . (int)$result['product_option_value_id'] . "', product_id = '".(int)$result['product_id']."'";
            } else if(isset($result['product_id'])){
                $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_pd WHERE product_id = '".(int)$result['product_id']."'");
                if($tmp->num_rows > 0){
                    return;
                }
                $sql = "INSERT INTO " . DB_PREFIX . "repository_pd SET repository_id = '" . (int)$data['repository_id'] . "', product_id = '".(int)$result['product_id']."'";
            }
            $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity + '".(int)$data['filter_prod_num']."' WHERE product_id = '".(int)$result['product_id']."'");

            if(isset($data['filter_prod_num'])){
                $repo_in['product_num'] = $data['filter_prod_num'];
                $sql .= ", product_num = '" . (int)$data['filter_prod_num'] . "'";
            } else{
                $repo_in['product_num'] = 0;
                $sql .= ", product_num = '0'";
            }
            $this->db->query($sql);
        }
        if($repo_in['product_num']>0){
            $repo_in['opt_type'] = "in";
            $this->updateLastOpt($repo_in);
            $this->addRepository_trans_history_in($repo_in);
        }
        $this->event->trigger('post.admin.repositpry_pd.edit',$data['product_option_value_id']);
    }

    //输入 (必须) product_id, product_option_value_id,repository_id,
    //     (可选) product_num
    //输出
    public function createRepository_pd_byId($data){
        $this->event->trigger('pre.admin.repositpry_pd.edit',$data);
        $repo_in = array(
            'repository_id' => $data['repository_id'],
            'product_id' => $data['product_id'],
            'user_id' => $this->user->getId()
            );
        if ($data['product_option_value_id']==0) {
            unset($data['product_option_value_id']);
        }
        if(isset($data['product_option_value_id']) && isset($data['product_id'])){
            $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_pd WHERE product_option_value_id = '".(int)$data['product_option_value_id']."' AND repository_id = '".(int)$data['repository_id']."'");
            if($tmp->num_rows > 0){
                return;
            }
            $repo_in['prod_option_value_id'] = $data['product_option_value_id'];
            $this->db->query("UPDATE ".DB_PREFIX."product_option_value SET quantity = quantity + '".(int)$data['filter_prod_num']."' WHERE product_option_value_id = '".(int)$data['product_option_value_id']."'");
            $sql = "INSERT INTO " . DB_PREFIX . "repository_pd SET repository_id = '" . (int)$data['repository_id'] . "', product_option_value_id = '" . (int)$data['product_option_value_id'] . "', product_id = '".(int)$data['product_id']."'";
        } else if(isset($data['product_id'])){
            $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_pd WHERE product_id = '".(int)$data['product_id']."'");
            if($tmp->num_rows > 0){
                return;
            }
            $sql = "INSERT INTO " . DB_PREFIX . "repository_pd SET repository_id = '" . (int)$data['repository_id'] . "', product_id = '".(int)$data['product_id']."'";
        }
        $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity + '".(int)$data['filter_prod_num']."' WHERE product_id = '".(int)$data['product_id']."'");

        if(isset($data['filter_prod_num'])){
            $repo_in['product_num'] = $data['filter_prod_num'];
            $sql .= ", product_num = '" . (int)$data['filter_prod_num'] . "'";
        } else{
            $repo_in['product_num'] = 0;
            $sql .= ", product_num = '0'";
        }
        $this->db->query($sql);
        if($repo_in['product_num']>0){
            $this->addRepository_trans_history_in($repo_in);
        }
        $this->event->trigger('post.admin.repositpry_pd.edit',$data['product_option_value_id']);
    }

    //输入 product_id,product_ovdname,repository_id
    //
    public function delRepository_pd($data){
        $this->event->trigger('pre.admin.repositpry_pd.delete',$data);
        $product_option_value_id = $this->db->query("SELECT r.product_option_value_id FROM ".DB_PREFIX."repository_pd r LEFT JOIN ".DB_PREFIX."product_option_value pov ON (r.product_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE r.product_id = '".(int)$data['product_id']."' AND ovd.name = '".$this->db->escape($data['product_ovdname'])."' AND pov.subtract = '1'");

        if(isset($product_option_value_id->row['product_option_value_id'])){
            $this->db->query("DELETE FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_option_value_id = '" . (int)$product_option_value_id->row['product_option_value_id'] . "'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . $data['product_id'] . "'");
        }
        $this->event->trigger('post.admin.repositpry_pd.delete',$data['product_id']);
    }


    //
    //
    public function clearRepository_pd(){
        $this->event->trigger('pre.admin.repositpry_pd.clear');
        $this->db->query("DELETE FROM ".DB_PREFIX."repository_pd WHERE product_num = '0'");
        $this->event->trigger('post.admin.repositpry_pd.clear');
    }


    //输入 (必须) filter_prod_ovdname, filter_prod_id ,repository_id,
    //输出 flag, result_code{
	//		0: success
	//		1: inconsistence in database
	//		2: product not found
	//		3: missing product option value
	//		4: product already exist in current repository
    public function isValidProd_id($data){
        if($data['filter_prod_ovdname']){
            $prod_num_total = $this->db->query("SELECT p.product_id, pov.product_option_value_id FROM ".DB_PREFIX."product p LEFT JOIN " .DB_PREFIX."product_option_value pov ON(p.product_id = pov.product_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '".(int)$data['filter_prod_id']."' AND ovd.name = '".$this->db->escape($data['filter_prod_ovdname'])."' AND pov.subtract = '1'");
            if($prod_num_total->num_rows == 0){
                $flag = false;
				$result_code = 2;
            } elseif ($prod_num_total->num_rows > 1){
				$flag = false;
				$result_code = 1;
			} else {
                $product_id = $prod_num_total->row['product_id'];
                //$this->log->write($product_id);
                $prod_num_now = $this->db->query("SELECT * FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . (int)$product_id . "' AND product_option_value_id = '".$prod_num_total->row['product_option_value_id']."'");
                if($prod_num_now->num_rows != 0){
                    $flag = false;
					$result_code = 4;
				} else{
                    $flag = true;
					$result_code = 0;
				}
			}
        }  else  {
            $prod_num_total = $this->db->query("SELECT p.product_id FROM ".DB_PREFIX."product p LEFT JOIN " .DB_PREFIX."product_option_value pov ON(p.product_id = pov.product_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '".(int)$data['filter_prod_id']."'");
            //$this->log->write($prod_num_total);
            if($prod_num_total->num_rows == 0){
                $flag = false;
				$result_code = 2;
            } else {
                $query = $this->db->query("SELECT pov.product_option_value_id FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_option_value pov ON(p.product_id = pov.product_id) WHERE p.product_id = '".(int)$data['filter_prod_id']."' AND pov.subtract = 1");
                if ($query->num_rows > 0) {
                    $flag = false;
					$result_code = 3;
                } else {
                    $product_id = $prod_num_total->row['product_id'];
                    //$this->log->write($product_id);
                    $prod_num_now = $this->db->query("SELECT * FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . (int)$product_id . "'");

                    if($prod_num_now->num_rows != 0){
                        $flag = false;
						$result_code = 4;
                    } else {
                        $flag = true;
						$result_code = 0;
					}
                }
            }
        }
        //$this->log->write($prod_num_total);
        return array(
            'cur' => $prod_num_now,
            'total' => $prod_num_total,
            'flag' => $flag,
            'result_code' => $result_code
            );
    }


    //输入 (必须) repository_id, product_id,product_ovdname, product_num, user_id (只适用ovdname)
    //输出
    public function addRepository_pd($data){
        $this->event->trigger('pre.admin.repositpry_pd.add',$data);

        if (isset($data['product_option_value_id'])){
            $product_option_value_id->row['product_option_value_id']=$data['product_option_value_id'];
        } else {
            $product_option_value_id = $this->db->query("SELECT r.product_option_value_id FROM ".DB_PREFIX."repository_pd r LEFT JOIN ".DB_PREFIX."product_option_value pov ON (r.product_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE r.product_id = '".(int)$data['product_id']."' AND ovd.name = '".$this->db->escape($data['product_ovdname'])."'");
            if ($product_option_value_id->num_rows==0) {
                unset($product_option_value_id);
            }
        }
        // $this->log->write($product_option_value_id);
        if(isset($product_option_value_id->row['product_option_value_id'])){
            $data['prod_option_value_id'] = $product_option_value_id->row['product_option_value_id'];
            $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_pd WHERE product_option_value_id = '".(int)$product_option_value_id->row['product_option_value_id']."' AND repository_id = '".(int)$data['repository_id']."'");
            if(!isset($tmp->row['repository_id'])){
                $this->db->query("INSERT INTO ".DB_PREFIX."repository_pd SET repository_id = '".(int)$data['repository_id']."', product_id = '".(int)$data['product_id']."', product_option_value_id = '".(int)$product_option_value_id->row['product_option_value_id']."', product_num = 0");
            }
        } else{
            $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_pd WHERE product_id = '".(int)$data['product_id']."' AND repository_id = '".(int)$data['repository_id']."'");
            if(!isset($tmp->row['repository_id'])){
                $this->db->query("INSERT INTO ".DB_PREFIX."repository_pd SET repository_id = '".(int)$data['repository_id']."', product_id = '".(int)$data['product_id']."', product_num = 0");
            }
        }

        if(isset($product_option_value_id->row['product_option_value_id'])){
            $prod_num_rep = $this->db->query("SELECT product_num, product_id FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_option_value_id = '" . (int)$product_option_value_id->row['product_option_value_id'] . "'");
            // $this->log->write($data['repository_id']);
            $prod_num_prod = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$data['product_id']. "'");
            $prod_num_pov = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . (int)$product_option_value_id->row['product_option_value_id'] . "'");

        } else {
            $prod_num_rep = $this->db->query("SELECT product_num, product_id FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . $data['product_id'] . "'");
            $prod_num_prod = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$data['product_id']."'");
            $prod_num_pov = 1000000;
        }

        $product_num_rep = (int)$data['product_num'] + $prod_num_rep->row['product_num'];

        $product_num_prod = (int)$data['product_num'] + $prod_num_prod->row['quantity'];
        if(isset($product_option_value_id->row['product_option_value_id'])){
            $product_num_pov = (int)$data['product_num'] + $prod_num_pov->row['quantity'];
        } else {
            $product_num_pov = (int)$data['product_num'] + $prod_num_pov;
        }

        if($product_num_rep<0 || $product_num_pov<0 || $product_num_pov<0){
            $this->event->trigger('pre.admin.repositpry_pd.edit',$data['product_id']);
            return -1;
        }
        //$this->log->write($product_option_value_id->row['product_option_value_id']);
        if(isset($product_option_value_id->row['product_option_value_id'])){
            $this->db->query("UPDATE " . DB_PREFIX . "repository_pd SET product_num = '" .(int)$product_num_rep . "' WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_option_value_id = '" . $product_option_value_id->row['product_option_value_id'] . "'");
            $this->db->query("UPDATE " . DB_PREFIX ."product_option_value SET quantity = '".(int)$product_num_pov . "' WHERE product_option_value_id = '".$product_option_value_id->row['product_option_value_id'] . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "repository_pd SET product_num = '" .(int)$product_num_rep . "' WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . $data['product_id'] . "'");
        }

        $this->db->query("UPDATE " . DB_PREFIX ."product SET quantity = '".(int)$product_num_prod . "' WHERE product_id = '".$data['product_id'] . "'");

        $this->event->trigger('pre.admin.repositpry_pd.add',$data['product_option_value_id']);

        // add history for repository_trans_ongoing and repository_trans_history

        if((int)$data['product_num'] < 0){       //出库
            $data['product_num'] = -1*(int)$data['product_num'];
            $data['opt_type'] = "out";
            $this->updateLastOpt($data);
            $repo_activity_id = $this->addRepository_trans_history_out($data);
            if(isset($data['receive_repository_id'])){
                $data['repo_activity_id'] = (int)$repo_activity_id;
                $this->addRepository_trans_ongoing($data);
            }
        } elseif((int)$data['product_num'] > 0){
            // $this->log->write($data);
            $this->addRepository_trans_history_in($data);
            $data['opt_type'] = "in";
            $this->updateLastOpt($data);
            if(isset($data['repo_activity_id'])){
                $this->delRepository_trans_ongoing($data['repo_activity_id']);
            }
        }
    }


    //输入 (必须) repository_id, product_id,product_option_value_id, product_num,user_id
    //输出
    public function addRepository_pd_byId($data){
        $this->event->trigger('pre.admin.repositpry_pd.add',$data);

        if(isset($data['product_option_value_id'])){
            $data['prod_option_value_id'] = $data['product_option_value_id'];
            $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_pd WHERE product_option_value_id = '".(int)$data['product_option_value_id']."' AND repository_id = '".(int)$data['repository_id']."'");
            if(!isset($tmp->row['repository_id'])){
                $this->db->query("INSERT INTO ".DB_PREFIX."repository_pd SET repository_id = '".(int)$data['repository_id']."', product_id = '".(int)$data['product_id']."', product_option_value_id = '".(int)$data['product_option_value_id']."', product_num = 0");
            }
        } else{
            $tmp = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_pd WHERE product_id = '".(int)$data['product_id']."' AND repository_id = '".(int)$data['repository_id']."'");
            if(!isset($tmp->row['repository_id'])){
                $this->db->query("INSERT INTO ".DB_PREFIX."repository_pd SET repository_id = '".(int)$data['repository_id']."', product_id = '".(int)$data['product_id']."', product_num = 0");
            }
        }

        if(isset($data['product_option_value_id'])){
            $prod_num_rep = $this->db->query("SELECT product_num, product_id FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_option_value_id = '" . (int)$data['product_option_value_id'] . "'");
            $prod_num_prod = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$data['product_id']. "'");
            $prod_num_pov = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . (int)$data['product_option_value_id'] . "'");

        } else {
            $prod_num_rep = $this->db->query("SELECT product_num, product_id FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . $data['product_id'] . "'");
            $prod_num_prod = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$data['product_id']."'");
            $prod_num_pov = 1000000;
        }

        $product_num_rep = (int)$data['product_num'] + $prod_num_rep->row['product_num'];

        $product_num_prod = (int)$data['product_num'] + $prod_num_prod->row['quantity'];
        if(isset($data['product_option_value_id'])){
            $product_num_pov = (int)$data['product_num'] + $prod_num_pov->row['quantity'];
        } else {
            $product_num_pov = (int)$data['product_num'] + $prod_num_pov;
        }

        if($product_num_rep<0 || $product_num_pov<0 || $product_num_pov<0){
            $this->event->trigger('pre.admin.repositpry_pd.edit',$data['product_id']);
            return -1;
        }

        if(isset($data['product_option_value_id'])){
            $this->db->query("UPDATE " . DB_PREFIX . "repository_pd SET product_num = '" .(int)$product_num_rep . "' WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_option_value_id = '" . $data['product_option_value_id'] . "'");
            $this->db->query("UPDATE " . DB_PREFIX ."product_option_value SET quantity = '".(int)$product_num_pov . "' WHERE product_option_value_id = '".$data['product_option_value_id'] . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "repository_pd SET product_num = '" .(int)$product_num_rep . "' WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . $data['product_id'] . "'");
        }

        $this->db->query("UPDATE " . DB_PREFIX ."product SET quantity = '".(int)$product_num_prod . "' WHERE product_id = '".$data['product_id'] . "'");

        $this->event->trigger('pre.admin.repositpry_pd.add',$data['product_option_value_id']);

        // add history for repository_trans_ongoing and repository_trans_history

        if((int)$data['product_num'] < 0){       //出库
            $data['product_num'] = -1*(int)$data['product_num'];
            $data['opt_type'] = "out";
            $this->updateLastOpt($data);
            $repo_activity_id = $this->addRepository_trans_history_out($data);
            if(isset($data['receive_repository_id'])){
                $data['repo_activity_id'] = (int)$repo_activity_id;
                $this->addRepository_trans_ongoing($data);
            }
            return $repo_activity_id;
        } elseif((int)$data['product_num'] > 0){
            $this->addRepository_trans_history_in($data);
            $data['opt_type'] = "in";
            $this->updateLastOpt($data);
            if(isset($data['repo_activity_id'])){
                $this->delRepository_trans_ongoing($data['repo_activity_id']);
            }
        }
    }

    //输入 (必须) order_product_id, repository_id, quantity, user_id, order_id
    //输出
    public function delRepository_pd_order($data){
        $this->event->trigger('pre.admin.repositpry_pd.add',$data);
        $product_option_value_id = $this->db->query("SELECT product_option_value_id FROM ".DB_PREFIX."order_option WHERE order_product_id = '".(int)$data['order_product_id']."'")->row['product_option_value_id'];
        $order_info = $this->db->query("SELECT product_id, order_id FROM ".DB_PREFIX."order_product WHERE order_product_id = '".(int)$data['order_product_id']."'")->row;
        $send_data = array(
            'product_id' => $order_info['product_id'],
            'repository_id' => $data['repository_id'],
            'product_num' => $data['quantity'],
            'user_id' => $data['user_id'],
            'order_id' => $order_info['order_id'],
            'for_order' => 1,
            'opt_type' => "out",
            'prod_option_value_id' => NULL
            );
        if(isset($product_option_value_id)){
            $send_data['prod_option_value_id'] = $product_option_value_id;
            $this->db->query("UPDATE ".DB_PREFIX."repository_pd SET product_num = product_num - '".(int)$data['quantity']."' WHERE repository_id = '".(int)$data['repository_id']."' AND product_option_value_id = '".(int)$product_option_value_id."'");
        } else{
            $this->db->query("UPDATE ".DB_PREFIX."repository_pd SET product_num = product_num - '".(int)$data['quantity']."' WHERE repository_id = '".(int)$data['repository_id']."' AND product_id = '".(int)$order_info['product_id']."'");
        }
        $this->addRepository_trans_history_out($send_data);
        $this->updateLastOpt($send_data);
        $this->event->trigger('pre.admin.repositpry_pd.add',$data['product_option_value_id']);
    }

    //输入 (必须) product_id,product_ovdname,repository_id,product_num
    //输出
    public function decreaseRepository_pd($data){
        $this->event->trigger('pre.admin.repositpry_pd.decrease',$data);
        $product_option_value_id = $this->db->query("SELECT r.product_option_value_id FROM ".DB_PREFIX."repository_pd r LEFT JOIN ".DB_PREFIX."product_option_value pov ON (r.product_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE r.product_id = '".(int)$data['product_id']."' AND ovd.name = '".$this->db->escape($data['product_ovdname'])."'");

        if(isset($product_option_value_id->row['product_option_value_id'])){
            $prod_num = $this->db->query("SELECT product_num, product_id FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_option_value_id = '" . (int)$product_option_value_id->row['product_option_value_id'] . "'");
        } else {
            $prod_num = $this->db->query("SELECT product_num, product_id FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . $data['product_id'] . "'");
        }

        $product_num = $prod_num->row['product_num'] - (int)$data['product_num'];
        if($product_num<0){
            $this->event->trigger('pre.admin.repositpry_pd.edit',$data['product_id']);
            return -1;
        }
        if(isset($product_option_value_id->row['product_option_value_id'])){
            $this->db->query("UPDATE " . DB_PREFIX . "repository_pd SET product_num = '" .(int)$product_num . "' WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_option_value_id = '" . $product_option_value_id->row['product_option_value_id'] . "'");
            // $product_num_option = (int)$data['product_num'] + $prod_num->row['quantity'];
            // $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = '" .(int)$product_num_option . "' WHERE product_option_value_id = '" . $data['product_option_value_id'] . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "repository_pd SET product_num = '" .(int)$product_num . "' WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . $data['product_id'] . "'");
        }

        $this->event->trigger('pre.admin.repositpry_pd.decrease',$data['product_option_value_id']);
    }


    //输入 repository_id
    //输出 对应repository_id行数
    public function getTotalRepository_pdbyrepoId($data){
        $sql = "SELECT DISTINCT COUNT(*) total FROM " . DB_PREFIX . "repository_pd r LEFT JOIN ".DB_PREFIX."product_option_value pov ON (r.product_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (r.product_id = pd.product_id) WHERE repository_id = '" . (int)$data['repository_id'] ."'";
        return $this->db->query($sql)->row['total'];
    }


    //输入 (必选)repository_id
    //     (可选)filter_prod_name, filter_prod_ovdname
    //输出 对应的所有商品项之和
    public function getTotalRepositort_pdbyfilter($data){
        $sql = "SELECT COUNT(*) total FROM " . DB_PREFIX . "repository_pd r LEFT JOIN ".DB_PREFIX."product_option_value pov ON (r.product_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (r.product_id = pd.product_id) WHERE ";

        if (!empty($data['filter_prod_id'])) {
            $sql .= " r.product_id = '".(int)$data['filter_prod_id']."' AND";
        }

        if (!empty($data['filter_prod_ovdname'])) {
            $sql .= " ovd.name LIKE '%" . $this->db->escape($data['filter_prod_ovdname']) . "%' AND";
        }

        $sql .= " repository_id = '" . (int)$data['repository_id'] ."'";

        return $this->db->query($sql)->row['total'];
    }

    //输入 (必须) repository_id,
    //     (可选) sort,order, start, limit, filter_prod_name, filter_prod_ovdname
    //输出  product_id, pdname, ovdname, product_num
    public function getRepository_pdbyrepoId($data){
        $sql = "SELECT DISTINCT r.product_id, p.model, pd.name pdname, ovd.name ovdname, r.product_num FROM  ".DB_PREFIX."repository_pd r LEFT JOIN ".DB_PREFIX."product p ON(r.product_id = p.product_id) LEFT JOIN ".DB_PREFIX."product_option_value pov ON (r.product_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (r.product_id = pd.product_id) WHERE ";

        if (!empty($data['filter_prod_id'])) {
            $sql .= "pd.product_id = '" . (int)$data['filter_prod_id'] . "' AND";
        }

        if (!empty($data['filter_prod_ovdname'])) {
            $sql .= " ovd.name LIKE '%" . $this->db->escape($data['filter_prod_ovdname']) . "%' AND";
        }

        $sql .= " repository_id = '" . (int)$data['repository_id'] ."'";

        $sort_data = array(
            'pdname',
            'model',
            'ovdname',
            'product_num'
            );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY model, pdname, ovdname, product_num";
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
        //$this->log->write($query);
        //$this->log->write($this->db->query($sql)->rows);
        return $query->rows;
    }


    //输入 (必须) user_id
    //     (可选) sort, order, start, limit
    //输出  repo_name, repo_type, prod_name, prod_ovdname, quantity
    public function getRepository_pdbyUid($data){
        $sql = "SELECT r.repository_name repo_name, rt.name repo_type, pd.name prod_name, p.product_id, p.model, ovd.name prod_ovdname, rp.product_num quantity FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id) LEFT JOIN ".DB_PREFIX."repository_type rt ON(r.repository_type_id = rt.repository_type_id) LEFT JOIN ".DB_PREFIX."repository_pd rp ON(ru.repository_id = rp.repository_id) LEFT JOIN ".DB_PREFIX."product p ON(rp.product_id=p.product_id) LEFT JOIN ".DB_PREFIX."product_description pd ON(rp.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rp.product_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id = ovd.option_value_id) WHERE ru.user_id = '".(int)$data['user_id']."'";

        if (isset($data['filter_repo_name'])) {
            $sql .= " AND r.repository_name LIKE '".$this->db->escape($data['filter_repo_name'])."%'";
        }

        if (isset($data['filter_prod_id'])) {
            $sql .= " AND p.product_id = '".(int)$data['filter_prod_id']."'";
        } else if (isset($data['filter_prod_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_prod_name'])."%'";
        }

        $sort_data = array(
            'repo_name',
            'prod_name',
            'quantity'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . ", pd.name";
        } else {
            $sql .= " ORDER BY repo_name, pd.name";
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
     * @param  int $user_id
     * @return int  total number of items
     */
    public function getTotalRepository_pdbyUid($data){
        $sql = "SELECT COUNT(*) total FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id) LEFT JOIN ".DB_PREFIX."repository_type rt ON(r.repository_type_id = rt.repository_type_id) LEFT JOIN ".DB_PREFIX."repository_pd rp ON(ru.repository_id = rp.repository_id) LEFT JOIN ".DB_PREFIX."product p ON(rp.product_id = p.product_id) LEFT JOIN ".DB_PREFIX."product_description pd ON(rp.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rp.product_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id = ovd.option_value_id) WHERE ru.user_id = '".(int)$data['user_id']."'";

        if (isset($data['filter_repo_name'])) {
            $sql .= " AND r.repository_name LIKE '".$this->db->escape($data['filter_repo_name'])."%'";
        }

        if (isset($data['filter_prod_id'])) {
            $sql .= " AND p.product_id = '".(int)$data['filter_prod_id']."'";
        } else if (isset($data['filter_prod_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_prod_name'])."%'";
        }

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    //输入 （必须）product_option_value_id, product_id
    //     （可选）sort, order, start, limit
    //输出  array(repository_name => product_num)
    public function getRepository_pdbyprodId($data){
        if(isset($data['product_option_value_id'])){
            $sql = "SELECT DISTINCT r.repository_id, r.repository_name, rp.product_num FROM " . DB_PREFIX . "repository r LEFT JOIN ".DB_PREFIX."repository_pd rp ON (r.repository_id = rp.repository_id) WHERE rp.product_option_value_id = '" . (int)$data['product_option_value_id'] . "'";
        }   else {
            $sql = "SELECT DISTINCT r.repository_id, r.repository_name, rp.product_num FROM " . DB_PREFIX . "repository r LEFT JOIN ".DB_PREFIX."repository_pd rp ON (r.repository_id = rp.repository_id) WHERE rp.product_id = '" . (int)$data['product_id'] . "'";
        }

        $query = $this->db->query($sql);
        $res = array();
        foreach ($query->rows as $row) {
            $res[$row['repository_name']] = $row['product_num'];
        }
        return $res;
    }


    //输入 product_id
    //输出 对应商品数量之和
    public function getRepository_pd_numbyprodId($product_id){
        $query = $this->db->query("SELECT IFNULL(SUM(IFNULL(product_num,0)),0) sum FROM ".DB_PREFIX."repository_pd WHERE product_id = '".(int)$product_id."'");
        return $query->row['sum'];
    }

    //输入 filter_prod_name, filter_prod_ovdname
    //输出 name
    public function getRepository_pdnames($data){

        $sql = "SELECT DISTINCT p.product_id, p.model, pd.name pdname, ovd.name ovdname, pov.quantity, pov.product_option_value_id FROM " . DB_PREFIX . "product p LEFT JOIN ".DB_PREFIX."product_option_value pov ON (p.product_id = pov.product_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) LEFT JOIN ".DB_PREFIX."product_description pd ON (p.product_id = pd.product_id) ";

        if (!empty($data['filter_prod_name'])) {
            $sql .= "WHERE p.subtract = 1 AND (pd.name LIKE '%" . $this->db->escape($data['filter_prod_name']) . "%' OR p.model LIKE '%" . $this->db->escape($data['filter_prod_name'])."%')";
        }


        $sql .= " ORDER BY pdname";
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
        $query = $this->db->query($sql);
        return $query->rows;
    }

    //输入 product_id
    //输出 ovdname
    public function getRepository_ovd_names($product_id){

        $sql = "SELECT ovd.name ovdname, pov.product_option_value_id FROM ".DB_PREFIX."product_option_value pov LEFT JOIN ".DB_PREFIX."option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '".(int)$product_id."' AND pov.subtract = 1";
        // if (isset($data['order']) && ($data['order'] == 'DESC')) {
        //     $sql .= " DESC";
        // } else {
        //     $sql .= " ASC";
        // }
        $query = $this->db->query($sql);
        return $query->rows;
    }


    //输入 repository_id, user_id
    //
    public function addRepository_user($data){
          $this->event->trigger('pre.admin.repository_user.add',$data);
          $results = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_user WHERE repository_id = '".(int)$data['repository_id']."' AND user_id = '".(int)$data['user_id']."'");
          if($results->num_rows > 0){
            $this->event->trigger('post.admin.repository_user.add',$data['user_id']);
            return -1;
          }
          $this->db->query("INSERT INTO ".DB_PREFIX."repository_user SET repository_id = '".(int)$data['repository_id']."', user_id = '".(int)$data['user_id']."'");
          $this->event->trigger('post.admin.repository_user.add',$data['user_id']);
    }


    //输入 repository_id, user_id
    //
    public function delRepository_user($data){
        $this->event->trigger('pre.admin.repositpry_user.del',$data);
        if(!isset($data['repository_id']))
            return;
        if(isset($data['user_id'])){
            $this->db->query("DELETE FROM ".DB_PREFIX."repository_user WHERE repository_id = '".(int)$data['repository_id']."' AND user_id = '".(int)$data['user_id']."'");
        } else {
            $this->db->query("DELETE FROM ".DB_PREFIX."repository_user WHERE repository_id = '".(int)$data['repository_id']."'");
        }
        $this->event->trigger('post.admin.repositpry_user.del',$data['user_id']);
    }

    //输入 user_id
    //输出 对应repository数量
    public function getTotalRepositorybyUid($data){
        $query = $this->db->query("SELECT COUNT(*) total FROM ".DB_PREFIX."repository_user WHERE user_id = '".(int)$data['user_id']."'");
        return $query->row['total'];
    }


    //输入 user_id
    //输出 repository_ids,  repository_names
    public function getRepositorybyUid($data){

        $query = $this->db->query("SELECT ru.repository_id, r.repository_name FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id = r.repository_id) WHERE user_id = '".(int)$data['user_id']."'");

        return $query->rows;
    }


    //输入 repository_id
    //输出 对应user数量
    public function getTotalUserbyrepoId($data){
        $query = $this->db->query("SELECT COUNT(*) total FROM ".DB_PREFIX."repository_user WHERE repository_id = '".(int)$data['repository_id']."'");
        return $query->row['total'];
    }


    //输入 repository_id
    //输出 user_ids, username
    public function getUserbyrepoId($data){
        $query = $this->db->query("SELECT ru.user_id, u.username FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."user u ON(ru.user_id = u.user_id) WHERE repository_id = '".(int)$data['repository_id']."'");
        return $query->rows;
    }

    //输入 repository_id, user_id
    //输出
    public function getLastOpt($data){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_user WHERE repository_id = '".(int)$data['repository_id']."' AND user_id = '".(int)$data['user_id']."'");
        return $query->row;
    }


    //输入 repository_id, user_id, opt_type, product_id, prod_option_value_id, product_num
    //
    public function updateLastOpt($data){
        $this->db->query("UPDATE ".DB_PREFIX."repository_user SET opt_date = sysdate(), product_id = '".(int)$data['product_id']."', product_option_value_id = '".(isset($data['prod_option_value_id'])?(int)$data['prod_option_value_id']:"NULL")."', opt_type = '".$data['opt_type']."', quantity = '".(int)$data['product_num']."' WHERE repository_id = '".(int)$data['repository_id']."' AND user_id = '".(int)$data['user_id']."'");
    }

    //输入 repository_id, user_id, product_id, product_option_value_id, opt_name, quantity
    //
    public function addRepository_opt($data){
        $this->event->trigger('pre.admin.repository_opt_history.add',$data);
        $this->db->query("INSERT INTO ".DB_PREFIX."repository_opt_history SET repository_id = '".(int)$data['repository_id']."', user_id = '".(int)$data['user_id']."', opt_date = sysdate(), product_id = '".(int)$data('product_id')."', product_option_value_id = '".(int)$data['product_option_value_id']."', opt_name = '".$this->db->escape($data['opt_name'])."', quantity = '".(int)$data['quantity']."'");
        $this->event->trigger('post.admin.repository_opt_history.add',$data);
    }

    //输入 (可选)repository_id, user_id
    //输出 所有对应的记录以及日期
    public function getRepository_opt_hostory($data){
        $sql = "SELECT * FROM ".DB_PREFIX."repository_opt_history WHERE ";
        if(isset($data['repository_id'])){
            $sql .= "repository_id = '".(int)$data['repository_id']."' AND ";
        }
        if(isset($data['user_id'])){
            $sql .= "user_id = '".$data['user_id']."'";
        }
        $sort_data = array(
            'repository_id',
            'user_id',
            'opt_date'
            );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY opt_date, repository_id, user_id";
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


    //输入 user_id
    //输出 bool值,判断是否有权限修改repository_user信息
    public function isValidAdmin($data){
        $result = $this->db->query("SELECT * FROM ".DB_PREFIX."user_group WHERE ".(int)$data['user_group_id']." = '1'");
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }
       /**
     * @param  [string] $name [repository_name]
     * @return [int]       [repository_id]
     */
    public  function getRepositoriesByName($name){
        $sql="SELECT repository_id FROM ".DB_PREFIX."repository WHERE repository_name = '".$this->db->escape($name)."'";

        $query = $this->db->query($sql);
        return $query->row['repository_id'];
    }


    //输入 order_product_id, repository_id, quantity, user_id
    //
    public function addOrderProductRepository($data){
        $this->event->trigger('pre.admin.orderproductrepository.add',$data);
        $sql = "INSERT INTO ".DB_PREFIX."order_product_repository SET order_product_id = '".(int)$data['order_product_id']."', repository_id = '".(int)$data['repository_id']."', quantity = '".(int)$data['quantity']."', user_id = '".(int)$data['user_id']."', timestamp = sysdate()";
        $this->db->query($sql);
        $this->event->trigger('post.admin.orderproductrepository.add',$data['order_product_id']);
    }

    //输入 order_product_id
    //输出 repository_id, repository_id, quantity
    public function getOrderProductRepository($data){
        $sql = "SELECT opr.repository_id, r.repository_name, opr.quantity FROM ".DB_PREFIX."order_product_repository opr LEFT JOIN ".DB_PREFIX."repository r ON(opr.repository_id = r.repository_id) WHERE order_product_id = '".(int)$data['order_product_id']."'";
        $query = $this->db->query($sql);
        $results = array();
        foreach ($query->rows as $item) {
            $results[$item['repository_id']] =  array(
                'name' => $item['repository_name'],
                'quantity' => $item['quantity']
                );
        }
        return $results;
    }


    //输入  repo_activity_id, product_id, send_repository_id, send_prod_option_value_id, send_quantity, send_user_id, receive_repository_id
    public function addRepository_trans_ongoing($data){
        $sql = "INSERT INTO ".DB_PREFIX."repository_trans_ongoing SET repo_activity_id = '".(int)$data['repo_activity_id']."', product_id = '".(int)$data['product_id']."', send_repository_id = '".(int)$data['repository_id']."', send_quantity = '".(int)$data['product_num']."', send_user_id = '".(int)$data['user_id']."', receive_repository_id = '".(int)$data['receive_repository_id']."', send_timestamp = sysdate()";
        if(isset($data['prod_option_value_id'])){
            $sql .= ", send_prod_option_value_id = '".(int)$data['prod_option_value_id']."'";
        }
        $this->db->query($sql);
    }


    //输入 repo_activity_id,
    public function delRepository_trans_ongoing($repo_activity_id){
        $this->event->trigger('pre.admin.repository_trans_ongoing.del', $repo_activity_id);
        $query = $this->db->query("DELETE FROM ".DB_PREFIX."repository_trans_ongoing WHERE repo_activity_id = '".(int)$repo_activity_id."'");
        $this->event->trigger('post.admin.repository_trans_ongoing.del', $repo_activity_id);
    }

    /**
     * Luke added to get the row in oc_repository_trans_ongoing
     * by repo_activity_id.
     * @param  int $repo_activity_id
     * @return array the info stored in the row.
     */
    public function getRepository_trans_onging_byRepoActivityId($repo_activity_id) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."repository_trans_ongoing WHERE repo_activity_id = '".(int)$repo_activity_id."'");
        return $query->row;
    }

    // 输入 repository_id
    // 输出 对应的 repo_activity_id
    public function getRepo_activity_idByrepoId($repository_id) {
        $query = $this->db->query("SELECT repo_activity_id FROM ".DB_PREFIX."repository_trans_ongoing WHERE receive_repository_id = '".(int)$repository_id."'");
        return $query->rows;
    }
    /**
     * 自动匹配repo_activity_id
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function getRepo_activity_idAuto($data){
        if (isset($data['product_option_value_id'])) {
            $query=$this->db->query("SELECT repo_activity_id FROM ".DB_PREFIX."repository_trans_ongoing WHERE send_repository_id= '".(int)$data['send_repository_id']."' AND send_prod_option_value_id='".(int)$data['product_option_value_id']."' AND product_id='".(int)$data['product_id']."' AND send_quantity='".$data['product_num']."'AND receive_repository_id='".(int)$data['repository_id']."'");
        }else{
            $query=$this->db->query("SELECT repo_activity_id FROM ".DB_PREFIX."repository_trans_ongoing WHERE send_repository_id= '".(int)$data['send_repository_id']."' AND product_id='".(int)$data['product_id']."' AND send_quantity='".$data['product_num']."'AND receive_repository_id='".(int)$data['repository_id']."'");
        }

        if ($query->num_rows) {
            return $query->rows[0];
        }else{
            return false;
        }
    }

    //输入 repository_id, product_id
    //输出 对应的 repo_activity_id
    public function getRepo_activity_id($data){
        $query = $this->db->query("SELECT repo_activity_id FROM ".DB_PREFIX."repository_trans_ongoing WHERE send_repository_id = '".(int)$data['repository_id']."' AND product_id = '".(int)$data['product_id']."'");
        return $query->rows;
    }


    //输入 repo_activity_id
    //输出 product_id, product_name, send_prod_option_value_id, option_value_name, send_quantity
    public function getProdsbyRepo_activity_id($repo_activity_id){
        $query = $this->db->query("SELECT rto.product_id, pd.name product_name, rto.send_prod_option_value_id, ovd.name option_value_name, rto.send_quantity FROM ".DB_PREFIX."repository_trans_ongoing rto LEFT JOIN ".DB_PREFIX."product_description pd ON(rto.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rto.send_prod_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id = ovd.option_value_id) WHERE rto.repo_activity_id = '".(int)$repo_activity_id."'");
        return $query->rows;
    }


    //输入 product_id, send_repository_id, send_prod_option_value_id, send_quantity, send_user_id,
    //新增 staff_code (借仪器的员工号)
    //     (可选)receive_repository_id
    public function addRepository_trans_history_out($data){

        $sql = "INSERT INTO ".DB_PREFIX."repository_trans_history SET product_id = '".(int)$data['product_id']."', send_repository_id = '".(int)$data['repository_id']."', send_quantity = '".(int)$data['product_num']."', send_user_id = '".(int)$data['user_id']."', staff_code = '".(int)$data['staff_code']."', send_timestamp = sysdate()";
        if(isset($data['receive_repository_id'])){
            $sql .= ", receive_repository_id = '".(int)$data['receive_repository_id']."'";
        }
        if(isset($data['prod_option_value_id'])){
            $sql .= ", send_prod_option_value_id = '".(int)$data['prod_option_value_id']."'";
        }
        if(isset($data['for_order'])){
            $sql .= ", for_order = '".(int)$data['for_order']."'";
        }
        if(isset($data['order_id'])){
            $sql .= ", order_id = '".(int)$data['order_id']."'";
        }

        $this->db->query($sql);
        $query = $this->db->query("SELECT MAX(repo_activity_id) last_raid FROM ".DB_PREFIX."repository_trans_history");
        return $query->row['last_raid'];

    }


    //输入 repo_activity_id, repository_id, prod_option_value_id, product_num, user_id
    public function addRepository_trans_history_in($data){
        $this->event->trigger('pre.admin.repository_trans_ongoing.add', $data);
        if(isset($data['repo_activity_id'])){
            $sql = "UPDATE ".DB_PREFIX."repository_trans_history SET receive_quantity = '".(int)$data['product_num']."', receive_user_id = '".(int)$data['user_id']."', receive_timestamp = sysdate()";
            if(isset($data['prod_option_value_id'])){
                $sql .= ", receive_prod_option_value_id = '".(int)$data['prod_option_value_id']."'";
            }
            $sql .= " WHERE repo_activity_id = '".(int)$data['repo_activity_id']."'";
            $this->db->query($sql);
        } else{
            $sql = "INSERT INTO ".DB_PREFIX."repository_trans_history SET product_id = '".(int)$data['product_id']."', receive_repository_id = '".(int)$data['repository_id']."', receive_quantity = '".(int)$data['product_num']."', receive_user_id = '".(int)$data['user_id']."', receive_timestamp = sysdate()";
            if(isset($data['prod_option_value_id'])){
                $sql .= ", receive_prod_option_value_id = '".(int)$data['prod_option_value_id']."'";
            }
            // 处理报错信息
            if ($data['transfer_error']!=0) {
                 $reponame_query = $this->db->query("SELECT repository_name FROM " . DB_PREFIX . "repository WHERE repository_id = '" . (int)$data['repository_id'] . "'");
                $sql .=" ,trans_error='试图从".$reponame_query->row['repository_name']."错误码为".$data['transfer_error']."转库入库失败，改为直接入库'";
            }
            $this->db->query($sql);
        }

        $this->event->trigger('post.admin.repository_trans_ongoing.add', $data['product_id']);
    }

    public function getProductOptionValuebyPOV($product_option_value_id){
        $query = $this->db->query("SELECT ovd.name option_value_name FROM ".DB_PREFIX."product_option_value pov LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '".(int)$product_option_value_id."'");
        return $query->row['option_value_name'];
    }

    public function getRepoidByRepo_activity_id($repository_activity_id) {
        $query = $this->db->query("SELECT receive_repository_id FROM ".DB_PREFIX."repository_trans_ongoing WHERE repo_activity_id = '".(int)$repository_activity_id."'");
        return $query->row['receive_repository_id'];
    }

    /**
     * @param  [int] $product_id [product_id]
     * @return [string]             [product_name]
     */
    public function getProductNameById($product_id){
        $sql="SELECT name FROM ".DB_PREFIX."product_description WHERE product_id='".(int)$product_id."'";

        return $this->db->query($sql)->row['name'];
    }
    /**
     *
     */
    public function getRepository_types_name(){
        $sql="SELECT name FROM ".DB_PREFIX ."repository_type";

        return $this->db->query($sql)->rows;
    }

    /**
     *
     */
    public function getRepository_type_name_byRepo_id($repository_id){
        $sql="SELECT repository_type_id FROM ".DB_PREFIX ."repository WHERE repository_id = '". (int)$repository_id."'";

        return $this->db->query($sql)->row['repository_type_id'];
    }

    /**
     * [getRepository_type_name description]
     * @param  [type] $repository_type_id [description]
     * @return [type]                     [description]
     */
    public function getRepository_type_name($repository_type_id){
        $sql="SELECT name FROM ".DB_PREFIX."repository_type WHERE repository_type_id='".(int)$repository_type_id."'";

        return $this->db->query($sql)->row['name'];
    }
    /**
     * [getRepository_type_id description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function getRepository_type_id($name){

        $query=$this->db->query("SELECT repository_type_id FROM ".DB_PREFIX."repository_type WHERE name='".$this->db->escape($name)."'");

        return $query->row['repository_type_id'];
    }

    /**
     *
     */
    public function getRepositoryByType_id($repository_type_id){
        $query=$this->db->query("SELECT repository_name,repository_id FROM ".DB_PREFIX."repository WHERE repository_ty_id='".(int)$repository_type_id."'");

        return $query->rows;
    }

    public function getProdNumByRepoId($data){

        $query = $this->db->query("SELECT product_num as quantity_repo FROM " . DB_PREFIX . "repository_pd WHERE repository_id = '" . (int)$data['repository_id'] . "' AND product_id = '" . $data['product_id'] . "'");

        return $query->rows;
    }

    public function getTotalRepository_inout($data){
        $sql = "(SELECT r.repository_name repo_name, rth.send_timestamp timestamp, u.username user_name, pd.name prod_name, pd.product_id, p.model, ovd.name ovd_name, rth.send_quantity quantity, (!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) type, (!isnull(rth.send_repository_id)) AS repo_opt, rth.for_order FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id=r.repository_id) LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(ru.repository_id=rth.send_repository_id) LEFT JOIN ".DB_PREFIX."user u ON(u.user_id=rth.send_user_id) LEFT JOIN ".DB_PREFIX."product p ON(p.product_id=rth.product_id) LEFT JOIN ".DB_PREFIX."product_description pd ON(rth.product_id=pd.product_id) LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rth.send_prod_option_value_id=pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id=ovd.option_value_id) WHERE ru.user_id = '".(int)$data['user_id']."' AND rth.send_repository_id is not NULL";

        if (isset($data['filter_repo_name'])) {
            $sql .= " AND r.repository_name LIKE '".$this->db->escape($data['filter_repo_name'])."%'";
        }

        if (isset($data['filter_user_name'])) {
            $sql .= " AND u.username LIKE '".$this->db->escape($data['filter_user_name'])."%'";
        }

        if (isset($data['filter_prod_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_prod_name'])."%'";
        }

        if (isset($data['filter_prod_id'])) {
            $sql .= " AND pd.product_id = '".(int)$data['filter_prod_id']."'";
        }

        if (isset($data['filter_reason'])){
            if($data['filter_reason']==1){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND !(!isnull(rth.send_repository_id))";
            }else if($data['filter_reason']==2){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND (!isnull(rth.send_repository_id)) AND rth.for_order = 0";
            }else if($data['filter_reason']==3){
                $sql .= " AND (!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id))";
            }else if($data['filter_reason']==4){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND (!isnull(rth.send_repository_id)) AND rth.for_order = 1";
            }
        }
        $sql .= ") UNION (";
        $sql .= "SELECT r.repository_name repo_name, rth.receive_timestamp timestamp, u.username user_name, pd.name prod_name, pd.product_id, p.model, ovd.name ovd_name, rth.receive_quantity quantity, (!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) type, (isnull(rth.receive_repository_id)) AS repo_opt, rth.for_order FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id=r.repository_id) LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(ru.repository_id=rth.receive_repository_id) LEFT JOIN ".DB_PREFIX."user u ON(u.user_id=rth.receive_user_id) LEFT JOIN ".DB_PREFIX."product p ON(p.product_id=rth.product_id) LEFT JOIN ".DB_PREFIX."product_description pd ON(rth.product_id=pd.product_id) LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rth.receive_prod_option_value_id=pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id=ovd.option_value_id) WHERE ru.user_id = '".(int)$data['user_id']."' AND rth.receive_repository_id is not NULL AND rth.receive_quantity is not NULL";


        if (isset($data['filter_repo_name'])) {
            $sql .= " AND r.repository_name LIKE '".$this->db->escape($data['filter_repo_name'])."%'";
        }

        if (isset($data['filter_user_name'])) {
            $sql .= " AND u.username LIKE '".$this->db->escape($data['filter_user_name'])."%'";
        }

        if (isset($data['filter_prod_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_prod_name'])."%'";
        }

        if (isset($data['filter_prod_id'])) {
            $sql .= " AND pd.product_id = '".(int)$data['filter_prod_id']."'";
        }

        if (isset($data['filter_reason'])){
            if($data['filter_reason']==1){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND !(!isnull(rth.send_repository_id))";
            }else if($data['filter_reason']==2){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND (!isnull(rth.send_repository_id)) AND rth.for_order = 0";
            }else if($data['filter_reason']==3){
                $sql .= " AND (!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id))";
            }else if($data['filter_reason']==4){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND (!isnull(rth.send_repository_id)) AND rth.for_order = 1";
            }
        }
        $sql .= ")";
        $sql .= " ORDER BY timestamp DESC";

        $query = $this->db->query($sql);
        return $query->num_rows;
    }

    public function getRepository_inout($data){

        // if (INSTRUMENT_SWITCH=="ON") {
        //  $sql = "
        //     (
        //     SELECT
        //     rth.staff_code staff_code,

        //     r.repository_name repo_name,
        //     rth.send_timestamp timestamp,
        //     u.username user_name,
        //     pd.name prod_name,
        //     pd.product_id,
        //     p.model,
        //     ovd.name ovd_name,
        //     rth.send_quantity quantity,
        //     (!isnull(rth.send_repository_id)
        //     AND
        //     !isnull(rth.receive_repository_id)) type,
        //     (!isnull(rth.send_repository_id)) AS repo_opt,
        //     rth.for_order

        //     FROM ".DB_PREFIX."repository_user ru
        //         LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id=r.repository_id)
        //         LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(ru.repository_id=rth.send_repository_id)
        //         LEFT JOIN ".DB_PREFIX."user u ON(u.user_id=rth.send_user_id)
        //         LEFT JOIN ".DB_PREFIX."product p ON(p.product_id=rth.product_id)
        //         LEFT JOIN ".DB_PREFIX."product_description pd ON(rth.product_id=pd.product_id)
        //         LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rth.send_prod_option_value_id=pov.product_option_value_id)
        //         LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id=ovd.option_value_id)
        //             WHERE ru.user_id = '".(int)$data['user_id']."'
        //                 AND rth.send_repository_id is not NULL";
        // }else{
            $sql = "
                    (
                    SELECT
            r.repository_name repo_name,
            rth.send_timestamp timestamp,
            u.username user_name,
            pd.name prod_name,
            pd.product_id,
            p.model,
            ovd.name ovd_name,
            rth.send_quantity quantity,
            (!isnull(rth.send_repository_id)
            AND
            !isnull(rth.receive_repository_id)) type,
            (!isnull(rth.send_repository_id)) AS repo_opt,
            rth.for_order

            FROM ".DB_PREFIX."repository_user ru
                LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id=r.repository_id)
                LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(ru.repository_id=rth.send_repository_id)
                LEFT JOIN ".DB_PREFIX."user u ON(u.user_id=rth.send_user_id)
                LEFT JOIN ".DB_PREFIX."product p ON(p.product_id=rth.product_id)
                LEFT JOIN ".DB_PREFIX."product_description pd ON(rth.product_id=pd.product_id)
                LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rth.send_prod_option_value_id=pov.product_option_value_id)
                LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id=ovd.option_value_id)
                    WHERE ru.user_id = '".(int)$data['user_id']."'
                        AND rth.send_repository_id is not NULL";
        // }

        if (isset($data['filter_repo_name'])) {
            $sql .= " AND r.repository_name LIKE '".$this->db->escape($data['filter_repo_name'])."%'";
        }

        if (isset($data['filter_user_name'])) {
            $sql .= " AND u.username LIKE '".$this->db->escape($data['filter_user_name'])."%'";
        }

        if (isset($data['filter_prod_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_prod_name'])."%'";
        }

        if (isset($data['filter_prod_id'])) {
            $sql .= " AND pd.product_id = '".(int)$data['filter_prod_id']."'";
        }

        if (isset($data['filter_reason'])){
            if($data['filter_reason']==1){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND !(!isnull(rth.send_repository_id))";
            }else if($data['filter_reason']==2){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND (!isnull(rth.send_repository_id)) AND rth.for_order = 0";
            }else if($data['filter_reason']==3){
                $sql .= " AND (!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id))";
            }else if($data['filter_reason']==4){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND (!isnull(rth.send_repository_id)) AND rth.for_order = 1";
            }
        }

        $sql .= ") UNION (";

        // if (INSTRUMENT_SWITCH=='ON') {
        //     $sql .= "
        //     SELECT
        //     rth.staff_code staff_code,
        //     r.repository_name repo_name,
        //     rth.receive_timestamp timestamp,
        //     u.username user_name,
        //     pd.name prod_name,
        //     pd.product_id,
        //     p.model,
        //     ovd.name ovd_name,
        //     rth.receive_quantity quantity,
        //     (!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) type,
        //     (isnull(rth.receive_repository_id)) AS repo_opt,
        //     rth.for_order

        //         FROM ".DB_PREFIX."repository_user ru

        //         LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id=r.repository_id)
        //         LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(ru.repository_id=rth.receive_repository_id)
        //         LEFT JOIN ".DB_PREFIX."user u ON(u.user_id=rth.receive_user_id)
        //         LEFT JOIN ".DB_PREFIX."product p ON(p.product_id=rth.product_id)
        //         LEFT JOIN ".DB_PREFIX."product_description pd ON(rth.product_id=pd.product_id)
        //         LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rth.receive_prod_option_value_id=pov.product_option_value_id)
        //         LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id=ovd.option_value_id)

        //             WHERE ru.user_id = '".(int)$data['user_id']."'
        //                 AND rth.receive_repository_id is not NULL
        //                 AND rth.receive_quantity is not NULL";
        // }else{
            $sql .= "
            SELECT
            r.repository_name repo_name,
            rth.receive_timestamp timestamp,
            u.username user_name,
            pd.name prod_name,
            pd.product_id,
            p.model,
            ovd.name ovd_name,
            rth.receive_quantity quantity,
            (!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) type,
            (isnull(rth.receive_repository_id)) AS repo_opt,
            rth.for_order

                FROM ".DB_PREFIX."repository_user ru

                LEFT JOIN ".DB_PREFIX."repository r ON(ru.repository_id=r.repository_id)
                LEFT JOIN ".DB_PREFIX."repository_trans_history rth ON(ru.repository_id=rth.receive_repository_id)
                LEFT JOIN ".DB_PREFIX."user u ON(u.user_id=rth.receive_user_id)
                LEFT JOIN ".DB_PREFIX."product p ON(p.product_id=rth.product_id)
                LEFT JOIN ".DB_PREFIX."product_description pd ON(rth.product_id=pd.product_id)
                LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rth.receive_prod_option_value_id=pov.product_option_value_id)
                LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id=ovd.option_value_id)

                    WHERE ru.user_id = '".(int)$data['user_id']."'
                        AND rth.receive_repository_id is not NULL
                        AND rth.receive_quantity is not NULL";
        // }

        if (isset($data['filter_repo_name'])) {
            $sql .= " AND r.repository_name LIKE '".$this->db->escape($data['filter_repo_name'])."%'";
        }

        if (isset($data['filter_user_name'])) {
            $sql .= " AND u.username LIKE '".$this->db->escape($data['filter_user_name'])."%'";
        }

        if (isset($data['filter_prod_name'])) {
            $sql .= " AND pd.name LIKE '".$this->db->escape($data['filter_prod_name'])."%'";
        }

        if (isset($data['filter_prod_id'])) {
            $sql .= " AND pd.product_id = '".(int)$data['filter_prod_id']."'";
        }

        if (isset($data['filter_reason'])){
            if($data['filter_reason']==1){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND !(!isnull(rth.send_repository_id))";
            }else if($data['filter_reason']==2){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND (!isnull(rth.send_repository_id)) AND rth.for_order = 0";
            }else if($data['filter_reason']==3){
                $sql .= " AND (!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id))";
            }else if($data['filter_reason']==4){
                $sql .= " AND !(!isnull(rth.send_repository_id) AND !isnull(rth.receive_repository_id)) AND (!isnull(rth.send_repository_id)) AND rth.for_order = 1";
            }
        }

        $sql .= ")";
        $sql .= " ORDER BY timestamp DESC";
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        // var_dump($sql);exit();
        $query = $this->db->query($sql);
        return $query->rows;
    }

    /**
     * Luke created to add error reason for repo trans.
     * @param array $data [repo_activity_id =>, error_reason =>]
     */
    public function addTransErrorReason($data) {
        // $sql = "INSERT INTO ".DB_PREFIX."trans_error_reason SET repo_activity_id = '".(int)$data['repo_activity_id']."', error_reason = '".$this->db->escape($data['error_reason'])."'";
        // $this->db->query($sql);
        $sql="UPDATE ".DB_PREFIX."repository_trans_history SET trans_error='".$this->db->escape($data['error_reason'])."' WHERE repo_activity_id='".(int)$data['repo_activity_id']."'";

        $this->db->query($sql);
    }

}
