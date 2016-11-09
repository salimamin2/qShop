<?php
class ModelReportOnline extends Model {
	public function getOnlineReport($data = array()) {
		$sql = "SELECT cs.customer_session_id,cs.modified_at AS session_date, IF(isnull(c.customer_id),'',c.customer_id) AS customer_id, IF(isnull(c.customer_id),'Visitor',CONCAT(c.firstname,' ',c.lastname)) AS customer, url, cs.ip,cs.session_id";
                $sql .= " FROM customer_session cs";
                $sql .= " LEFT JOIN customer c ON c.customer_id=cs.customer_id";
                if (isset($data['filter_email']) && $data['filter_email']) {
                        $sql .= " LEFT JOIN customer_session_details csd ON csd.customer_session_id = cs.customer_session_id";
                }

        $date_start = date('Y-m-d',strtotime($data['filter_date_start']));
        $date_end = date('Y-m-d',strtotime($data['filter_date_end']));

                
        if (isset($data['filter_id'])) {
            $sql .= ' WHERE customer_session_id = '.$data['filter_id'];
		}
        else{
            $sql .= " WHERE (DATE(cs.modified_at) >= '" . $this->db->escape($date_start) . "' AND DATE(cs.modified_at) <= '" . $this->db->escape($date_end) . "')";

            if (isset($data['filter_email']) && $data['filter_email']) {
                $sql .= " AND csd.data like '%" .  $data['filter_email'] . "%'";
            }

            if (isset($data['filter_customer']) && $data['filter_customer']) {
                $sql .= " AND LOWER(CONCAT(c.firstname,' ',c.lastname)) like '%" . strtolower($data['filter_customer']) . "%'";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                        $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                        $data['limit'] = 100;
                }
            }
            $sql .= " ORDER BY cs.modified_at desc";
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

                $query = $this->db->query($sql);
		
		return $query->rows;
	}	
	
	public function getOnlineReportTotal($data = array()) {
		$sql = "SELECT cs.modified_at AS session_date, IF(isnull(c.customer_id),'0',c.customer_id) AS customer_id, IF(isnull(c.customer_id),'Visitor',CONCAT(c.firstname,' ',c.lastname)) AS customer, url, cs.ip";
                $sql .= " FROM customer_session cs";
                $sql .= " LEFT JOIN customer c ON c.customer_id=cs.customer_id";
                if (isset($data['filter_email']) && $data['filter_email']) {
                        $sql .= " LEFT JOIN customer_session_details csd ON csd.customer_session_id = cs.customer_session_id";
                }
        $date_start = date('Y-m-d',strtotime($data['filter_date_start']));
        $date_end = date('Y-m-d',strtotime($data['filter_date_end']));

        $sql .= " WHERE (DATE(cs.modified_at) >= '" . $this->db->escape($date_start) . "' AND DATE(cs.modified_at) <= '" . $this->db->escape($date_end) . "')";
        if (isset($data['filter_email']) && $data['filter_email']) {
                $sql .= " AND csd.data like '%" .  $data['filter_email'] . "%'";
        }

        if (isset($data['filter_customer']) && $data['filter_customer']) {
                $sql .= " AND LOWER(CONCAT(c.firstname,' ',c.lastname)) like '%" . strtolower($data['filter_customer']) . "%'";
        }

        $sql .= " ORDER BY cs.modified_at desc";
		
		$query = $this->db->query($sql);

		return $query->num_rows;	
	}
        public function getOnlineActivity($data = array()){
            $sql = 'SELECT csd.*, IF(isnull(c.customer_id),"",c.customer_id) AS customer_id, IF(isnull(c.customer_id),"Visitor",CONCAT(c.firstname," ",c.lastname)) AS customer FROM customer_session_details csd
                    INNER JOIN customer_session cs ON cs.customer_session_id = csd.customer_session_id 
                    LEFT JOIN customer c ON c.customer_id=cs.customer_id
                    WHERE 1';
            if(isset($data['filter_customer_session_id']) && $data['filter_customer_session_id']){
                $sql.=' AND csd.customer_session_id = '.$this->db->escape($data['filter_customer_session_id']);
            }
            if(isset($data['filter_session_id']) && $data['filter_session_id']){
                $sql.=' AND csd.session_id = "'.$this->db->escape($data['filter_session_id']).'"';
            }
            if(isset($data['filter_ip']) && $data['filter_ip']){
                $sql.=' AND cs.ip = "'.$this->db->escape($data['filter_ip']).'"';
            }

            if(isset($data['filter_from_date']) && isset($data['filter_to_date'])){
                $sql.=' AND csd.created_at BETWEEN "'.$this->db->escape($data['filter_from_date']).'" AND "'.$this->db->escape($data['filter_to_date']).'"';
            }else if(isset($data['filter_from_date']) && !isset($data['filter_to_date'])){
                $sql.=' AND csd.created_at BETWEEN "'.$this->db->escape($data['filter_from_date'].' 00:00:00').'" AND "'.$this->db->escape($data['filter_from_date'].' 23:59:59').'"';
            }

            $sql .= " ORDER BY csd.created_at desc";
            
            $query = $this->db->query($sql);
            
            if(!$query || !$query->num_rows){
                return false;
            }
            return $query->rows;
        }
        
	public function getCustomers() {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) ";
                $query = $this->db->query($sql);

                return $query->rows;
	}
        
}
?>