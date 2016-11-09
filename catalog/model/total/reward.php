<?php

class ModelTotalReward extends Model {

    public function getTotal(&$total_data, &$total, &$taxes) {
	if (isset($this->session->data['points'])) {
	    $this->language->load('total/reward');

	    $points = $this->customer->getRewardPoints();
	    $max_points = $this->session->data['max-points'];

	    //d($this->session->data['points']); // 400
	    //d($points); // 400
	    //d($this->session->data['points']); // 100 : input


	    if ($this->session->data['points'] <= $points && $this->session->data['points']) {
		$discount_total = 0;
		$points_total = 0;

		//d($earn_point);
		foreach ($this->cart->getProducts() as $product) {
		    if ($product['points']) {
			$points_total += ($max_points * $product['quantity']);
		    }
		}


		//$points = min($points, $points_total);
		//d($points_total);
		//d($this->cart->getProducts());
		foreach ($this->cart->getProducts() as $product) {
		    $discount = 0;

		    if ($product['points']) {
			$discount = $product['total'] * ($this->session->data['points'] / $points_total);
			//d($discount);
			if ($product['tax_class_id']) {
			    $tax_rates = $this->tax->getRate($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

			    foreach ($tax_rates as $tax_rate) {
				if ($tax_rate['type'] == 'P') {
				    $taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
				}
			    }
			}
		    }

		    $discount_total += $discount;
		}





		$total_data[] = array(
		    'key' => 'reward',
		    'code' => 'reward',
		    'title' => sprintf($this->language->get('text_reward'), $this->session->data['reward']),
		    'text' => $this->currency->format(-$discount_total),
		    'value' => -$discount_total,
		    'sort_order' => $this->config->get('reward_sort_order')
		);
		//unset($this->session->data['max-points']);
		$total -= $discount_total;
	    }
	}
    }

    public function confirm($order_info, $order_total) {
	$this->language->load('total/reward');

	$points = 0;

	//$total_earn_points = $this->session->data['total_earn_points'];
	//$points += $total_earn_points;
	//$points = ($points - $this->session->data['input_points']);
	$points = $this->session->data['input_points'];

	if ($points) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int) $order_info['customer_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int) $order_info['order_id'])) . "', points = '" . (float) -$points . "', date_added = NOW()");
	}
    }

}

?>