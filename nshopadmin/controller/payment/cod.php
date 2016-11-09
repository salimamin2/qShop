<?php 
class ControllerPaymentCod extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('payment/cod');
                $this->load->language('common/customer_group_select');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
                $this->load->model('setting/extension');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
                        $customer_group = join(',',$this->request->post['cod_customer_group']);
                        $data = $this->request->post;
                        unset($data['cod_customer_group']);
                        $this->model_setting_extension->addCustomerGroupLink($customer_group,'payment','cod');
			Make::a('setting/setting')->create()->editSetting('cod', $data);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'extension/payment&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
				
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
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
       		'href'      => HTTPS_SERVER . 'extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'payment/cod&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'payment/cod&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'extension/payment&token=' . $this->session->data['token'];	
		
		if (isset($this->request->post['cod_order_status_id'])) {
			$this->data['cod_order_status_id'] = $this->request->post['cod_order_status_id'];
		} else {
			$this->data['cod_order_status_id'] = $this->config->get('cod_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['cod_geo_zone_id'])) {
			$this->data['cod_geo_zone_id'] = $this->request->post['cod_geo_zone_id'];
		} else {
			$this->data['cod_geo_zone_id'] = $this->config->get('cod_geo_zone_id'); 
		} 
		$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->load->model('localisation/geo_zone');						
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['cod_status'])) {
			$this->data['cod_status'] = $this->request->post['cod_status'];
		} else {
			$this->data['cod_status'] = $this->config->get('cod_status');
		}
		
		if (isset($this->request->post['cod_sort_order'])) {
			$this->data['cod_sort_order'] = $this->request->post['cod_sort_order'];
		} else {
			$this->data['cod_sort_order'] = $this->config->get('cod_sort_order');
		}
                if (isset($this->request->post['cod_customer_group'])) {
			$this->data['cod_customer_group'] = $this->request->post['cod_customer_group'];
		} else {
                    $customer_group = explode(',',$this->model_setting_extension->getCustomerGroupLink('payment','cod'));
			$this->data['cod_customer_group'] = $customer_group;
		}
		
		$this->template = 'payment/cod.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cod')) {
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