<?php
class ControllerPaymentEasypaisa extends Controller{
	protected function index(){
		if (!$this->config->get('easypaisa_test')){
			$this->data['action'] = 'https://easypaystg.easypaisa.com.pk/easypay/index.jsf';
		}else{
			$this->data['action'] = 'https:/easypay.easypaisa.com.pk/easypay/Index.jsf';
		}
		if (!$this->config->get('easypaisa_transaction')){
			$this->data['paymentaction'] = 'authorization';
		}else{
			$this->data['paymentaction'] = 'sale';
		}
		$this->data['return'] = HTTPS_SERVER . 'checkout/success';
		if ($this->request->get['act'] != 'checkout/guest_step_3'){
			$this->data['cancel_return'] = HTTPS_SERVER . 'checkout/confirm';
		}else{
			$this->data['cancel_return'] = HTTPS_SERVER . 'checkout/guest_step_2';
		}

		$this->id = 'payment';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/easypaisa.tpl')){
			$this->template = $this->config->get('config_template') . '/template/payment/easypaisa.tpl';
		}else{
			$this->template = 'default/template/payment/easypaisa.tpl';
		}

		$this->render();
	}

	public function confirm(){
		$this->load->model('checkout/order');
		if (!$this->config->get('easypaisa_test')){
			$this->data['action'] = 'https://easypaystg.easypaisa.com.pk/easypay/Index.jsf';
		}else{
			$this->data['action'] = 'https:/easypay.easypaisa.com.pk/easypay/Index.jsf';
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->data['business'] = $this->config->get('easypaisa_email');
		$this->data['item_name'] = html_entity_decode($this->config->get('config_name') , ENT_QUOTES, 'UTF-8');
		$this->data['currency_code'] = 'USD';
		$this->data['amount'] = number_format($order_info['total'], 0, '', '');
		$this->data['orderRefNum'] = $this->session->data['order_id'];
		$this->data['postBackURL'] = HTTPS_SERVER . 'payment/easypaisa/sendAuthToken';
		if ($this->request->get['act'] != 'checkout/guest_step_3'){
			$this->data['cancel_return'] = makeUrl('checkout/confirm', array() , true);
		}else{
			$this->data['cancel_return'] = makeUrl('checkout/guest_step_2', array() , true);
		}
		$this->data['cancel_return'] = makeUrl('checkout/guest_step_2', array() , true);

		$this->load->library('encryption');
		$encryption = new Encryption($this->config->get('config_encryption'));
		$this->model_checkout_order->confirm($this->data['orderRefNum'], $this->config->get('easypaisa_order_status_id'));
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/easypaisa.tpl')){
				$this->template = $this->config->get('config_template') . '/template/payment/easypaisa.tpl';
			}else{
				$this->template = 'default/template/payment/easypaisa.tpl';
			}

		$html = $this->render(true);
		echo json_encode(array('html' => $html));
		exit();
	}
	public function sendAuthToken(){
		if (isset($this->request->get['auth_token']) && isset($this->request->get['postBackURL'])) {
			if (!$this->config->get('easypaisa_test')){
				$cAction = 'https://easypaystg.easypaisa.com.pk/easypay/Confirm.jsf';
			}else{	
				$cAction = 'https:/easypay.easypaisa.com.pk/easypay/Confirm.jsf';
			}
			$orderRefNum = $this->session->data['order_id'];
    		$auth_token  = $this->request->get['auth_token'];
    		$this->session->data['auth_token'] = $auth_token;
			$postBackURL = HTTPS_SERVER . 'payment/easypaisa/response';
			echo '
			<form action="'.$cAction.'" method="POST" id="authForm" style="display:none">
				<input name="auth_token" type="hidden"  value="'.$auth_token.'"/>
				<input name="orderRefNumber" type="hidden" value="'.$orderRefNum.'"/>
				<input name="postBackURL" type="hidden" value="'.$postBackURL.'" />
				<input value="confirm" type="submit" name= "pay"/>
			</form>
			<script>
				document.getElementById("authForm").submit()
			</script>
			';
			exit();
    	}
	}
	public function response(){
		$order_id = $this->session->data['order_id'];
		$this->load->model('checkout/order');
		if (isset($this->request->get['paymentToken'])) {
			$this->model_checkout_order->confirm($order_id, $this->config->get('easypaisa_order_status_id'));
			$this->redirect(makeUrl('checkout/success', array('order_id=' . $this->session->data['order_id']),true));
		}else{
			$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
			$eError = "please try again";
			$this->redirect(makeUrl('checkout/confirm', array('eError='.$eError),true));
		}
	}
}
?>
