<?php
class ControllerCheckoutOnePage extends Controller {
	private $error = array();

  	public function index() {
            if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
                            $this->redirect(HTTPS_SERVER . 'checkout/cart');
            }

                    if ($this->customer->isLogged()) {
                            $this->redirect(HTTPS_SERVER . 'checkout/shipping');
            }

                    if (!$this->config->get('config_guest_checkout') || $this->cart->hasDownload()) {
                            $this->session->data['redirect'] = HTTPS_SERVER . 'checkout/shipping';

                            $this->redirect(HTTPS_SERVER . 'account/login');
            }

            $this->language->load('checkout/one_page');

            $this->document->title = $this->language->get('heading_title');

            $this->document->breadcrumbs = array();

            $this->document->breadcrumbs[] = array(
                    'href'      => HTTP_SERVER . 'common/home',
                    'text'      => $this->language->get('text_home'),
                    'separator' => FALSE
            );

            $this->document->breadcrumbs[] = array(
                    'href'      => HTTP_SERVER . 'checkout/cart',
                    'text'      => $this->language->get('text_cart'),
                    'separator' => $this->language->get('text_separator')
            );

            $this->document->breadcrumbs[] = array(
                    'href'      => HTTPS_SERVER . 'checkout/guest_step_1',
                    'text'      => $this->language->get('text_one_page'),
                    'separator' => $this->language->get('text_separator')
            );

            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_your_details'] = $this->language->get('text_your_details');
                    $this->data['text_your_address'] = $this->language->get('text_your_address');
                    $this->data['text_select'] = $this->language->get('text_select');

            $this->data['entry_firstname'] = $this->language->get('entry_firstname');
            $this->data['entry_lastname'] = $this->language->get('entry_lastname');
            $this->data['entry_email'] = $this->language->get('entry_email');
            $this->data['entry_telephone'] = $this->language->get('entry_telephone');
                    $this->data['entry_fax'] = $this->language->get('entry_fax');
            $this->data['entry_company'] = $this->language->get('entry_company');
            $this->data['entry_address_1'] = $this->language->get('entry_address_1');
            $this->data['entry_address_2'] = $this->language->get('entry_address_2');
            $this->data['entry_postcode'] = $this->language->get('entry_postcode');
            $this->data['entry_city'] = $this->language->get('entry_city');
            $this->data['entry_country'] = $this->language->get('entry_country');
            $this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}

		if (isset($this->error['address_1'])) {
			$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}

		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}

    	//$this->data['action'] = HTTPS_SERVER . 'checkout/guest_step_1';

		if (isset($this->request->post['firstname'])) {
    		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (isset($this->session->data['guest'])){
                   if (isset($this->session->data['guest'])) {
			$this->data['firstname'] = $this->session->data['guest']['firstname'];
                   }
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
    		$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (isset($this->session->data['guest'])){
			$this->data['lastname'] = $this->session->data['guest']['lastname'];
		} else {
			$this->data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
    		$this->data['email'] = $this->request->post['email'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['email'] = $this->session->data['guest']['email'];
		} else {
			$this->data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
    		$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['telephone'] = $this->session->data['guest']['telephone'];
		} else {
			$this->data['telephone'] = '';
		}

		if (isset($this->request->post['fax'])) {
    		$this->data['fax'] = $this->request->post['fax'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['fax'] = $this->session->data['guest']['fax'];
		} else {
			$this->data['fax'] = '';
		}

		if (isset($this->request->post['company'])) {
    		$this->data['company'] = $this->request->post['company'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['company'] = $this->session->data['guest']['company'];
		} else {
			$this->data['company'] = '';
		}

		if (isset($this->request->post['address_1'])) {
    		$this->data['address_1'] = $this->request->post['address_1'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['address_1'] = $this->session->data['guest']['address_1'];
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
    		$this->data['address_2'] = $this->request->post['address_2'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['address_2'] = $this->session->data['guest']['address_2'];
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
    		$this->data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['postcode'] = $this->session->data['guest']['postcode'];
		} else {
			$this->data['postcode'] = '';
		}

		if (isset($this->request->post['city'])) {
    		$this->data['city'] = $this->request->post['city'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['city'] = $this->session->data['guest']['city'];
		} else {
			$this->data['city'] = '';
		}

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['country_id'] = $this->session->data['guest']['country_id'];
		} else {
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($this->session->data['guest'])) {
			$this->data['zone_id'] = $this->session->data['guest']['zone_id'];
    	} else {
      		$this->data['zone_id'] = 'FALSE';
    	}

		$this->data['shipping'] = $this->cart->hasShipping();

		$this->load->model('localisation/country');

    	$this->data['countries'] = $this->model_localisation_country->getCountries();



        /***********************Shipping******************************************/
        /**
         *
         *
         */
        $this->load->model('checkout/extension');

		// Shipping Methods
		if ($this->cart->hasShipping() && !isset($this->session->data['shipping_methods'])) {
			$quote_data = array();

			$results = $this->model_checkout_extension->getExtensions('shipping');

			foreach ($results as $result) {
				$this->load->model('shipping/' . $result['key']);
                                $quote = '';
                                if(isset($this->session->data['guest']))
                                    $quote = $this->{'model_shipping_' . $result['key']}->getQuote($this->session->data['guest']);

				if ($quote) {
					$quote_data[$result['key']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
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

		// Payment Methods
		$method_data = array();

		$results = $this->model_checkout_extension->getExtensions('payment');

		foreach ($results as $result) {
			$this->load->model('payment/' . $result['key']);
                        $method = '';
                        if(isset($this->session->data['guest']))
                            $method = $this->{'model_payment_' . $result['key']}->getMethod($this->session->data['guest']);

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

		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_shipping_methods'] = $this->language->get('text_shipping_methods');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_payment_methods'] = $this->language->get('text_payment_methods');
		$this->data['text_comments'] = $this->language->get('text_comments');

		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_payment'] = $this->language->get('entry_payment');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
    		$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods']) && !$this->session->data['shipping_methods']) {
			$this->data['error_warning'] = $this->language->get('error_no_shipping');
		}

    	$this->data['action'] = HTTPS_SERVER . 'checkout/guest_step_2';

		if (isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$this->data['shipping_methods'] = array();
		}

   		if (isset($this->request->post['shipping_method'])) {
      		$this->data['shipping'] = $this->request->post['shipping_method'];
		} elseif (isset($this->session->data['shipping_method'])) {
			$this->data['shipping'] = $this->session->data['shipping_method']['id'];
    	} else {
      		$this->data['shipping'] = '';
    	}

	if (isset($this->session->data['payment_methods'])) {
        	$this->data['payment_methods'] = $this->session->data['payment_methods'];
      	} else {
        	$this->data['payment_methods'] = '';
		}

   	if (isset($this->request->post['payment_method'])) {
      		$this->data['payment'] = $this->request->post['payment_method'];
		} elseif (isset($this->session->data['payment_method'])) {
			$this->data['payment'] = $this->session->data['payment_method']['id'];
    	} else {
      		$this->data['payment'] = '';
    	}

		$this->load->model('localisation/country');

    	$this->data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->session->data['comment'])) {
    		$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), HTTP_SERVER . 'information/information&information_id=' . $this->config->get('config_checkout_id'), $information_info['title']);
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
                 /**********************************Confirm ************************/
                 /**
                  *
                  *
                  */
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		$this->load->model('checkout/extension');

		$sort_order = array();

		$results = $this->model_checkout_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);

			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
		}

		$sort_order = array();

		foreach ($total_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $total_data);

		$data = array();

		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');
		$data['store_url'] = $this->config->get('config_url');
		$data['customer_id'] = 0;
		$data['customer_group_id'] = $this->config->get('config_customer_group_id');
                if(isset($this->session->data['guest'])){
                    $data['firstname'] = $this->session->data['guest']['firstname'];
                    $data['lastname'] = $this->session->data['guest']['lastname'];
                    $data['email'] = $this->session->data['guest']['email'];
                    $data['telephone'] = $this->session->data['guest']['telephone'];
                    $data['fax'] = $this->session->data['guest']['fax'];
                }

		if ($this->cart->hasShipping()) {
                    if(isset($this->session->data['guest'])){
			$data['shipping_firstname'] = $this->session->data['guest']['firstname'];
			$data['shipping_lastname'] = $this->session->data['guest']['lastname'];
			$data['shipping_company'] = $this->session->data['guest']['company'];
			$data['shipping_address_1'] = $this->session->data['guest']['address_1'];
			$data['shipping_address_2'] = $this->session->data['guest']['address_2'];
			$data['shipping_city'] = $this->session->data['guest']['city'];
			$data['shipping_postcode'] = $this->session->data['guest']['postcode'];
			$data['shipping_zone'] = $this->session->data['guest']['zone'];
			$data['shipping_zone_id'] = $this->session->data['guest']['zone_id'];
			$data['shipping_country'] = $this->session->data['guest']['country'];
			$data['shipping_country_id'] = $this->session->data['guest']['country_id'];
			$data['shipping_address_format'] = $this->session->data['guest']['address_format'];
                    }
			if (isset($this->session->data['shipping_method']['title'])) {
				$data['shipping_method'] = $this->session->data['shipping_method']['title'];
			} else {
				$data['shipping_method'] = '';
			}
		} else {
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_zone'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_country'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_address_format'] = '';
			$data['shipping_method'] = '';
		}
                if(isset($this->session->data['guest'])){
                    $data['payment_firstname'] = $this->session->data['guest']['firstname'];
                    $data['payment_lastname'] = $this->session->data['guest']['lastname'];
                    $data['payment_company'] = $this->session->data['guest']['company'];
                    $data['payment_address_1'] = $this->session->data['guest']['address_1'];
                    $data['payment_address_2'] = $this->session->data['guest']['address_2'];
                    $data['payment_city'] = $this->session->data['guest']['city'];
                    $data['payment_postcode'] = $this->session->data['guest']['postcode'];
                    $data['payment_zone'] = $this->session->data['guest']['zone'];
                    $data['payment_zone_id'] = $this->session->data['guest']['zone_id'];
                    $data['payment_country'] = $this->session->data['guest']['country'];
                    $data['payment_country_id'] = $this->session->data['guest']['country_id'];
                    $data['payment_address_format'] = $this->session->data['guest']['address_format'];
                }

		if (isset($this->session->data['payment_method']['title'])) {
			$data['payment_method'] = $this->session->data['payment_method']['title'];
		} else {
			$data['payment_method'] = '';
		}

		$product_data = array();

		foreach ($this->cart->getProducts() as $product) {
      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
					'product_option_value_id' => $option['product_option_value_id'],
          			'name'                    => $option['name'],
          			'value'                   => $option['value'],
		  			'prefix'                  => $option['prefix']
        		);
      		}

      		$product_data[] = array(
        		'product_id' => $product['product_id'],
				'name'       => $product['name'],
        		'model'      => $product['model'],
        		'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'price'      => $product['price'],
        		'total'      => $product['total'],
				'tax'        => $this->tax->getRate($product['tax_class_id'])
      		);
    	}

		$data['products'] = $product_data;
		$data['totals'] = $total_data;
                if(isset($this->session->data['comment']))
		$data['comment'] = $this->session->data['comment'];
		$data['total'] = $total;
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency'] = $this->currency->getCode();
		$data['value'] = $this->currency->getValue($this->currency->getCode());

		if (isset($this->session->data['coupon'])) {
			$this->load->model('checkout/coupon');

			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

			if ($coupon) {
				$data['coupon_id'] = $coupon['coupon_id'];
			} else {
				$data['coupon_id'] = 0;
			}
		} else {
			$data['coupon_id'] = 0;
		}

		$data['ip'] = $this->request->server['REMOTE_ADDR'];

		$this->load->model('checkout/order');
                if(isset($this->session->data['guest']) && isset($this->session->data['payment_method']) && isset($this->session->data['shipping_method']))
                    $this->session->data['order_id'] = $this->model_checkout_order->create($data);


    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
    	$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_payment_address'] = $this->language->get('text_payment_address');
    	$this->data['text_payment_method'] = $this->language->get('text_payment_method');
    	$this->data['text_comment'] = $this->language->get('text_comment');
    	$this->data['text_change'] = $this->language->get('text_change');
    	$this->data['text_coupon'] = $this->language->get('text_coupon');

		$this->data['column_product'] = $this->language->get('column_product');
    	$this->data['column_model'] = $this->language->get('column_model');
    	$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_price'] = $this->language->get('column_price');
    	$this->data['column_total'] = $this->language->get('column_total');

		$this->data['entry_coupon'] = $this->language->get('entry_coupon');

		$this->data['button_coupon'] = $this->language->get('button_coupon');

		if (isset($this->error['warning'])) {
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

		$this->data['action'] = HTTP_SERVER . 'checkout/guest_step_3';

		if (isset($this->request->post['coupon'])) {
			$this->data['coupon'] = $this->request->post['coupon'];
		} elseif (isset($this->session->data['coupon'])) {
			$this->data['coupon'] = $this->session->data['coupon'];
		} else {
			$this->data['coupon'] = '';
		}

		if ($this->cart->hasShipping()) {
                    if (isset($this->session->data['shipping_method']['title'])) {
			$this->data['shipping_method'] = $this->session->data['shipping_method']['title'];
                    } else {
			$this->data['shipping_method'] = '';
                    }
                }
                    $this->data['checkout_shipping'] = HTTPS_SERVER . 'checkout/guest_step_2';

                    $this->data['checkout_shipping_address'] = HTTPS_SERVER . 'checkout/guest_step_1';


		if (isset($this->session->data['payment_method']['title'])) {
			$this->data['payment_method'] = $this->session->data['payment_method']['title'];
		} else {
			$this->data['payment_method'] = '';
		}

    	$this->data['checkout_payment'] = HTTPS_SERVER . 'checkout/guest_step_2';

    	$this->data['checkout_payment_address'] = HTTPS_SERVER . 'checkout/guest_step_1';

    	$this->data['products'] = array();

    	foreach ($this->cart->getProducts() as $product) {
      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value']
        		);
      		}

      		$this->data['products'][] = array(
				'product_id' => $product['product_id'],
        		'name'       => $product['name'],
        		'model'      => $product['model'],
        		'option'     => $option_data,
        		'quantity'   => $product['quantity'],
				'tax'        => $this->tax->getRate($product['tax_class_id']),
        		'price'      => $this->currency->format($product['price']),
        		'total'      => $this->currency->format($product['total']),
				'href'       => HTTP_SERVER . 'product/product&product_id=' . $product['product_id']
      		);
    	}

		$this->data['totals'] = $total_data;

                if(isset($this->session->data['comment']))
                    $this->data['comment'] = nl2br($this->session->data['comment']);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/one_page.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/one_page.tpl';
		} else {
			$this->template = 'default/template/checkout/one_page.tpl';
		}

		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
  	}

 
  	public function zone() {
		$output = '<option value="FALSE">' . $this->language->get('text_select') . '</option>';

		$this->load->model('localisation/zone');

    	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';

	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}

	    	$output .= '>' . $result['name'] . '</option>';
    	}

		if (!$results) {
			if (!$this->request->get['zone_id']) {
		  		$output .= '<option value="0" selected="selected">' . $this->language->get('text_none') . '</option>';
			} else {
				$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
			}
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
  	}
        public function callAddress(){
            $this->language->load('checkout/one_page');
            $error=array();
            if ((strlen(utf8_decode($this->request->post['firstname'])) < 3) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
                    $error['firstname'] = $this->language->get('error_firstname');

            }

            if ((strlen(utf8_decode($this->request->post['lastname'])) < 3) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
                    $error['lastname'] = $this->language->get('error_lastname');
            }

                    $pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';

            if (!preg_match($pattern, $this->request->post['email'])) {
                    $error['email'] = $this->language->get('error_email');
            }

            if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
                    $error['telephone'] = $this->language->get('error_telephone');
            }

            if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128)) {
                    $error['address_1'] = $this->language->get('error_address_1');
            }

            if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
                    $error['city'] = $this->language->get('error_city');
            }

            if ($this->request->post['country_id'] == 'FALSE') {
                    $error['country'] = $this->language->get('error_country');
            }

            if ($this->request->post['zone_id'] == 'FALSE') {
                    $error['zone'] = $this->language->get('error_zone');
            }

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$error) {
                    $this->session->data['guest']['firstname'] = $this->request->post['firstname'];
                    $this->session->data['guest']['lastname'] = $this->request->post['lastname'];
                    $this->session->data['guest']['email'] = $this->request->post['email'];
                    $this->session->data['guest']['telephone'] = $this->request->post['telephone'];
                    $this->session->data['guest']['fax'] = $this->request->post['fax'];
                    $this->session->data['guest']['company'] = $this->request->post['company'];
                    $this->session->data['guest']['address_1'] = $this->request->post['address_1'];
                    $this->session->data['guest']['address_2'] = $this->request->post['address_2'];
                    $this->session->data['guest']['postcode'] = $this->request->post['postcode'];
                    $this->session->data['guest']['city'] = $this->request->post['city'];
                    $this->session->data['guest']['country_id'] = $this->request->post['country_id'];
                    $this->session->data['guest']['zone_id'] = $this->request->post['zone_id'];

                    if ($this->cart->hasShipping()) {
                            $this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
                    }

                    $this->load->model('localisation/country');

                    $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

                    if ($country_info) {
                            $this->session->data['guest']['country'] = $country_info['name'];
                            $this->session->data['guest']['iso_code_2'] = $country_info['iso_code_2'];
                            $this->session->data['guest']['iso_code_3'] = $country_info['iso_code_3'];
                            $this->session->data['guest']['address_format'] = $country_info['address_format'];
                    } else {
                            $this->session->data['guest']['country'] = '';
                            $this->session->data['guest']['iso_code_2'] = '';
                            $this->session->data['guest']['iso_code_3'] = '';
                            $this->session->data['guest']['address_format'] = '';
                    }

                    $this->load->model('localisation/zone');

                    $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

                    if ($zone_info) {
                            $this->session->data['guest']['zone'] = $zone_info['name'];
                            $this->session->data['guest']['zone_code'] = $zone_info['code'];
                    } else {
                            $this->session->data['guest']['zone'] = '';
                            $this->session->data['guest']['zone_code'] = '';
                    }

                    unset($this->session->data['shipping_methods']);
                    unset($this->session->data['shipping_method']);
            }
            if(!$error){
                $arr=array();
                $arr['shipping_methods'] = $this->getShipping();
                $arr['payment_methods'] = $this->getPayment();
                $this->response->setOutput(json_encode($arr));
            }
            else{
                $error[0] = 'error';
                $this->response->setOutput(json_encode($error));
            }
        }
        private function getPayment() {
				// Payment Methods
		$method_data = array();

		$results = $this->model_checkout_extension->getExtensions('payment');

		foreach ($results as $result) {
			$this->load->model('payment/' . $result['key']);

			$method = $this->{'model_payment_' . $result['key']}->getMethod($this->session->data['guest']);

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
                return $method_data;
        }
        
        private function getShipping(){
		$this->load->model('checkout/extension');

                unset($this->session->data['shipping_methods']);
		// Shipping Methods
		if ($this->cart->hasShipping()) {
			$quote_data = array();

			$results = $this->model_checkout_extension->getExtensions('shipping');

			foreach ($results as $result) {
				$this->load->model('shipping/' . $result['key']);

				$quote = $this->{'model_shipping_' . $result['key']}->getQuote($this->session->data['guest']);
				if ($quote) {
					$quote_data[$result['key']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}

			$sort_order = array();

			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $quote_data);
                        $this->session->data['shipping_methods'] = $quote_data;
                       return $quote_data;
		}
        }
        public function callShipping(){
            $this->language->load('checkout/one_page');
            $error=array();
            if ($this->cart->hasShipping()) {
                if (!isset($this->request->post['shipping_method']) || $this->request->post['shipping_method'] == '') {
                            $error['warning'] = $this->language->get('error_shipping');
                    } else {
                            $shipping = explode('.', $this->request->post['shipping_method']);

                            if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                                    $error['warning'] = $this->language->get('error_shipping');
                            }
                    }
            }

    	if (!isset($this->request->post['payment_method'])) {
	  		$error['warning'] = $this->language->get('error_payment');
		} else {
			if (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$this->error['warning'] = $this->language->get('error_payment');
			}
		}

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info) {
    			if (!isset($this->request->post['agree'])) {
      				$error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
    			}
			}
		}


            if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$error) {
                    if (isset($this->request->post['shipping_method'])) {
                            $shipping = explode('.', $this->request->post['shipping_method']);

                            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                    }
                    $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

                    $this->session->data['comment'] = $this->request->post['comment'];

                    }
            if(!$error){
                $product_data = $this->getProduct();
                $this->response->setOutput(json_encode($product_data));
            }
            else{
                $error[0] = 'error';
                $this->response->setOutput(json_encode($error));
            }
        }
        private function getProduct(){
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		$this->load->model('checkout/extension');

		$sort_order = array();

		$results = $this->model_checkout_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);

			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
		}

		$sort_order = array();

		foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
                $data = array();

		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');
		$data['store_url'] = $this->config->get('config_url');
		$data['customer_id'] = 0;
		$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		$data['firstname'] = $this->session->data['guest']['firstname'];
		$data['lastname'] = $this->session->data['guest']['lastname'];
		$data['email'] = $this->session->data['guest']['email'];
		$data['telephone'] = $this->session->data['guest']['telephone'];
		$data['fax'] = $this->session->data['guest']['fax'];

		if ($this->cart->hasShipping()) {
			$data['shipping_firstname'] = $this->session->data['guest']['firstname'];
			$data['shipping_lastname'] = $this->session->data['guest']['lastname'];
			$data['shipping_company'] = $this->session->data['guest']['company'];
			$data['shipping_address_1'] = $this->session->data['guest']['address_1'];
			$data['shipping_address_2'] = $this->session->data['guest']['address_2'];
			$data['shipping_city'] = $this->session->data['guest']['city'];
			$data['shipping_postcode'] = $this->session->data['guest']['postcode'];
			$data['shipping_zone'] = $this->session->data['guest']['zone'];
			$data['shipping_zone_id'] = $this->session->data['guest']['zone_id'];
			$data['shipping_country'] = $this->session->data['guest']['country'];
			$data['shipping_country_id'] = $this->session->data['guest']['country_id'];
			$data['shipping_address_format'] = $this->session->data['guest']['address_format'];

			if (isset($this->session->data['shipping_method']['title'])) {
				$data['shipping_method'] = $this->session->data['shipping_method']['title'];
			} else {
				$data['shipping_method'] = '';
			}
		} else {
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_zone'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_country'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_address_format'] = '';
			$data['shipping_method'] = '';
		}

		$data['payment_firstname'] = $this->session->data['guest']['firstname'];
		$data['payment_lastname'] = $this->session->data['guest']['lastname'];
		$data['payment_company'] = $this->session->data['guest']['company'];
		$data['payment_address_1'] = $this->session->data['guest']['address_1'];
		$data['payment_address_2'] = $this->session->data['guest']['address_2'];
		$data['payment_city'] = $this->session->data['guest']['city'];
		$data['payment_postcode'] = $this->session->data['guest']['postcode'];
		$data['payment_zone'] = $this->session->data['guest']['zone'];
		$data['payment_zone_id'] = $this->session->data['guest']['zone_id'];
		$data['payment_country'] = $this->session->data['guest']['country'];
		$data['payment_country_id'] = $this->session->data['guest']['country_id'];
		$data['payment_address_format'] = $this->session->data['guest']['address_format'];

		if (isset($this->session->data['payment_method']['title'])) {
			$data['payment_method'] = $this->session->data['payment_method']['title'];
		} else {
			$data['payment_method'] = '';
		}
		$product_data = array();

		foreach ($this->cart->getProducts() as $product) {
                    $option_data = array();

                    foreach ($product['option'] as $option) {
                            $option_data[] = array(
                                            'product_option_value_id' => $option['product_option_value_id'],
                                    'name'                    => $option['name'],
                                    'value'                   => $option['value'],
                                            'prefix'                  => $option['prefix']
                            );
                    }

                    $product_data[] = array(
                            'product_id' => $product['product_id'],
                                    'name'       => $product['name'],
                            'model'      => $product['model'],
                            'option'     => $option_data,
                                    'download'   => $product['download'],
                                    'quantity'   => $product['quantity'],
                                    'price'      => $product['price'],
                            'total'      => $product['total'],
                                    'tax'        => $this->tax->getRate($product['tax_class_id']),
                        'href'       => HTTP_SERVER . 'product/product&product_id=' . $product['product_id']
                    );
                }

		$data['products'] = $product_data;
		$data['totals'] = $total_data;
		$data['comment'] = $this->session->data['comment'];
		$data['total'] = $total;
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency'] = $this->currency->getCode();
		$data['value'] = $this->currency->getValue($this->currency->getCode());

		if (isset($this->session->data['coupon'])) {
			$this->load->model('checkout/coupon');

			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

			if ($coupon) {
				$data['coupon_id'] = $coupon['coupon_id'];
			} else {
				$data['coupon_id'] = 0;
			}
		} else {
			$data['coupon_id'] = 0;
		}

		$data['ip'] = $this->request->server['REMOTE_ADDR'];

		$this->load->model('checkout/order');

		$this->session->data['order_id'] = $this->model_checkout_order->create($data);
                return $data;
        }
}
?>
