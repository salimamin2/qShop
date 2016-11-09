<?php

class ControllerAccountSuccess extends Controller {

    public function index() {
        $this->language->load('account/success');

        $this->document->title = $this->language->get('heading_title');

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
            'href' => makeUrl('account/success', array(), true, true),
            'text' => $this->language->get('text_success'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        if (!$this->config->get('config_customer_approval')) {
            $this->data['text_message'] = sprintf($this->language->get('text_message'), HTTP_SERVER . 'customer-service');
        } else {
            $this->data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), HTTP_SERVER . 'customer-service');
        }

        $this->data['button_continue'] = $this->language->get('button_continue');

        if ($this->cart->hasProducts()) {
            $this->data['continue'] = makeUrl('checkout/cart', array(), true, true);
        } else {
            $this->data['continue'] = makeUrl('account/account', array(), true, true);
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
        } else {
            $this->template = 'default/template/common/success.tpl';
        }

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>