<?php   
class ControllerErrorNotFound extends Controller {
	public function index() {		
		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

		$this->language->load('error/not_found');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->document->breadcrumbs = array();
 
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => $this->language->get('text_separator')
      	);

		if (isset($this->request->get['act'])) {
       		$this->document->breadcrumbs[] = array(
        		'href'      => HTTP_SERVER . '' . $this->request->get['act'],
        		'text'      => __('Not Found'),
        		'separator' => FALSE
      		);
		}

		$this->data['404_image'] = '404.jpg'//HTTP_IMAGE . "404.jpg";
		$this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['home'] = makeUrl('common/home',array(),true);
        $this->data['account'] = makeUrl('account/account',array(),true,true);
		
		$this->data['text_error'] = $this->language->get('text_error');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
		} else {
			$this->template = 'default/template/error/not_found.tpl';
		}
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));		
  	}
}
?>