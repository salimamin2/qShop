<?php

class ControllerPaymentHBL extends Controller {

    protected function index() 
    {
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		$this->load->language('payment/hbl');
		//$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		//	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		//	$this->data['entry_ccNumber'] = $this->language->get('entry_ccNumber');
		//	$this->data['entry_cvvValue'] = $this->language->get('entry_cvvValue');
		//	$this->data['entry_expire'] = $this->language->get('entry_expire');

		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/hbl.tpl')) {
		    $this->template = $this->config->get('config_template') . '/template/payment/hbl.tpl';
		} else {
		    $this->template = 'default/template/payment/hbl.tpl';
		}

		$this->render();
    }


    public function callback(){
    	$aResponse = $_REQUEST;
		$this->log->write_payment(array('response'=>$aResponse),'hbl_payment');
		$this->load->model('tool/seo_url');
		$this->load->model('checkout/order');
		if($aResponse['decision'] == 'ACCEPT'){
			$this->model_checkout_order->confirm($aResponse['req_reference_number'], $this->config->get('hbl_order_status_id'));
		} else {
			$this->model_checkout_order->update($aResponse['req_reference_number'], /*$this->config->get('config_failed_status_id')*/10,$aResponse['message']);	
		}
	    $continue = makeUrl('checkout/success', array('order_id='.$this->session->data['order_id'],'customer_id=' . $this->session->data['hdn_customer_id'],'msg=' . $aResponse['message'],'decision=' . $aResponse['decision']), true);
	    //echo json_encode(array('continue' => $continue));
	    $this->redirect($continue);
    }

    /*public function confirm() {
    	$aResponse = $_REQUEST;
		$this->log->write_payment(array('response'=>$aResponse),'hbl_payment');
        $this->load->model('checkout/order');
        $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('hbl_order_status_id'));
	    $continue = makeUrl('checkout/success', array('order_id='.$this->session->data['order_id'],'customer_id=' . $this->session->data['hdn_customer_id']), true);
	    echo json_encode(array('continue' => $continue));
    }*/

   public function confirm() 
   {	
   		
   		$this->load->model('checkout/order');
		$this->load->model('payment/hbl');
		//this->load->model('catalog/category');
		$this->load->model('account/customer');
		$this->load->language('payment/hbl');
				
		$error = array();

		if (!isset($this->session->data['order_id'])) {
		    $this->session->data['redirect'] = makeUrl('checkout/cart');
		    $error['warning'] = sprintf($this->language->get('error_session'), makeUrl('account/login'));
		}

		//define ('HMAC_SHA256', 'sha256');		
		if (!$error) {
		    $hbl_data = array();

		    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			//d($order_info);
		    //$paid_total = $this->currency->format($order_info['total'], 'MXP', $this->currency->getValue('MXP'), FALSE);
		    $hbl_data['access_key'] = $this->config->get('hbl_username');
		    $hbl_data['profile_id'] = $this->config->get('hbl_password');
		    $hbl_data['transaction_uuid'] = (rand() % 100000000);
		    $hbl_data['bill_to_address_country'] = $order_info['payment_iso_code_2'];
			$hbl_data['signed_field_names'] = ('access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,bill_to_address_country,bill_to_address_line1,bill_to_address_state,bill_to_address_city,bill_to_email,bill_to_forename,bill_to_phone,bill_to_surname,ship_to_address_line1,ship_to_address_city,ship_to_forename,ship_to_surname,customer_ip_address,consumer_id,device_fingerprint_id,merchant_defined_data1,merchant_defined_data2,merchant_defined_data3,merchant_defined_data4,merchant_defined_data5,merchant_defined_data6,merchant_defined_data7,merchant_defined_data8,merchant_defined_data20');
			$hbl_data['unsigned_field_names'] = ('');
			$hbl_data['signed_date_time'] = (gmdate("Y-m-d\TH:i:s\Z"));
		    $hbl_data['locale'] = $this->config->get('config_language');
		    $hbl_data['transaction_type'] = $this->config->get('hbl_transtype');
			$hbl_data['reference_number'] = $order_info['order_id'];
			$hbl_data['amount'] = intval($order_info['total']);
		    $hbl_data['currency'] = $this->config->get('config_currency');
		    $hbl_data['bill_to_address_line1'] = substr($order_info['payment_address_1'], 0, 63);
		    $hbl_data['bill_to_address_state'] = substr($order_info['shipping_zone_code'], 0, 60);
		    $hbl_data['bill_to_address_city'] = substr($order_info['payment_city'], 0, 25);
		    $hbl_data['bill_to_email'] = $order_info['email'];	
		    $hbl_data['bill_to_forename'] = $order_info['payment_firstname'];
		    $hbl_data['bill_to_phone'] = $order_info['payment_company'];
		    $hbl_data['bill_to_surname'] = $order_info['payment_lastname'];
		    $hbl_data['ship_to_address_line1'] = substr($order_info['shipping_address_1'], 0, 63);
		    $hbl_data['ship_to_address_city'] = substr($order_info['shipping_city'], 0, 25);
		    $hbl_data['ship_to_forename'] = $order_info['shipping_firstname'];
		    $hbl_data['ship_to_surname'] = $order_info['shipping_lastname'];
		    $hbl_data['customer_ip_address'] = $order_info['ip'];
		    $hbl_data['consumer_id'] = $order_info['customer_id'];	
			$hbl_data['device_fingerprint_id'] = $order_info['order_id'];
			$product_count =0;
			$product_name = "";
			foreach ($this->cart->getProducts() as $product)
			{	
				$product_name=$product_name.','.$product['name'];
				$aCat[$product['product_id']] = Make::a('catalog/category')->create()->getCategoryByProductId($product['product_id']);
				$product_count++;
			}
			/*$sCat = "";
			foreach($aCat as $product_id => $cat){
				//d($cat);
				$parentCat = Make::a('catalog/category')->create()->getCategory($cat['parent_id']);
				//d($parentCat);
				if($parentCat){
					$sCat = $sCat.$parentCat['name'].",";	
				}else{
					$sCat = $sCat.$cat['name'].",";	
				}
			}*/
			$order_count = $this->model_account_customer->getCustomerOrderCount($order_info['customer_id']);

			//d($order_count);
			
			$hbl_data['merchant_defined_data1'] = ('WC');
		    $hbl_data['merchant_defined_data2'] = ('YES');
		    $hbl_data['merchant_defined_data3'] = ('Clothing');
		    $hbl_data['merchant_defined_data4'] = ($product_name);
		    $hbl_data['merchant_defined_data5'] = ($order_count > 0?'YES':'NO');
		    $hbl_data['merchant_defined_data6'] = ('Standard');
		    $hbl_data['merchant_defined_data7'] = ($product_count);
		    $hbl_data['merchant_defined_data8'] = $order_info['shipping_country'];
		    $hbl_data['merchant_defined_data20'] = ('NO');
			$hbl_data['signature'] = $this->sign($hbl_data);


			if (!$this->config->get('hbl_test_status')) {
				$this->data['action'] = 'https://secureacceptance.cybersource.com/pay';
			} else {
				$this->data['action'] = 'https://testsecureacceptance.cybersource.com/pay';
			}	

			$this->data['sid'] = 'hbl';

			if ($this->config->get('hbl_test_status')) {
				$this->data['demo'] = 'Y';
			}

			$this->data['lang'] = $this->session->data['language'];

			$this->data['order_id']=$this->session->data['order_id'];

		    //d($hbl_data);
		    // $id = 
			$this->log->write_payment($hbl_data, 'hbl_payment');
		    //$hbl_data['id'] = $id;
		    //$this->session->data['hbl'] = 'hbl';
		    $this->load->model('checkout/order');

		    $comment = "Sending payment detail to HBL  ";
		    $this->model_checkout_order->update($this->session->data['order_id'], 0, $comment, false);

		    $this->data['response'] = $hbl_data;
		    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/hbl.tpl')) {
	    		$this->template = $this->config->get('config_template') . '/template/payment/hbl.tpl';
			} else {
	    		$this->template = 'default/template/payment/hbl.tpl';
			}

			$html = $this->render(true);
		    $this->response->setOutput(json_encode(array('html'=>$html)));
		} else {
		    $error[0] = 'error';
		    $this->response->setOutput(json_encode($error));
		}
    }
    function sign ($params) {
 	 return $this->signData($this->buildDataToSign($params), $this->config->get('hbl_clientid'));
	}

	function signData($data, $secretKey) {
	    return base64_encode(hash_hmac('sha256',  $data, $secretKey, true));
	}

	function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as &$field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return $this->commaSeparate($dataToSign);
    }
    function commaSeparate ($dataToSign) {
    	return implode(",",$dataToSign);
	}
}

?>