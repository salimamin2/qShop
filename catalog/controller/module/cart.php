<?php

class ControllerModuleCart extends Controller {

    protected function index() {
	$this->language->load('module/cart');

	$this->load->model('tool/seo_url');
	$this->load->model('tool/image');
	$this->data['logged'] = $this->customer->isLogged();
	$this->data['heading_title'] = $this->language->get('heading_title');
	$this->data['login'] = makeUrl('account/login', array(), true, true);
	$this->data['logout'] = makeUrl('account/logout', array(), true, true);
	$this->data['account'] = makeUrl('account/account', array(), true, true);
	$this->data['order'] = makeUrl('account/history', array(), true, true);
	$this->data['register'] = makeUrl('account/create', array(), true, true);
	$this->data['text_subtotal'] = $this->language->get('text_subtotal');
	$this->data['text_empty'] = $this->language->get('text_empty');
	$this->data['text_view'] = $this->language->get('text_view');
	$this->data['text_checkout'] = $this->language->get('text_checkout');

	$this->data['view'] = makeUrl('checkout/cart', array(), true, true);
	$this->data['checkout'] = makeUrl('checkout/confirm', array(), true, true);

	$this->data['products'] = array();
	$total_qty = 0;
	$i = 0;
	foreach ($this->cart->getProducts() as $key => $result) {
	    $option_data = array();
	    foreach ($result['option'] as $option) {
		$option_data[] = array(
		    'name' => $option['name'],
		    'value' => $option['value']
		);
	    }
	    if ($result['image']) {
		$image = $result['image'];
	    } else {
		$image = 'no_image.jpg';
	    }
	    $total_qty += $result['quantity'];
	    $this->data['products'][] = array(
    	'id' => ++$i,
	   	'product_id' => $result['product_id'],
		'name' => $result['name'],
		'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
		'option' => $option_data,
		'quantity' => $result['quantity'],
		'stock' => $result['stock'],
		'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
		'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
		'meta_link' => QS::getMetaLink(isset($result['meta_link'])?$result['meta_link']:'', $result['name']),
		'alt_title' => QS::getMetaLink(isset($result['img_alt'])?$result['img_alt']:'', $result['name']),
		// 'remove' => makeUrl('checkout/cart', array('remove=' . $key), true),
		'key' => $key,
		'stock_quantity' => $result['stock_quantity']
	    );
	}
	$cart_link = makeUrl('checkout/cart',array(),true);
	$checkout_link = makeUrl('checkout/shipping');
	$this->data['subtotal'] = $this->currency->format($this->cart->getTotal());
	$this->data['total_qty'] = $total_qty;
	$this->data['checkout_link'] = $checkout_link;
	$this->data['cart_link'] = $cart_link;
	$this->data['total_quantity'] = sprintf($this->language->get('text_total_qty'), $cart_link, $total_qty);
	$this->data['text_cart'] = sprintf($this->language->get('text_cart'), $total_qty);
	$this->data['text_error_stock'] = $this->language->get('text_error_stock');
	$this->data['error_stock'] = $this->language->get('error_stock');
	$this->data['text_confirm_remove'] = $this->language->get('text_confirm');
	$empty_html = '';

	// $this->data['empty_html'] = $empty_html;

	$this->data['ajax'] = $this->config->get('cart_ajax');

	$this->data['success'] = '';
	if(isset($this->request->get['params']['success_main'])) {
		$this->data['success'] = $this->request->get['params']['success_main'];
	}
	$this->data['error'] = '';
	if(isset($this->request->get['params']['error'])) {
		$this->data['error'] = $this->request->get['params']['error'];
	}

	$this->data['action'] = makeUrl('common/home', array(), true);
	if (!isset($this->request->get['act'])) {
	    $this->data['redirect'] = makeUrl('common/home', array(), true);
	} else {
	    $this->load->model('tool/seo_url');

	    $data = $this->request->get;

	    unset($data['_act_']);

	    $route = $data['act'];

	    unset($data['act']);

	    $url = array();

	    if ($data) {
		$url = explode('&', str_replace('&amp;', '&', urldecode(http_build_query($data))));
	    }

	    $this->data['redirect'] = makeUrl($route, $url, true);
	}
	$this->data['currency_code'] = $this->currency->getCode();

	$this->load->model('localisation/currency');

	$this->data['currencies'] = array();

	$results = $this->model_localisation_currency->getCurrencies();
	$this->data['template'] = $this->config->get('config_template');
	foreach ($results as $result) {
	    if ($result['status']) {
		$this->data['currencies'][] = array(
		    'title' => $result['title'],
		    'code' => $result['code']
		);
	    }
	}

	$this->id = 'cart';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cart.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/cart.tpl';
	} else {
	    $this->template = 'default/template/module/cart.tpl';
	}

	$this->render();
    }

    public function callback_def() {
	$this->language->load('module/cart');

	$this->load->model('tool/seo_url');
	$this->load->model('tool/image');

	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
	    if (isset($this->request->post['option'])) {
		$option = $this->request->post['option'];
	    } else {
		$option = array();
	    }

	    $this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);

	    unset($this->session->data['shipping_methods']);
	    unset($this->session->data['shipping_method']);
	    unset($this->session->data['payment_methods']);
	    unset($this->session->data['payment_method']);
	}
	$total_qty = 0;
	foreach ($this->cart->getProducts() as $product) {
	    $total_qty +=$product['quantity'];
	}
	$output = '';
	$output .= $this->currency->format($this->cart->getTotal()) . ' | ' . sprintf($this->language->get('text_cart'), $total_qty);


	/*        $output = '(' . $total_qty . '&nbsp;items)&nbsp;';
	  $output .= ; */

	$this->response->setOutput($output, $this->config->get('config_compression'));
    }

    public function callback() {
	$this->language->load('module/cart');

	$this->load->model('tool/seo_url');
	$this->load->model('tool/image');

	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
	    if (isset($this->request->post['option'])) {
		$option = $this->request->post['option'];
	    } else {
		$option = array();
	    }

	    $this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);

	    unset($this->session->data['shipping_methods']);
	    unset($this->session->data['shipping_method']);
	    unset($this->session->data['payment_methods']);
	    unset($this->session->data['payment_method']);
	}
	$total_quantity = '';
	$total_qty = 0;
	foreach ($this->cart->getProducts() as $product) {
	    $total_qty +=$product['quantity'];
	}
	$cart_link = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'checkout/cart');
	$total_quantity = sprintf($this->language->get('text_total_qty'), $cart_link, $total_qty);
	$output = '<div class="summary"><p class="amount">' . $total_quantity . '</p>';
	$output .= '<p class="subtotal"><span class="label">' . $this->language->get('text_subtotal') . '</span> <span class="price">' . $this->currency->format($this->cart->getTotal()) . '</span></p>';
	$output .= '</div>';
	$output .= '<p class="block-subtitle">Recently added item(s)</p>';
	$output .= '<ol class="mini-products-list" id="cart-sidebar">';
	$i = 1;
	foreach ($this->cart->getProducts() as $product) {
	    if ($product['image']) {
		$image = $product['image'];
	    } else {
		$image = 'no_image.jpg';
	    }
	    $output .= '<li class="item';
	    if ($i == count($this->cart->getProducts()))
		$output .= ' last">';
	    else
		$output .= '">';
	    $output .= '<a class="product-image" href="' . makeUrl('product/product', array('product_id=' . $product['product_id']), true) . '" title="' . QS::getMetaLink($product['meta_link'], $product['name']) . '" alt="' . QS::getMetaLink($product['meta_link'], $product['name']) . '">';
	    $output .= '<img src="' . $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')) . '" width="50" height="50" border="0" alt="' . QS::getMetaLink($product['img_alt'], $product['name']) . '" title="' . QS::getMetaLink($product['img_alt'], $product['name']) . '" /></a>';
	    $output .= '<div class="product-details">';
	    $output .= '<a class="btn-remove" onclick="return confirm(\'Are you sure you would like to remove this item from the shopping cart?\');" title="Remove This Item" href="' . $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'checkout/cart&remove=' . $product['product_id']) . '">Remove This Item</a>';
	    $output .= '<p class="product-name"><a href="' . makeUrl('product/product', array('product_id=' . $product['product_id']), true) . '" title="' . QS::getMetaLink($product['meta_link'], $product['name']) . '" alt="' . QS::getMetaLink($product['meta_link'], $product['name']) . '">' . $product['name'] . '</a></p>';
	    $output .= '<strong>' . $product['quantity'] . '</strong> x <span class="price">' . $product['price'] . '</span>';
	    if ($product['option']) {
		$output .= '<div class="truncated" id="">';
		$output .= '<div class="truncated_full_value val_' . $i . '">';
		$output .= '<dl class="item-options">';

		foreach ($product['option'] as $option) {
		    $output .= '<dt>' . $option['name'] . '</dt> <dd>' . $option['value'] . '</dd>';
		}
		$output .= '</dl>';
		$output .= '</div>';
		$output .= '<a class="details"  onmouseover="showToolTip(\'val_' . $i . '\')" onmouseout="hideToolTip(\'val_' . $i . '\')" onclick="return false;" href="#">Details</a>';
		$output .= '</div>';
	    }
	    $output .= '</div>';
	    $output .= '<br clear="all"/>';
	    $output .= '</li>';
	    $i++;
	}

	$output .= '</ol>';

	$this->response->setOutput($output, $this->config->get('config_compression'));
    }

}

?>