<?php

class ControllerModuleCategory extends Controller {

    protected $category_id = 0;
    protected $path = array();
    protected $level = 0;

    protected function index() {
	$this->language->load('module/category');
	$this->load->helper_obj('catalog');
//	$this->data['heading_title'] = $this->language->get('heading_title');


	$this->load->model('tool/seo_url');
	/*$this->data['categories'] = array();
	if (isset($this->request->get['path'])) {
	    $this->path = explode('_', $this->request->get['path']);

//	    $this->category_id = array_shift($this->path);
	    $this->category_id = array_pop($this->path);
	    $oCat = Make::a('catalog/category')->create()->getCategory($this->category_id);
	    $this->data['parent_title'] = html_entity_decode($oCat->name);

	    $cats = Make::a('catalog/category')->create()->getChildCategories($this->category_id);
//	    d($cats);

	    $path = '';
	    foreach ($cats as $result) {
		$childrens = array();
		if ($result['child']) {
		    foreach ($result['child'] as $children) {
			$path = $children['parent_id'] ? $result['parent_id'] . '_' . $children['parent_id'] . '_' . $children['category_id'] : $result['parent_id'] . '_' . $children['category_id'];
			$childrens[] = array(
			    'category_id' => $children['category_id'],
			    'name' => html_entity_decode($children['name']),
			    'href' => makeUrl('product/category', array('path=' . $path), true),
			    'title' => $children['meta_link'] ? metaLink($children['meta_link'], false, $children) : $children['name']
			);
		    }
		}
		$path = $result['parent_id'] ? $result['parent_id'] . '_' . $result['category_id'] : $result['category_id'];
		$this->data['categories'][] = array(
		    'category_id' => $result['category_id'],
		    'name' => html_entity_decode($result['name']),
		    'href' => makeUrl('product/category', array('path=' . $path), true),
		    'title' => $result['meta_link'] ? metaLink($result['meta_link'], false, $result) : $result['name'],
		    'child' => $childrens
		);
	    }
	}*/
	$this->data['category'] = $this->helper_catalog->getCategories(0);

	$this->id = 'category';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/category.tpl';
	} else {
	    $this->template = 'default/template/module/category.tpl';
	}

	$this->render();
    }

}

?>