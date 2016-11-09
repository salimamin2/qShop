<?php
class ControllerModuleLeftBanner extends Controller {
    protected function index() {
        //$this->language->load('module/left_banner');
        $this->data['status'] = $this->config->get('left_banner_status');
        $this->data['desc'] = html_entity_decode($this->config->get('left_banner_desc'));

        $this->id = 'left_banner';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/left_banner.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/left_banner.tpl';
        } else {
            $this->template = 'default/template/module/left_banner.tpl';
        }

        $this->render();
    }

}

?>