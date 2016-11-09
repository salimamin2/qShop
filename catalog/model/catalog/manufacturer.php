<?php

class ModelCatalogManufacturer extends ARModel {

    public static $_table = 'manufacturer';
    public static $_id_column = 'manufacturer_id';

    public function getManufacturer($manufacturer_id, $category_id = 0) {
    	$aOrm = ORM::for_table('manufacturer')
    				->table_alias('m');
		if($category_id) {
			$aOrm = $aOrm->inner_join('product',array('p.manufacturer_id','=','m.manufacturer_id'),'p')
						 ->inner_join('product_to_category',array('p2c.product_id','=','p.product_id'),'p2c')
						 ->where('p2c.category_id',$category_id);
		}
		return $aOrm->where('m.manufacturer_id',$manufacturer_id)->find_one(null,true);
    }

    public function getManufacturers($category_id = 0) {
	if ($category_id) {
	    $sSql = "SELECT m.* FROM manufacturer m
                            INNER JOIN
                            (SELECT DISTINCT manufacturer_id FROM product p
                                        INNER JOIN " . DB_PREFIX . " product_to_category p2c " . DB_PREFIX . " ON (p.product_id = p2c.product_id)
                                            WHERE category_id ='" . (int) $category_id . "') as m2 ON m.manufacturer_id = m2.manufacturer_id
                            ORDER BY sort_order, LCASE(m.name) ASC";

	    $query = QS::app()->db->query($sSql);
	    return $query->rows;
	} else {
	    $query = QS::app()->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m
                                        ORDER BY sort_order, LCASE(m.name) ASC");

	    $manufacturer = $query->rows;

	    return $manufacturer;
	}
    }

    public function getAllManufacturers($category_id) {
    	$aOrm = ORM::for_table('manufacturer');
    	if($category_id)
    		$aOrm = $aOrm->where('category_id',$category_id);
    	return $aOrm->order_by_asc('sort_order')->order_by_asc('name')->find_many(true);
    }

    public function getManufacturerByProductId($product_id) {
    	return ORM::for_table('manufacturer')
    				->table_alias('m')
    				->select_expr('m.*,c.name AS country')
    				->inner_join('product',array('p.manufacturer_id','=','m.manufacturer_id'),'p')
    				->left_outer_join('country',array('c.country_id','=','m.country_id'),'c')
    				->where('p.product_id',$product_id)
    				->where('p.status',1)
    				->find_one(null,true);
    }

    public function getCount() {
        return ORM::for_table('manufacturer')->where('is_deleted',0)->count();
    }

    public function getTotalProductsByManufacturerId($manufacturer_id) {
      	return ORM::for_table('manufacturer')
        ->table_alias('m')
        ->inner_join('product',array('p.manufacturer_id','=','m.manufacturer_id'),'p')
        ->where('m.manufacturer_id',$manufacturer_id)
        ->where_gt('p.quantity', 0)
        ->where_lte('p.date_available', date('Y-m-d'))
        ->where('p.status', 1)
        ->count();    
	}
}

?>