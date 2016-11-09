<?php

class ModelCatalogInformationLink extends Model {

    public function addLink($data) {
      $sSql = "INSERT INTO " . DB_PREFIX . "information_link
             SET title = '".$this->db->escape($data['title'])."',
                 information_id = '". (int) $data['information_id']."',
                 place_code = '".$this->db->escape($data['place_code'])."',
                 sort_order = '" . (int) $data['sort_order'] . "',
                 link = '" . $this->db->escape($data['link']) . "'";
        $this->db->query($sSql);
         return  $this->db->getLastId();
       /*
        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int) $information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }
         */
    }

    public function editLink($link_id, $data) {
      $sSql = "UPDATE " . DB_PREFIX . "information_link
              SET title = '".$this->db->escape($data['title'])."',
                 information_id = '". (int) $data['information_id']."',
                 place_code = '".$this->db->escape($data['place_code'])."',
                 sort_order = '" . (int) $data['sort_order'] . "',
                 link = '" . $this->db->escape($data['link']) . "'
              WHERE id = '" . (int) $link_id . "'";
        $this->db->query($sSql);
        /*
        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int) $information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }
          */
    }

    public function deleteLink($link_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "information_link WHERE id = '" . (int) $link_id . "'");
    //     $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int) $information_id . "'");
    return mysql_error();
    }

    public function getLink($link_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "information_link WHERE id = '" . (int) $link_id . "'");

        return $query->row;
    }

    public function getLists() {
            $sql = "SELECT * FROM " . DB_PREFIX . "information_link ";

                $sql .= " ORDER BY sort_order";
                $sql .= " ASC";

            $query = $this->db->query($sql);

            return $query->rows;
    }

}

?>