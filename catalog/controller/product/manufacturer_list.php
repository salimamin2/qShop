<?php

class ControllerProductManufacturerList extends Controller {

    public function index() {
		$this->language->load('product/manufacturer');
		$this->load->model('tool/image');

		$this->data['you_are_here'] = $this->language->get('you_are_here');
		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
		    'href' => HTTP_SERVER . 'common/home',
		    'text' => $this->language->get('text_home'),
		    'separator' => $this->language->get('text_separator')
		);

		$this->document->breadcrumbs[] = array(
		    'href' => HTTP_SERVER . 'product/manufacturer_list',
		    'text' => __('Designers'),
		    'separator' => $this->language->get('text_separator')
		);

		$aCategories = array( 
			1 => array(
				'name' => 'deLab Designers', 
				'information_id' => 74),
				
		    2 => array(
				 'name' => 'deLuxe Designers',
				 'information_id' => 75)		    	 

				);
		
		$category_id = 0;
		$sCategory = __('Designers');
		if(isset($this->request->get['manufacturer_cat_id'])) {
			$category_id = $this->request->get['manufacturer_cat_id'];
			$sCategory = $aCategories[$category_id]['name'];
			$this->data['information_id'] = $aCategories[$category_id]['information_id'];
		}

		$this->data['heading_title'] = $sCategory;
		$this->document->title = $this->data['heading_title'];
		$manufacturer_lists = Make::a('catalog/manufacturer')->create()->getAllManufacturers($category_id);
		//d(array(ORM::get_last_query(),$manufacturer_lists));
		$this->data['manufacturers'] = array();
	    foreach ($manufacturer_lists as $result) {
			if (isset($result['image'])) {
			    $image = $result['image'];
			} else {
			    $image = 'no_image.jpg';
			}

			$total_products = Make::a('catalog/manufacturer')->create()->getTotalProductsByManufacturerId($result['manufacturer_id']);

			$this->data['manufacturers'][] = array(
			    'manufacturer_id' => $result['manufacturer_id'],
			    'name' => $result['name'],
			    'image_url' => $result['image'],
			    'facebook' => $result['facebook'],
			    'twitter' => $result['twitter'],
			    'total_products' => $total_products,
			    'href' => makeUrl('product/manufacturer',array('manufacturer_id=' . $result['manufacturer_id']), true)
			);
	    }

	    //$this->data['breadcrumbs'] = $this->document->breadcrumbs;
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/manufacturer_list.tpl';
	    } else {
			$this->template = 'default/template/product/manufacturer_list.tpl';
	    }
	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>