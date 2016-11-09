<?php

class ControllerAccountReturn extends Controller
{

    private $error = array();

    public function index()
    {

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = makeUrl('account/return', array(), true, true);

            $this->redirect(makeUrl('account/login', array(), true, true));
        }

        $this->load->model('tool/seo_url');
        $this->language->load('account/account');
        $this->language->load('account/return');
        $this->load->model('catalog/product_description');
        $this->document->title = $this->language->get('heading_title');
        $this->document->layout_col = "col2-left-layout";

        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home', array(), true, true),
            'text' => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('account/account', array(), true, true),
            'text' => $this->language->get('text_account'),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('account/history', array(), true, true),
            'text' => $this->language->get('text_history'),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('account/return', array(), true, true),
            'text' => $this->language->get('text_return'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['text_account_information'] = $this->language->get('text_account_information');
        $this->data['text_contact_information'] = $this->language->get('text_contact_information');
        $this->data['text_my_orders'] = $this->language->get('text_my_orders');
        $this->data['text_orders'] = $this->language->get('text_orders');
        $this->data['text_address_book'] = $this->language->get('text_address_book');

        //$this->load->model('catalog/product_description');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                $customer_id = $this->customer->getId();
                $date_added = date('Y-m-d');
                $fields =$this->request->post;
                d($fields);
                $product_name = $this->request->post['product'];
                $products = Make::a('catalog/product_description')->create()->getData($product_name);
                $returnitem = Make::a('account/return')->create();
                    $returnitem->setFields($fields);
                    $returnitem->customer_id = $customer_id;
                    $returnitem->date_added = $date_added;
                    $returnitem->product_id = $products;
                $returnitem->save();
                $this->session->data['success'] = $this->language->get('text_insert');
                $this->redirect(makeUrl('account/account', array(), true, true));
        }
        $this->data['back'] = makeUrl('account/address', array(), true, true);

        if (!empty($this->error)) {
            $this->data['errors'] = $this->error;
        }
        else
            $this->data['errors'] = "";

        if (isset($this->request->post['firstname'])) {
            $this->data['firstname'] = $this->request->post['firstname'];
        } else {
            $this->data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $this->data['lastname'] = $this->request->post['lastname'];
        } else {
            $this->data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } else {
            $this->data['email'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $this->data['telephone'] = $this->request->post['telephone'];
        } else {
            $this->data['telephone'] = '';
        }

        if (isset($this->request->post['order_id'])) {
            $this->data['order_id'] = $this->request->post['order_id'];
        } else {
            $this->data['order_id'] = '';
        }

        if (isset($this->request->post['product'])) {
            $this->data['product'] = $this->request->post['product'];
        } else {
            $this->data['product'] = '';
        }

        if (isset($this->request->post['model'])) {
            $this->data['model'] = $this->request->post['model'];
        } else {
            $this->data['model'] = '';
        }

        if (isset($this->request->post['quantity'])) {
            $this->data['quantity'] = $this->request->post['quantity'];
        } else {
            $this->data['quantity'] = '';
        }

        $layout = 'account/return.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $layout)) {
            $this->template = $this->config->get('config_template') . '/template/' . $layout;
        } else {
            $this->template = 'default/template/account/return.tpl' . $layout;
        }

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));

    }

    private function validateForm() {

        if (empty($this->request->post['firstname'])) {
            $this->data['nameErr'] = $this->language->get('error_empty');
            return FALSE;
        }
        elseif ((strlen(utf8_decode(trim($this->request->post['firstname']))) < 3) || (strlen(utf8_decode(trim($this->request->post['firstname']))) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if (empty($this->request->post['lastname'])) {
            $this->data['nameErr'] = $this->language->get('error_empty');
            return FALSE;
        }
        elseif ((strlen(utf8_decode(trim($this->request->post['lastname']))) < 3) || (strlen(utf8_decode(trim($this->request->post['lastname']))) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if (empty($this->request->post['telephone'])) {
            $this->data['nameErr'] = $this->language->get('error_empty');
            return FALSE;
        }
        elseif ((strlen(utf8_decode(trim($this->request->post['telephone']))) < 7)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (empty($this->request->post['email'])) {
            $this->data['nameErr'] = $this->language->get('error_empty');
            return FALSE;
        }
        elseif (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (empty($this->request->post['order_id'])) {
            $this->data['nameErr'] = $this->language->get('error_empty');
            return FALSE;
        }
        elseif(!preg_match('/^\d+$/',$this->request->post['order_id'])) {
            $this->error['order_id'] = $this->language->get('error_order_id');
        }

        if (empty($this->request->post['product'])) {
            $this->data['nameErr'] = $this->language->get('error_empty');
            return FALSE;
        }
        elseif (!preg_match("/^[a-zA-Z ]*$/",$this->request->post['product']) || (strlen(utf8_decode(trim($this->request->post['product']))) < 3)) {
            $this->error['product'] = $this->language->get('error_product_name');
        }

        if (empty($this->request->post['model'])) {
            $this->data['nameErr'] = $this->language->get('error_empty');
            return FALSE;
        }
        elseif(preg_match("(?:\s*[a-zA-Z0-9]{2,}\s*)*",$this->request->post['model'])) {
            $this->error['model'] = $this->language->get('error_model');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }


    }
}


//<?php
//class ControllerAccountReturn extends Controller {
//    public function index() {
//        if (!$this->customer->isLogged()) {
//            $this->session->data['redirect'] = HTTPS_SERVER . 'account/return';
//
//            $this->redirect(HTTPS_SERVER . 'account/login');
//        }
//
//        $this->language->load('account/return');
//        $this->document->title = $this->language->get('heading_title');
//
//        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
//            $this->request->post = $this->shortcodes->strip_shortcodes($this->request->post);
//            if (strlen($this->request->post['reason']) > 0) {
//
//                $this->language->load('mail/order_return');
//
//                $subject = sprintf($this->language->get('text_subject'), $this->request->post['order_id']);
//
//                $message = sprintf($this->language->get('text_greeting'), $this->request->post['user_name']) . "\n";
//
//                $message .= $this->language->get('text_customer')."\n\n";
//                $message .= $this->language->get('head_customer_name').$this->request->post['customer_name'] . "\n";
//                $message .= $this->language->get('head_customer_email').$this->request->post['customer_email'] . "\n";
//                $message .= $this->language->get('head_customer_tel').$this->request->post['customer_tel'] . "\n";
//                $message .= $this->language->get('text_reason'). "\n";
//                $message .= $this->request->post['reason'] . "\n";
//
//                $mail = new Mail();
//                $mail->protocol = $this->config->get('config_mail_protocol');
//                $mail->hostname = $this->config->get('config_smtp_host');
//                $mail->username = $this->config->get('config_smtp_username');
//                $mail->password = $this->config->get('config_smtp_password');
//                $mail->port = $this->config->get('config_smtp_port');
//                $mail->timeout = $this->config->get('config_smtp_timeout');
//                $mail->setTo($this->request->post['user_email']);
//                $mail->setFrom($this->request->post['customer_email']);
//                $mail->setSender($this->request->post['customer_name']);
//                $mail->setSubject($subject);
//                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
//                $mail->send();
//                $this->session->data['success'] = $this->language->get('text_success');
//
//                $this->redirect(HTTP_SERVER . 'account/account');
//            }
//            else {
//                $this->redirect(HTTP_SERVER . 'account/return&order_id='.$this->request->get['order_id'].'&error=1');
//            }
//
//        }
//        if(isset($this->request->get['error'])){
//            $this->data['error_reason'] = $this->language->get('error_reason');
//        }
//
//
//
//
//        $this->data['heading_title'] = $this->language->get('heading_title');
//
//        $this->data['text_yes'] = $this->language->get('text_yes');
//        $this->data['text_no'] = $this->language->get('text_no');
//        $this->data['text_order'] = $this->language->get('text_order');
//        $this->data['text_agent'] = $this->language->get('text_agent');
//        $this->data['text_customer_name'] = $this->language->get('text_customer_name');
//        $this->data['text_customer_email'] = $this->language->get('text_customer_email');
//
//        $this->data['entry_reason'] = $this->language->get('entry_reason');
//
//        $this->data['button_continue'] = $this->language->get('button_continue');
//        $this->data['button_back'] = $this->language->get('button_back');
//
//        $this->data['action'] = HTTPS_SERVER . 'account/return';
//        $this->load->model('account/customer');
//
//        $this->data['customer_name'] = $this->customer->getFirstName()." ".$this->customer->getLastName();
//        $this->data['customer_email'] = $this->customer->getEmail();
//        $this->data['customer_id'] = $this->customer->getId();
//        $this->data['customer_tel'] = $this->customer->getTelephone();
//        $this->data['user_id'] = $this->customer->getUserId();
//        $this->data['order_id'] = $this->request->get['order_id'];
//        $user = $this->model_account_customer->getUser($this->customer->getUserId());
//        $this->data['user_name'] =$user['username'];
//        $this->data['user_email'] =$user['email'];
//
//        $this->data['back'] = HTTPS_SERVER . 'account/history';
//
//        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/return.tpl')) {
//            $this->template = $this->config->get('config_template') . '/template/account/return.tpl';
//        } else {
//            $this->template = 'default/template/account/return.tpl';
//        }
//
//        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
//    }
//}
//?>