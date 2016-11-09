<?php

class ModelCatalogTestimonial extends Model {

    public function getTestimonial($testimonial_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "testimonial t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) WHERE t.testimonial_id = '" . (int) $testimonial_id . "' AND td.language_id = '" . (int) $this->config->get('config_language_id') . "' AND t.status = '1'");
        if ($query)
            return $query->row;
    }

    public function getTestimonials($start = 0, $limit = 20) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "testimonial t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) WHERE td.language_id = '" . (int) $this->config->get('config_language_id') . "' AND t.status = '1' ORDER BY t.sort_order ASC LIMIT " . (int) $start . "," . (int) $limit);
        if (!$this->db->getError())
            return $query->rows;
    }

    public function getTotalTestimonials() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "testimonial");
        if ($query)
            return $query->row['total'];
    }

}

?>