<?php
class ControllerReportPurchased extends Controller { 
	public function index() {   
		$this->load->language('report/purchased');

		$this->document->title = $this->language->get('heading_title');


        if (isset($this->request->get['filter_country_id'])) {
            $filter_country_id = $this->request->get['filter_country_id'];
        } else {
            $filter_country_id = 0;
        }
        if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        if (isset($this->request->get['filter_country_id'])) {
            $url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
        }

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'report/purchased&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
        $data = array(
            'filter_country_id' => $filter_country_id,
            'start'                  => ($page - 1) * 10,
            'limit'                  => 10
        );
		$this->load->model('report/purchased');
        $this->load->model('localisation/country');

        $this->data['countries'] = $this->model_localisation_country->getCountries();


        $product_total = $this->model_report_purchased->getTotalOrderedProducts($data);
		
		$this->data['products'] = array();

		$results = $this->model_report_purchased->getProductPurchasedReport(($page - 1) * $this->config->get('config_admin_limit'), $this->config->get('config_admin_limit'),$data);
		foreach ($results as $result) {
			$this->data['products'][] = array(
				'name'     => $result['name'],
				'model'    => $result['model'],
				'quantity' => $result['quantity'],
				'total'    => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_total'] = $this->language->get('column_total');

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
        $pagination->style_links = "dataTables_paginate paging_bootstrap";
        $pagination->style_results = "dataTables_info";
        $pagination->list_class = "pagination pagination-sm";
        $pagination->links_wrapper = true;
        $pagination->results_wrapper = true;
        $pagination->wrapper_class = "col-md-6";
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'report/purchased&token=' . $this->session->data['token'] . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->template = 'report/purchased.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}	
}
?>