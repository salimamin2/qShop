<?php

class ControllerProductTestimonial extends Controller {

    private $page_limit = 12;

    public function index() {
	$this->language->load('product/testimonial');

	$this->load->model('catalog/testimonial');
	$this->load->model('tool/seo_url');

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => (HTTP_SERVER . 'common/home'),
	    'text' => $this->language->get('text_home'),
	    'separator' => $this->language->get('text_separator')
	);

	$testimonial_total = $this->model_catalog_testimonial->getTotalTestimonials();

	if ($testimonial_total) {

	    $this->document->title = $this->language->get('heading_title');

	    $this->document->breadcrumbs[] = array(
		'href' => (HTTP_SERVER . 'product/testimonial'),
		'text' => $this->language->get('heading_title'),
		'separator' => FALSE
	    );

	    $this->data['heading_title'] = $this->language->get('heading_title');

	    $this->data['button_continue'] = $this->language->get('button_continue');

	    $this->data['continue'] = (HTTP_SERVER . 'common/home');


	    if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
	    } else {
		$page = 1;
	    }

	    $this->data['testimonials'] = array();

	    $results = $this->model_catalog_testimonial->getTestimonials(($page - 1) * $this->page_limit, $this->page_limit);
	    if ($results) {
		foreach ($results as $result) {

		    $this->data['testimonials'][] = array(
			'title' => $result['title'],
			'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
		    );
		}
	    }

	    $url = '';

	    if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	    }

	    /*$pagination = new Pagination();
	    $pagination->total = $testimonial_total;
	    $pagination->page = $page;
	    $pagination->limit = $this->page_limit;
	    $pagination->text = $this->language->get('text_pagination');
        $pagination->url = makeUrl('product/testimonial', $aUrl, true) . '&page={page}';
        $this->data['pagination'] = $pagination->render();*/


         $pagination = new Pagination();
        $pagination->total = $testimonial_total;
        $pagination->page = $page;
        $pagination->limit = $limit; //$this->config->get('config_catalog_limit');
        $pagination->text = null;
        $pagination->url = makeUrl('product/testimonial', $aUrl, true) . '&amp;page={page}';
        $pagination->enable_np = true;
        $pagination->num_links = 10000;
        $pagination->list_type = "ol";
        $pagination->wrapper = false;
        $pagination->active_class = "current";
        $pagination->active_wrapper = false;
        $pagination->prev_class = "previous";
        $pagination->next_links = "next i-next";
        $pagination->prev_links = "previous i-previous";
        $this->data['pagination'] = $pagination->render();


	    $this->data['heading_title'] = __('Testimonials');
	    $this->document->title = $this->data['heading_title'];
	    $this->data['you_are_here'] = $this->language->get('you_are_here');
	    $this->data['breadcrumbs'] = $this->document->breadcrumbs;
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/testimonial.tpl')) {
		$this->template = $this->config->get('config_template') . '/template/product/testimonial.tpl';
	    } else {
		$this->template = 'default/template/product/testimonial.tpl';
	    }

	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
	} else {

	    $this->document->breadcrumbs[] = array(
		'href' => (HTTP_SERVER . 'testimonial/testimonial&testimonial_id=' . $this->request->get['testimonial_id']),
		'text' => $this->language->get('text_error'),
		'separator' => FALSE
	    );

	    $this->document->title = $this->language->get('text_error');

	    $this->data['heading_title'] = $this->language->get('text_error');

	    $this->data['text_error'] = $this->language->get('text_error');

	    $this->data['button_continue'] = $this->language->get('button_continue');

	    $this->data['continue'] = (HTTP_SERVER . 'common/home');
	    $this->data['you_are_here'] = $this->language->get('you_are_here');
	    $this->data['breadcrumbs'] = $this->document->breadcrumbs;
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
		$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
	    } else {
		$this->template = 'default/template/error/not_found.tpl';
	    }

	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
    }

}

?>