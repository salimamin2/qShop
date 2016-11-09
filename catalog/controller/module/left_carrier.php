<?php

class ControllerModuleLeftCarrier extends Controller {

    protected function index() {
        $this->language->load('module/left_carrier');
        $this->load->helper_obj('catalog');
        $this->data['title'] = $this->document->title;
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->load->model('tool/seo_url');

        $categories_id = implode(',',unserialize($this->config->get('config_category_carrier')));
        $categories = Make::a('catalog/category')->create()->getCategoriesById($categories_id,FALSE);
        
        $this->data['categories'] = array();
        $i = 0;
        foreach ($categories as $category) {
            $this->data['categories'][$i] = array(
                'category_id' => $category['category_id'],
                'name' => $category['name'],
                'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/category&path=' . $category['category_id'])
            );
            if (isset($category['child'])) {
                foreach ($category['child'] as $child) {
                    $children[] = array('child_id' => $child['category_id'],
                        'child_name' => $child['name'],
                        'child_href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/category&path=' . $category['category_id'])
                    );
                }
                $this->data['categories'][$i]['child'] = $children;
            }
            $i++;
        }
        $this->id = 'left_carrier';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/left_carrier.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/left_carrier.tpl';
        } else {
            $this->template = 'default/template/module/left_carrier.tpl';
        }

        $this->render();
    }

}

?>