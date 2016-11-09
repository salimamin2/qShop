<?php

/*
 * NOTICE OF LICENSE
 * 
 *  This source file is subject to the Open Software License (OSL 3.0)
 *  that is bundled with this package in the file LICENSE.txt.
 *  It is also available through the world-wide-web at this URL:
 *  http://opensource.org/licenses/osl-3.0.php
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to license@q-sols.com so we can send you a copy immediately.
 * 
 * 
 *  @copyright   Copyright (c) 2010 Q-Solutions. (www.q-sols.com)
 *  @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<?php

class ModelCatalogProductType extends Model {

    public function add($data) {
        if (!$data) {
            return false;
        }
        $sql = "INSERT INTO product_type SET title = '" . $data['title'] . "',status = '" . $data['status']."'";

       
        $this->db->query($sql);
        $product_type_id = $this->db->getLastId();
        
        if(!$product_type_id){
            return false;
        }
        foreach($data['product_type_option'] as $key => $value) {
            if($key == "new" && $value['name']) {
                $sql .= "SELECT *";
                $sql .= " FROM `product_type_option` pto";
                $sql .= " INNER JOIN `product_type_option_description` ptod ON pto.product_type_option_id = ptod.product_type_option_id";
                $sql .= " WHERE pto.product_type_id ='".$product_type_id."'";
                $sql .= " AND ptod.name = '".$value['name']."'";
                $query = $this->db->query($sql);
                if($query->num_rows) {
//                    $product_type_option_id = $query->row['product_type_option_id'];
//                    $sql = "UPDATE `product_type_option` SET product_type_id = '" . $product_type_id . "', sort_order = '" . $value['sort_order'] . "' WHERE product_type_option_id = '" . $product_type_option_id . "'";
//                    $this->db->query($sql);
//                    $sql = "UPDATE `product_type_option_description` SET product_type_id = '" . $product_type_id . "', name = '" . $value['name'] . "' WHERE product_type_option_id = '" . $product_type_option_id . "'";
//                    $this->db->query($sql);
                } else {
                    $sql = "INSERT INTO `product_type_option` SET product_type_id = '" . $product_type_id . "', sort_order = '" . $value['sort_order'] . "'";
                    $this->db->query($sql);
                    $product_type_option_id = $this->db->getLastId();
                    $sql = "INSERT INTO `product_type_option_description` SET product_type_option_id = '" . $product_type_option_id . "', product_type_id = '" . $product_type_id . "', language_id = '" . $value['language_id'] . "', name = '" . $value['name'] . "'";
                    $this->db->query($sql);
                }
            } else {
                $product_type_option_id = $key;
                $sql = "UPDATE `product_type_option` SET product_type_id = '" . $product_type_id . "', sort_order = '" . $value['sort_order'] . "' WHERE product_type_option_id = '" . $product_type_option_id . "'";
                $this->db->query($sql);
                $sql = "UPDATE `product_type_option_description` SET product_type_id = '" . $product_type_id . "', name = '" . $value['name'] . "' WHERE product_type_option_id = '" . $product_type_option_id . "'";
                $this->db->query($sql);
            }
        }
        
        foreach($data['product_type_option_value'] as $key => $value) {
            if($key == "new" && $value['name']) {
                $sql = "INSERT INTO `product_type_option_value` SET product_type_id = '" . $product_type_id . "', product_type_option_id = '" . $data['product_type_option_id'] . "', sort_order = '" . $value['sort_order'] . "'";
                $this->db->query($sql);
                $product_type_option_value_id = $this->db->getLastId();
                $sql = "INSERT INTO `product_type_option_value_description` SET product_type_option_value_id = '" . $product_type_option_value_id . "', product_type_id = '" . $product_type_id . "', language_id = '" . $value['language_id'] . "', name = '" . $value['name'] . "'";
                $this->db->query($sql);
            } else {
                $product_type_option_value_id = $key;
                $sql = "UPDATE `product_type_option_value` SET product_type_id = '" . $product_type_id . "', product_type_option_id = '" . $data['product_type_option_id'] . "', sort_order = '" . $value['sort_order'] . "' WHERE product_type_option_value_id = '" . $product_type_option_value_id . "'";
                $this->db->query($sql);
                $sql = "UPDATE `product_type_option_value_description` SET product_type_id = '" . $product_type_id . "', language_id = '" . $value['language_id'] . "', name = '" . $value['name'] . "' WHERE product_type_option_value_id = '" . $product_type_option_value_id . "'";
                $this->db->query($sql);
            }
        }
        return $product_type_id;
    }

    public function edit($id, $data) {
        if (!$id || !$data) {
            return false;
        }
        $product_type_id = $id;
//        d(array($product_type_id,$data),true);
        if($data['title'] != '') {
            $this->db->query('UPDATE ' . DB_PREFIX . 'product_type SET title = \'' . $data['title'] . '\',status = \' ' . $data['status'] . '\' WHERE product_type_id = ' . $id);
        }
        foreach($data['product_type_option'] as $key => $value) {
            if($key == "new" && $value['name']) {
                $sql .= "SELECT *";
                $sql .= " FROM `product_type_option` pto";
                $sql .= " INNER JOIN `product_type_option_description` ptod ON pto.product_type_option_id = ptod.product_type_option_id";
                $sql .= " WHERE pto.product_type_id ='".$product_type_id."'";
                $sql .= " AND ptod.name = '".$value['name']."'";
                $query = $this->db->query($sql);
                if($query->num_rows) {
//                    $product_type_option_id = $query->row['product_type_option_id'];
//                    $sql = "UPDATE `product_type_option` SET product_type_id = '" . $product_type_id . "', sort_order = '" . $value['sort_order'] . "' WHERE product_type_option_id = '" . $product_type_option_id . "'";
//                    $this->db->query($sql);
//                    $sql = "UPDATE `product_type_option_description` SET product_type_id = '" . $product_type_id . "', name = '" . $value['name'] . "' WHERE product_type_option_id = '" . $product_type_option_id . "'";
//                    $this->db->query($sql);
                } else {
                    $sql = "INSERT INTO `product_type_option` SET product_type_id = '" . $product_type_id . "', sort_order = '" . $value['sort_order'] . "'";
                    $this->db->query($sql);
                    $product_type_option_id = $this->db->getLastId();
                    $sql = "INSERT INTO `product_type_option_description` SET product_type_option_id = '" . $product_type_option_id . "', product_type_id = '" . $product_type_id . "', language_id = '" . $value['language_id'] . "', name = '" . $value['name'] . "'";
                    $this->db->query($sql);
                }
            } else {
                $product_type_option_id = $key;
                $sql = "UPDATE `product_type_option` SET product_type_id = '" . $product_type_id . "', sort_order = '" . $value['sort_order'] . "' WHERE product_type_option_id = '" . $product_type_option_id . "'";
                $this->db->query($sql);
                $sql = "UPDATE `product_type_option_description` SET product_type_id = '" . $product_type_id . "', name = '" . $value['name'] . "' WHERE product_type_option_id = '" . $product_type_option_id . "'";
                $this->db->query($sql);
            }
        }
        
        foreach($data['product_type_option_value'] as $key => $value) {
            if($key == "new" && $value['name']) {
                $sql = "INSERT INTO `product_type_option_value` SET product_type_id = '" . $product_type_id . "', product_type_option_id = '" . $data['product_type_option_id'] . "', sort_order = '" . $value['sort_order'] . "'";
                $this->db->query($sql);
                $product_type_option_value_id = $this->db->getLastId();
                $sql = "INSERT INTO `product_type_option_value_description` SET product_type_option_value_id = '" . $product_type_option_value_id . "', product_type_id = '" . $product_type_id . "', language_id = '" . $value['language_id'] . "', name = '" . $value['name'] . "'";
                $this->db->query($sql);
            } else {
                $product_type_option_value_id = $key;
                $sql = "UPDATE `product_type_option_value` SET product_type_id = '" . $product_type_id . "', product_type_option_id = '" . $data['product_type_option_id'] . "', sort_order = '" . $value['sort_order'] . "' WHERE product_type_option_value_id = '" . $product_type_option_value_id . "'";
                $this->db->query($sql);
                $sql = "UPDATE `product_type_option_value_description` SET product_type_id = '" . $product_type_id . "', language_id = '" . $value['language_id'] . "', name = '" . $value['name'] . "' WHERE product_type_option_value_id = '" . $product_type_option_value_id . "'";
                $this->db->query($sql);
            }
        }
    }

    public function delete($id) {
        if (!$id) {
            return false;
        }
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'product WHERE produc_type_id = ' . $id;
        $result = $this->db->query($sql);
        if ($result && $result->num_rows > 0) {
            return false;
        }
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_type WHERE product_type_id = '" . (int) $id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_type_option WHERE product_type_id = '" . (int) $id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_type_option_description WHERE product_type_id = '" . (int) $id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_type_option_value WHERE product_type_id = '" . (int) $id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_type_option_value_description WHERE product_type_id = '" . (int) $id . "'");
        if (!$this->db->getError())
            return true;
        else {
            return $this->db->getError();
        }
    }

    public function get($id) {
        $query = $this->db->query("SELECT *  FROM " . DB_PREFIX . 'product_type WHERE product_type_id = ' . (int) $id);
        return $query->row;
    }

    public function getList($data = array()) {
        $sql = 'SELECT *, (select count(*) from `product` WHERE product.product_type_id = product_type.product_type_id) as total FROM ' . DB_PREFIX . 'product_type WHERE status = 1';
        $sort_data = array(
            'title',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY title";
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

        $result = $this->db->query($sql);
        if (!$result || $result->num_rows == 0) {
            return array();
        }
        return $result->rows;
    }

    public function getOptions($id,$language_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'product_type_option po LEFT JOIN
                    ' . DB_PREFIX . 'product_type_option_description pod ON pod.product_type_option_id = po.product_type_option_id AND pod.language_id = ' . (int) $language_id.'
                        WHERE po.product_type_id=' . (int) $id;
        $sql .= ' ORDER BY sort_order';

        $result = $this->db->query($sql);

        if (!$result || $result->num_rows == 0) {
            return false;
        }
        $aOptions = $result->rows;
        foreach ($aOptions as &$row) {
            $sql = 'SELECT * FROM ' . DB_PREFIX . 'product_type_option_value ptv LEFT JOIN
                       ' . DB_PREFIX . 'product_type_option_value_description ptvod ON ptvod.product_type_option_value_id = ptv.product_type_option_value_id AND ptvod.language_id = ' . (int) $language_id.'
                        WHERE product_type_option_id = ' . (int) $row['product_type_option_id'];
            $sql .= ' ORDER BY sort_order, name';
            $iResult = $this->db->query($sql);
            if ($iResult && $iResult->num_rows > 0) {
                $row['values'] = $iResult->rows;
            }
        }

        return $aOptions;
    }
    
    public function getProductTypeOption($product_type_id){
        if(!$product_type_id){
            return array();
        }

        $product_type_option_data = array();

        $product_type_option = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_type_option WHERE product_type_id = '" . (int) $product_type_id . "' ORDER BY sort_order");

        foreach ($product_type_option->rows as $product_type_option) {
            $product_type_option_value_data = array();

            $product_type_option_value = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_type_option_value WHERE product_type_option_id = '" . (int) $product_type_option['product_type_option_id'] . "' ORDER BY sort_order");

            foreach ($product_type_option_value->rows as $product_type_option_value) {
                $product_type_option_value_description_data = array();

                $product_type_option_value_description = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_type_option_value_description WHERE product_type_option_value_id = '" . (int) $product_type_option_value['product_type_option_value_id'] . "'");

                foreach ($product_type_option_value_description->rows as $result) {
                    $product_type_option_value_description_data[$result['language_id']] = array('name' => $result['name']);
                }

                $product_type_option_value_data[] = array(
                    'product_type_option_value_id' => $product_type_option_value['product_type_option_value_id'],
                    'language' => $product_type_option_value_description_data,
                    'sort_order' => $product_type_option_value['sort_order']
                );
            }

            $product_type_option_description_data = array();

            $product_type_option_description = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_type_option_description WHERE product_type_option_id = '" . (int) $product_type_option['product_type_option_id'] . "'");

            foreach ($product_type_option_description->rows as $result) {
                $product_type_option_description_data[$result['language_id']] = array('name' => $result['name']);
            }

            $product_type_option_data[] = array(
                'product_type_option_id' => $product_type_option['product_type_option_id'],
                'language' => $product_type_option_description_data,
                'product_type_option_value' => $product_type_option_value_data,
                'sort_order' => $product_type_option['sort_order']
            );
        }

        return $product_type_option_data;
    }
    
    public function getProductTypeOptions($product_type_id) {
        $sql = "SELECT pto.product_type_option_id, ptod.language_id, l.name AS lang, ptod.name, pto.sort_order";
        $sql .= " FROM `product_type_option` pto";
        $sql .= " INNER JOIN `product_type_option_description` ptod ON pto.product_type_option_id = ptod.product_type_option_id";
        $sql .= " INNER JOIN `language` l ON l.language_id = ptod.language_id";
        $sql .= " WHERE pto.product_type_id = '" .$product_type_id. "'";
        $sql .= " ORDER BY sort_order, name";
        
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getProductTypeOptionValues($product_type_option_id) {
        $sql ="select ptov.product_type_option_value_id, ptov.sort_order, ptovd.language_id, l.name as lang, ptovd.name";
        $sql .= " from `product_type_option_value` ptov";
        $sql .= " inner join `product_type_option_value_description` ptovd on ptovd.product_type_option_value_id = ptov.product_type_option_value_id";
        $sql .= " inner join `language` l on l.language_id = ptovd.language_id";
        $sql .= " where ptov.product_type_option_id =" . $product_type_option_id;
        $sql .= " ORDER BY sort_order, name";
        
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function removeProductTypeOption($product_type_id, $product_type_option_id) {
        $sql  = "select *";
        $sql .= " from `product_type_option` pto";
        $sql .= " inner join `product_type_option_description` ptod on pto.product_type_option_id = ptod.product_type_option_id";
        $sql .= " inner join `product_type_option_value` ptov on ptov.product_type_option_id = pto.product_type_option_id";
        $sql .= " INNER JOIN `product_type_option_value_description` ptovd ON ptovd.product_type_option_value_id = ptov.product_type_option_value_id";
        $sql .= " where pto.product_type_id ='" .$product_type_id. "'";
        $sql .= " AND pto.product_type_option_id = '" . $product_type_option_id . "'";
        
        $query = $this->db->query($sql);
        if($query->num_rows) {
            $status['warning'] = 'Delete option values before deleting option';
            return $status;
        } else {
            $sql = "DELETE FROM `product_type_option` WHERE product_type_option_id = '" . $product_type_option_id . "'";
            $this->db->query($sql);
            $sql = "DELETE FROM `product_type_option_description` WHERE product_type_option_id = '" . $product_type_option_id . "'";
            $this->db->query($sql);
            $status['success'] = 'Option deleted successfully';
            return $status;
        }
    }
    
    public function removeProductTypeOptionValue($product_type_option_value_id) {
        $sql = "select *";
        $sql .= " from `product_detail_description` pdd";
        $sql .= " where pdd.product_type_option_value_id = " . $product_type_option_value_id;
        $query = $this->db->query($sql);
        if($query->num_rows) {
            $status['warning'] = 'Option value already used in Products, cannot delete';
            return $status;
        } else {
            $sql = "DELETE FROM `product_type_option_value` WHERE product_type_option_value_id = '" . $product_type_option_value_id . "'";
            $this->db->query($sql);
            $sql = "DELETE FROM `product_type_option_value_description` WHERE product_type_option_value_id = '" . $product_type_option_value_id . "'";
            $this->db->query($sql);
            $status['success'] = 'Option value deleted successfully';
            return $status;
        }
    }
    
    public function validate_delete($id) {
        if (!$id) {
            return false;
        }
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'product WHERE produc_type_id = ' . $id;
        $result = $this->db->query($sql);
        if ($result && $result->num_rows > 0) {
            return false;
        } else {
            return true;
        }
    }

}

?>