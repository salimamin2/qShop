<?php

class ControllerPaymentCod extends Controller {

    protected function index() {
	$this->data['button_confirm'] = $this->language->get('button_confirm');
	$this->data['button_back'] = $this->language->get('button_back');

	if ($this->request->get['act'] != 'checkout/guest_step_3') {
	    $this->data['back'] = makeUrl('checkout/shipping', array(), true);
	} else {
	    $this->data['back'] = makeUrl('checkout/guest_step_2', array(), true);
	}

	$this->id = 'payment';

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/payment/cod.tpl';
	} else {
	    $this->template = 'default/template/payment/cod.tpl';
	}

	$this->render();
    }

    public function confirm() {
        $this->load->model('checkout/order');
        $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
	    $continue = makeUrl('checkout/success', array('order_id='.$this->session->data['order_id'],'customer_id=' . $this->session->data['hdn_customer_id']), true);
	    echo json_encode(array('continue' => $continue));
    }

}

?>