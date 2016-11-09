<?php

class ControllerModuleFrontCrousel extends Controller {

    private $error = array();

    public function index() {

        $this->load->language('module/front_crousel');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $datas = array();
            $datas = $this->request->post;
            $i = 1;
            foreach($datas['frontc_image'] as $data_image) {
                $sData['frontc_image'.$i] = $data_image;
            $i++;
            }
            $j=1;
            foreach($datas['frontc_link'] as $data_link) {
                $sData['frontc_link'.$j] = $data_link;
            $j++;
            }
            $sData['frontc_status'] = $datas['frontc_status'];
            $sData['frontc_position'] = $datas['frontc_position'];
            $this->model_setting_setting->editSetting('frontc', $sData);
            // $this->session->data['success'] = $this->language->get('text_success');
            // $this->redirect(HTTPS_SERVER . 'extension/module&token=' . $this->request->get['token']);
            $this->data['success'] = $this->language->get('text_success');
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');

        $this->data['entry_image'] = $this->language->get('entry_image');
        $this->data['entry_link'] = $this->language->get('entry_link');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_position'] = $this->language->get('entry_position');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_add_row'] = $this->language->get('button_add_row');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'extension/module',
            'text' => $this->language->get('text_module'),
            'separator' => ' :: '
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'module/front_crousel',
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->load->model('tool/image');

        $this->data['action'] = HTTPS_SERVER . 'module/front_crousel&token=' . $this->request->get['token'];

        $this->data['cancel'] = HTTPS_SERVER . 'extension/module&token=' . $this->request->get['token'];
        $results = $this->model_setting_setting->getSetting('frontc');
        $this->data['frontc'] = array();
        if ($results) {
            $i = 1;
            foreach ($results as $key => $value) {
                if (substr($key, 0, 12) == 'frontc_image') {
                    $j = substr($key,12);
                    $this->data['frontc'][$j]['image'] = $key;
                    $this->data['frontc'][$j]['image_value'] = $value;
                }
                if (substr($key, 0, 11) == 'frontc_link') {
                    $j = substr($key,11);
                    $this->data['frontc'][$j]['link'] = $key;
                    $this->data['frontc'][$j]['link_value'] = $value;
                    $i++;
                }
            }
        }

        $this->data['image_url'] = HTTPS_IMAGE;
        if (isset($this->request->post['frontc_status'])) {
            $this->data['frontc_status'] = $this->request->post['frontc_status'];
        } else {
            $this->data['frontc_status'] = $this->config->get('frontc_status');
        }

        $this->data['positions'] = array();
        $this->data['positions'][] = array(
            'position' => 'left',
            'title' => $this->language->get('text_left'),
        );
        $this->data['positions'][] = array(
            'position' => 'right',
            'title' => $this->language->get('text_right'),
        );
        $this->data['positions'][] = array(
            'position' => 'home',
            'title' => $this->language->get('text_home'),
        );
        if (isset($this->request->post['frontc_position'])) {
            $this->data['frontc_position'] = $this->request->post['frontc_position'];
        } else {
            $this->data['frontc_position'] = $this->config->get('frontc_position');
        }


        $this->template = 'module/front_crousel.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/front_crousel')) {
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