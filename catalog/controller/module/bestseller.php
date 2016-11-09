<?php

class ControllerModuleBestSeller extends Controller {

    protected function index() {
	$this->language->load('module/bestseller');

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->load->model('tool/seo_url');
	$this->load->model('tool/image');

	$this->data['products'] = array();

	$this->data['action'] = makeUrl('checkout/cart', array(), true, true);

	$limit = $this->config->get('bestseller_limit');
	$width = $this->config->get('config_image_product_width');
	$height = $this->config->get('config_image_product_height');
	if(isset($this->request->get['params'])) {
		$params = $this->request->get['params'];
		if(isset($params['limit']))
			$limit = $params['limit'];
		if(isset($params['img_w']))
			$width = $params['img_w'];
		if(isset($params['img_h']))
			$height = $params['img_h'];
	}

	$results = Make::a('catalog/product')->create()->getBestSellerProducts($limit);
	//d($results);
	$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
	foreach ($results as $result) {
	    if (isset($result['thumb']) && $result['thumb'] && file_exists(DIR_IMAGE . $result['thumb'])) {
		$thumb = $result['thumb'];
	    } elseif (isset($result['image']) && $result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
		$thumb = $result['image'];
	    } else {
		$thumb = 'no_image.jpg';
	    }
	    $extraImages = Make::a('catalog/product')->create()->getProductImages($result['product_id']);

	    $extra_img = '';
	    if (!empty($extraImages) && file_exists(DIR_IMAGE . $extraImages[0]['image'])) {
		$extra_img = $extraImages[0]['image'];
	    }

	    $rating = Make::a('catalog/review')->create()->getAverageRating($result['product_id']);

	    $special = FALSE;

	    $discount = Make::a('catalog/product')->create()->getProductDiscount($result['product_id']);

	    if ($discount) {
		$price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
	    } else {
		$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

		$special = Make::a('catalog/product')->create()->getProductSpecial($result['product_id']);

		if ($special) {
		    $special = $this->currency->format($this->tax->calculate($special['price'], $result['tax_class_id'], $this->config->get('config_tax')));
		}
	    }
	    $options = Make::a('catalog/product')->create()->getProductOptions($result['product_id']);

	    $this->data['products'][] = array(
		'product_id' => $result['product_id'],
		'name' => $result['name'],
		'model' => $result['model'],
		'description' => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
		'rating' => $rating,
		'extra_img' => $this->model_tool_image->resize($extra_img,$width,$height),
		'stars' => sprintf($this->language->get('text_stars'), $rating),
		'price' => $price,
		'options' => $options,
		'special' => $special,
		'image' => $this->model_tool_image->resize($thumb,$width,$height),
		'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
		'meta_link' => QS::getMetaLink($result['meta_link'], $result['name']),
		'alt_title' => QS::getMetaLink($result['img_alt'], $result['name']),
		'wishlist' => makeUrl('account/wishlist', array(), true, true) . '&product_id=' . $result['product_id'],
		'add' => $add
	    );
	}
	//d($this->data['products']);
	if (!$this->config->get('config_customer_price')) {
	    $this->data['display_price'] = TRUE;
	} elseif ($this->customer->isLogged()) {
	    $this->data['display_price'] = TRUE;
	} else {
	    $this->data['display_price'] = FALSE;
	}

	$this->id = 'bestseller';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bestseller.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/bestseller.tpl';
	} else {
	    $this->template = 'default/template/module/bestseller.tpl';
	}

	$this->render();
    }

}

?>