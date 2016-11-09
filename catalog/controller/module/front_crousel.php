<?php

class ControllerModuleFrontCrousel extends Controller {

    public function index() {
        //$this->language->load('module/front_slideshow');

        if (isset($this->request->get['manufacturer_id'])) {
            $this->data['manufacturer_id'] = $this->request->get['manufacturer_id'];
        } else {
            $this->data['manufacturer_id'] = 0;
        }
        $this->data['text_check_theme'] = $this->language->get('text_check_theme');
        $this->load->model('setting/setting');

        $this->data['frontc'] = array();
        $results = $this->model_setting_setting->getFrontImages('frontc');
        $this->data['frontc_status'] = $results['frontc_status'];
        $this->data['frontc'] = array();
        if ($results) {
            unset($results['frontc_status']);
            $i = 1;
            foreach ($results as $key => $value) {
                if (substr($key, 0, 12) == 'frontc_image') {
                    $j = substr($key,12);
                    $this->data['frontc'][$j]['image'] = $value;
                }
                if (substr($key, 0, 11) == 'frontc_link') {
                    $j = substr($key,11);
                    $this->data['frontc'][$j]['link'] = $value;
                    $i++;
                }
            }
        }

        $this->id = 'front_crousel';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/front_crousel.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/front_crousel.tpl';
        } else {
            $this->template = 'default/template/module/front_crousel.tpl';
        }

        $this->render();
    }

}

?>