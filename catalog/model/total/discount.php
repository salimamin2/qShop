<?php

class ModelTotalDiscount extends Model {

    public function getTotal(&$total_data, &$total, &$taxes) {



        if ($this->config->get('discount_status')) {
            $this->load->model('checkout/discount');

            $discounts = $this->model_checkout_discount->getDiscount($this->cart->getProducts());


            
            if ($discounts) {
                foreach ($discounts as $discount) {
                    $discount_total = 0;
                    if (isset($discount['apply_discount']) && $discount['apply_discount']) {
                        $discount_stotal = $total;

                        $discount_rate = 0;

                        if ($discount['type'] == 'F') {
                            $discount['discount'] = min($discount['discount'], $discount_stotal);
                            $discount_rate = $discount['discount'];
                        } elseif ($discount['type'] == 'P') {
                            $discount_rate = $discount['total'] / 100 * $discount['discount'];
                        }

                        if ($discount['tax_class_id']) {
                            $taxes[$product['tax_class_id']] -= ($discount['total'] / 100 * $this->tax->getRate($discount['tax_class_id'])) - (($discount['total'] - $discount) / 100 * $this->tax->getRate($discount['tax_class_id']));
                        }

                        $discount_total += $discount_rate;
                    }

                    //d($this->session->data['shipping_method']);

                    if ($discount['shipping'] && isset($this->session->data['shipping_method'])) {
                        if (isset($this->session->data['shipping_method']['tax_class_id']) && $this->session->data['shipping_method']['tax_class_id']) {
                            $taxes[$this->session->data['shipping_method']['tax_class_id']] -= $this->session->data['shipping_method']['cost'] / 100 * $this->tax->getRate($this->session->data['shipping_method']['tax_class_id']);
                        }
                        
                        $discount_total += $this->session->data['shipping_method']['cost'];
                    }
                    if ($discount_total) {
                        $total_data[] = array(
			    'key' => 'discount',
                            'title' => $discount['name'] . ':',
                            'text' => '-' . $this->currency->format($discount_total),
                            'value' => - $discount_total,
                            'sort_order' => $this->config->get('discount_sort_order'),
                            'code' => 'discount'
                        );
                        //d($discount_total);
                        $total -= $discount_total;
                    }
                }
            }
        }
    }

}

?>