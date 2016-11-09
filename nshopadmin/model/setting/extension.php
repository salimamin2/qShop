<?php
class ModelSettingExtension extends Model {
	public function getInstalled($type) {
		$extension_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
		
		foreach ($query->rows as $result) {
			$extension_data[] = $result['key'];
		}
		
		return $extension_data;
	}
	
	public function install($type, $key) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `key` = '" . $this->db->escape($key) . "'");
                $extension_id = $this->db->getLastId();
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_link SET `extension_id` = '" . (int)$extension_id . "'");
	}
	
	public function uninstall($type, $key) {
                $query = $this->db->query("SELECT e.extension_id FROM " . DB_PREFIX . "customer_group_link c LEFT JOIN " . DB_PREFIX . "extension e ON c.extension_id = e.extension_id WHERE `type` = '" . $this->db->escape($type) . "' AND `key` = '" . $this->db->escape($key) . "'");
		$extension_id = $query->row['extension_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `key` = '" . $this->db->escape($key) . "'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_link WHERE extension_id ='". (int)$extension_id ."'");

	}
        public function addCustomerGroupLink($customer_groups, $type, $key){
            $extension_id = $this->getExtension($type, $key);
            $query = "SELECT customer_group_link_id FROM `" . DB_PREFIX . "customer_group_link` WHERE extension_id = '" . (int)$extension_id . "'";
            $result = $this->db->query($query);
            if(isset($result->row['customer_group_link_id'])) {
                $sql = "UPDATE " . DB_PREFIX . "customer_group_link SET customer_group_ids = '" . $this->db->escape($customer_groups) . "' WHERE customer_group_link_id = '" . (int)$result->row['customer_group_link_id'] . "'";
                $this->db->query($sql);
            }
            else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_link SET `extension_id` = '" . (int)$extension_id . "', `customer_group_ids` = '" . $this->db->escape($customer_groups) . "'");
            }
        }
        public function getCustomerGroupLink($type, $key){
            $extension_id = $this->getExtension($type, $key);
            $query = $this->db->query("SELECT customer_group_ids FROM " . DB_PREFIX . "customer_group_link WHERE extension_id = '" . (int)$extension_id . "'");
            return $query->row['customer_group_ids'];
        }
        public function getExtension($type, $key){
            $query = $this->db->query("SELECT extension_id FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `key` = '" . $this->db->escape($key) . "'");
            if($query->num_rows > 0){
                return $query->row['extension_id'];
            }
        }
}
?>