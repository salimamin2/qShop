<?php

class ControllerModuleRecommended extends Controller {

    protected function index() {

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = HTTPS_SERVER . 'account/login';

        }
        else {
                $this->language->load('module/recommended');
              $this->load->model('catalog/information');

                $this->data['heading_title'] = $this->language->get('heading_title');

                $this->load->model('tool/seo_url');
                $this->load->model('tool/image');

                $this->data['products'] = array();

                $this->data['action'] = makeUrl('checkout/cart', array(), true, true);


           // $results = Make::a('catalog/product')->create()->getRecommendedProducts($this->customer->isLogged());
            //d($results);
            $results =$this-> model_catalog_information->getRecommendedProducts($this->customer->isLogged());

                $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');
                foreach ($results as $result) {

                    $image = '';
                    if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                        $image = HTTPS_IMAGE . $result['image'];
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
                    if ($options) {
                        $add = makeUrl('product/product', array('product_id=' . $result['product_id']), true, true);
                    } else {
                        $add = makeUrl('checkout/cart', array(), true, true) . '&product_id=' . $result['product_id'];
                    }

                    $this->data['products'][] = array(
                        'product_id' => $result['product_id'],
                        'name' => $result['name'],
                        'model' => $result['model'],
                        'author' => $result['delivery_days'],
                        'description' => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
                        'rating' => $rating,
                        'extra_img' => $extra_img,
                        'stars' => sprintf($this->language->get('text_stars'), $rating),
                        'price' => $price,
                        'options' => $options,
                        'special' => $special,
                        'image' => $image,
                        'href' => makeUrl('product/product', array('product_id=' . $result['product_id']), true),
                        'meta_link' => QS::getMetaLink($result['meta_link'], $result['name']),
                        'alt_title' => QS::getMetaLink($result['img_alt'], $result['name']),
                        'wishlist' => makeUrl('account/wishlist', array(), true, true) . '&product_id=' . $result['product_id'],
                        'add' => $add
                    );
                }

                if (!$this->config->get('config_customer_price')) {
                    $this->data['display_price'] = TRUE;
                } elseif ($this->customer->isLogged()) {
                    $this->data['display_price'] = TRUE;
                } else {
                    $this->data['display_price'] = FALSE;
                }

                $this->id = 'recommended';

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/recommended.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/module/recommended.tpl';
                } else {
                    $this->template = 'default/template/module/recommended.tpl';
                }

                $this->render();



        }
    }

}

?>