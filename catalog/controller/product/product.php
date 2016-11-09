<?php

class ControllerProductProduct extends Controller {

    private $error = array();

    public function index() {
	$this->language->load('product/product');

	$this->load->model('tool/seo_url');
	$this->load->model('tool/image');
	$this->load->model('catalog/information');

	$this->document->breadcrumbs = array();
	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home', array(), true),
	    'text' => $this->language->get('text_home'),
	    'separator' => $this->language->get('text_separator')
	);

	if (isset($this->request->get['path'])) {
	    $path = '';
	    $parts = explode('_', $this->request->get['path']);
	    $count = count($parts);
	    foreach ($parts as $i => $path_id) {
		$category_info = Make::a('catalog/category')->create()->getCategory($path_id);
		if (!$path) {
		    $path = $path_id;
		} else {
		    $path .= '_' . $path_id;
		}
		if ($category_info) {
		    $this->document->breadcrumbs[] = array(
			'href' => makeUrl('product/category', array('path=' . $path), true),
			'text' => html_entity_decode($category_info->name),
			'separator' => $this->language->get('text_separator')
		    );
		}
	    }
	}
	$product_id = $this->request->get['product_id'];
	$category_info = Make::a('catalog/category')->create()->getCategoryByProductId($product_id);
	$this->data['category_name'] = html_entity_decode($category_info['name']);
	$this->data['category_code'] = html_entity_decode($category_info['ref_category_code']);
	$this->data['category_id'] = $category_info['category_id'];
	$this->data['parent_id'] = $category_info['parent_id'];

	if (isset($this->request->get['manufacturer_id'])) {
	    $manufacturer_info = Make::a('catalog/manufacturer')->create()->getManufacturer($this->request->get['manufacturer_id']);
	    if ($manufacturer_info) {
		$this->document->breadcrumbs[] = array(
		    'href' => makeUrl('product/manufacturer', array('manufacturer_id=' . $this->request->get['manufacturer_id']), true),
		    'text' => $manufacturer_info['name'],
		    'separator' => $this->language->get('text_separator')
		);
	    }
	}
	if (isset($this->request->get['keyword'])) {

        $url = array();
        $url[] = 'keyword=' . $this->request->get['keyword'];
	    if (isset($this->request->get['category_id'])) {
		$url[] = 'category_id=' . $this->request->get['category_id'];
	    }
	    if (isset($this->request->get['description'])) {
		$url[] = 'description=' . $this->request->get['description'];
	    }

	    $this->document->breadcrumbs[] = array(
		'href' => makeUrl('product/search',$url,true),
		'text' => $this->language->get('text_search'),
		'separator' => $this->language->get('text_separator')
	    );
	}

	if (isset($this->request->get['product_id'])) {
	    $product_id = $this->request->get['product_id'];
	} else {
	    $product_id = 0;
	}
	$customer_group_id = $this->customer->getCustomerGroupId() ? $this->customer->getCustomerGroupId() : $this->config->get('config_customer_group_id');

	$product_info = Make::a('catalog/product')->create()->getProduct($product_id);
	if ($product_info) {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' || (isset($this->request->get['wishlist']) && $this->request->get['wishlist'])) {
            $this->data['notice_main'] = __("Please specify the product's required option(s)");
        }
        else {
            $this->data['notice_main'] = "";
        }

	    $product_info = $product_info->toArray();
	    $url = '';
	    if (isset($this->request->get['path'])) {
		$url .= '&path=' . $this->request->get['path'];
	    }
	    if (isset($this->request->get['manufacturer_id'])) {
		$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
	    }
	    if (isset($this->request->get['keyword'])) {
		$url .= '&keyword=' . $this->request->get['keyword'];
	    }
	    if (isset($this->request->get['category_id'])) {
		$url .= '&category_id=' . $this->request->get['category_id'];
	    }
	    if (isset($this->request->get['description'])) {
		$url .= '&description=' . $this->request->get['description'];
	    }
	    $aUrl = array('product_id=' . $this->request->get['product_id']);
	    if ($url != '') {
		    $aUrl = array_merge($aUrl, explode('&', $url));
	    }
	    $this->document->breadcrumbs[] = array(
		'href' => makeUrl('product/product', $aUrl, true),
		'text' => $product_info['name'],
		'title' => $product_info['meta_link'] ? metaLink($product_info['meta_link'], false, $category_info['name']) : $product_info['name'],
		'separator' => FALSE
	    );
	    $this->data['url']=makeUrl('product/product', $aUrl , true);
	    $this->document->title = (isset($product_info['meta_title']) && $product_info['meta_title'] != '') ? $product_info['meta_title'] : $product_info['name'];
	    $this->document->description = $product_info['meta_description'];
	    $this->document->keywords = $product_info['meta_keywords'];
	    $this->document->links = array();
	    $this->document->links[] = array(
		'href' => makeUrl('product/product', array('product_id=' . $this->request->get['product_id']), true),
		'rel' => 'canonical'
	    );
	    
        $this->data['email'] = $this->config->get('config_email');
	    $this->data['customer'] = $this->customer->isLogged();
	    $this->data['heading_title'] = html_entity_decode($product_info['name']);
	    $this->data['meta_link'] = QS::getMetaLink($product_info['meta_link'], $product_info['name'], $category_info['name']);
	    $this->data['alt_title'] = QS::getMetaLink($product_info['img_alt'], $product_info['name'], $category_info['name']);
	    $this->data['manufacturer'] = $product_info['manufacturer'];
	    $this->data['manufacturer_id'] = $product_info['manufacturer_id'];
	    $this->data['text_enlarge'] = $this->language->get('text_enlarge');
	    $this->data['button_cart'] = $this->language->get('button_cart');
	    $this->data['text_total'] = $this->language->get('text_total');
	    $this->data['text_discount'] = $this->language->get('text_discount');
	    $this->data['text_options'] = $this->language->get('text_options');
	    $this->data['text_price'] = $this->language->get('text_price');
	    $this->data['text_availability'] = $this->language->get('text_availability');
	    $this->data['text_model'] = $this->language->get('text_model');
	    $this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
	    $this->data['text_order_quantity'] = $this->language->get('text_order_quantity');
	    $this->data['text_price_per_item'] = $this->language->get('text_price_per_item');
	    $this->data['text_qty'] = $this->language->get('text_qty');
	    $this->data['text_write'] = $this->language->get('text_write');
	    $this->data['text_average'] = $this->language->get('text_average');
	    $this->data['text_no_rating'] = $this->language->get('text_no_rating');
	    $this->data['text_note'] = $this->language->get('text_note');
	    $this->data['text_no_images'] = $this->language->get('text_no_images');
	    $this->data['text_no_related'] = $this->language->get('text_no_related');
	    $this->data['text_related_category'] = $this->language->get('text_related_category');
	    $this->data['text_no_related_category'] = $this->language->get('text_no_related_category');
	    $this->data['text_wait'] = $this->language->get('text_wait');
	    $this->data['text_range'] = $this->language->get('text_range');
	    $this->data['text_material'] = $this->language->get('text_material');
	    $this->data['text_email'] = $this->language->get('text_email');
	    $this->data['text_send'] = $this->language->get('text_send');

	    $this->data['entry_name'] = $this->language->get('entry_name');
	    $this->data['entry_review'] = $this->language->get('entry_review');
	    $this->data['entry_rating'] = $this->language->get('entry_rating');
	    $this->data['entry_good'] = $this->language->get('entry_good');
	    $this->data['entry_bad'] = $this->language->get('entry_bad');
	    $this->data['entry_captcha'] = $this->language->get('entry_captcha');
	    $this->data['text_buy_from'] = sprintf(__('text_buy_from'), $this->config->get('config_name'));
	    $this->data['button_continue'] = $this->language->get('button_continue');
	    $this->data['tab_description'] = $this->language->get('tab_description');
	    $this->data['tab_image'] = $this->language->get('tab_image');
	    $this->data['tab_review'] = sprintf($this->language->get('tab_review'), Make::a('catalog/review')->create()->getTotalReviewsByProductId($this->request->get['product_id']));
	    $this->data['tab_related'] = $this->language->get('tab_related');
	    $average = Make::a('catalog/review')->create()->getAverageRating($this->request->get['product_id']);
	    $this->data['total_review'] = Make::a('catalog/review')->create()->getTotalReviewsByProductId($this->request->get['product_id']);
	    $this->data['text_stars'] = sprintf($this->language->get('text_stars'), $average);
	    $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
	    $this->data['action'] = HTTP_SERVER . 'checkout/cart';
	    $this->data['redirect'] = makeUrl('product/product', $aUrl, true);

	    $discount = Make::a('catalog/product')->create()->getProductDiscount($this->request->get['product_id']);
	    if ($discount) {
		$price = $this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
		$this->data['special'] = FALSE;
	    } else {
		$price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
		$special = Make::a('catalog/product')->create()->getProductSpecial($this->request->get['product_id']);
		if ($special) {
		    $special_price = $this->tax->calculate($special['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
		    $save_price = $product_info['price'] - $special['price'];
		    $this->data['save_price'] = $this->currency->format($this->tax->calculate($save_price, $product_info['tax_class_id'], $this->config->get('config_tax'))); 
			$this->data['save_percent'] = ceil(($product_info['price'] > 0 ? (($save_price / $product_info['price']) * 100) : 0));
		} else {
		    $this->data['special'] = FALSE;
		}
	    }
	    $this->document->meta['price'] = $price;
	    $this->data['price'] = $this->currency->format($price);
	    if ($special_price) {
		$this->document->meta['price'] = $special_price;
		$this->data['special'] = $this->currency->format($special_price);
	    }
	    $discounts = Make::a('catalog/product')->create()->getProductDiscounts($this->request->get['product_id']);
	    $this->data['discounts'] = array();
	    foreach ($discounts as $discount) {
		$this->data['discounts'][] = array('quantity' => $discount['quantity'], 'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))));
	    }
	    
		$this->data['price_org'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')),'','',false);
		$this->data['special_price_org'] = $this->currency->format($this->tax->calculate($special['price'], $product_info['tax_class_id'], $this->config->get('config_tax')),'','',false);
		//d($this->data['special_price_org']);
		//d($product_info);
	    /*if ($product_info['stock_status_id'] == '5') {
		if ($this->config->get('config_stock_display')) {
		    $this->data['stock'] = $this->language->get('text_outstock');
		} else {}
	    } else {
		if ($product_info['stock_status_id'] == '7') {		    
		    $this->data['stock'] = $this->language->get('text_instock');
		} else {}
	    }*/
	    if ($product_info['quantity'] <= 0) {
		if ($this->config->get('config_stock_display')) {
		    $this->data['stock'] = $this->language->get('text_outstock');
		} else {
		}
	    } else {
		if ($this->config->get('config_stock_display')) {
		    $this->data['stock'] = $this->language->get('text_instock');
		} else {
		}
	    }
	    //d($product_info['quantity']);


	    $config_allow_reward = $this->config->get('config_allow_reward');


	    //$this->data['stock'] = $product_info['stock'];
	    $this->data['model'] = $product_info['model'];
	    $this->data['manufacturer'] = $product_info['manufacturer'];
	    $this->data['manufacturer_image'] = $this->model_tool_image->resize($product_info['manufacturer_image'], 90, 45);
	    $this->data['manufacturer_link'] = makeUrl('product/search', array('keyword= ', 'filter[manufacturer][]=' . $product_info['manufacturer_id']), true);
	    $this->data['meta_description'] = html_entity_decode($product_info['meta_description'], ENT_QUOTES, 'UTF-8');
	    $this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
	    $this->data['currency_symbol'] = $this->currency->getSymbol();
	    // = ($aCurrencySymbol['symbol_left']?$aCurrencySymbol['symbol_left']:$aCurrencySymbol['symbol_right']);
	    $this->data['material'] = $product_info['material'];
	    $this->data['product_id'] = $this->request->get['product_id'];
	    $this->data['weight'] = $product_info['weight'];
	    $this->data['width'] = $product_info['width'];
	    $this->data['height'] = $product_info['height'];
	    $this->data['length'] = $product_info['length'];
	    $this->data['points'] = $product_info['points'];
	    $this->data['size_chart'] = $product_info['size_chart'];;
	    $this->data['delivery_days'] = $product_info['delivery_days'];
	    $this->data['delivery_days_standard'] = $product_info['delivery_days_standard'];
	    $this->data['delivery_days_custom'] = $product_info['delivery_days_custom'];
	    $this->data['config_allow_reward'] = $config_allow_reward;
	    $this->data['wishlist_id'] = (isset($product_info['wishlist_id']) ? $product_info['wishlist_id'] : '');
	    $this->data['average'] = $average;
	    $length_class = Make::a('catalog/product')->create()->getLengthClass($product_info['length_class_id']);
	    $this->data['length_class'] = $length_class['title'];
	    $weight_class = Make::a('catalog/product')->create()->getWeightClass($product_info['weight_class_id']);
	    $this->data['weight_class'] = $weight_class['unit'];
	    $this->data['product_colors'] = Make::a('catalog/product')->create()->getProductColors($this->request->get['product_id']);
	    $this->data['allow_review'] = $this->config->get('config_review');
	    $this->data['stock_status'] = $product_info['stock'];
	    $this->data['quantity'] = $product_info['quantity'];
	    //$this->data['breadcrumbs'] = $this->document->breadcrumbs;
	    $returns_exchange = $this->model_catalog_information->getInformation(35);
		$this->data['returns_exchange'] = html_entity_decode($returns_exchange['description'], ENT_QUOTES, 'UTF-8');

		$shipping_details = $this->model_catalog_information->getInformation(42);
		$this->data['shipping_details'] = html_entity_decode($shipping_details['description'], ENT_QUOTES, 'UTF-8');

	    $this->data['options'] = array();
	    $options = Make::a('catalog/product')->create()->getProductOptions($this->request->get['product_id']);
	    $this->data['normal_options'] = $options;
	    $this->data['option_quantity'] = 0;
	    $this->data['extra_description'] = array();
	    $this->data['aDependent'] = array();
	    $aDependents = array();
	    foreach ($options as $option) {
			$option_value_data = $aColor = $aOptionColorValue = $aSize = array();
		    foreach ($option['option_value'] as $option_value) {
				$thumb = '';
				if ($option_value['thumb'] && file_exists(DIR_IMAGE . $option_value['thumb'])) {
				    $thumb = HTTPS_IMAGE . $option_value['thumb'];
				}
				$image = '';
				if ($option_value['image'] && file_exists(DIR_IMAGE . $option_value['image'])) {
				    $image = HTTPS_IMAGE . $option_value['image'];
				}
				$price = $price_org = false;
				$option_text = $option_value['name'];
				if($option_value['price'] != 0) {
					$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				    $price_org = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')),'','', false);
					$option_text .= ' (' . $option_value['prefix'] . ' ' . $price . ')';
				}
				$name = $option_value['name'];
				$key = count($option_value_data);
				if ($option['product_option_type_id'] == 5) {
					$aName = explode('/', $name);
					$aDependents[$aName[0]][$aName[1]]['id'] = $option_value['product_option_value_id'];
					$aDependents[$aName[0]][$aName[1]]['quantity'] = $option_value['quantity'];
					if(!in_array($aName[1],$aSize))
				    	$aSize[] = $aName[1];
					if (empty($aColor) || in_array($aName[0], $aColor) === false) {
					    $aColor[] = $aName[0];
					    //$iColor = $option_value['product_option_value_id'];
					  //   $thumb = '';
					  //   if ($option_value['thumb'] && file_exists(DIR_IMAGE . $option_value['thumb'])) {
							// $thumb = HTTPS_IMAGE . $option_value['thumb'];
					  //   }
					  //   $image = '';
					  //   if ($option_value['image'] && file_exists(DIR_IMAGE . $option_value['image'])) {
							// $image = HTTPS_IMAGE . $option_value['image'];
					  //   }
					    $aOptionColorValue[] = array(
							'option_value_id' => $option_value['product_option_value_id'],
							'name' => $aName[0],
							'index' => array_search($aName[0], $aColor),
							'quantity' => $option_value['quantity'],
							'help' => '',
							'price' => FALSE,
							'price_org' => FALSE,
							'thumb' => $thumb,
							'image' => $image,
							'prefix' => '',
							'min_size' => '',
							'max_size' => '',
					    );
					}
					$name = $aName[1];
					// $key = array_search($aName[0], $aColor);
					$index = array_search($aName[1],$aSize);
					$key = ($index !== false ? $index : $key);
					if (empty($this->data['aDependent']) || !in_array($aName[1], $this->data['aDependent'])) {
					    $this->data['aDependent'][$option_value['product_option_value_id']] = $aName[1];
					}
				}

				$option_value_data[$key] = array(
				    'option_value_id' => $option_value['product_option_value_id'],
				    'name' => $name,
				    'quantity' => $option_value['quantity'],
				    'help' => $option_value['help'],
				    'price' => $price,
				    'price_org' => $price_org,
				    'prefix' => $option_value['prefix'],
				    'thumb' => $thumb,
				    'image' => $image,
				    'min_size' => $option_value['min_size'],
				    'max_size' => $option_value['max_size'],
				    'option_text' => $option_text
				);
				$this->data['option_quantity'] += $option_value['quantity'];
			}
		    $bDependent = $option['product_option_type_id'] == 5 ? true : false;
		    $option_name = $option['name']; 
		    $type_id = $option['product_option_type_id'];
		    if($bDependent) {
			    $aOptionName = explode('/', $option['name']);
			    $baseDepended = array(
					'option_id' => $option['product_option_id'],
					'name' => $aOptionName[0],
					'product_option_id' => $option['product_option_id'],
					'product_option_type_id' => $option['product_option_type_id'],
					'parent_id' => $option['parent_id'],
					'option_value' => $aOptionColorValue,
					'isDependent' => true
			    );
			    $option_name = $aOptionName[1];
			    $type_id = 7;
		    }
		    $array = array(
				'option_id' => $option['product_option_id'],
				'name' => $option_name,
				'product_option_id' => $option['product_option_id'],
				'product_option_type_id' => $option['product_option_type_id'],
				'parent_id' => $option['parent_id'],
				'option_value' => $option_value_data,
				'isDependent' => $bDependent
		    );

		    if ($option['parent_id'] == 0) {
	    		$options_data[$type_id][$option['product_option_id']] = $array;
		    	if($bDependent)
				    $options_data[5][$option['product_option_id']] = $baseDepended;
		    }
		    else {
	    		$options_data[4][$option['parent_id']]['child'][$type_id][$option['product_option_id']] = $array;
		    	if($bDependent)
			    	$options_data[4][$option['parent_id']]['child'][5][$option['product_option_id']] = $baseDepended;
		    }
	    }
	    $this->data['options'] = $options_data;

	    if ($product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
        	$image = $product_info['image'];
        } else {
        	$image = 'no_image.jpg';
        }

        $this->data['images'] = array();
	    $results = Make::a('catalog/product')->create()->getProductImages($this->request->get['product_id']);
	    $size = getimagesize(DIR_IMAGE . $image);
	    $this->data['images'][] = array(
			'popup' => $this->model_tool_image->resize($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
			'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
			'large' => $this->model_tool_image->resize($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
			'size'  => $size[0] . 'x' . $size[1]
		);
	    foreach ($results as $result) {
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
			    $image = $result['image'];
			} else {
			    $image = 'no_image.jpg';
			}
			$size = getimagesize(DIR_IMAGE . $image);
			$this->data['images'][] = array(
			    'popup' => $this->model_tool_image->resize($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
			    'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
			    'large' => $this->model_tool_image->resize($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
			    'size'  => $size[0] . 'x' . $size[1]
			);
	    }

	    $this->data['products'] = array();
	    $results = Make::a('catalog/product')->create()->getProductRelated($this->request->get['product_id'], PRODUCT_RELATED, 5);
	    foreach ($results as $result) {
		$result = $result->toArray();

		if (isset($result['image']) && $result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
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
		$this->data['products'][] = array(
		    'id' => $result['product_id'],
		    'name' => $result['name'],
		    'product_id' => $result['product_id'],
		    'model' => $result['model'],
		    'manufacturer' => $result['manufacturer'],
		    'rating' => $rating,
		    'stars' => sprintf($this->language->get('text_stars'), $rating),
		    'thumb' => $this->model_tool_image->resize($thumb, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height')),
		    'extra_img' => $this->model_tool_image->resize($extra_img, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
		    'price' => $price,
		    'special' => $special,
		    'special_price_org' => $special,
		    'percent' => $special_percent,
		    'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
		    'meta_link' => QS::getMetaLink($result['meta_link'], $result['name']),
		    'alt_title' => QS::getMetaLink($result['img_alt'], $result['name']),
		    'options' => array(), //Make::a('catalog/product')->create()->getProductOptions($result['product_id']),
		    'add_to_wishlist' => makeUrl('account/wishlist', array(), true) . '&product_id=' . $result['product_id'],
		);
	    }

        $result_manufacturer=$this->model_catalog_information->getManufacturerByID($this->data['manufacturer_id']);
        foreach($result_manufacturer as $manufacturer){
            $Mimage = '';
                $Mimage = HTTPS_IMAGE . $manufacturer['image'];

            $this->load->model('localisation/country');
            $mCountry=$this->model_localisation_country->getCountry($manufacturer['country_id']);


            $this->data['manufacturer']=array(
                'manufacturer_id'=>$manufacturer['manufacturer_id'],
                'name'=>$manufacturer['name'],
                'image'=>$Mimage,
                'country'=>$mCountry['name'],
                'description'=>$manufacturer['description'],
                'meta_title'=>$manufacturer['meta_title']
            );
        }

	    $this->data['cross_products'] = array();
	    $results = Make::a('catalog/product')->create()->getProductRelated($this->request->get['product_id'], PRODUCT_CROSS_SELL, 5);

	    foreach ($results as $result) {
		$result = $result->toArray();

		if (isset($result['image']) && $result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
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

			$special_price_org = $this->currency->format($this->tax->calculate($special['price'], $result['tax_class_id'], $this->config->get('config_tax')));

		    }
		}
		$this->data['cross_products'][] = array(
		    'id' => $result['product_id'],
		    'name' => $result['name'],
		    'product_id' => $result['product_id'],
		    'model' => $result['model'],
		    'manufacturer' => $result['manufacturer'],
		    'rating' => $rating,
		    'stars' => sprintf($this->language->get('text_stars'), $rating),
		    'thumb' => $this->model_tool_image->resize($thumb, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height')),
		    'extra_img' => $this->model_tool_image->resize($extra_img, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
		    'price' => $price,
		    'special' => $special,
		    'percent' => $special_percent,
		    'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
		    'meta_link' => QS::getMetaLink($result['meta_link'], $result['name']),
		    'alt_title' => QS::getMetaLink($result['img_alt'], $result['name']),
		    'options' => array(), //Make::a('catalog/product')->create()->getProductOptions($result['product_id']),
		    'add_to_wishlist' => makeUrl('account/wishlist', array(), true) . '&product_id=' . $result['product_id'],
		);
	    }
	    if (!$this->config->get('config_customer_price')) {
		$this->data['display_price'] = TRUE;
	    } elseif ($this->customer->isLogged()) {
		$this->data['display_price'] = TRUE;
	    } else {
		$this->data['display_price'] = FALSE;
	    }
	    $this->data['wishlist'] = makeUrl('account/wishlist', array(), true) . '&product_id=' . $product_id;
// $price_slabs = Make::a('catalog/product')->create()->getProductSlabs();
	    $price_slabs = array();
	    $slabs = array_reverse($price_slabs, true);
	    $strSlab = '';
	    $count = count($slabs);
	    foreach ($slabs as $slab) {
		$count--;
		$strSlab .= 'if(qty >= ' . $slab['quantity'] . ') {' . "\n";
		$strSlab .= ' var price = $("#price_item_' . $count . '"+i).text().substring(1);' . "\n";
		$strSlab .= '}';
		if ($count) {
		    $strSlab .= ' else ';
		}
	    }
	    $this->data['slabs'] = $strSlab;

	    $this->data['out_of_stock'] = makeUrl('product/product/outofStock',array(),true);

	    $delivery_days = $product_info['delivery_days'];
	    $cutoff_time = (!empty($product_info['cutoff_time']) ? $product_info['cutoff_time'] : $this->config->get('config_cutoff_time'));
	    $this->document->addScript("catalog/view/javascript/jquery/jquery.easing.min.js", Document::POS_END);
	    $this->document->addScript("catalog/view/javascript/jquery/jquery.colorbox.min.js", Document::POS_END);
	    $this->document->addScript("catalog/view/javascript/jquery/jquery.cloudzoom.min.js", Document::POS_END);
	    $this->document->addScript("catalog/view/javascript/jquery/photo-swipe.js", Document::POS_END);
	    $this->document->addScript("catalog/view/javascript/jquery/product.js", Document::POS_END);
	    $this->document->addScriptInline("
	    	var aDependents = " . json_encode($aDependents) . ";
	    	var aDependents_length = " . count($aDependents)  . ";
	    	var product_id = " . $product_id . ";
	    	var delivery_days = " . $delivery_days . ";
	    	var tCuttOff = '" . $cutoff_time . "';
	    	var dServer = '" . date('m/d/Y H:i:s') . "';
	    	", Document::POS_HEAD);	   

	    Make::a('catalog/product')->create()->updateViewed($this->request->get['product_id']);
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
		$this->template = $this->config->get('config_template') . '/template/product/product.tpl';
	    } else {
		$this->template = 'default/template/product/product.tpl';
	    }
	    $this->load->model('catalog/information');
	    $results = $this->model_catalog_information->getInformationLink('product');
	    $this->data['informations'] = array();
	    $links = array();
	    foreach ($results as $result) {
		$links[] = array('meta_title' => str_replace(' ', '_', strtolower($result['title'])), 'name' => $result['title'], 'href' => $this->model_tool_seo_url->rewrite($result['link'] . '&box=1'));
	    }
	    $this->data['informations'] = $links;
	    $this->data['template'] = $this->config->get('config_template');
	    $this->data['quantities'] = array();
	    for ($i = 1; $i <= 50; $i++)
		$this->data['quantities'][] = $i;
		
	    $this->document->meta['image'] = HTTP_IMAGE . ($product_info['image'] ? $product_info['image'] : $product_info['thumb']);
	    $this->document->loadKnow = false;
	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
	} else {
	    $url = '';
	    if (isset($this->request->get['path'])) {
		$url .= '&path=' . $this->request->get['path'];
	    }
	    if (isset($this->request->get['manufacturer_id'])) {
		$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
	    }
	    if (isset($this->request->get['keyword'])) {
		$url .= '&keyword=' . $this->request->get['keyword'];
	    }
	    if (isset($this->request->get['category_id'])) {
		$url .= '&category_id=' . $this->request->get['category_id'];
	    }
	    if (isset($this->request->get['description'])) {
		$url .= '&description=' . $this->request->get['description'];
	    }
	    $aUrl = array('product_id=' . $product_id);
	    if ($url != '') {
		$aUrl = array_merge($aUrl, explode('&', $url));
	    }
	    $this->document->breadcrumbs[] = array(
		'href' => makeUrl('product/product', $aUrl, true),
		'text' => $this->language->get('text_error'),
		'separator' => FALSE
	    );
	    $this->document->title = $this->language->get('text_error');
	    $this->data['heading_title'] = $this->language->get('text_error');
	    $this->data['text_error'] = $this->language->get('text_error');
	    $this->data['button_continue'] = $this->language->get('button_continue');
	    $this->data['continue'] = makeUrl('common/home', array(), true);
	    $this->data['template'] = $this->config->get('config_template');
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
		$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
	    } else {
		$this->template = 'default/template/error/not_found.tpl';
	    }

	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
    }

    public function review() {
	$this->language->load('product/product');

	$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');
	if (isset($this->request->get['page'])) {
	    $page = $this->request->get['page'];
	} else {
	    $page = 1;
	}
	$this->data['reviews'] = array();
	$results = Make::a('catalog/review')->create()->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 3, 3);
	foreach ($results as $result) {
	    $this->data['reviews'][] = array('author' => $result['author'], 'rating' => $result['rating'], 'text' => strip_tags($result['text']), 'stars' => sprintf($this->language->get('text_stars'), $result['rating']), 'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])));
	}
	$review_total = Make::a('catalog/review')->create()->getTotalReviewsByProductId($this->request->get['product_id']);
	$this->data['template'] = $this->config->get('config_template');
	$pagination = new Pagination();
	$pagination->total = $review_total;
	$pagination->page = $page;
	$pagination->limit = 3;
	$pagination->showlinks = false;
	$pagination->showresults = false;
	$pagination->text = $this->language->get('text_pagination');
	$pagination->url = makeUrl('product/product/review', array('product_id=' . $this->request->get['product_id']), true) . '&page={page}';
	$this->data['pagination'] = $pagination->render();
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/product/review.tpl';
	} else {
	    $this->template = 'default/template/product/review.tpl';
	}
	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function write() {
	$this->language->load('product/product');

	$json = array();
	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	    Make::a('catalog/review')->create()->addReview($this->request->get['product_id'], $this->request->post);
	    $json['success'] = $this->language->get('text_success');
	} else {
	    $json['error'] = $this->error['message'];
	}
	$this->load->library('json');
	$this->response->setOutput(Json :: encode($json));
    }

    public function captcha() {
	$this->load->library('captcha');
	$captcha = new Captcha();
	$this->session->data['captcha'] = $captcha->getCode();
	$captcha->showImage();
    }

    private function validate() {
	if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 25)) {
	    $this->error['message'] = $this->language->get('error_name') . '<br />';
	}
	if ((strlen(utf8_decode($this->request->post['text'])) < 15) || (strlen(utf8_decode($this->request->post['text'])) > 1000)) {
	    $this->error['message'] .= $this->language->get('error_text') . '<br />';
	}
	if (!$this->request->post['rating']) {
	    $this->error['message'] .= $this->language->get('error_rating') . '<br />';
	}
	if (!$this->customer->isLogged() && $this->session->data['captcha'] != $this->request->post['captcha']) {
	    $this->error['message'] .= $this->language->get('error_captcha');
	}
	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

    public function like() {
    	try {
    		$this->language->load('product/product');
    		if(!$this->customer->isLogged()) {
    			$res['not_logged'] = true;
    			throw new Exception($this->language->get('text_error_customer'));
    		}
    		if(!isset($this->request->get['product_id']) || $this->request->get['product_id'] == '')
    			throw new Exception($this->language->get('text_error'));
    		$oModel = Make::a('account/wishlist')->create();
			$product_id = $this->request->get['product_id'];
			$customer_id = $this->customer->isLogged();
    		$aOrm = $oModel->getWishlistByProduct($product_id,$customer_id);
    		if($aOrm)
    			throw new Exception($this->language->get('text_error_like'));	
			$oModel->addWishlist(array('product_id' => $product_id,'customer_id' => $customer_id));
			$res['success'] = 'true';
    	}
    	catch(Exception $e) {
    		$res['error'] = $e->getMessage();
    	}
    	echo json_encode($res);
    }

    public function outofStock() {
    	try {
    		$this->language->load('product/product');
    		$email = $this->request->get['email'];
    		if($email == '' || !filter_var($email,FILTER_VALIDATE_EMAIL))
    			throw new Exception($this->language->get('error_email'));
    		if($this->request->get['product_id'] == '')
    			throw new Exception($this->language->get('error_product'));

    		$mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject($this->language->get('text_outstock_subject'));
            $mail->setHtml(sprintf($this->language->get('text_outstock_email'),
            	$this->request->get['name'],$this->request->get['product_id'],$email,$this->config->get('config_name')));
            $mail->send();
            $res['success'] = $this->language->get('text_outstock_success');
    	}
    	catch(Exception $e) {
    		$res['error'] = $e->getMessage();
    	}
    	echo json_encode($res);
    }
    public  function emailStockProduct(){

        if($this->request->get['email']!=''){
            $this->load->model('catalog/product');
            Make::a('catalog/product')->create()->addEmailForAvailabilityProduct($this->request->get);

            echo json_encode(array('success'=> true));
        }
    }

}

?>