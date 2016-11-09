<?php

class ModelCatalogCategory extends ARModel {

    public static $_table = 'category';
    public static $_id_column = 'category_id';
    public $keyword;
    protected $_fields = array(
	'category_id',
	'image',
	'parent_id',
	'sort_order',
	'date_added',
	'date_modified',
	'status',
	'level',
	'ref_category_code'
    );
    protected $_rules = array(
	'insert|update' => array(
	    'rules' => array(
		'ref_category_code' => array('required' => true),
	    )
    ));

    public function addCategory($data) {
	try {
	    $this->orm->beginTransaction();
//            $oCategory = Make::a('catalog/category')->create();
	    $this->setFields($data);
	    $this->date_added = date('Y-m-d H:i:s');
	    $this->date_modified = date('Y-m-d H:i:s');

	    if ($data['parent_id'] > 0) {
		    $oObj = Make::a('catalog/category')->select_expr('(level+1) level')->where('category_id', $data['parent_id'])->find_one();
            $this->level = $oObj->level;
	    }

        $this->save();
	    if ($this->hasErrors()) {
		    throw new Exception(CHtml::errorSummary($this));
	    }

	    foreach ($data['category_description'] as $language_id => $value) {
		$oOrm = Make::a('catalog/category_description')->create();
		$oOrm->setFields($value);
		$oOrm->category_id = $this->id();
		$oOrm->language_id = $language_id;
		$oOrm->save();
		if ($oOrm->hasErrors()) {
		    throw new Exception(CHtml::errorSummary($oOrm));
		}
	    }

	    if ($data['keyword']) {
            $oOrm = ORM::for_table('url_alias')->create();
            $oOrm->group = "category";
            $oOrm->query = "category_id=" . $this->id();
            $oOrm->keyword = $data['keyword'];
            $oOrm->save();
	    }
	    QS::app()->cache->delete('category');
	    $this->orm->commit();
	    return true;
	} catch (Exception $e) {
//        d($e);
	    $this->orm->rollback();
	    return array('error' => $e->getMessage());
	}
    }

    public function editCategory($data) {
	$result = array();
	try {
	    $this->orm->beginTransaction();
//            $oModel = Make::a('catalog/category')->find_one($this->id());
	    $this->setFields($data);
	    $this->date_modified = date('Y-m-d H:i:s');

	    $oObj = Make::a('catalog/category')->select_expr('(level+1) level')->where('category_id', $data['parent_id'])->find_one();
        $this->level = $oObj->level;
	    $this->updateChildLevel($this->id(), $oObj->level);

        $this->save();
        if ($this->hasErrors()) {
            throw new Exception("An error occurred in editing category");
        }

	    ORM::raw_execute("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = ?", array($this->id()));

	    foreach ($data['category_description'] as $language_id => $value) {
		$oOrm = Make::a('catalog/category_description')->create();
		$oOrm->setFields($value);
		$oOrm->category_id = $this->id();
		$oOrm->language_id = $language_id;
		$oOrm->save();
		if ($oOrm->hasErrors()) {
		    throw new Exception(CHtml::errorSummary($oOrm));
		}
	    }

	    ORM::raw_execute("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int) $this->id() . "'");

	    if ($data['keyword']) {
            $oOrm = ORM::for_table('url_alias')->create();
            $oOrm->group = "category";
            $oOrm->query = "category_id=" . $this->id();
            $oOrm->keyword = $data['keyword'];
            $oOrm->save();
	    }

	    QS::app()->cache->delete('category');
	    $result['success'] = "Success";
	    $this->orm->commit();
	} catch (Exception $e) {
	    $this->orm->rollback();
	    $result['error'] = $e->getMessage();
	}
	return $result;
    }

    protected function updateChildLevel($id, $level) {

	$aChildrens = Make::a('catalog/category')->where('parent_id', $id)
		                ->find_many();
        if ($aChildrens) {
            foreach ($aChildrens as $oChild) {
                $this->updateChildLevel($oChild->category_id, $oChild->level + 1);
                $oChild->level = (int) ($level + 1);
                $oChild->save();
                if ($oChild->hasErrors()) {
                    throw new Exception(CHtml::errorSummary($oChild));
                }
            }
        }
    }

    public function deleteCategory($category_ids) {
	try {
	    QS::app()->cache->delete('category');
	    foreach ($category_ids as $category_id) {
		$oModel = Make::a('catalog/category')->find_one($category_id);
		if ($oModel) {
		    ORM::raw_execute("DELETE FROM category_description WHERE category_id = ?", array($category_id));
		    ORM::raw_execute("DELETE FROM url_alias WHERE query = 'category_id=" . (int) $category_id . "'");
		    $childs = Make::a('catalog/category')
			    ->where('parent_id', $category_id)
			    ->find_many();

		    foreach ($childs as $result) {
			$result->parent_id = 0;
			$result->save();
		    }

		    $oModel->delete();
		    if ($oModel->hasErrors()) {
			throw new Exception("error in delete");
		    }
		}
	    }
	} catch (Exception $e) {
	    return $e->getMessage();
	}
    }

    public function getCategory($category_id) {
	$category = Make::a('catalog/category')
		->table_alias('c')
		->select_expr('c.*,u.keyword')
		->left_outer_join('url_alias', "u.query = 'category_id=" . (int) $category_id . "'", 'u')
		->find_one($category_id);
	return $category;
    }

    public function getCategories($parent_id) {

// $category_data = QS::app()->cache->get('category.' . QS::app()->config->get('config_language_id') . '.' . $parent_id);
// if (!$category_data) {
	$category_data = array();

	$rows = Make::a('catalog/category')
		->table_alias('c')
		->select_expr('c.category_id,c.ref_category_code,c.status,c.sort_order')
		->left_outer_join('category_description', array('c.category_id', '=', 'cd.category_id'), 'cd')
		->where('c.parent_id', $parent_id)
		->where('cd.language_id', QS::app()->config->get('config_language_id'))
		->order_by_asc('c.sort_order')
		->order_by_asc('cd.name')
		->find_many(true);

	foreach ($rows as $result) {
	    $category_data[] = array(
		'category_id' => $result['category_id'],
		'ref_category_code' => $result['ref_category_code'],
		'name' => $this->getPath($result['category_id']),
		'status' => $result['status'],
		'sort_order' => $result['sort_order']
	    );

	    $category_data = array_merge($category_data, $this->getCategories($result['category_id']));
	}

//   QS::app()->cache->set('category.' . QS::app()->config->get('config_language_id') . '.' . $parent_id, $category_data);
//}

	return $category_data;
    }

    public function getCategoriesByKeyword($keyword) {
		$category_data = array();

		$rows = Make::a('catalog/category')
			->table_alias('c')
			->select_expr('c.category_id,c.ref_category_code,c.status,c.sort_order')
			->left_outer_join('category_description', array('c.category_id', '=', 'cd.category_id'), 'cd')
			->where_like('cd.name', array('%'.$keyword.'%'))
			->where('cd.language_id', QS::app()->config->get('config_language_id'))
			->order_by_asc('c.sort_order')
			->order_by_asc('cd.name')
			->find_many(true);

		return $rows;
    }

    public function getPath($category_id) {
		$oOrm = Make::a('catalog/category')
			->table_alias('c')
			->select_expr('cd.name,c.parent_id')
			->left_outer_join('category_description', array('c.category_id', '=', 'cd.category_id'), 'cd')
			->where('cd.language_id', QS::app()->config->get('config_language_id'))
			->where('c.category_id', $category_id)
			->order_by_asc('c.sort_order')
			->order_by_asc('cd.name')
			->find_one();
		if($oOrm){
			$category_info = $oOrm->toArray();
			if ($category_info['parent_id']) {
			    return $this->getPath($category_info['parent_id']) . QS::app()->language->get('text_separator') . $category_info['name'];
			} else {
			    return $category_info['name'];
			}
		}
    }

    public function getCategoryDescriptions() {
	return $this->has_many('catalog/category_description', "category_id");
    }

    public function getTotalCategories() {
	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

	return $query->row['total'];
    }

    public function getTotalCategoriesByImageId($image_id) {
	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE image_id = '" . (int) $image_id . "'");

	return $query->row['total'];
    }

    public function getKeyword($category) {
	if (!is_array($category))
	    return '';

	if (!empty($category['keyword'])) {
	    return $category['keyword'];
	} else {
	    $description = $this->getCategoryDescriptions($category['category_id']);
	    list(, $description) = each($description);
	    return $this->friendlyUrl($description['name']);
	}
    }

    /* takes the input, scrubs bad characters */

    function friendlyURL($input, $replace = '-', $remove_words = true, $words_array = array('a', 'and', 'the', 'an', 'it', 'is', 'with', 'can', 'of', 'why', 'not')) {
//make it lowercase, remove punctuation, remove multiple/leading/ending spaces
	$return = trim(ereg_replace(' +', ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input))));

//remove words, if not helpful to seo
//i like my defaults list in remove_words(), so I wont pass that array
	if ($remove_words) {
	    $return = $this->removeBadSEOWords($return, $replace, $words_array);
	}

//convert the spaces to whatever the user wants
//usually a dash or underscore..
//...then return the value.
	$keyword = str_replace(' ', $replace, $return);
	return $this->getUniqueKeyword($keyword);
    }

    /* takes an input, scrubs unnecessary words */

    function removeBadSEOWords($input, $replace, $words_array = array(), $unique_words = true) {
//separate all words based on spaces
	$input_array = explode(' ', $input);

//create the return array
	$return = array();

//loops through words, remove bad words, keep good ones
	foreach ($input_array as $word) {
//if it's a word we should add...
	    if (!in_array($word, $words_array) && ($unique_words ? !in_array($word, $return) : true)) {
		$return[] = $word;
	    }
	}

//return good words separated by dashes
	return implode($replace, $return);
    }

    function getUniqueKeyword($keyword) {
	$sql = "SELECT COUNT(*) as total FROM url_alias WHERE keyword LIKE '{$keyword}%'";
	$query = $this->db->query($sql);
	return $query->row['total'] ? $keyword . '_' . $query->row['total'] : $keyword;
    }

}

?>