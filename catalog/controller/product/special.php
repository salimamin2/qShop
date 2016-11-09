<?php

class ControllerProductSpecial extends Controller {

    public function index() {
        $this->language->load('product/special');
        $this->document->layout_col = "col2-left-layout";
        $this->document->title = $this->language->get('heading_title');

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );

//	$url = '';
        $aUrl = array();

        if (isset($this->request->get['sort'])) {
            $aUrl['sort'] = 'sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $aUrl['order'] = 'order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $aUrl['limit'] = 'limit=' . $this->request->get['limit'];
        }

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('product/special', $aUrl, true),
            'text' => $this->language->get('heading_title'),
            'separator' => false
        );

        $this->session->data['redirect_continue'] = makeUrl('product/special', $aUrl, true);

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_sort'] = $this->language->get('text_sort');
        $this->data['text_model'] = $this->language->get('text_model');

        $this->data['text_error_product'] = $this->language->get('text_empty');

        $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');

        $this->data['action'] = HTTP_SERVER . 'checkout/cart';

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

//	$product_total = Make::a('catalog/product')->create()->getTotalProductSpecials();
        $this->data['filters'] = array();
        $this->data['post_filters'] = array();
        $oModel = Make::a('catalog/product')->create()->getProductSpecials();

        if ($this->config->get('product_filter_status')) {
            $aFilters = unserialize($this->config->get('product_filter'));

            $aPostFilters = array();
            if (isset($this->request->get['filter']) && $this->request->get['filter']) {

                $aPostFilters = $this->request->get['filter'];
                $this->data['post_filters'] = $aPostFilters;

                foreach ($this->request->get['filter'] as $key => $filter) {
                    if (is_array($filter)) {
                        foreach ($filter as $i => $f) {
//			    $url .= '&filter[' . $key . '][' . $i . ']=' . $f;
                            $aUrl['filter[' . $key . '][' . $i . ']'] = 'filter[' . $key . '][' . $i . ']=' . $f;
                        }
                    } else {
//			    $url .= '&filter[' . $key . ']=' . $f;
                        $aUrl['filter[' . $key . ']'] = 'filter[' . $key . ']=' . $f;
                    }
                }
            }

            foreach ($aFilters as $i => $aFilter) {
                $sClass = 'Filter' . strtocamel(str_replace('_', '', $aFilter['field']));
                //$aFilter['category_id'] = $category_id;

                $aPost = array();
                $oFilter = new $sClass($oModel, $aFilter);

                    if (isset($aPostFilters[$aFilter['field']]) && $aPostFilters[$aFilter['field']]) {
                        $aPost = $aPostFilters[$aFilter['field']];
                        $oModel = $oFilter->init($aPost);
                    }

                $this->data['filters'][$i] = $aFilter;

                $this->data['filters'][$i]['select'] = $oFilter->select;
            }
        }

        foreach ($aFilters as $i => $aFilter) {
            $sClass = 'Filter' . strtocamel(str_replace('_', '', $aFilter['field']));

            $aPost = array();
            $oFilter = new $sClass($oModel, $aFilter);

            if ($aFilter['value_defined'] == 0) {
                $aValues = $oFilter->getValues($oModel);
            }
            $this->data['filters'][$i]['values'] = $aValues;
        }

        $oOrm = clone $oModel;

        $start = ($page - 1) * $limit;
        if ($start < 0) {
            $start = 0;
        }

        $results = $oModel->order_by($sort, $order)
                ->offset($start)
                ->limit((int) $limit)
                ->find_many(true);

        $product_total = count($oOrm->find_many(true));

        $this->data['filter_action'] = makeUrl('product/special', $aUrl, true);
        $this->data['pageLink'] = makeUrl('product/special', array(), true);

        //if ($results) {
//    if(true) {
//	    $this->load->model('tool/seo_url');
        $this->load->model('tool/image');

        $this->data['products'] = array();

        foreach ($results as $result) {
            if (isset($result['thumb']) && $result['thumb'] && file_exists(DIR_IMAGE . $result['thumb'])) {
                $thumb = $result['thumb'];
            } elseif (isset($result['image']) && $result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                $thumb = $result['image'];
            } else {
                $thumb = 'no_image.jpg';
            }

            $rating = Make::a('catalog/review')->create()->getAverageRating($result['product_id']);
            $extraImages = Make::a('catalog/product')->create()->getProductImages($result['product_id']);
            $extra_img = '';
            if (!empty($extraImages) && file_exists(DIR_IMAGE . $extraImages[0]['image'])) {
                $extra_img = $extraImages[0]['image'];
            }
            $special = FALSE;
            $aSpecial = Make::a('catalog/product')->create()->getProductSpecial($result['product_id']);
            $discount = Make::a('catalog/product')->create()->getProductDiscount($result['product_id']);
            $special_percent = FALSE;
            /*
             * check if special or discount is define else show normal price
             */

            if ($aSpecial || $discount) {
                /*
                 * if only special is define show special
                 */
                if ($aSpecial && !$discount) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $product_tax_id, $this->config->get('config_tax')));
                    $special_percent = ceil((($result['price'] - $aSpecial['price']) / $product_price) * 100);
                    $special = $this->currency->format($this->tax->calculate($aSpecial['price'], $product_tax_id, $this->config->get('config_tax')));
                }
                /*
                 * if only discount is define show discount
                 */
                if (!$aSpecial && $discount) {
                    $price = $this->currency->format($this->tax->calculate($discount['price'], $product_tax_id, $this->config->get('config_tax')));
                    $special = FALSE;
                }
                /*
                 * if special and discount are both define and
                 * special priority is less than discount priority
                 * or  special priority is 1.
                 * Then show special else show discount.
                 */
                if ($aSpecial && $discount && ($aSpecial['priority'] < $discount['priority'] || $aSpecial['priority'] == 1)) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $product_tax_id, $this->config->get('config_tax')));
                    $special_percent = ceil((($result['price'] - $aSpecial['price']) / $product_price) * 100);
                    $special = $this->currency->format($this->tax->calculate($aSpecial['price'], $product_tax_id, $this->config->get('config_tax')));
                } else if ($discount) {
                    $price = $this->currency->format($this->tax->calculate($discount['price'], $product_tax_id, $this->config->get('config_tax')));
                    $special = FALSE;
                }
            } else {
                $price = $this->currency->format($this->tax->calculate($result['price'], $product_tax_id, $this->config->get('config_tax')));
            }

            $this->data['products'][] = array(
                'id' => $result['product_id'],
                'name' => $result['name'],
                'product_id' => $result['product_id'],
                'model' => $result['model'],
                'manufacturer' => $result['manufacturer'],
                'rating' => $rating,
                'stars' => sprintf($this->language->get('text_stars'), $rating),
                'thumb' => $this->model_tool_image->resize($thumb, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                'extra_img' => $this->model_tool_image->resize($extra_img, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                'price' => $price,
                'special' => $special,
                'percent' => $special_percent,
                'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
                'meta_link' => QS::getMetaLink($result['meta_link'], $result['name']),
                'alt_title' => QS::getMetaLink($result['img_alt'], $result['name']),
                'options' => Make::a('catalog/product')->create()->getProductOptions($result['product_id']),
                'wishlist' => makeUrl('account/wishlist', array(), true) . '&product_id=' . $result['product_id'],
            );
        }

        if (!$this->config->get('config_customer_price')) {
            $this->data['display_price'] = TRUE;
        } elseif ($this->customer->isLogged()) {
            $this->data['display_price'] = TRUE;
        } else {
            $this->data['display_price'] = FALSE;
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = null;
        $pagination->url = makeUrl('product/special', $aUrl, true) . '&page={page}';
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

        $this->data['total_amount'] = $product_total . "Item(s)";
        $this->data['sort_by'] = array('pd.name' => 'name', 'pi.price' => 'price');
        $this->data['limit_by'] = array(4, 8, 12, 24, 36, 48);
        $this->data['sort_type'] = ($order == "ASC" ? "category-asc" : "category-desc");

        $aSortArray = $aUrl;
        unset($aSortArray['sort']);

        $aOrderArray = $aUrl;
        unset($aOrderArray['order']);

        $aLimitArray = $aUrl;
        unset($aLimitArray['limit']);

        $this->data['sort_url'] = makeUrl('product/special', $aSortArray, true);
        $this->data['order_url'] = makeUrl('product/special', $aOrderArray, true);
        $this->data['limit_url'] = makeUrl('product/special', $aLimitArray, true);
        $this->data['limit'] = $limit;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/special.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/product/special.tpl';
        } else {
            $this->template = 'default/template/product/special.tpl';
        }

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>