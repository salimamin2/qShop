<?php
class ControllerCatalogTestimonial extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('catalog/testimonial');

		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('catalog/testimonial');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/testimonial');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/testimonial');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_testimonial->addTestimonial($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect((HTTPS_SERVER . 'catalog/testimonial' . $url));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/testimonial');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/testimonial');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_testimonial->editTestimonial($this->request->get['testimonial_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect((HTTPS_SERVER . 'catalog/testimonial' . $url));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('catalog/testimonial');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/testimonial');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $testimonial_id) {
				$this->model_catalog_testimonial->deleteTestimonial($testimonial_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_deleted');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect((HTTPS_SERVER . 'catalog/testimonial' . $url));
		}

		$this->getList();
	}

	private function getList() {

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => (HTTPS_SERVER . 'common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => makeUrl('catalog/testimonial'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = makeUrl('catalog/testimonial/insert');
		$this->data['delete'] = makeUrl('catalog/testimonial/delete');

		$this->data['testimonials'] = array();

		$results = $this->model_catalog_testimonial->getTestimonials();
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => makeUrl('catalog/testimonial/update&testimonial_id=' . $result['testimonial_id'])
			);
						
			$this->data['testimonials'][] = array(
				'testimonial_id' => $result['testimonial_id'],
				'title'      => $result['title'],
				'sort_order' => $result['sort_order'],
				'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['testimonial_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->template = 'catalog/testimonial_list.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => (HTTPS_SERVER . 'common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => (HTTPS_SERVER . 'catalog/testimonial'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
							
		if (!isset($this->request->get['testimonial_id'])) {
			$this->data['action'] = (HTTPS_SERVER . 'catalog/testimonial/insert' . $url);
		} else {
			$this->data['action'] = (HTTPS_SERVER . 'catalog/testimonial/update&testimonial_id=' . $this->request->get['testimonial_id'] . $url);
		}
		
		$this->data['cancel'] = (HTTPS_SERVER . 'catalog/testimonial' . $url);

		if (isset($this->request->get['testimonial_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$testimonial_info = $this->model_catalog_testimonial->getTestimonial($this->request->get['testimonial_id']);
		}

        $oLanguage = Make::a('localisation/language')->create();
		
		$this->data['languages'] = $oLanguage->getLanguages();
		
		if (isset($this->request->post['testimonial_description'])) {
			$this->data['testimonial_description'] = $this->request->post['testimonial_description'];
		} elseif (isset($this->request->get['testimonial_id'])) {
			$this->data['testimonial_description'] = $this->model_catalog_testimonial->gettestimonialDescriptions($this->request->get['testimonial_id']);
		} else {
			$this->data['testimonial_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($testimonial_info)) {
			$this->data['status'] = $testimonial_info['status'];
		} else {
			$this->data['status'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($testimonial_info)) {
			$this->data['sort_order'] = $testimonial_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		$this->template = 'catalog/testimonial_form.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/testimonial')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['testimonial_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen(utf8_decode($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/testimonial')) {
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