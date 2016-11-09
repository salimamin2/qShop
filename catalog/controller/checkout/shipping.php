<?php

class ControllerCheckoutShipping extends Controller {

    private $error = array();

    public function index() {
        /*
         * Cart have product or product stock is not full if client has checked stock check
         */

        if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->redirect(makeUrl('checkout/cart'));
        }

        /*
         * Check address id exist in session
         */

        if (!isset($this->session->data['shipping_address_id']) && $this->customer->isLogged()) {
            $this->session->data['shipping_address_id'] = $this->customer->getAddressId();
        }

        /* if (!isset($this->session->data['payment_address_id']) && isset($this->session->data['shipping_address_id']) && $this->session->data['shipping_address_id']) {
          $this->session->data['payment_address_id'] = $this->session->data['shipping_address_id'];
          } */
        if (!isset($this->session->data['payment_address_id']) && $this->customer->isLogged()) {
            $this->session->data['payment_address_id'] = $this->customer->getAddressId();
        }

        /*
         * Load Addresses
         */

        $this->load->model('account/address');
        $this->load->model('tool/image');

        $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);

//        if (isset($this->session->data['shipping_address_id']) && (!isset($this->session->data['payment_address_id']) || !$this->session->data['payment_address_id'])) {
//            $this->session->data['payment_address_id'] = $this->session->data['shipping_address_id'];
//        }
        $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);

        if (!$shipping_address) {
            $this->redirect(makeUrl('checkout/address'));
        }
        if (!$payment_address) {
            $this->redirect(makeUrl('checkout/address'));
        }

        $this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);

        /*
         * Get Methods from extension, that are install and save to session
         */

        $this->load->model('checkout/extension');

        /*
         * Shipping Methods
         */
        if(!isset($this->session->data['calculate_country_id'])) {
            unset($this->session->data['shipping_methods']);
            $quote_data = array();
            $results = $this->model_checkout_extension->getExtensions('shipping');
            foreach ($results as $result) {
                $this->load->model('shipping/' . $result['key']);
                $quote = $this->{'model_shipping_' . $result['key']}->getQuote($shipping_address);
//                d(array($result['key'],$quote,$shipping_address));
                if ($quote) {
                    $quote_data[$result['key']] = array(
                        'title' => $quote['title'],
                        'quote' => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error' => $quote['error']
                    );
                }
            }

            $sort_order = array();
            foreach ($quote_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }
            array_multisort($sort_order, SORT_ASC, $quote_data);
            $this->session->data['shipping_methods'] = $quote_data;
        }
//        d($quote_data);
        /*
         * Payment Methods
         */
        $method_data = array();

        $results = $this->model_checkout_extension->getExtensions('payment');

        foreach ($results as $result) {
            $this->load->model('payment/' . $result['key']);
            $method = $this->{'model_payment_' . $result['key']}->getMethod($payment_address);
            if ($method) {
                $method_data[$result['key']] = $method;
            }
        }

        $sort_order = array();
        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);
        $this->session->data['payment_methods'] = $method_data;
        //d($method_data);
        $this->session->data['payment_method'] = end($method_data);

        /*
         * Calling Language files
         */

        $this->language->load('checkout/shipping');

        $this->data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            if ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
                $image = $product['image'];
            } else {
                $image = 'no_image.jpg';
            }
            $option_data = array();

            foreach ($product['option'] as $option) {
                $option_data[] = array(
                    'name' => $option['name'],
                    'value' => $option['value']
                );
            }

            $this->data['products'][] = array(
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'model' => $product['model'],
                'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
                'option' => $option_data,
                'qty' => $product['quantity'],
                'href' => HTTP_SERVER . 'index.php?route=product/product&product_id=' . $product['product_id']
            );
        }

        $this->document->title = $this->language->get('heading_title');

        /*
         * Create Breadcrumbs
         */

        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'index.php?route=common/home',
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );
        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'index.php?route=checkout/cart',
            'text' => $this->language->get('text_basket'),
            'separator' => $this->language->get('text_separator')
        );
        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'index.php?route=checkout/address',
            'text' => $this->language->get('text_address'),
            'separator' => $this->language->get('text_separator')
        );
        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'index.php?route=checkout/shipping',
            'text' => $this->language->get('text_shipping'),
            'separator' => $this->language->get('text_separator')
        );

        /*
         * Template Date from language file
         */

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_shipping_to'] = $this->language->get('text_shipping_to');
        $this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $this->data['text_shipping_methods'] = $this->language->get('text_shipping_methods');
        $this->data['text_payment_to'] = $this->language->get('text_payment_to');
        $this->data['text_payment_address'] = $this->language->get('text_payment_address');
        $this->data['text_payment_method'] = $this->language->get('text_payment_method');
        $this->data['text_payment_methods'] = $this->language->get('text_payment_methods');
        $this->data['text_comments'] = $this->language->get('text_comments');

        $this->data['heading_subscription'] = $this->language->get('heading_subscription');
        $this->data['text_subscription'] = $this->language->get('text_subscription');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['entry_subscription_status'] = $this->language->get('entry_subscription_status');
        $this->data['entry_intimation_level'] = $this->language->get('entry_intimation_level');

        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_qty'] = $this->language->get('column_qty');
        $this->data['column_reorder_level'] = $this->language->get('column_reorder_level');

        $this->data['button_change_address'] = $this->language->get('button_change_address');
        $this->data['button_back'] = $this->language->get('button_back');
        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['text_help'] = sprintf($this->language->get('text_help'), HTTP_SERVER . 'index.php?route=information/contact', $this->config->get('config_telephone'));

        /*
         * Warning and Error
         */

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        if (isset($this->session->data['shipping_methods']) && !$this->session->data['shipping_methods']) {
            $this->data['error_warning'] = $this->language->get('error_no_shipping');
        }

        if (isset($this->session->data['error'])) {
            $this->data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } elseif (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        /*
         * Shipping Adress
         */

        if ($shipping_address['address_format']) {
            $format = $shipping_address['address_format'];
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{zone_code}',
            '{country}'
        );

        $replace = array(
            'firstname' => $shipping_address['firstname'],
            'lastname' => $shipping_address['lastname'],
            'company' => $shipping_address['company'],
            'address_1' => $shipping_address['address_1'],
            'address_2' => $shipping_address['address_2'],
            'city' => $shipping_address['city'],
            'postcode' => $shipping_address['postcode'],
            'zone' => $shipping_address['zone'],
            'zone_code' => $shipping_address['zone_code'],
            'country' => $shipping_address['country']
        );

        $this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

        if (isset($this->session->data['shipping_methods'])) {
            $this->data['shipping_methods'] = $this->session->data['shipping_methods'];
        } else {
            $this->data['shipping_methods'] = array();
        }
        //d($this->session->data['shipping_methods']);
        if (isset($this->session->data['shipping_method']['id'])) {
            $this->data['shipping'] = $this->session->data['shipping_method']['id'];
        } else {
            $this->data['shipping'] = '';
        }

        /*
         * Payment Adress
         */

        if ($payment_address['address_format']) {
            $format = $payment_address['address_format'];
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }
        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{zone_code}',
            '{country}'
        );
        $replace = array(
            'firstname' => $payment_address['firstname'],
            'lastname' => $payment_address['lastname'],
            'company' => $payment_address['company'],
            'address_1' => $payment_address['address_1'],
            'address_2' => $payment_address['address_2'],
            'city' => $payment_address['city'],
            'postcode' => $payment_address['postcode'],
            'zone' => $payment_address['zone'],
            'zone_code' => $payment_address['zone_code'],
            'country' => $payment_address['country']
        );
        $this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
        if (isset($this->session->data['payment_methods'])) {
            $this->data['payment_methods'] = $this->session->data['payment_methods'];
        } else {
            $this->data['payment_methods'] = array();
        }
        if (isset($this->request->post['payment_method'])) {
            $this->data['payment'] = $this->request->post['payment_method'];
        } elseif (isset($this->session->data['payment_method']['id'])) {
            $this->data['payment'] = $this->session->data['payment_method']['id'];
        } else {
            $this->data['payment'] = '';
        }

        /*
         * Comment
         */

        if (isset($this->request->post['comment'])) {
            $this->request->post['comment'] = $this->shortcodes->strip_shortcodes($this->request->post['comment']);
            $this->data['comment'] = $this->request->post['comment'];
        } else if (isset($this->session->data['comment'])) {
            $this->data['comment'] = $this->session->data['comment'];
        } else {
            $this->data['comment'] = '';
        }

        /*
         * Terms Agreement
         */

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $this->data['text_agree'] = sprintf($this->language->get('text_agree'), HTTPS_SERVER . 'index.php?route=information/information/loadInfo&information_id=' . $this->config->get('config_checkout_id'), $information_info['title']);
            } else {
                $this->data['text_agree'] = '';
            }
        } else {
            $this->data['text_agree'] = '';
        }

        if (isset($this->request->post['agree'])) {
            $this->data['agree'] = $this->request->post['agree'];
        } else {
            $this->data['agree'] = '';
        }

        $this->data['payment_id'] = $this->session->data['payment_method']['id'];
        /*
         * Action Links
         */

        $this->data['change_shipping_address'] = makeUrl('checkout/address');
        $this->data['change_payment_address'] = makeUrl('checkout/address');
        $this->data['back'] = makeUrl('checkout/cart');
        $this->data['action'] = makeUrl('checkout/shipping');

        /*
         * Template Definition
         */

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/shipping.tpl';
        } else {
            $this->template = 'default/template/checkout/shipping.tpl';
        }

        $this->children = array(
            'common/header',
            'common/footer',
            'common/column_left',
            'common/column_right',
            'checkout/left_bar'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function validate() {
        /*
         * Validate Payment Address
         */
        if (!isset($this->session->data['payment_address_id']) || !$this->session->data['payment_address_id']) {
            $this->error['warning'] = $this->language->get('error_payment_address');
        }

        /*
         * Validate Shipping Method
         */

        if (!isset($this->request->post['shipping_method'])) {
            $this->error['warning'] = $this->language->get('error_shipping');
        } else {
            $shipping = explode('.', $this->request->post['shipping_method']);
            if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->error['warning'] = $this->language->get('error_shipping');
            }
        }

        /*
         * Validate Payment Method
         */

        if (!isset($this->request->post['payment_method'])) {
            $this->error['warning'] = $this->language->get('error_payment');
        } else {
            if (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                $this->error['warning'] = $this->language->get('error_payment');
            }
        }

        /*
         * Validate term agreement
         */

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                if (!isset($this->request->post['agree'])) {
                    $this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
            }
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>