<?php
class ControllerPaymentEasypaisa extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/easypaisa');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			Make::a('setting/setting')->create()->editSetting('easypaisa', $this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'extension/payment&token=' . $this->session->data['token']);
		}
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['help_total'] = $this->language->get('help_total');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

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
       		'href'      => HTTPS_SERVER . 'payment/easypaisa&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);


		$this->data['action'] = HTTPS_SERVER . 'payment/easypaisa&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'extension/payment&token=' . $this->session->data['token'];



		if (isset($this->request->post['easypaisa_total'])) {
			$this->data['easypaisa_total'] = $this->request->post['easypaisa_total'];
		} else {
			$this->data['easypaisa_total'] = $this->config->get('easypaisa_total');
		}

		if (isset($this->request->post['easypaisa_order_status_id'])) {
			$this->data['easypaisa_order_status_id'] = $this->request->post['easypaisa_order_status_id'];
		} else {
			$this->data['easypaisa_order_status_id'] = $this->config->get('easypaisa_order_status_id');
		}
		if (isset($this->request->post['easypaisa_transaction'])) {
			$this->data['easypaisa_transaction'] = $this->request->post['easypaisa_transaction'];
		} else {
			$this->data['easypaisa_transaction'] = $this->config->get('easypaisa_transaction');
		}
		if (isset($this->request->post['easypaisa_test'])) {
			$this->data['easypaisa_test'] = $this->request->post['easypaisa_test'];
		} else {
			$this->data['easypaisa_test'] = $this->config->get('easypaisa_test');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['easypaisa_geo_zone_id'])) {
			$this->data['easypaisa_geo_zone_id'] = $this->request->post['easypaisa_geo_zone_id'];
		} else {
			$this->data['easypaisa_geo_zone_id'] = $this->config->get('easypaisa_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['easypaisa_status'])) {
			$this->data['easypaisa_status'] = $this->request->post['easypaisa_status'];
		} else {
			$this->data['easypaisa_status'] = $this->config->get('easypaisa_status');
		}

		if (isset($this->request->post['easypaisa_sort_order'])) {
			$this->data['easypaisa_sort_order'] = $this->request->post['easypaisa_sort_order'];
		} else {
			$this->data['easypaisa_sort_order'] = $this->config->get('easypaisa_sort_order');
		}

		if (isset($this->request->post['easypaisa_email'])) {
			$this->data['easypaisa_email'] = $this->request->post['easypaisa_email'];
		} else {
			$this->data['easypaisa_email'] = $this->config->get('easypaisa_email');
		}

		$this->template = 'payment/easypaisa.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/easypaisa')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['easypaisa_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}	
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}