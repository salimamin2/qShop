<?php

class ControllerAccountAccount extends Controller {

    public function index() {
	if (!$this->customer->isLogged()) {
	    $this->session->data['redirect'] = makeUrl('account/account', array(), true, true);

	    $this->redirect(makeUrl('account/login', array(), true, true));
	}

	$this->language->load('account/account');
	$this->load->model('account/customer');
	$this->load->model('tool/seo_url');
	$this->document->layout_col = "col2-left-layout";

	$this->document->breadcrumbs = array();
	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home', array(), true, true),
	    'text' => $this->language->get('text_home'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('account/account', array(), true, true),
	    'text' => $this->language->get('text_account'),
	    'separator' => FALSE
	);

	$this->document->title = $this->language->get('heading_title');

	$this->data['heading_title'] = $this->language->get('heading_title');

	$customer_id = $this->customer->getId();
	$customer = $this->model_account_customer->getCustomer($customer_id);
	$this->data['contact_name'] = $customer['firstname'] . ' ' . $customer['lastname'];
	$this->data['contact_email'] = $customer['email'];
	$this->data['welcome_title'] = sprintf($this->language->get('welcome_title'), $this->data['contact_name']);
	$this->data['text_welcome'] = $this->language->get('text_welcome');
	$this->data['text_newsletter_status'] = sprintf($this->language->get('text_newsletter_status'), ($this->customer->getNewsletter() == 1) ? 'subscribed' : 'not subscribed');

	$this->data['text_my_account'] = $this->language->get('text_my_account');
	$this->data['text_account_information'] = $this->language->get('text_account_information');
	$this->data['text_contact_information'] = $this->language->get('text_contact_information');
	$this->data['text_my_orders'] = $this->language->get('text_my_orders');
	$this->data['text_orders'] = $this->language->get('text_orders');
	$this->data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
	$this->data['text_information'] = $this->language->get('text_information');
	$this->data['text_password'] = $this->language->get('text_password');
	$this->data['text_reward'] = $this->language->get('text_reward');
	$this->data['text_address'] = $this->language->get('text_address');
	$this->data['text_address_book'] = $this->language->get('text_address_book');
	$this->data['text_history'] = $this->language->get('text_history');
	$this->data['text_download'] = $this->language->get('text_download');
	$this->data['text_newsletter'] = $this->language->get('text_newsletter');
	$this->data['text_logout'] = $this->language->get('text_logout');
	$this->data['text_edit'] = $this->language->get('text_edit');
	$this->data['text_detail'] = $this->language->get('text_detail');

	if (isset($this->session->data['success'])) {
	    $this->data['success'] = $this->session->data['success'];
	    unset($this->session->data['success']);
	} else {
	    $this->data['success'] = '';
	}

	if (isset($this->session->data['errors'])) {
	    $this->data['errors'] = $this->session->data['errors'];
	    unset($this->session->data['errors']);
	} else {
	    $this->data['errors'] = '';
	}

	if (isset($this->session->data['tab'])) {
	    $this->data['tab'] = $this->session->data['tab'];
	    unset($this->session->data['tab']);
	} elseif(isset($this->request->get['tab'])){
		$this->data['tab'] = $this->request->get['tab'];
	} else {
	    $this->data['tab'] = 'tab1';
	}

	$this->load->model('account/address');
	$result = $this->model_account_address->getAddress($this->customer->getAddressId());
	if ($result['address_format']) {
	    $format = $result['address_format'];
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
	    'firstname' => $result['firstname'],
	    'lastname' => $result['lastname'],
	    'company' => $result['company'],
	    'address_1' => $result['address_1'],
	    'address_2' => $result['address_2'],
	    'city' => $result['city'],
	    'postcode' => $result['postcode'],
	    'zone' => $result['zone'],
	    'zone_code' => $result['zone_code'],
	    'country' => $result['country']
	);

	$this->data['address_entry'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	$this->data['address_entry'] = (isset($this->data['address_entry']) && $this->data['address_entry'] != '') ? $this->data['address_entry'] : $this->language->get('text_address_empty');
	$this->data['address_edit'] = (isset($result['address_id']) && $result['address_id'] != 0) ? makeUrl('account/address/update', array('address_id=' . $result['address_id']), true, true) : '';

	$this->load->model('account/order');
	// $order_total = $this->model_account_order->getTotalOrders();
	// $this->data['order_total'] = sprintf($this->language->get('text_order_total'), $order_total);
	// $order_total_number = $order_total;
	

	if ($this->config->get('config_allow_reward')) {
	    $this->data['reward'] = makeUrl('account/reward',array(),true,true);
	} else {
	    $this->data['reward'] = '';
	}

	if ($customer['lcn']) {
	    $this->data['lcn'] = sprintf($this->language->get('text_lcn'), $customer['lcn']);
	} else {
	    $this->data['lcn'] = '';
	}

	$this->data['text_order_reward'] = sprintf($this->language->get('text_order_reward'), (int) $this->customer->getRewardPoints());

	$this->data['information'] = makeUrl('account/edit', array(), true, true);
	$this->data['password'] = makeUrl('account/password', array(), true, true);
	$this->data['address'] = makeUrl('account/address', array(), true, true);
	$this->data['history'] = makeUrl('account/history', array(), true, true);
	$this->data['download'] = makeUrl('account/download', array(), true, true);
	$this->data['newsletter'] = makeUrl('account/edit', array(), true, true);
	$this->data['logout'] = makeUrl('account/logout', array(), true, true);

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
                'firstname' => $result['firstname'],
                'lastname' => $result['lastname'],
                'address' => $result['address_1'] . ',' . $result['city'] . ', ' . (($result['zone']) ? $result['zone'] . ', ' : FALSE) . (($result['postcode']) ? $result['postcode'] . ', ' : FALSE) . '' . $result['country'] . ',' . ($result['company'] ? $result['company'] : '-'),
                'href' => makeUrl('account/address/update',array('address_id=' . $result['address_id']),true)
            );
            // if (isset($this->session->data['calculate_country_id'])) {
            //     if ($result['country_id'] == $this->session->data['calculate_country_id']) {
            //         $this->data['shipping_addresses'][] = array(
            //             'address_id' => $result['address_id'],
            //             'address' => $result['address_1'] . ',' . $result['city'] . ', ' . (($result['zone']) ? $result['zone'] . ', ' : FALSE) . (($result['postcode']) ? $result['postcode'] . ', ' : FALSE) . '' . $result['country'] . ',' . ($result['company'] ? $result['company'] : '-'),
            //             'href' => makeUrl('account/address/update',array('address_id=' . $result['address_id']),true)
            //         );
            //     }
            // }
        }

        // if (!isset($this->session->data['calculate_country_id']) || empty($this->session->data['calculate_country_id'])) {
        //     $this->data['shipping_addresses'] = $this->data['addresses'];
        // }
    }


	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/account/account.tpl';
	} else {
	    $this->template = 'default/template/account/account.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>
