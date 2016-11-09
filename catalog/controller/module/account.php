<?php
class ControllerModuleAccount extends Controller {
    protected function index() {
        $this->language->load('module/account');
        $this->load->model('tool/seo_url');
        
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_detail'] = $this->language->get('text_detail');
        $this->data['text_information'] = $this->language->get('text_information');
        $this->data['text_ship_address'] = $this->language->get('text_ship_address');
        $this->data['text_order_reorder'] = $this->language->get('text_order_reorder');
        $this->data['text_wishlist'] = $this->language->get('text_wishlist');
        $this->data['text_order_in_hold'] = $this->language->get('text_order_in_hold');
        $this->data['text_order_history'] = $this->language->get('text_order_history');
        $this->data['text_quick_buy'] = $this->language->get('text_quick_buy');
        $this->data['text_newsletter_subscription'] = $this->language->get('text_newsletter_subscription');
        $this->data['text_logout'] = $this->language->get('text_logout');
        $this->data['text_reward'] = $this->language->get('text_reward');

        $this->data['detail'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/account');
        $this->data['information'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/edit');
        if ($this->config->get('config_allow_reward')) {
            $this->data['reward'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/reward');
        } else {
          $this->data['reward'] = false;
        }
        $this->data['ship_address'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/address');
        $this->data['order_reorder'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/reorder');
        $this->data['wishlist'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/wishlist');
        $this->data['order_in_hold'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/cart');
        $this->data['order_history'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/history');
        $this->data['quick_buy'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/quick_order');
        $this->data['newsletter_subscription'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/newsletter');
        $this->data['logout'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'account/logout');

        $this->data['is_log'] = $this->customer->isLogged();
        
        $this->id = 'account';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/account.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/account.tpl';
        } else {
            $this->template = 'default/template/module/account.tpl';
        }

        $this->render();
    }
}
?>