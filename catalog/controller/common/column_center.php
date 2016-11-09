<?php

class ControllerCommonColumnCenter extends Controller {

    protected function index() {
	$module_data = array();

	$this->load->model('checkout/extension');

	$results = $this->model_checkout_extension->getExtensions('module');

	foreach ($results as $result) {
	    if ($this->config->get($result['key'] . '_status') && ($this->config->get($result['key'] . '_position') == 'center' || $this->config->get($result['key'] . '_position') == 'home')) {
		$code = $result['key'];
		$param = array();
		if (stristr($result['key'], 'home_banner_') !== false) {
		    $code = 'home_banner';
		    $param = array('key' => str_replace('home_banner_', '', $result['key']));
		}
		$module_data[] = array(
		    'code' => $code,
		    'param' => $param,
		    'sort_order' => $this->config->get($result['key'] . '_sort_order')
		);
	    }
	}
	$sort_order = array();

	foreach ($module_data as $key => $value) {
	    $sort_order[$key] = $value['sort_order'];
	}

	array_multisort($sort_order, SORT_ASC, $module_data);

	$this->data['modules'] = $module_data;

	$this->id = 'column_center';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_center.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/common/column_center.tpl';
	} else {
	    $this->template = 'default/template/common/column_center.tpl';
	}

	$this->render();
    }

}

?>