<?php
class ControllerPaymentBankTransfer extends Controller {
	protected function index() {
		$this->language->load('payment/bank_transfer');
		
		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['text_payment'] = $this->language->get('text_payment');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['bank'] = nl2br($this->config->get('bank_transfer_bank_' . $this->config->get('config_language_id')));

		$this->data['continue'] = HTTPS_SERVER . 'checkout/success';

		if ($this->request->get['act'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bank_transfer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/bank_transfer.tpl';
		} else {
			$this->template = 'default/template/payment/bank_transfer.tpl';
		}	
		
		$this->render(); 
	}
	
	public function confirm() {
		$this->language->load('payment/bank_transfer');
		$this->load->model('checkout/order');
		$comment  = $this->language->get('text_instruction') . "\n\n";
		$comment .= $this->config->get('bank_transfer_bank_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->language->get('text_payment');
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment);
        $continue = makeUrl('checkout/success', array('order_id='.$this->session->data['order_id'],'customer_id=' . $this->session->data['hdn_customer_id']), true);
        echo json_encode(array('continue' => $continue));
	}
}
?>