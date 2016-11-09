<?php

class ModelSettingSeoManagement extends Model {

    public function addUrl($data) {
        $url_query = "SELECT keyword FROM " . DB_PREFIX . " url_alias WHERE keyword='". $data['keyword']."'";
        $aProducts_url=$this->db->query($url_query);

        if($aProducts_url->num_rows > 0){
            $rand_num=rand(0,9);
            $product_id = str_replace('product_id=', '', $data['query']);
            $data['keyword']=$data['keyword'].''.$product_id.$rand_num;
        }
        $sql = "INSERT INTO " . DB_PREFIX . "url_alias SET `group` = '" . $this->db->escape($data['group']) . "', `query` = '" . $this->db->escape($data['query']) . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'";
        $this->db->query($sql);
        $url_id = $this->db->getLastId();

        return $url_id;
    }

    public function editUrl($url_alias_id, $data) {
        $url_query = "SELECT keyword FROM " . DB_PREFIX . " url_alias WHERE keyword='". $data['keyword']."'";
        $aProducts_url=$this->db->query($url_query);

        if($aProducts_url->num_rows > 0){
            $rand_num=rand(0,9);
            $product_id = str_replace('product_id=', '', $data['query']);
            $data['keyword']=$data['keyword'].''.$product_id.$rand_num;
        }
        $sql = "UPDATE " . DB_PREFIX . "url_alias SET `group` = '" . $this->db->escape($data['group']) . "', `query` = '" . $this->db->escape($data['query']) . "', `keyword` = '" . $this->db->escape($data['keyword']) . "' WHERE url_alias_id = " . (int) $url_alias_id;
        $this->db->query($sql);
    }

    public function deleteUrl($url_alias_id) {
        $sql = "DELETE FROM " . DB_PREFIX . "url_alias WHERE `url_alias_id` = '" . $this->db->escape($url_alias_id) . "'";
        $this->db->query($sql);
    }

    public function getUrl($url_alias_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "url_alias WHERE `url_alias_id` = '" . $this->db->escape($url_alias_id) . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getUrls() {
        $sql = "SELECT * FROM " . DB_PREFIX . "url_alias";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getUrlsByGroup($group) {
        $sql = "SELECT * FROM " . DB_PREFIX . "url_alias WHERE `group` = '".$this->db->escape($group)."'";
        /*if($group == 'custom') {
            $sql .= " " ;
        }
        elseif($group == "category") {
            $sql .= "`query` LIKE 'path=%'";
        }
        else{
            $sql .= " `query` LIKE '{$group}_id=%'";
        }*/
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getGroups() {
       // return array('custom','product','information','manufacturer','category');
        $sql = "SELECT DISTINCT `group` FROM " . DB_PREFIX . "url_alias";
       $query = $this->db->query($sql);
        $groups = array();
       foreach($query->rows as $row) {
            $groups[] = $row['group'];
       }
        asort($groups);
        return $groups;
//       return $query->rows;
    }

    public function getTotalUrlById($url_alias_id) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "url_alias WHERE `url_alias_id` = '" . $this->db->escape($url_alias_id) . "'";
        $query = $this->db->query($sql);
        return $query->row['total'];
    }

}

?>