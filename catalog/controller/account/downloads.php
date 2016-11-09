<?php

class ControllerAccountDownloads extends Controller {

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = HTTPS_SERVER . 'account/download';

            $this->redirect(HTTPS_SERVER . 'account/login');
        }

        $this->language->load('account/downloads');

        $this->document->title = $this->language->get('heading_title');

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'account/account',
            'text' => $this->language->get('text_account'),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTP_SERVER . 'account/download',
            'text' => $this->language->get('text_downloads'),
            'separator' => $this->language->get('text_separator')
        );

        $this->load->model('account/download');

        $download_total = $this->model_account_download->listTotalFolderDocuments();

        if ($download_total) {
            $this->data['heading_title'] = $this->language->get('heading_title');
            $this->data['text_name'] = $this->language->get('text_name');
            $this->data['text_size'] = $this->language->get('text_size');
            $this->data['text_download'] = $this->language->get('text_download');

            $this->data['button_continue'] = $this->language->get('button_continue');

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            $this->data['downloads'] = array();

            $results = $this->model_account_download->listFolderDocuments();

            foreach ($results as $result) {
                if (file_exists(DIR_ROOT . "/upload/documents/" . $result)) {
                    $size = filesize(DIR_ROOT . "/upload/documents/" . $result);

                    $i = 0;

                    while (($size / 1024) > 1) {
                        $size = $size / 1024;
                        $i++;
                    }

                    $this->data['downloads'][] = array(
                        'name' => $result,
                        'href' => HTTPS_SERVER . "upload/documents/" . $result
                    );
                }
            }



            $this->data['continue'] = HTTPS_SERVER . 'account/account';

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/downloads.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/account/downloads.tpl';
            } else {
                $this->template = 'default/template/account/downloads.tpl';
            }

            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        } else {
            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_error'] = $this->language->get('text_error');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['continue'] = HTTPS_SERVER . 'account/account';

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->template = 'default/template/error/not_found.tpl';
            }

            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        }
    }

}

?>