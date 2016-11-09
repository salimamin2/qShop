<?php 
class ControllerSaleContact extends Controller {
	private $error = array();
	 
	public function index() {
		$this->load->language('sale/contact');
 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer');
		$sent = false;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/store');
            $store_name = $this->config->get('config_name');
			$emails = array();
			
			// All customers by group
			if (isset($this->request->post['group'])) {
				switch ($this->request->post['group']) {
					case 'newsletter':
						$results = $this->model_sale_customer->getCustomersByNewsletter();
					
						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}
						break;
					case 'customer':
						$results = $this->model_sale_customer->getCustomers();
				
						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}						
						break;
				}
			}
			
			// All customers by name/email
			if (isset($this->request->post['to']) && $this->request->post['to']) {					
				foreach ($this->request->post['to'] as $customer_id) {
					$customer_info = $this->model_sale_customer->getCustomer($customer_id);
					
					if ($customer_info) {
						$emails[] = $customer_info['email'];
					}
				}
			}
			
			// All customers by product
			if (isset($this->request->post['product'])) {
				foreach ($this->request->post['product'] as $product_id) {
					$results = $this->model_sale_customer->getCustomersByProduct($product_id);
					
					foreach ($results as $result) {
						$emails[] = $result['email'];
					}
				}
			}
			
			// Prevent Duplicates
			$emails = array_unique($emails);
			
			if ($emails) {
				$message  = '<html dir="ltr" lang="en">' . "\n";
				$message .= '<head>' . "\n";
				$message .= '<title>' . $this->request->post['subject'] . '</title>' . "\n";
				$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '</head>' . "\n";
				$message .= '<body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
				$message .= '</html>' . "\n";

				$attachments = array();

				if (preg_match_all('#(src="([^"]*)")#mis', $message, $matches)) {
					foreach ($matches[2] as $key => $value) {
						$filename = md5($value) . strrchr($value, '.');
						$path = rtrim($this->request->server['DOCUMENT_ROOT'], '/') . parse_url($value, PHP_URL_PATH);
						
						$attachments[] = array(
							'filename' => $filename,
							'path'     => $path
						);
						
						$message = str_replace($value, 'cid:' . basename($filename), $message);
					}
				}	
				
				foreach ($emails as $email) {
					$mail = new Mail();	
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->hostname = $this->config->get('config_smtp_host');
					$mail->username = $this->config->get('config_smtp_username');
					$mail->password = $this->config->get('config_smtp_password');
					$mail->port = $this->config->get('config_smtp_port');
					$mail->timeout = $this->config->get('config_smtp_timeout');				
					$mail->setTo($email);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($store_name);
					$mail->setSubject($this->request->post['subject']);					
					
					foreach ($attachments as $attachment) {
						$mail->addAttachment($attachment['path'], $attachment['filename']);
					}
					
					$mail->setHtml($message);
					$mail->send();
				}
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
            $sent = true;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_default'] = $this->language->get('text_default');
		
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_message'] = $this->language->get('entry_message');
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
		$this->data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['subject'])) {
			$this->data['error_subject'] = $this->error['subject'];
		} else {
			$this->data['error_subject'] = '';
		}
	 	
		if (isset($this->error['message'])) {
			$this->data['error_message'] = $this->error['message'];
		} else {
			$this->data['error_message'] = '';
		}	

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'sale/contact&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
				
		$this->data['action'] = HTTPS_SERVER . 'sale/contact&token=' . $this->session->data['token'];
    	$this->data['cancel'] = HTTPS_SERVER . 'sale/contact&token=' . $this->session->data['token'];

		if (isset($this->request->post['store_id'])) {
			$this->data['store_id'] = $this->request->post['store_id'];
		} else {
			$this->data['store_id'] = '';
		}

//		$this->load->model('setting/store');
//
//		$this->data['stores'] = $this->model_setting_store->getStores();
		
		$this->data['customers'] = array();
		
		if (isset($this->request->post['to']) && $this->request->post['to'] && !$sent) {
			foreach ($this->request->post['to'] as $customer_id) {
				$customer_info = $this->model_sale_customer->getCustomer($customer_id);
					
				if ($customer_info) {
					$this->data['customers'][] = array(
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname'] . ' (' . $customer_info['email'] . ')'
					);
				}
			}
		}

        $oProducts = Make::a('catalog/product')->create();
		
		$this->data['products'] = $oProducts->getProducts();
		
		if (isset($this->request->post['product']) && !$sent) {
			$this->data['product'] = $this->request->post['product'];
		} else {
			$this->data['product'] = '';
		}
		
		if (isset($this->request->post['group'])  && !$sent) {
			$this->data['group'] = $this->request->post['group'];
		} else {
			$this->data['group'] = '';
		}
		
		if (isset($this->request->post['subject'])  && !$sent) {
			$this->data['subject'] = $this->request->post['subject'];
		} else {
			$this->data['subject'] = '';
		}
		
		if (isset($this->request->post['message'])  && !$sent) {
			$this->data['message'] = $this->request->post['message'];
		} else {
			$this->data['message'] = '';
		}
		
        $oCat = Make::a('catalog/category')->create();
				
		$this->data['categories'] = $oCat->getCategories(0);

		$this->template = 'sale/contact.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	public function customers() {
		$this->load->model('sale/customer');
			
		$customer_data = array();
		
		if (isset($this->request->get['keyword']) && $this->request->get['keyword']) {
			$results = $this->model_sale_customer->getCustomersByKeyword($this->request->get['keyword']);
		
			foreach ($results as $result) {
				$customer_data[] = array(
					'customer_id' => $result['customer_id'],
					'name'        => $result['firstname'] . ' ' . $result['lastname'] . ' (' . $result['email'] . ')'
				);
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($customer_data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'sale/contact')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->request->post['subject']) {
			$this->error['subject'] = $this->language->get('error_subject');
		}

		if (!$this->request->post['message']) {
			$this->error['message'] = $this->language->get('error_message');
		}
						
		if (!$this->error) {
			return TRUE;
		} else {
            $this->error['warning'] = $this->language->get('error_warning');
			return FALSE;
		}
	}
	
	public function category() {
//		$this->load->model('catalog/product');

        $oProducts = Make::a('catalog/product')->create();
		
		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}
		
		$product_data = array();
		
		$results = $oProducts->getProductsByCategoryId($category_id);
		
		foreach ($results as $result) {
			$product_data[] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model']
			);
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($product_data));
	}
}
?>