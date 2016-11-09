<?php

class ControllerAccountEdit extends Controller {

    private $error = array();

    public function index() {
	if (!$this->customer->isLogged()) {
	    $this->session->data['redirect'] = makeUrl('account/account', array(), true, true);
	    $this->redirect(makeUrl('account/login', array(), true, true));
	}

	$this->document->layout_col = "col2-left-layout";

	$this->language->load('account/edit');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('account/customer');

	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		if($this->validate()) {
			//d($this->request->post,1);
		    $this->model_account_customer->editCustomer($this->request->post);
	    	$this->session->data['success'] = __('The account information has been saved');
		}
		else {
			$this->session->data['errors'] = $this->error;
			$this->session->data['tab'] = 'tab2';
		}
	    $this->redirect(makeUrl('account/account', array(), true, true));
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home', array(), true),
	    'text' => $this->language->get('text_home'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('account/account', array(), true, true),
	    'text' => $this->language->get('text_account'),
	    'separator' => false//$this->language->get('text_separator')
	);

	$this->data['text_your_details'] = $this->language->get('text_your_details');

	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
	$this->data['entry_email'] = $this->language->get('entry_email');
	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
	$this->data['entry_fax'] = $this->language->get('entry_fax');
	$this->data['entry_lcn'] = $this->language->get('entry_lcn');

	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['button_back'] = $this->language->get('button_back');

	$this->data['errors'] = $this->error;

	$this->data['action'] = makeUrl('account/edit', array(), true, true);

	if ($this->request->server['REQUEST_METHOD'] != 'POST') {
	    $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
	    $this->load->model('account/customer');
	}

	if (isset($this->request->post['firstname'])) {
	    $this->data['firstname'] = $this->request->post['firstname'];
	} elseif (isset($customer_info)) {
	    $this->data['firstname'] = $customer_info['firstname'];
	} else {
	    $this->data['firstname'] = '';
	}

	if (isset($this->request->post['lastname'])) {
	    $this->data['lastname'] = $this->request->post['lastname'];
	} elseif (isset($customer_info)) {
	    $this->data['lastname'] = $customer_info['lastname'];
	} else {
	    $this->data['lastname'] = '';
	}

	if (isset($this->request->post['email'])) {
	    $this->data['email'] = $this->request->post['email'];
	} elseif (isset($customer_info)) {
	    $this->data['email'] = $customer_info['email'];
	} else {
	    $this->data['email'] = '';
	}

	if (isset($this->request->post['telephone'])) {
	    $this->data['telephone'] = $this->request->post['telephone'];
	} elseif (isset($customer_info)) {
	    $this->data['telephone'] = $customer_info['telephone'];
	} else {
	    $this->data['telephone'] = '';
	}

	if (isset($this->request->post['fax'])) {
	    $this->data['fax'] = $this->request->post['fax'];
	} elseif (isset($customer_info)) {
	    $this->data['fax'] = $customer_info['fax'];
	} else {
	    $this->data['fax'] = '';
	}

	if (isset($this->request->post['newsletter'])) {
	    $this->data['newsletter'] = $this->request->post['newsletter'];
	} elseif (isset($customer_info)) {
	    $this->data['newsletter'] = $customer_info['newsletter'];
	} else {
	    $this->data['newsletter'] = 0;
	}

	if (isset($this->request->post['password'])) {
	    $this->data['password'] = $this->request->post['password'];
	} elseif (isset($customer_info)) {
	    $this->data['password'] = $customer_info['password'];
	} 
	 else {
	    $this->data['password'] = '';
	}

	if (isset($this->request->post['confirm'])) {
	    $this->data['confirm'] = $this->request->post['confirm'];
	} else {
	    $this->data['confirm'] = '';
	}

	$this->document->addScriptInline('var dataForm = new VarienForm("edit",true);', Document::POS_END);

	$this->data['back'] = makeUrl('account/account', array(), true, true);

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/edit.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/account/edit.tpl';
	} else {
	    $this->template = 'default/template/account/edit.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
		if ((strlen(utf8_decode(trim($this->request->post['firstname']))) < 3) || (strlen(utf8_decode(trim($this->request->post['firstname']))) > 32)) {
		    $this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((strlen(utf8_decode(trim($this->request->post['lastname']))) < 3) || (strlen(utf8_decode(trim($this->request->post['lastname']))) > 32)) {
		    $this->error['lastname'] = $this->language->get('error_lastname');
		}

		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';

		if ((strlen(utf8_decode($this->request->post['email'])) > 32) || (!preg_match($pattern, $this->request->post['email']))) {
		    $this->error['email'] = $this->language->get('error_email');
		}

		if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
		    $this->error['warning'] = $this->language->get('error_exists');
		}
		if($this->request->post['old_password'] != ''){
		    $result=$this->model_account_customer->getCustomer($this->customer->isLogged());
		    if(md5($this->request->post['old_password'])!=$result['password']){
		        $this->error['warning'] = $this->language->get('wrong_password');
			}
		}

		if (!$this->error) {
		    return TRUE;
		} else {
		    return FALSE;
		}
    }

}

?>