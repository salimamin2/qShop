<?php

class ControllerCommonHome extends Controller {

    public function index() {
        $this->language->load('common/home');

         $this->load->model('catalog/information');

        $home_page = $this->model_catalog_information->getInformation(65);
        $this->data['home_page'] = html_entity_decode($home_page['description'], ENT_QUOTES, 'UTF-8');

        $this->document->title = $this->config->get('config_title');
        $this->document->description = $this->config->get('config_meta_description');
        $this->document->keywords = $this->config->get('config_meta_keywords');

        $this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

        $this->load->model('setting/store');
        //unsetting category id from session for brand

        if (!$this->config->get('config_store_id')) {
            $this->data['welcome'] = html_entity_decode($this->config->get('config_description_' . $this->config->get('config_language_id')), ENT_QUOTES, 'UTF-8');
        } else {
            $store_info = $this->model_setting_store->getStore($this->config->get('config_store_id'));
            if ($store_info) {
                $this->data['welcome'] = html_entity_decode($store_info['description'], ENT_QUOTES, 'UTF-8');
            } else {
                $this->data['welcome'] = '';
            }
        }
        $this->data['action'] = makeUrl('checkout/cart', array(), true, true);

        $this->data['text_latest'] = $this->language->get('text_latest');
        $this->data['text_view'] = $this->language->get('text_view');
        $this->data['text_model'] = $this->language->get('text_model');
        $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
        
        $this->load->model('tool/seo_url');
        $this->load->model('tool/image');

        if (!$this->config->get('config_customer_price')) {
            $this->data['display_price'] = TRUE;
        } elseif ($this->customer->isLogged()) {
            $this->data['display_price'] = TRUE;
        } else {
            $this->data['display_price'] = FALSE;
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/home.tpl';
        } else {
            $this->template = 'default/template/common/home.tpl';
        }

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>