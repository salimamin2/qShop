<?php

class ControllerModuleMenu extends Controller {

    private $error = array();

    public function index() {
	$this->load->language('module/menu');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('setting/setting');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
	    if (isset($this->request->post['menu_data']) && $this->request->post['menu_data']) {
		$this->request->post['menu_data'] = serialize($this->request->post['menu_data']);
	    }
	    Make::a('setting/setting')->create()->editSetting('menu', $this->request->post);

	    // $this->session->data['success'] = $this->language->get('text_success');

	    // $this->redirect(makeUrl('extension/module'));
	    $this->data['success'] = $this->language->get('text_success');
	}

	$this->data['heading_title'] = $this->language->get('heading_title');


	$this->data['text_left'] = $this->language->get('text_left');
	$this->data['text_right'] = $this->language->get('text_right');
	$this->data['text_top'] = $this->language->get('text_top');

	$this->data['entry_position'] = $this->language->get('entry_position');
	$this->data['entry_status'] = $this->language->get('entry_status');
	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_cancel'] = $this->language->get('button_cancel');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home'),
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('extension/module'),
	    'text' => $this->language->get('text_module'),
	    'separator' => ' :: '
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('module/menu'),
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->data['action'] = makeUrl('module/menu');

	$this->data['cancel'] = makeUrl('extension/module');

	if (isset($this->request->post['menu_data'])) {
	    $this->data['menu_data'] = $this->request->post['menu_data'];
	} else {
	    $this->data['menu_data'] = unserialize($this->config->get('menu_data'));
	}

	$this->load->model('catalog/menu');
	$this->data['aPlaceCodes'] = $this->model_catalog_menu->getPlaceCodes();

	$this->data['aPositions'] = array(
	    'top_menu',
	    'main_menu',
	    'left_menu',
	    'right_menu',
	    'footer_menu',
	    'bottom_middle_menu',
	    'bottom_menu'
	);

	$this->data['aStatus'] = array(
	    $this->language->get('text_disabled'),
	    $this->language->get('text_enabled')
	);


	$this->template = 'module/menu.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
	if (!$this->user->hasPermission('modify', 'module/menu')) {
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