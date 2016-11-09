<?php

class ControllerModuleCoupon extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('module/coupon');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            Make::a('setting/setting')->create()->editSetting('coupon', $this->request->post);

            // $this->session->data['success'] = $this->language->get('text_success');
            $this->cache->delete('config_settings');
            // $this->redirect(HTTPS_SERVER . 'extension/module');
            $this->data['success'] = $this->language->get('text_success');
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->data['entry_title'] = $this->language->get('entry_title');
        $this->data['entry_description'] = $this->language->get('entry_description');
        $this->data['entry_promo'] = $this->language->get('entry_promo');
        $this->data['entry_box_title'] = $this->language->get('entry_box_title');
        $this->data['entry_box_desc'] = $this->language->get('entry_box_desc');
        $this->data['entry_fb_link'] = $this->language->get('entry_fb_link');
        $this->data['entry_tt_link'] = $this->language->get('entry_tt_link');
        $this->data['entry_gp_link'] = $this->language->get('entry_gp_link');
        $this->data['entry_status'] = $this->language->get('entry_status');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'extension/module',
            'text' => $this->language->get('text_module'),
            'separator' => ' :: '
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'module/coupon',
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['action'] = HTTPS_SERVER . 'module/coupon';

        $this->data['cancel'] = HTTPS_SERVER . 'extension/module';

        if (isset($this->request->post['coupon_title'])) {
            $this->data['coupon_title'] = $this->request->post['coupon_title'];
        } else {
            $this->data['coupon_title'] = $this->config->get('coupon_title');
        }

        if (isset($this->request->post['coupon_description'])) {
            $this->data['coupon_description'] = $this->request->post['coupon_description'];
        } else {
            $this->data['coupon_description'] = $this->config->get('coupon_description');
        }

        if (isset($this->request->post['cart_status'])) {
            $this->data['cart_status'] = $this->request->post['cart_status'];
        } else {
            $this->data['cart_status'] = $this->config->get('cart_status');
        }

        if (isset($this->request->post['coupon_promo'])) {
            $this->data['coupon_promo'] = $this->request->post['coupon_promo'];
        } else {
            $this->data['coupon_promo'] = $this->config->get('coupon_promo');
        }

        if (isset($this->request->post['coupon_box_title'])) {
            $this->data['coupon_box_title'] = $this->request->post['coupon_box_title'];
        } else {
            $this->data['coupon_box_title'] = $this->config->get('coupon_box_title');
        }
        if (isset($this->request->post['coupon_box_desc'])) {
            $this->data['coupon_box_desc'] = $this->request->post['coupon_box_desc'];
        } else {
            $this->data['coupon_box_desc'] = $this->config->get('coupon_box_desc');
        }
        if (isset($this->request->post['coupon_fb_link'])) {
            $this->data['coupon_fb_link'] = $this->request->post['coupon_fb_link'];
        } else {
            $this->data['coupon_fb_link'] = $this->config->get('coupon_fb_link');
        }
        if (isset($this->request->post['coupon_tt_link'])) {
            $this->data['coupon_tt_link'] = $this->request->post['coupon_tt_link'];
        } else {
            $this->data['coupon_tt_link'] = $this->config->get('coupon_tt_link');
        }
        if (isset($this->request->post['coupon_gp_link'])) {
            $this->data['coupon_gp_link'] = $this->request->post['coupon_gp_link'];
        } else {
            $this->data['coupon_gp_link'] = $this->config->get('coupon_gp_link');
        }
        if (isset($this->request->post['coupon_status'])) {
            $this->data['coupon_status'] = $this->request->post['coupon_status'];
        } else {
            $this->data['coupon_status'] = $this->config->get('coupon_status');
        }

        $this->template = 'module/coupon.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/coupon')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>