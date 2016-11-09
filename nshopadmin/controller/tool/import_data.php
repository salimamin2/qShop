<?php

class ControllerToolImportData extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('tool/import_data');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('tool/import_data');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            if (isset($this->request->files) && is_uploaded_file($this->request->files['import']['tmp_name'])) {
                $this->model_tool_import_data->import($this->request->files['import']['tmp_name']);
                $this->session->data['success'] = $this->language->get('text_success');
                $this->redirect(HTTPS_SERVER . 'tool/import_data&token=' . $this->session->data['token']);
            } else {
                $this->error['warning'] = $this->language->get('error_file');
            }
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_select_all'] = $this->language->get('text_select_all');
        $this->data['text_unselect_all'] = $this->language->get('text_unselect_all');

        $this->data['entry_restore'] = $this->language->get('entry_restore');
        $this->data['entry_import'] = $this->language->get('entry_import');

        $this->data['button_import'] = $this->language->get('button_import');
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
            'href' => HTTPS_SERVER . 'tool/import_data&token=' . $this->session->data['token'],
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['reference_file'] = HTTP_DOWNLOAD . 'ref_file.csv';
        $this->data['import'] = HTTPS_SERVER . 'tool/import_data&token=' . $this->session->data['token'];

        $this->load->model('tool/import_data');

        $this->data['tables'] = $this->model_tool_import_data->getTables();

        $this->template = 'tool/import_data.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'tool/import_data')) {
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