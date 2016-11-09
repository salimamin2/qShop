<?php

class ControllerModuleSocialPromotion extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('module/social_promotion');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            Make::a('setting/setting')->create()->editSetting('social_promotion', $this->request->post);

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

        if (isset($this->error['title'])) {
            $this->data['error_title'] = $this->error['title'];
        } else {
            $this->data['error_title'] = '';
        }

        if (isset($this->error['promo'])) {
            $this->data['error_promo'] = $this->error['promo'];
        } else {
            $this->data['error_promo'] = '';
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
            'href' => HTTPS_SERVER . 'module/social_promotion',
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['action'] = HTTPS_SERVER . 'module/social_promotion';

        $this->data['cancel'] = HTTPS_SERVER . 'extension/module';

        if (isset($this->request->post['social_promotion_title'])) {
            $this->data['social_promotion_title'] = $this->request->post['social_promotion_title'];
        } else {
            $this->data['social_promotion_title'] = $this->config->get('social_promotion_title');
        }

        if (isset($this->request->post['social_promotion_description'])) {
            $this->data['social_promotion_description'] = $this->request->post['social_promotion_description'];
        } else {
            $this->data['social_promotion_description'] = $this->config->get('social_promotion_description');
        }

        if (isset($this->request->post['cart_status'])) {
            $this->data['cart_status'] = $this->request->post['cart_status'];
        } else {
            $this->data['cart_status'] = $this->config->get('cart_status');
        }

        $this->load->model('sale/coupon');
        $this->data['coupons'] = $this->model_sale_coupon->getAllCoupons();

        if (isset($this->request->post['social_promotion_promo'])) {
            $this->data['social_promotion_promo'] = $this->request->post['social_promotion_promo'];
        } else {
            $this->data['social_promotion_promo'] = $this->config->get('social_promotion_promo');
        }

        if (isset($this->request->post['social_promotion_box_title'])) {
            $this->data['social_promotion_box_title'] = $this->request->post['social_promotion_box_title'];
        } else {
            $this->data['social_promotion_box_title'] = $this->config->get('social_promotion_box_title');
        }
        if (isset($this->request->post['social_promotion_box_desc'])) {
            $this->data['social_promotion_box_desc'] = $this->request->post['social_promotion_box_desc'];
        } else {
            $this->data['social_promotion_box_desc'] = $this->config->get('social_promotion_box_desc');
        }
        if (isset($this->request->post['social_promotion_fb_link'])) {
            $this->data['social_promotion_fb_link'] = $this->request->post['social_promotion_fb_link'];
        } else {
            $this->data['social_promotion_fb_link'] = $this->config->get('social_promotion_fb_link');
        }
        if (isset($this->request->post['social_promotion_tt_link'])) {
            $this->data['social_promotion_tt_link'] = $this->request->post['social_promotion_tt_link'];
        } else {
            $this->data['social_promotion_tt_link'] = $this->config->get('social_promotion_tt_link');
        }
        if (isset($this->request->post['social_promotion_gp_link'])) {
            $this->data['social_promotion_gp_link'] = $this->request->post['social_promotion_gp_link'];
        } else {
            $this->data['social_promotion_gp_link'] = $this->config->get('social_promotion_gp_link');
        }
        if (isset($this->request->post['social_promotion_status'])) {
            $this->data['social_promotion_status'] = $this->request->post['social_promotion_status'];
        } else {
            $this->data['social_promotion_status'] = $this->config->get('social_promotion_status');
        }

        $this->template = 'module/social_promotion.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/social_promotion')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if(empty($this->request->post['social_promotion_title'])) {
            $this->error['title'] = $this->language->get('error_title');
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if(empty($this->request->post['social_promotion_promo'])) {
            $this->error['promo'] = $this->language->get('error_promo');
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>