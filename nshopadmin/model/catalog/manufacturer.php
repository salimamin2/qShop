<?php

class ModelCatalogManufacturer extends Model {

    public function addManufacturer($data) {
       // d($data,1);
        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "',email = '" . $this->db->escape($data['email']) . "',description = '" . $this->db->escape($data['description']) . "',
            meta_description = '" . $this->db->escape($data['meta_description']) . "',category_id = " . (int) $data['category_id'] . ",country_id = " . (int) $data['country_id'] . ",
            meta_title = '" . $this->db->escape($data['meta_title']) . "', sort_order = '" . (int) $data['sort_order'] . "',facebook = '" . $this->db->escape($data['facebook']) . "',twitter = '" . $this->db->escape($data['twitter']) . "'");

        $manufacturer_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");
        }

        if (isset($data['manufacturer_store'])) {
            foreach ($data['manufacturer_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int) $manufacturer_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `group` = 'manufacturer',`query` = 'manufacturer_id=" . (int) $manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('manufacturer');
    }

    public function editManufacturer($manufacturer_id, $data) {
        //d($data,1);
        $sql = "UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "',email = '" . $this->db->escape($data['email']) . "',description = '" . $this->db->escape($data['description']) . "',meta_description = '" . $this->db->escape($data['meta_description']) . "',
            category_id = " . (int) $data['category_id'] . ",country_id = " . (int) $data['country_id'] . ",meta_title = '" . $this->db->escape($data['meta_title']) . "', sort_order = '" . (int) $data['sort_order'] . "'
            ,facebook = '" . $this->db->escape($data['facebook']) . "',twitter = '" . $this->db->escape($data['twitter']) . "' WHERE manufacturer_id = '" . (int) $manufacturer_id . "'";
        $this->db->query($sql);
        //d(mysql_error(),1);
        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");

        if (isset($data['manufacturer_store'])) {
            foreach ($data['manufacturer_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int) $manufacturer_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int) $manufacturer_id . "'");

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `group` = 'manufacturer',query = 'manufacturer_id=" . (int) $manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('manufacturer');
    }

    public function deleteManufacturer($manufacturer_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int) $manufacturer_id . "'");

        $this->cache->delete('manufacturer');
    }

    public function getManufacturer($manufacturer_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int) $manufacturer_id . "') AS keyword FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");

        return $query->row;
    }

    public function getManufacturers($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";

            $sort_data = array(
                'name',
                'sort_order'
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
            $manufacturer_data = $this->cache->get('manufacturer');

            if (!$manufacturer_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY name");

                $manufacturer_data = $query->rows;

                $this->cache->set('manufacturer', $manufacturer_data);
            }

            return $manufacturer_data;
        }
    }

    public function getManufacturerStores($manufacturer_id) {
        $manufacturer_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");

        foreach ($query->rows as $result) {
            $manufacturer_store_data[] = $result['store_id'];
        }

        return $manufacturer_store_data;
    }

    public function getTotalManufacturersByImageId($image_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer WHERE image_id = '" . (int) $image_id . "'");

        return $query->row['total'];
    }

    public function getTotalManufacturers() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer");

        return $query->row['total'];
    }

    public function getTotalProductsByManufacturerId($manufacturer_id) {
        $count = ORM::for_table('product')
                    ->where('manufacturer_id',$manufacturer_id)
                    ->count();
        return $count;
    }

    public function getCategories() {
        return CHtml::listData(ORM::for_table('manufacturer_categories')->where('is_deleted',0)->find_many(true),'id','name');
    }

}

?>