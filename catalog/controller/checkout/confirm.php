<?php

class ControllerCheckoutConfirm extends Controller {

    private $error = array();

    public function index() {
        $this->load->model('setting/setting');
		$this->language->load('checkout/confirm');
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
		    $this->session->data['error_warning'] = $this->language->get('error_stock');
		    // $this->redirect(makeUrl('checkout/cart', array(), true, true));
		    $this->redirect(makeUrl('common/home',array(),true));
		}

		if (!$this->cart->hasShipping()) {
	        $this->session->data['error_warning'] = __("Some Product(s) in your cart cannot be shipped");
		    // $this->redirect(makeUrl('checkout/cart', array(), true, true));
		    $this->redirect(makeUrl('common/home',array(),true));
		}

		$this->session->data['form_code'] = date('znY');
		$form_code = $this->session->data['form_code'];

		$this->data['login_link'] = makeUrl('account/login', array(), true, true);

		$this->data['form_code'] = $form_code;

		$this->document->title = $this->language->get('heading_title');

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
		    'separator' => false
		);

		$this->load->model('account/address');
		$this->data['validateUrl'] = makeUrl('checkout/confirm/validateForm',array(),true);

		$this->document->addScript("catalog/view/javascript/jquery/checkout.js", Document::POS_END);

		$this->getForm();
    }

    private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_new_address'] = $this->language->get('text_new_address');
		$this->data['text_entries'] = $this->language->get('text_entries');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->error['shipping'])) {
		    $this->data['error_shipping'] = $this->error['shipping'];
		} else {
		    $this->data['error_shipping'] = array();
		}

		if (isset($this->error['payment'])) {
		    $this->data['error_payment'] = $this->error['payment'];
		} else {
		    $this->data['error_payment'] = array();
		}
		if (isset($this->error['warning'])) {
		    $this->data['error_warning'] = $this->error['warning'];
		} else {
		    $this->data['error_warning'] = array();
		}
		if (isset($this->request->get['eError'])) {
			$this->data['error_warning'] = "Error: A general problem has occurred with the transaction. Please try again.";
		}
		$this->data['action'] = makeUrl('checkout/address');


		if ($this->customer->getId() && $this->customer->getAddressId()) {
		    $this->data['default'] = $this->customer->getAddressId();
		} else {
		    $this->data['default'] = '';
		}

		$this->data['addresses'] = array();
		$this->data['shipping_addresses'] = array();

		$this->data['back'] = makeUrl('checkout/cart', array(), true, true);
		if ($this->customer->getId()) {
		    $results = $this->model_account_address->getAddresses();

		    foreach ($results as $result) {
		    	$type = ($result['address_type'] == 1 ? 'billing' : 'shipping');
				$this->data[$type . '_addresses'][] = array(
				    'address_id' => $result['address_id'],
				    'address' => $result['address_1'] . ',' . $result['city'] . ', ' . (($result['zone']) ? $result['zone'] . ', ' : FALSE) . (($result['postcode']) ? $result['postcode'] . ', ' : FALSE) . '' . $result['country'] . ',' . ($result['company'] ? $result['company'] : '-'),
				    'href' => HTTPS_SERVER . 'account/address&address_id=' . $result['address_id']
				);
		    }
		}

		$this->data['zone_id'] = '';
		$this->data['country_id'] = '';
		$this->data['shipping_address']['zone_id'] = '';
		$this->data['shipping_address']['country_id'] = $this->config->get('config_country_id');
		$this->data['payment_address']['zone_id'] = '';
		$this->data['payment_address']['country_id'] = $this->config->get('config_country_id');

		if (isset($this->request->post['shipping'])) {
		    $this->data['shipping_address'] = $this->request->post['shipping'];
		} else {
		    $this->data['shipping_address'] = array();
		}
		$this->data['shipping_address_id'] = '';
		if (isset($this->session->data['shipping_address_id']) && $this->session->data['shipping_address_id']) {
		    $this->data['shipping_address_id'] = $this->session->data['shipping_address_id'];
		} else {
		    $this->data['shipping_address']['country_id'] = $this->config->get('config_country_id');
		    $this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->request->post['payment'])) {
		    $this->data['payment_address'] = $this->request->post['payment'];
		} else {
		    $this->data['payment_address'] = array();
		}

		if(isset($this->session->data['checkout_register'])){
			$this->data['payment_address']['firstname'] = $this->data['shipping_address']['firstname'] = $this->customer->getFirstName();
			$this->data['payment_address']['lastname'] = $this->data['shipping_address']['lastname'] = $this->customer->getLastName();
		}

		$this->data['payment_address_id'] = '';
		if (!$this->request->post['payment'] && isset($this->session->data['payment_address_id']) && $this->session->data['payment_address_id']) {
		    $this->data['payment_address_id'] = $this->session->data['payment_address_id'];
		}
	    else {
	        $this->data['payment_address']['country_id'] = $this->config->get('config_country_id');
	    }

		/*
		 * Payment Methods
		 */
		$this->load->model('checkout/extension');
		$method_data = array();

		$results = $this->model_checkout_extension->getExtensions('payment');
		foreach ($results as $result) {
		    $this->load->model('payment/' . $result['key']);
		    $method = $this->{'model_payment_' . $result['key']}->getMethod($this->data['payment_address']);
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
		if (isset($this->session->data['payment_methods'])) {
		    $this->data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
		    $this->data['payment_methods'] = array();
		}

		if (isset($this->session->data['payment_method']['id'])) {
		    $this->data['payment'] = $this->session->data['payment_method']['id'];
		} else {
		    $this->data['payment'] = '';
		}

		if(isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods'];
		}
		else {
			$this->data['shipping_methods'] = array();
		}

		if($this->config->get('free_total')) {
			$this->data['free_shipping_amount'] = $this->config->get('free_total');
		}
		else {
			$this->data['free_shipping_amount'] = 50;
		}

		$this->data['cart_subtotal'] = $this->cart->getSubTotal();

		/*
		 * Comment
		 */

		if (isset($this->session->data['comment'])) {
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
			$this->data['text_agree'] = sprintf($this->language->get('agree_text'), makeUrl('information/information', array('information_id=' . $this->config->get('config_checkout_id')), true));
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

        $this->data['action'] = makeUrl('account/login', array(), true, true);
        $this->data['register'] = makeUrl('account/create', array(), true, true);

        $this->data['forgotten_password'] = makeUrl('account/forgotten', array(), true, true);

        $this->data['success_action'] = makeUrl('checkout/confirm', array(), true, true);

        if ($this->cart->hasProducts()) {
            $this->data['success_register'] = makeUrl('checkout/confirm', array(), true, true);
        }
        else{
            $this->data['success_register'] = makeUrl('account/success', array(), true, true);
        }
        $this->load->model('tool/seo_url');
        $this->load->model('tool/image');

        $this->data['products'] = array();
        $cart_qty = 0;

        foreach ($this->cart->getProducts() as $result) {
            $option_data = array();

            foreach ($result['option'] as $option) {
                $option_data[] = array(
                    'name' => $option['name'],
                    'value' => $option['value']
                );
            }

            if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $result['image'];
            } else {
                $image = 'no_image.jpg';
            }

            $cart_qty += $result['quantity'];

            $this->data['products'][] = array(
                'key' => $result['key'],
                'name' => $result['name'],
                'model' => $result['model'],
                'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
                'option' => $option_data,
                'stock_status_id' => $result['stock_status_id'],
                'quantity' => $result['quantity'],
                'stock' => $result['stock'],
                'product_type_id' => $result['product_type_id'],
                'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
                'total' => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
                'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
                'meta_link' => QS::getMetaLink(isset($result['meta_link'])?$result['meta_link']:'', $result['name']),
                'alt_title' => QS::getMetaLink(isset($result['img_alt'])?$result['img_alt']:'', $result['name']),
                'delete' => makeUrl('checkout/cart', array('remove=' . $result['key']), true, true),
                'wishlist' => makeUrl('account/wishlist', array(), true) . '&product_id=' . $result['product_id'],
            );
        }

		$this->data['coupon_action'] = makeUrl('checkout/confirm/coupon', array(), true, true);
	    $this->data['shipping_action'] = makeUrl('checkout/confirm/shipping',array(),true,true);
		$this->data['shipping_method_action'] = makeUrl('checkout/confirm/getShippingMethods',array(),true,true);
		$this->data['ajax_loader'] = HTTP_IMAGE . "opc-ajax-loader.gif";

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();
		$this->data['coupon_action'] = makeUrl('checkout/confirm/coupon', array(), true, true);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
		    $this->template = $this->config->get('config_template') . '/template/checkout/confirm.tpl';
		} else {
		    $this->template = 'default/template/checkout/confirm.tpl';
		}

		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    // Save comment before save
    // AJAX function
    public function comment() {
	$this->session->data['comment'] = strip_tags($this->request->post['comment']);
    }

    public function save() {
        $this->load->model('setting/setting');
	$this->language->load('checkout/confirm');
	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
	    unset($this->session->data['payment_address_id']);
	    unset($this->session->data['shipping_address_id']);

	    $this->load->model('localisation/country');
	    $this->load->model('localisation/zone');
	    $form_code = $this->session->data['form_code'];
	    $codeYesterday = date('znY', time() - 86400);
	    if (isset($this->request->post['shipping'][$codeYesterday])) {
		    $form_code = $codeYesterday;
	    }
	    if (isset($this->request->post['fax' . $form_code]) && trim($this->request->post['fax' . $form_code]) != '') {
		$this->error['warning'] = "Error: your request could not be completed.";
		unset($this->request->post);
	    }

	    if (!isset($this->request->post['payment_method'][$form_code]) || !isset($this->session->data['payment_methods'][$this->request->post['payment_method'][$form_code]])) {
		$this->error['warning'] = __('error_payment');
	    }
	    //d(array($form_code,$this->session->data['shipping_methods'],$this->request->post['shipping_method']));
	    if(!isset($this->request->post['shipping_method'][$form_code]) || !$this->request->post['shipping_method'][$form_code]){
	    	$this->error['warning'] = __('error_shipping_method');
	    } else{
	    	if(strpos($this->request->post['shipping_method'][$form_code],'.')){
	    		$aMethod = explode('.', $this->request->post['shipping_method'][$form_code]);
	    		if(!isset($this->session->data['shipping_methods'][$aMethod[0]])){
					$this->error['warning'] = __('error_shipping_method');
	    		}
	    	}
	    }

	    if (!$this->error) {
            $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method'][$form_code]];
//            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$this->request->post['shipping_method'][$form_code]];
	    }

	    if (isset($this->request->post['shipping']) && isset($this->request->post['payment'])) {
		$this->session->data['same_payment'] = 0;
		if ($this->validate('shipping')) {
		    $this->session->data['shipping'] = $this->request->post['shipping'][$form_code];
		    $this->load->model('account/address');
		    if ($this->customer->isLogged()) {
			if (isset($this->request->post['shipping']['address_id']) && !empty($this->request->post['shipping']['address_id'])) {
			    $this->session->data['shipping_address_id'] = $this->request->post['shipping']['address_id'];
			    $this->model_account_address->editAddress($this->session->data['shipping_address_id'], $this->session->data['shipping']);
			} else {
			    if ($this->customer->getFirstName() == '' || $this->customer->getLastName() == '') {
				$this->load->model('account/customer');
				$data['firstname'] = $this->request->post['shipping']['firstname'];
				$data['lastname'] = $this->request->post['shipping']['lastname'];
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->request->post['shipping']['company']; // cellphone

				$this->model_account_customer->editCustomer($data);
			    }
			    $shipping_address_id = $this->model_account_address->addAddress($this->session->data['shipping']);
			    $this->session->data['shipping_address_id'] = $shipping_address_id;
			}
		    }

		    /* Set Shipping Address */
		    $country_info = $this->model_localisation_country->getCountry($this->request->post['shipping'][$form_code]['country_id']);
		    if ($country_info) {
			$this->session->data['shipping']['country'] = $country_info['name'];
			$this->session->data['shipping']['iso_code_2'] = $country_info['iso_code_2'];
			$this->session->data['shipping']['iso_code_3'] = $country_info['iso_code_3'];
			$this->session->data['shipping']['address_format'] = $country_info['address_format'];
		    } else {
			$this->session->data['shipping']['country'] = '';
			$this->session->data['shipping']['iso_code_2'] = '';
			$this->session->data['shipping']['iso_code_3'] = '';
			$this->session->data['shipping']['address_format'] = '';
		    }

		    $zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping'][$form_code]['zone_id']);
		    if ($zone_info) {
			$this->session->data['shipping']['zone'] = $zone_info['name'];
			$this->session->data['shipping']['zone_code'] = $zone_info['code'];
		    } else {
			$this->session->data['shipping']['zone'] = '';
			$this->session->data['shipping']['zone_code'] = '';
		    }


		    if (isset($this->request->post['same_payment']) && $this->request->post['same_payment']) {
			$this->session->data['same_payment'] = $this->request->post['same_payment'];
			$this->session->data['payment'] = $this->session->data['shipping'];
		    }
		}

		if ((!isset($this->request->post['same_payment']) || !$this->request->post['same_payment']) && $this->validate('payment')) {
		    $this->session->data['payment'] = $this->request->post['payment'][$form_code];
		    if ($this->customer->isLogged()) {
			if (isset($this->request->post['payment']['address_id']) && !empty($this->request->post['payment']['address_id'])) {
			    $this->session->data['payment_address_id'] = $this->request->post['payment']['address_id'];
			    $this->model_account_address->editAddress($this->session->data['payment_address_id'], $this->session->data['payment']);
			} else {
			    $payment_address_id = $this->model_account_address->addAddress($this->session->data['payment']);
			    $this->session->data['payment_address_id'] = $payment_address_id;
			}
		    }

		    /* Set Payment Address */
		    $country_info = $this->model_localisation_country->getCountry($this->request->post['payment'][$form_code]['country_id']);
		    if ($country_info) {
			$this->session->data['payment']['country'] = $country_info['name'];
			$this->session->data['payment']['iso_code_2'] = $country_info['iso_code_2'];
			$this->session->data['payment']['iso_code_3'] = $country_info['iso_code_3'];
			$this->session->data['payment']['address_format'] = $country_info['address_format'];
		    } else {
			$this->session->data['payment']['country'] = '';
			$this->session->data['payment']['iso_code_2'] = '';
			$this->session->data['payment']['iso_code_3'] = '';
			$this->session->data['payment']['address_format'] = '';
		    }

		    $zone_info = $this->model_localisation_zone->getZone($this->request->post['payment'][$form_code]['zone_id']);
		    if ($zone_info) {
			$this->session->data['payment']['zone'] = $zone_info['name'];
			$this->session->data['payment']['zone_code'] = $zone_info['code'];
		    } else {
			$this->session->data['payment']['zone'] = '';
			$this->session->data['payment']['zone_code'] = '';
		    }
		}
	    }

	    if (!empty($this->error)) {
		echo json_encode(array('error' => $this->error));
		exit();
	    }

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
	    if (!$this->customer->isLogged()) {
			$this->load->model('account/customer');
			$this->session->data['payment']['telephone'] = $this->session->data['payment']['company'];
			$this->session->data['payment']['fax'] = '';
			$password = substr(md5(rand()), 0, 9);
			$this->session->data['payment']['password'] = $password;
			$aShipping = false;
			if ($this->session->data['same_payment'] == 0) {
			    $aShipping = $this->session->data['shipping'];
			}
			$aRes = $this->model_account_customer->addGuestCustomer($this->session->data['payment'], $aShipping);
			$this->session->data['payment']['customer_id'] = $aRes[0];
			if ($aRes[1]) {
	           $this->language->load('mail/guest_account_create');

	          /*  $ptn = "/^0/";
	            $str = $this->session->data['payment']['telephone'];
	            $rpltxt = "92";
	            $smsnumber=preg_replace($ptn, $rpltxt, $str);*/

	            $ptn = "/^0/";
	            $rpltxt = "92";
	            $k = $this->session->data['payment']['telephone'];
	            $a = substr($k, 0, 1);
	            if($a=='+') {
	                $smsnumber = ltrim($this->session->data['payment']['telephone'], $a);
	            }
	            else{
	                $smsnumber=preg_replace($ptn, $rpltxt, $this->session->data['payment']['telephone']);
	            }

	             $Cart_products = array();

	            foreach ($this->cart->getProducts() as $aProduct) {

	                $Cart_products[] = array(
	                    'name' => $aProduct['name'],
	                    'price' => $aProduct['price']
	                );
	            }
	            $sms_message='Your Order for following products has been received:';
	            foreach($Cart_products as $cProducts){
	                $sms_message .= ' Product Name: '.$cProducts['name'].' , ';
	                $sms_message .= 'Price: '.$cProducts['price'].' : ';
	            }
	            $sms_message .='if you want to cancel your order please call at :'.$this->config->get('config_telephone').'or email :'.$this->config->get('config_email');

			    $subject = sprintf($this->language->get('text_subject'), $this->session->data['payment']['firstname'] . ' ' . $this->session->data['payment']['lastname'], $this->config->get('config_name'));
			    $message = sprintf($this->language->get('text_body'), $this->session->data['payment']['firstname'] . ' ' . $this->session->data['payment']['lastname'], $this->session->data['payment']['email'], $password) . "\n\n";
			    $message .= sprintf($this->language->get('text_footer'), $this->config->get('config_name'));

	            $mail = new Mail();
	            $sms_username=$this->config->get('config_sms_username');
	            $sms_password=$this->config->get('config_sms_password');
	            $sms_mask=$this->config->get('config_sms_mask');
	            $send_sms=$this->config->get('config_sms_alert_mail');

	            if($send_sms=='1') {
	                if (!empty($sms_username)) {
	                    $mail->sendSms($sms_message, $smsnumber, $sms_username, $sms_password, $sms_mask);
	                }
	            }

			    $mail->protocol = $this->config->get('config_mail_protocol');
			    $mail->hostname = $this->config->get('config_smtp_host');
			    $mail->username = $this->config->get('config_smtp_username');
			    $mail->password = $this->config->get('config_smtp_password');
			    $mail->port = $this->config->get('config_smtp_port');
			    $mail->timeout = $this->config->get('config_smtp_timeout');
			    $mail->setTo($this->session->data['payment']['email']);
			    $mail->setFrom($this->config->get('config_email'));
			    $mail->setSender($this->config->get('config_name'));
			    $mail->setSubject($subject);
			    // $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			    $mail->setHtml($message);
			    $mail->send();
			}

			$data['customer_id'] = $this->session->data['payment']['customer_id'];
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = $this->session->data['payment']['firstname'];
			$data['lastname'] = $this->session->data['payment']['lastname'];
			$data['email'] = $this->session->data['payment']['email'];
			$data['telephone'] = $this->session->data['payment']['telephone'];
			$data['fax'] = $this->session->data['payment']['fax'];
	    } else {
			$data['customer_id'] = $this->customer->getId();
			$data['customer_group_id'] = $this->customer->getCustomerGroupId();
			$data['firstname'] = $this->customer->getFirstName();
			$data['lastname'] = $this->customer->getLastName();
			$data['email'] = $this->customer->getEmail();
			$data['telephone'] = $this->customer->getTelephone();
			$data['fax'] = $this->customer->getFax();

            $mail = new Mail();

         /*   $ptn = "/^0/";
            $str = $data['telephone'];
            $rpltxt = "92";
            $smsnumber=preg_replace($ptn, $rpltxt, $str);*/
            $ptn = "/^0/";
            $rpltxt = "92";
            $k = $data['telephone'];
            $a = substr($k, 0, 1);
            if($a=='+') {
                $smsnumber = ltrim($data['telephone'], $a);
            }
            else{
                $smsnumber=preg_replace($ptn, $rpltxt, $data['telephone']);
            }

            $Cart_products = array();

            foreach ($this->cart->getProducts() as $aProduct) {

                $Cart_products[] = array(
                    'name' => $aProduct['name'],
                    'price' => $aProduct['price']
                );
            }
            $sms_message='Your Order for following products has been received:';
            foreach($Cart_products as $cProducts){
                $sms_message .= ' Product Name: '.$cProducts['name'].' , ';
                $sms_message .= 'Price: '.$cProducts['price'].' : ';
            }
            $sms_message .='if you want to cancel your order please call at :'.$this->config->get('config_telephone').'or email :'.$this->config->get('config_email');



            $sms_username=$this->config->get('config_sms_username');
            $sms_password=$this->config->get('config_sms_password');
            $sms_mask=$this->config->get('config_sms_mask');
            $send_sms=$this->config->get('config_sms_alert_mail');
            if($send_sms=='1') {
                if (!empty($sms_username)) {

                    $mail->sendSms($sms_message, $smsnumber, $sms_username, $sms_password, $sms_mask);
                }
            }

	    }
	    //  $this->load->model('account/address');
	    $this->session->data['hdn_customer_id'] = $data['customer_id'];
	    if ($this->cart->hasShipping()) {

		$shipping_address = $this->session->data['shipping']; //$this->model_account_address->getAddress($shipping_address_id);

			/*
			 * Shipping Methods
			 */
			

		$data['shipping_firstname'] = $shipping_address['firstname'];
		$data['shipping_lastname'] = $shipping_address['lastname'];
		$data['shipping_company'] = $shipping_address['company'];
		$data['shipping_address_1'] = $shipping_address['address_1'];
		$data['shipping_address_2'] = $shipping_address['address_2'];
		$data['shipping_city'] = $shipping_address['city'];
		$data['shipping_postcode'] = $shipping_address['postcode'];
		$data['shipping_zone'] = $shipping_address['zone'];
		$data['shipping_zone_id'] = $shipping_address['zone_id'];
		$data['shipping_country'] = $shipping_address['country'];
		$data['shipping_country_id'] = $shipping_address['country_id'];
		$data['shipping_address_format'] = $shipping_address['address_format'];

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


	    $payment_address = $this->session->data['payment']; //$this->model_account_address->getAddress($payment_address_id);

	    $data['payment_firstname'] = $payment_address['firstname'];
	    $data['payment_lastname'] = $payment_address['lastname'];
	    $data['payment_company'] = $payment_address['company'];
	    $data['payment_address_1'] = $payment_address['address_1'];
	    $data['payment_address_2'] = $payment_address['address_2'];
	    $data['payment_city'] = $payment_address['city'];
	    $data['payment_postcode'] = $payment_address['postcode'];
	    $data['payment_zone'] = $payment_address['zone'];
	    $data['payment_zone_id'] = $payment_address['zone_id'];
	    $data['payment_country'] = $payment_address['country'];
	    $data['payment_country_id'] = $payment_address['country_id'];
	    $data['payment_address_format'] = $payment_address['address_format'];

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
			'name' => $option['name'],
			'value' => $option['value'],
			'prefix' => $option['prefix']
		    );
		}

		$product_data[] = array(
		    'product_id' => $product['product_id'],
		    'name' => $product['name'],
		    'model' => $product['model'],
		    'option' => $option_data,
		    'download' => $product['download'],
		    'quantity' => $product['quantity'],
		    'price' => $product['price'],
		    'cost' => $product['cost'],
		    'total' => $product['total'],
		    'tax' => $this->tax->getRate($product['tax_class_id'])
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
	    if (!isset($this->session->data['order_id'])) {
		$this->session->data['order_id'] = $this->model_checkout_order->create($data);
	    } else {
		$this->model_checkout_order->save($this->session->data['order_id'], $data);
	    }
	}
	echo json_encode(array('order_id' => $this->session->data['order_id'],
		'publicKey' => $this->config->get('checkoutapipayment_public_key'),'customerEmail' => $data['email']));
	exit();
    }

    function getAddress() {
	if (isset($this->request->get['address_id']) && $this->request->get['address_id']) {
	    $address_id = $this->request->get['address_id'];
	} else {
	    echo json_encode(array('error' => 'No address found.'));
	    exit();
	}
	$this->load->model('account/address');
	$aAddress = $this->model_account_address->getAddress($address_id);
	if ($aAddress) {
	    echo json_encode($aAddress);
	} else {
	    echo json_encode(array('error' => 'No address found.'));
	    exit();
	}
    }

    function getShippingMethods(){
		$this->load->model('checkout/extension');
		$this->load->language('checkout/confirm');
		$quote_data = array();
		$shipping_address = end($this->request->get['shipping']);
				$results = $this->model_checkout_extension->getExtensions('shipping');
				foreach ($results as $result) {
				    $this->load->model('shipping/' . $result['key']);
				    $quote = $this->{'model_shipping_' . $result['key']}->getQuote($shipping_address);
					//d(array($result['key'],$quote,$shipping_address));
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

		$res = array();
		if ($quote_data) {
		    $res['methods'] = $quote_data;
		} else {
		   $res['error'] = __('error_no_shipping');
		}
		echo json_encode($res);
		exit();
    }

    private function validate($type,$isAjax = false) {
		$form_code = $this->session->data['form_code'];
		$codeYesterday = date('znY', time() - 86400);
		if (isset($this->request->post[$type][$codeYesterday])) {
		    $form_code = $codeYesterday;
		}
		if ((strlen(utf8_decode(trim($this->request->post[$type][$form_code]['firstname']))) < 1) || (strlen(utf8_decode(trim($this->request->post[$type][$form_code]['firstname']))) > 60)) {
		    $this->error[$type]['firstname'] = $this->language->get('error_firstname');
		}

		if ((strlen(utf8_decode(trim($this->request->post[$type][$form_code]['lastname']))) < 1) || (strlen(utf8_decode(trim($this->request->post[$type][$form_code]['lastname']))) > 60)) {
		    $this->error[$type]['lastname'] = $this->language->get('error_lastname');
		}

		if ((strlen(utf8_decode(trim($this->request->post[$type][$form_code]['address_1']))) < 3) || (strlen(utf8_decode(trim($this->request->post[$type][$form_code]['address_1']))) > 255)) {
		    $this->error[$type]['address_1'] = $this->language->get('error_address_1');
		}

		if (!$this->customer->isLogged()) {
		    if (trim($this->request->post[$type][$form_code]['email']) == "" || !filter_var($this->request->post[$type][$form_code]['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error[$type]['email'] = $this->language->get('error_email');
		    }
		}

		if ((strlen(utf8_decode(trim($this->request->post[$type][$form_code]['company']))) < 3) || (strlen(utf8_decode(trim($this->request->post[$type][$form_code]['company']))) > 32)) {
		    $this->error[$type]['company'] = $this->language->get('error_company');
		}

		if ((strlen(utf8_decode(trim($this->request->post[$type][$form_code]['city']))) < 1) || (strlen(utf8_decode(trim($this->request->post[$type][$form_code]['city']))) > 60)) {
		    $this->error[$type]['city'] = $this->language->get('error_city');
		}

		if ($this->request->post[$type][$form_code]['country_id'] == '') {
		    $this->error[$type]['country_id'] = $this->language->get('error_country');
		} else {
		    $this->load->model('localisation/country');
		    $aCountry = $this->model_localisation_country->getCountry($this->request->post[$type][$form_code]['country_id']);
		    if ($aCountry['iso_code_2'] == 'US' && (strlen(trim($this->request->post[$type][$form_code]['postcode'])) < 3) || (strlen(trim($this->request->post[$type][$form_code]['postcode'])) > 10))
			$this->error[$type]['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post[$type][$form_code]['zone_id'] == 'FALSE' || $this->request->post[$type][$form_code]['zone_id'] == '') {
		    $this->error[$type]['zone_id'] = $this->language->get('error_zone');
		}

		if(!$isAjax) {
			if (!$this->error) {
			    $this->request->post[$type][$form_code]['firstname'] = trim($this->request->post[$type][$form_code]['firstname']);
			    $this->request->post[$type][$form_code]['lastname'] = trim($this->request->post[$type][$form_code]['lastname']);
			    $this->request->post[$type][$form_code]['company'] = trim($this->request->post[$type][$form_code]['company']);
			    $this->request->post[$type][$form_code]['city'] = trim($this->request->post[$type][$form_code]['city']);
			    $this->request->post[$type][$form_code]['country_id'] = trim($this->request->post[$type][$form_code]['country_id']);
			    $this->request->post[$type][$form_code]['postcode'] = trim($this->request->post[$type][$form_code]['postcode']);
			    $this->request->post[$type][$form_code]['zone_id'] = trim($this->request->post[$type][$form_code]['zone_id']);
			    $this->request->post[$type][$form_code]['email'] = trim($this->request->post[$type][$form_code]['email']);
			    $this->request->post[$type][$form_code]['address_1'] = trim($this->request->post[$type][$form_code]['address_1']);
			    $this->request->post[$type][$form_code]['address_2'] = trim($this->request->post[$type][$form_code]['address_2']);
			    return TRUE;
			} else {
			    return FALSE;
			}
		}
		else {
			return json_encode(array('errors' => $this->error));
		}
    }

    public function coupon() {
        if (isset($this->request->post['coupon'])) {
            $this->load->model('checkout/coupon');
            $coupon = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);
            if (!$coupon) {
            	echo "Error: " . __('Invalid Coupon Code');
            } else {
	            $this->session->data['coupon'] = $this->request->post['coupon'];
	            echo $this->load('checkout/left_bar',array('isAjax' => true));
            }
        }
    }

    public function shipping() {
        if (isset($this->request->post['shipping'])) {
	    $key = $this->request->post['shipping'];
	    if(strpos($this->request->post['shipping'],'.') !== false){
		$aKey = explode('.',$this->request->post['shipping']);
		$key = $aKey[0];
	    }
            if(isset($this->session->data['shipping_methods'][$key])) {
		
                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$key];
                echo $this->load('checkout/left_bar');
            }
        }
    }

    public function validateForm() {
    	$this->language->load('checkout/confirm');
    	if(stripos($this->request->post['form_type'],',') !== false){
    		$aTypes = explode(',',$this->request->post['form_type']);
    		$this->validate($aTypes[0],false);
    		echo $this->validate($aTypes[1],true);
    	} else {
    		echo $this->validate($this->request->post['form_type'],true);
    	}
    	exit();
    }

}

?>