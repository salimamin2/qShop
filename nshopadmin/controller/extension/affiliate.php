<?php
class ControllerExtensionAffiliate extends Controller {
	public function index() {
		$this->load->language('extension/affiliate');
		 
		$this->document->title = $this->language->get('heading_title'); 

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'extension/affiliate',
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['error'] = '';
		}

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('affiliate');
		
		$this->data['extensions'] = array();
						
		$files = glob(DIR_APPLICATION . 'controller/affiliate/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->load->language('affiliate/' . $extension);
	
				$action = array();
				
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => HTTPS_SERVER . 'extension/affiliate/install&extension=' . $extension
					);
				} else {
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => HTTPS_SERVER . 'affiliate/' . $extension
					);
								
					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => HTTPS_SERVER . 'extension/affiliate/uninstall&extension=' . $extension
					);
				}
				
				$text_link = $this->language->get('text_' . $extension);
				
				if ($text_link != 'text_' . $extension) {
					$link = $this->language->get('text_' . $extension);
				} else {
					$link = '';
				}
				
				$this->data['extensions'][] = array(
					'name'       => $this->language->get('heading_title'),
					'link'       => $link,
					'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get($extension . '_sort_order'),
					'action'     => $action
				);
			}
		}
		
		$this->template = 'extension/affiliate.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
	
	public function install() {
		if (!$this->user->hasPermission('modify', 'extension/affiliate')) {
			$this->session['error'] = $this->language->get('error_permission'); 
			
			$this->redirect(HTTPS_SERVER . 'extension/affiliate');
		} else {
			$this->load->model('setting/extension');
		
			$this->model_setting_extension->install('affiliate', $this->request->get['extension']);

			$this->load->model('user/user_group');
		
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'affiliate/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'affiliate/' . $this->request->get['extension']);

			$this->redirect(HTTPS_SERVER . 'extension/affiliate');
		}
	}
	
	public function uninstall() {
		if (!$this->user->hasPermission('modify', 'extension/affiliate')) {
			$this->session['error'] = $this->language->get('error_permission'); 
			
			$this->redirect(HTTPS_SERVER . 'extension/affiliate');
		} else {		
			$this->load->model('setting/extension');
			$this->load->model('setting/setting');
		
			$this->model_setting_extension->uninstall('affiliate', $this->request->get['extension']);
		
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
			$this->redirect(HTTPS_SERVER . 'extension/affiliate');	
		}			
	}
}
?>