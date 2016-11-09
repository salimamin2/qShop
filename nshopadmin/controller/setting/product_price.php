<?php 
class ControllerSettingProductPrice extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('setting/product_price');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('setting/product_price');

        $this->getForm();
    }

    public function insert() {
        $this->load->language('setting/product_price');
        $this->document->title = $this->language->get('heading_title');
        $this->load->model('setting/product_price');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_setting_product_price->update($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect(HTTPS_SERVER . 'setting/product_price&token=' . $this->session->data['token'] . $url);
        }
        $this->getForm();
    }

    private function getForm() {

        $this->data['entry_option'] = $this->language->get('entry_option');
        $this->data['entry_option_value'] = $this->language->get('entry_option_value');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_add_option'] = $this->language->get('button_add_option');
        $this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
        $this->data['button_add_discount'] = $this->language->get('button_add_discount');
        $this->data['button_add_special'] = $this->language->get('button_add_special');
        $this->data['button_add_image'] = $this->language->get('button_add_image');
        $this->data['button_remove'] = $this->language->get('button_remove');
        
        $this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['tab_option'] = $this->language->get('tab_option');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $this->data['error_title'] = $this->error['title'];
        } else {
            $this->data['error_title'] = '';
        }


        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
                'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
                'text'      => $this->language->get('text_home'),
                'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
                'href'      => HTTPS_SERVER . 'setting/product_price&token=' . $this->session->data['token'] . $url,
                'text'      => $this->language->get('heading_title'),
                'separator' => ' :: '
        );

        if (!isset($this->request->get['product_type_id'])) {
            $this->data['action'] = HTTPS_SERVER . 'setting/product_price/insert&token=' . $this->session->data['token'] . $url;
        } else {
            $this->data['action'] = HTTPS_SERVER . 'setting/product_price/update&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] . $url;
        }

        $this->data['cancel'] = HTTPS_SERVER . 'setting/product_price&token=' . $this->session->data['token'] . $url;

        $this->data['token'] = $this->session->data['token'];

        $this->data['product_prices'] = $this->model_setting_product_price->getPrices();
        if (isset($this->request->get['product_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $product_type_info = $this->model_setting_product_price->get($this->request->get['product_type_id']);
        }
        
        $this->load->model('localisation/language');

        $this->data['languages'] = $this->model_localisation_language->getLanguages();
        $this->data['language_id'] = $this->config->get('config_language_id');
        if (isset($this->request->post['title'])) {
            $this->data['title'] = $this->request->post['title'];
        } elseif (isset($product_type_info)) {
            $this->data['title'] = $product_type_info['title'];
        } else {
            $this->data['title'] = "";
        }

        if (isset($this->request->post['status'])) {
            $this->data['status'] = $this->request->post['status'];
        } elseif (isset($product_type_info)) {
            $this->data['status'] = $product_type_info['status'];
        } else {
            $this->data['status'] = array();
        }
        
        if (isset($this->request->post['product_type_option'])) {
            $this->data['product_type_options'] = $this->request->post['product_type_option'];
        } elseif (isset($product_type_info)) {
            $this->data['product_type_options'] = $this->model_setting_product_price->getProductTypeOption($this->request->get['product_type_id']);
        } else {
            $this->data['product_type_options'] = array();
        }

        
        $this->template = 'setting/product_price.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}
?>