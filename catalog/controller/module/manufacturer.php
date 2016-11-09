<?php

class ControllerModuleManufacturer extends Controller {

    protected function index() {
	$this->language->load('module/manufacturer');

	$this->data['heading_title'] = $this->language->get('heading_title');
	$this->data['text_select'] = $this->language->get('text_select');

	if (isset($this->request->get['manufacturer_id'])) {
	    $this->data['manufacturer_id'] = $this->request->get['manufacturer_id'];
	} else {
	    $this->data['manufacturer_id'] = 0;
	}

	$this->data['manufacturers'] = array();

	$results = Make::a('catalog/manufacturer')->create()->getManufacturers(0);

	foreach ($results as $result) {
	    if ($result && file_exists(DIR_IMAGE . $result['image'])) {
		$image = $result['image'];
	    } else {
		$image = 'no_image.jpg';
	    }
	    $this->data['manufacturers'][] = array(
		'manufacturer_id' => $result['manufacturer_id'],
		'name' => html_entity_decode($result['name']),
		'image' => HTTPS_IMAGE . $image,
//		'href' => makeUrl('product/manufacturer', array('manufacturer_id=' . $result['manufacturer_id']), true)
		'href' => makeUrl('product/search', array('keyword= ', 'filter[manufacturer][]=' . $result['manufacturer_id']), true)
	    );
	}

	$this->id = 'manufacturer';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturer.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/manufacturer.tpl';
	} else {
	    $this->template = 'default/template/module/manufacturer.tpl';
	}

	$this->render();
    }

}

?>