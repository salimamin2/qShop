<?php

class ModelCatalogKnow extends Model {



    public function getKnow($start = 0, $limit = 20) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) WHERE td.language_id = '" . (int) $this->config->get('config_language_id') . "' AND t.status = '1' ORDER BY t.sort_order ASC LIMIT " . (int) $start . "," . (int) $limit);
        if (!$this->db->getError())
            return $query->rows;
    }


}

?>