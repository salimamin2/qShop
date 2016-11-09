<?php
class ModelSaleCustomerGroup extends Model {
	public function addCustomerGroup($data) {
            $sql = "INSERT INTO " . DB_PREFIX . "customer_group SET
                        name = '" . $this->db->escape($data['name']) . "'";
            $this->db->query($sql);
            if($this->db->getError()>0)
              return false;
            else
              return true;
	}
	
	public function editCustomerGroup($customer_group_id, $data) {

            $sql = "UPDATE " . DB_PREFIX . "customer_group SET
                        name = '" . $this->db->escape($data['name']) . "'
                    WHERE customer_group_id = '" . (int)$customer_group_id . "'";
            $this->db->query($sql);
            if($this->db->getError()>0)
              return false;
            else
              return true;
	}
	
	public function deleteCustomerGroup($customer_group_id) {
        $sql = "DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'";
        $this->db->query($sql);
        $sql = "DELETE FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$customer_group_id . "'";
        $this->db->query($sql);

        if($this->db->getError()>0)
          return false;
        else
          return true;
	}
	
	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		
		return $query->row;
	}
	
	public function getCustomerGroups() {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group WHERE is_deleted = 0";
        $sql .= " ORDER BY name";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalCustomerGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group");
		return $query->row['total'];
	}
}
?>