<?php
class ControllerTotalReward extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/reward');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            Make::a('setting/setting')->create()->editSetting('reward', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'extension/total&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
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
       		'text'      => $this->language->get('text_home'),
			'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => HTTPS_SERVER . 'extension/total&token=' . $this->session->data['token'],
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => HTTPS_SERVER . 'total/reward&token=' . $this->session->data['token'],
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'total/reward&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'extension/total&token=' . $this->session->data['token'];

		if (isset($this->request->post['reward_status'])) {
			$this->data['reward_status'] = $this->request->post['reward_status'];
		} else {
			$this->data['reward_status'] = $this->config->get('reward_status');
		}

		if (isset($this->request->post['reward_sort_order'])) {
			$this->data['reward_sort_order'] = $this->request->post['reward_sort_order'];
		} else {
			$this->data['reward_sort_order'] = $this->config->get('reward_sort_order');
		}
																		
		$this->template = 'total/reward.tpl';
				
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/reward')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>