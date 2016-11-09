<?php
class ControllerPaymentcheckoutapipayment extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('payment/checkoutapipayment');
        $this->document->title = $this->language->get('heading_title');
        // $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
        {
           // print_r($this->request->post); die();
            Make::a('setting/setting')->create()->editSetting('checkoutapipayment', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect(makeUrl('extension/payment'));
        }

        $this->data['heading_title']                 = $this->language->get('heading_title');
        $this->data['text_edit']                     = $this->language->get('text_edit');
        $this->data['text_checkoutapipayment_join']  = $this->language->get('text_checkoutapipayment_join');
        $this->data['text_checkoutapipayment']       = $this->language->get('text_checkoutapipayment');
        $this->data['text_payment']                  = $this->language->get('text_payment');
        $this->data['text_success']                  = $this->language->get('text_success');
        $this->data['text_page_title']               = $this->language->get('text_page_title');
        $this->data['text_status_on']                = $this->language->get('text_status_on');
        $this->data['text_status_off']               = $this->language->get('text_status_off');
        $this->data['text_mode_sandbox']             = $this->language->get('text_mode_sandbox');
        $this->data['text_mode_live']                = $this->language->get('text_mode_live');
        $this->data['text_auth_only']                = $this->language->get('text_auth_only');
        $this->data['text_auth_capture']             = $this->language->get('text_auth_capture');
        $this->data['text_pci_yes']                  = $this->language->get('text_pci_yes');
        $this->data['text_pci_no']                   = $this->language->get('text_pci_no');
        $this->data['text_localPayment_yes']         = $this->language->get('text_localPayment_yes');
        $this->data['text_localPayment_no']          = $this->language->get('text_localPayment_no');
        $this->data['text_gateway_timeout']          = $this->language->get('text_gateway_timeout');
        $this->data['text_button_settings']          = $this->language->get('text_button_settings');
        $this->data['text_code']                     = $this->language->get('text_code');
        $this->data['text_symbol']                   = $this->language->get('text_symbol');

        $this->data['entry_test_mode']               = $this->language->get('entry_test_mode');
        $this->data['entry_secret_key']              = $this->language->get('entry_secret_key');
        $this->data['entry_public_key']              = $this->language->get('entry_public_key');
        $this->data['entry_localpayment_enable']     = $this->language->get('entry_localpayment_enable');
        $this->data['entry_payment_url']             = $this->language->get('entry_payment_url');
        $this->data['entry_pci_enable']              = $this->language->get('entry_pci_enable');
        $this->data['entry_payment_action']          = $this->language->get('entry_payment_action');
        $this->data['entry_autocapture_delay']       = $this->language->get('entry_autocapture_delay');
        $this->data['entry_card_type']               = $this->language->get('entry_card_type');
        $this->data['entry_gateway_timeout']         = $this->language->get('entry_gateway_timeout');
        $this->data['entry_successful_order_status'] = $this->language->get('entry_successful_order_status');
        $this->data['entry_failed_order_status']     = $this->language->get('entry_failed_order_status');
        $this->data['entry_sort_order']              = $this->language->get('entry_sort_order');
        $this->data['entry_status']                  = $this->language->get('entry_status');
        $this->data['entry_sort_order']              = $this->language->get('entry_sort_order');
        $this->data['entry_gateway_timeout']         = $this->language->get('entry_gateway_timeout');
        $this->data['entry_logo_url']                = $this->language->get('entry_logo_url');
        $this->data['entry_theme_color']             = $this->language->get('entry_theme_color');
        $this->data['entry_button_color']            = $this->language->get('entry_button_color');
        $this->data['entry_icon_color']              = $this->language->get('entry_icon_color');
        $this->data['entry_currency_format']         = $this->language->get('entry_currency_format');

        $this->data['button_save']                   = $this->language->get('button_save');
        $this->data['button_cancel']                 = $this->language->get('button_cancel');


        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['checkoutapipayment_secret_key'])) {
            $this->data['error_secret_key'] = $this->error['secret_key'];
        } else {
            $this->data['error_secret_key'] = '';
        }

        if (isset($this->error['checkoutapipayment_public_key'])) {
            $this->data['error_public_key'] = $this->error['public_key'];
        } else {
            $this->data['error_public_key'] = '';
        }


        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'text' => $this->language->get('text_home'),
            'href' => makeUrl('common/home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'text' => $this->language->get('text_payment'),
            'href' => makeUrl('extension/payment'),
            'separator' => ' :: '
        );

        $this->document->breadcrumbs[] = array(
            'text' => $this->language->get('heading_title'),
            'href' => makeUrl('payment/checkoutapipayment'),
            'separator' => ' :: '
        );

        $this->data['action'] = makeUrl('payment/checkoutapipayment');

        $this->data['cancel'] = makeUrl('extension/payment');


        if (isset($this->request->post['checkoutapipayment_test_mode'])) {
            $this->data['checkoutapipayment_test_mode'] = $this->request->post['checkoutapipayment_test_mode'];
        } else {
            $this->data['checkoutapipayment_test_mode'] = $this->config->get('checkoutapipayment_test_mode');
        }

        if (isset($this->request->post['checkoutapipayment_secret_key'])) {
            $this->data['checkoutapipayment_secret_key'] = $this->request->post['checkoutapipayment_secret_key'];
        } else {
            $this->data['checkoutapipayment_secret_key'] = $this->config->get('checkoutapipayment_secret_key');
        }

//        if (isset($this->request->post['secret_key'])) {
//            $this->data['secret_key'] = $this->request->post['secret_key'];
//        } else {
//            $this->data['secret_key'] = $this->config->get('secret_key');
//        }

        if (isset($this->request->post['checkoutapipayment_public_key'])) {
            $this->data['checkoutapipayment_public_key'] = $this->request->post['checkoutapipayment_public_key'];
        } else {
            $this->data['checkoutapipayment_public_key'] = $this->config->get('checkoutapipayment_public_key');
        }

        if (isset($this->request->post['checkoutapipayment_localpayment_enable'])) {
            $this->data['checkoutapipayment_localpayment_enable'] = $this->request->post['checkoutapipayment_localpayment_enable'];
        } else {
            $this->data['checkoutapipayment_localpayment_enable'] = $this->config->get('checkoutapipayment_localpayment_enable');
        }

        if (isset($this->request->post['checkoutapipayment_pci_enable'])) {
            $this->data['checkoutapipayment_pci_enable'] = $this->request->post['checkoutapipayment_pci_enable'];
        } else {
            $this->data['checkoutapipayment_pci_enable'] = $this->config->get('checkoutapipayment_pci_enable');
        }

        if (isset($this->request->post['checkoutapipayment_payment_action'])) {
            $this->data['checkoutapipayment_payment_action'] = $this->request->post['checkoutapipayment_payment_action'];
        } else {
            $this->data['checkoutapipayment_payment_action'] = $this->config->get('checkoutapipayment_payment_action');
        }

        if (isset($this->request->post['checkoutapipayment_autocapture_delay'])) {
            $this->data['checkoutapipayment_autocapture_delay'] = $this->request->post['checkoutapipayment_autocapture_delay'];
        } else {
            $this->data['checkoutapipayment_autocapture_delay'] = $this->config->get('checkoutapipayment_autocapture_delay');
        }

        if (isset($this->request->post['checkoutapipayment_gateway_timeout'])) {
            $this->data['checkoutapipayment_gateway_timeout'] = $this->request->post['checkoutapipayment_gateway_timeout'];
        } else {
            $this->data['checkoutapipayment_gateway_timeout'] = $this->config->get('checkoutapipayment_gateway_timeout');
        }

        if (isset($this->request->post['checkoutapipayment_checkout_successful_order'])) {
            $this->data['checkoutapipayment_checkout_successful_order'] = $this->request->post['checkoutapipayment_checkout_successful_order'];
        } else {
            $this->data['checkoutapipayment_checkout_successful_order'] = $this->config->get('checkoutapipayment_checkout_successful_order');
        }

        if (isset($this->request->post['checkoutapipayment_checkout_failed_order'])) {
            $this->data['checkoutapipayment_checkout_failed_order'] = $this->request->post['checkoutapipayment_checkout_failed_order'];
        } else {
            $this->data['checkoutapipayment_checkout_failed_order'] = $this->config->get('checkoutapipayment_checkout_failed_order');
        }

        if (isset($this->request->post['checkoutapipayment_logo_url'])) {
            $this->data['checkoutapipayment_logo_url'] = $this->request->post['checkoutapipayment_logo_url'];
        } else {
            $this->data['checkoutapipayment_logo_url'] = $this->config->get('checkoutapipayment_logo_url');
        }

        if (isset($this->request->post['checkoutapipayment_theme_color'])) {
            $this->data['checkoutapipayment_theme_color'] = $this->request->post['checkoutapipayment_theme_color'];
        } else {
            $this->data['checkoutapipayment_theme_color'] = $this->config->get('checkoutapipayment_theme_color');
        }

        if (isset($this->request->post['checkoutapipayment_button_color'])) {
            $this->data['checkoutapipayment_button_color'] = $this->request->post['checkoutapipayment_button_color'];
        } else {
            $this->data['checkoutapipayment_button_color'] = $this->config->get('checkoutapipayment_button_color');
        }

        if (isset($this->request->post['checkoutapipayment_icon_color'])) {
            $this->data['checkoutapipayment_icon_color'] = $this->request->post['checkoutapipayment_icon_color'];
        } else {
            $this->data['checkoutapipayment_icon_color'] = $this->config->get('checkoutapipayment_icon_color');
        }

        if (isset($this->request->post['checkoutapipayment_currency_format'])) {
            $this->data['checkoutapipayment_currency_format'] = $this->request->post['checkoutapipayment_currency_format'];
        } else {
            $this->data['checkoutapipayment_currency_format'] = $this->config->get('checkoutapipayment_currency_format');
        }

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['checkoutapipayment_status'])) {
            $this->data['checkoutapipayment_status'] = $this->request->post['checkoutapipayment_status'];
        } else {
            $this->data['checkoutapipayment_status'] = $this->config->get('checkoutapipayment_status');
        }

        if (isset($this->request->post['checkoutapipayment_sort_order'])) {
            $this->data['checkoutapipayment_sort_order'] = $this->request->post['checkoutapipayment_sort_order'];
        } else {
            $this->data['checkoutapipayment_sort_order'] = $this->config->get('checkoutapipayment_sort_order');
        }

        $this->template = 'payment/checkoutapi/checkoutapipayment.tpl';
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }


    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/checkoutapipayment')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['checkoutapipayment_secret_key']) {
            $this->error['checkoutapipayment_secret_key'] = $this->language->get('error_secret_key');
        }

        if (!$this->request->post['checkoutapipayment_public_key']) {
            $this->error['checkoutapipayment_public_key'] = $this->language->get('error_public_key');
        }

        return !$this->error;
    }
}
