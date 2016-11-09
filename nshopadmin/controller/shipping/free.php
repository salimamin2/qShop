<?php
class ControllerShippingFree extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/free');
                $this->load->language('common/customer_group_select');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
                $this->load->model('setting/extension');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
                        $customer_group = join(',',$this->request->post['free_customer_group']);
                        $data = $this->request->post;
                        unset($data['free_customer_group']);
                        $this->model_setting_extension->addCustomerGroupLink($customer_group,'shipping','free');
			Make::a('setting/setting')->create()->editSetting('free', $data);
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'extension/shipping&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
                $this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'extension/shipping&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'shipping/free&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'shipping/free&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'extension/shipping&token=' . $this->session->data['token'];
	
		if (isset($this->request->post['free_total'])) {
			$this->data['free_total'] = $this->request->post['free_total'];
		} else {
			$this->data['free_total'] = $this->config->get('free_total');
		}

		if (isset($this->request->post['free_geo_zone_id'])) {
			$this->data['free_geo_zone_id'] = $this->request->post['free_geo_zone_id'];
		} else {
			$this->data['free_geo_zone_id'] = $this->config->get('free_geo_zone_id');
		}
		
		if (isset($this->request->post['free_status'])) {
			$this->data['free_status'] = $this->request->post['free_status'];
		} else {
			$this->data['free_status'] = $this->config->get('free_status');
		}
		
		if (isset($this->request->post['free_sort_order'])) {
			$this->data['free_sort_order'] = $this->request->post['free_sort_order'];
		} else {
			$this->data['free_sort_order'] = $this->config->get('free_sort_order');
		}				
		
                if (isset($this->request->post['free_customer_group'])) {
			$this->data['free_customer_group'] = $this->request->post['free_customer_group'];
		} else {
                    $customer_group = explode(',',$this->model_setting_extension->getCustomerGroupLink('shipping','free'));
			$this->data['free_customer_group'] = $customer_group;
		}
		$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
                
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
								
		$this->template = 'shipping/free.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/free')) {
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