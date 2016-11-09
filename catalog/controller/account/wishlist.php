<?php

class ControllerAccountWishlist extends Controller {

    protected $error = array();

    public function index() {

		if (!$this->customer->isLogged()) {
		    $this->session->data['redirect'] = makeUrl('account/account', array(), true, true);
		    $this->redirect(makeUrl('account/login', array(), true, true));
		}

		$this->language->load('account/wishlist');
		$this->load->model('tool/image');
		$this->load->model('tool/seo_url');

		$this->document->layout_col = "col2-left-layout";
		if (isset($this->request->get['page'])) {
	        $page = $this->request->get['page'];
	    } else {
	        $page = 1;
	    }
		if (!$this->config->get('config_customer_price')) {
		    $this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged()) {
		    $this->data['display_price'] = TRUE;
		} else {
		    $this->data['display_price'] = FALSE;
		}

		if (isset($this->error['warning']) && $this->error['warning']) {
		    $this->data['error_warning'] = $this->error['warning'];
		} else {
		    $this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success']) && $this->session->data['success']) {
		    $this->data['success'] = $this->session->data['success'];
		    unset($this->session->data['success']);
		} else {
		    $this->data['success'] = '';
		}

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
		    'href' => makeUrl('account/wishlist', array(), true, true),
		    'text' => $this->language->get('text_wishlist'),
		    'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_error'] = $this->language->get('text_error');

		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_comments'] = $this->language->get('column_comment');
		$this->data['column_added'] = $this->language->get('column_date_added');
		$this->data['column_product'] = $this->language->get('column_product');

		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_update'] = $this->language->get('button_update');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['action'] = makeUrl('account/wishlist', array(), true, true);

		$this->data['products'] = array();
		$limit = $this->config->get('config_catalog_limit');
		$start = ($page - 1) * $limit;
		$results = Make::a('account/wishlist')->create()->getWishlist($this->customer->getId(),$start,$limit);
		foreach ($results[1] as $result) {
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
			'thumb' => $this->model_tool_image->resize($thumb,$width,$height),
			'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
			'meta_link' => QS::getMetaLink($result['meta_link'], $result['name']),
			'alt_title' => QS::getMetaLink($result['img_alt'], $result['name']),
			'wishlist' => makeUrl('account/wishlist', array(), true, true) . '&product_id=' . $result['product_id'],
			'add' => $add
		    );
		}

		$pagination = new Pagination();
	    $pagination->total = $results[0];
	    $pagination->page = $page;
	    $pagination->limit = $limit; //$this->config->get('config_catalog_limit');
	    $pagination->text = null;
	    $pagination->url = makeUrl('account/account', array('tab=tab4'), true) . '&amp;page={page}';
	    $pagination->enable_np = true;
	    $pagination->num_links = 10000;
	    $pagination->list_type = "ol";
	    $pagination->wrapper = false;
	    $pagination->active_class = "current";
	    $pagination->active_wrapper = false;
	    $pagination->prev_class = "previous";
	    $pagination->next_links = "next i-next";
	    $pagination->prev_links = "previous i-previous";
	    $this->data['pagination'] = $pagination->render();


		$this->data['code'] = true;

		$this->data['continue'] = makeUrl('common/home', array(), true);
		$this->data['back'] = makeUrl('account/account', array(), true, true);


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/wishlist.tpl')) {
		    $this->template = $this->config->get('config_template') . '/template/account/wishlist.tpl';
		} else {
		    $this->template = 'default/template/account/wishlist.tpl';
		}

		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>
