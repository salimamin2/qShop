<?php

class ControllerAccountReorder extends Controller {

    public function index() {
	if (!$this->customer->isLogged()) {
	    $this->session->data['redirect'] = HTTPS_SERVER . 'account/reorder';

	    $this->redirect(HTTPS_SERVER . 'account/login');
	}
	$this->load->model('account/order');
	$this->load->model('tool/seo_url');

	$this->language->load('account/reorder');

	$this->document->title = $this->language->get('heading_title');

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTP_SERVER . 'common/home',
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTP_SERVER . 'account/account',
	    'text' => $this->language->get('text_account'),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTP_SERVER . 'account/reorder',
	    'text' => $this->language->get('text_reorder'),
	    'separator' => $this->language->get('text_separator')
	);

	$order_total = $this->model_account_order->getTotalOrders();

	if ($order_total) {
	    $this->data['heading_title'] = $this->language->get('heading_title');

	    $this->data['text_qty'] = $this->language->get('text_qty');
	    $this->data['text_category_name'] = $this->language->get('text_category_name');
	    $this->data['text_product_name'] = $this->language->get('text_product_name');
	    $this->data['text_price'] = $this->language->get('text_price');

	    $this->data['button_cart'] = $this->language->get('button_cart');
	    $this->data['button_continue'] = $this->language->get('button_continue');

	    $this->data['action'] = HTTP_SERVER . 'account/reorder/addCart';

	    if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
	    } else {
		$page = 1;
	    }

	    $this->data['orders'] = array();


	    $categories = $this->model_account_order->getOrderCatProducts();

	    foreach ($categories as $product) {
		$special = FALSE;

		$discount = Make::a('catalog/product')->create()->getProductDiscount($product['product_id']);

		if ($discount) {
		    $price = $this->currency->format($this->tax->calculate($discount, $product['tax_class_id'], $this->config->get('config_tax')));
		} else {
		    $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));

		    $special = Make::a('catalog/product')->create()->getProductSpecial($product['product_id']);

		    if ($special) {
			$special = $this->currency->format($this->tax->calculate($special['price'], $product['tax_class_id'], $this->config->get('config_tax')));
		    }
		}
		$this->data['orders'][] = array(
		    'category_id' => $product['category_id'],
		    'category_name' => $product['category_name'],
		    'product_id' => $product['product_id'],
		    'product_name' => $product['name'],
		    'product_model' => $product['model'],
		    'product_price' => $product['price'],
		    'uom' => $product['uom'],
		    'price' => $price,
		    'special' => $special,
		    'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/product&product_id=' . $product['product_id'])
		);
	    }

	    $this->data['continue'] = HTTPS_SERVER . 'account/account';

	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/reorder.tpl')) {
		$this->template = $this->config->get('config_template') . '/template/account/reorder.tpl';
	    } else {
		$this->template = 'default/template/account/reorder.tpl';
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

    public function addCart() {
	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
	    if (isset($this->request->post['qty'])) {
		print_r($this->request->post['qty']);
		foreach ($this->request->post['qty'] as $key => $val):
		    if ($val != "")
			$this->cart->add($key, $val);
		endforeach;
		$this->redirect(HTTPS_SERVER . 'checkout/cart');
	    }
	}
    }

}

?>
