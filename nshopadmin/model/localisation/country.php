<?php
class ModelLocalisationCountry extends Model {
	public function addCountry($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "country SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "'");
	
		$this->cache->delete('country');
	}
	
	public function editCountry($country_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "country SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "' WHERE country_id = '" . (int)$country_id . "'");
	
		$this->cache->delete('country');
	}
	
	public function deleteCountry($country_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		
		$this->cache->delete('country');
	}
	
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row;
	}
		
	public function getCountries() {
        $sql = "SELECT * FROM " . DB_PREFIX . "country";
        $sql .= " ORDER BY name";

        $query = $this->db->query($sql);
        return $query->rows;
	}
	
	public function getTotalCountries() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");
		
		return $query->row['total'];
	}	
}
?>