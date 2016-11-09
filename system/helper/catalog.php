<?php
class HelperCatalog {
  public $category_id = 0;
  public $path = array( );
  public $level = 0;
  protected $registery;
  public function __construct($registery) {
    $this->registery = $registery;
  }
  /**
  * Param int: $parent_id; parent id
  * Param string: $current_path current path
  * Return string: $output;
  */
  public function getCategories($parent_id, $current_path = '', $aPath = array( ), $iCategory_id = 0) {
    $category_id = array_shift($aPath);
    $output = '';
    $loader = $this->registery->get('load');
    $results = Make::a('catalog/category')->create()->getCategories($parent_id);
    if ( $results ) {
        $output .= '<ul';
        if ( $current_path == '' ){
            $output .= ' id="categoryy-nav" class="catMenu"';
        }
        $output .= ' >';
    }
    $i = 0;
    foreach ( $results as $result ) {
      if ( !$current_path ) {
        $new_path = $result['category_id'];
      }
      else {
        $new_path = $current_path . '_' . $result['category_id'];
      }
      $hasChildren = Make::a('catalog/category')->create()->getTotalCategoriesByCategoryId($result['category_id']);
      $output .= '<li class="';
      $children = $this->getCategories($result['category_id'], $new_path, $aPath, $iCategory_id);
      $output .= $result['ref_category_code'] . ' ';
      if ( $hasChildren != 0 )
        $output .= 'level-0';
      else {
        if($current_path != ''){
            $output .= 'level-' . $this->level .' child';
        } else {
            $output .= 'level-0';
        }
      }
      if ( $iCategory_id == $result['category_id'] ) {
        $output .= ' active';
      }
      if ( $hasChildren != 0 ) {
//        $output .= ' nav-' . ( ( int ) $this->level + ( int ) $i );
       // if ( $current_path )
  //        $output .= '-' . $new_path;
        $output .= ' parent';
        // $output .= 'onmouseout="toggleMenu(this,0)" onmouseover="toggleMenu(this,1)"';
      }
      else {
 //       $output .= ' nav-' . ( ( int ) $this->level + ( int ) $i ) . '" ';
      }
      $output .= '" >';
      $output .= '<a href="' . $loader->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/category&amp;path=' . $new_path) . '"><span>' . $result['name'] . '</span></a>';
      if ( $hasChildren != 0 ) {
        $output .= $children;
      }
      $output .= '</li>';
      $i++;
    }
    if ( $results ) {
      $output .= '</ul>';
    }
    $this->level++;
    return $output;
  }
  /**
  * Param int: $parent_id; parent id
  * Param string: $current_path current path
  * Return Array: $aCategories; Array of Categories
  */
  public function getCategoriesArray($parent_id, $current_path = '') {
    $category_id = array_shift($this->path);
    $aCategories = array( );
    $loader = $this->registery->get('load');
    $loader->model('catalog/category');
    $results = Make::a('catalog/category')->create()->getCategories($parent_id);
    $i = 0;
    foreach ( $results as $result ) {
      if ( !$current_path ) {
        $new_path = $result['category_id'];
      }
      else {
        $new_path = $current_path . '_' . $result['category_id'];
      }
      $hasChildren = Make::a('catalog/category')->create()->getTotalCategoriesByCategoryId($result['category_id']);
      $children = $this->getCategoriesArray($result['category_id'], $new_path);
      $aCategories[$i]['link'] = $loader->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/category&amp;path=' . $new_path);
      $aCategories[$i]['name'] = $result['name'];
      $aCategories[$i]['child'] = $children;
      $i++;
    }
    return $aCategories;
  }

  public static function generateButtonHtml($options,$dependedType = 1) {
      $html = '';
      foreach($options as $option) {
          $html .= '<div><label class="required"><em>*</em> Select ' . $option['name'] . ':</label></div>';
          $html .= '<div>';
          foreach($option['option_value'] as $value) {
              $html .= '<span ' . ($option['isDependent'] && $dependedType == 2 ? 
                'class="value-index" id="value_index_' . $value['option_value_id'] . '"' : '') . '>';
              $html .= '<a href="javascript:void(0)" class="swatch ' . ($option['isDependent'] ? ($dependedType == 1 ? 'color-swatch' : 'dependent-value btn-option') : 'btn-option') . '" 
                    alt="' . $value['name'] . '" title="' . $value['name']. '" data-option-id="' . $option['option_id']. '" rel="' . (isset($value['index']) ? $value['index'] : '') . '" data-value="' . $value['option_value_id'] . '">';       
              if($dependedType == 1) {
                  if ($value['thumb'])
                      $html .= '<img src="' . $value['thumb'] . '" alt="' . $value['name'] . '" />';
                  else
                      $html .= '<span rel="' . $value['name'] . '" style="background:' . str_replace(' ','',strtolower($value['name'])) . '">' . $value['name'] . '</span>';
              }
              else {
                $html .= '<span rel="' . $value['option_value_id'] . '">' . $value['name'] . '</span>';
              }
              $html .= '</a></span>';
          }
          $html .= '<div class="clearfix"></div></div>';
      }
      return $html;
  }
}
?>