<?php

class ControllerModuleFrontCrawler extends Controller {

    protected function index() {
        //$this->language->load('module/front_slideshow');
        $this->load->model('setting/setting');
        $this->load->model('tool/image');
        $this->data['frontcrlr'] = array();
        $this->data['direction'] = $this->language->get('direction');

        $results = $this->model_setting_setting->getFrontImages('frontcrlr');
        $this->data['frontcrlr_status'] = $results['frontcrlr_status'];
        $this->data['frontcrlr'] = array();
        $this->data['count'] = 0;
        if ($results) {
            $i = 1;

            foreach ($results as $key => $value) {
                if (substr($key, 0, 15) == 'frontcrlr_image') {
                    $j = strtolower(substr($key,15));
                    $this->data['frontcrlr'][$j]['image'] = $key;
                    $this->data['frontcrlr'][$j]['image_value'] = $this->model_tool_image->resize($value,75,75);
                }
                if (substr($key, 0, 14) == 'frontcrlr_link') {
                    $j = substr($key,14);
                    $this->data['frontcrlr'][$j]['link'] = $key;
                    $this->data['frontcrlr'][$j]['link_value'] = $value;
                }
                if (substr($key, 0, 20) == 'frontcrlr_sort_order') {
                    $j = substr($key,20);
                    $this->data['frontcrlr'][$j]['sort_order'] = $key;
                    $this->data['frontcrlr'][$j]['sort_order_value'] = $value;
                    $i++;
                }
            }
        }
        //d($this->data['frontss']);
        
        $this->data['image_url'] = HTTPS_IMAGE;
        $this->id = 'front_crawler';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/front_crawler.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/front_crawler.tpl';
        } else {
            $this->template = 'default/template/module/front_crawler.tpl';
        }

        $this->render();
    }

}

?>