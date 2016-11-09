<?php
class ControllerCatalogBlogCategory extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/blog_category');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/blog_category');

        $this->getList();
    }

    private function getList() {
        $this->load->language('catalog/blog_category');
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'catalog/blog_category&token=' . $this->session->data['token'],
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['insert'] = makeUrl('catalog/blog_category/insert');
        $this->data['copy'] = makeUrl('catalog/blog_category/copy');
        $this->data['delete'] = makeUrl('catalog/blog_category/delete');
        $this->data['sblogCateList'] = makeUrl('catalog/blog_category/ajaxList');

        $this->data['products'] = array();

        $this->load->model('tool/image');

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
        $this->data['column_sort_order']=$this->language->get('column_sort_order');
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



        $this->template = 'catalog/blog_category_list.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }


    public function ajaxList() {
        $this->load->language('catalog/blog_category');

        $aStatus = array(__('text_disabled'), __('text_enabled'));
        $columns = array(
            array('db' => 'id', 'dt' => 0, 'formatter' => function($d, $row) {
                return '<input type="checkbox" value="' . $row['id'] . '" name="selected[]" />';
            }),

            array('db' => 'id', 'dt' => 1,'formatter' => function($d,$row) {
                return $row['id'];
            }),
            array('db' => 'name', 'dt' => 2),
            array('db' => 'status', 'dt' => 3, 'formatter' => function($d, $row) {
                return ($d == 1 ? __('text_enabled') : __('text_disabled'));
            }),
            array('db' => 'sort_order', 'dt' => 4),
            array('db' => 'id', 'dt' => 5,
                'formatter' => function($d, $row) {
                    return '<a class="btn btn-info btn-sm" href="' . makeUrl('catalog/blog_category/update', array('id=' . $row['id'])) . '"><i class="icon-pencil"></i></a>';
                })
        );

        $oModel = Make::a('catalog/blog_category')
            ->table_alias('c')
            ->select_expr('c.*')
            ->where('c.language_id', (int) $this->config->get('config_language_id'));

        echo json_encode(
            QS::DT_simple($this->request->get, $oModel, $columns, 'catalog/blog_category')
        );
    }
    public function update() {

        $this->load->model('catalog/blog_category');

        $this->load->language('catalog/blog_category');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $oCategories = Make::a('catalog/blog_category')->create();
            $oModel = $oCategories->editCategory($this->request->post);

            $oModel = Make::a('catalog/blog_category')->find_one($this->request->post['blog_categor_id']);

            /*$oModel->name = $this->request->post['b_category_name'];
            $oModel->seo_keyword = $this->request->post['b_seo_keyword'];
            $oModel->seo_description = $this->request->post['b_seo_description'];
            $oModel->status = $this->request->post['status'];
            $oModel->sort_order = $this->request->post['sort_order'];
            $oModel->date_modified = date('Y-m-d H:i:s');
            $oModel->save();*/
            if ($oModel->hasErrors()) {
                throw new Exception("Error Editing category");
            }
            else{
                $this->session->data['success']=$this->language->get('text_success');
                $this->redirect(makeUrl('catalog/blog_category'));
            }

        }
        $this->getForm();
    }

    public function insert() {
        $this->load->language('catalog/blog_category');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $oCategories = Make::a('catalog/blog_category')->create();
            $oModel = $oCategories->addBlogCategory($this->request->post);

                $this->session->data['success']=$this->language->get('text_success');
                $this->redirect(makeUrl('catalog/blog_category'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/blog_category');

        $this->document->title = $this->language->get('heading_title');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            $oCategories = Make::a('catalog/blog_category')->create();
            $oModel = $oCategories->deleteBlogCategory($this->request->post);
            if(isset($result)) {
                $this->error = $this->language->get('text_error');
            }
            else {
                $this->session->data['success'] = $this->language->get('text_deleted');
                $this->redirect(makeUrl('catalog/blog_category'));
            }
        }

        $this->getList();
    }
    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/blog_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    private function validateForm() {
        $this->load->language('catalog/blog_category');

        if (!$this->user->hasPermission('modify', 'catalog/blog_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }


        foreach ($this->request->post['category_description'] as $language_id => $value) {
            if ((strlen(utf8_decode($value['name'])) < 1) || (strlen(utf8_decode($value['name'])) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');

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
    public function getForm() {
        $this->load->language('catalog/blog_category');
        $this->load->model('catalog/blog_category');
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->document->title = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_none'] = $this->language->get('text_none');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');

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


        if (isset($this->error['date_available'])) {
            $this->data['error_date_available'] = $this->error['date_available'];
        } else {
            $this->data['error_date_available'] = '';
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
            'href' => HTTPS_SERVER . 'catalog/blog_category&token=' . $this->session->data['token'] . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['id'])) {
            $this->data['action'] = makeUrl('catalog/blog_category/insert');
        } else {
            $this->data['product_id'] = $this->request->get['id'];
            $this->data['action'] = makeUrl('catalog/blog_category/update', array('id=' . $this->request->get['id']));
        }

        $this->data['languages'] = Make::a('localisation/language')->find_many(true);

        $this->data['cancel'] = makeUrl('catalog/blog_category');

        $this->data['token'] = $this->session->data['token'];

        $oModel = $this->model;

        $this->data['model'] = $oModel;

        $this->data['aStatus'] = $this->aStatus;

        $oProd = Make::a('catalog/blog_category')
            ->where('id', $this->request->get['id'])
            ->find_one();
        if ($oProd !== FALSE) {
            $this->data['blog_category']=$oProd->toArray();

            $this->data['blog_categor_id']=$this->data['blog_category']['id'];
            $this->data['blog_category_name']=$this->data['blog_category']['name'];
            $this->data['blog_seo_keyword']=$this->data['blog_category']['seo_keyword'];

            $this->data['blog_meta_title']=$this->data['blog_category']['meta_title'];
            $this->data['blog_meta_link']=$this->data['blog_category']['meta_link'];
            $this->data['blog_meta_keywords']=$this->data['blog_category']['meta_keywords'];
            $this->data['blog_meta_description']=$this->data['blog_category']['meta_description'];

            $this->data['blog_seo_description']=$this->data['blog_category']['seo_description'];
            $this->data['status']=$this->data['blog_category']['status'];
            $this->data['sort_order']=$this->data['blog_category']['sort_order'];

        }

        $this->template = 'catalog/blog_category_form.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }


}

?>