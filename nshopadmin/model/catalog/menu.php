<?php

class ModelCatalogMenu extends Model {

    public function addMenu($data) {
	$sSql = "INSERT INTO " . DB_PREFIX . "menu SET parent_id = '" . (int) $data['parent_id'] . "', link = '" . $this->db->escape($data['link']) . "', link_type = '" . $this->db->escape($data['link_type']) . "', place_code = '" . $this->db->escape($data['place_code']) . "', sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', batch = '". $data['batch'] . "', static_block = '" . $this->db->escape($data['static_block']) . "', date_modified = NOW(), date_added = NOW()";
	$this->db->query($sSql);
	$menu_id = $this->db->getLastId();
	if (!$menu_id) {
	    return false;
	}

	$s = "SELECT `level`+1 AS level FROM " . DB_PREFIX . "menu WHERE menu_id = '" . $data['parent_id'] . "'";
	$level = $this->db->query($s);
	$sql = "UPDATE " . DB_PREFIX . "menu SET level = '" . (int) $level->row['level'] . "' WHERE menu_id = '" . (int) $menu_id . "'";
	$this->db->query($sql);

	foreach ($data['menu_description'] as $language_id => $value) {
	    $sSql = "INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int) $menu_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_link = '" . $this->db->escape($value['meta_link']) ."', attributes = '" . $this->db->escape($value['attributes']) . "'";
	    $this->db->query($sSql);
	}

	$this->cache->delete('menu');
    }

    public function editMenu($menu_id, $data) {
	$s = "SELECT `level`+1 AS level FROM " . DB_PREFIX . "menu WHERE menu_id = '" . $data['parent_id'] . "'";
	$level = $this->db->query($s);

	$this->updateChildLevel($menu_id, $level->row['level']);

	$sql = "UPDATE " . DB_PREFIX . "menu SET parent_id = '" . (int) $data['parent_id'] . "', level = '" . (int) $level->row['level'] . "', link = '" . $this->db->escape($data['link']) . "', link_type = '" . $this->db->escape($data['link_type']) . "', place_code = '" . $this->db->escape($data['place_code']) . "', sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', batch = '". $data['batch'] ."', static_block = '" . $this->db->escape($data['static_block']) . "', date_modified = NOW() WHERE menu_id = '" . (int) $menu_id . "'";
	$this->db->query($sql);

	$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int) $menu_id . "'");
	foreach ($data['menu_description'] as $language_id => $value) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int) $menu_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "',meta_link = '" . $this->db->escape($value['meta_link']) ."', attributes = '" . $this->db->escape($value['attributes']) . "'");
	}

	$this->cache->delete('menu');
    }

    public function deleteMenu($menu_id) {
	$this->db->query("DELETE FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int) $menu_id . "'");
	$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int) $menu_id . "'");

	$query = $this->db->query("SELECT menu_id FROM " . DB_PREFIX . "menu WHERE parent_id = '" . (int) $menu_id . "'");

	foreach ($query->rows as $result) {
	    $this->deleteMenu($result['menu_id']);
	}

	$this->cache->delete('menu');
    }

    public function getMenu($menu_id) {
	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int) $menu_id . "'");

	return $query->row;
    }

    protected function updateChildLevel($menu_id, $level) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu WHERE parent_id = '" . $menu_id . "'");
	$childrens = $query->rows;

	if ($childrens) {
	    foreach ($childrens as $child) {
		$this->updateChildLevel($child['menu_id'], $child['level']);
		$this->db->query("UPDATE " . DB_PREFIX . "menu SET level = '" . (int) ($level + 1) . "' WHERE menu_id = " . $child['menu_id']);
	    }
	}/* else {
	  $this->db->query("UPDATE " . DB_PREFIX . "menu SET level = '".(int)($level-1)."' WHERE menu_id = ".$menu_id);
	  } */
    }

    public function getMenus($parent_id, $data) {
//        if($data){
//            $this->cache->delete('menu');
//        }
//        $menu_data = $this->cache->get('menu.' . $this->config->get('config_language_id') . '.' . $parent_id);
//
//        if (!$menu_data) {
	$menu_data = array();

	$sql = "SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.parent_id = '" . (int) $parent_id . "' AND md.language_id = '" . (int) $this->config->get('config_language_id') . "'";
	if (isset($data['filter_place_code']) && !is_null($data['filter_place_code'])) {
	    $sql .= " AND m.place_code = '" . $this->db->escape($data['filter_place_code']) . "'";
	}
	$sql .= " ORDER BY m.place_code, m.sort_order, md.name ASC";
	$query = $this->db->query($sql);

	foreach ($query->rows as $result) {
	    $menu_data[] = array(
		'menu_id' => $result['menu_id'],
		'parent_id' => $result['parent_id'],
		'level' => $result['level'],
		'name' => $this->getPath($result['menu_id'], $this->config->get('config_language_id')),
		'link' => $result['link'],
		'place_code' => $result['place_code'],
		'attributes' => $result['attributes'],
		'status' => $result['status'],
		'sort_order' => $result['sort_order']
	    );

	    $menu_data = array_merge($menu_data, $this->getMenus($result['menu_id']));
	}

//            $this->cache->set('menu.' . $this->config->get('config_language_id') . '.' . $parent_id, $menu_data);
//        }

	return $menu_data;
    }

    public function getPlaceCodes() {
//        if($data){
//            $this->cache->delete('menu');
//        }
//        $menu_data = $this->cache->get('menu.' . $this->config->get('config_language_id') . '.' . $parent_id);
//
//        if (!$menu_data) {
	$menu_data = array();

	$sql = "SELECT * FROM " . DB_PREFIX . "menu m  GROUP BY m.place_code";
	$sql .= " ORDER BY m.place_code";
	$query = $this->db->query($sql);

//            $this->cache->set('menu.' . $this->config->get('config_language_id') . '.' . $parent_id, $menu_data);
//        }

	return $query->rows;
    }

    public function getPath($menu_id) {
	$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_id = '" . (int) $menu_id . "' AND md.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY m.sort_order, md.name ASC");

	$menu_info = $query->row;

	if ($menu_info['parent_id']) {
	    return $this->getPath($menu_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $menu_info['name'];
	} else {
	    return $menu_info['name'];
	}
    }

    public function getMenuDescriptions($menu_id) {
	$menu_description_data = array();

	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int) $menu_id . "'");

	foreach ($query->rows as $result) {
	    $menu_description_data[$result['language_id']] = array(
		'name' => $result['name'],
		'attributes' => $result['attributes'],
        'meta_link' => $result['meta_link']
	    );
	}

	return $menu_description_data;
    }

    public function getTotalMenus() {
	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "menu");

	return $query->row['total'];
    }

}

?>