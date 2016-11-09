<?php

class ControllerModuleHybridAuth extends Controller {

    protected $error = array();

    protected function index() {
	$this->language->load('module/hybrid_auth');

	$this->data['text_social'] = $this->language->get('text_social');

	$this->data['hybrid_auth'] = $this->config->get('hybrid_status');
	
	$this->data['hybrid_social'] = array();
//	if ($this->config->get('hybrid_yahoo_status'))
//	    $this->data['hybrid_social']['Yahoo'] = $this->config->get('hybrid_yahoo_status');
//	if ($this->config->get('hybrid_google_status'))
//	    $this->data['hybrid_social']['Google'] = $this->config->get('hybrid_google_status');
//	if ($this->config->get('hybrid_fb_status'))
	    $this->data['hybrid_social']['Facebook'] = 'image/icon/social_fb_on.png';
//	if ($this->config->get('hybrid_twitter_status'))
//	    $this->data['hybrid_social']['Twitter'] = $this->config->get('hybrid_twitter_status');
//	if ($this->config->get('hybrid_linkedin_status'))
//	    $this->data['hybrid_social']['LinkedIn'] = $this->config->get('hybrid_linkedin_status');
//	if ($this->config->get('hybrid_live_status'))
//	    $this->data['hybrid_social']['Live'] = $this->config->get('hybrid_live_status');
//	if ($this->config->get('hybrid_aol_status'))
//	    $this->data['hybrid_social']['AOL'] = $this->config->get('hybrid_aol_status');
//	if ($this->config->get('hybrid_myspace_status'))
//	    $this->data['hybrid_social']['MySpace'] = $this->config->get('hybrid_myspace_status');
//	if ($this->config->get('hybrid_foursquare_status'))
//	    $this->data['hybrid_social']['Foursquare'] = $this->config->get('hybrid_foursquare_status');

	$this->id = 'hybrid_auth';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/hybrid_auth.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/hybrid_auth.tpl';
	} else {
	    $this->template = 'default/template/module/hybrid_auth.tpl';
	}

	$this->render();
    }

    public function hauth() {

	//$this->log->write('debug', "controllers.HAuth.login($provider) called");
	if (isset($this->request->get['provider'])) {
	    $provider = $this->request->get['provider'];
	} else {
	    $this->redirect(makeUrl('account/login',array(),true));
	}

	try {
	    //	log_message('debug', 'controllers.HAuth.login: loading HybridAuthLib');
	    //$this->load->library('HybridAuthLib');
	    require_once DIR_ROOT . '/hybridauth_conf.php';
	    $this->hybridauth = new HybridAuth($_hybrd_config);

	    if ($this->hybridauth->serviceEnabled($provider)) {
		//log_message('debug', "controllers.HAuth.login: service $provider enabled, trying to authenticate.");
		$service = $this->hybridauth->authenticate($provider);

		if ($service->isUserConnected()) {

		    //log_message('debug', 'controller.HAuth.login: user authenticated.');

		    $user_profile = $service->getUserProfile();

		    //  d($user_profile,true);

		    if (property_exists($user_profile, 'email') && $user_profile->email) {
			$this->loginUser($user_profile);
		    } else {  // Email Not Found
			$bUser = false;
			if ($user_profile->displayName) {
			    $this->load->model('account/customer');
			    $aCustomer = $this->model_account_customer->getCustomerByName($user_profile->displayName);
			    if ($aCustomer && isset($aCustomer['email']) && $aCustomer['email']) {
				$user_profile->email = $aCustomer['email'];
				$this->loginUser($user_profile);
				$bUser = true;
			    }
			}

			if (!$bUser) {
			    $this->session->data['user_profile'] = $this->objectToArray($user_profile);
			    $this->session->data['provider'] = $provider;
			    $this->redirect(makeUrl('common/signup',array(),true));
			}
		    }

		    //$data['user_profile'] = $user_profile;
		} else { // Cannot authenticate user
		    //show_error('Cannot authenticate user');
		    $error = 'Authentication Failed!';
		}
	    } else { // This service is not enabled.
		$error = 'Service is not available at the moment. Please try again!';
	    }
	} catch (Exception $e) {
	    $error = 'Unexpected error';
	    switch ($e->getCode()) {
		case 0 : $error = 'Unspecified error.';
		    break;
		case 1 : $error = 'Hybriauth configuration error.';
		    break;
		case 2 : $error = 'Provider not properly configured.';
		    break;
		case 3 : $error = 'Unknown or disabled provider.';
		    break;
		case 4 : $error = 'Missing provider application credentials.';
		    break;
		case 5 : if (isset($service)) {
			$service->logout();
		    }
		    $error = 'You have cancelled the authentication or the provider refused the connection.';
		    break;
		case 6 : $error = 'Profile request failed. Most likely that you are not connected to the provider and you should need to authenticate again.';
		    break;
		case 7 : $error = 'User not connected to the provider.';
		    break;
	    }
	}
	if (isset($error) && $error) {
	    $this->error['warning'] = 'Error authenticating user.<br />';
	    $this->error['warning'] .= $error;
	    $this->session->data['error'] = $this->error['warning'];
	}
	$this->redirect(makeUrl('account/login',array(),true));
    }

    public function loginUser($oUser = false) {
	if ($oUser) {
	    $user_email = $oUser->email;
	    $user_firstname = $oUser->firstName;
	    $user_lastname = $oUser->lastName;
	    $user_displayname = $oUser->displayName;
	} else if (isset($this->request->post['user'])) {
	    $user_email = $this->request->post['user']['email'];
	    $user_firstname = $this->request->post['user']['firstname'];
	    $user_lastname = $this->request->post['user']['lastname'];
	    $user_displayname = $this->request->post['user']['displayname'];
	    unset($this->session->data['user_profile']);
	    unset($this->session->data['provider']);
	}

	$this->load->model('account/customer');

	$aCustomer = $this->model_account_customer->getCustomerByEmail($user_email, true);
	if (empty($aCustomer)) {
	    $this->session->data['account'] = 'register';
	    $customer_firstname = $user_firstname;

	    $password = substr(md5(time()), 0, 6);
	    $aData = array(
		'firstname' => $user_firstname,
		'lastname' => $user_lastname,
		'displayname' => $user_displayname,
		'password' => $password,
		'email' => $user_email,
		'telephone' => '',
		'fax' => '',
		'newsletter' => 1
	    );
	    $this->model_account_customer->addCustomer($aData, true);
	    unset($this->session->data['guest']);
	    $this->customer->login($aData['email'], $password);

	    $this->language->load('mail/account_create');

	    $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
	    $message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
	    $message .= $this->language->get('text_account') . "\n";
	    $message .= "Email Address: " . $aData['email'] . "\n";
	    $message .= "Password: " . $aData['password'] . "\n";
	    $message .= $this->language->get('text_social_login') . "\n";
	    $message .= HTTPS_SERVER . 'index.php?route=account/account' . "\n\n";
	    $message .= $this->language->get('text_services') . "\n\n";
	    $message .= $this->language->get('text_thanks') . "\n";
	    $message .= $this->config->get('config_name');
	    $mail = new Mail();
	    $mail->protocol = $this->config->get('config_mail_protocol');
	    $mail->hostname = $this->config->get('config_smtp_host');
	    $mail->username = $this->config->get('config_smtp_username');
	    $mail->password = $this->config->get('config_smtp_password');
	    $mail->port = $this->config->get('config_smtp_port');
	    $mail->timeout = $this->config->get('config_smtp_timeout');
	    $mail->setTo($aData['email']);
	    $mail->setFrom($this->config->get('config_email'));
	    $mail->setSender($this->config->get('config_name'));
	    $mail->setSubject($subject);
	    $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
	    $mail->send();
	} else {
	    $this->session->open();
	    $this->session->data['logged_in'] = true;
	    $this->customer->initCustomer($aCustomer);
	    $customer_firstname = $aCustomer['firstname'];
	}
	if ($customer_firstname == '') {
	    $this->redirect(makeUrl('account/edit',array(),true));
	} else {
	    if (isset($this->session->data['redirect'])) {
		$this->redirect($this->session->data['redirect']);
		unset($this->session->data['redirect']);
	    } else {
		$this->redirect(makeUrl('account/account',array(),true));
	    }
	}
    }

    public function endpoint() {
	require_once DIR_SYSTEM . 'library/hybrid/index.php';
    }

    public function objectToArray($d) {
	if (is_object($d)) {
	    // Gets the properties of the given object
	    // with get_object_vars function
	    $d = get_object_vars($d);
	}

	if (is_array($d)) {
	    /*
	     * Return array converted to object
	     * Using __FUNCTION__ (Magic constant)
	     * for recursive call
	     */
	    return array_map(array($this, 'objectToArray'), $d);
	} else {
	    // Return array
	    return $d;
	}
    }

}

?>