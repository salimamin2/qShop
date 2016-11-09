<?php

// Color Class
class FilterOptionColorSize extends Filter {

    public function __construct($oModel, $aOptions) {
	$this->model = $oModel;
	$this->type = 5;
	$this->field = 'podv_colorsize.name';
	$this->select = 'Color/Size';
	$this->declared_values = $aOptions['value_defined'];
	$this->aValues = $aOptions['filter_value'];
	$this->category_id = $aOptions['category_id'];
    }

    public function init($aFilter) {
	$this->model = $this->model
		->left_outer_join('product_option_value_description', 'p.product_id=podv_colorsize.product_id', 'podv_colorsize');
	$this->filters = $aFilter;
        return $this->getWhereClause();
    }

    public function getValues($oOrm) {
	$oModel = clone $oOrm;
	if ($this->declared_values) {
	    $aValues = $this->aValues;
	} else {
	    $aValues = ARModel::factory('product_option_description')->table_alias('pod')
		    ->inner_join('product_option_value', 'pov.product_option_id=pod.product_option_id', 'pov')
		    ->inner_join('product_option_value_description', 'pov.product_option_value_id=povd.product_option_value_id', 'povd')
		    ->left_outer_join('product_to_category', 'pov.product_id=p2c.product_id', 'p2c')
		    ->left_outer_join('product', 'pov.product_id=p.product_id', 'p')
		    ->where_raw('LOWER(pod.name)="color" AND pod.product_id != "-1"', array())
		    //->where('p2c.category_id', $this->category_id)
		    ->where('p.status', 1)
		    ->where_gt('p.quantity', 0)
		    //->where_lte('p.date_available', date('Y-m-d'))
		    ->group_by('povd.name')
		    ->find_many(true);
	}
	$aResults = array();
	foreach ($aValues as $aValue) {
	    $aResults[strtolower($aValue['name'])] = $aValue['name'];
	}
       // d($aValues);
	return $aResults;
    }

}

?>
