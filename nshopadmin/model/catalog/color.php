<?php

class ModelCatalogColor extends Model {

    public function addColor($data) {
        $sql = "INSERT INTO color SET name = '" . $data['name'] . "', hex_code = '" . $data['hex_code'] . "'";
        $this->db->query($sql);
        $this->cache->delete('color');
    }

    public function editColor($color_id, $data) {
        $sql = "UPDATE color SET name = '" . $data['name'] . "', hex_code = '" . $data['hex_code'] . "' WHERE color_id = '" . $color_id . "'";
        $this->db->query($sql);
        $this->cache->delete('color');
    }

    public function editColorCode($color_id, $hex_code) {
        $sql = "UPDATE color SET hex_code = '" . $hex_code . "' WHERE color_id = '" . $color_id . "'";
        d($sql);
        $this->db->query($sql);
        $this->cache->delete('color');
    }

    public function deleteColor($color_id) {
        $sql = "DELETE FROM color WHERE color_id = '" . $color_id . "'";
        $this->db->query($sql);
        $this->cache->delete('color');
    }

    public function getcolor($color_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'color_id=" . (int) $color_id . "') AS keyword FROM " . DB_PREFIX . "color WHERE color_id = '" . (int) $color_id . "'");

        return $query->row;
    }

    public function getcolors($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "color WHERE TRUE";

            if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
                $sql .= " AND LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
            }
            
            if (isset($data['filter_hex_code']) && !is_null($data['filter_hex_code'])) {
                $sql .= " AND LCASE(hex_code) LIKE '%" . $this->db->escape(strtolower($data['filter_hex_code'])) . "%'";
            }
            
            $sort_data = array(
                'name',
                'hex_code',
                'status'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY name";
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
            $color_data = $this->cache->get('color');

            if (!$color_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "color ORDER BY name");

                $color_data = $query->rows;

                $this->cache->set('color', $color_data);
            }

            return $color_data;
        }
    }

    public function getColorStores($color_id) {
        $color_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "color_to_store WHERE color_id = '" . (int) $color_id . "'");

        foreach ($query->rows as $result) {
            $color_store_data[] = $result['store_id'];
        }

        return $color_store_data;
    }

    public function getTotalColorsByImageId($image_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "color WHERE image_id = '" . (int) $image_id . "'");

        return $query->row['total'];
    }

    public function getTotalColors($data) {
        $sql = "SELECT count(*) as total FROM " . DB_PREFIX . "color WHERE TRUE";

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= " AND LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
        }

        if (isset($data['filter_hex_code']) && !is_null($data['filter_hex_code'])) {
            $sql .= " AND LCASE(hex_code) LIKE '%" . $this->db->escape(strtolower($data['filter_hex_code'])) . "%'";
        }
        
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

}

?>