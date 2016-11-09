<?php
class ControllerAccountCreate extends Controller {
    private $error = array();
    public function index() {
		$this->load->model('tool/seo_url');
		if ($this->customer->isLogged()) {
		    $this->redirect(makeUrl('account/account', array(), true, true));
		}
		$this->document->loadKnow = false;
		$this->language->load('account/create');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('account/customer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if($this->request->post && $this->validate()){
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
		        $ptn = "/^0/";
		        $rpltxt = "92";
		        $k = $this->request->post['telephone'];
		        $a = substr($k, 0, 1);
		       	if($a=='+') {
		           $smsnumber = ltrim($this->request->post['telephone'], $a);
		       	}
		        else{
		            $smsnumber=preg_replace($ptn, $rpltxt, $this->request->post['telephone']);
		        }
		        //$smsnumber=preg_replace($ptn, $rpltxt, $str);
		        $sms_username=$this->config->get('config_sms_username');
		        $sms_password=$this->config->get('config_sms_password');
		        $sms_mask=$this->config->get('config_sms_mask');
		        $send_sms=$this->config->get('config_sms_alert_mail');
		        if($send_sms=='1') {
		            if (!empty($sms_username)) {
		                $mail->sendSms($message, $smsnumber, $sms_username, $sms_password, $sms_mask);
		            }
		        }
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
			    $mail->setHtml(html_entity_decode(nl2br($message), ENT_QUOTES, 'UTF-8'));
			    $mail->send();
	            if(isset($this->request->post['checkout_login'])) {
	            	$this->session->data['checkout_register'] = true;
	                echo json_encode(array('success' => $this->language->get('text_account_success')));
	                exit();
	            }
			    if ($this->cart->hasProducts()) {
	                $this->redirect(makeUrl('checkout/confirm', array(), true, true));
			    } else {
	                $this->redirect(makeUrl('account/success', array(), true, true));
			    }
			}
		    if($this->error && isset($this->request->post['checkout_login'])) {
		        echo json_encode(array('error' => $this->error));
		    }
		}
		$this->data['error'] = $this->error;
		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
		    'href' => makeUrl('common/home', array(), true),
		    'text' => $this->language->get('text_home'),
		    'separator' => $this->language->get('text_separator')
		);
		$this->document->breadcrumbs[] = array(
		    'href' => makeUrl('account/account', array(), true, true),
		    'text' => $this->language->get('text_account'),
		    'separator' => $this->language->get('text_separator')
		);
		$this->document->breadcrumbs[] = array(
		    'href' => makeUrl('account/create', array(), true, true),
		    'text' => $this->language->get('text_create'),
		    'separator' => FALSE
		);
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->model_tool_seo_url->rewrite(HTTPS_SERVER . 'account/login'));
		$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_address'] = $this->language->get('text_your_address');
		$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_confirm_email'] = $this->language->get('entry_confirm_email');
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
		$this->data['button_continue'] = $this->language->get('button_continue');
		if (isset($this->error['warning'])) {
		    $this->data['error_warning'] = $this->error['warning'];
		} else {
		    $this->data['error_warning'] = '';
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
		if (isset($this->error['postcode'])) {
		    $this->data['error_postcode'] = $this->error['postcode'];
		} else {
		    $this->data['error_postcode'] = '';
		}
		if (isset($this->error['zone'])) {
		    $this->data['error_zone'] = $this->error['zone'];
		} else {
		    $this->data['error_zone'] = '';
		}
		$this->data['action'] = makeUrl('account/create', array(), true, true);
		$this->data['login'] = makeUrl('account/login', array(), true, true);
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
		    $zone_id = $this->data['zone_id'] = $this->request->post['zone_id'];
		} else {
		    $zone_id = $this->data['zone_id'] = 'FALSE';
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
				//$this->data['text_agree'] = sprintf($this->language->get('text_agree'), makeUrl('information/information', array('information_id=' . $this->config->get('config_account_id')), true), $information_info['title']);
				$this->data['text_agree'] = $this->language->get('text_agree');
		    } else {
				$this->data['text_agree'] = '';
		    }
		} else {
		    $this->data['text_agree'] = '';
		}
		$privacy_information = $this->model_catalog_information->getInformation('18');
		$this->data['text_privacy'] = sprintf($this->language->get('text_privacy'), makeUrl('information/information', array('information_id=18'), true), $privacy_information['title']);
		if (isset($this->request->post['agree'])) {
		    $this->data['agree'] = $this->request->post['agree'];
		} else {
		    $this->data['agree'] = FALSE;
		}
		$this->data['you_are_here'] = $this->language->get('you_are_here');
		$this->document->addScriptInline('var dataForm = new VarienForm("create",true);', Document::POS_END);
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/create.tpl')) {
		    $this->template = $this->config->get('config_template') . '/template/account/create.tpl';
		} else {
		    $this->template = 'default/template/account/create.tpl';
		}
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate()
    {
    	$aError = array();
        if ((strlen(utf8_decode(trim($this->request->post['firstname']))) < 1) || (strlen(utf8_decode(trim($this->request->post['firstname']))) > 32)) {
            $aError['firstname'] = $this->language->get('error_firstname');
        }
        if ((strlen(utf8_decode(trim($this->request->post['lastname']))) < 1) || (strlen(utf8_decode(trim($this->request->post['lastname']))) > 32)) {
            $aError['lastname'] = $this->language->get('error_lastname');
        }
        $pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';
        if (!preg_match($pattern, $this->request->post['email'])) {
            $aError['email'] = $this->language->get('error_email');
        }
        if(!isset($this->request->post['checkout_login']) || $this->request->post['checkout_login'] != 'checkout_login') {
        	if (!preg_match($pattern, $this->request->post['confirm_email'])) {
	            $aError['confirm_email'] = $this->language->get('error_email');
	        }
	        if($this->request->post['email'] != $this->request->post['confirm_email']){
	        	$aError['confirm_email'] = __('error_confirm_email');
	        }
        }
        if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }
        if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
            $aError['password'] = $this->language->get('error_password');
        }
        if(!isset($this->request->post['checkout_login']) || $this->request->post['checkout_login'] != 'checkout_login') {
	        if ($this->request->post['confirm'] != $this->request->post['password']) {
	            $aError['confirm'] = $this->language->get('error_confirm');
	        }
	        if ($this->config->get('config_account_id')) {
	            $this->load->model('catalog/information');
	            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
	            if ($information_info) {
	                if (!isset($this->request->post['agree'])) {
	                    $this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
	                }
	            }
	        }
        }
        if(!empty($aError)){
        	$aError['warning'] = join("<br />",$aError);
        }
	    return !$this->error;		
    }
}
?>