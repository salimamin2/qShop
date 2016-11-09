<?php

class ModelCatalogInformation extends Model {

    public function getInformation($information_id) {
	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.information_id = '" . (int) $information_id . "' AND id.language_id = '" . (int) $this->config->get('config_language_id') . "' AND i.status = '1'");

	return $query->row;
    }

    public function getInformations() {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "' AND i2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND i.status = '1' ORDER BY i.sort_order, LCASE(id.title) ASC");

	return $query->rows;
    }

    public function getInformationList($start, $limit) {
	$sSql = "SELECT SQL_CALC_FOUND_ROWS  * FROM information i "
		. "LEFT JOIN information_description id ON (i.information_id = id.information_id) "
		. "WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "' "
		. " AND i.status = '1' "
		. " ORDER BY i.sort_order, LCASE(id.title) ASC"
		. " LIMIT " . $start . ", " . $limit;
	$query = $this->db->query($sSql);
	
	$sql = "SELECT FOUND_ROWS() total";
	$iQuery = $this->db->query($sql);
	return array($query->rows, $iQuery->row['total']);
    }

    public function getInformationById($id) {
	$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE i.information_id IN (" . $id . ") AND id.language_id = '" . (int) $this->config->get('config_language_id') . "' AND i2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND i.status = '1' AND i.sort_order <> '-1' ORDER BY i.sort_order, LCASE(id.title) ASC";
	$query = $this->db->query($sql);

	return $query->rows;
    }

    public function getInformationLink($code) {
	$sql = "SELECT * FROM " . DB_PREFIX . "information_link
                WHERE place_code = '" . $code . "'
                ORDER BY sort_order";
	$query = $this->db->query($sql);

	return $query->rows;
    }
    public function getManufacturerDetails($manufacturer_id){
        $sql="SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id='".(int)$manufacturer_id."'";

        $query = $this->db->query($sql);

        return $query->row;
    }
    public function getProductsByManufacturer($manufacturer_id,$limit,$product_id) {
        $sql="SELECT m.manufacturer_id,pd.name,pd.description,p.* FROM " . DB_PREFIX . "manufacturer m INNER JOIN " . DB_PREFIX . " product p ON m.manufacturer_id=p.manufacturer_id INNER JOIN " . DB_PREFIX . " product_description pd ON pd.product_id=p.product_id WHERE m.manufacturer_id='".(int)$manufacturer_id."' AND p.product_id!='".(int)$product_id."' ORDER BY p.product_id DESC LIMIT ".(int)$limit;

        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getProductsByManufacturerCategory($category_id,$manufacturer_id,$limit,$product_id){

       $sql="SELECT m.manufacturer_id,pd.name,pd.description,p.* FROM " . DB_PREFIX . "manufacturer m INNER JOIN " . DB_PREFIX . " product p ON m.manufacturer_id=p.manufacturer_id INNER JOIN " . DB_PREFIX . " product_description pd ON pd.product_id=p.product_id INNER JOIN " . DB_PREFIX . " product_to_category ptc ON p.`product_id`=ptc.`product_id` WHERE ptc.category_id='".(int)$category_id."' AND m.`manufacturer_id`='".(int)$manufacturer_id."' AND p.product_id != '".(int)$product_id."' ORDER BY p.product_id LIMIT ".(int)$limit."";
        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getProductsByCategory($category_id,$limit) {
        $sql="SELECT pd.name,pd.description,p.* FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . " product_description pd ON pd.product_id=p.product_id INNER JOIN " . DB_PREFIX . " product_to_category ptc ON p.`product_id`=ptc.`product_id` WHERE ptc.category_id='".(int)$category_id."' ORDER BY p.product_id LIMIT ".(int)$limit."";

        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getManufacturerByID($manufacturer_id){
            $sql="SELECT * from " . DB_PREFIX . "manufacturer WHERE manufacturer_id='".(int)$manufacturer_id."'";

        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getRecommendedProducts($customer_id){
        $sql="SELECT op.product_id,op.name,op.price,p.image FROM " . DB_PREFIX . " product p INNER JOIN " . DB_PREFIX . " order_product op ON op.product_id=p.product_id INNER JOIN " . DB_PREFIX . " product_to_category ptc ON ptc.product_id=op.product_id INNER JOIN `" . DB_PREFIX . "order` o ON o.order_id=op.order_id WHERE o.customer_id=".(int)$customer_id." GROUP BY p.`product_id` LIMIT 10";
      // d($sql);
        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getProductSearchResult($keyword){
        $sql="SELECT product_id AS id,`name` as label,`name` as value from product_description where LCASE(`name`) like '%".$this->db->escape(strtolower($keyword))."%'";
        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getCategorySearchResult($keyword){
        $sql="SELECT category_id AS id,`name` as label,`name` as value from category_description where LCASE(`name`) like '%".$this->db->escape(strtolower($keyword))."%'";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getManufacturerSearchResult($keyword){
        $keyword = $this->db->escape(strtolower($keyword));
        $sql="(SELECT p.product_id AS id,pd.`name` as label,'' as value FROM product p 
            INNER JOIN product_description pd ON p.product_id = pd.product_id WHERE p.date_available <= NOW() 
            AND p.status = 1 AND LCASE(pd.`name`) LIKE '%". $keyword ."%' OR LCASE(pd.meta_description) LIKE '%". $keyword ."%' AND pd.language_id = " . $this->config->get('config_language_id') . ")
        UNION (SELECT manufacturer_id AS id,`name` as label,'designer' as value FROM manufacturer WHERE LCASE(`name`) LIKE '%". $keyword ."%')";
        $query = $this->db->query($sql);
        $rows = $query->rows;

        $aCategories = Make::a('catalog/category')
                        ->table_alias('c')
                        ->select_expr('c.category_id')
                        ->inner_join('category_description', array('c.category_id', '=', 'cd.category_id'), 'cd')
                        ->where('c.status',1)
                        ->where('cd.language_id',$this->config->get('config_language_id'))
                        ->where_raw('LCASE(cd.name) LIKE ?',array('%' . $keyword . '%'))
                        ->find_many(true);

        if($aCategories) {
            $oModel = Make::a('catalog/category')->create();
            foreach ($aCategories as $result) {
                $rows[] = array(
                    'id' => $oModel->getPathUrl($result['category_id']),
                    'label' => $oModel->getPath($result['category_id']),
                    'value' => 'category'
                );
            }
        }
        return $rows;
    }
}

?>