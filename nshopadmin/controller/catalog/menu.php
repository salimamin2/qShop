<?php

class ControllerCatalogMenu extends Controller {

    private $error = array();

    public function index() {
	$this->load->language('catalog/menu');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('catalog/menu');

	$this->getList();
    }

    public function insert() {
	$this->load->language('catalog/menu');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('catalog/menu');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

	    $this->model_catalog_menu->addMenu($this->request->post);

	    $this->session->data['success'] = $this->language->get('text_success');

	    $this->redirect(HTTPS_SERVER . 'catalog/menu&token=' . $this->session->data['token']);
	}

	$this->getForm();
    }

    public function update() {
	$this->load->language('catalog/menu');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('catalog/menu');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	    $this->model_catalog_menu->editMenu($this->request->get['menu_id'], $this->request->post);

	    $this->session->data['success'] = $this->language->get('text_success');

	    $this->redirect(HTTPS_SERVER . 'catalog/menu&token=' . $this->session->data['token']);
	}

	$this->getForm();
    }

    public function delete() {
	$this->load->language('catalog/menu');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('catalog/menu');

	if (isset($this->request->post['selected']) && $this->validateDelete()) {
	    foreach ($this->request->post['selected'] as $menu_id) {
		$this->model_catalog_menu->deleteMenu($menu_id);
	    }

	    $this->session->data['success'] = $this->language->get('text_deleted');

	    $this->redirect(HTTPS_SERVER . 'catalog/menu&token=' . $this->session->data['token']);
	}

	$this->getList();
    }

    private function getList() {
	if (isset($this->request->get['filter_place_code'])) {
	    $filter_place_code = $this->request->get['filter_place_code'];
	} else {
	    $filter_place_code = NULL;
	}

	$url = '';

	if (isset($this->request->get['filter_place_code'])) {
	    $url .= '&filter_place_code=' . $this->request->get['filter_place_code'];
	}

	$this->data['aBlocks'] = array(
	    'top-menu' => 'Header Menu',
	    'top-quick-menu' => 'Top Menu',
	    'footer-menu' => 'Footer Menu',
	    'footer-menu-1' => 'Footer Menu 1',
	    'footer-menu-2' => 'Footer Menu 2',
	    'footer-menu-3' => 'Footer Menu 3',
	    'footer-menu-4' => 'Footer Menu 4',
	    'footer-menu-5' => 'Footer Menu 5',
	    'left-menu' => 'Left Help Menu',
	    'left-pages-menu' => 'Left Column Menu',
	    'right-menu' => 'Right Menu',
	    'product' => 'Product Description',
	    'account' => 'Create Account',
	    'checkout' => 'Checkout',
	    'mobile-menu' => 'Mobile Menu'
	);

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'catalog/menu&token=' . $this->session->data['token'],
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->data['insert'] = HTTPS_SERVER . 'catalog/menu/insert&token=' . $this->session->data['token'] . $url;
	$this->data['delete'] = HTTPS_SERVER . 'catalog/menu/delete&token=' . $this->session->data['token'] . $url;

	$this->data['menus'] = array();

	$data = array(
	    'filter_place_code' => $filter_place_code
	);

	$results = $this->model_catalog_menu->getMenus(0, $data);

	foreach ($results as $result) {
	    $action = array();

	    $action[] = array(
		'text' => $this->language->get('text_edit'),
		'icon' => 'icon-pencil',
        'class' => 'btn-info',
		'href' => HTTPS_SERVER . 'catalog/menu/update&token=' . $this->session->data['token'] . '&menu_id=' . $result['menu_id'] . $url
	    );

	    $this->data['menus'][] = array(
		'menu_id' => $result['menu_id'],
		'name' => $result['name'],
		'place_code' => $this->data['aBlocks'][$result['place_code']],
		'sort_order' => $result['sort_order'],
		'selected' => isset($this->request->post['selected']) && in_array($result['menu_id'], $this->request->post['selected']),
		'action' => $action
	    );
	}

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_no_results'] = $this->language->get('text_no_results');

	$this->data['column_menu_code'] = $this->language->get('column_menu_code');
	$this->data['menu_code'] = $this->language->get('menu_code');
	$this->data['column_name'] = $this->language->get('column_name');
	$this->data['column_place_code'] = $this->language->get('column_place_code');
	$this->data['column_sort_order'] = $this->language->get('column_sort_order');
	$this->data['column_action'] = $this->language->get('column_action');

	$this->data['button_insert'] = $this->language->get('button_insert');
	$this->data['button_delete'] = $this->language->get('button_delete');
	$this->data['button_filter'] = $this->language->get('button_filter');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	if (isset($this->session->data['success'])) {
	    $this->data['success'] = $this->session->data['success'];

	    unset($this->session->data['success']);
	} else {
	    $this->data['success'] = '';
	}

	$url = '';

	if (isset($this->request->get['filter_place_code'])) {
	    $url .= '&filter_place_code=' . $this->request->get['filter_place_code'];
	}

	$this->data['filter_place_code'] = $filter_place_code;
	$this->data['token'] = $this->session->data['token'];

	$this->template = 'catalog/menu_list.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function getForm() {
	if (isset($this->request->get['filter_place_code'])) {
	    $filter_place_code = $this->request->get['filter_place_code'];
	} else {
	    $filter_place_code = NULL;
	}

	$url = '';

	if (isset($this->request->get['filter_place_code'])) {
	    $url .= '&filter_place_code=' . $this->request->get['filter_place_code'];
	}

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_none'] = $this->language->get('text_none');
	$this->data['text_default'] = $this->language->get('text_default');
	$this->data['text_image_manager'] = $this->language->get('text_image_manager');
	$this->data['text_enabled'] = $this->language->get('text_enabled');
	$this->data['text_disabled'] = $this->language->get('text_disabled');

	$this->data['entry_name'] = $this->language->get('entry_name');
	$this->data['entry_meta_keywords'] = $this->language->get('entry_meta_keywords');
	$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
	$this->data['entry_description'] = $this->language->get('entry_description');
	$this->data['entry_store'] = $this->language->get('entry_store');
	$this->data['entry_keyword'] = $this->language->get('entry_keyword');
	$this->data['entry_menu'] = $this->language->get('entry_menu');
	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
	$this->data['entry_image'] = $this->language->get('entry_image');
	$this->data['entry_status'] = $this->language->get('entry_status');
	$this->data['entry_menu_code'] = $this->language->get('entry_menu_code');
    $this->data['entry_batch'] = $this->language->get('entry_batch');
    $this->data['entry_meta_link'] = $this->language->get('entry_meta_link');

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_cancel'] = $this->language->get('button_cancel');

	$this->data['tab_general'] = $this->language->get('tab_general');
	$this->data['tab_data'] = $this->language->get('tab_data');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	if (isset($this->error['name'])) {
	    $this->data['error_name'] = $this->error['name'];
	} else {
	    $this->data['error_name'] = '';
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'catalog/menu&token=' . $this->session->data['token'],
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	if (!isset($this->request->get['menu_id'])) {
	    $this->data['action'] = HTTPS_SERVER . 'catalog/menu/insert&token=' . $this->session->data['token'] . $url;
	} else {
	    $this->data['action'] = HTTPS_SERVER . 'catalog/menu/update&token=' . $this->session->data['token'] . '&menu_id=' . $this->request->get['menu_id'] . $url;
	}

	$this->data['cancel'] = HTTPS_SERVER . 'catalog/menu&token=' . $this->session->data['token'] . $url;

	$this->data['token'] = $this->session->data['token'];

	if (isset($this->request->get['menu_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
	    $menu_info = $this->model_catalog_menu->getMenu($this->request->get['menu_id']);
	}

	$this->data['languages'] = Make::a('localisation/language')->find_many(true);

	if (isset($this->request->post['menu_description'])) {
	    $this->data['menu_description'] = $this->request->post['menu_description'];
	} elseif (isset($menu_info)) {
	    $this->data['menu_description'] = $this->model_catalog_menu->getMenuDescriptions($this->request->get['menu_id']);
	} else {
	    $this->data['menu_description'] = array();
	}

	if (isset($this->request->post['status'])) {
	    $this->data['status'] = $this->request->post['status'];
	} elseif (isset($menu_info)) {
	    $this->data['status'] = $menu_info['status'];
	} else {
	    $this->data['status'] = 1;
	}

	$this->data['menus'] = $this->model_catalog_menu->getMenus(0);

	if (isset($this->request->post['menu_id'])) {
	    $this->data['menu_id'] = $this->request->post['menu_id'];
	} elseif (isset($menu_info)) {
	    $this->data['menu_id'] = $menu_info['menu_id'];
	} else {
	    $this->data['menu_id'] = 0;
	}

    if (isset($this->request->post['static_block'])) {
        $this->data['static_block'] = $this->request->post['static_block'];
    } elseif (isset($menu_info)) {
        $this->data['static_block'] = html_entity_decode($menu_info['static_block']);
    } else {
        $this->data['static_block'] = "";
    }

	if (isset($this->request->post['parent_id'])) {
	    $this->data['parent_id'] = $this->request->post['parent_id'];
	} elseif (isset($menu_info)) {
	    $this->data['parent_id'] = $menu_info['parent_id'];
	} else {
	    $this->data['parent_id'] = 0;
	}

	if (isset($this->request->post['place_code'])) {
	    $this->data['place_code'] = $this->request->post['place_code'];
	} elseif (isset($menu_info)) {
	    $this->data['place_code'] = $menu_info['place_code'];
	} else {
	    $this->data['place_code'] = '';
	}

	if (isset($this->request->post['sort_order'])) {
	    $this->data['sort_order'] = $this->request->post['sort_order'];
	} elseif (isset($menu_info)) {
	    $this->data['sort_order'] = $menu_info['sort_order'];
	} else {
	    $this->data['sort_order'] = 0;
	}

	if (isset($this->request->post['link'])) {
	    $this->data['link'] = $this->request->post['link'];
	} elseif (isset($menu_info)) {
	    $this->data['link'] = $menu_info['link'];
	} else {
	    $this->data['link'] = '';
	}

	if (isset($this->request->post['link_type'])) {
	    $this->data['link_type'] = $this->request->post['link_type'];
	} elseif (isset($menu_info)) {
	    $this->data['link_type'] = $menu_info['link_type'];
	} else {
	    $this->data['link_type'] = '';
	}

	// List - Information Pages
	$this->load->model('catalog/information');
	$this->data['aInformations'] = array();
	$results = $this->model_catalog_information->getInformations();
	foreach ($results as $result) {
	    $this->data['aInformations'][] = array(
		'information_id' => $result['information_id'],
		'title' => $result['title'],
		'link' => 'information/information?information_id=' . $result['information_id']
	    );
	}

	// List - Products
	$this->data['aProducts'] = array();
	$results = array();
	$results = Make::a('catalog/product_description')->find_many(true);
	foreach ($results as $result) {
	    $this->data['aProducts'][] = array(
		'product_id' => $result['product_id'],
		'title' => $result['name'],
		'link' => 'product/product?product_id=' . $result['product_id'],
	    );
	}

	// List - Categorys
	$this->data['aCategory'] = array();
	$results = array();
	$results = Make::a('catalog/category')->create()->getCategories(0);
	foreach ($results as $result) {
	    $this->data['aCategories'][] = array(
		'category_id' => $result['category_id'],
		'title' => $result['name'],
		'link' => 'product/category?path=' . $result['category_id'],
	    );
	}

	$this->data['aBlocks'] = array(
	    'top-menu' => 'Header Menu',
	    'top-quick-menu' => 'Top Menu',
	    'footer-menu' => 'Footer Menu',
	    'footer-menu-1' => 'Footer Menu 1',
	    'footer-menu-2' => 'Footer Menu 2',
	    'footer-menu-3' => 'Footer Menu 3',
	    'footer-menu-4' => 'Footer Menu 4',
	    'footer-menu-5' => 'Footer Menu 5',
	    'left-menu' => 'Left Help Menu',
	    'left-pages-menu' => 'Left Column Menu',
	    'right-menu' => 'Right Menu',
	    'product' => 'Product Description',
	    'account' => 'Create Account',
	    'checkout' => 'Checkout',
	    'mobile-menu' => 'Mobile Menu'
	);

    $this->data['aBatches'] = array('New','Hot','Sale','Featured','Popular');

	$url = '';

	if (isset($this->request->get['filter_place_code'])) {
	    $url .= '&filter_place_code=' . $this->request->get['filter_place_code'];
	}

	$this->data['filter_place_code'] = $filter_place_code;
//    d($this->data,true);
	$this->template = 'catalog/menu_form.tpl';
	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validateForm() {
	if (!$this->user->hasPermission('modify', 'catalog/menu')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}

	foreach ($this->request->post['menu_description'] as $language_id => $value) {
	    if ((strlen(utf8_decode($value['name'])) < 2) || (strlen(utf8_decode($value['name'])) > 255)) {
		$this->error['name'][$language_id] = $this->language->get('error_name');
	    }
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    if (!isset($this->error['warning'])) {
		$this->error['warning'] = $this->language->get('error_required_data');
	    }

	    return FALSE;
	}
    }

    private function validateDelete() {
	if (!$this->user->hasPermission('modify', 'catalog/menu')) {
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