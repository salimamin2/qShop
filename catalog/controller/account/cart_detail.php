<?php 
class ControllerAccountCartDetail extends Controller {
	public function index() {
            if (!$this->customer->isLogged()) {
                    $this->session->data['redirect'] = HTTPS_SERVER . 'account/history';

                            $this->redirect(HTTPS_SERVER . 'account/login');
            }

            $this->language->load('account/cart_detail');
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
            $this->document->breadcrumbs[] = array(
                    'href'      => HTTP_SERVER . 'account/cart_detail',
                    'text'      => $this->language->get('text_cart_detail'),
                    'separator' => $this->language->get('text_separator')
            );

            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_quote'] = $this->language->get('text_quote');
            $this->data['text_price'] = $this->language->get('text_price');
            $this->data['text_date_added'] = $this->language->get('text_date_added');
            $this->data['text_qty'] = $this->language->get('text_qty');
            $this->data['text_option'] = $this->language->get('text_option');
            $this->data['text_total'] = $this->language->get('text_total');

            $this->data['button_back'] = $this->language->get('button_back');
            $this->data['button_continue'] = $this->language->get('button_continue');
            $this->data['quote_id'] = $this->request->get['quote_id'];
            $this->data['products'] = array();

            $results = $this->cart->getQuoteProducts($this->request->get['quote_id']);

            foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $result['image'];
                    } else {
                        $image = 'no_image.jpg';
                    }
                    $this->data['products'][] = array(
                        'product_name'   => $result['product_name'],
                        'model'   => $result['model'],
                        'thumb'   => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
                        'options'    => $result['options'],
                        'details'    => $result['details'],
                        'quantity'   => $result['quantity'],
                        'price'      => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')))
                    );
              /* $this->data['products'][] = array(
                    'quote_id'   => $result['quote_id'],
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'products' => $product_data,
                    'total'      => $this->currency->format($result['total']),
                );*/
            }

            $this->data['continue'] = HTTPS_SERVER . 'account/account';
            $this->data['back'] = HTTPS_SERVER . 'account/cart';

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/cart_detail.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/account/cart_detail.tpl';
            } else {
                    $this->template = 'default/template/account/cart_detail.tpl';
            }

            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        }
}
?>
