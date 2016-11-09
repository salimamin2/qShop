<?php

class ControllerCatalogCategory extends CRUDController {

    protected $error = array();
    protected $status;

    public function getLanguageAlias() {
        return 'catalog/category';
    }

    public function init() {
        $this->load->language($this->getLanguageAlias());
        $this->setAlias('catalog/category');
        $this->setModel(Make::a('catalog/category'));
        $this->status = array(
            $this->language->get('text_disabled'),
            $this->language->get('text_enabled')
        );
    }

    public function insert() {
        $this->load->language('catalog/category');

        $this->document->title = $this->language->get('heading_title');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $result = $this->model->addCategory($this->request->post);
            if(isset($result['error'])) {
                $this->error['warning'] = $result['error'];
            }
            else {
                $this->session->data['success'] = $this->language->get('text_success');
                $this->redirect(makeUrl('catalog/category'));
            }
        }
        $this->getForm();
    }

    public function update() {
        $this->load->language('catalog/category');

        $this->document->title = $this->language->get('heading_title');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $result = $this->model->editCategory($this->request->post);
            if(isset($result['error'])) {
                $this->error['warning'] = $result['error'];
            }
            else {
                $this->session->data['success'] = $this->language->get('text_success');
                $this->redirect(makeUrl('catalog/category'));
            }
        }
        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/category');

        $this->document->title = $this->language->get('heading_title');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            $result = $this->model->deleteCategory($this->request->post['selected']);
            if(isset($result)) {
                $this->error = $this->language->get('text_error');
            }
            else {
                $this->session->data['success'] = $this->language->get('text_success');
                $this->redirect(makeUrl('catalog/category'));
            }
        }

        $this->getList();
    }

    protected function getList() {
        parent::getList();

        $this->data['categories'] = array();

        $results = $this->model->getCategories(0);
        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'icon' => 'icon-pencil',
                'class' => 'btn-info',
                'href' => makeUrl('catalog/category/update',array('category_id='.$result['category_id']))
            );

            $this->data['categories'][] = array(
                'category_id' => $result['category_id'],
                'ref_category_code' => $result['ref_category_code'],
                'name' => $result['name'],
                'sort_order' => $result['sort_order'],
                'selected' => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
                'action' => $action
            );

        }
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    protected function getForm() {
        parent::getForm();
        $bInsert = true;
        if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $oModel = $this->model->getCategory($this->request->get['category_id']);
            $this->data['model'] = $oModel;
            $aModel = $oModel->toArray();
            $bInsert = false;
        }

        $this->data['languages'] = Make::a('localisation/language')->find_many(true);

        if (isset($this->request->post['category_description'])) {
            $this->data['category_description'] = $this->request->post['category_description'];
        } elseif (isset($oModel)) {
            $cat_descriptions = $oModel->getCategoryDescriptions()->find_many(true);

            $array = array();
            foreach($cat_descriptions as $descrip) {
                $array[$descrip['language_id']] = $descrip;
            }
            $this->data['category_description'] = $array;

        } else {
            $this->data['$category_description'] = array();
        }

        if (isset($this->request->post['keyword'])) {
            $this->data['keyword'] = $this->request->post['keyword'];
        }
        elseif(isset($aModel)) {
            $this->data['keyword'] = $aModel['keyword'];
        }
        else {
            $this->data['keyword'] = '';
        }

        $this->data['categories'] = $this->model->getCategories(0);
        $this->data['status'] = $this->status;
        if($bInsert)
            $this->model->status = 1;
        $this->load->model('tool/image');

        if (isset($oModel) && $oModel->image && file_exists(DIR_IMAGE . $oModel->image)) {
            $this->data['preview'] = $this->model_tool_image->resize($oModel->image, 100, 100);
        } else {
            $this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        $this->data['model'] = $this->model;
        $this->data['directory'] = "category";



        $this->document->addScriptInline("
        $('#fileupload').fileupload({
            url: '".makeUrl("common/filemanager/upload")."',
            dataType: 'json',
            dropZone: $(this).parent(),
            start: function(e,data) {
                $(this).parent().hide();
                $(this).parent().after('<div class=\"loader\"></div>');
            },
            done: function(e,data) {
                $(this).parent().show();
                $('.loader').remove();
                var result = data.result;
                if(!result.hasOwnProperty('error')) {
                    $('#preview').attr('src','".HTTP_IMAGE."data/category/'+data.files[0].name);
                    $('input[name=image]').val('data/category/'+data.files[0].name);
                }
                else {
                    alert(result.error);
                }
            }
        });",Document::POS_READY);

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/category')) {
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