<?php
/**
*
*/
class ModelCatalogRepository extends Model
{

     /**
     *
     */
    public function getRepositoryByType_id($repository_type_id){
        $query=$this->db->query("SELECT repository_name,repository_id FROM ".DB_PREFIX."repository WHERE repository_type_id='".(int)$repository_type_id."'");

        return $query->rows;
    }

    //输入 data[]: product_id, order_product_id, quantity
    //输出 repository_id, repository_name
    public function getRepository_quantitybyId($data){

        foreach ($data as $item){

            $sql = "SELECT rp.repository_id, r.repository_name FROM ".DB_PREFIX."repository r LEFT JOIN ".DB_PREFIX."repository_pd rp ON(r.repository_id = rp.repository_id) WHERE repository_type_id =1  AND ";
            if(isset($item['option']['product_option_value_id'])){
                $sql .= "rp.product_option_value_id = '".(int)$item['option']['product_option_value_id']."'";
            } else{
                $sql .= "rp.product_id = '".(int)$item['product_id']."'";
            }
            $sql .= " AND rp.product_num >= '".(int)$item['quantity']."'";

            $query = $this->db->query($sql);

            if (isset($results)) {
                $results=array_intersect($results, $query->rows);
            }else{
                $results=$query->rows;
            }

            return $results;
        }


        // $sql = "SELECT rp.repository_id, r.repository_name FROM ".DB_PREFIX."repository r LEFT JOIN ".DB_PREFIX."repository_pd rp ON(r.repository_id = rp.repository_id) WHERE repository_type_id =1  AND ";
        // if(isset($product_option_value_id)){
        //     $sql .= "rp.product_option_value_id = '".(int)$data['product_option_value_id']."'";
        // } else{
        //     $sql .= "rp.product_id = '".(int)$data['product_id']."'";
        // }
        // $sql .= " AND rp.product_num > '".(int)$data['quantity']."'";
        // $query = $this->db->query($sql);
        // $results = array();
        // foreach ($query->rows as $item) {
        //     $results[] = array('id' => $item['repository_id'], 'name' => $item['repository_name']);
        // }
        // return $results;
    }

    //输入 (必须) order_product_id, repository_id, quantity
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

        if(isset($data['product_option_value_id'])){
            $send_data['prod_option_value_id'] = $product_option_value_id;
            $product_num=$this->db->query("SELECT product_num FROM ".DB_PREFIX."repository_pd WHERE repository_id = '".(int)$data['repository_id']."' AND product_option_value_id = '".(int)$data['product_option_value_id']."'")->row['product_num'];
            if ($product_num-(int)$data['quantity']<0) {
                return false;
            }
            $this->db->query("UPDATE ".DB_PREFIX."repository_pd SET product_num = product_num - '".(int)$data['quantity']."' WHERE repository_id = '".(int)$data['repository_id']."' AND product_option_value_id = '".(int)$data['product_option_value_id']."'");
        } else{
            $product_num=$this->db->query("SELECT product_num FROM ".DB_PREFIX."repository_pd WHERE repository_id = '".(int)$data['repository_id']."' AND product_id = '".(int)$data['product_id']."'")->row['product_num'];
            if ($product_num-(int)$data['quantity']<0) {
                return false;
            }
            $this->db->query("UPDATE ".DB_PREFIX."repository_pd SET product_num = product_num - '".(int)$data['quantity']."' WHERE repository_id = '".(int)$data['repository_id']."' AND product_id = '".(int)$data['product_id']."'");
        }
        $this->addRepository_trans_history_out($send_data);
        $this->updateLastOpt($send_data);
        return true;
        $this->event->trigger('pre.admin.repositpry_pd.add',$data['product_option_value_id']);
    }


    //输入 order_product_id, repository_id, quantity, user_id
    //
    public function addOrderProductRepository($data){
        $this->event->trigger('pre.admin.orderproductrepository.add',$data);
        $sql = "INSERT INTO ".DB_PREFIX."order_product_repository SET order_product_id = '".(int)$data['order_product_id']."', repository_id = '".(int)$data['repository_id']."', quantity = '".(int)$data['quantity']."', user_id = '".(int)$data['user_id']."', timestamp = sysdate()";
        $this->db->query($sql);
        $this->event->trigger('post.admin.orderproductrepository.add',$data['order_product_id']);
    }

    //输入 product_id, send_repository_id, send_prod_option_value_id, send_quantity, send_user_id,
    //     (可选)receive_repository_id
    public function addRepository_trans_history_out($data){
        $this->log->write('看看出入库的参数');
        $this->log->write($data);
        $sql = "INSERT INTO ".DB_PREFIX."repository_trans_history SET product_id = '".(int)$data['product_id']."', send_repository_id = '".(int)$data['repository_id']."', send_quantity = '".(int)$data['product_num']."', send_user_id = '".(int)$data['user_id']."', send_timestamp = sysdate()";
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

    //输入 repository_id, user_id, opt_type, product_id, prod_option_value_id, product_num
    //
    public function updateLastOpt($data){
        $this->db->query("UPDATE ".DB_PREFIX."repository_user SET opt_date = sysdate(), product_id = '".(int)$data['product_id']."', product_option_value_id = '".(int)$data['prod_option_value_id']."', opt_type = '".$data['opt_type']."', quantity = '".(int)$data['product_num']."' WHERE repository_id = '".(int)$data['repository_id']."' AND user_id = '".(int)$data['user_id']."'");
    }

    /**
     * 获取order_product_id
     */
    public function getOrderProductId($data){

        $query=$this->db->query("SELECT order_product_id FROM ".DB_PREFIX."order_product WHERE order_id='".(int)$data['order_id']."' AND product_id='".(int)$data['product_id']."'");

        return $query->row['order_product_id'];

    }

}
