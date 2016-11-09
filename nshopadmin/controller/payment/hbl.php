<?php 
class ControllerPaymentHBL extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/hbl');

		$this->load->language('common/customer_group_select');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
        $this->load->model('setting/extension');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
                        $customer_group = join(',',$this->request->post['hbl_customer_group']);
                        $data = $this->request->post;
                        unset($data['hbl_customer_group']);
                        $this->model_setting_extension->addCustomerGroupLink($customer_group,'payment','hbl');
			Make::a('setting/setting')->create()->editSetting('hbl', $data);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'extension/payment&token=' . $this->session->data['token']);
		}


		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_test_status'] = $this->language->get('entry_test_status');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_cctypes'] = $this->language->get('entry_cctypes');
		$this->data['entry_cvv2_indicator'] = $this->language->get('entry_cvv2_indicator');
		$this->data['entry_use_ssl'] = $this->language->get('entry_use_ssl');
		$this->data['entry_mode'] = $this->language->get('entry_mode');
		$this->data['entry_transtype'] = $this->language->get('entry_transtype');
		$this->data['entry_response_path'] = $this->language->get('entry_response_path');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
                $this->data['entry_clientid'] = $this->language->get('entry_clientid');
                
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		 
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}	
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		if (isset($this->error['clientid'])) {
			$this->data['error_clientid'] = $this->error['clientid'];
		} else {
			$this->data['error_clientid'] = '';
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/hbl&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = makeUrl('payment/hbl');
		
		$this->data['cancel'] = makeUrl('extension/payment');
		
		if (isset($this->request->post['hbl_username'])) {
			$this->data['hbl_username'] = $this->request->post['hbl_username'];
		} else {
			$this->data['hbl_username'] = $this->config->get('hbl_username');
		}

		if (isset($this->request->post['hbl_password'])) {
			$this->data['hbl_password'] = $this->request->post['hbl_password'];
		} else {
			$this->data['hbl_password'] = $this->config->get('hbl_password');
		}
		
                if (isset($this->request->post['hbl_clientid'])) {
			$this->data['hbl_clientid'] = $this->request->post['hbl_clientid'];
		} else {
			$this->data['hbl_clientid'] = $this->config->get('hbl_clientid');
		}
		
		if (isset($this->request->post['hbl_cctypes'])) {
                        $cctypes = explode(',',$this->request->post['hbl_cctypes']);
			$this->data['hbl_cctypes'] = $cctypes;
		} else {
                    $cctypes = explode(',',$this->config->get('hbl_cctypes'));
			$this->data['hbl_cctypes'] = $cctypes;
		}
		
		if (isset($this->request->post['hbl_cvv2_indicator'])) {
			$this->data['hbl_cvv2_indicator'] = $this->request->post['hbl_cvv2_indicator'];
		} else {
			$this->data['hbl_cvv2_indicator'] = $this->config->get('hbl_cvv2_indicator');
		}
                
		if (isset($this->request->post['hbl_use_ssl'])) {
			$this->data['hbl_use_ssl'] = $this->request->post['hbl_use_ssl'];
		} else {
			$this->data['hbl_use_ssl'] = $this->config->get('hbl_use_ssl');
		}

		if (isset($this->request->post['hbl_test_status'])) {
			$this->data['hbl_test_status'] = $this->request->post['hbl_test_status'];
		} else {
			$this->data['hbl_test_status'] = $this->config->get('hbl_test_status');
		}

		if (isset($this->request->post['hbl_mode'])) {
			$this->data['hbl_mode'] = $this->request->post['hbl_mode'];
		} else {
			$this->data['hbl_mode'] = $this->config->get('hbl_mode');
		}

		if (isset($this->request->post['hbl_transtype'])) {
			$this->data['hbl_transtype'] = $this->request->post['hbl_transtype'];
		} else {
			$this->data['hbl_transtype'] = $this->config->get('hbl_transtype');
		}

		if (isset($this->request->post['hbl_response_path'])) {
			$this->data['hbl_response_path'] = $this->request->post['hbl_response_path'];
		} else {
			$this->data['hbl_response_path'] = $this->config->get('hbl_response_path');
		}

		if (isset($this->request->post['hbl_order_status_id'])) {
			$this->data['hbl_order_status_id'] = $this->request->post['hbl_order_status_id'];
		} else {
			$this->data['hbl_order_status_id'] = $this->config->get('hbl_order_status_id');
		}

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['hbl_geo_zone_id'])) {
			$this->data['hbl_geo_zone_id'] = $this->request->post['hbl_geo_zone_id'];
		} else {
			$this->data['hbl_geo_zone_id'] = $this->config->get('hbl_geo_zone_id');
		}
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['hbl_status'])) {
			$this->data['hbl_status'] = $this->request->post['hbl_status'];
		} else {
			$this->data['hbl_status'] = $this->config->get('hbl_status');
		}
		
		if (isset($this->request->post['hbl_sort_order'])) {
			$this->data['hbl_sort_order'] = $this->request->post['hbl_sort_order'];
		} else {
			$this->data['hbl_sort_order'] = $this->config->get('hbl_sort_order');
		}
		  if (isset($this->request->post['cod_customer_group'])) {
			$this->data['cod_customer_group'] = $this->request->post['cod_customer_group'];
		} else {
                    $customer_group = explode(',',$this->model_setting_extension->getCustomerGroupLink('payment','cod'));
			$this->data['cod_customer_group'] = $customer_group;
		}
		

                $this->data['modes']=array(
                        'P' => 'Production',
                        'Y' => 'Approved',
                        'N' => 'Reject',
                        'R' => 'Random'
                );
                $this->data['trans_types']=array(
                        'authorization' => 'authorization',
                        'sale' => 'sale'
                );
                $this->data['card_types']=array(
                    'AE' => 'American Express',
                    'VI' => 'Visa',
                    'MC' => 'Master Card',
                    'DI' => 'Discover',
                    'SS' => 'Switch/Solo',
                    'OT' => 'Other'
                );

		$this->template = 'payment/hbl.tpl';
		/*$this->children = array(
			'common/header',	
			'common/footer'	
		);*/
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/hbl')) {
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