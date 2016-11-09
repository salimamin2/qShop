<?php

class ControllerCheckoutProcessBar extends Controller {
    protected function index() {
        $this->load->model('tool/seo_url');
        
        $route = $this->request->get['act'];
        $this->data['links'][] = array(
            'name' => $this->language->get('process_login'),
            'href' => ($route == 'account/login' || $route == 'checkout/address/shipping' || $route == 'checkout/shipping' || $route == 'checkout/payment' || $route == 'checkout/confirm') ? $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/login') : 'javascript:void(0);',
            'class' => ($route == 'account/login') ? 'active first' : 'first'
        );
        
        $this->data['links'][] = array(
            'name' => $this->language->get('process_address'),
            'href' => ($route == 'checkout/address/shipping' || $route == 'checkout/shipping' || $route == 'checkout/payment' || $route == 'checkout/confirm') ? $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'checkout/address/shipping') : 'javascript:void(0);',
            'class' => ($route == 'checkout/address/shipping') ? 'active' : ''
        );
        
        $this->data['links'][] = array(
            'name' => $this->language->get('process_shipping'),
            'href' => ($route == 'checkout/shipping' || $route == 'checkout/payment' || $route == 'checkout/confirm') ? $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'checkout/shipping') : 'javascript:void(0);',
            'class' => ($route == 'checkout/shipping') ? 'active' : ''
        );
        
        $this->data['links'][] = array(
            'name' => $this->language->get('process_payment'),
            'href' => ($route == 'checkout/payment' || $route == 'checkout/confirm') ? $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'checkout/payment') : 'javascript:void(0);',
            'class' => ($route == 'checkout/payment') ? 'active' : ''
        );
        
        $this->data['links'][] = array(
            'name' => $this->language->get('process_checkout'),
            'href' => ($route == 'checkout/confirm') ? $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'checkout/confirm') : 'javascript:void(0);',
            'class' => ($route == 'checkout/confirm') ? 'active last' : 'last'
        );
        
        $this->data['image'] = HTTP_IMAGE;
        $aRoute = array();
        
        $route = explode('/',$route);
        $this->data['current_page'] = $route[1];
        
        $this->id = 'process_bar';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/process_bar.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/process_bar.tpl';
        } else {
            $this->template = 'default/template/checkout/process_bar.tpl';
        }

        $this->render();
    }
}

?>