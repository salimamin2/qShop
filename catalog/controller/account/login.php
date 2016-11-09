<?php

class ControllerAccountLogin extends Controller {

    private $error = array();

    public function index() {
        //d($this->request->post['checkout_login'],1);
	if ($this->customer->isLogged()) {
		if(isset($this->request->post['checkout_login']) && $this->request->post['checkout_login']=='checkout_login'){
			echo json_encode(array('options' => 'Success Login', 'url' => makeUrl('account/account', array(), true, true)));
        	exit();
		}
    	$this->redirect(makeUrl('account/account', array(), true, true));
	}
	$this->language->load('account/login');
	$this->load->model('tool/seo_url');
	$this->document->title = $this->language->get('heading_title');
	if (( $this->request->server['REQUEST_METHOD'] == 'POST')) {
	    if (isset($this->request->post['account'])) {
			$this->session->data['account'] = $this->request->post['account'];

			if ($this->request->post['account'] == 'guest') {
			    $this->redirect(HTTPS_SERVER . 'checkout/guest_step_1');
			}
	    }
	    if (isset($this->request->post['account']) && $this->request->post['account'] == 'register' && $this->validateForm()) {
			$this->session->data['account'] = $this->request->post['account'];
			$this->load->model('account/customer');
			$this->load->model('account/address');
			$this->model_account_address->addAddress($this->request->post);
			$this->model_account_customer->addCustomer($this->request->post);
			unset($this->session->data['guest']);
			$this->customer->login($this->request->post['email'], $this->request->post['password']);
			$this->language->load('mail/account_create');
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
			if (!$this->config->get('config_customer_approval')) {
			    $message .= $this->language->get('text_login') . "\n";
			} else {
			    $message .= $this->language->get('text_approval') . "\n";
			}
			$message .= makeUrl('account/login', array(), true, true) . "\n\n";
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
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			if ($this->cart->hasProducts()) {
			    $this->redirect(makeUrl('checkout/confirm', array(), true, true));
			} else {
			    $this->redirect(makeUrl('account/success', array(), true, true));
			}
	    } else if (isset($this->request->post['email']) && isset($this->request->post['password']) && $this->request->post['account'] != 'register' && $this->request->post['account'] != 'guest') {
	    	$aResults=array();
	    	if($this->validate()){
				unset($this->session->data['guest']);

	            $this->data['addresses'] = array();
	            $this->data['shipping_addresses'] = array();

	            $this->load->model('account/address');

	            if ($this->customer->getId()) {
	                $results = $this->model_account_address->getAddresses();

	                foreach ($results as $result) {
	                    $this->data['addresses'][] = array(
	                        'address_id' => $result['address_id'],
	                        // 'address' => '<strong>' . $result['firstname'] . ' ' . $result['lastname'] . ' </strong><br />' . $result['address_1'] . '<br /> ' . $result['city'] . ', ' . (($result['zone']) ? $result['zone'] . ', ' : FALSE) . (($result['postcode']) ? $result['postcode'] . ', ' : FALSE) . '<br />' . $result['country'] . '<br/>T: ' . ($result['company'] ? $result['company'] : '-'),
	                        'address' => $result['address_1'] . ',' . $result['city'] . ', ' . (($result['zone']) ? $result['zone'] . ', ' : FALSE) . (($result['postcode']) ? $result['postcode'] . ', ' : FALSE) . '' . $result['country'] . ',' . ($result['company'] ? $result['company'] : '-'),
	                        'href' => HTTPS_SERVER . 'account/address&address_id=' . $result['address_id']
	                    );
	                    if (isset($this->session->data['calculate_country_id'])) {
	                        if ($result['country_id'] == $this->session->data['calculate_country_id']) {
	                            $this->data['shipping_addresses'][] = array(
	                                'address_id' => $result['address_id'],
	                                /*'address' => '<strong>' . $result['firstname'] . ' ' . $result['lastname'] . ' </strong><br />' . $result['address_1'] . '<br /> ' . $result['city'] . ', ' . (($result['zone']) ? $result['zone'] . ', ' : FALSE) . (($result['postcode']) ? $result['postcode'] . ', ' : FALSE) . '<br />' . $result['country'] . '<br/>T: ' . ($result['company'] ? $result['company'] : '-'),*/
	                                'address' => $result['address_1'] . ',' . $result['city'] . ', ' . (($result['zone']) ? $result['zone'] . ', ' : FALSE) . (($result['postcode']) ? $result['postcode'] . ', ' : FALSE) . '' . $result['country'] . ',' . ($result['company'] ? $result['company'] : '-'),
	                                'href' => HTTPS_SERVER . 'account/address&address_id=' . $result['address_id']
	                            );
	                        }
	                    }
	                }

	                if (!isset($this->session->data['calculate_country_id']) || empty($this->session->data['calculate_country_id'])) {
	                    $this->data['shipping_addresses'] = $this->data['addresses'];
	                }
	            }
	            
				if ($this->cart->hasProducts()) {
		            if(isset($this->request->post['checkout_login']) && $this->request->post['checkout_login']=='checkout_login'){
		                $aResults['options']='successfully logged in';
		                $aResults['addresses']=$this->data['shipping_addresses'];		                
		                $aResults['url']=makeUrl('account/account', array(), true, true);
		            }
		            else{
		                $this->redirect(makeUrl('checkout/confirm', array(), true, true));
		            }
				} else {
		            if(isset($this->request->post['checkout_login']) && $this->request->post['checkout_login']=='checkout_login'){
		                $aResults['options']='successfully logged in';
		                $aResults['url']=makeUrl('account/account', array(), true, true);
		                // d($aResults);
		                echo json_encode($aResults);
		            }
		            else{
		                $this->redirect(makeUrl('account/account', array(), true, true));
		            }
				}
		    }
	        else{
	            $aResults['error']=$this->error['message'];
	        }
	        if(isset($this->request->post['checkout_login']) && $this->request->post['checkout_login']=='checkout_login'){
	        	echo json_encode($aResults);
	        	exit();
	        }
	    }
	}
	$this->document->breadcrumbs = array();
	$this->document->breadcrumbs[] = array('href' => makeUrl('common/home', array(), true), 'text' => $this->language->get('text_home'), 'separator' => $this->language->get('text_separator'));
	$this->document->breadcrumbs[] = array('href' => makeUrl('account/account', array(), true, true), 'text' => $this->language->get('text_account'), 'separator' => $this->language->get('text_separator'));
	$this->document->breadcrumbs[] = array('href' => makeUrl('account/login', array(), true, true), 'text' => $this->language->get('text_login'), 'separator' => FALSE);
	$this->data['heading_title'] = $this->language->get('heading_title');
	$this->data['text_new_customer'] = $this->language->get('text_new_customer');
	$this->data['text_i_am_new_customer'] = $this->language->get('text_i_am_new_customer');
	$this->data['text_checkout'] = $this->language->get('text_checkout');
	$this->data['text_account'] = $this->language->get('text_account');
	$this->data['text_register'] = $this->language->get('text_register');
	$this->data['text_guest'] = $this->language->get('text_guest');
	$this->data['text_create_account'] = $this->language->get('text_create_account');
	$this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
	$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
	$this->data['text_forgotten_password'] = $this->language->get('text_forgotten_password');
	$this->data['entry_email'] = $this->language->get('entry_email_address');
	$this->data['entry_password'] = $this->language->get('entry_password');
	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['button_guest'] = $this->language->get('button_guest');
	$this->data['button_login'] = $this->language->get('button_login');
	$this->data['text_yes'] = $this->language->get('text_yes');
	$this->data['text_no'] = $this->language->get('text_no');
	$this->data['text_select'] = $this->language->get('text_select');
	$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), HTTPS_SERVER . 'account/login');
	$this->data['text_your_details'] = $this->language->get('text_your_details');
	$this->data['text_your_address'] = $this->language->get('text_your_address');
	$this->data['text_your_password'] = $this->language->get('text_your_password');
	$this->data['text_newsletter'] = $this->language->get('text_newsletter');
	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
	$this->data['entry_email'] = $this->language->get('entry_email');
	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
	$this->data['entry_fax'] = $this->language->get('entry_fax');
	$this->data['entry_company'] = $this->language->get('entry_company');
	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
	$this->data['entry_city'] = $this->language->get('entry_city');
	$this->data['entry_country'] = $this->language->get('entry_country');
	$this->data['entry_zone'] = $this->language->get('entry_zone');
	$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
	$this->data['entry_password'] = $this->language->get('entry_password');
	$this->data['entry_confirm'] = $this->language->get('entry_confirm');

	if (isset($this->error['message'])) {
	    $this->data['error'] = $this->error['message'];
	} else {
	    $this->data['error'] = '';
	}

	$this->data['action'] = makeUrl('account/login', array(), true, true);
	$this->data['register'] = makeUrl('account/create', array(), true, true);

	if (isset($this->request->post['redirect'])) {
	    $this->data['redirect'] = $this->request->post['redirect'];
	} elseif (isset($this->session->data['redirect'])) {
	    $this->data['redirect'] = $this->session->data['redirect'];
	    unset($this->session->data['redirect']);
	} else {
	    $this->data['redirect'] = '';
	}
	if (isset($this->session->data['success'])) {
	    $this->data['success'] = $this->session->data['success'];
	    unset($this->session->data['success']);
	} else {
	    $this->data['success'] = '';
	}
	if (isset($this->request->post['account'])) {
	    $this->data['account'] = $this->request->post['account'];
	} elseif (isset($this->session->data['account'])) {
	    $this->data['account'] = $this->session->data['account'];
	} else {
	    $this->data['account'] = 'register';
	}
	if (isset($this->error['firstname'])) {
	    $this->data['error_firstname'] = $this->error['firstname'];
	} else {
	    $this->data['error_firstname'] = '';
	}
	if (isset($this->error['lastname'])) {
	    $this->data['error_lastname'] = $this->error['lastname'];
	} else {
	    $this->data['error_lastname'] = '';
	}
	if (isset($this->error['email'])) {
	    $this->data['error_email'] = $this->error['email'];
	} else {
	    $this->data['error_email'] = '';
	}
	if (isset($this->error['telephone'])) {
	    $this->data['error_telephone'] = $this->error['telephone'];
	} else {
	    $this->data['error_telephone'] = '';
	}
	if (isset($this->error['password'])) {
	    $this->data['error_password'] = $this->error['password'];
	} else {
	    $this->data['error_password'] = '';
	}
	if (isset($this->error['confirm'])) {
	    $this->data['error_confirm'] = $this->error['confirm'];
	} else {
	    $this->data['error_confirm'] = '';
	}
	if (isset($this->error['address_1'])) {
	    $this->data['error_address_1'] = $this->error['address_1'];
	} else {
	    $this->data['error_address_1'] = '';
	}
	if (isset($this->error['city'])) {
	    $this->data['error_city'] = $this->error['city'];
	} else {
	    $this->data['error_city'] = '';
	}
	if (isset($this->error['country'])) {
	    $this->data['error_country'] = $this->error['country'];
	} else {
	    $this->data['error_country'] = '';
	}
	if (isset($this->error['zone'])) {
	    $this->data['error_zone'] = $this->error['zone'];
	} else {
	    $this->data['error_zone'] = '';
	}
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
	if (isset($this->request->post['fax'])) {
	    $this->data['fax'] = $this->request->post['fax'];
	} else {
	    $this->data['fax'] = '';
	}
	if (isset($this->request->post['company'])) {
	    $this->data['company'] = $this->request->post['company'];
	} else {
	    $this->data['company'] = '';
	}
	if (isset($this->request->post['address_1'])) {
	    $this->data['address_1'] = $this->request->post['address_1'];
	} else {
	    $this->data['address_1'] = '';
	}
	if (isset($this->request->post['address_2'])) {
	    $this->data['address_2'] = $this->request->post['address_2'];
	} else {
	    $this->data['address_2'] = '';
	}
	if (isset($this->request->post['postcode'])) {
	    $this->data['postcode'] = $this->request->post['postcode'];
	} else {
	    $this->data['postcode'] = '';
	}
	if (isset($this->request->post['city'])) {
	    $this->data['city'] = $this->request->post['city'];
	} else {
	    $this->data['city'] = '';
	}
	if (isset($this->request->post['country_id'])) {
	    $this->data['country_id'] = $this->request->post['country_id'];
	} else {
	    $this->data['country_id'] = $this->config->get('config_country_id');
	}
	if (isset($this->request->post['zone_id'])) {
	    $this->data['zone_id'] = $this->request->post['zone_id'];
	} else {
	    $this->data['zone_id'] = 'FALSE';
	}
	$this->load->model('localisation/country');
	$this->data['countries'] = $this->model_localisation_country->getCountries();
	if (isset($this->request->post['password'])) {
	    $this->data['password'] = $this->request->post['password'];
	} else {
	    $this->data['password'] = '';
	}
	if (isset($this->request->post['confirm'])) {
	    $this->data['confirm'] = $this->request->post['confirm'];
	} else {
	    $this->data['confirm'] = '';
	}
	if (isset($this->request->post['newsletter'])) {
	    $this->data['newsletter'] = $this->request->post['newsletter'];
	} else {
	    $this->data['newsletter'] = '';
	}
	if ($this->config->get('config_account_id')) {
	    $this->load->model('catalog/information');

	    $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

	    if ($information_info) {
		$this->data['text_agree'] = sprintf($this->language->get('text_agree'), makeUrl('information/information/loadInfo',array('information_id=' . $this->config->get('config_account_id')),true), $information_info['title']);
	    } else {
		$this->data['text_agree'] = '';
	    }
	} else {
	    $this->data['text_agree'] = '';
	}

	if (isset($this->request->post['agree'])) {
	    $this->data['agree'] = $this->request->post['agree'];
	} else {
	    $this->data['agree'] = FALSE;
	}

	$this->data['forgotten'] = makeUrl('account/forgotten', array(), true, true);
	$this->data['guest_checkout'] = false; //( $this->config->get('config_guest_checkout') && $this->cart->hasProducts() && !$this->cart->hasDownload());
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/account/login.tpl';
	} else {
	    $this->template = 'default/template/account/login.tpl';
	}

	$this->document->addScriptInline('var dataForm = new VarienForm("login_create", true);', Document::POS_END);
	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
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

    private function validateForm() {
	$this->load->model('account/customer');
	if ($this->model_account_customer->getTotalCustomersByEmailNewsletter($this->request->post['email'])) {
	    $this->error['message'] = $this->language->get('error_exists');
	}

	if ($this->config->get('config_account_id')) {
	    $this->load->model('catalog/information');

	    $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

	    if ($information_info) {
		if (!isset($this->request->post['agree'])) {
		    $this->error['message'] = sprintf($this->language->get('error_agree'), $information_info['title']);
		}
	    }
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>