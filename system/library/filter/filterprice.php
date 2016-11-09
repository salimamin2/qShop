<?php

// Price Class
class FilterPrice extends Filter {

    public $registry;

    public function __construct($oModel, $aOptions) {
        $this->registry = Registry::getInstance();

        $this->model = $oModel;
        $this->type = 2;
        $this->field = 'pi.price';
        $this->select = 'Price';
        $this->declared_values = $aOptions['value_defined'];
        $this->aValues = $aOptions['filter_value'];
        $this->category_id = isset($aOptions['category_id']) ? $aOptions['category_id'] : 0;
        $this->keywords = isset($aOptions['keyword']) ? strtolower($aOptions['keyword']) : '';
        $this->slots = isset($aOptions['slots']) ? $aOptions['slots'] : 2;
    }

    public function init($aFilter) {
        foreach ($aFilter as $i => $sFilter) {
            $aPrice = explode('-', $sFilter);
            $aFilter['from'] = $aPrice[0];
            $aFilter['to'] = $aPrice[1];
        }
        $this->filters = $aFilter;
        return $this->getWhereClause();
    }

    public function getValues($oOrm) {
	$oModel = clone $oOrm;

        if ($this->declared_values) {
            $aValues = $this->aValues;
        } else {

            $oModel = $oModel
                    ->select_expr('Max(pi.price)', 'max_price')
                    ->select_expr('Min(pi.price)', 'min_price');
            $oValue = $oModel->find_one();
        }
        $results = array();
        if ($oValue) {
            $aValue = $oValue->toArray();
            $slots = $this->slots;
            $start = 0;
            if ($aValue['max_price'] != $aValue['min_price']) {
                $mid = (int) round($aValue['max_price'] / $slots);
                if ($aValue['min_price'] > $mid) {
                    $mid = $aValue['min_price'];
                }
                $end = $aValue['max_price'];
                if ($start != $end) {
                    for ($i = 0; $i < $slots; $i++) {
                        $oCount = clone $this->model;

                        $sValue = '';
                        $sKey = '';
                        if (($i + 1) != $slots) {
                            $sKey .= (int) $start . '-';
                            $sValue .= $this->registry->currency->format($start) . '-';
                        }
                        $sValue .= $this->registry->currency->format($mid);
                        $sKey .= (int) $mid;
                        if ($mid <= $end) {
                            if (($i + 1) == $slots) {
                                $sKey .= '-' . (int) $end;
                                $sValue .= '-' . $this->registry->currency->format($end);
                            }
                        } else {
                            $sValue .= '-Above';
                        }
                        $iCount = $oCount->where_raw('pi.price BETWEEN ? AND ?', explode('-', $sKey))->count();
                        $results[$sKey] = array('name' => $sValue, 'count' => $iCount);
                        $start = $mid;
                        $mid = (int) $mid * ($i + 1) + 1;
                    }
                }
            }
        }
//	if (empty($results)) {
//	    $results['0-100'] = array('name' => $this->registry->currency->format(0) . '-' . $this->registry->currency->format(100), 'count' => 0);
//	}

        return $results;
    }

}

?>
