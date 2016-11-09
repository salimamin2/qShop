<?php
class ModelSettingSetting extends Model {
	public function getFrontImages($group) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "' ORDER BY setting_id");
                
		foreach ($query->rows as $result) {
			$data[$result['key']] = $result['value'];
		}
		return $data;
	}

    public function getSetting($value,$group) {
        $query = "SELECT * FROM " . DB_PREFIX . "setting WHERE value = '" . $this->db->escape($value) ."' AND `group` LIKE '" . $this->db->escape($group) ."%'";

        $query = $this->db->query($query);
        return $query->rows;
    }
}
?>