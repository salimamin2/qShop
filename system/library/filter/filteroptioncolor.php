<?php

// Color Class
class FilterOptionColor extends Filter {

    public function __construct($oModel, $aOptions) {
        $this->model = $oModel;
        $this->type = 6;
        $this->field = 'podv_color.name';
        $this->select = 'Color';
        $this->declared_values = $aOptions['value_defined'];
        $this->aValues = $aOptions['filter_value'];
        $this->category_id = isset($aOptions['category_id']) ? $aOptions['category_id'] : 0;
        $this->keywords = isset($aOptions['keyword']) ? strtolower($aOptions['keyword']) : '';
    }

    public function init($aFilter) {
        $this->model = $this->model
                ->left_outer_join('product_option_value_description', 'p.product_id=podv_color.product_id', 'podv_color');
        $this->filters = $aFilter;
        return $this->getWhereClause();
    }

    public function getValues($oOrm) {
        $oModel = clone $oOrm;
        if ($this->declared_values) {
            $aValues = $this->aValues;
        } else {
            $aValues = $oModel
                    ->select('povd.name')
                    ->select_expr('count(p.product_id)', 'count')
                    ->inner_join('product_option_description', 'pod.product_id=p.product_id', 'pod')
                    ->inner_join('product_option_value', 'pov.product_option_id=pod.product_option_id', 'pov')
                    ->inner_join('product_option_value_description', 'pov.product_option_value_id=povd.product_option_value_id', 'povd')
                    ->where_raw('(LOWER(pod.name)="color" OR LOWER(pod.name)="color/size" OR LOWER(pod.name)="colour" OR LOWER(pod.name)="colour/size") AND pod.product_id != "-1"', array())
                    ->group_by('povd.name')
                    ->find_many(true);

//	    $oModels = ARModel::factory('product_option_description')->table_alias('pod')
//		    ->select('*')
//		    ->select('povd.name')
//		    ->select_expr('count(p.product_id)', 'count')
//		    ->inner_join('product_option_value', 'pov.product_option_id=pod.product_option_id', 'pov')
//		    ->inner_join('product_option_value_description', 'pov.product_option_value_id=povd.product_option_value_id', 'povd')
//		    ->left_outer_join('product_to_category', 'pov.product_id=p2c.product_id', 'p2c')
//		    ->left_outer_join('product', 'pov.product_id=p.product_id', 'p')
//		    ->left_outer_join('product_description', 'pd.product_id=p.product_id', 'pd')
//		    ->where('p.status', 1)
//		    ->where_gt('p.quantity', 0)
//		    ->where_lte('p.date_available', date('Y-m-d'));
//	    if ($this->category_id) {
//		$oModels = $oModels->where('p2c.category_id', $this->category_id);
//	    }
//	    if ($this->keywords) {
//		$oModels = $oModels->where_raw('(lower(pd.name) LIKE ? OR lower(p.model) LIKE ? OR  lower(pd.description) LIKE ?)', array('%' . $this->keywords . '%', '%' . $this->keywords . '%', '%' . $this->keywords . '%'));
//	    }
//
//	    $aValues = $oModels
//		    ->where_raw('(LOWER(pod.name)="color" OR LOWER(pod.name)="color/size") AND pod.product_id != "-1"', array())
//		    ->group_by('povd.name')
//		    ->find_many(true);
        }
        $aResults = array();
        $aNames = array();
        foreach ($aValues as $aValue) {
            if (strpos($aValue['name'], '/')) {
                $aColors = explode('/', $aValue['name']);
                if (empty($aNames) || !in_array($aColors[0], $aNames)) {
                    $sName = $aColors[0];
                    $aNames[] = $aColors[0];
                }
            } else {
                $sName = $aValue['name'];
            }
            $aResults[trim(strtolower($sName))] = array('name' => $sName, 'count' => $aValue['count']);
        }
        return $aResults;
    }

}

?>
