<?php

class ControllerModuleHomeBanner extends Controller {

    protected function index() {
	//$this->language->load('module/home_banner');
    $this->load->model('setting/setting');
//	$sKey = '';
    $sKey = array();
	if (isset($this->request->get['params']) && isset($this->request->get['params']['key'])) {
//	    $sKey = '_' . $this->request->get['params']['key'];
        $sKey[] = '_' . $this->request->get['params']['key'];
        //d($sKey);
	}
    elseif(isset($this->request->get['params']) && isset($this->request->get['params']['position'])) {
        $results = $this->model_setting_setting->getSetting($this->request->get['params']['position'],"home_banner");
        foreach($results as $result) {
            $sKey[] = str_replace('home_banner','',$result['group']);
        }
    }
    else {
        $sKey[] = "";
    }

    $this->data['blocks'] = array();
    foreach($sKey as $key) {
        $this->data['blocks'][] = array(
            'status'  =>  $this->config->get('home_banner' . $key . '_status'),
            'desc' => html_entity_decode($this->config->get('home_banner' . $key . '_desc')),
            'position' => $this->config->get('home_banner' . $key . '_position')
        );
    }

//    d($this->data['blocks']);

	$this->id = 'home_banner';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/home_banner.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/home_banner.tpl';
	} else {
	    $this->template = 'default/template/module/home_banner.tpl';
	}

	$this->render();
    }

}

?>