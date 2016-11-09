<?php
class ControllerPaymentTestconfirm extends Controller{
	public function index() {
    	if (isset($this->request->get['auth_token']) && isset($this->request->get['postBackURL'])) {
			if (!$this->config->get('easypaisa_test')){
				$cAction = 'https://easypaystg.easypaisa.com.pk/easypay/Confirm.jsf';
			}else{	
				$cAction = 'https:/easypay.easypaisa.com.pk/easypay/Confirm.jsf';
			}
    		$auth_token  = $this->request->get['auth_token'];
    		$postBackURL = makeUrl('checkout/success', array('order_id=' . $this->session->data['order_id']), true);
			// d($postBackURL, true);
			// $postBackURL = HTTPS_SERVER . 'payment/testconfirm/callback?confirm=true';
			echo '
			<form action="'.$cAction.'" method="POST" id="authForm" style="display:none">
				<input name="auth_token" type="hidden"  value="'.$auth_token.'"/>
				<input name="orderRefNumber" type="hidden" value="37"/>
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
	public function callback(){
		d($this->request,true);
			exit();

	}
}
?>