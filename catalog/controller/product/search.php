<?php

class ControllerProductSearch extends Controller {

    public function index() {
        //d($this->request->get);


        $keyWord=$this->request->get['term'];
        if($keyWord!=''){
            $this->load->model('catalog/information');

            $aResults['options']['Mresult']=$this->model_catalog_information->getManufacturerSearchResult($keyWord);
          //  d($aResults);
            foreach($aResults['options']['Mresult'] as $i=>$val){
                if($val['value']=='designer'){
                    $value='manufacturer';
                    $id='manufacturer_id';
                }
                else if($val['value']==''){
                    $value='product';
                    $id='product_id';
                }
                else if($val['value']=='category'){
                    $value='category';
                    $id='path';
                }
                $link="<a href='".makeUrl('product/'.$value, array($id.'=' . $val['id']), true)."'>Link</a>";
                $result['all_options'][]=array(
                    'id'=>$val['id'],
                    'category'=>strtoupper($val['value']),
                    'label'=> html_entity_decode($val['label']),
                    'value'=> html_entity_decode($val['label']),
                    'href'=> makeUrl('product/'.$value,array($id.'=' . $val['id']), true),
                );
            }

            echo json_encode($result['all_options']);

        }


        $this->language->load('product/search');

        $this->document->title = $this->language->get('heading_title');

        $this->document->breadcrumbs = array();
        $this->document->layout_col = "col2-left-layout";

        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['action'] = HTTP_SERVER . 'checkout/cart';

        $url = '';
        if (isset($this->request->get['keyword'])) {
            $url .= '&keyword=' . urlencode($this->request->get['keyword']);
        }

        if (isset($this->request->get['category_id'])) {
            $url .= '&category_id=' . $this->request->get['category_id'];
        }

        if (isset($this->request->get['description'])) {
            $url .= '&description=' . $this->request->get['description'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_critea'] = $this->language->get('text_critea');
        $this->data['text_search'] = $this->language->get('text_search');
        $this->data['text_keyword'] = $this->language->get('text_keyword');
        $this->data['text_category'] = $this->language->get('text_category');
        $this->data['text_empty'] = $this->language->get('text_empty');
        $this->data['text_sort'] = $this->language->get('text_sort');
        $this->data['text_model'] = $this->language->get('text_model');

        $this->data['entry_search'] = $this->language->get('entry_search');
        $this->data['entry_description'] = $this->language->get('entry_description');

        $this->data['button_search'] = $this->language->get('button_search');

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

        if (isset($this->request->get['keyword'])) {
            $this->data['keyword'] = urlencode($this->request->get['keyword']);
        } else {
            $this->data['keyword'] = '';
        }

        if (isset($this->request->get['category_id'])) {
            $this->data['category_id'] = $this->request->get['category_id'];
        } else {
            $this->data['category_id'] = '';
        }

        $this->data['categories'] = array(); //$this->getCategories(0);

        if (isset($this->request->get['description'])) {
            $this->data['description'] = $this->request->get['description'];
        } else {
            $this->data['description'] = '';
        }

        if (isset($this->request->get['keyword'])) {
            $oModel = Make::a('catalog/product')->create()
                    ->getProductsByKeyword($this->request->get['keyword'], 0, 0, isset($this->request->get['category_id']) ? $this->request->get['category_id'] : '', isset($this->request->get['description']) ? $this->request->get['description'] : '', TRUE, $sort, $order, ($page - 1) * $limit, $limit);

            $this->data['filters'] = array();
            $this->data['post_filters'] = array();

            if ($this->config->get('product_filter_status')) {
                $aFilters = unserialize($this->config->get('product_filter'));

                $aPostFilters = array();
                if (isset($this->request->get['filter']) && $this->request->get['filter']) {

                    $aPostFilters = $this->request->get['filter'];
                    $this->data['post_filters'] = $aPostFilters;

                    foreach ($this->request->get['filter'] as $key => $filter) {
                        if (is_array($filter)) {
                            foreach ($filter as $i => $f) {
                                $url .= '&filter[' . $key . '][' . $i . ']=' . $f;
                            }
                        } else {
                            $url .= '&filter[' . $key . ']=' . $f;
                        }
                    }
                }

                foreach ($aFilters as $i => $aFilter) {
                    $sClass = 'Filter' . strtocamel(str_replace('_', '', $aFilter['field']));
                    $aFilter['keyword'] = $this->request->get['keyword'];

                    $aPost = array();
                    $oFilter = new $sClass($oModel, $aFilter);

                    if (isset($aPostFilters[$aFilter['field']]) && $aPostFilters[$aFilter['field']]) {
                        $aPost = $aPostFilters[$aFilter['field']];
                        $oModel = $oFilter->init($aPost);
                    }

                    //d($oModel);

                    $this->data['filters'][$i] = $aFilter;

                    $this->data['filters'][$i]['select'] = $oFilter->select;
                }
                foreach ($aFilters as $i => $aFilter) {
                    $sClass = 'Filter' . strtocamel(str_replace('_', '', $aFilter['field']));
                    $aFilter['keyword'] = $this->request->get['keyword'];

                    $aPost = array();
                    $oFilter = new $sClass($oModel, $aFilter);

                    if ($aFilter['value_defined'] == 0) {
                        $aValues = $oFilter->getValues($oModel);
                    }
                    $this->data['filters'][$i]['values'] = $aValues;
                }
            }

            $aUrl = array();
            if ($url != '') {
                $aUrl = explode('&', $url);
            }

            $this->document->breadcrumbs[] = array(
                'href' => makeUrl('product/search', $aUrl, true),
                'text' => $this->language->get('heading_title'),
                'separator' => false
            );
            $oModel = $oModel->group_by('p.product_id');
            $oOrm = clone $oModel;

            $start = ($page - 1) * $limit;
            if ($start < 0) {
                $start = 0;
            }
//	    $limit = $this->config->get('config_catalog_limit');
            $results = $oModel->order_by($sort, $order)
                    ->offset($start)
                    ->limit((int) $limit)
                    ->find_many(true);


            $product_total = count($oOrm->find_many(true));

            $this->data['filter_action'] = makeUrl('product/search', $aUrl, true);
            $this->data['pageLink'] = makeUrl('product/search', array('keyword=' . urlencode($this->request->get['keyword'])), true);

            $this->load->model('tool/seo_url');
            $this->load->model('tool/image');

            $this->data['products'] = array();
            if ($product_total) {
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
                        $price = $this->currency->format($this->tax->calculate($discount['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

                        $special = Make::a('catalog/product')->create()->getProductSpecial($result['product_id']);

                        if ($special) {
                            $special = $this->currency->format($this->tax->calculate($special['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                        }
                    }

                    $aProductUrl = array_merge($aUrl, array('product_id=' . $result['product_id']));

                    $this->data['products'][] = array(
                        'product_id' => $result['product_id'],
                        'name' => $result['name'],
                        'model' => $result['model'],
                        'rating' => $rating,
                        'manufacturer' => $result['manufacturer'],
                        'stars' => sprintf($this->language->get('text_stars'), $rating),
                        'thumb' => $this->model_tool_image->resize($thumb, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                        'extra_img' => $this->model_tool_image->resize($extra_img, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                        'price' => $price,
                        'special' => $special,
                        'percent' => $special_percent,
                        'href' => makeUrl('product/product', $aProductUrl, true),
                        'meta_link' => QS::getMetaLink($result['meta_link'], $result['name']),
                        'alt_title' => QS::getMetaLink($result['alt_title'], $result['name']),
                        'options' => Make::a('catalog/product')->create()->getProductOptions($result['product_id']),
                        'wishlist' => makeUrl('account/wishlist', array(), true) . '&product_id=' . $result['product_id'],
                    );
                }
            }

            if (!$this->config->get('config_customer_price')) {
                $this->data['display_price'] = TRUE;
            } elseif ($this->customer->isLogged()) {
                $this->data['display_price'] = TRUE;
            } else {
                $this->data['display_price'] = FALSE;
            }

//        $limit = $this->config->get('config_catalog_limit');
            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->text = null;
            $pagination->url = makeUrl('product/search', $aUrl, true) . '&page={page}';
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
            $this->data['sort_by'] = array('pd.name' => 'name', 'p.price' => 'price');
            $this->data['limit_by'] = array(4, 8, 12, 24, 36, 48);
            $this->data['sort_type'] = ($order == "ASC" ? "category-asc" : "category-desc");

            $aSortArray = $aUrl;
            unset($aSortArray['sort']);

            $aOrderArray = $aUrl;
            unset($aOrderArray['order']);

            $aLimitArray = $aUrl;
            unset($aLimitArray['limit']);

            $this->data['sort_url'] = makeUrl('product/search', $aSortArray, true);
            $this->data['order_url'] = makeUrl('product/search', $aOrderArray, true);
            $this->data['limit_url'] = makeUrl('product/search', $aLimitArray, true);
            $this->data['limit'] = $limit;
            $this->data['sort'] = $sort;
            $this->data['order'] = $order;
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/search.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/product/search.tpl';
        } else {
            $this->template = 'default/template/product/search.tpl';
        }

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function search($parent_id, $level = 0) {
        $html = '';
        $this->load->model('tool/image');
        if (isset($this->request->get['keyword']) && trim($this->request->get['keyword']) != '') {
            $results = Make::a('catalog/product')->create()
                    ->getProductsByKeyword($this->request->get['keyword'])
                    //->select('name')
                    ->find_many(true);
            
            if ($results) {
                $html .='<ul>';
                foreach ($results as $result) {
                    if($result['image'] && file_exists(DIR_IMAGE.$result['image'])){
                        $result['image'] = $this->model_tool_image->resize($result['image'],50,50);
                    } else {
                        $result['image'] = $this->model_tool_image->resize('no_image.jpg',50,50);
                    }
                    $name = $result['name'];
                    if(strlen($result['name']) > 23){
                        $name = trim(substr($result['name'],0,23)).'...';
                    }
                    $html .= '<li title="' . ($result['meta_title']?$result['meta_title']:$result['name']) . '" class="odd first last"><span class="amount">' . $this->currency->format($result['price']) . '</span>' .'<img src="'.$result['image'].'" /> ' . $name . '</li>';
                }
                $html .= '</ul>';
            }
        }
        $this->response->setOutput($html);
    }

}

?>