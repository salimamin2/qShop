<?php

class ControllerCheckoutSuccess extends Controller {

    public function index() {
		
    	$this->data['checkout_invoice'] = false;

		if (isset($this->session->data['order_id'])) {

			$this->data['checkout_invoice'] = true;
			if($this->session->data['hdn_customer_id']){
				$customer_id = $this->session->data['hdn_customer_id'];
			} else {
				$customer_id = 0;
			}

			$this->invoice($this->session->data['order_id'], $customer_id);

		    $this->cart->clear();

		    unset($this->session->data['shipping_method']);
		    unset($this->session->data['shipping_methods']);
		    unset($this->session->data['payment_method']);
		    unset($this->session->data['payment_methods']);
		    unset($this->session->data['guest']);
		    unset($this->session->data['comment']);
		    unset($this->session->data['order_id']);
		    unset($this->session->data['coupon']);
		    unset($this->session->data['input_points']);
		    unset($this->session->data['hdn_customer_id']);
		    unset($this->session->data['same_payment']);

			unset($this->session->data['firstname']);
			unset($this->session->data['lastname']);
			unset($this->session->data['pemail']);
			unset($this->session->data['telephone']);
			unset($this->session->data['company']);
			unset($this->session->data['address_1']);
			unset($this->session->data['address_2']);
			unset($this->session->data['country_id']);
			unset($this->session->data['city']);
			unset($this->session->data['zone_id']);
			unset($this->session->data['postcode']);

		}

		$this->language->load('checkout/success');

		if (isset ($this->request->get ['decision'])) {
			if ($this->request->get ['decision'] == 'ACCEPT')  {
				$this->document->title = $this->language->get('heading_title');
			} else {
				$this->document->title = $this->language->get('heading_title_fail');
			}
		} else {
			$this->document->title = $this->language->get('heading_title');
		}

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
		    'href' => makeUrl('common/home', array(), true),
		    'text' => $this->language->get('text_home'),
		    'separator' => $this->language->get('text_separator')
		);


		$this->document->breadcrumbs[] = array(
		    'href' => makeUrl('checkout/cart', array(), true, true),
		    'text' => $this->language->get('text_basket'),
		    'separator' => $this->language->get('text_separator')
		);

		$this->document->breadcrumbs[] = array(
		    'href' => makeUrl('checkout/confirm', array(), true, true),
		    'text' => $this->language->get('text_confirm'),
		    'separator' => $this->language->get('text_separator')
		);

		$this->document->breadcrumbs[] = array(
		    'href' => makeUrl('checkout/success', array(), true, true),
		    'text' => $this->language->get('text_success'),
		    'separator' => false
		);

		$this->data['order_id'] = '';
		if (isset($this->session->data['order_id'])) {
		    $this->data['order_id'] = $this->session->data['order_id'];
		} else if (isset($this->request->get['order_id'])) {
		    $this->data['order_id'] = $this->request->get['order_id'];
		}
		
		if (isset ($this->request->get ['decision'])) {
			if ($this->request->get ['decision'] == 'ACCEPT')  {
				$this->data['heading_title'] = $this->language->get('heading_title');
				//$this->data['text_message'] = sprintf($this->language->get('text_message'), HTTPS_SERVER . 'index.php?route=account/account', HTTPS_SERVER . 'index.php?route=account/history', HTTP_SERVER . 'index.php?route=information/contact');
				$this->data['text_message'] = sprintf($this->language->get('text_message'), $this->request->get['order_id']);
			} else {
				$this->data['heading_title'] = $this->language->get('heading_title_fail');
				$this->data['text_message'] = sprintf($this->language->get('text_message_fail'), $this->request->get['msg'], HTTP_SERVER . 'index.php?route=information/contact');
			}
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
			//$this->data['text_message'] = sprintf($this->language->get('text_message'), HTTPS_SERVER . 'index.php?route=account/account', HTTPS_SERVER . 'index.php?route=account/history', HTTP_SERVER . 'index.php?route=information/contact');
			$this->data['text_message'] = sprintf($this->language->get('text_message'), $this->request->get['order_id']);
		}

		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['continue'] = makeUrl('common/home', array(), true);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
		    $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
		    $this->template = 'default/template/common/success.tpl';
		}

		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function invoice($order_id, $customer_id){
		$this->language->load('account/invoice');

        $this->load->model('account/order');
        $this->load->model('tool/image');

        $order_info = $this->model_account_order->getOrder($order_id, $customer_id);

        if ($order_info) {
            $this->data['heading_invoice'] = $this->language->get('heading_title');

            $this->data['text_invoice_id'] = $this->language->get('text_invoice_id');
            $this->data['text_order_id'] = $this->language->get('text_order_id');
            $this->data['text_email'] = $this->language->get('text_email');
            $this->data['text_telephone'] = $this->language->get('text_telephone');
            $this->data['text_fax'] = $this->language->get('text_fax');
            $this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
            $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $this->data['text_payment_address'] = $this->language->get('text_payment_address');
            $this->data['text_payment_method'] = $this->language->get('text_payment_method');
            $this->data['text_order_history'] = $this->language->get('text_order_history');
            $this->data['text_product'] = $this->language->get('text_product');
            $this->data['text_quantity'] = $this->language->get('text_quantity');
            $this->data['text_price'] = $this->language->get('text_price');
            $this->data['text_total'] = $this->language->get('text_total');
            $this->data['text_comment'] = $this->language->get('text_comment');

            $this->data['column_date_added'] = $this->language->get('column_date_added');
            $this->data['column_status'] = $this->language->get('column_status');
            $this->data['column_comment'] = $this->language->get('column_comment');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['order_id'] = $order_id;

            if ($order_info['invoice_id']) {
                $this->data['invoice_id'] = $order_info['invoice_prefix'] . $order_info['invoice_id'];
            } else {
                $this->data['invoice_id'] = '';
            }

            $this->data['email'] = $order_info['email'];
            $this->data['telephone'] = $order_info['telephone'];
            $this->data['fax'] = $order_info['fax'];

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
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
                'firstname' => $order_info['shipping_firstname'],
                'lastname' => $order_info['shipping_lastname'],
                'company' => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city' => $order_info['shipping_city'],
                'postcode' => $order_info['shipping_postcode'],
                'zone' => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country' => $order_info['shipping_country']
            );

            $this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->data['shipping_method'] = $order_info['shipping_method'];

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
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
                'firstname' => $order_info['payment_firstname'],
                'lastname' => $order_info['payment_lastname'],
                'company' => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city' => $order_info['payment_city'],
                'postcode' => $order_info['payment_postcode'],
                'zone' => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country' => $order_info['payment_country']
            );

            $this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->data['payment_method'] = $order_info['payment_method'];

            $this->data['currency_code'] = $this->currency->getSymbol();
            $currency_symbol=$this->data['currency_code']['symbol_left'];

            $this->data['products'] = array();
            $products = $this->model_account_order->getOrderProducts($order_id);
            //d($products);
            if(isset($this->session->data['Sgift_card_id']))
            {
                $this->data['gift_card_id']=$this->session->data['Sgift_card_id'];
            }
            else if(isset($this->session->data['gift_card_id']))
            {
                $this->data['gift_card_id'] = $this->session->data['gift_card_id'];
            }
            else
            {
                $this->data['gift_card_id']='';
            }

            foreach ($products as $product) {

                $details = $this->model_account_order->getOrderDetailOptions($order_id, $product['order_product_id']);

                $detail_data = array();

                foreach ($details as $option) {
                    $detail_data[] = array(
                        'name' => $option['option_name'],
                        'value' => $option['value_name'],
                        'color_code' => $option['value_id']
                    );
                }
                $options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);
                $option_data = array();

                foreach ($options as $option) {
                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => $option['value'],
                    );
                }

                if ($product['model'] && $detail_data && $detail_data[0]['color_code']) {
                    //$image = $product['image'];
                    $image = 'data/products/'.$product['model'].'_'.$detail_data[0]['color_code'].'.jpg';
                } else {
                    if($product['image'] && file_exists(DIR_IMAGE. $product['image'])){
                        $image = $product['image'];
                    } else {
                        $image = 'no_image.jpg';
                    }
                }
            if($product['model']){
                $this->data['text_model'] = $this->language->get('text_model');

            }
                $this->data['products'][] = array(
                    'name' => $product['name'],
                    'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
                    'model' => $product['model'],
                    'option' => $option_data,
                    'detail' => $detail_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'], $this->currency->getCode(),$this->currency->getValue()),
                    'total' => $this->currency->format($product['total'], $this->currency->getCode(),$this->currency->getValue())
                );
            }
            
            unset($this->session->data['Sgift_card_id']);
            unset($this->session->data['Scard_name']);
            unset($this->session->data['Samount_for_gift']);
            unset($this->session->data['Sselect_own']);
            unset($this->session->data['Semail']);
            unset($this->session->data['Ssender_name']);
            unset($this->session->data['Smessage']);
            unset($this->session->data['Squantity']);
            unset($this->session->data['Sdelivery_date']);

            unset($this->session->data['firstname']);
            unset($this->session->data['lastname']);
            unset($this->session->data['pemail']);
            unset($this->session->data['telephone']);
            unset($this->session->data['company']);
            unset($this->session->data['address_1']);
            unset($this->session->data['address_2']);
            unset($this->session->data['city']);
            unset($this->session->data['postcode']);
            unset($this->session->data['country_id']);
            unset($this->session->data['zone_id']);

            $this->data['gift_order']=$this->session->data['order_type'];

            $this->data['totals'] = $this->model_account_order->getOrderTotals($order_id);

            $this->data['comment'] = $order_info['comment'];

            $this->data['historys'] = array();

            $results = $this->model_account_order->getOrderHistories($order_id);

            foreach ($results as $result) {
                $this->data['historys'][] = array(
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'status' => $result['status'],
                    'comment' => nl2br($result['comment'])
                );
            }

            $this->data['continue'] = HTTPS_SERVER . 'index.php?route=account/history';
            $this->data['continue'] = HTTPS_SERVER . 'index.php?route=account/history';

			$this->data['print'] = HTTPS_SERVER . 'index.php?route=account/invoice&print=1&order_id='.$order_id;

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/invoice.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/account/invoice.tpl';
            } else {
                $this->template = 'default/template/account/invoice.tpl';
            }

            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_error'] = $this->language->get('text_error');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['continue'] = HTTP_SERVER . 'index.php?route=account/history';

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