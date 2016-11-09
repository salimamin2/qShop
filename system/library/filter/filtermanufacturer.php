<?php

// Manufacturer Class
class FilterManufacturer extends Filter {

    public function __construct($oModel, $aOptions) {
	$this->model = $oModel;
	$this->type = 3;
	$this->field = 'p.manufacturer_id';
	$this->select = 'Brand';
	$this->declared_values = $aOptions['value_defined'];
	$this->aValues = $aOptions['filter_value'];
	$this->category_id = isset($aOptions['category_id']) ? $aOptions['category_id'] : 0;
	$this->keywords = isset($aOptions['keyword']) ? strtolower($aOptions['keyword']) : '';
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
	    $aValues = $oModel
		    ->select('m.name')
		    ->select_expr('count(p.product_id)', 'count')
		    ->inner_join('manufacturer', 'p.manufacturer_id = m.manufacturer_id', 'm')
		    ->group_by('p.manufacturer_id')
		    ->find_many(true);
        //d($aValues);
	}
	$aResults = array();
	foreach ($aValues as $aValue) {
	    $aResults[$aValue['manufacturer_id']] = array('name' => $aValue['name'], 'count' => 1);
	}
	return $aResults;
    }

}

?>
