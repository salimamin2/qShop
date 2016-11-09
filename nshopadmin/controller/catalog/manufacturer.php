<?php

class ControllerCatalogManufacturer extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('catalog/manufacturer');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/manufacturer');

        $this->getList();
    }

    public function insert() {
        $this->load->language('catalog/manufacturer');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/manufacturer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_manufacturer->addManufacturer($this->request->post);

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

            $this->redirect(HTTPS_SERVER . 'catalog/manufacturer&token=' . $this->session->data['token'] . $url);
        }

        $this->getForm();
    }

    public function update() {
        $this->load->language('catalog/manufacturer');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/manufacturer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_manufacturer->editManufacturer($this->request->get['manufacturer_id'], $this->request->post);

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

            $this->redirect(HTTPS_SERVER . 'catalog/manufacturer&token=' . $this->session->data['token'] . $url);
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/manufacturer');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/manufacturer');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $manufacturer_id) {
                $this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
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

            $this->redirect(HTTPS_SERVER . 'catalog/manufacturer&token=' . $this->session->data['token'] . $url);
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
            'href' => makeUrl('catalog/manufacturer'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['insert'] = makeUrl('catalog/manufacturer/insert');
        $this->data['delete'] = makeUrl('catalog/manufacturer/delete');

        $this->data['manufacturers'] = array();

        $results = $this->model_catalog_manufacturer->getManufacturers($data);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'icon' => 'icon-pencil',
                'class' => 'btn-info',
                'href' => makeUrl('catalog/manufacturer/update',array('manufacturer_id=' . $result['manufacturer_id']))
            );

            $this->data['manufacturers'][] = array(
                'manufacturer_id' => $result['manufacturer_id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'sort_order' => $result['sort_order'],
                'selected' => isset($this->request->post['selected']) && in_array($result['manufacturer_id'], $this->request->post['selected']),
                'action' => $action
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_name'] = $this->language->get('column_name');
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

        $this->template = 'catalog/manufacturer_list.tpl';
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function getForm() {
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_default'] = $this->language->get('text_default');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_description'] = $this->language->get('entry_description');
        $this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $this->data['entry_facebook'] = $this->language->get('entry_facebook');
        $this->data['entry_twitter'] = $this->language->get('entry_twitter');
        $this->data['entry_category'] = $this->language->get('entry_category');
        $this->data['entry_keyword'] = $this->language->get('entry_keyword');
        $this->data['entry_image'] = $this->language->get('entry_image');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_country']=$this->language->get('entry_country');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

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

        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }

        if (isset($this->error['description'])) {
            $this->data['error_description'] = $this->error['description'];
        } else {
            $this->data['error_description'] = '';
        }

        if (isset($this->error['category'])) {
            $this->data['error_category'] = $this->error['category'];
        } else {
            $this->data['error_category'] = '';
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('catalog/manufacturer'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['manufacturer_id'])) {
            $this->data['action'] = makeUrl('catalog/manufacturer/insert');
        } else {
            $this->data['action'] = makeUrl('catalog/manufacturer/update',array('manufacturer_id=' . $this->request->get['manufacturer_id']));
        }

        $this->data['cancel'] = makeUrl('catalog/manufacturer');

        if (isset($this->request->get['manufacturer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
        }

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } elseif (isset($manufacturer_info)) {
            $this->data['name'] = $manufacturer_info['name'];
        } else {
            $this->data['name'] = '';
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } elseif (isset($manufacturer_info)) {
            $this->data['email'] = $manufacturer_info['email'];
        } else {
            $this->data['email'] = '';
        }

        if (isset($this->request->post['description'])) {
            $this->data['description'] = $this->request->post['description'];
        } elseif (isset($manufacturer_info)) {
            $this->data['description'] = $manufacturer_info['description'];
        } else {
            $this->data['description'] = '';
        }

        if (isset($this->request->post['meta_description'])) {
            $this->data['meta_description'] = $this->request->post['meta_description'];
        } elseif (isset($manufacturer_info)) {
            $this->data['meta_description'] = $manufacturer_info['meta_description'];
        } else {
            $this->data['meta_description'] = '';
        }

        if (isset($this->request->post['category_id'])) {
            $this->data['category_id'] = $this->request->post['category_id'];
        } elseif (isset($manufacturer_info)) {
            $this->data['category_id'] = $manufacturer_info['category_id'];
        } else {
            $this->data['category_id'] = '';
        }

        if (isset($this->request->post['country_id'])) {
            $this->data['country_id'] = $this->request->post['country_id'];
        } elseif (isset($manufacturer_info)) {
            $this->data['country_id'] = $manufacturer_info['country_id'];
        } else {
            $this->data['country_id'] = '';
        }
        
        if (isset($this->request->post['meta_title'])) {
            $this->data['meta_title'] = $this->request->post['meta_title'];
        } elseif (isset($manufacturer_info)) {
            $this->data['meta_title'] = $manufacturer_info['meta_title'];
        } else {
            $this->data['meta_title'] = '';
        }

        if (isset($this->request->post['keyword'])) {
            $this->data['keyword'] = $this->request->post['keyword'];
        } elseif (isset($manufacturer_info)) {
            $this->data['keyword'] = $manufacturer_info['keyword'];
        } else {
            $this->data['keyword'] = '';
        }

        if (isset($this->request->post['image'])) {
            $this->data['image'] = $this->request->post['image'];
        } elseif (isset($manufacturer_info)) {
            $this->data['image'] = $manufacturer_info['image'];
        } else {
            $this->data['image'] = '';
        }

        if (isset($this->request->post['facebook'])) {
            $this->data['facebook'] = $this->request->post['facebook'];
        } elseif (isset($manufacturer_info)) {
            $this->data['facebook'] = $manufacturer_info['facebook'];
        } else {
            $this->data['facebook'] = '';
        }

        if (isset($this->request->post['twitter'])) {
            $this->data['twitter'] = $this->request->post['twitter'];
        } elseif (isset($manufacturer_info)) {
            $this->data['twitter'] = $manufacturer_info['twitter'];
        } else {
            $this->data['twitter'] = '';
        }

        $this->load->model('tool/image');

        if (isset($manufacturer_info) && $manufacturer_info['image'] && file_exists(DIR_IMAGE . $manufacturer_info['image'])) {
            $this->data['preview'] = $this->model_tool_image->resize($manufacturer_info['image'], 100, 100);
        } else {
            $this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        if (isset($this->request->post['sort_order'])) {
            $this->data['sort_order'] = $this->request->post['sort_order'];
        } elseif (isset($manufacturer_info)) {
            $this->data['sort_order'] = $manufacturer_info['sort_order'];
        } else {
            $this->data['sort_order'] = '';
        }

        $this->load->model('localisation/country');

        $this->data['countries'] = $this->model_localisation_country->getCountries();

        $this->data['directory'] = "manufacturers";

        $this->data['categories'] = $this->model_catalog_manufacturer->getCategories();

        $this->document->addScriptInline("
        $('#fileupload').fileupload({
            url: '".makeUrl("common/filemanager/upload")."',
            dataType: 'json',
            dropZone: $(this).parent(),
            done: function(e,data) {
                var result = data.result;
                if(!result.hasOwnProperty('error')) {
                    $('#preview').attr('src','".HTTP_IMAGE."data/manufacturers/'+data.files[0].name);
                    $('input[name=image]').val('data/manufacturers/'+data.files[0].name);
                }
                else {
                    alert(result.error);
                }
            }
        });",Document::POS_READY);

        $this->template = 'catalog/manufacturer_form.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ($this->request->post['email'] == '') {
            $this->error['email'] = $this->language->get('error_email');
        }
        
        if ($this->request->post['description'] == '') {
            $this->error['description'] = $this->language->get('error_description');
        }

        if ($this->request->post['category_id'] == '') {
            $this->error['category'] = $this->language->get('error_category');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('catalog/manufacturer');

        foreach ($this->request->post['selected'] as $manufacturer_id) {
            $product_total = $this->model_catalog_manufacturer->getTotalProductsByManufacturerId($manufacturer_id);
            if ($product_total > 0) {
                $this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
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