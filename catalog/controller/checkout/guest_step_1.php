<?php

class ControllerCheckoutGuestStep1 extends Controller {

    private $error = array();

    public function index() {
	
	if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	    $this->redirect(makeUrl('checkout/cart', array(), true));
	}

	if ($this->customer->isLogged() && !isset($this->session->data['guest'])) {
	    $this->redirect(makeUrl('checkout/shipping', array(), true));
	}

	if (!$this->config->get('config_guest_checkout') || $this->cart->hasDownload()) {
	    $this->session->data['redirect'] = makeUrl('checkout/shipping', array(), true);
	    $this->redirect(makeUrl('account/login', array(), true));
	}

	$this->language->load('checkout/guest_step_1');

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

	    if (!isset($this->request->post['payment_method']) || !isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
		$this->error['warning'] = __('error_payment');
	    }
	    if (!$this->error) {
		$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
	    }
	    if (isset($this->request->post['shipping']) && isset($this->request->post['payment'])) {
		$this->session->data['guest']['same_payment'] = 0;
		if ($this->validate('shipping')) {
		    $this->session->data['guest']['shipping'] = $this->request->post['shipping'][$form_code];
		    /* Set Shipping Address */
		    $country_info = $this->model_localisation_country->getCountry($this->request->post['shipping'][$form_code]['country_id']);
		    if ($country_info) {
			$this->session->data['guest']['shipping']['country'] = $country_info['name'];
			$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
			$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
			$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
		    } else {
			$this->session->data['guest']['shipping']['country'] = '';
			$this->session->data['guest']['shipping']['iso_code_2'] = '';
			$this->session->data['guest']['shipping']['iso_code_3'] = '';
			$this->session->data['guest']['shipping']['address_format'] = '';
		    }

		    $zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping'][$form_code]['zone_id']);
		    if ($zone_info) {
			$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
			$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
		    } else {
			$this->session->data['guest']['shipping']['zone'] = '';
			$this->session->data['guest']['shipping']['zone_code'] = '';
		    }


		    if (isset($this->request->post['same_payment']) && $this->request->post['same_payment']) {
			$this->session->data['guest']['same_payment'] = $this->request->post['same_payment'];
			$this->session->data['guest']['payment'] = $this->session->data['guest']['shipping'];
		    }
		}

		if ((!isset($this->request->post['same_payment']) || !$this->request->post['same_payment']) && $this->validate('payment')) {
		    $this->session->data['guest']['payment'] = $this->request->post['payment'][$form_code];
		    /* Set Payment Address */
		    $country_info = $this->model_localisation_country->getCountry($this->request->post['payment'][$form_code]['country_id']);
		    if ($country_info) {
			$this->session->data['guest']['payment']['country'] = $country_info['name'];
			$this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
			$this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
			$this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
		    } else {
			$this->session->data['guest']['payment']['country'] = '';
			$this->session->data['guest']['payment']['iso_code_2'] = '';
			$this->session->data['guest']['payment']['iso_code_3'] = '';
			$this->session->data['guest']['payment']['address_format'] = '';
		    }

		    $zone_info = $this->model_localisation_zone->getZone($this->request->post['payment'][$form_code]['zone_id']);
		    if ($zone_info) {
			$this->session->data['guest']['payment']['zone'] = $zone_info['name'];
			$this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
		    } else {
			$this->session->data['guest']['payment']['zone'] = '';
			$this->session->data['guest']['payment']['zone_code'] = '';
		    }
		}
	    }

	    if (!isset($this->session->data['calculate_country_id']) || !$this->session->data['calculate_country_id']) {

		$this->load->model('checkout/extension');
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['shipping_method']);
		$quote_data = array();
		$results = $this->model_checkout_extension->getExtensions('shipping');
		foreach ($results as $result) {
		    $this->load->model('shipping/' . $result['key']);
		    $quote = $this->{'model_shipping_' . $result['key']}->getQuote($this->request->post['shipping'][$form_code]);
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

		$aMethod = end($quote_data);
		if (!$aMethod['error']) {
		    $aMethodPrice = end($aMethod['quote']);
		    $this->session->data['shipping_method'] = $aMethodPrice;
		    $this->tax->setZone($this->request->post['shipping'][$form_code]['country_id'], $this->request->post['shipping'][$form_code]['zone_id']);
		} else {
		    $this->session->data['error_warning'] = $aMethod['error'];
		    $this->redirect(makeUrl('checkout/cart'));
		}
	    }

	    if (!$this->error)
		$this->redirect(makeUrl('checkout/guest_step_3'));
	} else {
	    $this->session->data['form_code'] = date('znY');
	    $form_code = $this->session->data['form_code'];
	}

	$this->document->title = $this->language->get('heading_title');

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTP_SERVER . 'index.php?route=common/home',
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTP_SERVER . 'index.php?route=checkout/cart',
	    'text' => $this->language->get('text_cart'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'index.php?route=checkout/guest_step_1',
	    'text' => $this->language->get('text_guest_step_1'),
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

	$this->data['form_code'] = $form_code;

	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['button_back'] = $this->language->get('button_back');

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

	$this->data['action'] = makeUrl('checkout/guest_step_1');
	$this->data['default'] = '';

	$this->data['zone_id'] = '';
	$this->data['country_id'] = '';
	$this->data['shipping_address']['zone_id'] = '';
	$this->data['shipping_address']['country_id'] = $this->config->get('config_country_id');
	$this->data['payment_address']['zone_id'] = '';
	$this->data['payment_address']['country_id'] = $this->config->get('config_country_id');
	$this->data['first_visit'] = false;
	if (isset($this->request->post['shipping']) && isset($this->request->post['shipping'][$form_code])) {
	    $this->data['shipping_address'] = $this->request->post['shipping'][$form_code];
	} else if (isset($this->session->data['guest']['shipping'])) {
	    $this->data['shipping_address'] = $this->session->data['guest']['shipping'];
	} else {
	    $this->data['shipping_address'] = array();
	    $this->data['first_visit'] = true;
	}

	if (isset($this->request->post['payment']) && isset($this->request->post['payment'][$form_code])) {
	    $this->data['payment_address'] = $this->request->post['payment'][$form_code];
	} else if (isset($this->session->data['guest']['payment'])) {
	    $this->data['payment_address'] = $this->session->data['guest']['payment'];
	} else {
	    $this->data['payment_address'] = array();
	}



	if (isset($this->session->data['calculate_country_id'])) {
	    $this->data['shipping_address']['country_id'] = $this->session->data['calculate_country_id'];
	    //$this->data['payment_address']['country_id'] = $this->session->data['calculate_country_id'];
	    $this->data['country_id'] = $this->session->data['calculate_country_id'];
	}

	if (isset($this->session->data['calculate_zone_id'])) {
	    $this->data['shipping_address']['zone_id'] = $this->session->data['calculate_zone_id'];
	    //$this->data['payment_address']['zone_id'] = $this->session->data['calculate_zone_id'];
	    $this->data['zone_id'] = $this->session->data['calculate_zone_id'];
	}

	if (isset($this->request->post['same_payment'])) {
	    $this->data['same_payment'] = $this->request->post['same_payment'];
	} else if (isset($this->session->data['guest']['same_payment'])) {
	    $this->data['same_payment'] = $this->session->data['guest']['same_payment'];
	} else {
	    $this->data['same_payment'] = 0;
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
	//$this->session->data['payment_method'] = end($method_data);
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
		$this->data['text_agree'] = sprintf($this->language->get('text_agree'), HTTPS_SERVER . 'index.php?route=information/information&popup=1&information_id=' . $this->config->get('config_checkout_id'), $information_info['title']);
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

	$this->load->model('localisation/country');

	$this->data['countries'] = $this->model_localisation_country->getCountries();

	$this->data['back'] = HTTP_SERVER . 'index.php?route=checkout/cart';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest_step_1.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/checkout/guest_step_1.tpl';
	} else {
	    $this->template = 'default/template/checkout/guest_step_1.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate($type) {
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

	if ((strlen(utf8_decode(trim($this->request->post[$type][$form_code]['city']))) < 1) || (strlen(utf8_decode(trim($this->request->post[$type][$form_code]['city']))) > 60)) {
	    $this->error[$type]['city'] = $this->language->get('error_city');
	}

	if ($this->request->post[$type][$form_code]['country_id'] == '') {
	    $this->error[$type]['country'] = $this->language->get('error_country');
	} else {
	    $this->load->model('localisation/country');
	    $aCountry = $this->model_localisation_country->getCountry($this->request->post[$type][$form_code]['country_id']);
	    if ($aCountry['iso_code_2'] == 'US' && (strlen(trim($this->request->post[$type][$form_code]['postcode'])) < 3) || (strlen(trim($this->request->post[$type][$form_code]['postcode'])) > 10))
		$this->error[$type]['postcode'] = $this->language->get('error_postcode');
	}

	if ($this->request->post[$type][$form_code]['zone_id'] == '') {
	    $this->error[$type]['zone'] = $this->language->get('error_zone');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
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

}

?>