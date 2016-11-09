<?php

class ControllerModuleSpecial extends Controller {

    protected function index() {
        $this->language->load('module/special');
        
        $this->load->model('tool/seo_url');
        $this->load->model('tool/image');

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['button_add_to_cart'] = $this->language->get('button_add_to_cart');

        $this->data['products'] = array();
        $limit = $this->config->get('special_limit');
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
        $results = Make::a('catalog/product')->create()->getProductSpecials('pd.name', 'ASC', 0, $limit);
        foreach ($results as $result) {
            if ($result['image'] && file_exists(DIR_IMAGE.$result['image'])) {
                $image = $result['image'];
            } else {
                $image = 'no_image.jpg';
            }

            if ($this->config->get('config_review')) {
                $rating = Make::a('catalog/review')->create()->getAverageRating($result['product_id']);
            } else {
                $rating = false;
            }

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
                $add = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/product&product_id=' . $result['product_id']);
            } else {
                $add = HTTPS_SERVER . 'checkout/cart&product_id=' . $result['product_id'];
            }

            $this->data['products'][] = array(
                'product_id' => $result['product_id'],
                'name' => $result['name'],
                'model' => $result['model'],
                'rating' => $rating,
                'stars' => sprintf($this->language->get('text_stars'), $rating),
                'price' => $price,
                'options' => $options,
                'special' => $special,
                'image' => $this->model_tool_image->resize($image, $width, $height),
                'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/product&product_id=' . $result['product_id']),
                'meta_link' => QS::getMetaLink($result['meta_link'],$result['name']),
                'alt_title' => QS::getMetaLink($result['img_alt'],$result['name']),
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

        $this->id = 'special';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/special.tpl';
        } else {
            $this->template = 'default/template/module/special.tpl';
        }
        $this->render();
    }

}

?>