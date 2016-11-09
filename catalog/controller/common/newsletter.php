<?php

class ControllerCommonNewsletter extends Controller {

    private $error = '';

    protected function index() {

	$this->language->load('common/newsletter');

	$this->data['heading_title'] = $this->language->get('heading_title');
	$this->data['text_label'] = $this->language->get('text_label');
	$this->data['text_button'] = $this->language->get('text_button');

	$this->load->model('account/customer');

	$this->data['error_warning'] = $this->error_warning;
	$this->data['error_email'] = $this->error_email;
	$this->data['msg_success'] = $this->language->get('msg_success');

	$this->id = 'newsletter';
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/newsletter.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/common/newsletter.tpl';
	} else {
	    $this->template = 'default/template/common/newsletter.tpl';
	}

	$this->render();
    }
    
    public function insert() {
		$this->load->model('account/customer');
		$this->language->load('common/newsletter');
		$aData = array();
		$email = $this->request->post['email'];
		if ($this->customer->isLogged() && $email == $this->customer->getEmail()) {
		    $this->model_account_customer->editNewsletter(1);
		    $aData['success'] = $this->language->get('text_success');
		} else {
		    if ($this->validate()) {
				$this->model_account_customer->addNewsletter($this->request->post);
				$aData['success'] = $this->language->get('text_success');
		    } else {
				$aData['error'] = $this->error['message'];
		    }
		}
		echo json_encode($aData);
		exit();
    }

    private function validate() {
	if (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', $this->request->post['email'])) {
	    $this->error['message'] = $this->language->get('error_email');
	}
	if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
	    $this->error['message'] = $this->language->get('error_exists');
	}

	if (!$this->error) {

	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>