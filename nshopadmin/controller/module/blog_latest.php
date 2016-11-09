<?php
class ControllerModuleBlogLatest extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/blog_latest');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            Make::a('setting/setting')->create()->editSetting('blog_latest', $this->request->post);		
			// $this->session->data['success'] = $this->language->get('text_success');
			// // $this->redirect(HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token']);
			// $this->redirect(makeUrl('extension/module'));
			$this->data['success'] = $this->language->get('text_success');
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		$this->data['text_home'] = $this->language->get('text_home');
		
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
       		'href'      => makeUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => makeUrl('extension/module'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => makeUrl('module/blog_latest'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = makeUrl('module/blog_latest');
		
		$this->data['cancel'] = makeUrl('extension/module');

		if (isset($this->request->post['blog_latest_limit'])) {
			$this->data['blog_latest_limit'] = $this->request->post['blog_latest_limit'];
		} else {
			$this->data['blog_latest_limit'] = $this->config->get('blog_latest_limit');
		}	
		
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
		    'title' => $this->language->get('text_top'),
		);
		$this->data['positions'][] = array(
		    'position' => 'bottom',
		    'title' => $this->language->get('text_bottom'),
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
		
		if (isset($this->request->post['blog_latest_position'])) {
			$this->data['blog_latest_position'] = $this->request->post['blog_latest_position'];
		} else {
			$this->data['blog_latest_position'] = $this->config->get('blog_latest_position');
		}
		
		if (isset($this->request->post['blog_latest_status'])) {
			$this->data['blog_latest_status'] = $this->request->post['blog_latest_status'];
		} else {
			$this->data['blog_latest_status'] = $this->config->get('blog_latest_status');
		}
		
		if (isset($this->request->post['blog_latest_sort_order'])) {
			$this->data['blog_latest_sort_order'] = $this->request->post['blog_latest_sort_order'];
		} else {
			$this->data['blog_latest_sort_order'] = $this->config->get('blog_latest_sort_order');
		}				
		
		$this->template = 'module/blog_latest.tpl';

		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/blog_latest')) {
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