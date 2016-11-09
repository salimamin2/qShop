<?php
class ControllerModuleNews extends Controller {
	protected function index() {
		$this->load->language('module/news');
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	$this->data['text_read_more'] = $this->language->get('text_read_more');
            	$this->data['text_news_more'] = $this->language->get('text_news_more');

        $this->data['news_info'] = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'information/news');
		$this->load->model('catalog/news');
		$this->load->model('tool/seo_url');
		$this->data['news'] = array();
		$results = $this->model_catalog_news->getNews($this->config->get('news_limit'));
		foreach ($results as $result) {
                   $newsDate=date('l - F d, Y',strtotime($result['date_added']));
    		$this->data['news'][] = array(
                    'date' => $newsDate,
		    'short_description' => explode('.', html_entity_decode($result['description'])),
	    	    'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'information/news&news_id=' . $result['news_id'])
     		);
    	}
                $this->data['homepage'] = '';
		if ($this->config->get('news_position') == 'homepage') {
			$this->data['homepage'] = 'TRUE';
		}
		$this->id       = 'news';
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/news.tpl';
		} else {
			$this->template = 'default/template/module/news.tpl';
		}
		$this->render();
	}
}
?>
