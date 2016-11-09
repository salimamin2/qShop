<?php

// Category Class
class FilterCategory extends Filter {

    public $reg;

    public function __construct($oModel, $aOptions) {
		$this->model = $oModel;
		$this->type = 0;
		$this->field = 'c.category';
		$this->reg = Registry::getInstance();
		$this->select = 'Category';
		$this->declared_values = $aOptions['value_defined'];
		$this->aValues = $aOptions['filter_value'];
		if($aOptions['category_id']) {
			$this->category_id = $aOptions['category_id'];
		}
    }

    public function init($aFilter) {
	$this->filters = $aFilter;
        return $this->getWhereClause();
    }

    public function getValues($oOrm) {
	$oModel = clone $oOrm;
	if ($this->declared_values) {
	    $aValues = $this->aValues;
	} else {
		$aValues = Make::a('catalog/category')->table_alias('c')
					    ->inner_join('category_description', 'c.category_id=cd.category_id AND cd.language_id=' . (int) $this->reg->config->get('config_language_id'), 'cd')
				    	->find_many(true);
	}

	$aResults = array();
	foreach ($aValues as $aValue) {
		$aResults[$aValue['category_id']]['name'] = $aValue['name'];
	}
	d($aResults);
	return $aResults;
    }

}

?>
