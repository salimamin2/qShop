<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of customer_import
 *
 * @author qasim
 */
class ControllerToolCustomerExport extends Controller {
    private $error = array();
    public function index() {
        $this->load->language('tool/customer_export');
        $this->load->model('tool/customer_export');
        $this->document->title = $this->language->get('heading_title');

        if($this->request->server['REQUEST_METHOD'] == 'POST'){

            $this->export();
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
                'href'      => HTTPS_SERVER . 'common/home',
                'text'      => $this->language->get('text_home'),
                'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
                'href'      => HTTPS_SERVER . 'sale/customer_export',
                'text'      => $this->language->get('heading_title'),
                'separator' => ' :: '
        );
        $this->data['button_export'] = $this->language->get('button_export');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['action'] = HTTPS_SERVER . 'tool/customer_export';

         if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
            $this->data['success'] = '';
        } else {
            $this->data['error_warning'] = '';
            $this->data['success'] = "Customer Export successfully";
        }

        $this->template = 'tool/customer_export.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }


    private function export() {
        $this->load->model('tool/customer_export');
        $export = $this->model_tool_customer_export->getCustomers();
        if($export != 1)
           $this->error['warning'] = $export;
        //$this->response->setOutput(json_encode($arMappings), $this->config->get('config_compression'));
    }
}
?>
