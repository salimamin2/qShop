<?php
class ModelCheckoutExtension extends Model {
    function getExtensions($type) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

        return $query->rows;
    }
    function validateCustomer($customer_group_id, $type, $key) {
        $sql = "SELECT c.customer_group_ids,e.extension_id FROM " . DB_PREFIX . "customer_group_link c LEFT JOIN " . DB_PREFIX . "extension e ON c.extension_id = e.extension_id WHERE `type` = '" . $this->db->escape($type) . "' AND `key` = '" . $this->db->escape($key) . "'";
        $query = $this->db->query($sql);

        if($query->num_rows > 0) {
            $customer_gorup = explode(',',$query->row['customer_group_ids']);
            if(in_array($customer_group_id,$customer_gorup)) {
                return true;

            }
        }

        return false;
    }
    function getExtensionsByPosition($type, $position) {
        $module_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

        foreach ($query->rows as $result) {
            if ($this->config->get($result['key'] . '_status') && ($this->config->get($result['key'] . '_position') == $position)) {
                $module_data[] = array(
                        'code'       => $result['key'],
                        'sort_order' => $this->config->get($result['key'] . '_sort_order')
                );
            }
        }
        $sort_order = array();

        foreach ($module_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $module_data);

        return $module_data;

    }
}

?>