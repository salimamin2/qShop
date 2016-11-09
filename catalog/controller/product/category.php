<?php

class ControllerProductCategory extends Controller {

    public function index() {
        $this->language->load('product/category');
        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home', array(), true),
            'text' => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['currency_symbol'] = $this->currency->getSymbol();

        $this->data['text_show_filter'] = $this->language->get('text_show_filter');
        $this->data['text_hide_filter'] = $this->language->get('text_hide_filter');
        $this->data['text_filter_products'] = $this->language->get('text_filter_products');
        $this->data['text_filter_price_range'] = $this->language->get('text_filter_price_range');
        $this->data['text_filter_color'] = $this->language->get('text_filter_color');
        $this->data['text_filter_rating'] = $this->language->get('text_filter_rating');
        $this->data['text_tooltip'] = $this->language->get('text_tooltip');

        $this->data['text_error_category'] = $this->language->get('text_error_category');
        $this->data['text_error_product'] = $this->language->get('text_error_product');

        $this->data['button_filter_products'] = $this->language->get('button_filter_products');
        $this->data['button_filter_reset'] = $this->language->get('button_filter_reset');



        $this->load->model('tool/seo_url');
        if (isset($this->request->get['path'])) {
            $path = '';
            $parts = explode('_', $this->request->get['path']);
            $this->session->data['department_id'] = $parts[0];
            $count = count($parts);
            foreach ($parts as $i => $path_id) {
                $category_info = Make::a('catalog/category')->create()->getCategory($path_id);

                if ($category_info) {
                    if (!$path) {
                        $path = $path_id;
                    } else {
                        $path .= '_' . $path_id;
                    }
                    $this->document->breadcrumbs[] = array(
                        'href' => makeUrl('product/category', array('path=' . $path), true),
                        'text' => html_entity_decode($category_info->name),
                        'separator' => ($i == $count - 1 ? FALSE : $this->language->get('text_separator'))
                    );
                }
            }
            $parent_id = $parts[0];
            $category_id = array_pop($parts);
        } else {
            $category_id = 0;
        }
        $this->session->data['redirect_continue'] = makeUrl('product/category', array('path=' . $path), true);

        $category_info = Make::a('catalog/category')->create()->getCategory($category_id);
       // d($category_info);
        if ($category_info) {
            $ref_code = Make::a('catalog/category')->create()->getCategory(($category_info->parent_id) ? $category_info->parent_id : $category_info->category_id);
            $this->document->layout_col = "col2-left-layout category-".$ref_code->ref_category_code;
            $this->document->title = (isset($category_info->meta_title) && $category_info->meta_title != '') ? $category_info->meta_title : html_entity_decode($category_info->name);
            $this->document->description = $category_info->meta_description;
            $this->document->keywords = $category_info->meta_keywords;
            $this->data['heading_title'] = html_entity_decode($category_info->name);
            $this->data['meta_link'] = QS::getMetaLink($category_info->meta_link, $category_info->name, $category_info->name);
            $this->data['alt_title'] = QS::getMetaLink($category_info->img_alt, $category_info->name, $category_info->name);
            $this->data['parent_id'] = ($category_info->parent_id) ? $category_info->parent_id : $category_info->category_id;
            $this->data['category_id'] = $category_info->category_id;
            $this->data['description'] = html_entity_decode($category_info->description, ENT_QUOTES, 'UTF-8');
            $ref_code = Make::a('catalog/category')->create()->getCategory($this->data['parent_id']);
            $this->data['ref_category_code'] = $ref_code->ref_category_code;
            $this->data['text_sort'] = $this->language->get('text_sort');
            $this->data['text_items'] = $this->language->get('text_items');
            $this->data['text_model'] = $this->language->get('text_model');
            $this->data['text_regular_price'] = $this->language->get('text_regular_price');
            $this->data['text_special_price'] = $this->language->get('text_special_price');
            $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
            $this->data['action'] = makeUrl('checkout/cart', array(), true);
            $this->data['stock_status'] = $product_info['stock'];
            $this->data['blog_page'] = makeUrl('blog/blog',array(),true);

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'p.sort_order';
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

            $this->data['open_filter'] = 'false';
            if (isset($this->request->get['open_filter'])) {
                $this->data['open_filter'] = 'true';
            }

            $aUrl = array();
            $aUrl['path'] = 'path=' . $path;
            if (isset($this->request->get['sort'])) {
                $aUrl['sort'] = 'sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $aUrl['order'] = 'order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $aUrl['limit'] = 'limit=' . $this->request->get['limit'];
            }

//        if (isset($this->request->get['page'])) {
//            $aUrl['page'] = 'page=' . $this->request->get['page'];
//        }
            $aFilterUrl = $aUrl;
            $this->load->model('tool/image');
            $oCategory = Make::a('catalog/category')->create();
            $category_total = $oCategory->getTotalCategoriesByCategoryId($parent_id, $special);
            $aCategories = $oCategory->getChildCategoriesByParent($parent_id);
            $aDesigners = CHtml::listData(Make::a('catalog/manufacturer')->create()->getAllManufacturers(),'manufacturer_id','name');
            foreach($aCategories as $id => $Cat) {
                $bSel = false;
                if(!empty($Cat['child'])){
                   $bSel = in_array($category_id,array_keys($Cat['child']));
                }
                $this->data['aCategories'][$id] = array(
                    'name' => $Cat['name'],
                    'selected' => ($id == $category_id),
                    'child_selected' => $bSel,
                    'href' => makeUrl('product/category',array('path='.$parent_id . '_' . $id,'open_filter=true'),true)
                );
                foreach($Cat['child'] as $cId => $cCat) {
                    $this->data['aCategories'][$id]['child'][$cId] = array(
                        'name' => $cCat,
                        'selected' => ($cId == $category_id),
                        'href' => makeUrl('product/category',array('path='.$parent_id . '_' . $id . '_' . $cId,'open_filter=true'),true)
                    );
                }
            }
            $oModel = Make::a('catalog/product')->create()->getProductsByCategoryId($category_id, $special);
            $this->data['filters'] = array();
            $this->data['post_filters'] = array();

            if ($this->config->get('product_filter_status')) {
                $aFilters = unserialize($this->config->get('product_filter'));
                usort($aFilters, function($a, $b) {
                    return $a['sort_order'] - $b['sort_order'];
                });
                $aPostFilters = array();
                if (isset($this->request->get['filter']) && $this->request->get['filter']) {

                    $aPostFilters = $this->request->get['filter'];
                    $aViewFilters = array();
                    foreach ($aPostFilters as $key => $filter) {
                        $aViewFilters[$key] = array();
                        if (is_array($filter)) {
                            foreach ($filter as $i => $f) {
                                $aViewFilters[$key][$i] = array('value' => $f,'name' => ucfirst($f));
                                $aUrl['filter[' . $key . '][' . $i . ']'] = 'filter[' . $key . '][' . $i . ']=' . $f;
                                if($key == 'manufacturer')
                                    $aViewFilters[$key][$i]['name'] = $aDesigners[$f];
                            }
                        } else {
                            $aViewFilters[$key]['value'] = $filter;
                            $aUrl['filter[' . $key . ']'] = 'filter[' . $key . ']=' . $filter;
                        }
                    }
                    $this->data['post_filters'] = $aViewFilters;
                    $this->data['org_post_filters'] = $aPostFilters;
                }
                $oFModel = clone $oModel;
                $aClause = array();
                foreach ($aFilters as $i => $aFilter) {
                    $sClass = 'Filter' . strtocamel(str_replace('_', '', $aFilter['field']));
                    // $aFilter['category_id'] = $category_id;

                    $aPost = array();

                    // $oFilter = new $sClass($oModel, $aFilter);
                    $oFilter = new $sClass($oFModel,$aFilter);
                    if (isset($aPostFilters[$aFilter['field']]) && $aPostFilters[$aFilter['field']]) {
                        // $oFModel = $oFilter->init($aPost);
                        $aPost = $aPostFilters[$aFilter['field']];
                        $aClause[] = $oFilter->init($aPost);
                        
                    }

                    $this->data['filters'][$i] = $aFilter;
                    $this->data['filters'][$i]['select'] = $oFilter->select;

                }
                foreach ($aFilters as $i => $aFilter) {
                    $sClass = 'Filter' . strtocamel(str_replace('_', '', $aFilter['field']));
                    $aFilter['category_id'] = $category_id;
//d($sClass);
                    $aPost = array();
                    $oFilter = new $sClass($oModel, $aFilter);
                    // $oFilter = new $sClass($oFModel,$aFilter);
                    $aFilter['selected'] = true;
                    if ($aFilter['value_defined'] == 0) {
                        // $oModel->where_raw(implode(' OR ',$aClause));
                        $aValues = $oFilter->getValues($oModel);
                        // $aValues = $oFilter->getValues($oFModel);
                    }

                    $this->data['filters'][$i]['values'] = $aValues;
                }
            }

            if($aClause) {
                $oFModel = $oFModel->where_raw('(' . implode(' AND ',$aClause) . ')');
            }
            // $oOrm = clone $oModel;
            $oOrm = clone $oFModel;

            $start = ($page - 1) * $limit;
            if ($start < 0) {
                $start = 0;
            }
            $oFModel = $oFModel->order_by($sort, $order)
                    ->offset($start)
                    ->limit((int) $limit);
            //$sort, $order, ($page - 1) * $this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit')
            //d($aProducts);
            //d($oModel);
            $product_total = count($oOrm->find_many(true));

            //$aUrl = array('path=' . $path);
//	    if ($url != '') {
//		$aUrl = array_merge($aUrl, explode('&', $url));
//	    }
            //d(ORM::get_last_query());

            $this->data['filter_action'] = makeUrl('product/category', $aFilterUrl, true);
//        unset($aUrl['page']);
            $parent_name = Make::a('catalog/category')->create()->getCategoryTitle($parent_id);
            $this->data['product_total'] = $product_total;
            if ($category_info->image) {
                $this->data['category_image'] = $this->model_tool_image->resize($category_info->image, 771, 389);
            } else {
                $this->data['category_image'] = '';
            }
            $this->data['alt_title'] = metaLink($category_info->img_alt);
            $this->data['pageLink'] = makeUrl('product/category', array('path=' . $path), true);
            $this->data['text_error'] = $this->language->get('text_empty');

            $this->data['categories'] = array();
            if ($category_total) {
                $results = Make::a('catalog/category')->create()->getCategories($category_id);
                foreach ($results as $result) {
                    if (!in_array($result['category_id'], unserialize($this->config->get('config_category_left_menu')))) {
                        continue;
                    }
                    if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                        $image = $result['image'];
                    } else {
                        $image = 'no_image.jpg';
                    }

                    $aChildUrl = $aUrl;
                    $aChildUrl['path'] = 'path=' . $this->request->get['path'] . '_' . $result['category_id'];
                    $total_products = 1;
                    if ($total_products != 0) {
                        $this->data['categories'][] = array(
                            'name' => $result['name'],
                            'total_products' => 0,
                            'title' => QS::getMetaLink($result['meta_link'], $result['name'], $result['name']),
                            'href' => makeUrl('product/category', $aChildUrl, true),
                            'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
                        );
                    }
                }
            }


            $this->data['products'] = array();
            if ($product_total) {

                // $results = $oModel->find_many(true);
                $results = $oFModel->find_many(true);
//            d($oModel->find_many(true));
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

                    if (isset($result['product_type_id']) && $result['product_type_id'] == PRODUCT_TYPE_GROUP) {
                        $grouped_product = Make::a('catalog/product')->create()->getDefaultGroupProduct($result['product_id']);

                        $product_id = $grouped_product['product_id'];
                        $product_price = $grouped_product['price'];
                        $product_tax_id = $grouped_product['tax_class_id'];
                    } else {
                        $product_id = $result['product_id'];
                        $product_price = $result['price'];
                        $product_tax_id = $result['tax_class_id'];
                    }

                    $rating = Make::a('catalog/review')->create()->getAverageRating($product_id);
                    $special = FALSE;
                    $aSpecial = Make::a('catalog/product')->create()->getProductSpecial($product_id);
                    $discount = Make::a('catalog/product')->create()->getProductDiscount($product_id);
                    $special_percent = FALSE;
                    /*
                     * check if special or discount is define else show normal price
                     */

                    if ($aSpecial || $discount) {
                        /*
                         * if only special is define show special
                         */
                        if ($aSpecial && !$discount) {
                            $price = $this->currency->format($this->tax->calculate($product_price, $product_tax_id, $this->config->get('config_tax')));
                            $special_percent = ceil((($product_price - $aSpecial['price']) / $product_price) * 100);
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
                            $price = $this->currency->format($this->tax->calculate($product_price, $product_tax_id, $this->config->get('config_tax')));
                            $special_percent = ceil((($product_price - $aSpecial['price']) / $product_price) * 100);
                            $special = $this->currency->format($this->tax->calculate($aSpecial['price'], $product_tax_id, $this->config->get('config_tax')));
                        } else if ($discount) {
                            $price = $this->currency->format($this->tax->calculate($discount['price'], $product_tax_id, $this->config->get('config_tax')));
                            $special = FALSE;
                        }
                    } else {
                        $price = $this->currency->format($this->tax->calculate($product_price, $product_tax_id, $this->config->get('config_tax')));
                    }

                    if ($result['quantity'] <= 0) {
                        if ($this->config->get('config_stock_display')) {
                            $stock = $this->language->get('text_outstock');
                        } else {
                        }
                        } else {
                        if ($this->config->get('config_stock_display')) {
                            $stock = $this->language->get('text_instock');
                        } else {
                        }
                    }


                    $aProductUrl = $aUrl;
                    $aProductUrl['product'] = 'product_id=' . $result['product_id'];

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
                        'href' => makeUrl('product/product', $aProductUrl, true),
                        'meta_link' => QS::getMetaLink($result['meta_link'], $result['name'], $category_info->name),
                        'alt_title' => QS::getMetaLink($result['img_alt'], $result['name'], $category_info->name),
                        'options' => Make::a('catalog/product')->create()->getProductOptions($result['product_id']),
                        'wishlist' => makeUrl('account/wishlist', array(), true) . '&amp;product_id=' . $result['product_id'],
                        'wishlist_id' => (isset($result['wishlist_id']) ? $result['wishlist_id'] : ''),
                        'stock' => $stock,
                        'quantity' => $result['quantity'],
                    );
                }
            }
           //d($this->data['products']);
            if (!$this->config->get('config_customer_price')) {
                $this->data['display_price'] = TRUE;
            } elseif ($this->customer->isLogged()) {
                $this->data['display_price'] = TRUE;
            } else {
                $this->data['display_price'] = FALSE;
            }

           
            $price_range = Make::a('catalog/category')->create()->getPriceRange($category_id);
            $this->data['filter']['price_range'] = $price_range;

            $result_colors = Make::a('catalog/category')->create()->getCategories($this->config->get('config_category_color'));
            $this->data['filter']['colors'] = array();
            foreach ($result_colors as $result) {
                $this->data['filter']['colors'][] = array(
                    'category_id' => $result['category_id'],
                    'name' => $result['name'],
                    'color_code' => strtolower($result['name']),
                    'ref_category_code' => $result['ref_category_code']
                );
            }

            $this->data['filter']['ratings']['high'] = array('text' => $this->language->get('text_rating_desc'), 'value' => 'rating-DESC', 'href' => makeUrl('product/category', array('path=' . $this->request->get['path'], 'sort=rating', 'order=DESC'), true, true));
            $this->data['filter']['ratings']['low'] = array('text' => $this->language->get('text_rating_asc'), 'value' => 'rating-ASC', 'href' => makeUrl('product/category', array('path=' . $this->request->get['path'], 'sort=rating', 'order=ASC'), true, true));

            $this->data['parent_name'] = $parent_name['name'];
            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit; //$this->config->get('config_catalog_limit');
            $pagination->text = null;
            $pagination->url = makeUrl('product/category', $aUrl, true) . '&amp;page={page}';
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
            
            $aParams = $aUrl;
            foreach($aParams as $i => $val) {
                if(strpos($val,'sort') !== false || strpos($val,'order') !== false)
                    unset($aParams[$i]);
            }

            $this->data['sorts'] = array();

            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => makeUrl('product/category',array_merge($aParams,array('sort=pd.name','order=ASC')),true)
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => makeUrl('product/category',array_merge($aParams,array('sort=pd.name','order=DESC')),true)
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => makeUrl('product/category',array_merge($aParams,array('sort=p.price','order=ASC')),true)
            );
            $this->data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => makeUrl('product/category',array_merge($aParams,array('sort=p.price','order=DESC')),true)
            );
            // $this->data['limit_by'] = array(4, 8, 12, 24, 36, 48);
            // $this->data['sort_type'] = ($order == "ASC" ? "category-asc" : "category-desc");

            $aSortArray = $aUrl;
            unset($aSortArray['sort']);

            $aOrderArray = $aUrl;
            unset($aOrderArray['order']);

            $aLimitArray = $aUrl;
            unset($aLimitArray['limit']);

            $this->data['sort_url'] = makeUrl('product/category', $aSortArray, true);
            $this->data['order_url'] = makeUrl('product/category', $aOrderArray, true);
            $this->data['limit_url'] = makeUrl('product/category', $aLimitArray, true);
            $this->data['limit'] = $limit;
            $this->data['sort'] = $sort;
            $this->data['order'] = $order;

            $this->data['breadcrumbs'] = $this->document->breadcrumbs;

            if (!$categories || isset($this->request->post['filter'])) {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/product/category.tpl';
                } else {
                    $this->template = 'default/template/product/category.tpl';
                }
            } else {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category_list.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/product/category_list.tpl';
                } else {
                    $this->template = 'default/template/product/category_list.tpl';
                }
            }
            $this->document->loadKnow = false;
//d($this->data['filters']);
            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        } else {
            $this->document->title = $this->language->get('heading_title');
            $this->document->breadcrumbs = array();
            $this->document->breadcrumbs[] = array(
                'href' => HTTP_SERVER . 'common/home',
                'text' => $this->language->get('text_home'),
                'separator' => $this->language->get('text_separator')
            );

            if (isset($this->request->get['act'])) {
                $this->document->breadcrumbs[] = array(
                    'href' => HTTP_SERVER . '' . $this->request->get['act'],
                    'text' => __('Not Found'),
                    'separator' => FALSE
                );
            }
            $this->data['image'] = HTTP_IMAGE . "404.jpg";
            $this->data['heading_title'] = $this->language->get('heading_title');
            $this->data['home'] = makeUrl('common/home', array(), true);
            $this->data['account'] = makeUrl('account/account', array(), true, true);

            $this->data['text_error'] = $this->language->get('text_error');

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