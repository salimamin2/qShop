<?php

class ControllerAccountLogout extends Controller {

    public function index() {
	if ($this->customer->isLogged()) {
	    $this->customer->logout();
	    $this->cart->clear();

	    unset($this->session->data['shipping_address_id']);
	    unset($this->session->data['shipping_method']);
	    unset($this->session->data['shipping_methods']);
	    unset($this->session->data['payment_address_id']);
	    unset($this->session->data['payment_method']);
	    unset($this->session->data['payment_methods']);
	    unset($this->session->data['comment']);
	    unset($this->session->data['order_id']);
	    unset($this->session->data['checkout_register']);
	    unset($this->session->data['coupon']);

	    $this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

	    $this->redirect(makeUrl('account/logout', array(), true, true));
	}

	$this->language->load('account/logout');

	$this->document->title = $this->language->get('heading_title');

	$this->document->breadcrumbs = array();

	$this->data['heading_title'] = __('You are now logged Out');

	$this->data['text_message'] = $this->language->get('text_message');

	$this->data['button_continue'] = $this->language->get('button_continue');

	$this->data['continue'] = makeUrl('common/home', array(), true);

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
	} else {
	    $this->template = 'default/template/common/success.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>
