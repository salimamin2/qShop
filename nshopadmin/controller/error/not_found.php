<?php

class ControllerErrorNotFound extends Controller {

    public function index() {
	$this->load->language('error/not_found');

	$this->document->title = $this->language->get('heading_title');

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_not_found'] = $this->language->get('text_not_found');

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'error/not_found&token=' . $this->session->data['token'],
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->template = 'error/not_found.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>