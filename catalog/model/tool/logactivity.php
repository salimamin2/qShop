<?php
class ModelToolLogActivity extends Model {
	public function addSession($data) {
            $this->db->disableCache();
            $sql ="SELECT customer_session_id  FROM customer_session WHERE session_id = '{$data['session_id']}'";
            $result = $this->db->query($sql);
            
            $cs_session_id = 0;
            if($result && $result->num_rows)
                $cs_session_id = $result->row['customer_session_id'];
            if(!$data['customer_id']){
                $sql = "SELECT customer_id,COUNT(customer_id) FROM customer WHERE ip='{$data['ip']}'
                        GROUP BY customer_id
                        UNION
                        SELECT customer_id,COUNT(customer_id) FROM customer_session WHERE ip='{$data['ip']}' AND customer_id !=0
                        GROUP BY customer_id";
                $result = $this->db->query($sql);

                if($result && $result->num_rows)
                    $data['customer_id'] = $result->row['customer_id'];
            }
            if($cs_session_id >0){
                $sql = "UPDATE customer_session SET
                        customer_id = {$data['customer_id']},
                        url = '{$data['page']}'
                        WHERE customer_session_id = {$cs_session_id}";
                $this->db->query($sql);
            }else{
                $sql = "INSERT INTO customer_session SET
                    customer_id = {$data['customer_id']},
                    session_id = '{$data['session_id']}',
                    ip = '{$data['ip']}',
                    url = '{$data['page']}'";
                $this->db->query($sql);
                $cs_session_id = $this->db-> getLastId();
            }
            $data['customer_session_id']=$cs_session_id;
            unset($data['ip']);
            $this->db->insert('customer_session_details',$data);
            $this->db->enableCache();
	}
}
?>