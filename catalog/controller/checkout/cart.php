<?php

class ControllerCheckoutCart extends Controller {

    private $error = array();

    public function index() {
		$this->language->load('checkout/cart');

		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
		    if (isset($this->request->get['remove'])) {
			$this->cart->remove($this->request->get['remove']);
			$this->session->data['success_main'] = "Success: Product removed successfully";
			$this->redirect($_SERVER['HTTP_REFERER']);
		    }
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST' || $this->request->server['REQUEST_METHOD'] == 'GET') {
		    if (isset($this->request->get['product_id']) || isset($this->request->get['product_detail']) || isset($this->request->get['quantity'])) {
			$this->request->post['product_id'] = $this->request->get['product_id'];
			$this->request->post['product_detail'] = $this->request->get['product_detail'];
			$this->request->post['quantity'] = $this->request->get['quantity'];
		    }

		    if (isset($this->request->post['quantity'])) {
				if (!empty($this->request->post['quantity'])) {
				    if (!is_array($this->request->post['quantity'])) {
						if (isset($this->request->post['option'])) {
						    $aOption = $this->request->post['option'];
						    $group_id = $this->request->post['option_group'];
						    $option =array();
						    if($group_id){
							    $option[$group_id] = $aOption[$group_id];
							    $value = $option[$group_id];
							} else {
								$option = $aOption;
								$value = end($option);
							}
						    //d(array($option, $value), true);

							//d(array($option, $group_id, $value));
						} else {
						    $option = array();
						}

						$this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);
						$this->session->data['success_main'] = __('text_success');
						if (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))
			    			$this->session->data['error_warning'] = $this->language->get('error_stock');
				    } else {
						foreach ($this->request->post['quantity'] as $key => $value) {
						    $this->cart->update($key, $value);
						}
						$this->session->data['success_main'] = __('text_update_success');
				    }
				    $this->session->data['cart_updated'] = true;
				    unset($this->session->data['shipping_methods']);
				    unset($this->session->data['shipping_method']);
				    unset($this->session->data['payment_methods']);
				    unset($this->session->data['payment_method']);
				} else {
				    $this->session->data['error_main'] = __('Quantity should be greater than 0');
				}
		    }


		    if (isset($this->request->post['remove'])) {
				foreach (array_keys($this->request->post['remove']) as $key) {
				    $this->cart->remove($key);
				}
		    }

		    if (isset($this->request->post['redirect'])) {
				$this->session->data['redirect'] = $this->request->post['redirect'];
		    }

		    if(isset($this->request->post['isAjax'])) {
		    	if(isset($this->session->data['error_main'])) {
		    		$res['error'] = $this->session->data['error_main'];
		    	}
		    	else {
		    		$res['success'] = isset($this->session->data['success_main']) ? $this->session->data['success_main'] : __('text_remove_success');
		    		$res['total'] = $this->currency->format($this->cart->getTotal());
		    		$res['count'] = $this->cart->countProducts();
		    	}
		    	$this->response->setOutput(json_encode($res), $this->config->get('config_compression'));
		    }
		    elseif (isset($this->request->post['quantity']) || isset($this->request->post['remove'])) {
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['payment_method']);
		//		if (isset($this->request->post['redirect']) && $this->request->post['redirect']) {
		//		    $this->redirect($this->request->post['redirect']);
		//		} else {
		//		    $this->redirect(HTTPS_SERVER . 'checkout/cart');
		//		}
				$this->redirect($_SERVER['HTTP_REFERER']);
		    }
		}

		if(!isset($this->request->post['isAjax'])) {
			$this->redirect(makeUrl('common/home',array(),true));
			$this->document->title = $this->language->get('heading_title');
			$this->document->breadcrumbs = array();
			$this->document->breadcrumbs[] = array(
			    'href' => makeUrl('common/home', array(), true),
			    'text' => $this->language->get('text_home'),
			    'separator' => $this->language->get('text_separator')
			);
			$this->document->breadcrumbs[] = array(
			    'href' => makeUrl('checkout/cart', array(), true, true),
			    'text' => $this->language->get('text_basket'),
			    'separator' => FALSE
			);
			if ($this->cart->hasProducts()) {
			    $this->data['heading_title'] = $this->language->get('heading_title');

			    $this->data['text_select'] = $this->language->get('text_select');
			    $this->data['text_sub_total'] = $this->language->get('text_sub_total');
			    $this->data['text_discount'] = $this->language->get('text_discount');
			    $this->data['text_weight'] = $this->language->get('text_weight');
			    $this->data['text_alert'] = $this->language->get('text_alert');
			    $this->data['text_message'] = $this->language->get('text_error');

			    $this->data['column_remove'] = $this->language->get('column_remove');
			    $this->data['column_image'] = $this->language->get('column_image');
			    $this->data['column_name'] = $this->language->get('column_name');
			    $this->data['column_model'] = $this->language->get('column_model');
			    $this->data['column_quantity'] = $this->language->get('column_quantity');
			    $this->data['column_price'] = $this->language->get('column_price');
			    $this->data['column_total'] = $this->language->get('column_total');
			    $this->data['column_through'] = $this->language->get('column_through');
			    $this->data['column_min_amount'] = $this->language->get('column_min_amount');
			    $this->data['column_alert_name'] = $this->language->get('column_alert_name');
			    $this->data['column_alert_email'] = $this->language->get('column_alert_email');

			    $this->data['button_update'] = $this->language->get('button_update');
			    $this->data['button_delete'] = $this->language->get('button_delete');
			    $this->data['button_save'] = $this->language->get('button_save');
			    $this->data['button_shopping'] = $this->language->get('button_shopping');
			    $this->data['button_checkout'] = $this->language->get('button_checkout');
			    $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
			    $this->data['button_wishlist'] = $this->language->get('button_wishlist');

			    if (isset($this->error['warning'])) {
				    $this->data['error_warning'] = $this->error['warning'];
			    } else if (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) {
				    $this->data['error_warning'] = $this->language->get('error_stock');
			    } else if(isset($this->session->data['error_warning'])) {
		            $this->data['error_warning'] = $this->session->data['error_warning'];
		            unset($this->session->data['error_warning']);
		        }
		        else {
				    $this->data['error_warning'] = '';
			    }

			    $this->data['action'] = makeUrl('checkout/cart', array(), true, true);

			    $this->load->model('tool/seo_url');
			    $this->load->model('tool/image');

			    $this->data['products'] = array();
			    $cart_qty = 0;

			    //d($this->cart->getProducts());
			    foreach ($this->cart->getProducts() as $result) {
				$option_data = array();
				$detail_data = array();
				//d($result);

				foreach ($result['option'] as $option) {
				    $option_data[] = array(
					'name' => $option['name'],
					'value' => $option['value']
				    );
				}

				if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				    $image = $result['image'];
				} else {
				    $image = 'no_image.jpg';
				}

				$cart_qty += $result['quantity'];

				$this->data['products'][] = array(
				    'key' => $result['key'],
				    'name' => $result['name'],
				    'model' => $result['model'],
				    'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
				    'option' => $option_data,
				    'stock_status_id' => $result['stock_status_id'],
				    'quantity' => $result['quantity'],
				    'stock' => $result['stock'],
				    'product_type_id' => $result['product_type_id'],
				    'price' => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				    'total' => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
				    'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
				    'meta_link' => QS::getMetaLink($result['meta_link'], $result['name']),
				    'alt_title' => QS::getMetaLink($result['img_alt'], $result['name']),
				    'delete' => makeUrl('checkout/cart', array('remove=' . $result['key']), true, true),
				    'wishlist' => makeUrl('account/wishlist', array(), true) . '&product_id=' . $result['product_id'],
				);
			    }

			    //d(array($cart_qty,sprintf($this->language->get('text_you_have'),$cart_qty)));

			    $this->data['text_you_have'] = sprintf($this->language->get('text_you_have'), $cart_qty);

			    $this->data['sub_total'] = $this->currency->format($this->cart->getTotal());

			    if (isset($this->session->data['redirect_continue'])) {
				$this->data['continue'] = $this->model_tool_seo_url->rewrite(str_replace('&amp;', '&', $this->session->data['redirect_continue']));

				//unset($this->session->data['redirect_continue']);
			    } else if (isset($this->session->data['redirect'])) {
				$this->data['continue'] = $this->model_tool_seo_url->rewrite(str_replace('&amp;', '&', $this->session->data['redirect']));

				//unset($this->session->data['redirect']);
			    } else {
				$this->data['continue'] = makeUrl('common/home', array(), true);
			    }

			    $this->data['checkout'] = makeUrl('checkout/confirm', array(), true);
			} else {
			    $this->data['heading_title'] = __('Shopping Cart is Empty');
			    $this->data['text_message'] = __('You have no items in your shopping cart');
			    $this->data['button_continue'] = __("Continue Shopping");
			    $this->data['continue'] = makeUrl('common/home', array(), true);
			}
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
			    $this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
			} else {
			    $this->template = 'default/template/checkout/cart.tpl';
			}
			$this->response->setOutput($this->render(), $this->config->get('config_compression'));
		}

	
    }

}

?>