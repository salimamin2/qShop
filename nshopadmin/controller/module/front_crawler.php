<?php

class ControllerModuleFrontCrawler extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('module/front_crawler');

        $this->document->title = $this->language->get('heading_title');
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $datas = array();
            $datas = $this->request->post;
            $i = 1;
            foreach($datas['frontcrlr_image'] as $data_image) {
                $sData['frontcrlr_image'.$i] = $data_image;
            $i++;
            }
            $j=1;
            foreach($datas['frontcrlr_link'] as $data_link) {
                $sData['frontcrlr_link'.$j] = $data_link;
            $j++;
            }
            $k=1;
            foreach($datas['frontcrlr_sort_order'] as $data_sort_order) {
                $sData['frontcrlr_sort_order'.$k] = $data_sort_order;
            $k++;
            }
            $sData['frontcrlr_status'] = $datas['frontcrlr_status'];
            $sData['frontcrlr_position'] = $datas['frontcrlr_position'];
            //d($sData,true);
            $this->model_setting_setting->editSetting('frontcrlr', $sData);
            // $this->session->data['success'] = $this->language->get('text_success');

            // $this->redirect(HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token']);
            $this->data['success'] = $this->language->get('text_success');
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');
        $this->data['text_left'] = $this->language->get('text_left');
        $this->data['text_right'] = $this->language->get('text_right');
        $this->data['text_top'] = $this->language->get('text_top');
        $this->data['text_bottom'] = $this->language->get('text_bottom');

        $this->data['entry_title'] = $this->language->get('entry_title');
        $this->data['entry_image'] = $this->language->get('entry_image');
        $this->data['entry_link'] = $this->language->get('entry_link');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_position'] = $this->language->get('entry_position');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_action'] = $this->language->get('entry_action');
        $this->data['entry_s_no'] = $this->language->get('entry_s_no');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_remove'] = $this->language->get('button_remove');
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
            'href' => HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_module'),
            'separator' => ' :: '
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'module/front_crawler&token=' . $this->session->data['token'],
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->load->model('tool/image');

        $this->data['action'] = HTTPS_SERVER . 'module/front_crawler&token=' . $this->session->data['token'];
        $this->data['cancel'] = HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token'];
        $results = $this->model_setting_setting->getSetting('frontcrlr');
        $this->data['frontcrlr'] = array();
        if ($results) {
            $i = 1;
            $ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');
            foreach ($results as $key => $value) {
                if (substr($key, 0, 15) == 'frontcrlr_image') {
                    $j = strtolower(substr($key,15));
                    $finfo = strtolower(substr($value, -3));
                    if(in_array($finfo, $ext)) {
                        $this->data['frontcrlr'][$j]['image_info'] = 'image';
                    } else {
                        $this->data['frontcrlr'][$j]['image_info'] = 'video';
                    }
                    $this->data['frontcrlr'][$j]['image'] = $key;
                    $this->data['frontcrlr'][$j]['image_value'] = $value;
                }
                if (substr($key, 0, 14) == 'frontcrlr_link') {
                    $j = substr($key,14);
                    $this->data['frontcrlr'][$j]['link'] = $key;
                    $this->data['frontcrlr'][$j]['link_value'] = $value;
                    $i++;
                }
                if (substr($key, 0, 20) == 'frontcrlr_sort_order') {
                    $j = substr($key,20);
                    $this->data['frontcrlr'][$j]['sort_order'] = $key;
                    $this->data['frontcrlr'][$j]['sort_order_value'] = $value;
                    $i++;
                }
            }
        }
        sort($this->data['frontcrlr']);
        //d($this->data['frontcrlr']);
        
        $this->data['image_url'] = HTTPS_IMAGE;
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
        
        if (isset($this->request->post['frontcrlr_title'])) {
            $this->data['frontcrlr_title'] = $this->request->post['frontcrlr_title'];
        } else {
            $this->data['frontcrlr_title'] = $this->config->get('frontcrlr_title');
        }

        if (isset($this->request->post['frontcrlr_status'])) {
            $this->data['frontcrlr_status'] = $this->request->post['frontcrlr_status'];
        } else {
            $this->data['frontcrlr_status'] = $this->config->get('frontcrlr_status');
        }

        if (isset($this->request->post['frontcrlr_position'])) {
            $this->data['frontcrlr_position'] = $this->request->post['frontcrlr_position'];
        } else {
            $this->data['frontcrlr_position'] = $this->config->get('frontcrlr_position');
        }

        if (isset($this->request->post['frontcrlr_sort_order'])) {
            $this->data['frontcrlr_sort_order'] = $this->request->post['frontcrlr_sort_order'];
        } else {
            $this->data['frontcrlr_sort_order'] = $this->config->get('frontcrlr_sort_order');
        }

        $this->template = 'module/front_crawler.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/front_crawler')) {
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