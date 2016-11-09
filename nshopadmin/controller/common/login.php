<?php

class ControllerCommonLogin extends Controller {

    private $error = array();

    public function index() {
		$this->load->language('common/login');

		$this->document->title = $this->language->get('heading_title');
		
		//d(';here');

		$this->data['base'] = (HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER;
		$this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		    if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
		    } else {
				$this->redirect(HTTPS_SERVER . 'common/home');
		    }
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_login'] = $this->language->get('text_login');

		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');

		$this->data['button_login'] = $this->language->get('button_login');

		if (isset($this->error['warning'])) {
		    $this->data['error_warning'] = $this->error['warning'];
		} else {
		    $this->data['error_warning'] = '';
		}

		$this->data['action'] = HTTPS_SERVER . 'common/login';

		if (isset($this->request->post['username'])) {
		    $this->data['username'] = $this->request->post['username'];
		} else {
		    $this->data['username'] = '';
		}

		if (isset($this->request->post['password'])) {
		    $this->data['password'] = $this->request->post['password'];
		} else {
		    $this->data['password'] = '';
		}

		if (isset($this->request->get['act'])) {
		    $route = $this->request->get['act'];

		    unset($this->request->get['act']);

		    if (isset($this->request->get['token'])) {
			unset($this->request->get['token']);
		    }

		    $url = '';

		    if ($this->request->get) {
			$url = '&' . http_build_query($this->request->get);
		    }

		    $this->data['redirect'] = HTTPS_SERVER . '' . $route . $url;
		} else {
		    $this->data['redirect'] = '';
		}

		$this->template = 'common/login.tpl';
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
		
    }

    private function validate() {
	if (isset($this->request->post['username']) && isset($this->request->post['password']) && !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
	    $this->error['warning'] = $this->language->get('error_login');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>