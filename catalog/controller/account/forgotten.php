<?php

class ControllerAccountForgotten extends Controller {

    private $error = array();

    public function index() {
	if ($this->customer->isLogged()) {
	    $this->redirect(makeUrl('account/account', array(), true, true));
	}

	$this->language->load('account/forgotten');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('account/customer');

	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		if($this->validate()){
		    $this->language->load('mail/account_forgotten');

		    $password = substr(md5(rand()), 0, 7);

		    $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		    $message = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
		    $message .= $this->language->get('text_password') . "\n\n";
		    $message .= $password;

		    $mail = new Mail();
		    $mail->protocol = $this->config->get('config_mail_protocol');
		    $mail->hostname = $this->config->get('config_smtp_host');
		    $mail->username = $this->config->get('config_smtp_username');
		    $mail->password = $this->config->get('config_smtp_password');
		    $mail->port = $this->config->get('config_smtp_port');
		    $mail->timeout = $this->config->get('config_smtp_timeout');
		    $mail->setTo($this->request->post['email']);
		    $mail->setFrom($this->config->get('config_email'));
		    $mail->setSender($this->config->get('config_name'));
		    $mail->setSubject($subject);
		    $mail->setHtml(html_entity_decode(nl2br($message), ENT_QUOTES, 'UTF-8'));
		    $mail->send();

		    $this->model_account_customer->editPassword($this->request->post['email'], $password);


	        if(isset($this->request->post['checkout_login']) && $this->request->post['checkout_login']=='checkout_login'){
	            $aResults['options']= $this->language->get('text_success');
	        } else {
			   	$this->session->data['success'] = $this->language->get('text_success');
		        $this->redirect(makeUrl('account/login', array(), true, true));
	    	}
	    } else {
	    	$aResults['error'] = $this->error;
	    }
	    if(isset($this->request->post['checkout_login']) && $this->request->post['checkout_login']=='checkout_login') {
            echo json_encode($aResults);
            exit();
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
	    'href' => makeUrl('account/forgotten', array(), true, true),
	    'text' => $this->language->get('text_forgotten'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_your_email'] = $this->language->get('text_your_email');
	$this->data['text_email'] = $this->language->get('text_email');

	$this->data['entry_email'] = $this->language->get('entry_email');

	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['button_back'] = $this->language->get('button_back');

	if (isset($this->error['message'])) {
	    $this->data['error'] = $this->error['message'];
	} else {
	    $this->data['error'] = '';
	}

	$this->data['action'] = makeUrl('account/forgotten', array(), true, true);

	$this->data['back'] = makeUrl('account/account', array(), true, true);

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/account/forgotten.tpl';
	} else {
	    $this->template = 'default/template/account/forgotten.tpl';
	}

	$this->document->addScriptInline('var dataForm = new VarienForm("forgotten",true);', Document::POS_END);

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
	if (!isset($this->request->post['email'])) {
	    $this->error['message'] = $this->language->get('error_email');
	} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
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