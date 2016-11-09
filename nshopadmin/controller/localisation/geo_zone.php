<?php
class ControllerLocalisationGeoZone extends Controller { 
	private $error = array();
 
	public function index() {
		$this->load->language('localisation/geo_zone');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/geo_zone');
		
		$this->getList();
	}

	public function insert() {
		$this->load->language('localisation/geo_zone');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/geo_zone');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_geo_zone->addGeoZone($this->request->post);
			
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
			
			$this->redirect(HTTPS_SERVER . 'localisation/geo_zone&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('localisation/geo_zone');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/geo_zone');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_geo_zone->editGeoZone($this->request->get['geo_zone_id'], $this->request->post);

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
			
			$this->redirect(HTTPS_SERVER . 'localisation/geo_zone&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/geo_zone');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('localisation/geo_zone');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $geo_zone_id) {
				$this->model_localisation_geo_zone->deleteGeoZone($geo_zone_id);
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
			
			$this->redirect(HTTPS_SERVER . 'localisation/geo_zone&token=' . $this->session->data['token'] . $url);
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
       		'href'      => HTTPS_SERVER . 'localisation/geo_zone&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = makeUrl('localisation/geo_zone/insert');
		$this->data['delete'] = makeUrl('localisation/geo_zone/delete');
		
		$this->data['geo_zones'] = array();

		$results = $this->model_localisation_geo_zone->getGeoZones();

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'icon' => 'icon-pencil',
                'class' => 'btn-info',
				'href' => makeUrl('localisation/geo_zone/update',array('geo_zone_id=' . $result['geo_zone_id']))
			);
					
			$this->data['geo_zones'][] = array(
				'geo_zone_id' => $result['geo_zone_id'],
				'name'        => $result['name'],
				'description' => $result['description'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['geo_zone_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
	
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_description'] = $this->language->get('column_description');
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

		$this->template = 'localisation/geo_zone_list.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_geo_zone'] = $this->language->get('button_add_geo_zone');
		$this->data['button_remove'] = $this->language->get('button_remove');
				
		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['token'] = $this->session->data['token'];

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

 		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
		
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

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'localisation/geo_zone&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		if (!isset($this->request->get['geo_zone_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'localisation/geo_zone/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'localisation/geo_zone/update&token=' . $this->session->data['token'] . '&geo_zone_id=' . $this->request->get['geo_zone_id'] . $url;
		}

		$this->data['cancel'] = HTTPS_SERVER . 'localisation/geo_zone&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['geo_zone_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($this->request->get['geo_zone_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($geo_zone_info)) {
			$this->data['name'] = $geo_zone_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($geo_zone_info)) {
			$this->data['description'] = $geo_zone_info['description'];
		} else {
			$this->data['description'] = '';
		}
		
		$this->load->model('localisation/country');
		 
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		if (isset($this->request->post['zone_to_geo_zone'])) {
			$this->data['zone_to_geo_zones'] = $this->request->post['zone_to_geo_zone'];
		} elseif (isset($this->request->get['geo_zone_id'])) {
			$this->data['zone_to_geo_zones'] = $this->model_localisation_geo_zone->getZoneToGeoZones($this->request->get['geo_zone_id']);
		} else {
			$this->data['zone_to_geo_zones'] = array();
		}
		
		$this->template = 'localisation/geo_zone_form.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((strlen(utf8_decode($this->request->post['description'])) < 3) || (strlen(utf8_decode($this->request->post['description'])) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('localisation/tax_class');

		foreach ($this->request->post['selected'] as $geo_zone_id) {
			$tax_rate_total = $this->model_localisation_tax_class->getTotalTaxRatesByGeoZoneId($geo_zone_id);

			if ($tax_rate_total) {
				$this->error['warning'] = sprintf($this->language->get('error_tax_rate'), $tax_rate_total);
			}
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function zone() {
		$output = '<option value="0">' . $this->language->get('text_all_zones') . '</option>';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if ($this->request->get['zone_id'] == $result['zone_id']) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
	} 		
}
?>