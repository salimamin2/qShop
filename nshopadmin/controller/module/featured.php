<?php

class ControllerModuleFeatured extends Controller {

    private $error = array();

    public function index() {
	$this->load->language('module/featured');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('setting/setting');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
	    $this->request->post['featured_product'] = serialize($this->request->post['featured_product']);
	    Make::a('setting/setting')->create()->editSetting('featured', $this->request->post);
	    // $this->session->data['success'] = $this->language->get('text_success');
	    // $this->redirect(HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token']);
	    $this->data['success'] = $this->language->get('text_success');
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

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_cancel'] = $this->language->get('button_cancel');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token'],
	    'text' => $this->language->get('text_module'),
	    'separator' => ' :: '
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'module/featured&token=' . $this->session->data['token'],
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->data['action'] = HTTPS_SERVER . 'module/featured&token=' . $this->session->data['token'];

	$this->data['cancel'] = HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token'];

	if (isset($this->request->post['featured_limit'])) {
	    $this->data['featured_limit'] = $this->request->post['featured_limit'];
	} else {
	    $this->data['featured_limit'] = $this->config->get('featured_limit');
	}

	$this->data['positions'] = array();

	$this->data['positions'][] = array(
	    'position' => 'left',
	    'title' => $this->language->get('text_left'),
	);

	$this->data['positions'][] = array(
	    'position' => 'right',
	    'title' => $this->language->get('text_right'),
	);

	$this->data['positions'][] = array(
	    'position' => 'home',
	    'title' => $this->language->get('text_home'),
	);

	if (isset($this->request->post['featured_position'])) {
	    $this->data['featured_position'] = $this->request->post['featured_position'];
	} else {
	    $this->data['featured_position'] = $this->config->get('featured_position');
	}

	if (isset($this->request->post['featured_status'])) {
	    $this->data['featured_status'] = $this->request->post['featured_status'];
	} else {
	    $this->data['featured_status'] = $this->config->get('featured_status');
	}

	if (isset($this->request->post['featured_sort_order'])) {
	    $this->data['featured_sort_order'] = $this->request->post['featured_sort_order'];
	} else {
	    $this->data['featured_sort_order'] = $this->config->get('featured_sort_order');
	}

	$oModel = Make::a('catalog/product')->create();
	$this->data['products'] = $oModel->getProducts()->find_many(true);

	if (isset($this->request->post['featured_product'])) {
	    $this->data['featured_product'] = $this->request->post['featured_product'];
	} else {
	    $this->data['featured_product'] = $oModel->getFeaturedProducts();
	}


	$this->template = 'module/featured.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
	if (!$this->user->hasPermission('modify', 'module/featured')) {
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