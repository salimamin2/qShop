<?php
class ControllerModuleFooterArea extends Controller {
    protected function index() {
        //$this->language->load('module/footer_area');
        $this->data['status'] = $this->config->get('footer_area_status');
        $this->data['desc'] = html_entity_decode($this->config->get('footer_area_desc'));

        $this->id = 'footer_area';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/footer_area.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/footer_area.tpl';
        } else {
            $this->template = 'default/template/module/footer_area.tpl';
        }

        $this->render();
    }
}
?>