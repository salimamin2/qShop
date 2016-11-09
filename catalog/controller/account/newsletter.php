<?php

class ControllerAccountNewsletter extends Controller {

    private $error = array();

    public function index() {

	$this->language->load('account/newsletter');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('account/customer');
	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
	    if ($this->customer->isLogged()) {
		$this->model_account_customer->editNewsletter($this->request->post['newsletter']);
		$this->session->data['success'] = $this->language->get('text_success');
		$this->redirect(makeUrl('account/account', array(), true, true));
	    } else {
		if ($this->validate()) {
		    $this->model_account_customer->addNewsletter($this->request->post);
		    $this->session->data['success'] = $this->language->get('text_success');
		}
	    }
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
	    'href' => makeUrl('account/newsletter', array(), true, true),
	    'text' => $this->language->get('text_newsletter'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_yes'] = $this->language->get('text_yes');
	$this->data['text_no'] = $this->language->get('text_no');

	$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
	$this->data['entry_name'] = $this->language->get('entry_name');
	$this->data['entry_email'] = $this->language->get('entry_email');

	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['button_back'] = $this->language->get('button_back');

	$this->data['action'] = makeUrl('account/newsletter', array(), true, true);

	$this->data['newsletter'] = $this->customer->getNewsletter();

	if (isset($this->error['message'])) {
	    $this->data['error'] = $this->error['message'];
	} else {
	    $this->data['error'] = '';
	}

	if (isset($this->session->data['success'])) {
	    $this->data['success'] = $this->session->data['success'];
	    unset($this->session->data['success']);
	} else {
	    $this->data['success'] = '';
	}

	$this->data['back'] = makeUrl('account/account', array(), true, true);

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/account/newsletter.tpl';
	} else {
	    $this->template = 'default/template/account/newsletter.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
	if (!isset($this->request->post['email'])) {
	    $this->error['message'] = $this->language->get('error_email');
	}
	if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email']) > 0) {
	    $this->error['message'] = $this->language->get('error_email');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>