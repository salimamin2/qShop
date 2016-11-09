<?php
class ControllerPaymentIntl extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->data['continue'] = HTTPS_SERVER . 'checkout/success';
//		$this->data['continue'] = HTTPS_SERVER . 'payment/cod/confirm';

		if ($this->request->get['act'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'checkout/shipping';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/intl.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/intl.tpl';
		} else {
			$this->template = 'default/template/payment/intl.tpl';
		}	
		
		$this->render();
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('intl_order_status_id'));
	}
}
?>