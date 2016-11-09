<?php

class ControllerModuleRightBannerBottom extends Controller {

    protected function index() {
        //$this->language->load('module/right_banner_bottom');
        $this->data['status'] = $this->config->get('right_banner_bottom_status');
        $this->data['desc'] = html_entity_decode($this->config->get('right_banner_bottom_desc'));

        $this->id = 'right_banner_bottom';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/right_banner_bottom.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/right_banner_bottom.tpl';
        } else {
            $this->template = 'default/template/module/right_banner_bottom.tpl';
        }

        $this->render();
    }

}

?>