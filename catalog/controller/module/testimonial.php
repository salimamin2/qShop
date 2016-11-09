<?php  
class ControllerModuleTestimonial extends Controller {
	protected function index() {
		$this->language->load('module/testimonial');

      	$this->data['heading_title'] = $this->language->get('heading_title');
      	$this->data['text_more'] = $this->language->get('text_more');
		
		$this->load->model('catalog/testimonial');
		
		$this->data['testimonials'] = array();
		
		$results = $this->model_catalog_testimonial->getTestimonials(0, $this->config->get('testimonial_limit'));
		if($results){
                    foreach ($results as $result) {

                            $this->data['testimonials'][0] = array(
                                    'title'			=> $result['title'],
                                    'description'	=> html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
                            );
                    }
                }
		
		$this->data['more'] = (HTTP_SERVER . 'product/testimonial');

		$this->id = 'testimonial';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/testimonial.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/testimonial.tpl';
		} else {
			$this->template = 'default/template/module/testimonial.tpl';
		}
		
		$this->render();
	}
}
?>