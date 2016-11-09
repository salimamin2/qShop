<?php

class ControllerCatalogAttributes extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('catalog/attributes');
        $this->load->model('catalog/product');

        $this->data['token'] = $this->session->data['token'];
        if (isset($this->request->post['product_detail'])) {
            $this->insert();
        }

        $this->getForm();
    }

    public function insert() {
        $this->load->model('catalog/product');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_catalog_product->updateProductDetails(-1, $this->request->post);

            $this->redirect(HTTPS_SERVER . 'catalog/attributes&token=' . $this->session->data['token']);
        }
    }

    public function delete() {
        $this->load->model('catalog/product');
        $this->model_catalog_product->deleteProductDetails($this->request->get['option_id']);
        echo json_encode(array('success' => true));
    }

    private function getForm() {
        $this->data['language_id'] = $this->config->get('config_language_id');
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'catalog/attributes&token=' . $this->session->data['token'] . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['aOptionTypes'] = array();
        $aDetailTypes = $this->model_catalog_product->getDetailTypes(2);
        foreach ($aDetailTypes as $aType) {
            $this->data['aOptionTypes'][$aType['product_type_option_id']] = $aType['name'];
        }

        $this->data['action'] = HTTPS_SERVER . 'catalog/attributes&token=' . $this->session->data['token'];

        $product_details = array();
        if (isset($this->request->post['product_detail'])) {
            $product_details = $this->request->post['product_detail'];
        } else {
            $product_details = $this->model_catalog_product->getProductDetails(-1);
        }
        $this->data['aProductDetails'] = $product_details;

        $this->document->title = $this->data['heading_title'];

        $this->template = 'catalog/attributes_form.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>