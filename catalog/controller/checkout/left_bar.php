<?php
class ControllerCheckoutLeftBar extends Controller {
    public function index() {
		$this->language->load('checkout/confirm');
		$this->data['text_help'] = sprintf($this->language->get('text_help'), makeUrl('information/contact', array(), true), $this->config->get('config_telephone'));
	    $act = $this->request->get['_act_'];
	    $array = array('checkout/confirm','checkout/confirm/coupon','checkout/confirm/shipping');
		if (in_array($act,$array)) {
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
		    $this->data['totals'] = $total_data;
		}
		$this->load->model('tool/image');
		$this->data['products'] = array();
		$this->data['total'] = 0.00;
	    $this->data['isAjax'] = (isset($this->request->get['params']['isAjax']) ? true : false);
	    $this->data['coupon'] = false;
	    if(isset($this->session->data['coupon'])) {
	    	$this->data['coupon'] = true;
	    }
		foreach ($this->cart->getProducts() as $product) {
		    if ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
				$image = $product['image'];
		    } else {
				$image = 'no_image.jpg';
		    }
		    $option_data = array();
		    foreach ($product['option'] as $option) {
			$option_data[] = array(
			    'name' => $option['name'],
			    'value' => $option['value']
			);
		    }
		    $this->data['products'][] = array(
			'product_id' => $product['product_id'],
			'name' => $product['name'],
			'thumb' => $this->model_tool_image->resize($image, 150, 150),
			'model' => $product['model'],
			'option' => $option_data,
			'quantity' => $product['quantity'],
			'tax' => $this->tax->getRate($product['tax_class_id']),
			'price' => $this->currency->format($product['price']),
			'total' => $this->currency->format($product['total']),
			'href' => makeUrl('product/product', array('product_id=' . $product['product_id']), true),
			'meta_link' => QS::getMetaLink(isset($product['meta_link'])?$product['meta_link']:'',$product['name']),
			'alt_title' => QS::getMetaLink(isset($product['img_alt'])?$product['img_alt']:'',$product['name'])
		    );
		    $this->data['total'] += $product['total'];
		}

		$this->data['link_edit_cart'] = makeUrl('route=checkout/cart', array(), true);
		$aSymbol = $this->currency->getSymbol();
		$this->data['currency_sign_left'] = $aSymbol['symbol_left'];
		$this->data['currency_sign_right'] = $aSymbol['symbol_right'];
		$this->id = 'left_bar';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/left_bar.tpl')) {
		    $this->template = $this->config->get('config_template') . '/template/checkout/left_bar.tpl';
		} else {
		    $this->template = 'default/template/checkout/left_bar.tpl';

		}
        $this->render();
    }
}
?>