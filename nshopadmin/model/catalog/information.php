<?php

class ModelCatalogInformation extends Model {

    public function addInformation($data) {
	$this->db->query("INSERT INTO " . DB_PREFIX . "information SET 
		sort_order = '" . (int) $this->request->post['sort_order'] . "', 
		leftcolumn = '" . (int) $this->request->post['leftcolumn'] . "', 
		status = '" . (int) $data['status'] . "',
		show_title = '" . $data['show_title'] . "',
		show_recommended = '" . $data['show_recommended'] . "'"
	);

	$information_id = $this->db->getLastId();

	foreach ($data['information_description'] as $language_id => $value) {
	    if ($value){
		$sSql = "INSERT INTO " . DB_PREFIX . "information_description SET "
			. "information_id = '" . (int) $information_id . "',"
			. "language_id = '" . (int) $language_id . "',"
			. "title = '" . $this->db->escape($value['title']) . "',"
			. "meta_title = '" . $this->db->escape($value['meta_title']) . "',"
			. "meta_link = '" . $this->db->escape($value['meta_link']) . "',"
			. "meta_keywords = '" . $this->db->escape($value['meta_keywords']) ."',"
			. "meta_description = '" . $this->db->escape($value['meta_description']) . "',"
			. "description = '" . $this->db->escape($value['description']) . "'";
		$this->db->query($sSql);
	    }
	}

	if (isset($data['information_store'])) {
	    foreach ($data['information_store'] as $store_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "information_to_store SET information_id = '" . (int) $information_id . "', store_id = '" . (int) $store_id . "'");
	    }
	}

	if ($data['keyword']) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `group` = 'information', `query` = 'information_id=" . (int) $information_id . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
	}

	$this->cache->delete('information');
    }

    public function editInformation($information_id, $data) {
	$this->db->query("UPDATE " . DB_PREFIX . "information SET 
		sort_order = '" . (int) $data['sort_order'] . "', 
		leftcolumn = '" . (int) $data['leftcolumn'] . "', 
		status = '" . (int) $data['status'] . "',
		show_title = '" . $data['show_title'] . "',
		show_recommended = '" . $data['show_recommended'] . "' WHERE information_id = '" . (int) $information_id . "'");

	$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int) $information_id . "'");

	foreach ($data['information_description'] as $language_id => $value) {
	    if ($value) {
//            $this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int) $information_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		$sSql = "INSERT INTO information_description SET "
			. "information_id = '" . (int) $information_id . "',"
			. "language_id = '" . (int) $language_id . "',"
			. "title = '" . $this->db->escape($value['title']) . "',"
			. "meta_title = '" . $this->db->escape($value['meta_title']) . "',"
			. "meta_link = '" . $this->db->escape($value['meta_link']) . "',"
			. "meta_keywords = '" . $this->db->escape($value['meta_keywords']) . "',"
			. "meta_description = '" . $this->db->escape($value['meta_description']) . "',"
			. "description = '" . $this->db->escape($value['description']) . "'";

		$this->db->query($sSql);
	    }
	}

	$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int) $information_id . "'");

	if (isset($data['information_store'])) {
	    foreach ($data['information_store'] as $store_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "information_to_store SET information_id = '" . (int) $information_id . "', store_id = '" . (int) $store_id . "'");
	    }
	}

	$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'information_id=" . (int) $information_id . "'");

	if ($data['keyword']) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `group` = 'information', `query` = 'information_id=" . (int) $information_id . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
	}

	$this->cache->delete('information');
    }

    public function deleteInformation($information_id) {
	$this->db->query("DELETE FROM " . DB_PREFIX . "information WHERE information_id = '" . (int) $information_id . "'");
	$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int) $information_id . "'");
	$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int) $information_id . "'");
	$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int) $information_id . "'");

	$this->cache->delete('information');
    }

    public function getInformation($information_id) {
	$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int) $information_id . "') AS keyword FROM " . DB_PREFIX . "information WHERE information_id = '" . (int) $information_id . "'");

	return $query->row;
    }

    public function getInformations() {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY id.title");
        return $query->rows;
    }

    public function getInformationDescriptions($information_id) {
	$information_description_data = array();

	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int) $information_id . "'");

	foreach ($query->rows as $result) {
	    $information_description_data[$result['language_id']] = array(
		'title' => $result['title'],
		'meta_title' => $result['meta_title'],
		'meta_link' => $result['meta_link'],
		'meta_keywords' => $result['meta_keywords'],
		'meta_description' => $result['meta_description'],
		'description' => $result['description']
	    );
	}

	return $information_description_data;
    }

    public function getInformationStores($information_id) {
	$information_store_data = array();

	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int) $information_id . "'");

	foreach ($query->rows as $result) {
	    $information_store_data[] = $result['store_id'];
	}

	return $information_store_data;
    }

    public function getTotalInformations() {
	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information");

	return $query->row['total'];
    }

    public function deleteModuleKnow($key,$key_id,$data,$page){

        if (($key = array_search($key, $data)) !== false) {
            unset($data[$key]);
        }


        if (($key_id = array_search($key_id, $page)) !== false) {
            unset($page[$key_id]);
        }

        $aData['know_comments'] = serialize($data);
        $aData['know_information_page'] = serialize($page);

        Make::a('setting/setting')->create()->editSetting('did_you_know', $aData);
    }

}

?>