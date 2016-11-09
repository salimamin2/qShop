<?php

class ControllerAccountHelpCenter extends Controller {

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = HTTPS_SERVER . 'account/help_center';

            $this->redirect(HTTPS_SERVER . 'account/login');
        }

        $this->language->load('account/help_center');
        $this->document->title = $this->language->get('heading_title');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if (strlen($this->request->post['question']) > 0) {

                $this->language->load('mail/account_help');

                $subject = sprintf($this->language->get('text_subject'), $this->request->post['customer_name']);

                $message = sprintf($this->language->get('text_greeting'), $this->request->post['user_name']) . "\n";

                $message .= $this->language->get('text_customer') . "\n\n";
                $message .= $this->language->get('head_customer_name') . $this->request->post['customer_name'] . "\n";
                $message .= $this->language->get('head_customer_email') . $this->request->post['customer_email'] . "\n";
                $message .= $this->language->get('head_customer_tel') . $this->request->post['customer_tel'] . "\n";
                $message .= $this->language->get('text_reason') . "\n";
                $message .= $this->request->post['question'] . "\n";

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($this->request->post['user_email']);
                $mail->setFrom($this->request->post['customer_email']);
                $mail->setSender($this->request->post['customer_name']);
                $mail->setSubject($subject);
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();
                $this->session->data['success'] = $this->language->get('text_success');

                $this->redirect(HTTP_SERVER . 'account/help_center');
            } else {
                $this->redirect(HTTP_SERVER . 'account/help_center&error=1');
            }
        }
        if (isset($this->request->get['error'])) {
            $this->data['error_question'] = $this->language->get('error_question');
        }


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
            'href' => HTTP_SERVER . 'account/help_center',
            'text' => $this->language->get('text_help_center'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        $this->data['text_agent'] = $this->language->get('text_agent');
        $this->data['text_customer_name'] = $this->language->get('text_customer_name');
        $this->data['text_customer_email'] = $this->language->get('text_customer_email');

        $this->data['entry_question'] = $this->language->get('entry_question');

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');

        $this->data['action'] = HTTPS_SERVER . 'account/help_center';
        $this->load->model('account/customer');

        $this->data['customer_name'] = $this->customer->getFirstName() . " " . $this->customer->getLastName();
        $this->data['customer_email'] = $this->customer->getEmail();
        $this->data['customer_id'] = $this->customer->getId();
        $this->data['customer_tel'] = $this->customer->getTelephone();
        $this->data['user_id'] = $this->customer->getUserId();
        $user = $this->model_account_customer->getUser($this->customer->getUserId());
        $this->data['user_name'] = $user['username'];
        $this->data['user_email'] = $user['email'];

        $this->data['back'] = HTTPS_SERVER . 'account/account';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/help_center.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/help_center.tpl';
        } else {
            $this->template = 'default/template/account/help_center.tpl';
        }

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>