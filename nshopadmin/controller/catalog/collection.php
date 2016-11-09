<?php
class ControllerCatalogCollection extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('catalog/collection');

		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('catalog/collection');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/collection');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/collection');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_collection->addCollection($this->request->post);
			
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/collection');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/collection');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_collection->editCollection($this->request->get['collection_id'], $this->request->post);
			
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('catalog/collection');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/collection');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $collection_id) {
				$this->model_catalog_collection->deleteCollection($collection_id);
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . $url);
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
			$sort = 'fd.title';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/collection/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/collection/delete&token=' . $this->session->data['token'] . $url;	

		$this->data['collections'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$collection_total = $this->model_catalog_collection->getTotalCollections();
	
		$results = $this->model_catalog_collection->getCollections($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/collection/update&token=' . $this->session->data['token'] . '&collection_id=' . $result['collection_id'] . $url
			);
						
			$this->data['collections'][] = array(
				'collection_id' => $result['collection_id'],
				'title'      => $result['title'],
				'sort_order' => $result['sort_order'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['collection_id'], $this->request->post['selected']),
				'action'     => $action
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title'] = HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . '&sort=fd.title' . $url;
		$this->data['sort_sort_order'] = HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . '&sort=f.sort_order' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $collection_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'catalog/collection_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->load->model('tool/image');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_media'] = $this->language->get('entry_media');
		$this->data['entry_media_type'] = $this->language->get('entry_media_type');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_video'] = $this->language->get('entry_video');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_media'] = $this->language->get('tab_media');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_add_video'] = $this->language->get('button_add_video');
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->data['token'] = $this->session->data['token'];

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
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['collection_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/collection/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/collection/update&token=' . $this->session->data['token'] . '&collection_id=' . $this->request->get['collection_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=catalog/collection&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['collection_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$collection_info = $this->model_catalog_collection->getCollection($this->request->get['collection_id']);
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['collection_description'])) {
			$this->data['collection_description'] = $this->request->post['collection_description'];
		} elseif (isset($this->request->get['collection_id'])) {
			$this->data['collection_description'] = $this->model_catalog_collection->getCollectionDescriptions($this->request->get['collection_id']);
		} else {
			$this->data['collection_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($collection_info)) {
			$this->data['status'] = $collection_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['collection_store'])) {
			$this->data['collection_store'] = $this->request->post['collection_store'];
		} elseif (isset($collection_info)) {
			$this->data['collection_store'] = $this->model_catalog_collection->getCollectionStores($this->request->get['collection_id']);
		} else {
			$this->data['collection_store'] = array(0);
		}		
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($collection_info)) {
			$this->data['keyword'] = $collection_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($collection_info)) {
			$this->data['sort_order'] = $collection_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		if (isset($this->request->post['media_type'])) {
			$this->data['media_type'] = $this->request->post['media_type'];
		} elseif (isset($collection_info)) {
			$this->data['media_type'] = $collection_info['media_type'];
		} else {
			$this->data['media_type'] = '';
		}
		
		if (isset($this->request->post['image'])) {
            $this->data['image'] = $this->request->post['image'];
        } elseif (isset($collection_info)) {
            $this->data['image'] = $collection_info['image'];
        } else {
            $this->data['image'] = '';
        }
		
		if (isset($collection_info) && $collection_info['image'] && file_exists(DIR_IMAGE . $collection_info['image'])) {
            $this->data['preview'] = $this->model_tool_image->resize($collection_info['image'], 100, 100);
        } else {
            $this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }
		
		$this->data['collection_medias'] = array();
		if (isset($this->request->post['collection_media'])) {
			$collection_medias_results = $this->request->post['collection_media'];
		} elseif (isset($collection_info)) {
            $collection_medias_results = $this->model_catalog_collection->getCollectionMedias($this->request->get['collection_id']);
        } else {
			$collection_medias_results = array();
		}
		
		foreach ($collection_medias_results as $result) {
			if($result['type'] == 'image'){
				if ($result['media'] && file_exists(DIR_IMAGE . $result['media'])) {
					$image = $result['media'];
				} else {
					$image = 'no_image.jpg';
				}
				$this->data['collection_medias'][$result['type']][] = array(
					'preview' => $this->model_tool_image->resize($image, 100, 100),
					'media' => $result['media'],
					'type' => $result['type'],
					'sort_order' => $result['sort_order']
				);
			} elseif($result['type'] == 'video'){
				$this->data['collection_medias'][$result['type']] = array(
					'preview' => '',
					'media' => $result['media'],
					'type' => $result['type'],
					'sort_order' => $result['sort_order']
				);
			}
		}

		$this->template = 'catalog/collection_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/collection')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['collection_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen(utf8_decode($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
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

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/collection')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		
		foreach ($this->request->post['selected'] as $collection_id) {
			if ($this->config->get('config_account_id') == $collection_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			
			if ($this->config->get('config_checkout_id') == $fashion_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}
			
			$store_total = $this->model_setting_store->getTotalStoresByCollectionId($collection_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>