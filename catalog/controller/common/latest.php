<?php

class ControllerCommonLatest extends Controller {

    public function index() {
	$this->language->load('common/latest');
	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTP_SERVER . 'common/home',
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);
	$this->document->breadcrumbs[] = array(
	    'href' => HTTP_SERVER . 'common/latest',
	    'text' => $this->language->get('heading_title'),
	    'separator' => $this->language->get('text_separator')
	);
	if (isset($this->request->get['keyword'])) {
	    $url = '';

	    if (isset($this->request->get['category_id'])) {
		$url .= '&category_id=' . $this->request->get['category_id'];
	    }

	    if (isset($this->request->get['description'])) {
		$url .= '&description=' . $this->request->get['description'];
	    }

	    $this->document->breadcrumbs[] = array(
		'href' => HTTP_SERVER . 'product/search&keyword=' . $this->request->get['keyword'] . $url,
		'text' => $this->language->get('text_search'),
		'separator' => $this->language->get('text_separator')
	    );
	}

	$this->session->data['redirect_continue'] = HTTPS_SERVER . 'common/latest';
	$this->document->title = $this->config->get('config_title');
	$this->document->description = $this->config->get('config_meta_description');

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->load->model('setting/store');
	$this->data['text_latest'] = $this->language->get('text_latest');
	$this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');


	$this->load->model('tool/seo_url');
	$this->load->model('tool/image');
	$this->data['action'] = HTTP_SERVER . 'checkout/cart';
	$this->data['products'] = array();
	$this->data['total_product'] = 21;
	foreach (Make::a('catalog/product')->create()->getLatestProducts(21) as $result) {
	    if ($result['image']) {
		$image = $result['image'];
	    } else {
		$image = 'no_image.jpg';
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

	    $this->data['products'][] = array(
		'id' => $result['product_id'],
		'name' => $result['name'],
		'model' => $result['model'],
		'manufacturer' => $result['manufacturer'],
		'rating' => $rating,
		'stars' => sprintf($this->language->get('text_stars'), $rating),
		'image' => $this->model_tool_image->resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
		'price' => $price,
		'special' => $special,
		'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/product&product_id=' . $result['product_id']),
		'options' => Make::a('catalog/product')->create()->getProductOptions($result['product_id'])
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

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/latest.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/common/latest.tpl';
	} else {
	    $this->template = 'default/template/common/latest.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>