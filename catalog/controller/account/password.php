<?php

class ControllerAccountPassword extends Controller {

    private $error = array();

    public function index() {
	if (!$this->customer->isLogged()) {
	    $this->session->data['redirect'] = makeUrl('account/password', array(), true, true);
	    $this->redirect(makeUrl('account/login', array(), true, true));
	}

	$this->language->load('account/password');

	$this->document->title = $this->language->get('heading_title');
	$this->document->layout_col = "col2-left-layout";

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	    $this->load->model('account/customer');

	    $this->model_account_customer->editPassword($this->customer->getEmail(), $this->request->post['password']);

	    $this->session->data['success'] = $this->language->get('text_success');

	    $this->redirect(makeUrl('account/account', array(), true, true));
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home', array(), true),
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('account/account', array(), true, true),
	    'text' => $this->language->get('text_account'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('account/password', array(), true, true),
	    'text' => $this->language->get('heading_title'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_password'] = $this->language->get('text_password');

	$this->data['entry_password'] = $this->language->get('entry_password');
	$this->data['entry_confirm'] = $this->language->get('entry_confirm');

	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['button_back'] = $this->language->get('button_back');

	if (isset($this->error['password'])) {
	    $this->data['error_password'] = $this->error['password'];
	} else {
	    $this->data['error_password'] = '';
	}

	if (isset($this->error['confirm'])) {
	    $this->data['error_confirm'] = $this->error['confirm'];
	} else {
	    $this->data['error_confirm'] = '';
	}

	$this->data['action'] = makeUrl('account/password', array(), true, true);

	if (isset($this->request->post['password'])) {
	    $this->data['password'] = $this->request->post['password'];
	} else {
	    $this->data['password'] = '';
	}

	if (isset($this->request->post['confirm'])) {
	    $this->data['confirm'] = $this->request->post['confirm'];
	} else {
	    $this->data['confirm'] = '';
	}

	$this->data['back'] = makeUrl('account/account', array(), true, true);
	$this->document->addScriptInline('var dataForm = new VarienForm("password",true);', Document::POS_END);

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/password.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/account/password.tpl';
	} else {
	    $this->template = 'default/template/account/password.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
	if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
	    $this->error['password'] = $this->language->get('error_password');
	}

	if ($this->request->post['confirm'] != $this->request->post['password']) {
	    $this->error['confirm'] = $this->language->get('error_confirm');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>
