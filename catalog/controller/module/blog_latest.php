<?php

class ControllerModuleBlogLatest extends Controller {

    protected function index() {
		$this->language->load('module/blog_latest');
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('tool/image');

		$this->data['class'] = 'col-md-6';
		$limit = $this->config->get('blog_latest_limit');
		$category_id = false;
		if(isset($this->request->get['params'])) {
			if(isset($this->request->get['params']['class'])) {
				$this->data['class'] = $this->request->get['params']['class'];
			}
			if(isset($this->request->get['params']['limit'])) {
				$limit = $this->request->get['params']['limit'];
			}			
			if(isset($this->request->get['params']['category_id'])) {
				$category_id = $this->request->get['params']['category_id'];
			}
		}

		$results = Make::a('catalog/blog_post')->create()->getLatestBlogs($limit,$category_id);
		foreach ($results as $result) {

			if (isset($result['thumb']) && $result['thumb'] && file_exists(DIR_IMAGE . $result['thumb'])) {
				$image = $result['thumb'];
		    } else {
				$image = 'no_image.jpg';
		    }

		    $this->data['blogs'][] = array(
				'blog_post_id' => $result['blog_post_id'],
				'title' => $result['title'],
				'image' => $this->model_tool_image->resize($image, $this->config->get('config_image_blog_width'), 
					$this->config->get('config_image_blog_height')),
				'href' => makeUrl('blog/post', array('blog_post_id=' . $result['blog_post_id']), true),
				'alt_title' => QS::getMetaLink($result['meta_title'], $result['name']),
		    );
		}

		$this->id = 'blog_latest';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog_latest.tpl')) {
		    $this->template = $this->config->get('config_template') . '/template/module/blog_latest.tpl';
		} else {
		    $this->template = 'default/template/module/blog_latest.tpl';
		}
		$this->render();
    }
}

?>