<?php

class ModelCatalogCollection extends Model {

	public function getCollection($collection_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "collection c LEFT JOIN " . DB_PREFIX . "collection_description cd ON (c.collection_id = cd.collection_id) LEFT JOIN " . DB_PREFIX . "collection_to_store c2s ON (c.collection_id = c2s.collection_id) WHERE c.collection_id = '" . (int) $collection_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1'";
		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getCollections() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection c LEFT JOIN " . DB_PREFIX . "collection_description cd ON (c.collection_id = cd.collection_id) LEFT JOIN " . DB_PREFIX . "collection_to_store c2s ON (c.collection_id = c2s.collection_id) WHERE cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1' ORDER BY c.sort_order DESC");

		return $query->rows;
	}

	public function getCollectionById($id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "collection c LEFT JOIN " . DB_PREFIX . "collection_description cd ON (c.collection_id = cd.collection_id) LEFT JOIN " . DB_PREFIX . "collection_to_store c2s ON (c.collection_id = c2s.collection_id) WHERE c.collection_id IN (" . $id . ") AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status = '1' AND c.sort_order <> '-1' ORDER BY c.sort_order, LCASE(cd.title) ASC";
		$query = $this->db->query($sql);

		return $query->rows;
	}
    
	public function getCollectionMedias($collection_id, $type = '') {
		$sql = "SELECT * FROM " . DB_PREFIX . "collection_media WHERE collection_id = '" . (int) $collection_id . "'";
		if(isset($type) && $type != ''){
			$sql .= " AND `type` = '" . $type . "'";
		}
		$sql .= " ORDER BY sort_order ASC";
        $query = $this->db->query($sql);

        return $query->rows;
    }

}

?>