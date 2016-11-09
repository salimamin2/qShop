<?php
class ModelLocalisationGeoZone extends Model {
	public function addGeoZone($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "geo_zone SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");

		$geo_zone_id = $this->db->getLastId();
		
		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '"  . (int)$value['country_id'] . "', zone_id = '"  . (int)$value['zone_id'] . "', geo_zone_id = '"  .(int)$geo_zone_id . "', date_added = NOW()");
			}
		}
		
		$this->cache->delete('geo_zone');
	}
	
	public function editGeoZone($geo_zone_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "geo_zone SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', date_modified = NOW() WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		
		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '"  . (int)$value['country_id'] . "', zone_id = '"  . (int)$value['zone_id'] . "', geo_zone_id = '"  .(int)$geo_zone_id . "', date_added = NOW()");
			}
		}
		
		$this->cache->delete('geo_zone');
	}
	
	public function deleteGeoZone($geo_zone_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "zone_to_geo_zone WHERE zone_id = '" . (int)$geo_zone_id . "'");

		$this->cache->delete('geo_zone');
	}
	
	public function getGeoZone($geo_zone_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		
		return $query->row;
	}

	public function getGeoZones() {
        $sql = "SELECT * FROM " . DB_PREFIX . "geo_zone";
        $sql .= " ORDER BY name";

        $query = $this->db->query($sql);

        return $query->rows;
	}
	
	public function getTotalGeoZones() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "geo_zone");
		
		return $query->row['total'];
	}	
	
	public function getZoneToGeoZones($geo_zone_id) {	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		
		return $query->rows;	
	}		

	public function getTotalZoneToGeoZoneByGeoZoneId($geo_zone_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalZoneToGeoZoneByCountryId($country_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getTotalZoneToGeoZoneByZoneId($zone_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];
	}		
}
?>