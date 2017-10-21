<?php
class ModelReportRepository extends Model {

    public function getRepoUser_topo($data){
        $results = array();
        $sql = "SELECT r.repository_name, r.repository_id, rt.name FROM oc_repository r LEFT JOIN ".DB_PREFIX."repository_type rt ON(r.repository_type_id=rt.repository_type_id) WHERE 1";

        if (isset($data['filter_name']) && !empty($data['filter_name'])) {
            $sql .= " AND r.repository_name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_type']) && !empty($data['filter_type'])) {
            $sql .= " AND rt.name LIKE '".$this->db->escape($data['filter_type'])."%'";
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
        foreach($query->rows as $item){
            $results[$item['repository_id']]['name']=$item['repository_name'];
            $results[$item['repository_id']]['type']=$item['name'];
            $users = $this->db->query("SELECT u.username FROM ".DB_PREFIX."repository_user ru LEFT JOIN ".DB_PREFIX."user u ON(ru.user_id=u.user_id) WHERE ru.repository_id = '".(int)$item['repository_id']."'");
            foreach ($users->rows as $user) {
                $results[$item['repository_id']]['users'][] = $user['username'];
            }
        }
        return $results;
    }


    public function getTotalRepoUser_topo($data){
        $results = array();
        $sql = "SELECT COUNT(*) total FROM oc_repository r LEFT JOIN ".DB_PREFIX."repository_type rt ON(r.repository_type_id=rt.repository_type_id) WHERE 1";
        if (isset($data['filter_name']) && !empty($data['filter_name'])) {
            $sql .= " AND r.repository_name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_type']) && !empty($data['filter_type'])) {
            $sql .= " AND rt.name LIKE '".$this->db->escape($data['filter_type'])."%'";
        }

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    //output: send_timestamp, send_repo, receive_repo, product_name, ovd_name, quantity
    public function getProducts_trans_ongoing($data){
        $sql = "SELECT rto.send_timestamp, r1.repository_name send_repo, r2.repository_name receive_repo, pd.name product_name, ovd.name ovd_name, rto.send_quantity quantity FROM ".DB_PREFIX."repository_trans_ongoing rto LEFT JOIN ".DB_PREFIX."repository r1 ON(rto.send_repository_id=r1.repository_id) LEFT JOIN ".DB_PREFIX."repository r2 ON(rto.receive_repository_id = r2.repository_id) LEFT JOIN ".DB_PREFIX."product_description pd ON(rto.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rto.send_prod_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id = ovd.option_value_id)";
        $sql .= " ORDER BY send_timestamp, product_name DESC";
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

    public function getTotalProduct_trans_ongoing(){
        $sql = "SELECT COUNT(*) total FROM ".DB_PREFIX."repository_trans_ongoing rto LEFT JOIN ".DB_PREFIX."repository r1 ON(rto.send_repository_id=r1.repository_id) LEFT JOIN ".DB_PREFIX."repository r2 ON(rto.receive_repository_id = r2.repository_id) LEFT JOIN ".DB_PREFIX."product_description pd ON(rto.product_id = pd.product_id) LEFT JOIN ".DB_PREFIX."product_option_value pov ON(rto.send_prod_option_value_id = pov.product_option_value_id) LEFT JOIN ".DB_PREFIX."option_value_description ovd ON(pov.option_value_id = ovd.option_value_id)";

        $query = $this->db->query($sql);
        return $query->row['total'];
    }
}
