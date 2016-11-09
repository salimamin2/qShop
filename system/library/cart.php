<?php

final class Cart {

    public function __construct() {
		$registry = Registry::getInstance();
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->cache = $registry->get('cache');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');
		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
		    $this->session->data['cart'] = array();
		}
    }

    public function getProducts() {
		$product_data = array();
		if (isset($this->session->data['cart']) && $this->session->data['cart']) {
		    foreach ($this->session->data['cart'] as $key => $value) {
				if (!isset($this->session->data['cart_products'][$key])) {
					$product_id = $key;
					$options = array();
					$group_id = false;
				    if (strpos($key, ':')) {
						$array = explode(':', $key);
						$product_id = $array[0];
						$group_id = false;
						if (isset($array[1])) {
							if(strpos($array[1],'|')){
								$aGroups = explode('|',$array[1]);
								$group_id = $aGroups[0];
								$options = explode('.',$aGroups[1]);
							} else {
						    	$options = explode('.', $array[1]);
							}
						} 
				    }
				    $quantity = $value;
				    $stock = TRUE;

				    $sql = "SELECT *, wcd.unit AS weight_class, mcd.unit AS length_class";
				    $sql .= " FROM product p";
				    $sql .= " LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "')";
				    $sql .= " LEFT JOIN weight_class wc ON (p.weight_class_id = wc.weight_class_id)";
				    $sql .= " LEFT JOIN weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int) $this->config->get('config_language_id') . "')";
				    $sql .= " LEFT JOIN length_class mc ON (p.length_class_id = mc.length_class_id)";
				    $sql .= " LEFT JOIN length_class_description mcd ON (mc.length_class_id = mcd.length_class_id)";
				    $sql .= " WHERE p.product_id = '" . (int) $product_id . "' AND p.date_available <= NOW() AND p.status = '1'";

				    $product_query = $this->db->query($sql);
				    //d($product_query->row);

				    if ($product_query->num_rows) {
					$option_price = 0;
					$option_data = array();
					$group_data = array();
					$group_name = '';
					if($group_id){
						$sql_group = "SELECT pov.product_option_id, povd.name, pov.price, pov.quantity, pov.subtract, pov.prefix, pod.name group_name FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "product_option_value_description povd ON (pov.product_option_value_id = povd.product_option_value_id) LEFT JOIN " . DB_PREFIX . "product_option_description pod ON (pov.product_option_id = pod.product_option_id) WHERE pov.product_option_id = '" . (int) $group_id . "' AND pov.product_id = '" . (int) $product_id . "' AND povd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND pod.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY pov.sort_order";
						$group_query = $this->db->query($sql_group);
						if ($group_query->row['prefix'] == '+') {
								$option_price = $option_price + $group_query->row['price'];
						}
						elseif ($group_query->row['prefix'] == '-') {
							$option_price = $option_price - $group_query->row['price'];
						}
						$group_name = "<span class='group_name'>".$group_query->row['group_name']."</span><br />";
						$group_data[] = array(
							'product_option_value_id' => $group_id, 
							'name' => $group_name, 
							'value' => $group_query->row['group_name'],//$option_value_query->row['name'], 
							'prefix' => $group_query->row['prefix'], 
							'price' => $group_query->row['price']
						);
					}
					if ($options) {
					    foreach ($options as $product_option_value_id) {
							$sql_option = "SELECT pov.product_option_id, povd.name, pov.price, pov.quantity, pov.subtract, pov.prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "product_option_value_description povd ON (pov.product_option_value_id = povd.product_option_value_id) WHERE pov.product_option_value_id = '" . (int) $product_option_value_id . "' AND pov.product_id = '" . (int) $product_id . "' AND povd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY pov.sort_order";
							$option_value_query = $this->db->query($sql_option);

							if ($option_value_query->num_rows) {

								$option_query = $this->db->query("SELECT pod.name FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "product_option_description pod ON (po.product_option_id = pod.product_option_id) WHERE po.product_option_id = '" . (int) $option_value_query->row['product_option_id'] . "' AND (po.product_id = '" . (int) $product_id . "' OR po.product_id = '-1') AND pod.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY po.sort_order");
								
								$sResult = $this->getOptionValue($key, $product_option_value_id);

								if (!$sResult) {
									if ($option_value_query->row['prefix'] == '+') {
											$option_price = $option_price + $option_value_query->row['price'];
									}
									elseif ($option_value_query->row['prefix'] == '-') {
										$option_price = $option_price - $option_value_query->row['price'];
									}
									$option_prefix = $option_value_query->row['prefix'];
	                                $option_value = $option_value_query->row['name'];
	                                $option_price_val = $option_value_query->row['price'];
	                                $option_name = $group_name.$option_query->row['name'];
								} else {
									$option_name = $group_name."<b>".$option_query->row['name'] . ":</b>&nbsp;" . $option_value_query->row['name'];
									//$option_name = $option_value_query->row['name'];
									$option_value = $sResult;
	                                $option_prefix = '';
	                                $option_price_val = '';
								}
								//d($option_data);
								
								$option_data[] = array(
									'product_option_value_id' => $product_option_value_id, 
									'name' => $option_name, 
									'value' => $option_value,//$option_value_query->row['name'], 
									'prefix' => $option_value_query->row['prefix'], 
									'price' => $option_value_query->row['price']
								);

								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {

									$stock = FALSE;

								}
							
							}
					    }
					}

					if ($this->customer->isLogged()) {
					    $customer_group_id = $this->customer->getCustomerGroupId();
					} else {
					    $customer_group_id = $this->config->get('config_customer_group_id');
					}


					$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "' AND customer_group_id = '" . (int) $customer_group_id . "' AND quantity <= '" . (int) $quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
					if ($product_discount_query->num_rows) {
					    $price = $product_discount_query->row['price'];
					} else {
					    $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "' AND customer_group_id = '" . (int) $customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
					    if ($product_special_query->num_rows) {
						$price = $product_special_query->row['price'];
					    } else {
						$price = $product_query->row['price'];
					    }
					}


					//$reward_data = array();
					$reward_query = $this->db->query("SELECT points AS rewardpoints FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = '" . (int) $product_id . "' AND pr.customer_group_id = '" . $customer_group_id . "'");
					if ($reward_query->num_rows) {
					    foreach ($reward_query->rows as $reward) {
						$reward_points = $reward['rewardpoints'];
					    }
					}

					$download_data = array();
					$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int) $product_id . "' AND dd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
					foreach ($download_query->rows as $download) {
					    $download_data[] = array('download_id' => $download['download_id'], 'name' => $download['name'], 'filename' => $download['filename'], 'mask' => $download['mask'], 'remaining' => $download['remaining']);
					}
					if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
					    $stock = FALSE;
					}

					$product_data[$key] = array(
					    'key' => $key,
					    'max-points' => $product_query->row['points'],
					    'points' => $reward_points,
					    'product_id' => $product_query->row['product_id'],
					    'name' => $product_query->row['name'],
					    'model' => $product_query->row['model'],
					    'shipping' => $product_query->row['shipping'],
					    'image' => $product_query->row['image'],
					    'option' => $option_data,
					    'group_option' => $group_data,
					    'download' => $download_data,
					    'quantity' => $quantity,
					    'stock_status_id' => $product_query->row['stock_status_id'],
					    'stock' => $stock,
					    'price' => ($price + $option_price),
					    'total' => ($price + $option_price) * $quantity,
					    'tax_class_id' => $product_query->row['tax_class_id'],
					    'weight' => $product_query->row['weight'],
					    'weight_class' => $product_query->row['weight_class'],
					    'length' => $product_query->row['length'],
					    'width' => $product_query->row['width'],
					    'height' => $product_query->row['height'],
					    'length_class' => $product_query->row['length_class'],
					    'product_type_id' => $product_query->row['product_type_id'],
					    'stock_quantity' => $product_query->row['quantity']
					);
			    } else {
					$this->remove($key);
			    }
			    	//d(array($product_data,$key),1);
				    $this->session->data['cart_products'][$key] = $product_data[$key];
				} else {
				    $product_data[$key] = $this->session->data['cart_products'][$key];
				}
		    }
		}
		return $product_data;
    }

    public function add($product_id, $qty = 1, $options = array()) {
	if (!$options) {
	    $key = $product_id;
	} else {
	    $key = $product_id;
	   $iOption = '';
            $j = 0;
            foreach ($options as $id => $val) {
                if (is_array($val)) {
                    //d(array ($id, $val));
                    $iOption .= $id .'|';
                    foreach ($val as $k => $v) {
                        if ($v) {
                        	if(strpos($v,'sel_') !== false){
                        		$iOption .= str_replace('sel_', '', $v).'.';
                        	} else {
                        	//d(array ($v, $k));
	                            $this->session->data['option_value'][$key][$k] = addslashes($v);
	                            $iOption .=$k.'.';
	                            $j++;
                        	}
                        }
                    }
                } else {
                   // d(array ($id, $val));
                    $iOption .= $val.'.';
                }
                $j++;
            }
            $iOption = substr($iOption, 0, strlen($iOption)-1);
            //d($iOption,1);
            $key = $key . ':' . $iOption;
            //d($key, true);
	}
	if ((int) $qty && ((int) $qty > 0)) {

	    if (!isset($this->session->data['cart'][$key])) {
		$this->session->data['cart'][$key] = (int) $qty;
	    } else {
		$this->session->data['cart'][$key] += (int) $qty;
		unset($this->session->data['cart_products'][$key]);
	    }
	}
	//$this->cache->delete('cart');
    }

    public function update($key, $qty) {
	if ((int) $qty && ((int) $qty > 0)) {
	    $this->session->data['cart'][$key] = (int) $qty;
	    unset($this->session->data['cart_products'][$key]);
	} else {
	    $this->remove($key);
	}
	//$this->cache->delete('cart');
    }

    public function remove($key) {
	if (isset($this->session->data['cart'][$key])) {
	    unset($this->session->data['cart'][$key]);
	    unset($this->session->data['cart_products'][$key]);
	}
	//$this->cache->delete('cart');
    }

    public function clear() {
	$this->session->data['cart'] = array();
	$this->session->data['cart_products'] = array();
	//$this->cache->delete('cart');
    }

    public function getWeight() {
	$weight = 0;
	foreach ($this->getProducts() as $product) {
	    if ($product['shipping']) {
		$weight += $this->weight->convert($product['weight'] * $product['quantity'], $product['weight_class'], $this->config->get('config_weight_class'));
	    }
	}
	return $weight;
    }

    public function getSubTotal() {
	$total = 0;
	foreach ($this->getProducts() as $product) {
	    $total += $product['total'];
	}
	return $total;
    }

    public function getTaxes() {
	$taxes = array();
	//d($this->getProducts());
	foreach ($this->getProducts() as $product) {
	    if ($product['tax_class_id']) {
		if (!isset($taxes[$product['tax_class_id']])) {
		    $taxes[$product['tax_class_id']] = $product['total'] / 100 * $this->tax->getRate($product['tax_class_id']);
		} else {
		    $taxes[$product['tax_class_id']] += $product['total'] / 100 * $this->tax->getRate($product['tax_class_id']);
		}
	    }
	}
	return $taxes;
    }

    public function getTotal() {
	$total = 0;
	foreach ($this->getProducts() as $product) {
	    $total += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'));
	}
	return $total;
    }

    public function countProducts() {
	$total = 0;
	foreach ($this->session->data['cart'] as $value) {
	    $total += $value;
	}
	return $total;
    }

    public function hasProducts() {
	return count($this->session->data['cart']);
    }

    public function hasStock() {
	$stock = TRUE;
	foreach ($this->getProducts() as $product) {
	    if (!$product['stock']) {
		$stock = FALSE;
	    }
	}
	return $stock;
    }

    public function hasShipping() {
	$shipping = FALSE;
	foreach ($this->getProducts() as $product) {
	    if ($product['shipping']) {
		$shipping = TRUE;
		break;
	    }
	}
	return $shipping;
    }

    public function hasDownload() {
	$download = FALSE;
	foreach ($this->getProducts() as $product) {
	    if ($product['download']) {
		$download = TRUE;
		break;
	    }
	}
	return $download;
    }

    /* Quote Section */

    public function quoteSave($data) {
	$this->db->query("INSERT INTO `" . DB_PREFIX . "quote` SET store_id = '" . (int) $data['store_id'] . "', customer_id = '" . (int) $data['customer_id'] . "', total = '" . (float) $data['total'] . "',  date_added = NOW()");
	$quote_id = $this->db->getLastId();
	if ($quote_id) {
	    foreach ($data['product'] as $product) {
		$sql = "INSERT INTO `" . DB_PREFIX . "quote_detail` SET quote_id = '" . (int) $quote_id . "', product_id = '" . (int) $product['product_id'] . "', product_options = '" . $product['options'] . "', quantity = '" . (int) $product['quantity'] . "', price = '" . (float) $product['price'] . "'";
		$this->db->query($sql);
	    }
	}
	return $quote_id;
    }

    public function quoteUpdate($quote_id, $data) {
	$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET total = '" . (float) $data['total'] . "' WHERE id = '" . (int) $quote_id . "'");
	foreach ($data['product'] as $product) {
	    $this->db->query("UPDATE `" . DB_PREFIX . "quote` SET product_id = '" . (int) $product['product_id'] . "', product_options = '" . $product['options'] . "', quantity = '" . (int) $product['quantity'] . "', price = '" . (float) $product['price'] . "' WHERE id = '" . (int) $quote_id . "'");
	}
    }

    public function getTotalQuotes($customer_id) {
	$query = $this->db->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "quote` WHERE customer_id = '" . (int) $customer_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'", true);
	return $query->row['total'];
    }

    public function deleteQuote($quote_id) {
	$this->db->query("DELETE FROM `" . DB_PREFIX . "quote` WHERE  quote_id = '" . (int) $quote_id . "'");
	$this->db->query("DELETE FROM `" . DB_PREFIX . "quote_detail` WHERE  quote_id = '" . (int) $quote_id . "'");
    }

    public function getQuotes($customer_id, $start = 0, $limit = 20) {
	if ($start < 0) {
	    $start = 0;
	}
	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote` WHERE customer_id = " . $customer_id . " AND store_id = '" . $this->config->get('config_store_id') . "' ORDER BY quote_id DESC LIMIT " . (int) $start . "," . (int) $limit, true);
	return $query->rows;
    }

    public function getTotalQuoteProducts($quote_id) {
	$qProduct = $this->db->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "quote_detail` WHERE quote_id = '" . (int) $quote_id . "'", true);
	return $qProduct->row['total'];
    }

    public function getTotalQuoteQuantity($quote_id) {
	$query = $this->db->query("SELECT sum(quantity) AS qty FROM `" . DB_PREFIX . "quote_detail` WHERE quote_id = '" . (int) $quote_id . "'", true);
	return $query->row['qty'];
    }

    public function getQuoteProducts($quote_id) {
	$this->db->disableCache();
	$sql = "SELECT qd.*,p.model,p.image,p.tax_class_id,pd.name AS product_name FROM `" . DB_PREFIX . "quote_detail` AS qd LEFT JOIN `" . DB_PREFIX . "product` AS p ON p.product_id = qd.product_id LEFT JOIN `" . DB_PREFIX . "product_description` AS pd ON pd.product_id = p.product_id WHERE qd.quote_id = '" . (int) $quote_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
	$qProduct = $this->db->query($sql, true);
	$results = $qProduct->rows;
	$j = 0;
	foreach ($results as $product) {
	    if ($product['product_options']) {
		list($options, $details) = explode('|', $product['product_options']);
		$option_data = explode('.', $options);
		foreach ($option_data as $options) {
		    $option = explode('-', $options);
		    $qOption = $this->db->query("SELECT o.product_option_id, o.name AS option_name,ov.product_option_value_id,ov.name AS option_value_name FROM `" . DB_PREFIX . "product_option_description` AS o, `" . DB_PREFIX . "product_option_value_description` AS ov WHERE ov.product_option_value_id=" . $option[0] . " AND o.product_option_id=" . $option[1] . " AND ov.language_id = '" . (int) $this->config->get('config_language_id') . "'");
		    if ($qOption->num_rows) {
			$results[$j]['options'] = $qOption->rows;
		    }
		}
		$detail_data = explode('.', $details);
		$details_data = array();
		foreach ($detail_data as $options) {
		    $registry = Registry :: getInstance();
		    $registry->get('load')->model('catalog/product');
		    $product = $registry->get('model_catalog_product');
		    $detail = $product->getDetail($product_detail_id);
		    $details_data[] = array('product_detail_id' => $detail['product_detail_id'], 'code' => $detail['code'], 'price' => $detail['price'], 'quantity' => $detail['quantity'], 'options' => $detail['options'],);
		}
		$results[$j]['details'] = $details_data;
		$j++;
	    }
	}
	$this->db->enableCache();
	return $results;
    }

    public function getQty() {
	$qty = 0;
	foreach ($this->getProducts() as $product) {
	    if ($product['shipping']) {
			$qty += $product['quantity'];
	    }
	}
	return $qty;
    }
    public function getOptionValue($key, $iOption) {
        $aKey = explode(':', $key);
        //d(array($aKey[0],$this->session->data['option_value'],$iOption));
        if (!empty($this->session->data['option_value']) && isset($this->session->data['option_value'][$aKey[0]]) && array_key_exists($iOption, $this->session->data['option_value'][$aKey[0]])) {
            return $this->session->data['option_value'][$aKey[0]][$iOption];
        } else {
            return false;
        }
    }

}

?>