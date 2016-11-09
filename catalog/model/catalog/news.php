<?php
class ModelCatalogNews extends Model {
	public function getNewsStory($news_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE n.news_id = '" . (int)$news_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1'");
		return $query->row;
	}

	public function getNews() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' ORDER BY n.date_added DESC");
		return $query->rows;
	}
	
	public function getNewsTop5($limit) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' ORDER BY n.date_added DESC LIMIT " . (int)$limit);
		return $query->rows;
	}
	
	public function getNewsLimited($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' ORDER BY n.date_added DESC  ";
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
	
	public function getTotalNews() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM  " . DB_PREFIX . "news where status = '1'");
		
		return $query->row['total'];
	}
}
?>
