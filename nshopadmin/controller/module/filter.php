<?php

class ControllerModuleFilter extends Controller {

    private $error = array();

    public function index() {
	$this->load->language('module/filter');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('setting/setting');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

	    //	$this->load->model('catalog/product');
//			$this->model_catalog_product->addFeatured($this->request->post);
	    $this->request->post['product_filter'] = serialize($this->request->post['product_filter']);
	    //unset($this->request->post['featured_product']);

	    Make::a('setting/setting')->create()->editSetting('product_filter', $this->request->post);

	    $this->session->data['success'] = $this->language->get('text_success');
	    $this->redirect(makeUrl('module/filter'));
	}
	if(isset($this->session->data['success']) && $this->session->data['success']){
		$this->data['success'] = $this->session->data['success'];
	}
	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_enabled'] = $this->language->get('text_enabled');
	$this->data['text_disabled'] = $this->language->get('text_disabled');
	$this->data['text_left'] = $this->language->get('text_left');
	$this->data['text_right'] = $this->language->get('text_right');
	$this->data['text_home'] = $this->language->get('text_home');

	$this->data['entry_limit'] = $this->language->get('entry_limit');
	$this->data['entry_position'] = $this->language->get('entry_position');
	$this->data['entry_status'] = $this->language->get('entry_status');
	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
	$this->data['entry_product'] = $this->language->get('entry_product');
	$this->data['entry_slot'] = $this->language->get('entry_slot');

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_cancel'] = $this->language->get('button_cancel');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl( 'common/home'),
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('extension/module'),
	    'text' => $this->language->get('text_module'),
	    'separator' => ' :: '
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('module/filter'),
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->data['action'] = makeUrl('module/filter');

	$this->data['cancel'] = makeUrl('extension/module');

	$this->data['filter_types'] = array(
	    'Select Option',
	    'Select Box',
	    'Text Box',
	    'Range',
	    'Checkbox',
	    'Radio Button',
	    'Dependend'
	);

	$this->data['fields'] = array(
	    //'category' => 'Category',
	    'manufacturer' => 'Designer',
	    //'designer' => 'Designer',
	    'country' => 'Country',
	    'price' => 'Price',
	    'option_color' => 'Color',
	    'option_colorsize' => 'Color/Size',
	    'option_size' => 'Size',
	);

	if (isset($this->request->post['product_filter'])) {
	    $this->data['filters'] = $this->request->post['product_filter'];
	} else if ($this->config->get('product_filter')) {
	    $this->data['filters'] = unserialize($this->config->get('product_filter'));
	} else {
	    $this->data['filters'] = array();
	}
	if (isset($this->request->post['product_filter_status'])) {
	    $this->data['product_filter_status'] = $this->request->post['product_filter_status'];
	} else if ($this->config->get('product_filter_status')) {
	    $this->data['product_filter_status'] = $this->config->get('product_filter_status');
	} else {
	    $this->data['product_filter_status'] = array();
	}


	$this->template = 'module/filter.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
	if (!$this->user->hasPermission('modify', 'module/filter')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>