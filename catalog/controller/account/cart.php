<?php 
class ControllerAccountCart extends Controller {
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = HTTPS_SERVER . 'account/history';

            $this->redirect(HTTPS_SERVER . 'account/login');
        }
        if ($this->request->server['REQUEST_METHOD'] == 'GET') {
            if (isset($this->request->get['quote_id'])) {
                $quote_products = $this->cart->getQuoteProducts($this->request->get['quote_id']);
                foreach($quote_products as $product){
                    if ($product['options']) {
                        foreach($product['options'] as $option){
                            $option_data[$option['product_option_id']] = $option['product_option_value_id'];
                        }
                    } else {
                        $option_data = array();
                    }
                    unset($this->session->data['cart']);
                    unset($this->session->data['shipping_methods']);
                    unset($this->session->data['shipping_method']);
                    unset($this->session->data['payment_methods']);
                    unset($this->session->data['payment_method']);
                    $this->cart->add($product['product_id'], $product['quantity'], $option_data);
                }
			
               $this->redirect(HTTPS_SERVER . 'checkout/cart');
            }
        }

        $this->language->load('account/cart');
        $this->load->model('tool/image');

        $this->document->title = $this->language->get('heading_title');

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
                'href'      => HTTP_SERVER . 'common/home',
                'text'      => $this->language->get('text_home'),
                'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
                'href'      => HTTP_SERVER . 'account/account',
                'text'      => $this->language->get('text_account'),
                'separator' => $this->language->get('text_separator')
        );

        $this->document->breadcrumbs[] = array(
                'href'      => HTTP_SERVER . 'account/cart',
                'text'      => $this->language->get('text_cart'),
                'separator' => $this->language->get('text_separator')
        );

        $quote_total = $this->cart->getTotalQuotes($this->customer->getId());
        if ($quote_total) {
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_quote'] = $this->language->get('text_quote');
            $this->data['text_price'] = $this->language->get('text_price');
            $this->data['text_date_added'] = $this->language->get('text_date_added');
            $this->data['text_qty'] = $this->language->get('text_qty');
            $this->data['text_products'] = $this->language->get('text_products');
            $this->data['text_total'] = $this->language->get('text_total');

            $this->data['button_cart'] = $this->language->get('button_cart');
            $this->data['button_view'] = $this->language->get('button_view');
            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['action'] = HTTP_SERVER . 'account/cart';

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            $this->data['orders'] = array();

            $results = $this->cart->getQuotes($this->customer->getId(),($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit'));

            foreach ($results as $result) {
                $this->data['carts'][] = array(
                        'quote_id'   => $result['quote_id'],
                        'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                        'products'  =>  $this->cart->getTotalQuoteProducts($result['quote_id']),
                        'quantity'  =>  $this->cart->getTotalQuoteQuantity($result['quote_id']),
                        'total'      => $this->currency->format($result['total']),
                        'href'      =>  HTTPS_SERVER . 'account/cart_detail&quote_id='.$result['quote_id'] ,
                        'checkout'  => HTTPS_SERVER . 'account/cart&quote_id='.$result['quote_id']
                );
            }

            $pagination = new Pagination();
            $pagination->total = $quote_total;
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_catalog_limit');
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = HTTP_SERVER . 'account/cart&page=%s';

            $this->data['pagination'] = $pagination->render();

            $this->data['continue'] = HTTPS_SERVER . 'account/account';

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/cart.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/account/cart.tpl';
            } else {
                $this->template = 'default/template/account/cart.tpl';
            }

            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_error'] = $this->language->get('text_error');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['continue'] = HTTPS_SERVER . 'account/account';

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->template = 'default/template/error/not_found.tpl';
            }

            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        }
    }
}
?>
