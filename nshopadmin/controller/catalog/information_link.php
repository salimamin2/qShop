<?php
class ControllerCatalogInformationLink extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/information_link');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('catalog/information_link');

		$this->getList();
	}

	public function delete() {
		$this->load->language('catalog/information_link');

		$this->load->model('catalog/information_link');

		if (isset($this->request->post['link_id'])) {

            $bResult = $this->model_catalog_information_link->deleteLink($this->request->post['link_id']);

            if(!$bResult){
                $data['success'] = __('text_deleted');
            } else {
                $data['error'] = __('error_delete');
            }
        }
        $this->response->setOutput(json_encode($data));
	}

	private function getList() {

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            foreach($this->request->post['link'] as $data){
              if($data['information_id'] != 0){
                  $data['link'] = 'information/information&information_id='.$data['information_id'];
              }
              if($data['id']){
                 $this->model_catalog_information_link->editLink($data['id'], $data);
              } else {
			    $id = $this->model_catalog_information_link->addLink($data);
              }
            }
			    $this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'catalog/information_link&token=' . $this->session->data['token']);
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'catalog/information_link&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['action'] = HTTPS_SERVER . 'catalog/information_link&token=' . $this->session->data['token'];
		$this->data['delete'] = HTTPS_SERVER . 'catalog/information_link/delete&token=' . $this->session->data['token'];

        $this->data['aBlocks'] = array(
                'top-menu' => 'Header Menu',
                'navigation' => 'Footer Navigation',
                'help' => 'Footer Help',
                'product' => 'Product Description',
                'account' => 'Create Account',
                'checkout' => 'Checkout'
                );

        $this->data['aResults'] = $this->model_catalog_information_link->getLists();

        $this->data['aInformations'] = array();

        $this->load->model('catalog/information');

		$results = $this->model_catalog_information->getInformations();

    	foreach ($results as $result) {

			$this->data['aInformations'][] = array(
				'information_id' => $result['information_id'],
				'title'      => $result['title'],
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
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

		$this->template = 'catalog/information_link_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/information_link')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['link'] as $i=>$value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 32)) {
				$this->error['title'][$i] = $this->language->get('error_title');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

}
?>