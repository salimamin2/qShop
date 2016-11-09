<?php

class ControllerModuleTopBanner extends Controller {

    protected function index() {
        //$this->language->load('module/top_banner');
        $this->data['status'] = $this->config->get('top_banner_status');
        $this->data['desc'] = html_entity_decode($this->config->get('top_banner_desc'));

        $this->id = 'top_banner';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/top_banner.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/top_banner.tpl';
        } else {
            $this->template = 'default/template/module/top_banner.tpl';
        }

        $this->render();
    }

}

?>