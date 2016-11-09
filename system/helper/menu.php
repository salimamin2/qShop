<?php

class HelperMenu {

    protected $registery;

    const COLUMN_ID = 0;
    const COLUMN_LINK = 1;
    const COLUMN_TYPE = 2;
    const COLUMN_ORDER = 3;
    const COLUMN_NAME = 4;
    const COLUMN_ATTRIBUTE = 5;
    const COLUMN_BATCH = 6;
    const COLUMN_META_LINK = 7;
    const COLUMN_STATIC_BLOCK = 8;

    public static $batch = array(
	"New" => 1,
	"Sale" => 2,
	"Hot" => 3,
	'Featured' => 4,
	'Popular' => 5
    );

    public function __construct($registery) {
	$this->registery = $registery;
    }

    //using in recursive level information
    var $temp_level = 0;

    /**
     * Param string: $place_code place code
     * Return string: $output;
     */
    public function getMenuHtml($place_code) {
	$aArray = array();
	$output = '';
	$loader = $this->registery->get('load');
	$loader->model('catalog/menu');
	$loader->model('tool/seo_url');
	$results = $loader->model_catalog_menu->getMenuByPlaceCode($place_code);
	$output .= '<ul class="' . strtolower($place_code) . '">';
	//negetive counter setup to add root tree row
	$j = -1;
	foreach ($results['matrix'] as $key => $value) {
	    //setup root tree
	    if (isset($value['level0']) && !isset($aArray[$j][$value['level0']])) {
		$aMenu = $loader->model_catalog_menu->getMenu($value['level0']);
		$output .= '<li class="level_' . $aMenu['level'] . ' item-' . $aMenu['menu_id'] . '">';
		if ($aMenu['link_type'] == 'external') {
			$link = $aMenu['link'];
			if(strpos($link,'.com') === false) {
				$link = makeUrl($link,array(),true);
			}
		} else {
		    $link = $loader->model_tool_seo_url->rewrite(HTTPS_SERVER . $aMenu['link']);
		}
		$output .= '<a href="' . $link . '"' . ($aMenu['attributes'] ? html_entity_decode(' ' . $aMenu['attributes'], ENT_QUOTES, 'UTF-8') : '') . '><span></span>';
		$output .= html_entity_decode(str_replace('&', '&amp;', $aMenu['name']), ENT_QUOTES, 'UTF-8');
		$output .= '</a>';
		$aArray[++$j][$value['level0']] = $aMenu;

		if ($results['levels'] > 0) {
		    //getting complete child trees of each root tree
		    $childrens = $this->getChildHtml($results['matrix'], 1, $results['levels'], $aArray[$j][$value['level0']]);
		    if ($childrens) {
			$output .= '<div class="child"><span class="img"></span><ul>';
			$output .= $childrens;
			$output .= '</ul></div>';
		    }
		}
		$output .= '</li>';
	    }
	}

	$output .= '</ul>';
	return $output;
    }

    
    public function getChildHtml($matrix, $level, $max_level, $parent) {
		$output = '';
		foreach ($matrix as $i => $result) {
		    if (isset($result['level' . $level]) && $result['level' . ($level - 1)] == $parent['menu_id']) {
		    	foreach($result['level' . $level] as $iMenu => $aChilds) {
					$output .= $this->getChildMenuHtml($iMenu,$aChilds);
		    	}
		    }
		}
		return $output;
    }
    
	//recursive calling to filled up child tree for Html
    public function getChildMenuHtml($iMenu,$aChilds) {
		$loader = $this->registery->get('load');
		$loader->model('catalog/menu');
		$output = '';
		if($iMenu != '') {
			$aMenu = $loader->model_catalog_menu->getMenu($iMenu);
			$output .= '<li class="level_' . $aMenu['level'] . ' item-' . $aMenu['menu_id'] . '">';
			if ($aMenu['link_type'] == 'external') {
			    $link = $aMenu['link'];
			} else {
			    $link = $loader->model_tool_seo_url->rewrite(HTTPS_SERVER . $aMenu['link']);
			}
			$output .= '<a href="' . $link . '"' . ($aMenu['attributes'] ? html_entity_decode(' ' . $aMenu['attributes'], ENT_QUOTES, 'UTF-8') : '') . '><span></span>';
			$output .= html_entity_decode(str_replace('&', '&amp;', $aMenu['name']), ENT_QUOTES, 'UTF-8');
			$output .= '</a>';
			if(!empty($aChilds)) {
				$output .= '<div class="child"><span class="img"></span><ul>';
				foreach($aChilds as $iChild => $aChild) {
					$output .= $this->getChildMenuHtml($iChild,$aChild);
				}
				$output .= '</ul></div>';
			}
			$output .= '</li>';
		}
		return $output;
    }

    /**
     * Param string: $place_code; place_code
     * Return Array: $aArray; Array of Menu Tree
     */
    public function getFullMenu($place_code, $aParam = array()) {
	$aArray = array();
	$loader = $this->registery->get('load');
	$loader->model('catalog/menu');
	$results = $loader->model_catalog_menu->getMatrix($place_code);
	$html = '';
        //QS::d($results);
	//negetive counter setup to add root tree row
	$j = -1;

	$iMax = $results['levels'];
	$iRows = count($results['matrix']);
	$aChildWrapper = array();
	$aInnerWrapper = array();
	$aNameWrapper = array();
    $aStaticWrapper = array();
	if (isset($aParam['child_wrapper'])) {
	    $aChildWrapper = explode('{data}', $aParam['child_wrapper']);
	}
	if (isset($aParam['inner_wrapper'])) {
	    $aInnerWrapper = explode('{data}', $aParam['inner_wrapper']);
	}
	if (isset($aParam['name_wrapper'])) {
	    $aNameWrapper = explode('{data}', $aParam['name_wrapper']);
	}
    if (isset($aParam['static_block_wrapper'])) {
        $aStaticWrapper = explode('{data}', $aParam['static_block_wrapper']);
    }

	//$i = 0;
	// Itrating each rows 
	// Expected Array(0 => array(level0 => menu string, level1 => menu string ...), 1 => ...  )
	foreach ($results['matrix'] as $key => $aValues) {
	    // Get 1 column Columns i-e: level0
	    // Expecting menu string = id||link||type||order||name||attributes
	    $sMenu = $aValues['level0'];
	    $iLevel = 0;
	    // convert string to array
	    $aMenu = explode('||', $sMenu);
	    // d($aMenu,1);
	    // navigation class level wise
	    $sNav = 'nav-' . ($key + 1);
	    // adding class like first and last class
	    $cls = 'level0 ' . $sNav . ' ';
	    if ($key == 0)
		$cls = 'first';
	    else if (($iRows * $iMax) == ($key + 1))
		$cls = 'last';

	    if ((isset($aValues['level1']) && $aValues['level1']) || $aMenu[self::COLUMN_STATIC_BLOCK] != 0)
		$cls .= ' parent';

	    //$cls .= ' level-top';
	    if (isset($aParam['li_class'])) {
		$cls .= ' ' . $aParam['li_class'];
	    }

        if($aMenu[self::COLUMN_STATIC_BLOCK] == 0) {
            $cls .= ' ' . $aParam['no_static_class'];
        }

	    if (!empty($aMenu[self::COLUMN_ATTRIBUTE])) {
		$cls .= " " . $aMenu[self::COLUMN_ATTRIBUTE];
	    }

	    // generating html
	    if (count($aInnerWrapper) == 2) {
		$html .= str_replace(array('{class}', '{level}'), array($cls, $k), $aInnerWrapper[0]);
	    } else {
		$html .= '<li class="' . $cls . '"  >';
	    }


	    // getting link by type
	    if ($aMenu[self::COLUMN_TYPE] == 'external') {
			$link = $aMenu[self::COLUMN_LINK];
			if(strpos($link,'.com') === false) {
				$link = makeUrl($link,array(),true);
			}
	    }
	    else {
			$link = makeUrl($aMenu[self::COLUMN_LINK], array(), true);
	    }
	    // 
	    $sHrefCls = '';
	    if (isset($aParam['a_class'])) {
		$sHrefCls = $aParam['a_class'];
	    }
	    $metalink = QS::getMetaLink($aMenu[self::COLUMN_META_LINK], $aMenu[self::COLUMN_NAME]);
	    $html .= '<a href="' . $link . '" class="' . $sHrefCls . '" title="' . $metalink . '" alt="' . $metalink . '">';
	    $batch_html = "";
	    if (!empty($aMenu[self::COLUMN_BATCH])) {
		$batch_html = "<span class='cat-label cat-label-label" . self::$batch[$aMenu[self::COLUMN_BATCH]] . " pin-bottom'>" . $aMenu[self::COLUMN_BATCH] . "</span>";
	    }

	    if (count($aNameWrapper) == 2) {
		$html .= $aNameWrapper[0] . $aMenu[self::COLUMN_NAME] . " " . $batch_html . $aNameWrapper[1];
	    } else {
		$html .= $aMenu[self::COLUMN_NAME] . " " . $batch_html;
	    }
	    if (isset($aParam['parent_caret']) && ((isset($aValues['level1']) && $aValues['level1']) || $aMenu[self::COLUMN_STATIC_BLOCK] != 0)) {
		$html .= $aParam['parent_caret'];
//            QS::d($html);
	    }



	    $html .= '</a>';
	    // check and itrate through children 
	    if (isset($aValues['level1']) && $aValues['level1']) {
		for ($k = 1; $k <= $iMax; $k++) {
		    if (isset($aValues['level' . $k]) && $aValues['level' . $k]) {
			// get child menu string
			$sChild = $aValues['level' . $k];
			$aChild = explode('||', $sChild);
            $iLevel = $k;
                //QS::d($iLevel);
			// create child html
//			$sChildCls = 'level' . ($k - 1) . ' ';
            $sChildCls = 'level' . ($k - 1) . '-wrapper';
			if (count($aChildWrapper) == 2) {
			    $html .= str_replace(array('{class}', '{level}', '{old_level}'), array($sChildCls, $k, ($k - 1)), $aChildWrapper[0]);
			} else {
			    $html .= '<ul class="' . $sChildCls . '" style="display: none;">';
			}
			$sNav = 'nav-' . ($key + 1);
			if ($k > 1) {
			    for ($j = 1; $j <= $k; $j++) {
				$sNav .= '-' . $j;
			    }
			} else {
			    $sNav .= '-' . $k;
			}
			$cls = 'level' . $k . ' ' . $sNav . ' ';
			if (($iRows * $iMax) == ($key * $k))
			    $cls = 'last';

			if (isset($aValues['level' . ($k + 1)]) && $aValues['level' . ($k + 1)])
			    $cls .= ' parent';

			if (isset($aParam['li_child_class'])) {
			    $cls .= ' ' . $aParam['li_child_class'];
			}
			if (count($aInnerWrapper) == 2) {
			    $html .= str_replace(array('{class}', '{level}'), array($cls, $k), $aInnerWrapper[0]);
			} else {
			    $html .= '<li class="' . $cls . '" >';
			}
			if ($aChild[self::COLUMN_TYPE] == 'external')
			    $link = $aChild[self::COLUMN_LINK];
			else
			    $link = makeUrl($aChild[self::COLUMN_LINK], array(), true);
			$sHrefCls = '';
			if (isset($aParam['a_class'])) {
			    $sHrefCls = $aParam['a_class'];
			}
			if (isset($aParam['a_child_class'])) {
			    $sHrefCls .= ' ' . $aParam['a_child_class'];
			}
			$metalink = QS::getMetaLink($aChild[self::COLUMN_META_LINK], $aChild[self::COLUMN_NAME]);
			$html .= '<a href="' . $link . '" class="' . $sHrefCls . '" title="' . $metalink . '" alt="' . $metalink . '">';
			if (count($aNameWrapper) == 2) {
			    $html .= $aNameWrapper[0] . $aChild[self::COLUMN_NAME] . $aNameWrapper[1];
			} else {
			    $html .= $aChild[self::COLUMN_NAME];
			}

			if (isset($aParam['child_caret']) && isset($aValues['level' . ($k + 1)]) && $aValues['level' . ($k + 1)])
			    $html .= $aParam['child_caret'];

			$html .= '</a>';
			// check if it is last level or not have child then end,
			if ($iLevel == $iMax || (!isset($aValues['level' . ($k + 1)]) || !$aValues['level' . ($k + 1)])) {
			    if (count($aInnerWrapper) == 2) {
				$html .= $aInnerWrapper[1] . "\n";
			    } else {
				    $html .= '</li>' . "\n";
			    }
			}
			// closing child
			if (count($aChildWrapper) == 2) {
			    $html .= $aChildWrapper[1] . "\n";
			} else {
			    $html .= '</ul>' . "\n";
			}
			// close last parent
			if ($iLevel != 1 || !isset($aValues['level' . ($k - 1)])) {
			    if (count($aInnerWrapper) == 2) {
				$html .= $aInnerWrapper[1] . "\n";
			    } else {
				$html .= '</li>' . "\n";
			    }
			}
		    }
		}
	    }
        elseif($aMenu[self::COLUMN_STATIC_BLOCK] != 0) {
            if(isset($aParam['controller']) && $aParam['controller']) {
                $static = $aParam['controller']->load('information/information/loadInformation',array('id' => $aMenu[self::COLUMN_STATIC_BLOCK]));
                //d($aStaticWrapper,true);
                if(count($aStaticWrapper) == 2) {
                    $html .= $aStaticWrapper[0];
                    $html .= $static;
                    $html .= $aStaticWrapper[1];
                }
                else {
                    $html .= $static;
                }
            }
        }
	    if (count($aInnerWrapper) == 2) {
		$html .= $aInnerWrapper[1] . "\n";
	    } else {
		$html .= '</li>' . "\n";
	    }
	}
	return $html;
    }

    /**
     * Param string: $place_code; place_code
     * Return Array: $aArray; Array of Menu Tree
     */
    public function getMenuArray($place_code) {
	$aArray = array();
	$loader = $this->registery->get('load');
	$loader->model('catalog/menu');
	$results = $loader->model_catalog_menu->getMenuByPlaceCode($place_code);

	//negetive counter setup to add root tree row
	$j = -1;
	foreach ($results['matrix'] as $key => $value) {
	    //setup root tree
	    if (isset($value['level0']) && !isset($aArray[$j][$value['level0']])) {
		$aMenu = $loader->model_catalog_menu->getMenu($value['level0']);
		$aArray[++$j][$value['level0']] = $aMenu;
	    }
	    //getting complete child trees of each root tree
	    $aArray[$j][$value['level0']]['child'] = $this->getChild($value, $aArray[$j][$value['level0']], 1, $results['levels']);
	}

	return $aArray;
    }

    //recursive calling to filled up child tree
    public function getChild($levels, $parent, $level, $max_level) {
	$loader = $this->registery->get('load');
	$loader->model('catalog/menu');

	if (!isset($levels['level' . $level])) {
	    return array();
	}
	if (isset($parent['child'][$levels['level' . $level]])) {
	    $parent['child'][$levels['level' . $level]]['child'] = $this->getChild($levels, $parent['child'][$levels['level' . $level]], $level + 1, $max_level);
	    return $parent['child'];
	}
	$aMenu = $loader->model_catalog_menu->getMenu($levels['level' . $level]);

	$parent['child'][$levels['level' . $level]] = $aMenu;
	if ($level == $max_level) {
	    return $parent['child'];
	} else {
	    $parent['child'][$aMenu['menu_id']]['child'] = $this->getChild($levels, $parent['child'][$aMenu['menu_id']], $level + 1, $max_level);
	    return $parent['child'];
	}
    }

}

?>