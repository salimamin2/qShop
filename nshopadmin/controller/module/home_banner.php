<?php

class ControllerModuleHomeBanner extends Controller {

    private $error = array();

    public function index() {
	$this->load->language('module/home_banner');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('setting/setting');
	$this->load->model('setting/extension');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

	    foreach ($this->request->post['home_banner'] as $i => $value) {
		$sKey = '';
		if ($i != 0) {
		    $sKey = '_' . $i;
		}
		$aPost = array();
		foreach ($value as $k => $v) {
		    $aPost['home_banner' . $sKey . '_' . $k] = $v;
		}
		$name = 'home_banner' . $sKey;

		$aExten = $this->model_setting_extension->getExtension('module', $name);

		if (!$aExten) {

		    $this->model_setting_extension->install('module', $name);
		}
		Make::a('setting/setting')->create()->editSetting($name, $aPost);
	    }

	    $this->cache->clear('product');

	    // $this->session->data['success'] = $this->language->get('text_success');

	    // $this->redirect(HTTPS_SERVER . 'extension/module');
	    $this->data['success'] = $this->language->get('text_success');
	}

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_enabled'] = $this->language->get('text_enabled');
	$this->data['text_disabled'] = $this->language->get('text_disabled');

	$this->data['entry_description'] = $this->language->get('entry_description');
	$this->data['entry_position'] = $this->language->get('entry_position');
	$this->data['entry_status'] = $this->language->get('entry_status');
	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_cancel'] = $this->language->get('button_cancel');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home',
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'extension/module',
	    'text' => $this->language->get('text_module'),
	    'separator' => ' :: '
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'module/home_banner',
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->data['action'] = HTTPS_SERVER . 'module/home_banner';

	$this->data['cancel'] = HTTPS_SERVER . 'extension/module';

	$this->data['positions'] = array();

	$this->data['positions'][] = array(
	    'position' => 'left',
	    'title' => $this->language->get('text_left'),
	);

	$this->data['positions'][] = array(
	    'position' => 'right',
	    'title' => $this->language->get('text_right'),
	);
	$this->data['positions'][] = array(
	    'position' => 'top',
	    'title' => __('Top'),
	);
	$this->data['positions'][] = array(
	    'position' => 'bottom',
	    'title' => __('Bottom'),
	);

	$this->data['positions'][] = array(
	    'position' => 'home',
	    'title' => $this->language->get('text_home'),
	);

    $this->data['positions'][] = array(
        'position' => 'footer_1',
        'title' => $this->language->get('text_footer_1'),
    );

    $this->data['positions'][] = array(
        'position' => 'footer_2',
        'title' => $this->language->get('text_footer_2'),
    );

    $this->data['positions'][] = array(
        'position' => 'footer_3',
        'title' => $this->language->get('text_footer_3'),
    );


	$iTotal = Make::a('setting/setting')->create()->getTotalSettingByLike('home_banner');
	$this->data['iTotal'] = $iTotal;
	for ($i = 0; $i < $iTotal; $i++) {
	    $key = '';
	    if ($i != 0) {
		$key = '_' . $i;
	    }
	    if (isset($this->request->post['home_banner'][$i]['position']) && isset($this->request->post['home_banner'][$i]['position'])) {
		$this->data['home_banner'][$i]['position'] = $this->request->post['home_banner'][$i]['position'];
	    } else {
		$this->data['home_banner'][$i]['position'] = $this->config->get('home_banner' . $key . '_position');
	    }
	    if (isset($this->request->post['home_banner'][$i]) && isset($this->request->post['home_banner'][$i]['desc'])) {
		$this->data['home_banner'][$i]['desc'] = $this->request->post['home_banner'][$i]['desc'];
	    } else {
		$this->data['home_banner'][$i]['desc'] = $this->config->get('home_banner' . $key . '_desc');
	    }
	    if (isset($this->request->post['home_banner'][$i]) && isset($this->request->post['home_banner'][$i]['status'])) {
		$this->data['home_banner'][$i]['status'] = $this->request->post['home_banner'][$i]['status'];
	    } else {
		$this->data['home_banner'][$i]['status'] = $this->config->get('home_banner' . $key . '_status');
	    }
	    if (isset($this->request->post['home_banner'][$i]) && isset($this->request->post['home_banner'][$i]['sort_order'])) {
		$this->data['home_banner'][$i]['sort_order'] = $this->request->post['home_banner'][$i]['sort_order'];
	    } else {
		$this->data['home_banner'][$i]['sort_order'] = $this->config->get('home_banner' . $key . '_sort_order');
	    }
	}

	$this->template = 'module/home_banner.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
	if (!$this->user->hasPermission('modify', 'module/home_banner')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>