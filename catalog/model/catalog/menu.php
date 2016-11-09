<?php

class ModelCatalogMenu extends Model {

    /* public function getMenuByPlaceCoden($place_code,$level = 0) {
      $sql = "SELECT * FROM " . DB_PREFIX . "menu WHERE place_code = '".$place_code."' AND level=" . $level;
      $query = $this->db->query($sql);
      $menu_data = array();
      foreach($query->rows as $row) {

      }

      } */

    /* public function getMenuByPlaceCode($place_code) {
	$sql = "SELECT MAX(level) AS levels FROM " . DB_PREFIX . "menu WHERE place_code = '" . $place_code . "'";
	$levels = $this->db->query($sql);

	$sql = "SELECT m0.menu_id AS level0";
	if ($levels->row['levels'] > 0) {
	    $sql .= ",";
	    for ($i = 0; $i < $levels->row['levels']; $i++) {
		$k = $i + 1;
		$sql .= " m" . $k . ".menu_id AS level" . $k . ($k == $levels->row['levels'] ? '' : ',');
	    }
	}
	$sql .= " FROM menu AS m0";
	if ($levels->row['levels'] > 0) {
	    for ($i = 0; $i < $levels->row['levels']; $i++) {
		$k = $i + 1;
		$sql .= " LEFT JOIN menu AS m" . $k . " ON m" . $k . ".parent_id = m" . ($k - 1) . ".menu_id AND m" . $k . ".status = '1'";
	    }
	}
	$sql .= " WHERE m0.place_code = '" . $place_code . "' AND m0.parent_id = '0' AND m0.status = '1'";
	$sql .= " ORDER BY m0.sort_order ASC";
	if ($levels->row['levels'] > 0) {
	    $sql .= ",";
	    for ($i = 0; $i < $levels->row['levels']; $i++) {
		$k = $i + 1;
		$sql .= " m" . $k . ".sort_order ASC" . ($k == $levels->row['levels'] ? '' : ',');
	    }
	}

	$query = $this->db->query($sql);

	return array('matrix' => $query->rows, 'levels' => $levels->row['levels']);
    } */

    public function getMenuByPlaceCode($place_code) {
		$sql = "SELECT MAX(level) AS levels FROM " . DB_PREFIX . "menu WHERE place_code = '" . $place_code . "'";
		$levels = $this->db->query($sql);

		$sql = "SELECT m0.menu_id AS level0";
		$level = $levels->row['levels'];
		if ($level > 0) {
		    $sql .= ",";
		    for ($i = 0; $i < $level; $i++) {
				$k = $i + 1;
				$col = "m" . $k . ".menu_id";
				if($k == $level)
					$col = 'CONCAT(m' . $i . '.menu_id,"|",' . $col . ')';
				$sql .= " GROUP_CONCAT(DISTINCT " . $col . " ORDER BY m" . $k . ".menu_id) AS level" . $k . ($k == $levels->row['levels'] ? '' : ',');
		    }
		}
		$sql .= " FROM menu AS m0";
		if ($levels->row['levels'] > 0) {
		    for ($i = 0; $i < $levels->row['levels']; $i++) {
			$k = $i + 1;
			$sql .= " LEFT JOIN menu AS m" . $k . " ON m" . $k . ".parent_id = m" . ($k - 1) . ".menu_id AND m" . $k . ".status = '1'";
		    }
		}
		$sql .= " WHERE m0.place_code = '" . $place_code . "' AND m0.parent_id = '0' AND m0.status = '1'";
		$sql .= " GROUP BY level0";
		$sql .= " ORDER BY m0.sort_order ASC";
		if ($levels->row['levels'] > 0) {
		    $sql .= ",";
		    for ($i = 0; $i < $levels->row['levels']; $i++) {
			$k = $i + 1;
			$sql .= " m" . $k . ".sort_order ASC" . ($k == $levels->row['levels'] ? '' : ',');
		    }
		}
		
		$query = $this->db->query($sql);
		$aMenu = array();
		foreach($query->rows as $row) {
			$array = array();
			$array['level0'] = $row['level0'];
			for($i = 1; $i <= $level;$i++) {
				if($row['level' . $i] != '') {
					$aItems = explode(',',$row['level' . $i]);
					foreach($aItems as $sItem) {
						if(strpos($sItem,'|') === false) {
							$array['level'.$i][$sItem] = array();
						}
						else {
							$parts = explode('|',$sItem);
							$array['level1'][$parts[0]][$parts[1]] = array(); 
						}
					}
				}
			}
			$aMenu[] = $array;
		}
		return array('matrix' => $aMenu, 'levels' => $level);
    }

    public function getMatrix($place_code) {
	$sql = "SELECT MAX(level) AS levels FROM " . DB_PREFIX . "menu WHERE place_code = '" . $place_code . "'";
	$levels = $this->db->query($sql);

	$sql = "SELECT CONCAT(m0.menu_id,'||',m0.link,'||',m0.link_type,'||',m0.sort_order,'||',m0d.name,'||',m0d.attributes,'||',m0.batch,'||',m0d.meta_link,'||',m0.static_block) AS level0";
	if ($levels->row['levels'] > 0) {
	    $sql .= ",";
	    for ($i = 0; $i < $levels->row['levels']; $i++) {
		$k = $i + 1;
		$sql .= " CONCAT(m" . $k . ".menu_id,'||',m" . $k . ".link,'||',m" . $k . ".link_type,'||',m" . $k . ".sort_order,'||',m" . $k . "d.name,'||',m" . $k . "d.attributes,'||', m" . $k . ".batch,'||',m" . $k . "d.meta_link,'||',m" . $k .".static_block) AS level" . $k . ($k == $levels->row['levels'] ? '' : ',');
	    }
	}
	$sql .= " FROM menu AS m0";
	$sql .= " LEFT JOIN menu_description AS m0d ON m0.menu_id = m0d.menu_id AND m0d.language_id = " . (int) $this->config->get('config_language_id');
	if ($levels->row['levels'] > 0) {
	    for ($i = 0; $i < $levels->row['levels']; $i++) {
		$k = $i + 1;
		$sql .= " LEFT JOIN menu AS m" . $k . " ON m" . $k . ".parent_id = m" . ($k - 1) . ".menu_id AND m" . $k . ".status = '1'";
		$sql .= " LEFT JOIN menu_description AS m" . $k . "d ON m" . $k . ".menu_id = m" . $k . "d.menu_id AND m" . $k . "d.language_id = " . (int) $this->config->get('config_language_id');
	    }
	}
	$sql .= " WHERE m0.place_code = '" . $place_code . "' AND m0.parent_id = '0' AND m0.status = '1'";
	$sql .= " ORDER BY m0.sort_order ASC";
	if ($levels->row['levels'] > 0) {
	    $sql .= ",";
	    for ($i = 0; $i < $levels->row['levels']; $i++) {
		$k = $i + 1;
		$sql .= " m" . $k . ".sort_order ASC" . ($k == $levels->row['levels'] ? '' : ',');
	    }
	}
	$query = $this->db->query($sql);

	return array('matrix' => $query->rows, 'levels' => $levels->row['levels']);
    }

    /*    //using in recursive level information
      var $temp_level=0;
      public function getMenuByPlaceCode($place_code) {
      $levels = $this->db->query("SELECT MAX(level) AS levels FROM " . DB_PREFIX . "menu WHERE place_code = '".$place_code."'");

      $sql = "SELECT m0.menu_id AS level0,";
      for($i=0;$i<$levels->row['levels'];$i++){
      $k = $i+1;
      $sql .= " m".$k.".menu_id AS level".$k.($k == $levels->row['levels'] ? '' : ',');
      }
      $sql .= " FROM menu AS m0";
      for($i=0;$i<$levels->row['levels'];$i++){
      $k = $i+1;
      $sql .= " LEFT JOIN menu AS m".$k." ON m".$k.".parent_id = m".($k-1).".menu_id";
      }
      $sql .= " WHERE m0.place_code = '".$place_code."' AND m0.parent_id = '0' ORDER BY m0.sort_order";

      $query = $this->db->query($sql);
      $aArray = array();

      //negetive counter setup to add root tree row
      $j=-1;
      foreach($query->rows as $key => $value){
      //setup root tree
      if(isset($value['level0']) && !isset($aArray[$j][$value['level0']])){
      $aMenu = $this->getMenu($value['level0']);
      $aArray[++$j][$value['level0']]=$aMenu;
      }
      //getting complete child trees of each root tree
      $aArray[$j][$value['level0']]['child'] = $this->getChild($value,$aArray[$j][$value['level0']],1,$levels->row['levels']);
      }
      return $aArray;
      }
      //recursive calling to filled up child tree
      public function getChild($levels,$parent,$level,$max_level){
      if(!isset($levels['level'.$level]))
      {
      return array();
      }
      if(isset($parent['child'][$levels['level'.$level]])){
      $parent['child'][$levels['level'.$level]]['child'] = $this->getChild($levels,$parent['child'][$levels['level'.$level]],$level+1,$max_level);
      return $parent['child'];
      }
      $aMenu = $this->getMenu($levels['level'.$level]);

      $parent['child'][$levels['level'.$level]]=$aMenu;
      if($level==$max_level){
      return $parent['child'];
      } else {
      $parent['child'][$aMenu['menu_id']]['child'] = $this->getChild($levels,$parent['child'][$aMenu['menu_id']],$level+1,$max_level);
      return $parent['child'];
      }
      } */

    public function getMenu($menu_id) {
	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_id = '" . (int) $menu_id . "'");

	return $query->row;
    }

    public function getMenus($parent_id) {
	$menu_data = array();

	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.parent_id = '" . (int) $parent_id . "' AND md.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY m.sort_order, md.name ASC");

	foreach ($query->rows as $result) {
	    $menu_data[] = array(
		'menu_id' => $result['menu_id'],
		'name' => $this->getPath($result['menu_id']),
		'link' => $result['link'],
		'link_type' => $result['link_type'],
		'place_code' => $result['place_code'],
		'attributes' => $result['attributes'],
		'status' => $result['status'],
		'sort_order' => $result['sort_order']
	    );

	    $menu_data = array_merge($menu_data, $this->getMenus($result['menu_id']));
	}
	return $menu_data;
    }

    public function getPath($menu_id) {
	$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_id = '" . (int) $menu_id . "' AND md.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY m.sort_order, md.name ASC");

	$menu_info = $query->row;

	if ($menu_info['parent_id']) {
	    return $this->getPath($menu_info['parent_id']) . $menu_info['name'];
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
		'attributes' => $result['attributes']
	    );
	}

	return $menu_description_data;
    }

}

?>