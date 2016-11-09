<?php

// Declare the interface 'iFilter'
interface iFilter {

    public function getWhereClause();

    public function getFilter($aFilter);

    public function getLikeFilter($aFilter);

    public function getRangeFilter($aFilter);

    public function getInFilter($aFilter);

}

// Implement the interface
// Category Class
class Filter implements iFilter {

    public $model;
    public $filters;
    public $type;
    public $field;
    public $select;
    public $declared_values;
    public $aValues;
    public $category_id = 0;
    public $keywords = "";

    public function __construct() {
	
    }

    /**
     * Get Model with filter implemented
     * Param object: $orm; ORM Wrapper
     * Param object: $aFilter Filter Array
     * Return object: $orm Filter Array
     */
    public function getWhereClause() {

	if (isset($this->filters) && !empty($this->filters)) {
		// $aClause = array();
		// $aOperators = array();
		$sClause = '';
	    switch ($this->type) {
		case 0:
		case 4:
		default:
		    
		    $aWhere = $this->getFilter($this->filters);
		    if ($aWhere) {
		    	$i = 1;
				foreach ($aWhere as $key => $value) {
				    // $this->model->where($key, $value);
				    $sClause .= $key . ' = ' . $value . (count($aWhere) == $i++ ? '' : ' OR ');
				}
		    }
		    break;
		case 1:
		    $aWhere = $this->getLikeFilter($this->filters);
		    if ($aWhere) {
		    	$i = 1;
				foreach ($aWhere as $key => $value) {
				    // $this->model->where_like($key, $value);
				    $sClause .= $key . ' LIKE ' . $value . (count($aWhere) == $i++ ? '' : ' OR ');
				}
		    }
		    break;
		case 2:
		    $aWhere = $this->getRangeFilter($this->filters);
		    if ($aWhere) {
		    	$i = 1;
				foreach ($aWhere as $key => $value) {
				    // $this->model->where_raw($key, $value);
					$sClause .= vsprintf(str_replace('?','%s',$key),$value) . (count($aWhere) == $i++ ? '' : ' OR ');
				}
		    }
		    break;
		case 3:
		    $aWhere = $this->getInFilter($this->filters);
		    if ($aWhere) {
		    	$i = 1;
				foreach ($aWhere as $key => $value) {
					$sClause .= $key . ' IN (' . implode(',',$value) . ')' . (count($aWhere) == $i++ ? '' : ' OR ');
				    // $this->model->where_in($key, $value);
				}
		    }
		    break;
		case 5:
		    $aWhere = $this->getInRawFilter($this->filters);
		    if ($aWhere) {
		    	$i = 1;
				foreach ($aWhere as $key => $value) {
				    // $this->model->where_raw($key, $value);
					$sClause .= vsprintf(str_replace('?','%s',$key),$value) . (count($aWhere) == $i++ ? '' : ' OR ');
				}
		    }
		    break;
		case 6:
		    $aWhere = $this->getDependendFilter($this->filters);
		    if ($aWhere) {
		    	$i = 1;
				foreach ($aWhere as $key => $value) {
				    // $this->model->where_raw($value, array());
				    $sClause .= $value . (count($aWhere) == $i++ ? '' : ' OR ');
				}
		    }
		    break;
	    }
	}
	return $sClause;
	// return $this->model;
    }

    /**
     * Param object: $orm; ORM Wrapper
     * Return string: $orm; Orm
     */
    public function getFilter($value) {
	$filter = array();
	if ($value && !is_array($value)) {
	    $filter[$this->field] = $value;
	}
	return $filter;
    }

    public function getLikeFilter($value) {
	$filter = array();
	if (!is_array($value)) {
	    $filter[$this->field] = '%' . strtolower($value) . '%';
	}

	return $filter;
    }

    public function getInFilter($value) {
	$filter = array();
	if (!is_array($value)) {
	    $filter[$this->field] = explode(',', $value);
	} else {
	    foreach ($value as $result) {
		$filter[$this->field][] = $result;
	    }
	}
	return $filter;
    }

    public function getRangeFilter($value) {
	$filter = array();
	if (is_array($value)) {
	    $lhs = $this->field . ' BETWEEN ? and ?';
	    $filter[$lhs] = array($value['from'], $value['to']);
	}
	return $filter;
    }

    public function getInRawFilter($value) {
	$filter = array();
	$rhs = array();
	$in = '';

	if (!is_array($value)) {
	    $rhs = explode(',', $value);
	} else {
	    foreach ($value as $result) {
		$rhs[] = $result;
	    }
	}

	// foreach ($rhs as $k => $r) {
	//     $in .= '?' . ($k == count($rhs) - 1 ? '' : ',');
	// }
	$in .= '?' . implode(',',$rhs);
	$lhs = 'LOWER(' . $this->field . ') IN (' . $in . ')';
	$filter[$lhs] = $rhs;
	return $filter;
    }

    public function getDependendFilter($value) {
	$filter = array();
	if (count($value) == 1) {
	    if ($this->field == 'podv_size.name') {
		// $filter[] = "(LOWER(" . $this->field . ") LIKE '%/" . $value[0] . "')";
	 //    } elseif ($this->field == 'podv_color.name') {
		// $filter[] = "(LOWER(" . $this->field . ") LIKE '" . $value[0] . "/%')";
	 //    }
			$filter[] = "(LOWER(" . $this->field . ") = '" . $value[0] . "')";
	    } 
	    elseif ($this->field == 'podv_color.name') {
			$filter[] = "(LOWER(" . $this->field . ") = '" . $value[0] . "')";
		}
	} 
	else {
	    $in = '';
	    $value = array_unique($value);
	    foreach ($value as $k => $result) {
			if ($this->field == 'podv_size.name') {
			    // $in .= " LOWER(" . $this->field . ") LIKE '%/" . $result . ($k == count($value) - 1 ? "'" : "' OR");
			    $in .= " LOWER(" . $this->field . ") = '" . $result . ($k == count($value) - 1 ? "'" : "' OR");
			} elseif ($this->field == 'podv_color.name') {
			    // $in .= " LOWER(" . $this->field . ") LIKE '" . $result . ($k == count($value) - 1 ? "/%'" : "/%' OR");
			    $in .= " LOWER(" . $this->field . ") = '" . $result . ($k == count($value) - 1 ? "'" : "' OR");
			}
	    }
	    $lhs = '(' . $in . ')';
	    $filter[] = $lhs;
	}

	return $filter;
    }

}

?>
