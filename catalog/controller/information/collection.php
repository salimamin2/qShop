<?php

class ControllerInformationCollection extends Controller {

	public function index() {
		$this->language->load('information/collection');
		$this->load->model('catalog/collection');
		$this->load->model('tool/image');
		
		$this->document->breadcrumbs = array( );
		$this->document->breadcrumbs[] = array(
			'href' => HTTP_SERVER . 'index.php?route=common/home',
			'text' => $this->language->get('text_home'),
			'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
			'href' => HTTP_SERVER . 'index.php?route=information/collection',
			'text' => $this->language->get('heading_title'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->document->title = $this->language->get('heading_title');
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$collections = $this->model_catalog_collection->getCollections();
		
		/* Latest Collection */
		$collection_info = $collections[0];
		
		$this->data['title'] = $collection_info['title'];
		$this->data['description'] = html_entity_decode($collection_info['description'], ENT_QUOTES, 'UTF-8');
		
		if ($collection_info['image'] && file_exists(DIR_IMAGE.$collection_info['image'])) {
			$image = $collection_info['image'];
		} else {
			$image = 'no_image.jpg';
		}
		$this->data['image'] = $this->model_tool_image->resize($image,806,599);
		
		$this->data['media_type'] = $collection_info['media_type'];
		
		$this->data['medias'] = array();
		$medias = $this->model_catalog_collection->getCollectionMedias($collection_info['collection_id'], $collection_info['media_type']);
		foreach($medias as $media) {
			if($media['type'] == 'image'){
				if ($media['media'] && file_exists(DIR_IMAGE.$media['media'])) {
					$image = $media['media'];
				} else {
					$image = 'no_image.jpg';
				}
				
				$this->data['medias'][] = array(
					'collection_media_id' => $media['collection_media_id'],
					'media' => $this->model_tool_image->resize($image,806,599)
				);
			} elseif($media['type'] == 'video'){
				$this->data['medias'] = array(
					'collection_media_id' => $media['collection_media_id'],
					'media' => html_entity_decode($media['media'], ENT_QUOTES, 'UTF-8')
				);
			}
		}
		//d($this->data['medias']['media']);
		
		/* All Collections List */
		$this->data['collections'] = array();
		foreach($collections as $result) {
			if ($result['image'] && file_exists(DIR_IMAGE.$result['image'])) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$this->data['collections'][] = array(
				'id' => $result['collection_id'],
				'title' => $result['title'],
				'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'image' => $this->model_tool_image->resize($image,398,323)
			);
		}
		
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/collection.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/collection.tpl';
		} else {
			$this->template = 'default/template/information/collection.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		
	}
	
	public function getCollection() {
		$this->language->load('information/collection');
		$this->load->model('catalog/collection');
		$this->load->model('tool/image');
		
		if (isset($this->request->get['collection_id'])) {
			$collection_id = $this->request->get['collection_id'];
		} else {
			$collection_id = 0;
		}
		
		$collection_info = $this->model_catalog_collection->getCollection($collection_id);
		if ($collection_info) {
		
			$this->data['title'] = $collection_info['title'];
			$this->data['description'] = html_entity_decode($collection_info['description'], ENT_QUOTES, 'UTF-8');
			
			if ($collection_info['image'] && file_exists(DIR_IMAGE.$collection_info['image'])) {
				$image = $collection_info['image'];
			} else {
				$image = 'no_image.jpg';
			}
			$this->data['image'] = $this->model_tool_image->resize($image,806,599);
			
			$this->data['media_type'] = $collection_info['media_type'];
			
			$this->data['medias'] = array();
			$medias = $this->model_catalog_collection->getCollectionMedias($collection_id, $collection_info['media_type']);
			foreach($medias as $media) {
				if($media['type'] == 'image'){
					if ($media['media'] && file_exists(DIR_IMAGE.$media['media'])) {
						$image = $media['media'];
					} else {
						$image = 'no_image.jpg';
					}
					
					$this->data['medias'][] = array(
						'collection_media_id' => $media['collection_media_id'],
						'media' => $this->model_tool_image->resize($image,806,599)
					);
				} elseif($media['type'] == 'video'){
					$this->data['medias'] = array(
						'collection_media_id' => $media['collection_media_id'],
						'media' => html_entity_decode($media['media'], ENT_QUOTES, 'UTF-8')
					);
				}
			}
		}

		$this->response->setOutput(json_encode($this->data));
	}
}
?>