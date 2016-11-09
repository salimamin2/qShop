<?php

class ControllerCatalogProduct extends Controller {

    private $error = array();
    protected $aStatus = array();
    protected $model;

    public function _pre() {
	$this->load->language('catalog/product');
	$this->setModel(Make::a('catalog/product'));
	$this->aStatus = array(__('text_disabled'), __('text_enabled'));
	$this->document->title = __('heading_title');
	switch ($this->getMethod()) {
	    case 'update':
		$this->model = $this->getModel()->find_one($this->request->get[$this->getModel()->get_id_column_name()]);
		break;
	    case 'delete':
		if (isset($this->request->get['id'])) {
		    $this->model = $this->getModel()->find_one($this->request->get[$this->getModel()->get_id_column_name()]);
		    break;
		}
	    default:
		$this->model = $this->getModel()->create();
	}
    }

    public function index() {
	    $this->getList();
    }

    public function insert() {

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
        $product_id = $this->model->addProduct($this->request->post);
        if(is_array($product_id) && isset($product_id['error'])) {
        	$this->error['warning'] = $product_id['error'];
        }
        else {
            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->get['continue'])) {
                if ($this->request->get['continue'] == 1) {
                    $this->redirect(makeUrl('catalog/product/update',array('product_id=' . $product_id)));
                } else {
                    $this->redirect(makeUrl('catalog/product_option',array('product_id=' . $product_id)));
                }
            } else {
                $this->redirect(makeUrl('catalog/product'));
            }
        }
	}

	$this->getForm();
    }

    public function update() {

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

	    $mResult = $this->model->editProduct($this->request->get['product_id'], $this->request->post);
        if(!is_array($mResult)) {
            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->get['continue'])) {
                if ($this->request->get['continue'] == 1) {
                    $this->redirect(makeUrl('catalog/product/update',array('product_id=' . $this->request->get['product_id'])));
                } else {
                    $this->redirect(makeUrl('catalog/product_option',array('product_id=' . $this->request->get['product_id'])));
                }
            } else {
                $this->redirect(makeUrl('catalog/product'));
            }
        }
	}
	$this->getForm();
    }

    public function delete() {

	if (isset($this->request->post['selected']) && $this->validateDelete()) {
	    foreach ($this->request->post['selected'] as $product_id) {
		    $oProduct = Make::a('catalog/product')->find_one($product_id);
            if($oProduct !== FALSE) {
                $oProduct->delete();
            }
	    }

	    $this->session->data['success'] = $this->language->get('text_success');
        $this->redirect(makeUrl('catalog/product'));
	}

	$this->getList();
    }

    public function copy() {

	if (isset($this->request->post['selected']) && $this->validateCopy()) {
	    foreach ($this->request->post['selected'] as $product_id) {
		    $result = $this->model->copyProduct($product_id);
	    }

	    $this->session->data['success'] = __('You have successfully copied product');

	    $url = '';

	    if (isset($this->request->get['type'])) {
		$url .= '&type=' . $this->request->get['type'];
	    }
	    if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . $this->request->get['filter_name'];
	    }

	    if (isset($this->request->get['filter_model'])) {
		$url .= '&filter_model=' . $this->request->get['filter_model'];
	    }

	    if (isset($this->request->get['filter_quantity'])) {
		$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
	    }

	    if (isset($this->request->get['filter_status'])) {
		$url .= '&filter_status=' . $this->request->get['filter_status'];
	    }

	    if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	    }

	    if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
	    }

	    if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
	    }

	    $this->redirect(HTTPS_SERVER . 'catalog/product&token=' . $this->session->data['token'] . $url);
	}

	$this->getList();
    }

    private function getList() {

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'catalog/product&token=' . $this->session->data['token'],
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->data['insert'] = makeUrl('catalog/product/insert');
	$this->data['copy'] = makeUrl('catalog/product/copy');
	$this->data['delete'] = makeUrl('catalog/product/delete');
	$this->data['sProductList'] = makeUrl('catalog/product/ajaxList');

	$this->data['products'] = array();

	$this->load->model('tool/image');
	$this->load->model('catalog/product_type'); //$this->model_catalog_product_type->getList();

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_enabled'] = $this->language->get('text_enabled');
	$this->data['text_disabled'] = $this->language->get('text_disabled');
	$this->data['text_no_results'] = $this->language->get('text_no_results');
	$this->data['text_image_manager'] = $this->language->get('text_image_manager');

	$this->data['column_image'] = $this->language->get('column_image');
	$this->data['column_id'] = $this->language->get('column_id');
	$this->data['column_name'] = $this->language->get('column_name');
	$this->data['column_model'] = $this->language->get('column_model');
	$this->data['column_quantity'] = $this->language->get('column_quantity');
	$this->data['column_status'] = $this->language->get('column_status');
	$this->data['column_action'] = $this->language->get('column_action');
	$this->data['column_code'] = $this->language->get('column_code');

	$this->data['button_copy'] = $this->language->get('button_copy');
	$this->data['button_insert'] = $this->language->get('button_insert');
	$this->data['button_delete'] = $this->language->get('button_delete');
	$this->data['button_filter'] = $this->language->get('button_filter');

	$this->data['token'] = $this->session->data['token'];

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} elseif (isset($this->session->data['warning'])) {
	    $this->data['error_warning'] = $this->session->data['warning'];
	    unset($this->session->data['warning']);
	} else {
	    $this->data['error_warning'] = '';
	}

	if (isset($this->session->data['success'])) {
	    $this->data['success'] = $this->session->data['success'];

	    unset($this->session->data['success']);
	} else {
	    $this->data['success'] = '';
	}

	$this->document->addScriptInline('var sCategoryAction = "'.makeUrl('catalog/product/getCategories').'";');

	$this->template = 'catalog/product_list.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function getCategories(){
    	if(!isset($this->request->get['keyword']) && $this->request->get['keyword'] != ''){
    		echo json_encode(array());
    		exit();
    	}
    	$oModel = Make::a('catalog/category')->create();
    	$aCategories = $oModel->getCategoriesByKeyword('');
    	//d(ORM::get_last_query());
    	$aResults = array();
    	foreach ($aCategories as $aCategory) {
		    $aResults[] = array('id' => $aCategory['category_id'], 'name' => Make::a('catalog/category')->create()->getPath($aCategory['category_id']));
		}
		echo json_encode($aResults);
		exit();
    }

    public function ajaxList() {
		$this->load->language('catalog/product');

		$aStatus = array(__('text_disabled'), __('text_enabled'));
		$columns = array(
		    array('db' => 'p.product_id', 'dt' => 0, 'formatter' => function($d, $row) {
		    	return '<input type="checkbox" value="' . $row['product_id'] . '" name="selected[]" />';
			}),
			array('db' => 'p.product_id','dt' => 1,'formatter' => function($d,$row) {
				return $row['product_id'];
			}),
		    array('db' => 'p.image', 'dt' => 2,
				'formatter' => function($d, $row) {
			    	return '<img src="' . HTTPS_IMAGE . $row['image'] . '" width="100" height="100" />';
			}),
		    array('db' => 'pd.name', 'dt' => 3, 'formatter' => function($d,$row){
		    	return $row['name'];
		    }),
		    array('db' => 'p.model', 'dt' => 4, 'formatter' => function($d,$row){
		    	return $row['model'];
		    }),
		    array('db' => 'p.manufacturer_id', 'dt' => 5, 'formatter' => function($d, $row){
	    		return $row['manufacturer'];
		    }),
		    array('db' => 'p.quantity', 'dt' => 6, 'formatter' => function($d, $row){
	    		return $row['quantity'];
		    }),
		    array('db' => 'p.status', 'dt' => 7, 'formatter' => function($d, $row) {
		    	return ($row['status'] == 1 ? __('text_enabled') : __('text_disabled'));
			}),
		    array('db' => 'pd.product_id', 'dt' => 8,
				'formatter' => function($d, $row) {
		    		return '<a class="btn btn-info btn-sm" href="' . makeUrl('catalog/product/update', array('product_id=' . $row['product_id'])) . '"><i class="icon-pencil"></i></a>';
			}),
		    array('db' => 'c.category_id', 'dt' => 9,
				'formatter' => function($d, $row) {
			   		return $row['category_id'];
			})
		);

		$oModel = Make::a('catalog/product')->table_alias('p')
			->select('pd.*')
			->select('p.*')
			->select('m.name','manufacturer')
			->select_expr('GROUP_CONCAT(c.category_id)','category_id')
			->left_outer_join('product_description', 'p.product_id = pd.product_id', 'pd')
			->left_outer_join('product_to_category', 'c.product_id = p.product_id', 'c')
			->left_outer_join('manufacturer', 'm.manufacturer_id = p.manufacturer_id', 'm')
			->where('pd.language_id', (int) $this->config->get('config_language_id'))
			->group_by('p.product_id');

		echo json_encode(
			QS::DT_simple($this->request->get, $oModel, $columns, 'catalog/product', true)
		);
    }

    private function getForm() {

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_enabled'] = $this->language->get('text_enabled');
	$this->data['text_disabled'] = $this->language->get('text_disabled');
	$this->data['text_none'] = $this->language->get('text_none');
	$this->data['text_yes'] = $this->language->get('text_yes');
	$this->data['text_no'] = $this->language->get('text_no');
	$this->data['text_plus'] = $this->language->get('text_plus');
	$this->data['text_minus'] = $this->language->get('text_minus');
	$this->data['text_default'] = $this->language->get('text_default');
	$this->data['text_image_manager'] = $this->language->get('text_image_manager');
	$this->data['text_option'] = $this->language->get('text_option');
	$this->data['text_option_value'] = $this->language->get('text_option_value');
	$this->data['text_select'] = $this->language->get('text_select');
	$this->data['text_none'] = $this->language->get('text_none');
	$this->data['text_percent'] = $this->language->get('text_percent');
	$this->data['text_amount'] = $this->language->get('text_amount');

	$this->data['tab_shipping'] = $this->language->get('tab_shipping');
	$this->data['tab_links'] = $this->language->get('tab_links');

	$this->data['entry_name'] = $this->language->get('entry_name');
	$this->data['entry_meta_keywords'] = $this->language->get('entry_meta_keywords');
	$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
	$this->data['entry_description'] = $this->language->get('entry_description');
	$this->data['entry_store'] = $this->language->get('entry_store');
	$this->data['entry_keyword'] = $this->language->get('entry_keyword');
	$this->data['entry_model'] = $this->language->get('entry_model');
	$this->data['entry_sku'] = $this->language->get('entry_sku');
	// $this->data['entry_location'] = $this->language->get('entry_location');
	$this->data['entry_minimum'] = $this->language->get('entry_minimum');
	$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
	$this->data['entry_shipping'] = $this->language->get('entry_shipping');
	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
	$this->data['entry_quantity'] = $this->language->get('entry_quantity');
	$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
	$this->data['entry_status'] = $this->language->get('entry_status');
	$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
	$this->data['entry_price'] = $this->language->get('entry_price');
	$this->data['entry_price_standard'] = $this->language->get('entry_price_standard');
	$this->data['entry_price_custom'] = $this->language->get('entry_price_custom');
	$this->data['entry_cost'] = $this->language->get('entry_cost');
	$this->data['entry_subtract'] = $this->language->get('entry_subtract');
	$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
	$this->data['entry_weight'] = $this->language->get('entry_weight');
	$this->data['entry_material'] = $this->language->get('entry_material');
	$this->data['entry_dimension'] = $this->language->get('entry_dimension');
	$this->data['entry_length'] = $this->language->get('entry_length');
	$this->data['entry_image'] = $this->language->get('entry_image');
	$this->data['entry_thumb'] = $this->language->get('entry_thumb');
	$this->data['entry_download'] = $this->language->get('entry_download');
	$this->data['entry_category'] = $this->language->get('entry_category');
	$this->data['entry_related_category'] = $this->language->get('entry_related_category');
	$this->data['entry_option_description'] = $this->language->get('entry_option_description');
	$this->data['entry_related'] = $this->language->get('entry_related');
	$this->data['entry_upsell'] = $this->language->get('entry_upsell');
	$this->data['entry_cross_sell'] = $this->language->get('entry_cross_sell');
	$this->data['entry_points'] = $this->language->get('entry_points');
	$this->data['entry_option'] = $this->language->get('entry_option');
	$this->data['entry_option_value'] = $this->language->get('entry_option_value');
	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
	$this->data['entry_prefix'] = $this->language->get('entry_prefix');
	$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
	$this->data['entry_date_start'] = $this->language->get('entry_date_start');
	$this->data['entry_date_end'] = $this->language->get('entry_date_end');
	$this->data['entry_priority'] = $this->language->get('entry_priority');
	$this->data['entry_tags'] = $this->language->get('entry_tags');
	$this->data['entry_uom'] = $this->language->get('entry_uom');
	$this->data['entry_case_qty'] = $this->language->get('entry_case_qty');
	$this->data['entry_ea_per_um'] = $this->language->get('entry_ea_per_um');
	$this->data['entry_min_order_qty'] = $this->language->get('entry_min_order_qty');
	$this->data['entry_max_order_qty'] = $this->language->get('entry_max_order_qty');
	$this->data['entry_reward'] = $this->language->get('entry_reward');
	$this->data['entry_is_clearance'] = $this->language->get('entry_is_clearance');
	$this->data['entry_delivery_days'] = $this->language->get('entry_delivery_days');
	// $this->data['entry_delivery_days_standard'] = $this->language->get('entry_delivery_days_standard');
	$this->data['entry_delivery_days_custom'] = $this->language->get('entry_delivery_days_custom');
	$this->data['entry_cutoff_time'] = $this->language->get('entry_cutoff_time');

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_cancel'] = $this->language->get('button_cancel');
	$this->data['button_add_option'] = $this->language->get('button_add_option');
	$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
	$this->data['button_add_discount'] = $this->language->get('button_add_discount');
	$this->data['button_add_special'] = $this->language->get('button_add_special');
	$this->data['button_add_image'] = $this->language->get('button_add_image');
	$this->data['button_remove'] = $this->language->get('button_remove');

	$this->data['tab_general'] = $this->language->get('tab_general');
	$this->data['tab_data'] = $this->language->get('tab_data');
	$this->data['tab_discount'] = $this->language->get('tab_discount');
	$this->data['tab_special'] = $this->language->get('tab_special');
	$this->data['tab_option'] = $this->language->get('tab_option');
	$this->data['tab_attach'] = $this->language->get('tab_attach');
	$this->data['tab_image'] = $this->language->get('tab_image');
	$this->data['tab_reward'] = $this->language->get('tab_reward');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	if (isset($this->error['name'])) {
	    $this->data['error_name'] = $this->error['name'];
	} else {
	    $this->data['error_name'] = '';
	}

	if (isset($this->error['meta_description'])) {
	    $this->data['error_meta_description'] = $this->error['meta_description'];
	} else {
	    $this->data['error_meta_description'] = '';
	}

	if (isset($this->error['description'])) {
	    $this->data['error_description'] = $this->error['description'];
	} else {
	    $this->data['error_description'] = '';
	}

	if (isset($this->error['model'])) {
	    $this->data['error_model'] = $this->error['model'];
	} else {
	    $this->data['error_model'] = '';
	}

   if (isset($this->error['quantity_numbers'])) {
	    $this->data['error_quantity_numbers'] = $this->error['quantity_numbers'];
	} else {
	    $this->data['error_quantity_numbers'] = '';
	}

	if (isset($this->error['date_available'])) {
	    $this->data['error_date_available'] = $this->error['date_available'];
	} else {
	    $this->data['error_date_available'] = '';
	}

	if (isset($this->error['price'])) {
	    $this->data['error_price'] = $this->error['price'];
	} else {
	    $this->data['error_price'] = '';
	}

	if (isset($this->error['cost'])) {
	    $this->data['error_cost'] = $this->error['cost'];
	} else {
	    $this->data['error_cost'] = '';
	}

	if (isset($this->error['discount'])) {
	    $this->data['error_discount'] = '<ol><li>' . implode('</li><li>',array_map(function($array) {
	    	return implode('<br/>',$array);
	    },$this->error['discount'])) . '</li></ol>';
	} else {
	    $this->data['error_discount'] = '';
	}

	if (isset($this->error['special'])) {
	    $this->data['error_special'] = '<ol><li>' . implode('</li><li>',array_map(function($array) {
	    	return implode('<br/>',$array);
	    },$this->error['special'])) . '</li></ol>';
	} else {
	    $this->data['error_special'] = '';
	}

    if(isset($this->session->data['success'])) {
        $this->data['success'] = $this->session->data['success'];
        unset($this->session->data['success']);
    }
    else {
        $this->data['success'] = "";
    }


	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'catalog/product&token=' . $this->session->data['token'] . $url,
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);
	if (!isset($this->request->get['product_id'])) {
        $this->data['product_id'] = -1;
	    $this->data['action'] = makeUrl('catalog/product/insert');
	    $this->data['action_continue'] = makeUrl('catalog/product/insert', array('continue=1'));
	    $this->data['action_option'] = makeUrl('catalog/product/insert', array('continue=2'));
	    $bInsert = true;
	} else {
        $this->data['product_id'] = $this->request->get['product_id'];
	    $this->data['action'] = makeUrl('catalog/product/update', array('product_id=' . $this->request->get['product_id']));
	    $this->data['action_continue'] = makeUrl('catalog/product/update', array('product_id=' . $this->request->get['product_id'], 'continue=1'));
	    $this->data['action_option'] = makeUrl('catalog/product/update', array('product_id=' . $this->request->get['product_id'], 'continue=2'));
	    $this->data['inventory'] = makeUrl('catalog/product_option', array('product_id=' . $this->request->get['product_id']));
		$bInsert = false;
	}

	$this->data['cancel'] = makeUrl('catalog/product');

	$this->data['token'] = $this->session->data['token'];

	$oModel = $this->model;
	if($bInsert)
		$oModel->status = 1;
	$this->data['model'] = $oModel;

	$this->data['aStatus'] = $this->aStatus;
	$this->data['languages'] = Make::a('localisation/language')->find_many(true);

	$this->data['product_types'] = array(); //$this->model_catalog_product_type->getList();

	$this->data['aOptionTypes'] = array();
//	$aDetailTypes = $this->model_catalog_product->getDetailTypes($this->data['product_type_id']);
//	foreach ($aDetailTypes as $aType) {
//	    $this->data['aOptionTypes'][$aType['product_type_option_id']] = $aType['name'];
//	}
//	if (isset($this->request->post['product_detail'])) {
//	    $this->data['aProductDetails'] = $this->request->post['product_detail'];
//	} else if (isset($product_info)) {
//	    $this->data['aProductDetails'] = $this->model_catalog_product->getProductDetails($this->request->get['product_id']);
//	} else {
	$this->data['aProductDetails'][] = array(
	    'image' => 'no_image.jpg',
	    'thumb_image' => 'no_image.jpg',
	    'full_image' => 'no_image.jpg',
	);
//	}

	if (isset($this->request->post['product_description'])) {
	    $this->data['product_description'] = $this->request->post['product_description'];
	} elseif (isset($oModel) && $oModel) {
	    $aDesc = $oModel->getProductDescriptions()->find_many(true);
	    $this->data['product_description'] = array();
	    foreach ($aDesc as $aDescription) {
		    $this->data['product_description'][$aDescription['language_id']] = $aDescription;
	    }
	} else {
	    $this->data['product_description'] = array();
	}


        if (isset($this->request->post['keyword'])) {
	    $this->data['keyword'] = $this->request->post['keyword'];
	} elseif (isset($oModel) && $oModel) {
	    //$this->data['keyword'] = $oModel->getKeyword($oModel->toArray());
        $this->data['keyword'] = $oModel->getProductUrlAlias($oModel->toArray());
	} else {
	    $this->data['keyword'] = '';
	}
	if (isset($this->request->post['product_tags'])) {
	    $this->data['product_tags'] = $this->request->post['product_tags'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['product_tags'] = $oModel->getProductTags();
	} else {
	    $this->data['product_tags'] = array();
	}

	if (isset($this->request->post['image'])) {
	    $this->data['image'] = $this->request->post['image'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['image'] = $oModel->image;
	} else {
	    $this->data['image'] = '';
	}

	if (isset($this->request->post['thumb'])) {
	    $this->data['thumb'] = $this->request->post['thumb'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['thumb'] = $oModel->thumb;
	} else {
	    $this->data['thumb'] = '';
	}

        if (isset($this->request->post['size_chart'])) {
	    $this->data['AsizeChart'] = $this->request->post['size_chart'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['AsizeChart'] = $oModel->size_chart;
	} else {
	    $this->data['AsizeChart'] = '';
	}

	$this->load->model('tool/image');

	if (isset($oModel) && $oModel->image && file_exists(DIR_IMAGE . $oModel->image)) {
	    $this->data['preview'] = $this->model_tool_image->resize($oModel->image, 100, 100);
	} else {
	    $this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
	}

	if (isset($oModel) && $oModel->thumb && file_exists(DIR_IMAGE . $oModel->thumb)) {
	    $this->data['preview_thumb'] = $this->model_tool_image->resize($oModel->thumb, 100, 100);
	} else {
	    $this->data['preview_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
	}

	$this->load->model('catalog/manufacturer');

	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

	if (isset($this->request->post['quantity'])) {
        $this->data['quantity'] = $this->request->post['quantity'];
        /*} elseif (isset($oModel) && $oModel && $this->data['product_type_id'] != 2) {
            $this->load->model('catalog/product_option');
            $qty = $this->model_catalog_product_option->getTotalQty($this->request->get['product_id']);
            if($qty == 0) {
                $qty = $oModel->quantity;
            }
            $this->data['quantity'] = $qty;
        */
    }elseif (isset($oModel) && $oModel) {
	    $this->data['quantity'] = $oModel->quantity;
	} else {
	    $this->data['quantity'] = 1;
	}

	$this->load->model('localisation/stock_status');

	$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

	$this->load->model('localisation/tax_class');

	$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

	$this->load->model('localisation/weight_class');

	$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

	$weight_info = $this->model_localisation_weight_class->getWeightClassDescriptionByUnit($this->config->get('config_weight_class'));

	if (isset($this->request->post['weight_class_id'])) {
	    $this->data['weight_class_id'] = $this->request->post['weight_class_id'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['weight_class_id'] = $oModel->weight_class_id;
	} elseif (isset($weight_info)) {
	    $this->data['weight_class_id'] = $weight_info['weight_class_id'];
	} else {
	    $this->data['weight_class_id'] = '';
	}

	$this->load->model('localisation/length_class');

	$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
	$length_info = $this->model_localisation_length_class->getLengthClassDescriptionByUnit($this->config->get('config_length_class'));
	if (isset($this->request->post['length_class_id'])) {
	    $this->data['length_class_id'] = $this->request->post['length_class_id'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['length_class_id'] = $oModel->length_class_id;
	} elseif (isset($length_info)) {
	    $this->data['length_class_id'] = $length_info['length_class_id'];
	} else {
	    $this->data['length_class_id'] = '';
	}

	$this->data['language_id'] = $this->config->get('config_language_id');

	$this->load->model('catalog/product_type');
	$this->data['product_type_options'] = $this->model_catalog_product_type->getOptions($this->data['product_type_id'], $this->data['language_id']);


	if (isset($this->request->post['product_option'])) {
	    $this->data['product_options'] = $this->request->post['product_option'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['product_options'] = $oModel->getProductOptions($this->request->get['product_id']);
	} else {
	    $this->data['product_options'] = array();
	}

	$this->load->model('sale/customer_group');

	$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

	if (isset($this->request->post['product_discount'])) {
	    $this->data['product_discounts'] = $this->request->post['product_discount'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['product_discounts'] = $oModel->getProductDiscounts();
	} else {
	    $this->data['product_discounts'] = array();
	}

	if (isset($this->request->post['product_special'])) {
	    $this->data['product_specials'] = $this->request->post['product_special'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['product_specials'] = $oModel->getProductSpecials();
	} else {
	    $this->data['product_specials'] = array();
	}

	if (isset($this->request->post['product_reward'])) {
	    $this->data['product_reward'] = $this->request->post['product_reward'];
	} elseif (isset($this->request->get['product_id'])) {
	    $rewards = $oModel->getProductRewards();
        foreach($rewards as $reward) {
            $this->data['product_reward'][$reward['customer_group_id']] = $reward;
        }
	} else {
	    $this->data['product_reward'] = array();
	}

	$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

	$this->data['product_images'] = array();

	if (isset($oModel) && $oModel) {
	    $results = $oModel->getProductImages($this->request->get['product_id']);

	    foreach ($results as $result) {
		if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
		    $this->data['product_images'][] = array(
            'name' => $result['image'],
            'url' => $result['image'],
			'preview' => $this->model_tool_image->resize($result['image'], 100, 100),
			'file' => $result['image'],
             'deleteUrl' => '#',
             'deleteType' => 'DELETE'
		    );
		} else {
		    $this->data['product_images'][] = array(
            'name' => $result['image'],
            'url' => $result['image'],
			'preview' => $this->model_tool_image->resize('no_image.jpg', 100, 100),
			'file' => $result['image'],
            'deleteUrl' => '#',
            'deleteType' => 'DELETE'
		    );
		}
	    }
	}

	$this->load->model('catalog/download');

	$this->data['downloads'] = $this->model_catalog_download->getDownloads();

	if (isset($this->request->post['product_download'])) {
	    $this->data['product_download'] = $this->request->post['product_download'];
	} elseif (isset($oModel) && $oModel) {
        $this->data['product_download'] = array();
	    $downloads = $oModel->getProductDownloads();
        foreach($downloads as $download) {
            $this->data['product_download'][] = $download['download_id'];
        }
	} else {
	    $this->data['product_download'] = array();
	}

    $oCategories = Make::a('catalog/category')->create();
	$this->data['categories'] = $oCategories->getCategories(0);

	if (isset($this->request->post['product_category'])) {
	    $this->data['product_category'] = $this->request->post['product_category'];
	} elseif (isset($oModel) && $oModel) {
        $this->data['product_category'] = array();
        $categories = $oModel->getProductCategories();
        foreach($categories as $category) {
            $this->data['product_category'][] = $category['category_id'];
        }
	} else {
	    $this->data['product_category'] = array();
	}

    if (isset($this->request->post['product_related'])) {
        $this->data['product_related'] = $this->request->post['product_related'];
    } elseif (isset($oModel) && $oModel) {
        $this->data['product_related'] = $oModel->getProductRelated(PRODUCT_RELATED);
    } else {
        $this->data['product_related'] = array();
    }

    if (isset($this->request->post['product_cross_sell'])) {
	    $this->data['product_cross_sell'] = $this->request->post['product_cross_sell'];
	} elseif (isset($oModel) && $oModel) {
	    $this->data['product_cross_sell'] = $oModel->getProductRelated(PRODUCT_CROSS_SELL);
	} else {
	    $this->data['product_cross_sell'] = array();
	}



    if (isset($this->request->post['product_up_sell'])) {
        $this->data['product_up_sell'] = $this->request->post['product_up_sell'];
    } elseif (isset($oModel) && $oModel) {
        $this->data['product_up_sell'] = $oModel->getProductRelated(PRODUCT_UP_SELL);
    } else {
        $this->data['product_up_sell'] = array();
    }


    if (isset($this->request->post['delivery_days'])) {
        $this->data['delivery_days'] = $this->request->post['delivery_days'];
    } elseif (isset($oModel) && $oModel) {
        $this->data['delivery_days'] = $oModel->delivery_days;
    } else {
        $this->data['delivery_days'] = '';
    }



    // if (isset($this->request->post['delivery_days_standard'])) {
    //     $this->data['delivery_days'] = $this->request->post['delivery_days_standard'];
    // } elseif (isset($oModel) && $oModel) {
    //     $this->data['delivery_days_standard'] = $oModel->delivery_days_standard;
    // } else {
    //     $this->data['delivery_days_standard'] = '';
    // }



    if (isset($this->request->post['delivery_days_custom'])) {
        $this->data['delivery_days_custom'] = $this->request->post['delivery_days_custom'];
    } elseif (isset($oModel) && $oModel) {
        $this->data['delivery_days_custom'] = $oModel->delivery_days_custom;
    } else {
        $this->data['delivery_days_custom'] = '';
    }


    if (isset($this->request->post['price'])) {
       $oModel->price = $this->request->post['price'];
    } elseif (isset($oModel) && $oModel) {
        $this->data['price'] = $oModel->price;
    }

    if (isset($this->request->post['cost'])) {
        $oModel->cost = $this->request->post['cost'];
    } elseif (isset($oModel) && $oModel) {
        $this->data['cost'] = $oModel->cost;
    }


	$upload_url = makeUrl("common/filemanager/upload");
    $this->data['directory'] = "product";

    $this->document->addScript('view/javascript/jquery/tmpl.min.js', Document::POS_END);
    $this->document->addScript('view/javascript/jquery/load-image.min.js', Document::POS_END);
    $this->document->addScript('view/javascript/jquery/canvas-to-blob.min.js', Document::POS_END);

    $this->document->addScript('view/javascript/jquery/jquery.fileupload-process.js', Document::POS_END);
    $this->document->addScript('view/javascript/jquery/jquery.fileupload-image.js', Document::POS_END);
    $this->document->addScript('view/javascript/jquery/jquery.fileupload-ui.js', Document::POS_END);

    $this->document->addScriptInline("
    	$('.fileupload').fileupload({
            url: '".$upload_url."',
            dataType: 'json',
            dropZone: $(this).parent(),
            autoUpload: true,
            start: function(e,data) {
                $(this).parent().hide();
                $(this).parent().after('<div class=\"loader\"></div>');
            },
            done: function(e,data) {
                $(this).parent().show();
                $('.loader').remove();
                var type = $(this).attr('rel');
                var result = data.result;
                if(!result.hasOwnProperty('error')) {
                    $('#preview_'+type).attr('src','".HTTP_IMAGE."data/product/'+data.files[0].name);
                    $('input[name='+type+']').val('data/product/'+data.files[0].name);
                }
                else {
                    alert(result.error);
                }
            }
        });

        $('#fileupload_images').fileupload({
            url: '".makeUrl("catalog/product/uploadImage")."',
            dropZone: $(this).parent()
        });

		$('#fileupload_images').bind('fileuploadsubmit', function (e, data) {
			var rel = data.context.attr('data-rel');
		    data.formData = {sort_order:$('input[name=\"sort_order[' + rel + ']\"]').val()};
		});

        $('#fileupload_images').addClass('fileupload-processing');
        $.ajax({
		    url: '".makeUrl('catalog/product/productImages',array('product_id='.$this->request->get['product_id']))."',
		    dataType: 'json',
		    context: $('#fileupload_images')[0]
        }).always(function() {
	        $(this).removeClass('fileupload-processing');
        }).done(function(result, e) {
	        $(this).removeClass('fileupload-processing')
		    .fileupload('option', 'done')
		    .call(this, $.Event(this), {result: result});
        });
        ",Document::POS_READY);


	$this->template = 'catalog/product_form.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function productImages() {
        if(isset($this->request->get['product_id']) && !empty($this->request->get['product_id'])) {
            $oModel = Make::a('catalog/product')->find_one($this->request->get['product_id']);
        }
        else {
            $oModel = $this->model;
        }
        $aResults = $oModel->getProductImages();
        $aFiles = array();
        foreach($aResults as $result) {
            $name = substr($result['image'],strrpos($result['image'],"/")+1);
            $aFiles[] = array(
                'id' => $result['product_image_id'],
                'name' => $name,
                'preview' => HTTP_IMAGE.$result['image'],
                'url' => $result['image'],
                'deleteUrl' => "#",
                'deleteType' => 'GET'
            );
        }
        $oFiles = new stdClass();
        $oFiles->files = $aFiles;
        $this->response->setOutput(json_encode($oFiles));
    }

    public function uploadImage() {
        $res = QS::uploadFile($this->request->files,"product");
        $aFile = $this->request->files['image'];
        $aFiles = array();
        if(isset($res['success'])) {
            $aFiles[] = array(
                'id' => -1,
                'name' => $aFile['name'],
                'preview' => HTTP_IMAGE ."data/product/".$aFile['name'],
                'url' => "data/product/".$aFile['name'],
                'deleteUrl' => "#",
                'deleteType' => 'GET',
                'sort_order' => $this->request->post['sort_order']
            );
        }
        $oFiles = new stdClass();
        $oFiles->files = $aFiles;
        $this->response->setOutput(json_encode($oFiles));
    }

    public function deleteImage() {
        if(isset($this->request->post['delete']) && !empty($this->request->post['delete'])) {
            foreach($this->request->post['delete'] as $selected) {
                $img = explode('|',$selected);
                $image = DIR_IMAGE."data/product/".$img[0];
                if(file_exists($image)) {
                    unlink($image);
                }
                if($img[1] != -1) {
                    $this->model->deleteProductImage($img[1]);
                }
            }
        }
        elseif(isset($this->request->get['name']) && !empty($this->request->get['name'])) {
            $image = DIR_IMAGE."data/product/".$this->request->get['name'];
            if(file_exists($image)) {
                unlink($image);
            }
            if($this->request->get['id'] != -1) {
                $this->model->deleteProductImage($this->request->get['id']);
            }
        }
    }

    private function validateForm() {
	if (!$this->user->hasPermission('modify', 'catalog/product')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}

	foreach ($this->request->post['product_description'] as $language_id => $value) {
	    if ((strlen(utf8_decode($value['name'])) < 1) || (strlen(utf8_decode($value['name'])) > 255)) {
		$this->error['name'][$language_id] = $this->language->get('error_name');
	    }
	}

	if ((strlen(utf8_decode($this->request->post['model'])) < 1) || (strlen(utf8_decode($this->request->post['model'])) > 64)) {
	    $this->error['model'] = $this->language->get('error_model');
	}
	if ($this->request->post['price'] != '' && (!is_numeric($this->request->post['price']) || $this->request->post['price'] < 0)) {
	    $this->error['price'] = $this->language->get('error_price');
	}

	if ($this->request->post['cost'] != '' && (!is_numeric($this->request->post['cost']) || $this->request->post['cost'] < 0 || ($this->request->post['cost'] > $this->request->post['price']))) {
	    $this->error['cost'] = $this->language->get('error_cost');
	}

	if(isset($this->request->post['product_discount'])) {
		$aDiscounts = $this->request->post['product_discount'];
		foreach($aDiscounts as $i => $discount) {
			if($discount['quantity'] == '' || $discount['quantity'] < 0) {
				$this->error['discount'][$i]['quantity'] = $this->language->get('error_discount_quantity');
			}

			if($discount['price'] == '' || $discount['price'] < 0 || $discount['price'] > $this->request->post['price']) {
				$this->error['discount'][$i]['price'] = $this->language->get('error_discount_price');
			}
		}
	}

	if(isset($this->request->post['product_discount'])) {
		$aDiscounts = $this->request->post['product_discount'];
		foreach($aDiscounts as $i => $discount) {
			if($discount['quantity'] == '' || $discount['quantity'] < 1) {
				$this->error['discount'][$i]['quantity'] = $this->language->get('error_discount_quantity');
			}

			if($discount['price'] == '' || $discount['price'] < 0 || $discount['price'] > $this->request->post['price']) {
				$this->error['discount'][$i]['price'] = $this->language->get('error_discount_price');
			}
		}
	}
        if (is_numeric($this->request->post['quantity'])!='1') {
            $this->error['quantity_numbers'] = $this->language->get('error_quantity_numbers');
        }
	if(isset($this->request->post['product_special'])) {
		$aSpecials = $this->request->post['product_special'];
		foreach($aSpecials as $i => $special) {
			if($special['price'] == '' || $special['price'] < 0 || $special['price'] > $this->request->post['price']) {
				$this->error['special'][$i]['price'] = $this->language->get('error_special_price');
			}
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
	if (!$this->user->hasPermission('modify', 'catalog/product')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

    private function validateCopy() {
	if (!$this->user->hasPermission('modify', 'catalog/product')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

    public function category() {

	if (isset($this->request->get['category_id'])) {
	    $category_id = $this->request->get['category_id'];
	} else {
	    $category_id = 0;
	}

	$product_data = array();

    $results = $this->model->getProductsByCategoryId($category_id);
    $product_id = $this->request->get['product_id'];
	foreach ($results as $result) {
        if($result['product_id'] != $product_id) {
            $product_data[] = array(
                'product_id' => $result['product_id'],
                'name' => $result['name'],
                'model' => $result['model']
            );
        }
	}

	$this->response->setOutput(json_encode($product_data));
    }

    public function related() {
	if (isset($this->request->post)) {
	    $products = $this->request->post;
	} else {
	    $products = array();
	}

	$product_data = array();

	foreach ($products as $product_id) {
	    $oModel = Make::a('catalog/product')
                        ->table_alias('p')
                        ->select_expr('p.product_id,pd.name,p.model')
                        ->inner_join('product_description',array('p.product_id','=','pd.product_id'),'pd')
                        ->where('p.product_id',$product_id)
                        ->find_one();

	    if ($oModel) {
		$product_data[] = array(
		    'product_id' => $oModel->product_id,
		    'name' => $oModel->name,
		    'model' => $oModel->model
		);
	    }
	}
	$this->response->setOutput(json_encode($product_data));
    }

}

?>