<?php

class ControllerInformationFeedback extends Controller {

    private $error = array();

    public function index() {
	$this->language->load('information/feedback');

	$this->document->layout_col = "col2-right-layout";
	$this->document->title = $this->language->get('heading_title');

	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
	    $mail = new Mail();
	    $mail->protocol = $this->config->get('config_mail_protocol');
	    $mail->hostname = $this->config->get('config_smtp_host');
	    $mail->username = $this->config->get('config_smtp_username');
	    $mail->password = $this->config->get('config_smtp_password');
	    $mail->port = $this->config->get('config_smtp_port');
	    $mail->timeout = $this->config->get('config_smtp_timeout');
	    $mail->setTo($this->config->get('config_email'));
	    $mail->setFrom($this->request->post['email']);
	    $mail->setSender($this->request->post['name']);
	    $mail->setSubject(sprintf($this->language->get('email_subject'), $this->request->post['name']));
	    $mail->setText(strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')));
	    $mail->send();

	    $this->redirect(makeUrl('information/contact/success', array(), true));
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home', array(), true),
	    'text' => $this->language->get('text_home'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('information/feedback', array(), true),
	    'text' => $this->language->get('heading_title'),
	    'separator' => FALSE
	);

    $this->data['email'] = $this->config->get('config_email');
    $this->data['telephone'] = $this->config->get('config_telephone');
	$this->data['heading_title'] = $this->language->get('heading_title');
	$this->data['text_address'] = $this->language->get('text_address');
	$this->data['text_telephone'] = $this->language->get('text_telephone');
	$this->data['text_fax'] = $this->language->get('text_fax');
	$this->data['text_email'] = $this->language->get('text_email');


	
	$this->data['text_message'] = $this->language->get('text_message');
	$this->data['text_how_often'] = $this->language->get('text_how_often');
	$this->data['text_views_params'] = $this->language->get('text_views_params');
	$this->data['text_learned'] = $this->language->get('text_learned');


	
	$this->data['text_why_not_shop'] = $this->language->get('text_why_not_shop');
	$this->data['text_get_know_better'] = $this->language->get('text_get_know_better');
	$this->data['text_what_else'] = $this->language->get('text_what_else');
	$this->data['text_comments'] = $this->language->get('text_comments');



	
	$this->data['entry_name'] = $this->language->get('entry_name');
	$this->data['entry_email'] = $this->language->get('entry_email');
	$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
	$this->data['entry_captcha'] = $this->language->get('entry_captcha');


	$this->data['button_continue'] = $this->language->get('button_continue');

	$this->data['action'] = makeUrl('information/contact', array(), true);
	$this->data['store'] = $this->config->get('config_name');
	$this->data['address'] = nl2br($this->config->get('config_address'));
	$this->data['telephone'] = $this->config->get('config_telephone');
	$this->data['fax'] = $this->config->get('config_fax');
	$this->data['conf_email'] = $this->config->get('config_email');
	$this->data['google_map'] = html_entity_decode($this->config->get('config_google_map'), ENT_QUOTES, 'UTF-8');

	if (isset($this->request->post['name'])) {
	    $this->data['name'] = $this->request->post['name'];
	} else {
	    $this->data['name'] = '';
	}

	if (isset($this->request->post['email'])) {
	    $this->data['email'] = $this->request->post['email'];
	} else {
	    $this->data['email'] = '';
	}

	if (isset($this->request->post['enquiry'])) {
	    $this->data['enquiry'] = $this->request->post['enquiry'];
	} else {
	    $this->data['enquiry'] = '';
	}

	if (isset($this->request->post['captcha'])) {
	    $this->data['captcha'] = $this->request->post['captcha'];
	} else {
	    $this->data['captcha'] = '';
	}

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/feedback.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/information/feedback.tpl';
	} else {
	    $this->template = 'default/template/information/feedback.tpl';
	}

	$this->document->addScriptInline("var dataForm = new VarienForm('contact',true)", Document::POS_END);

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function success() {
	$this->language->load('information/contact');

	$this->document->title = $this->language->get('heading_title');

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home', array(), true),
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('information/contact', array(), true),
	    'text' => $this->language->get('heading_title'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_message'] = $this->language->get('text_message');

	$this->data['button_continue'] = $this->language->get('button_continue');

	$this->data['continue'] = HTTP_SERVER . 'common/home';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
	} else {
	    $this->template = 'default/template/common/success.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function captcha() {
	$this->load->library('captcha');

	$captcha = new Captcha();

	$this->session->data['captcha'] = $captcha->getCode();

	$captcha->showImage();
    }

    private function validate() {
	if ((strlen(utf8_decode(trim($this->request->post['name']))) < 3) || (strlen(utf8_decode(trim($this->request->post['name']))) > 32)) {
	    $this->error['name'] = $this->language->get('error_name');
	}

	$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';

	if (!preg_match($pattern, $this->request->post['email'])) {
	    $this->error['email'] = $this->language->get('error_email');
	}

	if ((strlen(utf8_decode(trim($this->request->post['enquiry']))) < 10) || (strlen(utf8_decode(trim($this->request->post['enquiry']))) > 1000)) {
	    $this->error['enquiry'] = $this->language->get('error_enquiry');
	}

	if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
	    $this->error['captcha'] = $this->language->get('error_captcha');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>
