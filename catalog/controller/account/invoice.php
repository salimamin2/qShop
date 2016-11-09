<?php

class ControllerAccountInvoice extends Controller {

    public function index() {

	if (isset($this->request->get['order_id'])) {
	    $order_id = $this->request->get['order_id'];
	} else if (isset($this->request->get['params']['order_id'])) {
	    $order_id = $this->request->get['params']['order_id'];
	} else {
	    $order_id = 0;
	}

	// if (isset($this->request->get['customer_id'])) {
	//     $customer_id = $this->request->get['customer_id'];
	// } else if (isset($this->request->get['params']['customer_id'])) {
	//     $customer_id = $this->request->get['params']['customer_id'];
	// } else {
	//     $customer_id = false;
	// }

	if (!$this->customer->isLogged() && !isset($this->request->get['params']['order_id'])) {
	    $this->session->data['redirect'] = makeUrl('account/invoice', array('&order_id=' . $order_id), true, true);
	    $this->redirect(makeUrl('account/login', array(), true, true));
	}

	$customer_id = $this->customer->getId();
	$this->document->layout_col = "col2-left-layout";
	$this->language->load('account/invoice');
	$this->document->title = $this->language->get('heading_title');
	$this->document->breadcrumbs = array();
	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home', array(), true),
	    'text' => $this->language->get('text_home'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('account/account', array(), true, true),
	    'text' => $this->language->get('text_account'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('account/history', array(), true, true),
	    'text' => $this->language->get('text_history'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('account/invoice', array('order_id=' . $order_id), true, true),
	    'text' => $this->language->get('text_invoice'),
	    'separator' => FALSE
	);

	$this->load->model('account/order');
	$this->load->model('tool/image');

	$this->data['module'] = false;
	if (isset($this->request->get['params']['module'])) {
	    $this->data['module'] = true;
	}
	$order_info = $this->model_account_order->getOrder($order_id, $customer_id);
	if ($order_info) {
	    $this->data['heading_title'] = "Order # " . $order_info['order_id'];

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
	    $this->data['text_model'] = $this->language->get('text_model');
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
	    $this->data['date_added'] = date('M d,Y', strtotime($order_info['date_added']));

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

	    $this->data['products'] = array();

	    $products = $this->model_account_order->getOrderProducts($order_id);
	    $oProduct = array();
	    $aProduct = array();
	    foreach ($products as $product) {
		$oProduct = Make::a('catalog/product')->create()->getProduct($product['product_id']);
		if ($oProduct) {
		    $aProduct = $oProduct->toArray();
		}

		$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);
		$option_data = array();

		foreach ($options as $option) {
		    $option_data[] = array(
			'name' => $option['name'],
			'value' => $option['value'],
		    );
		}
		if (isset($aProduct) && $aProduct['image'] && file_exists(DIR_IMAGE . $aProduct['image'])) {
		    $image = $aProduct['image'];
		} else {
		    $image = 'no_image.jpg';
		}

		$this->data['products'][] = array(
		    'name' => $product['name'],
		    'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
		    'model' => $product['model'],
		    'option' => $option_data,
		    'detail' => array(),
		    'quantity' => $product['quantity'],
		    'price' => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
		    'total' => $this->currency->format($product['total'], $order_info['currency'], $order_info['value']),
		    'meta_link' => QS::getMetaLink($product['meta_link'], $product['name']),
		    'alt_title' => QS::getMetaLink($product['img_alt'], $product['name'])
		);
	    }

	    $this->data['totals'] = $this->model_account_order->getOrderTotals($order_id);

	    $this->data['comment'] = $order_info['comment'];

	    $this->data['historys'] = array();

	    $results = $this->model_account_order->getOrderHistories($order_id);

	    foreach ($results as $result) {
		$this->data['histories'][] = array(
		    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
		    'status' => $result['status'],
		    'comment' => nl2br($result['comment'])
		);
	    }

	    $this->data['continue'] = makeUrl('account/account', array(), true, true);
	    $this->data['reorder'] = makeUrl('account/invoice/addCart', array('order_id=' . $order_id), true, true);

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

	    $this->data['continue'] = makeUrl('account/history', array(), true, true);

	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
		$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
	    } else {
		$this->template = 'default/template/error/not_found.tpl';
	    }

	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
    }

    public function addCart() {
	if ($this->request->server['REQUEST_METHOD'] == 'GET') {
	    if (isset($this->request->get['order_id'])) {
		$this->load->model('account/order');
		$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
		$order_products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);
		foreach ($order_products as $product) {
		    $product_options = $this->model_account_order->getProductOptions($this->request->get['order_id'], $product['product_id']);
		    $this->cart->add($product['product_id'], 1, $product_options);
		}

		$this->redirect(makeUrl('checkout/confirm', array(), true));
	    }
	}
    }

}

?>