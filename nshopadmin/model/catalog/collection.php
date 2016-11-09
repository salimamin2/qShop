<?php

class ModelCatalogCollection extends Model {

    public function addCollection($data) {
		
        $this->db->query("INSERT INTO " . DB_PREFIX . "collection SET sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', `media_type` = '" . $data['media_type'] . "'");

        $collection_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "collection SET image = '" . $this->db->escape($data['image']) . "' WHERE collection_id = '" . (int) $collection_id . "'");
        }

        foreach ($data['collection_description'] as $language_id => $value) {
          if($value)
            $this->db->query("INSERT INTO " . DB_PREFIX . "collection_description SET collection_id = '" . (int) $collection_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', description = '" . $this->db->escape($value['description']) . "'");
        }

        if (isset($data['collection_store'])) {
            foreach ($data['collection_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "collection_to_store SET collection_id = '" . (int) $collection_id . "', store_id = '" . (int) $store_id . "'");
            }
        }
		
		if (isset($data['collection_media'])) {
            foreach ($data['collection_media'] as $media) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "collection_media SET collection_id = '" . (int) $collection_id . "', media = '" . $this->db->escape($media['media']) . "', `type` = '" . $media['type'] . "', sort_order = '" . (int) $media['sort_order'] . "'");
            }
        }

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'collection_id=" . (int) $collection_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('collection');
    }

    public function editCollection($collection_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "collection SET sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', `media_type` = '" . $data['media_type'] . "' WHERE collection_id = '" . (int) $collection_id . "'");
		
		if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "collection SET image = '" . $this->db->escape($data['image']) . "' WHERE collection_id = '" . (int) $collection_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "collection_description WHERE collection_id = '" . (int) $collection_id . "'");

        foreach ($data['collection_description'] as $language_id => $value) {
          if($value)
            $this->db->query("INSERT INTO " . DB_PREFIX . "collection_description SET collection_id = '" . (int) $collection_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', description = '" . $this->db->escape($value['description']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "collection_to_store WHERE collection_id = '" . (int) $collection_id . "'");

        if (isset($data['collection_store'])) {
            foreach ($data['collection_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "collection_to_store SET collection_id = '" . (int) $collection_id . "', store_id = '" . (int) $store_id . "'");
            }
        }
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "collection_media WHERE collection_id = '" . (int) $collection_id . "'");

		if (isset($data['collection_media'])) {
            foreach ($data['collection_media'] as $media) {
				$sql_media = "INSERT INTO " . DB_PREFIX . "collection_media SET collection_id = '" . (int) $collection_id . "', media = '" . $this->db->escape($media['media']) . "', `type` = '" . $media['type'] . "', sort_order = '" . (int) $media['sort_order'] . "'";
                $this->db->query($sql_media);
            }
        }


        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'collection_id=" . (int) $collection_id . "'");

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'collection_id=" . (int) $collection_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('collection');
    }

    public function deleteCollection($collection_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "collection WHERE collection_id = '" . (int) $collection_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "collection_description WHERE collection_id = '" . (int) $collection_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "collection_to_store WHERE collection_id = '" . (int) $collection_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "collection_media WHERE collection_id = '" . (int) $collection_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'collection_id=" . (int) $collection_id . "'");

        $this->cache->delete('collection');
    }

    public function getCollection($collection_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'collection_id=" . (int) $collection_id . "') AS keyword FROM " . DB_PREFIX . "collection WHERE collection_id = '" . (int) $collection_id . "'");

        return $query->row;
    }

    public function getCollections($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "collection c LEFT JOIN " . DB_PREFIX . "collection_description cd ON (c.collection_id = cd.collection_id) WHERE cd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

            $sort_data = array(
                'cd.title',
                'c.sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY cd.title";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $collection_data = $this->cache->get('collection.' . $this->config->get('config_language_id'));

            if (!$collection_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection c LEFT JOIN " . DB_PREFIX . "collection_description cd ON (c.collection_id = cd.collection_id) WHERE cd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY cd.title");

                $collection_data = $query->rows;

                $this->cache->set('collection.' . $this->config->get('config_language_id'), $collection_data);
            }

            return $collection_data;
        }
    }

    public function getCollectionDescriptions($collection_id) {
        $collection_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_description WHERE collection_id = '" . (int) $collection_id . "'");

        foreach ($query->rows as $result) {
            $collection_description_data[$result['language_id']] = array(
                'title' => $result['title'],
                'meta_title' => $result['meta_title'],
                'description' => $result['description']
            );
        }

        return $collection_description_data;
    }

    public function getCollectionStores($collection_id) {
        $collection_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_to_store WHERE collection_id = '" . (int) $collection_id . "'");

        foreach ($query->rows as $result) {
            $collection_store_data[] = $result['store_id'];
        }

        return $collection_store_data;
    }

    public function getTotalCollections() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "collection");

        return $query->row['total'];
    }
	
	public function getCollectionMedias($collection_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "collection_media WHERE collection_id = '" . (int) $collection_id . "' ORDER BY collection_media_id ASC";
        $query = $this->db->query($sql);

        return $query->rows;
    }

}

?>