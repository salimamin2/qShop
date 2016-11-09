<?php

class ControllerProductManufacturer extends Controller {

    public function index() {
        $this->language->load('product/manufacturer');

        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/seo_url');
        $this->load->model('tool/image');

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->loadKnow = false;

        if (isset($this->request->get['manufacturer_id'])) {
            $manufacturer_id = $this->request->get['manufacturer_id'];
        } else {
            $manufacturer_id = 0;
        }
        //getting cartegory id.
        $category_id=0;
        // if(!isset($this->request->get['ignore_cat'])){
        //     if(isset($this->session->data['department_id'])){
        //         $category_id = $this->session->data['department_id'];
        //     }
        // }
        $manufacturer_info = Make::a('catalog/manufacturer')->create()->getManufacturer($manufacturer_id,$category_id);
        if ($manufacturer_info) {
            $this->document->breadcrumbs[] = array(
                'href' => makeUrl('product/manufacturer',array('manufacturer_id=' . $this->request->get['manufacturer_id']),true),
                'text' => html_entity_decode($manufacturer_info['name']),
                'email' => html_entity_decode($manufacturer_info['email']),
                'separator' => false
            );

            $this->data['manufacturer'] = $manufacturer_info;
			
			$this->session->data['redirect_continue'] = makeUrl('product/manufacturer',array('manufacturer_id=' . $this->request->get['manufacturer_id']),true);

            $this->document->title = html_entity_decode((isset($manufacturer_info['meta_title']) && $manufacturer_info['meta_title'] != '') ? $manufacturer_info['meta_title'] : $manufacturer_info['name']);

            $this->data['heading_title'] = html_entity_decode($manufacturer_info['name']);
            $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
            $this->data['action'] = makeUrl('checkout/cart', array(), true);

            $this->data['text_sort'] = $this->language->get('text_sort');
            $this->data['text_model'] = $this->language->get('text_model');
        
            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'pd.name';
            }

            if (isset($this->request->get['order'])) {
                $order = $this->request->get['order'];
            } else {
                $order = 'ASC';
            }
            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = $this->config->get('config_catalog_limit');
            }

            $start = ($page - 1) * $limit;
            $aProducts = Make::a('catalog/product')->create()->getProductsByManufacturerId($manufacturer_id, $sort, $order, $start, $limit,$category_id);
                        
            $this->data['products'] = array();
            $this->data['pagination'] = array();
            $this->data['manufacturer_id']=$manufacturer_id;
            $product_total = isset($aProducts[0]) ? $aProducts[0] : 0;

            if ($product_total) {
                $results = isset($aProducts[1]) ? $aProducts[1] : array();
                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $result['image'];
                    } else {
                        $image = 'no_image.jpg';
                    }

                    $rating = Make::a('catalog/review')->create()->getAverageRating($result['product_id']);

                    $special = FALSE;

                    $discount = Make::a('catalog/product')->create()->getProductDiscount($result['product_id']);

                    if ($discount) {
                        $price = $this->currency->format($this->tax->calculate($discount['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                        $special = Make::a('catalog/product')->create()->getProductSpecial($result['product_id']);

                        if ($special) {
                            $special = $this->currency->format($this->tax->calculate($special['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                        }
                    }
                    $this->data['products'][] = array(
                        'id' => $result['product_id'],
                        'name' => html_entity_decode($result['name']),
                        'model' => html_entity_decode($result['model']),
                        'rating' => $rating,
                        'stars' => sprintf($this->language->get('text_stars'), $rating),
                        'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                        'price' => $price,
                        'special' => $special,
                        'options' => Make::a('catalog/product')->create()->getProductOptions($result['product_id']),
                        'wishlist' => makeUrl('account/wishlist', array(), true) . '&amp;product_id=' . $result['product_id'],
                        'wishlist_id' => (isset($result['wishlist_id']) ? $result['wishlist_id'] : ''),
                        'href' => makeUrl('product/product', array('manufacturer_id=' . $this->request->get['manufacturer_id'], 'product_id=' . $result['product_id']), true)
                    );
                }
                // d($this->data['products']);
               
                if (!$this->config->get('config_customer_price')) {
                    $this->data['display_price'] = TRUE;
                } elseif ($this->customer->isLogged()) {
                    $this->data['display_price'] = TRUE;
                } else {
                    $this->data['display_price'] = FALSE;
                }

                $url = '';

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                $this->data['sorts'] = array();

                $this->data['sorts'][] = array(
                    'text' => $this->language->get('text_name_asc'),
                    'value' => 'pd.name-ASC',
                    'href' => makeUrl('product/manufacturer',array('manufacturer_id=' . $manufacturer_id,'sort=pd.name','order=ASC'),true)
                );

                $this->data['sorts'][] = array(
                    'text' => $this->language->get('text_name_desc'),
                    'value' => 'pd.name-DESC',
                    'href' => makeUrl('product/manufacturer',array('manufacturer_id=' . $manufacturer_id,'sort=pd.name','order=DESC'),true)
                );

                $this->data['sorts'][] = array(
                    'text' => $this->language->get('text_price_asc'),
                    'value' => 'p.price-ASC',
                    'href' => makeUrl('product/manufacturer',array('manufacturer_id=' . $manufacturer_id,'sort=p.price','order=ASC'),true)
                );

                $this->data['sorts'][] = array(
                    'text' => $this->language->get('text_price_desc'),
                    'value' => 'p.price-DESC',
                    'href' => makeUrl('product/manufacturer',array('manufacturer_id=' . $manufacturer_id,'sort=p.price','order=DESC'),true)
                );

                $url = '';
                $aUrl = array('manufacturer_id=' . $this->request->get['manufacturer_id']);

                if (isset($this->request->get['sort'])) {
                    $aUrl[] = 'sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $aUrl[] = 'order=' . $this->request->get['order'];
                }

                $aUrl[] = 'page={page}';

                $pagination = new Pagination();
                $pagination->total = $product_total;
                $pagination->page = $page;
                $pagination->limit = $limit;
                $pagination->text = $this->language->get('text_pagination');
                $pagination->url = makeUrl('product/manufacturer', $aUrl, true);

                $this->data['pagination'] = $pagination->render();
            }

            $this->data['sort'] = $sort;
            $this->data['order'] = $order;

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/product/manufacturer.tpl';
            } else {
                $this->template = 'default/template/product/manufacturer.tpl';
            }


            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        } else {
            $url = '';
            $aUrl = array('manufacturer_id=' . $manufacturer_id);

            if (isset($this->request->get['sort'])) {
                $aUrl[] = 'sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $aUrl[] = 'order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $aUrl[] = 'page=' . $this->request->get['page'];
            }

            $this->document->breadcrumbs[] = array(
                'href' => makeUrl('product/manufacturer', $aUrl),
                'text' => $this->language->get('text_error'),
                'separator' => false
            );

            $this->document->title = $this->language->get('text_error');

            $this->data['heading_title'] = $this->language->get('text_error');

            $this->data['text_error'] = $this->language->get('text_error');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['continue'] = makeUrl('common/home');


            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->template = 'default/template/error/not_found.tpl';
            }

            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        }
    }

}

?>