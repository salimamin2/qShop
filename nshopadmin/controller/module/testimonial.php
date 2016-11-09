<?php
class ControllerModuleTestimonial extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/testimonial');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			Make::a('setting/setting')->create()->editSetting('testimonial', $this->request->post);		
					
			// $this->session->data['success'] = $this->language->get('text_success');
						
			// $this->redirect((HTTPS_SERVER . 'extension/module'));
			$this->data['success'] = $this->language->get('text_success');
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_limit'] = $this->language->get('entry_limit');
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
       		'href'      => (HTTPS_SERVER . 'common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => (HTTPS_SERVER . 'extension/module'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => (HTTPS_SERVER . 'module/testimonial'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = (HTTPS_SERVER . 'module/testimonial');
		
		$this->data['cancel'] = (HTTPS_SERVER . 'extension/module');

		if (isset($this->request->post['testimonial_limit'])) {
			$this->data['testimonial_limit'] = $this->request->post['testimonial_limit'];
		} else {
			$this->data['testimonial_limit'] = $this->config->get('testimonial_limit');
		}	
		
		if (isset($this->request->post['testimonial_position'])) {
			$this->data['testimonial_position'] = $this->request->post['testimonial_position'];
		} else {
			$this->data['testimonial_position'] = $this->config->get('testimonial_position');
		}
		
		if (isset($this->request->post['testimonial_status'])) {
			$this->data['testimonial_status'] = $this->request->post['testimonial_status'];
		} else {
			$this->data['testimonial_status'] = $this->config->get('testimonial_status');
		}
		
		if (isset($this->request->post['testimonial_sort_order'])) {
			$this->data['testimonial_sort_order'] = $this->request->post['testimonial_sort_order'];
		} else {
			$this->data['testimonial_sort_order'] = $this->config->get('testimonial_sort_order');
		}				
		
		$this->template = 'module/testimonial.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/testimonial')) {
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