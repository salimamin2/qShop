<?php
class ControllerModuleRightBanner extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/right_banner');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			Make::a('setting/setting')->create()->editSetting('right_banner', $this->request->post);

			$this->cache->delete('product');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'extension/module');
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
              
                $this->data['entry_description'] = $this->language->get('entry_description');
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
       		'href'      => HTTPS_SERVER . 'common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'extension/module',
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'module/right_banner',
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = HTTPS_SERVER . 'module/right_banner';

		$this->data['cancel'] = HTTPS_SERVER . 'extension/module';


		if (isset($this->request->post['right_banner_desc'])) {
			$this->data['right_banner_desc'] = $this->request->post['right_banner_desc'];
		} else {
			$this->data['right_banner_desc'] = $this->config->get('right_banner_desc');
		}

		if (isset($this->request->post['right_banner_status'])) {
			$this->data['right_banner_status'] = $this->request->post['right_banner_status'];
		} else {
			$this->data['right_banner_status'] = $this->config->get('right_banner_status');
		}

		if (isset($this->request->post['right_banner_sort_order'])) {
			$this->data['right_banner_sort_order'] = $this->request->post['right_banner_sort_order'];
		} else {
			$this->data['right_banner_sort_order'] = $this->config->get('right_banner_sort_order');
		}

		$this->template = 'module/right_banner.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/right_banner')) {
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