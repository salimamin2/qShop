<?php

class ControllerModuleMenu extends Controller {

    public function index() {
	$this->load->model('tool/seo_url');
	if (isset($this->request->get['params']) && isset($this->request->get['params']['position'])) {
	    $position = $this->request->get['params']['position'];
	}
	$this->data['menu_wrapper'] = '';
	
	if (isset($this->request->get['params']['wrapper'])) {
	    $this->data['menu_wrapper'] = explode('{data}',$this->request->get['params']['wrapper']);
	}
	$this->data['headers'] = array(
	    'Questions?',
	    'Shipping',
	    'About Us',
	    'News',
	);
	$aResults = unserialize($this->config->get('menu_data'));
	$aMenu = array();
	$this->load->helper_obj('menu');
	foreach ($aResults as $sMenu => $aData) {
	    if ($aData['position'] == $position && $aData['status'] == 1) {
		$aMenu[] = $sMenu; //$this->helper_menu->getFullMenu($sMenu);
	    }
	}
	
	$this->data['aMenus'] = $aMenu;
	$this->data['position'] = $position;
	$this->id = 'menu';
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/menu.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/menu.tpl';
	} else {
	    $this->template = 'default/template/module/menu.tpl';
	}

	$this->render();
    }

    protected function sort_menu(&$array, $key) {
	$sorter = array();
	$ret = array();
	reset($array);
	foreach ($array as $ii => $va) {
	    $sorter[$ii] = $va[$key];
	}
	asort($sorter);
	foreach ($sorter as $ii => $va) {
	    $ret[$va] = $array[$ii];
	}
	return $ret;
    }

}

?>