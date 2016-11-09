<?php

class ControllerToolBackup extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('tool/backup');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('tool/backup');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_select_all'] = $this->language->get('text_select_all');
        $this->data['text_unselect_all'] = $this->language->get('text_unselect_all');

        $this->data['entry_restore'] = $this->language->get('entry_restore');
        $this->data['entry_backup'] = $this->language->get('entry_backup');

        $this->data['button_backup'] = $this->language->get('button_backup');
        $this->data['button_restore'] = $this->language->get('button_restore');

        $this->data['tab_general'] = $this->language->get('tab_general');

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

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'tool/backup&token=' . $this->session->data['token'],
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['restore'] = HTTPS_SERVER . 'tool/backup&token=' . $this->session->data['token'];

        $this->data['backup'] = makeUrl('tool/backup/backup',array('no-layout=1'));

        $this->load->model('tool/backup');

        $this->data['tables'] = $this->model_tool_backup->getTables();

        $this->template = 'tool/backup.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function backup() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->response->addheader('Pragma: public');
            $this->response->addheader('Expires: 0');
            $this->response->addheader('Content-Description: File Transfer');
            $this->response->addheader('Content-Type: application/octet-stream');
            $this->response->addheader('Content-Disposition: attachment; filename=backup.sql');
            $this->response->addheader('Content-Transfer-Encoding: binary');

            $this->load->model('tool/backup');
            $this->response->setOutput($this->model_tool_backup->backup($this->request->post['backup']));
        } else {
            return $this->forward('error/permission');
        }
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'tool/backup')) {
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