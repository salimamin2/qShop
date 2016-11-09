<?php

class ControllerModuleFrontSlideshow extends Controller {

    public function index() {
	$this->data['frontss_status'] = $this->config->get('frontss_status');
	$this->data['frontss'] = unserialize($this->config->get('frontss_data'));

	$this->data['image_url'] = HTTPS_IMAGE;

	$this->id = 'front_slideshow';
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/front_slideshow.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/front_slideshow.tpl';
	} else {
	    $this->template = 'default/template/module/front_slideshow.tpl';
	}

	$this->render();
    }

}

?>