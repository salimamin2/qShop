<?php

class ControllerSaleDiscount extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('sale/discount');
        $this->document->title = $this->language->get('heading_title');
        $this->load->model('sale/discount');
        $this->getList();
    }

    public function insert() {
        $this->load->language('sale/discount');
        $this->document->title = $this->language->get('heading_title');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = Make::a('sale/discount')->create()->addDiscount($this->request->post);
            if($res['success']) {
                $this->session->data['success'] = $this->language->get('text_success');
                $this->redirect(makeUrl('sale/discount'));
            }
            $this->error['warning'] = $res['error'];
        }
        $this->getForm();
    }

    public function update() {
        $this->load->language('sale/discount');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('sale/discount');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $res = Make::a('sale/discount')->create()->editDiscount($this->request->get['discount_id'], $this->request->post);
            if($res['success']) {
                $this->session->data['success'] = $this->language->get('text_success');
                $this->redirect(makeUrl('sale/discount'));
            }
            $this->error['warning'] = $res['error'];
        }
        $this->getForm();
    }

    public function delete() {
        $this->load->language('sale/discount');
        $this->document->title = $this->language->get('heading_title');
        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $discount_id) {
                Make::a('sale/discount')->create()->deleteDiscount($discount_id);
            }
            $this->session->data['success'] = $this->language->get('text_deleted');
            $this->redirect(makeUrl('sale/discount'));
        }
        $this->getList();
    }

    private function getList() {
        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('sale/discount'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['insert'] = makeUrl('sale/discount/insert');
        $this->data['delete'] = makeUrl('sale/discount/delete');
        $this->data['discounts'] = array();

        $results = Make::a('sale/discount')->create()->getDiscounts();

        foreach ($results as $result) {
            $this->data['discounts'][] = array(
                'discount_id' => $result['discount_id'],
                'name' => $result['name'],
                'description' => $result['description'],
                'customer_group_id' => $result['customer_group_id'],
                'cgname' => $result['cgname'],
                'sort_order' => $result['sort_order'],
                'discount' => $result['discount'],
                'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
                'date_end' => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'selected' => isset($this->request->post['selected']) && in_array($result['discount_id'], $this->request->post['selected']),
                'edit' => makeUrl('sale/discount/update',array('discount_id=' . $result['discount_id']))
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_code'] = $this->language->get('column_code');
        $this->data['column_discount'] = $this->language->get('column_discount');
        $this->data['column_customer_group_id'] = $this->language->get('column_customer_group_id');
        $this->data['column_date_start'] = $this->language->get('column_date_start');
        $this->data['column_date_end'] = $this->language->get('column_date_end');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['column_action'] = $this->language->get('column_action');
        $this->data['column_sort_order'] = $this->language->get('column_sort_order');

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

        $this->template = 'sale/discount_list.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function getForm() {
        $this->data['heading_title'] = $this->language->get('form_heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        $this->data['text_percent'] = $this->language->get('text_percent');
        $this->data['text_amount'] = $this->language->get('text_amount');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_description'] = $this->language->get('entry_description');
        $this->data['entry_code'] = $this->language->get('entry_code');
        $this->data['entry_discount'] = $this->language->get('entry_discount');
        $this->data['entry_logged'] = $this->language->get('entry_logged');
        $this->data['entry_shipping'] = $this->language->get('entry_shipping');
        $this->data['entry_type'] = $this->language->get('entry_type');
        $this->data['entry_total'] = $this->language->get('entry_total');
        $this->data['entry_product'] = $this->language->get('entry_product');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');



        $this->data['entry_customer_groups'] = $this->language->get('entry_customer_groups');
        $this->data['text_default'] = $this->language->get('text_default');
        $this->data['text_wholesale'] = $this->language->get('text_wholesale');

        $this->data['entry_date_start'] = $this->language->get('entry_date_start');
        $this->data['entry_date_end'] = $this->language->get('entry_date_end');
        $this->data['entry_uses_total'] = $this->language->get('entry_uses_total');
        $this->data['entry_uses_customer'] = $this->language->get('entry_uses_customer');
        $this->data['entry_status'] = $this->language->get('entry_status');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['tab_general'] = $this->language->get('tab_general');

        if($this->error) {
            $this->data['error'] = $this->language->get('error_message');
        }
        else {
            $this->data['error'] = '';
        }

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

        if (isset($this->error['description'])) {
            $this->data['error_description'] = $this->error['description'];
        } else {
            $this->data['error_description'] = '';
        }
        
        if (isset($this->error['sort_order'])) {
        $this->data['error_sort_order'] = $this->error['sort_order'];
        } else {
            $this->data['error_sort_order'] = '';
        }
        
        

        if (isset($this->error['date_start'])) {
            $this->data['error_date_start'] = $this->error['date_start'];
        } else {
            $this->data['error_date_start'] = '';
        }

        if (isset($this->error['date_end'])) {
            $this->data['error_date_end'] = $this->error['date_end'];
        } else {
            $this->data['error_date_end'] = '';
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('sale/discount'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['discount_id'])) {
            $this->data['action'] = makeUrl('sale/discount/insert');
        } else {
            $this->data['action'] = makeUrl('sale/discount/update',array('discount_id=' . $this->request->get['discount_id']));
        }

        $this->data['cancel'] = makeUrl('sale/discount');
        $oModel = Make::a('sale/discount')->create();
        if (isset($this->request->get['discount_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
            $discount_info = $oModel->getDiscount($this->request->get['discount_id']);
        }

        $this->data['languages'] = Make::a('localisation/language')->create()->getLanguages();

        if (isset($this->request->post['discount_description'])) {
            $this->data['discount_description'] = $this->request->post['discount_description'];
        } elseif (isset($this->request->get['discount_id'])) {
            $this->data['discount_description'] = $oModel->getDiscountDescriptions($this->request->get['discount_id']);
        } else {
            $this->data['discount_description'] = array();
        }

        if (isset($this->request->post['type'])) {
            $this->data['type'] = $this->request->post['type'];
        } elseif (isset($discount_info)) {
            $this->data['type'] = $discount_info['type'];
        } else {
            $this->data['type'] = '';
        }

        if (isset($this->request->post['discount'])) {
            $this->data['discount'] = $this->request->post['discount'];
        } elseif (isset($discount_info)) {
            $this->data['discount'] = $discount_info['discount'];
        } else {
            $this->data['discount'] = '';
        }

        if (isset($this->request->post['shipping'])) {
            $this->data['shipping'] = $this->request->post['shipping'];
        } elseif (isset($discount_info)) {
            $this->data['shipping'] = $discount_info['shipping'];
        } else {
            $this->data['shipping'] = '';
        }

        if (isset($this->request->post['total'])) {
            $this->data['total'] = $this->request->post['total'];
        } elseif (isset($discount_info)) {
            $this->data['total'] = $discount_info['total'];
        } else {
            $this->data['total'] = '';
        }

        if (isset($this->request->post['product'])) {
            $this->data['discount_product'] = $this->request->post['product'];
        } elseif (isset($discount_info)) {
            $this->data['discount_product'] = $oModel->getDiscountProducts($this->request->get['discount_id']);
        } else {
            $this->data['discount_product'] = array();
        }


        if (isset($this->request->post['date_start'])) {
            $this->data['date_start'] = $this->request->post['date_start'];
        } elseif (isset($discount_info)) {
            $this->data['date_start'] = date('d-m-Y', strtotime($discount_info['date_start']));
        } else {
            $this->data['date_start'] = date('d-m-Y');
        }

        if (isset($this->request->post['date_end'])) {
            $this->data['date_end'] = $this->request->post['date_end'];
        } elseif (isset($discount_info)) {
            $this->data['date_end'] = date('d-m-Y', strtotime($discount_info['date_end']));
        } else {
            $this->data['date_end'] = date('d-m-Y');
        }

        if (isset($this->request->post['sort_order'])) {
            $this->data['sort_order'] = $this->request->post['sort_order'];
        } elseif (isset($discount_info)) {
            $this->data['sort_order'] = $discount_info['sort_order'];
        } else {
            $this->data['sort_order'] = '';
        }

        if (isset($this->request->post['status'])) {
            $this->data['status'] = $this->request->post['status'];
        } elseif (isset($discount_info)) {
            $this->data['status'] = $discount_info['status'];
        } else {
            $this->data['status'] = 1;
        }

        if (isset($this->request->post['customer_group_id'])) {
            $this->data['customer_group_id'] = $this->request->post['customer_group_id'];
        } elseif (isset($discount_info)) {
            $this->data['customer_group_id'] = $discount_info['customer_group_id'];
        } else {
            $this->data['customer_group_id'] = '';
        }

        $this->load->model('sale/customer_group');
        $this->data['customer_groups'] = CHtml::listData($this->model_sale_customer_group->getCustomerGroups(),'customer_group_id','name');

        $this->data['categories'] = CHtml::listData(Make::a('catalog/category')->create()->getCategories(0),'category_id',
            'name');
        $this->data['sCatUrl'] = makeUrl('sale/discount/category');
        $this->data['sProdUrl'] = makeUrl('sale/discount/product');

        $this->template = 'sale/discount_form.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'sale/discount')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if(!$this->request->post['discount']) {
            $this->error['discount'] = $this->language->get('error_discount');
        }

        foreach ($this->request->post['discount_description'] as $language_id => $value) {
            if ((strlen(utf8_decode($value['name'])) < 3) || (strlen(utf8_decode($value['name'])) > 64)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if (strlen(utf8_decode($value['description'])) < 3) {
                $this->error['description'][$language_id] = $this->language->get('error_description');
            }          
        }

        if (strlen($this->request->post['sort_order']) < 1) {
            $this->error['sort_order'] = $this->language->get('error_sort_order');
        }

        return (!$this->error ? true : false);
    }

    public function category() {
        if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
        } else {
            $category_id = 0;
        }

        $product_data = array();

        $results = Make::a('catalog/product')->create()->getProductsByCategoryId($category_id);

        foreach ($results as $result) {
            $product_data[] = array(
                'product_id' => $result['product_id'],
                'name' => $result['name'],
                'model' => $result['model']
            );
        }

        $this->load->library('json');

        $this->response->setOutput(Json::encode($product_data));
    }

    public function product() {
        if (isset($this->request->post['discount_product'])) {
            $products = $this->request->post['discount_product'];
        } else {
            $products = array();
        }

        $product_data = array();
        $oModel = Make::a('catalog/product')->create();
        foreach ($products as $product_id) {
            $product_info = $oModel->getProduct($product_id);
            if ($product_info) {
                $product_data[] = array(
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name'],
                    'model' => $product_info['model']
                );
            }
        }
        $this->response->setOutput(json_encode($product_data));
    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'sale/discount')) {
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