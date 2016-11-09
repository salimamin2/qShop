<?php
class ControllerModuleRightBanner extends Controller {
	protected function index() {
		//$this->language->load('module/right_banner');
                $this->data['status'] = $this->config->get('right_banner_status');
                $this->data['desc'] = html_entity_decode($this->config->get('right_banner_desc'));
                
		$this->id = 'right_banner';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/right_banner.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/right_banner.tpl';
		} else {
			$this->template = 'default/template/module/right_banner.tpl';
		}

		$this->render();
	}
}
?>