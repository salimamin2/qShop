<?php

class ControllerCommonColumnLeft extends Controller {

    protected function index() {
	$module_data = array();
	$this->load->model('checkout/extension');
	$results = $this->model_checkout_extension->getExtensions('module');

	foreach ($results as $result) {
	    if ($this->config->get($result['key'] . '_status') && (($this->config->get($result['key'] . '_position') == 'left') || $result['key'] == 'left_banner' )) {
		$code = $result['key'];
		$param = array();
		if (stristr($result['key'], 'home_banner_') !== false) {
		    $code = 'home_banner';
		    $param = array('key' => str_replace('home_banner_', '', $result['key']));
		}
		$module_data[] = array(
		    'code' => $code,
		    'param' => $param,
		    'sort_order' => ($this->config->get($result['key'] . '_sort_order') >= 0 ? $this->config->get($result['key'] . '_sort_order') : 1)
		);
	    }
	}

	$sort_order = array();

	foreach ($module_data as $key => $value) {
	    $sort_order[$key] = $value['sort_order'];
	}

	array_multisort($sort_order, SORT_ASC, $module_data);

	$this->data['modules'] = $module_data;
	$this->data['language_code'] = $this->session->data['language'];

	$this->load->model('localisation/language');

	$this->data['languages'] = array();

	$results = $this->model_localisation_language->getLanguages();

	foreach ($results as $result) {
	    if ($result['status']) {
		$this->data['languages'][] = array(
		    'name' => $result['name'],
		    'code' => $result['code'],
		    'image' => $result['image']
		);
	    }
	}

	$this->data['currency_code'] = $this->currency->getCode();

	$this->load->model('localisation/currency');

	$this->data['currencies'] = array();

	$results = $this->model_localisation_currency->getCurrencies();

	foreach ($results as $result) {
	    if ($result['status']) {
		$this->data['currencies'][] = array(
		    'title' => $result['title'],
		    'code' => $result['code']
		);
	    }
	}
	if (isset($this->request->get['path'])) {
	    if (strstr($this->request->get['path'], '_') && $this->request->get['path'] !== '') {
		$cat_id = explode('_', $this->request->get['path']);
		$categories = Make::a('catalog/category')->create()->getChildCategories($cat_id[0]);
		$cat_id = $cat_id[0];
	    } else {
		$cat_id = $this->request->get['path'];
		$categories = Make::a('catalog/category')->create()->getChildCategories($cat_id);
	    }
	    $this->data['childcategories'] = $categories;
	    $this->data['parentTitle'] = Make::a('catalog/category')->create()->getCategoryTitle($cat_id);
	}



	//d($this->data['childcategories']);

	$this->id = 'column_left';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_left.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/common/column_left.tpl';
	} else {
	    $this->template = 'default/template/common/column_left.tpl';
	}

	$this->render();
    }

}

?>