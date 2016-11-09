<?php

class ControllerSettingSeoManagement extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('setting/seo_management');
        $this->document->title = $this->language->get('heading_title');
        $this->load->model('setting/seo_management');
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_query'] = $this->language->get('entry_query');
        $this->data['entry_keyword'] = $this->language->get('entry_keyword');
        $this->data['column_action'] = $this->language->get('column_action');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_add_store'] = $this->language->get('button_add_store');

        $this->data['tab_common'] = $this->language->get('tab_common');
        $this->data['tab_product'] = $this->language->get('tab_product');
        $this->data['tab_category'] = $this->language->get('tab_category');
        $this->data['tab_information'] = $this->language->get('tab_information');
        $this->data['tab_manufacturer'] = $this->language->get('tab_manufacturer');
        $this->data['text_edit'] = $this->language->get('text_edit');
        $this->data['text_delete'] = $this->language->get('button_delete');


        $groups = $this->model_setting_seo_management->getGroups();
        //d($groups);
        $this->data['groups'] =  $groups;
        foreach ($groups as $group) {
            $results = $this->model_setting_seo_management->getUrlsByGroup($group);
            foreach ($results as $result) {
                $this->data['seo_urls'][$group][] = array(
                    'url_alias_id' => $result['url_alias_id'],
                    'query' => $result['query'],
                    'keyword' => $result['keyword']
                );
            }
        }

        //d($this->data['seo_urls']);
        if (isset($this->request->get['url_alias_id'])) {
            $result = $this->model_setting_seo_management->getUrl($this->request->get['url_alias_id']);
            $this->data['group'] = $result['group'];
        } else {
            $this->data['group'] = $groups[0];
        }

        if(isset($this->request->get['group'])) {
            $this->data['group'] = $this->request->get['group'];
        }

        if($this->data['group'] != "custom") {
            $this->data['query'] = $this->data['group']."_id=";//($this->data['group'] != "category" ?  : "path=");
        }
        else {
            $this->data['query'] = "";
        }

        if(isset($this->request->post['query'])) {
            $this->data['query'] = $this->request->post['query'];
        }

        if(isset($this->request->post['keyword'])) {
            $this->data['keyword'] = $this->request->post['keyword'];
        }

        if(isset($this->request->post['group'])) {
            $this->data['group'] = $this->request->post['group'];
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['query'])) {
            $this->data['error_query'] = $this->error['query'];
        } else {
            $this->data['error_query'] = '';
        }

        if (isset($this->error['keyword'])) {
            $this->data['error_keyword'] = $this->error['keyword'];
        } else {
            $this->data['error_keyword'] = '';
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'setting/seo_management&token=' . $this->session->data['token'],
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

//        if (isset($this->request->get['url_alias_id'])) {
//            $this->data['form_action'] = HTTPS_SERVER . 'setting/seo_management/update&url_alias_id=' . $this->request->get['url_alias_id'].'&token=' . $this->session->data['token'];
//        } else {
//            $this->data['form_action'] = HTTPS_SERVER . 'setting/seo_management/insert&token=' . $this->session->data['token'];
//        }

        $this->data['edit_action'] = makeUrl('setting/seo_management/update');
        $this->data['form_action'] = makeUrl('setting/seo_management/insert');
        $this->data['delete_action'] = makeUrl('setting/seo_management/delete');


        $this->data['tokens'] = array();

        $this->template = 'setting/seo_management.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function insert() {
        $this->load->language('setting/seo_management');
        $this->document->title = $this->language->get('heading_title');
        $this->load->model('setting/seo_management');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $url_alias_id = $this->model_setting_seo_management->addUrl($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect(makeUrl('setting/seo_management'));
        }
        $this->index();
    }

    public function update() {
        $this->load->language('setting/seo_management');
        $this->document->title = $this->language->get('heading_title');
        $this->load->model('setting/seo_management');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_setting_seo_management->editUrl($this->request->get['url_alias_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect(makeUrl('setting/seo_management',array('url_alias_id='.$this->request->get['url_alias_id'])));
        }
        $this->index();
    }

    public function delete() {
        $this->load->language('setting/seo_management');
        $this->document->title = $this->language->get('heading_title');
        $this->load->model('setting/seo_management');
        if (isset($this->request->get['url_alias_id']) && $this->validateDelete()) {
            $this->model_setting_seo_management->deleteUrl($this->request->get['url_alias_id']);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect(makeUrl('setting/seo_management',array('group=' . $this->request->get['group'])));
        }
        $this->index();
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'setting/seo_management')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (utf8_decode($this->request->post['query']) == '') {
            $this->error['query'] = $this->language->get('error_query');
        }
        
        if (utf8_decode($this->request->post['keyword']) == '') {
            $this->error['keyword'] = $this->language->get('error_keyword');
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
        if (!$this->user->hasPermission('modify', 'setting/seo_management')) {
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