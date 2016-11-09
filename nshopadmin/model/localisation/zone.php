<?php
class ModelLocalisationZone extends Model {
	public function addZone($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "zone SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "'");
			
		$this->cache->delete('zone');
	}
	
	public function editZone($zone_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "zone SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "' WHERE zone_id = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');
	}
	
	public function deleteZone($zone_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');	
	}
	
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row;
	}
	
	public function getZones() {
		$sql = "SELECT *, z.name, c.name AS country FROM " . DB_PREFIX . "zone z LEFT JOIN " . DB_PREFIX . "country c ON (z.country_id = c.country_id)";
        $sql .= " ORDER BY c.name";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . $country_id);
	
		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' ORDER BY name");
	
			$zone_data = $query->rows;
			
			$this->cache->set('zone.' . $country_id, $zone_data);
		}
	
		return $zone_data;
	}
	
	public function getTotalZones() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone");
		
		return $query->row['total'];
	}
				
	public function getTotalZonesByCountryId($country_id) {
		$query = $this->db->query("SELECT count(*) AS total FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "'");
	
		return $query->row['total'];
	}
}
?>