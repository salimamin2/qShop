<?php



class ModelCatalogCategory extends ARModel {



    public static $_table = 'category';

    public static $_id_column = 'category_id';

    public $reg;

    //fields

    protected $_fields = array(

	'category_id',

	'image',

	'parent_id',

	'sort_order',

	'date_added',

	'date_modified',

	'status',

	'category_code',

	'ref_category_code'

    );



    //validation rules

    /*   protected $_rules = array(



      )));

     */

    public function init() {

	//setting up default values

	$this->reg = Registry::getInstance();

	parent::init();

    }



    private $aProductTotals = array();



    public function getCategory($category_id) {
		return Make::a('catalog/category')->table_alias('c')
				->distinct()
				->left_outer_join('category_description', 'c.category_id = cd.category_id AND cd.language_id = "' . (int) $this->reg->config->get('config_language_id') . '"', 'cd')
				->where('c.category_id', (int) $category_id)
				->where('c.status', 1)
				->find_one();
    }



    public function getCategoryByProductId($product_id) {

	return ORM::for_table('product_to_category')->table_alias('pc')

			->select('cd.name')

			->select('c.ref_category_code')

			->select('cd.category_id')

			->select('c.parent_id')

			->left_outer_join('category', 'c.category_id = pc.category_id', 'c')

			->left_outer_join('category_description', 'c.category_id = cd.category_id AND cd.language_id = ' . (int) $this->reg->config->get('config_language_id'), 'cd')

			->where('pc.product_id', (int) $product_id)

			->find_one(null, true);

    }



    public function getParentName($category_id) {

	return ORM::for_table('category_description')

			->select('name')

			->where('category_id', (int) $product_id)

			->find_many(true);

    }



    public function getCategories($parent_id = 0) {

	return Make::a('catalog/category')->table_alias('c')

			->left_outer_join('category_description', 'c.category_id = cd.category_id AND cd.language_id = "' . (int) $this->reg->config->get('config_language_id') . '"', 'cd')

			->where('c.parent_id', (int) $parent_id)

			->where('c.status', 1)

			->order_by_asc('c.sort_order')

			->order_by_asc('cd.name')

			->find_many(true);

    }



    public function getMatrix($start = 0, $limit = 50) {



	$sql = "SELECT MAX(level) AS levels FROM " . DB_PREFIX . "category";

	$levels = $this->reg->db->query($sql);



	$sql = "SELECT SQL_CALC_FOUND_ROWS CONCAT_WS('||',m0.category_id,m0.sort_order,m0d.name,m0d.meta_link) AS level0";

	if ($levels->row['levels'] > 0) {

	    $sql .= ",";

	    for ($i = 0; $i < $levels->row['levels']; $i++) {

		$k = $i + 1;

		$sql .= " CONCAT_WS('||',m" . $k . ".category_id,m" . $k . ".sort_order,m" . $k . "d.name,m" . $k . "d.meta_link) AS level" . $k . ($k == $levels->row['levels'] ? '' : ',');

	    }

	}

	$sql .= " FROM category AS m0";

	$sql .= " LEFT JOIN category_description AS m0d ON m0.category_id = m0d.category_id AND m0d.language_id = " . (int) $this->reg->config->get('config_language_id');

	if ($levels->row['levels'] > 0) {

	    for ($i = 0; $i < $levels->row['levels']; $i++) {

		$k = $i + 1;

		$sql .= " LEFT JOIN category AS m" . $k . " ON m" . $k . ".parent_id = m" . ($k - 1) . ".category_id AND m" . $k . ".status = '1'";

		$sql .= " LEFT JOIN category_description AS m" . $k . "d ON m" . $k . ".category_id = m" . $k . "d.category_id AND m" . $k . "d.language_id = " . (int) $this->reg->config->get('config_language_id');

	    }

	}

	$sql .= " WHERE m0.parent_id = '0' AND m0.status = '1' AND m0d.name IS NOT NULL";

	$sql .= " ORDER BY m0.sort_order ASC";

	if ($levels->row['levels'] > 0) {

	    $sql .= ",";

	    for ($i = 0; $i < $levels->row['levels']; $i++) {

		$k = $i + 1;

		$sql .= " m" . $k . ".sort_order ASC" . ($k == $levels->row['levels'] ? '' : ',');

	    }

	}

	$sql .= " LIMIT " . $start . "," . $limit;

	$query = $this->reg->db->query($sql);

	$sql = "SELECT FOUND_ROWS() total";

	$iQuery = $this->reg->db->query($sql);

	return array('matrix' => $query->rows, 'levels' => $levels->row['levels'],'total' => $iQuery->row['total']);

    }



    public function getAllCategories() {

	return Make::a('catalog/category')->table_alias('c')

			->left_outer_join('category_description', 'c.category_id = cd.category_id AND cd.language_id = "' . (int) $this->reg->config->get('config_language_id') . '"', 'cd')

			->where('c.status', 1)

			->order_by_asc('c.sort_order')

			->order_by_asc('cd.name')

			->find_many(true);

    }



    public function getCategoriesById($category_id = 0, $not_in = FALSE) {

	$oModel = Make::a('catalog/category')->table_alias('c')

		->distinct()

		->left_outer_join('category_description', 'c.category_id = cd.category_id AND cd.language_id = "' . (int) $this->reg->config->get('config_language_id') . '"', 'cd')

		->where('c.category_id', (int) $category_id)

		->where('c.status', 1);

	if ($not_in)

	    $oModel = $oModel->where_not_in('c.category_id', $category_id);

	else

	    $oModel = $oModel->where_in('c.category_id', $category_id);

	return $oModel->find_many(true);

    }



    public function getTotalCategoriesByCategoryId($parent_id = 0) {

	$query = $this->reg->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c WHERE c.parent_id = '" . (int) $parent_id . "' AND c.status = '1'");



	return $query->row['total'];

    }



    public function getChildCategories($parent_id) {

//        if (empty($this->aProductTotals)) {

//            $aResults = array();

//            $aTotals = Make::a('catalog/product')->table_alias('p')

//                    ->select('c.category_id')

//                    ->select_expr('COUNT(p.product_id)', 'total')

//                    ->join('product_to_category', 'p.product_id=c.product_id', 'c')

//                    ->where('p.status', 1)

//                    ->group_by('c.category_id')

//                    ->find_many(true);

//            //->where('c.category_id',$cat['category_id']);

//            foreach ($aTotals as $result) {

//                $aResults[$result['category_id']] = $result['total'];

//            }

//

//            $this->aProductTotals = $aResults;

//        }



	$sSql = "SELECT * FROM " . DB_PREFIX . "category c 

                LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 

                    WHERE c.parent_id = '" . (int) $parent_id . "' 

                        AND cd.language_id = '" . (int) $this->reg->config->get('config_language_id') . "' 

                        AND c.status = '1' 

                        ORDER BY c.sort_order, LCASE(cd.name)";

	$query = $this->reg->db->query($sSql);

	$results = $query->rows;



//        $i = 0;

	foreach ($results as $i => $cat) {

	    $hasChildren = $this->getTotalCategoriesByCategoryId($cat['category_id']);

	    if (!$hasChildren) {

//                if (!array_key_exists($cat['category_id'], $this->aProductTotals))

		continue;

	    } else {

//                $bChild = false;

		$aChildren = $this->getChildCategories($cat['category_id']);

		foreach ($aChildren as $aChild) {

//                    if (array_key_exists($aChild['category_id'], $this->aProductTotals) && $this->aProductTotals[$aChild['category_id']] > 0) {

//

//                    }

//                    $bChild = true;

		    $results[$i]['child'][] = $aChild;

		}

//                if (!$bChild)

//                    continue;

	    }

//          $results[$i]['child'] = $aChildren;

//            $i++;

	}



	return $results;

    }



    public function emptyIndexCategory() {

	$sSql = "TRUNCATE TABLE ix_category_zone;";

	$this->reg->db->query($sSql);

    }



    public function addIndexCategory($aData) {

	$sSql = "INSERT INTO ix_category_zone

        SET `category_id` = " . $aData['category_id'] . ",

            `geo_zone_id` = " . $aData['geo_zone_id'] . ",

            `name` = '" . $aData['name'] . "',

            `meta_link` = '" . $aData['meta_link'] . "',

            `products` = '" . $aData['products'] . "',

            `parent_id` = '" . $aData['parent_id'] . "',

            `path` = '" . $aData['path'] . "',

            `level` = '" . $aData['level'] . "'";

	$this->reg->db->query($sSql);

    }



    public function getIdxParentCategories() {

	$sSql = "SELECT * FROM ix_category_zone WHERE parent_id = 0 ORDER BY geo_zone_id, category_id";

	$query = $this->reg->db->query($sSql);

	return $query->rows;

    }



    public function getIdxCategoryTree($iZone, $iCategory) {

	$sSql = "SELECT * FROM ix_category_zone

              WHERE geo_zone_id = $iZone AND path LIKE '$iCategory/%'

              ORDER BY path, `level`,parent_id";

	$query = $this->reg->db->query($sSql);

	return $query->rows;

    }



    public function getMaxIndexCategoryLevel() {

	$sSql = "SELECT MAX(level) level FROM ix_category_zone";

	$query = $this->reg->db->query($sSql);

	return $query->row['level'];

    }



    public function getCategoryTitle($cat_id) {

	$sSql = "SELECT name FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $cat_id . "' AND language_id = '" . (int) $this->reg->config->get('config_language_id') . "'";

	$query = $this->reg->db->query($sSql);

	return $query->row;

    }



    public function getPriceRange($category_id) {

	$sql = "SELECT MIN(p.price) AS min_price, MAX(p.price) AS max_price FROM " . DB_PREFIX . "product p";

	$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON p2c.product_id = p.product_id";

	$sql .= " WHERE p2c.category_id = '" . (int) $category_id . "' AND p.status = '1'";



	$query = $this->reg->db->query($sql);

	return $query->row;

    }

    public function getChildCategoriesByParent($category_id) {
    	$oModel = ORM::for_table('category')
    				->table_alias('c')
    				->inner_join('category_description',array('c.category_id','=','cd.category_id'),'cd')
    				->where('cd.language_id',$this->reg->config->get('config_language_id'));
		$aOrm = clone $oModel;
    	$aOrm = CHtml::listData($aOrm->where('c.parent_id',$category_id)->find_many(true),'category_id','name');
		foreach($aOrm as $id => $name) {
			$aCats[$id]['name'] = $name;
			$aChilds = clone $oModel;
			$aCats[$id]['child'] = CHtml::listData($aChilds->where('c.parent_id',$id)->find_many(true),'category_id','name');
		}
		return $aCats;
    }

    public function getPath($category_id) {
		$oOrm = Make::a('catalog/category')
			->table_alias('c')
			->select_expr('cd.name,c.parent_id')
			->left_outer_join('category_description', array('c.category_id', '=', 'cd.category_id'), 'cd')
			->where('cd.language_id', $this->reg->config->get('config_language_id'))
			->where('c.category_id', $category_id)
			->order_by_asc('c.sort_order')
			->order_by_asc('cd.name')
			->find_one();
		$category_info = $oOrm->toArray();
		if ($category_info['parent_id']) {
		    return $this->getPath($category_info['parent_id']) . $this->reg->language->get('text_separator_search') . $category_info['name'];
		} else {
		    return $category_info['name'];
		}
    }


    public function getPathUrl($category_id){
    	$oOrm = Make::a('catalog/category')
			->where('category_id', $category_id)
			->find_one();
		$category_info = $oOrm->toArray();
		if ($category_info['parent_id']) {
		    return $this->getPathUrl($category_info['parent_id']) . '_' . $category_info['category_id'];
		} else {
		    return $category_info['category_id'];
		}
    }
}



?>