<?php

class ControllerModuleHybridAuth extends Controller {

    private $error = array();

    public function index() {
	$this->load->language('module/hybrid_auth');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('setting/setting');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

	    Make::a('setting/setting')->create()->editSetting('hybrid', $this->request->post);

	    // $this->session->data['success'] = $this->language->get('text_success');

	    // $this->redirect(makeUrl('extension/module'));
	    $this->data['success'] = $this->language->get('text_success');
	}

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_enabled'] = $this->language->get('text_enabled');
	$this->data['text_disabled'] = $this->language->get('text_disabled');
	$this->data['text_id'] = $this->language->get('text_id');
	$this->data['text_secret'] = $this->language->get('text_secret');
	$this->data['text_scope'] = $this->language->get('text_scope');
	$this->data['text_display'] = $this->language->get('text_display');

	$this->data['entry_facebook'] = $this->language->get('entry_facebook');
	$this->data['entry_google'] = $this->language->get('entry_google');
	$this->data['entry_status'] = $this->language->get('entry_status');
	$this->data['entry_open_id'] = $this->language->get('entry_open_id');
	$this->data['entry_yahoo'] = $this->language->get('entry_yahoo');
	$this->data['entry_aol'] = $this->language->get('entry_aol');
	$this->data['entry_myspace'] = $this->language->get('entry_myspace');
	$this->data['entry_live'] = $this->language->get('entry_live');
	$this->data['entry_foursquare'] = $this->language->get('entry_foursquare');
	$this->data['entry_linkedin'] = $this->language->get('entry_linkedin');
	$this->data['entry_twitter'] = $this->language->get('entry_twitter');

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_cancel'] = $this->language->get('button_cancel');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home'),
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('extension/module'),
	    'text' => $this->language->get('text_module'),
	    'separator' => ' :: '
	);

	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('module/hybrid_auth'),
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->data['action'] = makeUrl('module/hybrid_auth');

	$this->data['cancel'] = makeUrl('extension/module');

	if (isset($this->request->post['hybrid_status'])) {
	    $this->data['hybrid_status'] = $this->request->post['hybrid_status'];
	} else {
	    $this->data['hybrid_status'] = $this->config->get('hybrid_status');
	}

	if (isset($this->request->post['hybrid_open_id'])) {
	    $this->data['hybrid_open_id'] = $this->request->post['hybrid_open_id'];
	} else {
	    $this->data['hybrid_open_id'] = $this->config->get('hybrid_open_id');
	}

	if (isset($this->request->post['hybrid_yahoo_status'])) {
	    $this->data['hybrid_yahoo_status'] = $this->request->post['hybrid_yahoo_status'];
	} else {
	    $this->data['hybrid_yahoo_status'] = $this->config->get('hybrid_yahoo_status');
	}

	if (isset($this->request->post['hybrid_aol_status'])) {
	    $this->data['hybrid_aol_status'] = $this->request->post['hybrid_aol_status'];
	} else {
	    $this->data['hybrid_aol_status'] = $this->config->get('hybrid_aol_status');
	}

	if (isset($this->request->post['hybrid_google_status'])) {
	    $this->data['hybrid_google_status'] = $this->request->post['hybrid_google_status'];
	} else {
	    $this->data['hybrid_google_status'] = $this->config->get('hybrid_google_status');
	}

	if (isset($this->request->post['hybrid_google_id'])) {
	    $this->data['hybrid_google_id'] = $this->request->post['hybrid_google_id'];
	} else {
	    $this->data['hybrid_google_id'] = $this->config->get('hybrid_google_id');
	}

	if (isset($this->request->post['hybrid_google_secret'])) {
	    $this->data['hybrid_google_secret'] = $this->request->post['hybrid_google_secret'];
	} else {
	    $this->data['hybrid_google_secret'] = $this->config->get('hybrid_google_secret');
	}

	if (isset($this->request->post['hybrid_google_scope'])) {
	    $this->data['hybrid_google_scope'] = $this->request->post['hybrid_google_scope'];
	} else {
	    $this->data['hybrid_google_scope'] = $this->config->get('hybrid_google_scope');
	}

	if (isset($this->request->post['hybrid_fb_status'])) {
	    $this->data['hybrid_fb_status'] = $this->request->post['hybrid_fb_status'];
	} else {
	    $this->data['hybrid_fb_status'] = $this->config->get('hybrid_fb_status');
	}

	if (isset($this->request->post['hybrid_fb_id'])) {
	    $this->data['hybrid_fb_id'] = $this->request->post['hybrid_fb_id'];
	} else {
	    $this->data['hybrid_fb_id'] = $this->config->get('hybrid_fb_id');
	}

	if (isset($this->request->post['hybrid_fb_secret'])) {
	    $this->data['hybrid_fb_secret'] = $this->request->post['hybrid_fb_secret'];
	} else {
	    $this->data['hybrid_fb_secret'] = $this->config->get('hybrid_fb_secret');
	}

	if (isset($this->request->post['hybrid_fb_scope'])) {
	    $this->data['hybrid_fb_scope'] = $this->request->post['hybrid_fb_scope'];
	} else {
	    $this->data['hybrid_fb_scope'] = $this->config->get('hybrid_fb_scope');
	}

	if (isset($this->request->post['hybrid_fb_display'])) {
	    $this->data['hybrid_fb_display'] = $this->request->post['hybrid_fb_display'];
	} else {
	    $this->data['hybrid_fb_display'] = $this->config->get('hybrid_fb_display');
	}

	if (isset($this->request->post['hybrid_twitter_status'])) {
	    $this->data['hybrid_twitter_status'] = $this->request->post['hybrid_twitter_status'];
	} else {
	    $this->data['hybrid_twitter_status'] = $this->config->get('hybrid_twitter_status');
	}

	if (isset($this->request->post['hybrid_twitter_id'])) {
	    $this->data['hybrid_twitter_id'] = $this->request->post['hybrid_twitter_id'];
	} else {
	    $this->data['hybrid_twitter_id'] = $this->config->get('hybrid_twitter_id');
	}

	if (isset($this->request->post['hybrid_twitter_secret'])) {
	    $this->data['hybrid_twitter_secret'] = $this->request->post['hybrid_twitter_secret'];
	} else {
	    $this->data['hybrid_twitter_secret'] = $this->config->get('hybrid_twitter_secret');
	}

	if (isset($this->request->post['hybrid_live_status'])) {
	    $this->data['hybrid_live_status'] = $this->request->post['hybrid_live_status'];
	} else {
	    $this->data['hybrid_live_status'] = $this->config->get('hybrid_live_status');
	}

	if (isset($this->request->post['hybrid_live_id'])) {
	    $this->data['hybrid_live_id'] = $this->request->post['hybrid_live_id'];
	} else {
	    $this->data['hybrid_live_id'] = $this->config->get('hybrid_live_id');
	}

	if (isset($this->request->post['hybrid_live_secret'])) {
	    $this->data['hybrid_live_secret'] = $this->request->post['hybrid_live_secret'];
	} else {
	    $this->data['hybrid_live_secret'] = $this->config->get('hybrid_live_secret');
	}

	if (isset($this->request->post['hybrid_myspace_status'])) {
	    $this->data['hybrid_myspace_status'] = $this->request->post['hybrid_myspace_status'];
	} else {
	    $this->data['hybrid_myspace_status'] = $this->config->get('hybrid_myspace_status');
	}

	if (isset($this->request->post['hybrid_myspace_id'])) {
	    $this->data['hybrid_myspace_id'] = $this->request->post['hybrid_myspace_id'];
	} else {
	    $this->data['hybrid_myspace_id'] = $this->config->get('hybrid_myspace_id');
	}

	if (isset($this->request->post['hybrid_myspace_secret'])) {
	    $this->data['hybrid_myspace_secret'] = $this->request->post['hybrid_myspace_secret'];
	} else {
	    $this->data['hybrid_myspace_secret'] = $this->config->get('hybrid_myspace_secret');
	}

	if (isset($this->request->post['hybrid_linkedin_status'])) {
	    $this->data['hybrid_linkedin_status'] = $this->request->post['hybrid_linkedin_status'];
	} else {
	    $this->data['hybrid_linkedin_status'] = $this->config->get('hybrid_linkedin_status');
	}

	if (isset($this->request->post['hybrid_linkedin_id'])) {
	    $this->data['hybrid_linkedin_id'] = $this->request->post['hybrid_linkedin_id'];
	} else {
	    $this->data['hybrid_linkedin_id'] = $this->config->get('hybrid_linkedin_id');
	}

	if (isset($this->request->post['hybrid_linkedin_secret'])) {
	    $this->data['hybrid_linkedin_secret'] = $this->request->post['hybrid_linkedin_secret'];
	} else {
	    $this->data['hybrid_linkedin_secret'] = $this->config->get('hybrid_linkedin_secret');
	}

	if (isset($this->request->post['hybrid_foursquare_status'])) {
	    $this->data['hybrid_foursquare_status'] = $this->request->post['hybrid_foursquare_status'];
	} else {
	    $this->data['hybrid_foursquare_status'] = $this->config->get('hybrid_foursquare_status');
	}

	if (isset($this->request->post['hybrid_foursquare_id'])) {
	    $this->data['hybrid_foursquare_id'] = $this->request->post['hybrid_foursquare_id'];
	} else {
	    $this->data['hybrid_foursquare_id'] = $this->config->get('hybrid_foursquare_id');
	}

	if (isset($this->request->post['hybrid_foursquare_secret'])) {
	    $this->data['hybrid_foursquare_secret'] = $this->request->post['hybrid_foursquare_secret'];
	} else {
	    $this->data['hybrid_foursquare_secret'] = $this->config->get('hybrid_foursquare_secret');
	}

	$this->template = 'module/hybrid_auth.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
	if (!$this->user->hasPermission('modify', 'module/hybrid_auth')) {
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