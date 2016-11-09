<?php 
class ControllerModuleLogin extends Controller {
    private $error = array();

    public function index() {


        $this->language->load('module/login');

        $this->document->title = $this->language->get('heading_title');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_new_customer'] = $this->language->get('text_new_customer');
        $this->data['text_i_am_new_customer'] = $this->language->get('text_i_am_new_customer');
        $this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $this->data['text_create_account'] = $this->language->get('text_create_account');
        $this->data['text_forgotten_password'] = $this->language->get('text_forgotten_password');
        $this->data['text_forgotten_password'] = $this->language->get('text_forgotten_password');
        $this->data['text_remember'] = $this->language->get('text_remember');
        $this->data['text_sign_in'] = $this->language->get('text_sign_in');
        $this->data['site_name'] = 'CLOVEBUY.COM';//$this->config->get('config_name');

        $this->data['entry_email'] = $this->language->get('entry_email_address');
        $this->data['entry_password'] = $this->language->get('entry_password');

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_login'] = $this->language->get('button_login');

        if(isset($this->error['message']))
        {
            $this->data['error'] = @$this->error['message'];
        }
        

        $this->data['action'] = makeUrl('account/login',array(),true);

        if(isset($this->session->data['success']))
        {
           $this->data['success'] = @$this->session->data['success'];
        }else{
            $this->data['success'] = '';
        }

        unset($this->session->data['success']);

        $this->data['continue'] = makeUrl('account/create',array(),true);

        $this->data['forgotten'] = makeUrl('account/forgotten',array(),true);

        $this->document->addScriptInline("var modal_login_url = '".makeUrl('account/login',array(),true)."'");

        $this->id = 'login';
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/login.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/login.tpl';
        } else {
            $this->template = 'default/template/module/login.tpl';
        }

        $this->render();
    }
    private function validate() {
        if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
            $this->error['message'] = $this->language->get('error_login');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>