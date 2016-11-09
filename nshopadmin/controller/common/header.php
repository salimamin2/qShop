<?php

class ControllerCommonHeader extends Controller {

    protected function index() {
        $this->load->language('common/header');

        $this->data['title'] = $this->document->title;

        $this->data['base'] = (HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER;
        $this->data['charset'] = $this->language->get('charset');
        $this->data['lang'] = $this->language->get('code');
        $this->data['direction'] = $this->language->get('direction');
        $this->data['links'] = $this->document->links;
        $this->data['styles'] = $this->document->styles;
        $this->data['scripts'] = $this->document->scripts;
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_dashboard'] = $this->language->get('text_dashboard');
        $this->data['text_catalog'] = $this->language->get('text_catalog');
        $this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_color_swatch'] = $this->language->get('text_color_swatch');
        $this->data['text_product'] = $this->language->get('text_product');
        $this->data['text_backup'] = $this->language->get('text_backup');
        $this->data['text_import_data'] = $this->language->get('text_import_data');
        $this->data['text_export'] = $this->language->get('text_export');
        $this->data['text_country'] = $this->language->get('text_country');
        $this->data['text_coupon'] = $this->language->get('text_coupon');
        $this->data['text_discount'] = $this->language->get('text_discount'); // majid added
        $this->data['text_currency'] = $this->language->get('text_currency');
        $this->data['text_customer'] = $this->language->get('text_customer');
        $this->data['text_customer_group'] = $this->language->get('text_customer_group');
        $this->data['text_sale'] = $this->language->get('text_sale');
        $this->data['text_download'] = $this->language->get('text_download');
        $this->data['text_error_log'] = $this->language->get('text_error_log');
        $this->data['text_extension'] = $this->language->get('text_extension');
        $this->data['text_feed'] = $this->language->get('text_feed');
        $this->data['text_front'] = $this->language->get('text_front');
        $this->data['text_geo_zone'] = $this->language->get('text_geo_zone');
        $this->data['text_help'] = $this->language->get('text_help');
        $this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_page_layout'] = $this->language->get('text_page_layout');
        $this->data['text_information_link'] = $this->language->get('text_information_link');
        $this->data['text_language'] = $this->language->get('text_language');
        $this->data['text_localisation'] = $this->language->get('text_localisation');
        $this->data['text_logout'] = $this->language->get('text_logout');
        $this->data['text_contact'] = $this->language->get('text_contact');
        $this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_brand'] = $this->language->get('text_brand');
		$this->data['text_carrier'] = $this->language->get('text_carrier');
        $this->data['text_module'] = $this->language->get('text_module');
        $this->data['text_order'] = $this->language->get('text_order');
        $this->data['text_order_status'] = $this->language->get('text_order_status');
        $this->data['text_payment'] = $this->language->get('text_payment');
        $this->data['text_reports'] = $this->language->get('text_reports');
        $this->data['text_report_purchased'] = $this->language->get('text_report_purchased');
        $this->data['text_report_reward'] = $this->language->get('text_report_reward');
        $this->data['text_report_online']= $this->language->get('text_report_online');
        $this->data['text_report_sale'] = $this->language->get('text_report_sale');
        $this->data['text_report_viewed'] = $this->language->get('text_report_viewed');
        $this->data['text_review'] = $this->language->get('text_review');
        $this->data['text_support'] = $this->language->get('text_support');
        $this->data['text_shipping'] = $this->language->get('text_shipping');
        $this->data['text_testimonial'] = $this->language->get('text_testimonial');
        $this->data['text_setting'] = $this->language->get('text_setting');
		$this->data['text_design'] = $this->language->get('text_design');
		$this->data['text_layout'] = $this->language->get('text_layout');
		$this->data['text_banner'] = $this->language->get('text_banner');
		$this->data['text_media'] = $this->language->get('text_media');
		$this->data['text_collection'] = $this->language->get('text_collection');
        $this->data['text_stock_status'] = $this->language->get('text_stock_status');
        $this->data['text_system'] = $this->language->get('text_system');
        $this->data['text_tax_class'] = $this->language->get('text_tax_class');
        $this->data['text_total'] = $this->language->get('text_total');
        $this->data['text_user'] = $this->language->get('text_user');
        $this->data['text_user_group'] = $this->language->get('text_user_group');
        $this->data['text_users'] = $this->language->get('text_users');
        $this->data['text_documentation'] = $this->language->get('text_documentation');
        $this->data['text_weight_class'] = $this->language->get('text_weight_class');
        $this->data['text_length_class'] = $this->language->get('text_length_class');
        $this->data['text_opencart'] = $this->language->get('text_opencart');
        $this->data['text_zone'] = $this->language->get('text_zone');
        $this->data['text_news'] = $this->language->get('text_news');
        $this->data['text_customer_import'] = $this->language->get('text_customer_import');
        $this->data['text_customer_export'] = $this->language->get('text_customer_export');
        $this->data['text_confirm'] = $this->language->get('text_confirm');
        $this->data['text_product_price'] = $this->language->get('text_product_price');
        $this->data['text_seo_management'] = $this->language->get('text_seo_management');
        $this->data['text_menu'] = $this->language->get('text_menu');

        if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
            $this->data['logged'] = '';
            $this->data['home'] = HTTPS_SERVER . 'index.php?route=common/login';
        } else {
            $this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
            $this->data['news'] = HTTPS_SERVER . 'index.php?route=catalog/news&token=' . $this->session->data['token'];
            $this->data['home'] = HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'];
            $this->data['backup'] = HTTPS_SERVER . 'index.php?route=tool/backup&token=' . $this->session->data['token'];
            $this->data['import_data'] = HTTPS_SERVER . 'index.php?route=tool/import_data&token=' . $this->session->data['token'];
            $this->data['category'] = HTTPS_SERVER . 'index.php?route=catalog/category&token=' . $this->session->data['token'];
			$this->data['color_swatch'] = HTTPS_SERVER . 'index.php?route=catalog/color_swatch&token=' . $this->session->data['token'];
            $this->data['color'] = HTTPS_SERVER . 'index.php?route=catalog/color&token=' . $this->session->data['token'];
            $this->data['country'] = HTTPS_SERVER . 'index.php?route=localisation/country&token=' . $this->session->data['token'];
            $this->data['currency'] = HTTPS_SERVER . 'index.php?route=localisation/currency&token=' . $this->session->data['token'];
            $this->data['coupon'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $this->session->data['token'];
            $this->data['gift_card'] = HTTPS_SERVER . 'index.php?route=sale/gift_card&token=' . $this->session->data['token'];
            $this->data['gift_category'] = HTTPS_SERVER . 'index.php?route=sale/gift_category&token=' . $this->session->data['token'];
            $this->data['gift_voucher']= HTTPS_SERVER . 'index.php?route=sale/gift_voucher&token=' . $this->session->data['token'];
            $this->data['discount'] = HTTPS_SERVER . 'index.php?route=sale/discount&token=' . $this->session->data['token']; //majid added
            $this->data['customer'] = HTTPS_SERVER . 'index.php?route=sale/customer&token=' . $this->session->data['token'];
            $this->data['customer_group'] = HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'];
            $this->data['download'] = HTTPS_SERVER . 'index.php?route=catalog/download&token=' . $this->session->data['token'];
            $this->data['error_log'] = HTTPS_SERVER . 'index.php?route=tool/error_log&token=' . $this->session->data['token'];
            $this->data['feed'] = HTTPS_SERVER . 'index.php?route=extension/feed&token=' . $this->session->data['token'];
            $this->data['live_chat'] = '/livechat/admin.php';

            $this->data['stores'] = array();

            $this->load->model('setting/store');

            $results = $this->model_setting_store->getStores();

            foreach ($results as $result) {
                $this->data['stores'][] = array(
                    'name' => $result['name'],
                    'href' => $result['url']
                );
            }

            $this->data['geo_zone'] = HTTPS_SERVER . 'index.php?route=localisation/geo_zone&token=' . $this->session->data['token'];
            $this->data['page_layout'] = HTTPS_SERVER . 'index.php?route=catalog/page_layout&token=' . $this->session->data['token'];
			$this->data['information'] = HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'];
            $this->data['information_link'] = HTTPS_SERVER . 'index.php?route=catalog/information_link&token=' . $this->session->data['token'];
            $this->data['language'] = HTTPS_SERVER . 'index.php?route=localisation/language&token=' . $this->session->data['token'];
            $this->data['testimonial'] = HTTPS_SERVER . 'index.php?route=catalog/testimonial&token=' . $this->session->data['token'];
            $this->data['logout'] = HTTPS_SERVER . 'index.php?route=common/logout&token=' . $this->session->data['token'];
            $this->data['home'] = HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'];
            $this->data['manufacturer'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'];
			$this->data['brand'] = HTTPS_SERVER . 'index.php?route=catalog/brand&token=' . $this->session->data['token'];
			$this->data['carrier'] = HTTPS_SERVER . 'index.php?route=catalog/carrier&token=' . $this->session->data['token'];
            $this->data['module'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];
            $this->data['order'] = HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'];
            $this->data['order_status'] = HTTPS_SERVER . 'index.php?route=localisation/order_status&token=' . $this->session->data['token'];
            $this->data['payment'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
            $this->data['product'] = HTTPS_SERVER . 'index.php?route=catalog/product&token=' . $this->session->data['token'];
            $this->data['report_purchased'] = HTTPS_SERVER . 'index.php?route=report/purchased&token=' . $this->session->data['token'];
            $this->data['report_reward'] = HTTPS_SERVER . 'index.php?route=report/customer_reward&token=' . $this->session->data['token'];
            $this->data['report_online'] = HTTPS_SERVER . 'index.php?route=report/online&token=' . $this->session->data['token'];
            $this->data['report_sale'] = HTTPS_SERVER . 'index.php?route=report/sale&token=' . $this->session->data['token'];
            $this->data['report_viewed'] = HTTPS_SERVER . 'index.php?route=report/viewed&token=' . $this->session->data['token'];
            $this->data['review'] = HTTPS_SERVER . 'index.php?route=catalog/review&token=' . $this->session->data['token'];
            $this->data['shipping'] = HTTPS_SERVER . 'index.php?route=extension/shipping&token=' . $this->session->data['token'];
            $this->data['setting'] = HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token'];
			$this->data['layout'] = HTTPS_SERVER . 'index.php?route=design/layout&token=' . $this->session->data['token'];
			$this->data['banner'] = HTTPS_SERVER . 'index.php?route=design/banner&token=' . $this->session->data['token'];
			$this->data['media'] = HTTPS_SERVER . 'index.php?route=design/media&token=' . $this->session->data['token'];
			$this->data['collection'] = HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'];
            $this->data['seo_management'] = HTTPS_SERVER . 'index.php?route=setting/seo_management&token=' . $this->session->data['token'];
            $this->data['product_price'] = HTTPS_SERVER . 'index.php?route=setting/product_price&token=' . $this->session->data['token'];
            $this->data['store'] = HTTP_CATALOG;
            $this->data['stock_status'] = HTTPS_SERVER . 'index.php?route=localisation/stock_status&token=' . $this->session->data['token'];
            $this->data['tax_class'] = HTTPS_SERVER . 'index.php?route=localisation/tax_class&token=' . $this->session->data['token'];
            $this->data['total'] = HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token'];
            $this->data['user'] = HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'];
            $this->data['user_group'] = HTTPS_SERVER . 'index.php?route=user/user_permission&token=' . $this->session->data['token'];
            $this->data['weight_class'] = HTTPS_SERVER . 'index.php?route=localisation/weight_class&token=' . $this->session->data['token'];
            $this->data['length_class'] = HTTPS_SERVER . 'index.php?route=localisation/length_class&token=' . $this->session->data['token'];
            $this->data['zone'] = HTTPS_SERVER . 'index.php?route=localisation/zone&token=' . $this->session->data['token'];
            $this->data['product_type'] = HTTPS_SERVER . 'index.php?route=catalog/product_type&token=' . $this->session->data['token'];
            $this->data['menu'] = HTTPS_SERVER . 'index.php?route=catalog/menu&token=' . $this->session->data['token'];
        }

        $this->id = 'header';
        $this->template = 'common/header.tpl';

        $this->render();
    }

}

?>