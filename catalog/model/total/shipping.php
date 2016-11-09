<?php

class ModelTotalShipping extends Model {

    public function getTotal(&$total_data, &$total, &$taxes) {
	if ($this->cart->hasShipping() && isset($this->session->data['shipping_method']) && $this->config->get('shipping_status')) {
	    $var = end($this->session->data['shipping_method']['quote']);
        $total_data[] = array(
		'key' => 'shipping',
		'title' => $this->session->data['shipping_method']['title'] . ':',
		'text' => $this->currency->format($var['cost']),
		'value' => $var['cost'],
		'sort_order' => $this->config->get('shipping_sort_order')
	    );

	    if ($var['tax_class_id']) {
		if (!isset($taxes[$var['tax_class_id']])) {
		    $taxes[$var['tax_class_id']] = $var['cost'] / 100 * $this->tax->getRate($var['tax_class_id']);
		} else {
		    $taxes[$var['tax_class_id']] += $var['cost'] / 100 * $this->tax->getRate($var['tax_class_id']);
		}
	    }

	    $total += $var['cost'];
//            d($this->session->data['shipping_method']['cost']);
	}
    }

}

?>