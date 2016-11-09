<?php
class ControllerModuleBottomBanner extends Controller {
	protected function index() {
		//$this->language->load('module/bottom_banner');
                $this->data['status'] = $this->config->get('bottom_banner_status');
                $this->data['desc'] = html_entity_decode($this->config->get('bottom_banner_desc'));
                
		$this->id = 'bottom_banner';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bottom_banner.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/bottom_banner.tpl';
		} else {
			$this->template = 'default/template/module/bottom_banner.tpl';
		}

		$this->render();
	}
}
?>