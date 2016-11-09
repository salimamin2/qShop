<?php

class ControllerSaleCustomerGroup extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/customer_group');
 
		$this->document->title = $this->language->get('heading_title');
 		
		$this->load->model('sale/customer_group');
		
		$this->getList();
	}

	public function insert() {
		$this->load->language('sale/customer_group');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer_group');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_group->addCustomerGroup($this->request->post);
			
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
			
			$this->redirect(HTTPS_SERVER . 'sale/customer_group&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('sale/customer_group');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer_group');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_group->editCustomerGroup($this->request->get['customer_group_id'], $this->request->post);
			
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
			
			$this->redirect(HTTPS_SERVER . 'sale/customer_group&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function delete() { 
		$this->load->language('sale/customer_group');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer_group');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $customer_group_id) {
				$this->model_sale_customer_group->deleteCustomerGroup($customer_group_id);	
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
			
			$this->redirect(HTTPS_SERVER . 'sale/customer_group&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	private function getList() {

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'sale/customer_group&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = makeUrl('sale/customer_group/insert');
		$this->data['delete'] = makeUrl('sale/customer_group/delete');
	
		$this->data['customer_groups'] = array();

		$results = $this->model_sale_customer_group->getCustomerGroups();

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'icon' => 'icon-pencil',
                'class' => 'btn-info',  
				'href' => makeUrl('sale/customer_group/update',array('customer_group_id=' . $result['customer_group_id']))
			);		
		
			$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : NULL),
				'selected'          => isset($this->request->post['selected']) && in_array($result['customer_group_id'], $this->request->post['selected']),
				'action'            => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
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

		$this->template = 'sale/customer_group_list.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
 	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_ref_acc_type_id'] = $this->language->get('entry_ref_acc_type_id');
                $this->data['entry_ref_acc_type_code'] = $this->language->get('entry_ref_acc_type_code');
                $this->data['entry_free_ship_price'] = $this->language->get('entry_free_ship_price');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'sale/customer_group&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		if (!isset($this->request->get['customer_group_id'])) {
			$this->data['action'] = makeUrl('sale/customer_group/insert');
		} else {
			$this->data['action'] = makeUrl('sale/customer_group/update',array('customer_group_id=' . $this->request->get['customer_group_id']));
		}
		  
    	$this->data['cancel'] = makeUrl('sale/customer_group');

		if (isset($this->request->get['customer_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($this->request->get['customer_group_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];

		} elseif (isset($customer_group_info)) {
			$this->data['name'] = $customer_group_info['name'];
                        
		} else {
			$this->data['name'] = '';
		}

		$this->template = 'sale/customer_group_form.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression')); 
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/customer');
      	
		foreach ($this->request->post['selected'] as $customer_group_id) {
    		if ($this->config->get('config_customer_group_id') == $customer_group_id) {
	  			$this->error['warning'] = $this->language->get('error_default');	
			}  

			$customer_total = $this->model_sale_customer->getTotalCustomersByCustomerGroupId($customer_group_id);

			if ($customer_total) {
				$this->error['warning'] = sprintf($this->language->get('error_customer'), $customer_total);
			}
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>