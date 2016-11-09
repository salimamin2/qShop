<?php
class ControllerCatalogNews extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/news');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('catalog/news');
		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/news');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('catalog/news');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_news->addNews($this->request->post);
			if ($this->config->get('config_seo_url')) {
				$this->load->model('tool/seo_url');
				$this->model_tool_seo_url->generate();
			}
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
			$this->redirect(HTTPS_SERVER . 'catalog/news' . $url);
		}
		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/news');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('catalog/news');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_news->editNews($this->request->get['news_id'], $this->request->post);
			if ($this->config->get('config_seo_url')) {
				$this->load->model('tool/seo_url');
				$this->model_tool_seo_url->generate();
			}
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
			$this->redirect(HTTPS_SERVER . 'catalog/news' . $url);
		}
		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/news');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('catalog/news');
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $news_id) {
				$this->model_catalog_news->deleteNews($news_id);
			}
			if ($this->config->get('config_seo_url')) {
				$this->load->model('tool/seo_url');
				$this->model_tool_seo_url->generate();
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
			$this->redirect(HTTPS_SERVER . 'catalog/news' . $url);
		}
		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'nd.title';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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
     		'href'      => HTTPS_SERVER . 'common/home',
     		'text'      => $this->language->get('text_home'),
     		'separator' => FALSE
  		);
  		$this->document->breadcrumbs[] = array(
     		'href'      => HTTPS_SERVER . 'catalog/news' . $url,
     		'text'      => $this->language->get('heading_title'),
     		'separator' => ' :: '
  		);
		$this->data['insert'] = HTTPS_SERVER . 'catalog/news/insert' . $url;
		$this->data['delete'] = HTTPS_SERVER . 'catalog/news/delete' . $url;
		$this->data['news'] = array();
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		$news_total = $this->model_catalog_news->getTotalNews();
		$results = $this->model_catalog_news->getNewsLimited($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'catalog/news/update&news_id=' . $result['news_id'] . $url
			);
			$this->data['news'][] = array(
				'news_id'     => $result['news_id'],
				'title'       => $result['title'],
				'status'		  => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'delete'   => isset($this->request->post['delete']) && in_array($result['news_id'], $this->request->post['delete']),
				'action'      => $action
			);
		}
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_status'] = $this->language->get('column_status');
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
		
		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['sort_title'] = HTTPS_SERVER . 'catalog/news&sort=nd.title' . $url;
		$this->data['sort_sort_order'] = HTTPS_SERVER . 'catalog/news&sort=n.sort_order' . $url;
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $news_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'catalog/news' . $url . '&page=%s';
		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'catalog/news_list.tpl';

		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_fullsize'] = $this->language->get('text_fullsize');
    	$this->data['text_thumbnail'] = $this->language->get('text_thumbnail');
		$this->data['text_upload'] = $this->language->get('text_upload');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_image_size'] = $this->language->get('entry_image_size');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_image'] = $this->language->get('tab_image');
	if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
  		$this->document->breadcrumbs = array();
  		$this->document->breadcrumbs[] = array(
     		'href'      => HTTPS_SERVER . 'common/home',
     		'text'      => $this->language->get('text_home'),
     		'separator' => FALSE
  		);
  		$this->document->breadcrumbs[] = array(
     		'href'      => HTTPS_SERVER . 'catalog/news',
     		'text'      => $this->language->get('heading_title'),
     		'separator' => ' :: '
  		);
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
		if (!isset($this->request->get['news_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'catalog/news/insert' . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'catalog/news/update&news_id=' . $this->request->get['news_id'] . $url;
		}
		$this->data['cancel'] = HTTPS_SERVER . 'catalog/news' . $url;
		if ((isset($this->request->get['news_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$news_info = $this->model_catalog_news->getNewsStory($this->request->get['news_id']);
		}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->request->post['news_description'])) {
			$this->data['news_description'] = $this->request->post['news_description'];
		} elseif (isset($this->request->get['news_id'])) {
			$this->data['news_description'] = $this->model_catalog_news->getNewsDescriptions($this->request->get['news_id']);
		} else {
			$this->data['news_description'] = array();
		}
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} else {
			$this->data['keyword'] = "";
		}
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		}  elseif (isset($news_info)) {
			$this->data['status'] = $news_info['status'];
		} else {
      		$this->data['status'] = '';
    	}
		
		/*if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($news_info)) {
			$this->data['image'] = $news_info['image'];
		} else {
      		$this->data['image'] = '';
    	}
		

		$this->load->helper('image');

		if (isset($this->request->post['image'])) {
			$this->data['preview'] = image_resize($this->request->post['image'], 100, 75);
		} elseif (isset($news_info)) {
			$this->data['preview'] = image_resize($news_info['image'], 100, 75);
		} else {
			$this->data['preview'] = image_resize('no_image.jpg', 100, 75);
		}
		if (isset($this->request->post['image_size'])) {
			$this->data['image_size'] = $this->request->post['image_size'];
		} elseif (isset($news_info)) {
			$this->data['image_size'] = $news_info['image_size'];
		} else {
      		$this->data['image_size'] = '';
    	}
		*/
		//$this->id       = 'content';
		$this->template = 'catalog/news_form.tpl';

 		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		foreach ($this->request->post['news_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) >100)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/news')) {
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
