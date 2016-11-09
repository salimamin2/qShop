<?php

class ModelCheckoutOrder extends Model {

    public function getOrder($order_id) {
	$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");

	if ($order_query->num_rows) {
	    $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['shipping_country_id'] . "'");

	    if ($country_query->num_rows) {
		$shipping_iso_code_2 = $country_query->row['iso_code_2'];
		$shipping_iso_code_3 = $country_query->row['iso_code_3'];
	    } else {
		$shipping_iso_code_2 = '';
		$shipping_iso_code_3 = '';
	    }

	    $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

	    if ($zone_query->num_rows) {
		$shipping_zone_code = $zone_query->row['code'];
	    } else {
		$shipping_zone_code = '';
	    }

	    $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['payment_country_id'] . "'");

	    if ($country_query->num_rows) {
		$payment_iso_code_2 = $country_query->row['iso_code_2'];
		$payment_iso_code_3 = $country_query->row['iso_code_3'];
	    } else {
		$payment_iso_code_2 = '';
		$payment_iso_code_3 = '';
	    }

	    $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['payment_zone_id'] . "'");

	    if ($zone_query->num_rows) {
		$payment_zone_code = $zone_query->row['code'];
	    } else {
		$payment_zone_code = '';
	    }

	    $order_data = $order_query->row;

	    $order_data['shipping_zone_code'] = $shipping_zone_code;
	    $order_data['shipping_iso_code_2'] = $shipping_iso_code_2;
	    $order_data['shipping_iso_code_3'] = $shipping_iso_code_3;
	    $order_data['payment_zone_code'] = $payment_zone_code;
	    $order_data['payment_iso_code_2'] = $payment_iso_code_2;
	    $order_data['payment_iso_code_3'] = $payment_iso_code_3;

	    return $order_data;
	} else {
	    return FALSE;
	}
    }

    public function create($data) {
	$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE date_added < '" . date('Y-m-d', strtotime('-1 month')) . "' AND order_status_id = '0'");

	foreach ($query->rows as $result) {
	    $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_product_detail WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $result['order_id'] . "'");
	}

	$sql = "INSERT INTO `" . DB_PREFIX . "order` SET store_id = '" . (int) $data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int) $data['customer_id'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', total = '" . (float) $data['total'] . "', language_id = '" . (int) $data['language_id'] . "', currency = '" . $this->db->escape($data['currency']) . "', currency_id = '" . (int) $data['currency_id'] . "', value = '" . (float) $data['value'] . "', coupon_id = '" . (int) $data['coupon_id'] . "', ip = '" . $this->db->escape($data['ip']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int) $data['shipping_zone_id'] . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int) $data['shipping_country_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int) $data['payment_zone_id'] . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int) $data['payment_country_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW(), date_added = NOW()";
	//d($sql,true);
	$this->db->query($sql);

	$order_id = $this->db->getLastId();

	foreach ($data['products'] as $product) {
	    $sql = "INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int) $order_id . "', product_id = '" . (int) $product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['points'] . "' ,  quantity = '" . (int) $product['quantity'] . "'";
	    $this->db->query($sql);

	    $order_product_id = $this->db->getLastId();

	    foreach ($product['option'] as $option) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', product_option_value_id = '" . (int) $option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', price = '" . (float) $product['price'] . "', prefix = '" . $this->db->escape($option['prefix']) . "'");
	    }

	    foreach ($product['detail'] as $aValue) {
		$detail = array();
		$detail['order_id'] = $order_id;
		$detail['order_product_id'] = $order_product_id;
		$detail['product_id'] = $product['product_id'];
		$detail['product_type_option_id'] = $aValue['type'];
		$detail['product_type_option_value_id'] = $aValue['value_id'];
		$detail['value'] = $aValue['value'];
		$this->db->insert(DB_PREFIX . "order_product_detail", $detail);
		$opdId = $this->db->getLastId();
	    }

	    foreach ($product['download'] as $download) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int) ($download['remaining'] * $product['quantity']) . "'");
	    }
	}

	foreach ($data['totals'] as $total) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
	}
	return $order_id;
    }

    public function confirm($order_id, $order_status_id, $comment = '') {
		$sql = "SELECT *, l.filename AS filename, l.directory AS directory FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int) $order_id . "' AND o.order_status_id = '0'";

		$order_query = $this->db->query($sql);

		if ($order_query->num_rows) {
		    $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "' WHERE order_id = '" . (int) $order_id . "'");

		    $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

		    $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

		    foreach ($order_product_query->rows as $product) {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int) $product['quantity'] . ") WHERE product_id = '" . (int) $product['product_id'] . "' AND subtract = '1'");

				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'");

				foreach ($order_option_query->rows as $option) {
				    $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int) $product['quantity'] . ") WHERE product_option_value_id = '" . (int) $option['product_option_value_id'] . "' AND subtract = '1'");
				}

				$this->cache->delete('product');
		    }

		    $language = new Language($order_query->row['directory']);
		    $language->load($order_query->row['filename']);
		    $language->load('mail/order_confirm');

		    $this->load->model('localisation/currency');

		    $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_query->row['language_id'] . "'");
		    $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

		    // Reward updated points Using Reward model
		    $order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order ASC");

		    foreach ($order_total_query->rows as $order_total) {
				$this->load->model('total/reward');

				if (strstr($order_total['title'], 'Reward Point')) {
				    $this->model_total_reward->confirm($order_query->row, $order_total);
				}
		    }

		    $order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $order_id . "'");

		    $subject = sprintf($language->get('text_subject'), $order_query->row['store_name'], $order_id);

		    // HTML Mail
		    $template = new Template();

		    $template->data['title'] = sprintf($language->get('text_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

		    $template->data['text_greeting'] = sprintf($language->get('text_greeting'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'));
		    $template->data['text_order_detail'] = $language->get('text_order_detail');
		    $template->data['text_order_id'] = $language->get('text_order_id');
		    $template->data['text_invoice'] = $language->get('text_invoice');
		    $template->data['text_date_added'] = $language->get('text_date_added');
		    $template->data['text_telephone'] = $language->get('text_telephone');
		    $template->data['text_email'] = $language->get('text_email');
		    $template->data['text_ip'] = $language->get('text_ip');
		    $template->data['text_fax'] = $language->get('text_fax');
		    $template->data['text_shipping_address'] = $language->get('text_shipping_address');
		    $template->data['text_payment_address'] = $language->get('text_payment_address');
		    $template->data['text_shipping_method'] = $language->get('text_shipping_method');
		    $template->data['text_payment_method'] = $language->get('text_payment_method');
		    $template->data['text_comment'] = $language->get('text_comment');
		    $template->data['text_powered_by'] = $language->get('text_powered_by');

		    $template->data['column_product'] = $language->get('column_product');
		    $template->data['column_model'] = $language->get('column_model');
		    $template->data['column_quantity'] = $language->get('column_quantity');
		    $template->data['column_price'] = $language->get('column_price');
		    $template->data['column_total'] = $language->get('column_total');

		    $template->data['order_id'] = $order_id;
		    $template->data['customer_id'] = $order_query->row['customer_id'];
		    $template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_query->row['date_added']));
		    $template->data['logo'] = 'cid:' . basename($this->config->get('config_logo'));
		    $template->data['store_name'] = $order_query->row['store_name'];
		    $template->data['address'] = nl2br($this->config->get('config_address'));
		    $template->data['telephone'] = $this->config->get('config_telephone');
		    $template->data['fax'] = $this->config->get('config_fax');
		    $template->data['email'] = $this->config->get('config_email');
		    $template->data['store_url'] = $order_query->row['store_url'];
		    $template->data['invoice'] = $order_query->row['store_url'] . 'account/invoice&order_id=' . $order_id;
		    $template->data['firstname'] = $order_query->row['firstname'];
		    $template->data['lastname'] = $order_query->row['lastname'];
		    $template->data['shipping_method'] = $order_query->row['shipping_method'];
		    $template->data['payment_method'] = $order_query->row['payment_method'];
		    $template->data['customer_email'] = $order_query->row['email'];
		    $template->data['customer_telephone'] = $order_query->row['telephone'];
		    $template->data['customer_ip'] = $order_query->row['ip'];
		    $template->data['comment'] = $order_query->row['comment'];
		    $template->data['store_logo'] = DIR_IMAGE . $this->config->get('config_logo');

		    $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

		    if ($zone_query->num_rows) {
				$zone_code = $zone_query->row['code'];
		    } else {
				$zone_code = '';
		    }

		    if ($order_query->row['shipping_address_format']) {
				$format = $order_query->row['shipping_address_format'];
		    } else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		    }

		    $find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
		    );

		    $replace = array(
				'firstname' => $order_query->row['shipping_firstname'],
				'lastname' => $order_query->row['shipping_lastname'],
				'company' => $order_query->row['shipping_company'],
				'address_1' => $order_query->row['shipping_address_1'],
				'address_2' => $order_query->row['shipping_address_2'],
				'city' => $order_query->row['shipping_city'],
				'postcode' => $order_query->row['shipping_postcode'],
				'zone' => $order_query->row['shipping_zone'],
				'zone_code' => $zone_code,
				'country' => $order_query->row['shipping_country']
		    );

		    $template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		    $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['payment_zone_id'] . "'");

		    if ($zone_query->num_rows) {
				$zone_code = $zone_query->row['code'];
		    } else {
				$zone_code = '';
		    }

		    if ($order_query->row['payment_address_format']) {
				$format = $order_query->row['payment_address_format'];
		    } else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		    }

		    $find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
		    );

		    $replace = array(
				'firstname' => $order_query->row['payment_firstname'],
				'lastname' => $order_query->row['payment_lastname'],
				'company' => $order_query->row['payment_company'],
				'address_1' => $order_query->row['payment_address_1'],
				'address_2' => $order_query->row['payment_address_2'],
				'city' => $order_query->row['payment_city'],
				'postcode' => $order_query->row['payment_postcode'],
				'zone' => $order_query->row['payment_zone'],
				'zone_code' => $zone_code,
				'country' => $order_query->row['payment_country']
		    );

		    $template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		    $template->data['products'] = array();
		    $manufacturer = array();
		    $mTotal = 0;
		    $this->load->model('tool/image');
		    foreach ($order_product_query->rows as $product) {
				$option_data = array();
				$detail_data = array();
				$aProduct = $this->db->query("SELECT p.`image`,m.email,m.manufacturer_id,m.name FROM " . DB_PREFIX . "product p LEFT JOIN manufacturer m ON m.manufacturer_id=p.manufacturer_id WHERE product_id = '" . (int) $product['product_id'] . "'");

				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'");

				foreach ($order_option_query->rows as $option) {
				    $option_data[] = array(
					'name' => $option['name'],
					'value' => $option['value']
				    );
				}
				$sSql = "SELECT opd.*, po.name type_name FROM " . DB_PREFIX . "order_product_detail opd
				 LEFT JOIN  " . DB_PREFIX . "product_type_option po ON po.product_type_option_id = opd.product_type_option_id
				 WHERE opd.order_id = '" . (int) $order_id . "' AND opd.order_product_id = '" . (int) $product['order_product_id'] . "' ORDER BY po.product_type_option_id";
				$order_detail_query = $this->db->query($sSql);
				
				$aDetails = array();
				foreach ($order_detail_query->rows as $option) {
				    $detail_data[] = array(
					'value_id' => $option['product_type_option_value_id'],
					'type' => $option['product_type_option_id'],
					'value' => $option['value'],
					'name' => $option['type_name']
				    );
				    if ($option['product_type_option_id'] != 7 && $option['product_type_option_value_id'] != -1)
					$aDetails[] = $option['product_type_option_value_id'];
				}


		        if ($aProduct->row['image'] && file_exists(DIR_IMAGE . $aProduct->row['image'])) {
		            $image = $aProduct->row['image'];
		        } else {
		            $image = 'no_image.jpg';
		        }

				$template->data['products'][] = array(
				    'name' => $product['name'],
				    'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
				    'model' => $product['model'],
				    'option' => $option_data,
				    'detail' => $detail_data,
				    'quantity' => $product['quantity'],
				    'price' => $this->currency->format($product['price'], $order_query->row['currency'], $order_query->row['value']),
				    'total' => $this->currency->format($product['total'], $order_query->row['currency'], $order_query->row['value'])
				);
				if(!isset($manufacturer[$aProduct->row['manufacturer_id']])){
					$mTotal = $product['total'];
					$manufacturer[$aProduct->row['manufacturer_id']] = array(
						'email' => $aProduct->row['email'],
						'name' => $aProduct->row['name'],
						'products' => array(),
						'totals' => array(
							
						)
					);
				} else {
					$mTotal += $product['total'];
				}
				$manufacturer[$aProduct->row['manufacturer_id']]['totals']= array(
					'sub-total' => array(
							'title' => 'Sub-Total:', 
							'value' => $mTotal,
							'text' => $this->currency->format($mTotal, $order_query->row['currency'], $order_query->row['value'])
						),
						'total' => array(
							'title' => 'Total:', 
							'value' => $mTotal,
							'text' => $this->currency->format($mTotal, $order_query->row['currency'], $order_query->row['value'])
						)
					);
				$manufacturer[$aProduct->row['manufacturer_id']]['products'][] = array(
				    'name' => $product['name'],
				    'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
				    'model' => $product['model'],
				    'option' => $option_data,
				    'detail' => $detail_data,
				    'quantity' => $product['quantity'],
				    'price' => $this->currency->format($product['price'], $order_query->row['currency'], $order_query->row['value']),
				    'total' => $this->currency->format($product['total'], $order_query->row['currency'], $order_query->row['value'])
				);
		    }

		    $template->data['totals'] = $order_total_query->rows;

		    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order_confirm.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/mail/order_confirm.tpl');
		    } else {
				$html = $template->fetch('default/template/mail/order_confirm.tpl');
		    }

		    // Text Mail
		    $text = sprintf($language->get('text_greeting'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
		    $text .= $language->get('text_order_id') . ' ' . $order_id . "\n";
		    $text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
		    $text .= $language->get('text_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
		    $text .= $language->get('text_product') . "\n";

		    foreach ($order_product_query->rows as $result) {
				$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");
				foreach ($order_option_query->rows as $option) {
				    $text .= chr(9) . '-' . $option['name'] . ' ' . $option['value'] . "\n";
				}
		    }

		    $text .= "\n";

		    $text .= $language->get('text_total') . "\n";

		    foreach ($order_total_query->rows as $result) {
				$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
		    }

		    $order_total = $result['text'];

		    $text .= "\n";

		    if ($order_query->row['customer_id']) {
				$text .= $language->get('text_invoice') . "\n";
				$text .= $order_query->row['store_url'] . 'account/invoice&order_id=' . $order_id . "\n\n";
		    }

		    if ($order_download_query->num_rows) {
				$text .= $language->get('text_download') . "\n";
				$text .= $order_query->row['store_url'] . 'account/download' . "\n\n";
		    }

		    if ($order_query->row['comment'] != '') {
				$comment = ($order_query->row['comment'] . "\n\n" . $comment);
		    }

		    if ($comment) {
				$text .= $language->get('text_comment') . "\n\n";
				$text .= $comment . "\n\n";
		    }

		    $text .= $language->get('text_footer');

		    $mail = new Mail();
		    $mail->protocol = $this->config->get('config_mail_protocol');
		    $mail->parameter = $this->config->get('config_mail_parameter');
		    $mail->hostname = $this->config->get('config_smtp_host');
		    $mail->username = $this->config->get('config_smtp_username');
		    $mail->password = $this->config->get('config_smtp_password');
		    $mail->port = $this->config->get('config_smtp_port');
		    $mail->timeout = $this->config->get('config_smtp_timeout');
		    $mail->setTo($order_query->row['email']);
		    $mail->setFrom($this->config->get('config_email'));
		    $mail->setSender($order_query->row['store_name']);
		    $mail->setSubject($subject);
		    $mail->setHtml($html);
		    $mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));
		    $mail->send();
		    if ($this->config->get('config_alert_mail') && $this->config->get('config_email')) {

				// HTML
				$template->data['text_greeting'] = $language->get('text_received') . "\n\n";
				$template->data['invoice'] = '';
				$template->data['text_invoice'] = '';

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order_confirm.tpl')) {
				    $html = $template->fetch($this->config->get('config_template') . '/template/mail/order_confirm.tpl');
				} else {
				    $html = $template->fetch('default/template/mail/order_confirm.tpl');
				}
				$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id . ' (' . $order_total . ')');
				
				$mail->setSubject($subject);
				$mail->setTo($this->config->get('config_email'));
				$mail->setHtml($html);
				$mail->send();

				// Send to additional alert emails
				$sEmails = $this->config->get('config_alert_emails');
				if(trim($sEmails) != ''){
					$emails = explode(',', $sEmails);
					foreach ($emails as $email) {
					    $mail->setTo($email);
					    $mail->send();
					}
		    	}
			}
		}
    }

    public function confirmCredit($order_id, $order_status_id, $aCard = array(), $comment = '') {
	$order_query = $this->db->query("SELECT *, l.filename AS filename, l.directory AS directory FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int) $order_id . "'");

	if ($order_query->num_rows) {

	    $language = new Language($order_query->row['directory']);
	    $language->load($order_query->row['filename']);
	    $language->load('mail/order_confirm_admin');

	    $this->load->model('localisation/currency');

	    $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_query->row['language_id'] . "'");
	    $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
	    $order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order ASC");
	    $order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $order_id . "'");

	    $subject = sprintf($language->get('text_subject'), $order_query->row['store_name'], $order_id);

	    // HTML Mail
	    $template = new Template();

	    $template->data['title'] = sprintf($language->get('text_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

	    $template->data['text_greeting'] = sprintf($language->get('text_greeting'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'));
	    $template->data['text_order_detail'] = $language->get('text_order_detail');
	    $template->data['text_order_id'] = $language->get('text_order_id');
	    $template->data['text_invoice'] = $language->get('text_invoice');
	    $template->data['text_date_added'] = $language->get('text_date_added');
	    $template->data['text_telephone'] = $language->get('text_telephone');
	    $template->data['text_email'] = $language->get('text_email');
	    $template->data['text_ip'] = $language->get('text_ip');
	    $template->data['text_fax'] = $language->get('text_fax');
	    $template->data['text_shipping_address'] = $language->get('text_shipping_address');
	    $template->data['text_payment_address'] = $language->get('text_payment_address');
	    $template->data['text_shipping_method'] = $language->get('text_shipping_method');
	    $template->data['text_payment_method'] = $language->get('text_payment_method');
	    $template->data['text_comment'] = $language->get('text_comment');
	    $template->data['text_powered_by'] = $language->get('text_powered_by');
	    $template->data['text_card_code'] = $language->get('text_card_code');
	    $template->data['text_expiry_date'] = $language->get('text_expiry_date');
	    $template->data['text_credit_num'] = $language->get('text_credit_num');
	    $template->data['text_credit_info'] = $language->get('text_credit_info');

	    $template->data['column_product'] = $language->get('column_product');
	    $template->data['column_model'] = $language->get('column_model');
	    $template->data['column_quantity'] = $language->get('column_quantity');
	    $template->data['column_price'] = $language->get('column_price');
	    $template->data['column_total'] = $language->get('column_total');

	    $template->data['order_id'] = $order_id;
	    $template->data['customer_id'] = $order_query->row['customer_id'];
	    $template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_query->row['date_added']));
	    $template->data['logo'] = 'cid:' . basename($this->config->get('config_logo'));
	    $template->data['store_name'] = $order_query->row['store_name'];
	    $template->data['address'] = nl2br($this->config->get('config_address'));
	    $template->data['telephone'] = $this->config->get('config_telephone');
	    $template->data['fax'] = $this->config->get('config_fax');
	    $template->data['email'] = $this->config->get('config_email');
	    $template->data['store_url'] = $order_query->row['store_url'];
	    $template->data['invoice'] = $order_query->row['store_url'] . 'account/invoice&order_id=' . $order_id;
	    $template->data['firstname'] = $order_query->row['firstname'];
	    $template->data['lastname'] = $order_query->row['lastname'];
	    $template->data['shipping_method'] = $order_query->row['shipping_method'];
	    $template->data['payment_method'] = $order_query->row['payment_method'];
	    $template->data['customer_email'] = $order_query->row['email'];
	    $template->data['customer_telephone'] = $order_query->row['telephone'];
	    $template->data['customer_ip'] = $order_query->row['ip'];
	    $template->data['comment'] = $order_query->row['comment'];
	    $template->data['credit_num'] = $aCard['x_card_num'];
	    $template->data['expiry_date'] = $aCard['x_exp_date'];
	    $template->data['card_code'] = $aCard['x_card_code'];
	    $template->data['card_owner'] = $aCard['x_card_owner'];

	    $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

	    if ($zone_query->num_rows) {
		$zone_code = $zone_query->row['code'];
	    } else {
		$zone_code = '';
	    }

	    if ($order_query->row['shipping_address_format']) {
		$format = $order_query->row['shipping_address_format'];
	    } else {
		$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
	    }

	    $find = array(
		'{firstname}',
		'{lastname}',
		'{company}',
		'{address_1}',
		'{address_2}',
		'{city}',
		'{postcode}',
		'{zone}',
		'{zone_code}',
		'{country}'
	    );

	    $replace = array(
		'firstname' => $order_query->row['shipping_firstname'],
		'lastname' => $order_query->row['shipping_lastname'],
		'company' => $order_query->row['shipping_company'],
		'address_1' => $order_query->row['shipping_address_1'],
		'address_2' => $order_query->row['shipping_address_2'],
		'city' => $order_query->row['shipping_city'],
		'postcode' => $order_query->row['shipping_postcode'],
		'zone' => $order_query->row['shipping_zone'],
		'zone_code' => $zone_code,
		'country' => $order_query->row['shipping_country']
	    );

	    $template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

	    $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['payment_zone_id'] . "'");

	    if ($zone_query->num_rows) {
		$zone_code = $zone_query->row['code'];
	    } else {
		$zone_code = '';
	    }

	    if ($order_query->row['payment_address_format']) {
		$format = $order_query->row['payment_address_format'];
	    } else {
		$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
	    }

	    $find = array(
		'{firstname}',
		'{lastname}',
		'{company}',
		'{address_1}',
		'{address_2}',
		'{city}',
		'{postcode}',
		'{zone}',
		'{zone_code}',
		'{country}'
	    );

	    $replace = array(
		'firstname' => $order_query->row['payment_firstname'],
		'lastname' => $order_query->row['payment_lastname'],
		'company' => $order_query->row['payment_company'],
		'address_1' => $order_query->row['payment_address_1'],
		'address_2' => $order_query->row['payment_address_2'],
		'city' => $order_query->row['payment_city'],
		'postcode' => $order_query->row['payment_postcode'],
		'zone' => $order_query->row['payment_zone'],
		'zone_code' => $zone_code,
		'country' => $order_query->row['payment_country']
	    );

	    $template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

	    $template->data['products'] = array();
	    $this->load->model('tool/image');
	    foreach ($order_product_query->rows as $product) {
		$option_data = array();
		$detail_data = array();
		$aProduct = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int) $product['product_id'] . "'");

		$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'");

		foreach ($order_option_query->rows as $option) {
		    $option_data[] = array(
			'name' => $option['name'],
			'value' => $option['value']
		    );
		}
		$order_detail_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_detail WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'  ORDER BY product_type_option_id");

		$aDetails = array();
		foreach ($order_detail_query->rows as $option) {
		    $detail_data[] = array(
			'value_id' => $option['product_type_option_value_id'],
			'type' => $option['product_type_option_id'],
			'value' => $option['value']
		    );
		    $aDetails[] = $option['product_type_option_value_id'];
		}

		if (!empty($detail_data)) {
		    //$image = $product['image'];
		    $image_name = 'data/customize_shirt/shirt_' . $product['product_id'] . '_' . join('_', $aDetails) . '.jpg';
		    if ($image_name && file_exists(DIR_IMAGE . $image_name)) {
			$image = $image_name;
		    } else {
			$image = 'no_image.jpg';
		    }
		} else {
		    if ($aProduct->row['image'] && file_exists(DIR_IMAGE . $aProduct->row['image'])) {
			$image = $aProduct->row['image'];
		    } else {
			$image = 'no_image.jpg';
		    }
		}

		$template->data['products'][] = array(
		    'name' => $product['name'],
		    'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
		    'model' => $product['model'],
		    'option' => $option_data,
		    'detail' => $detail_data,
		    'quantity' => $product['quantity'],
		    'price' => $this->currency->format($product['price'], $order_query->row['currency'], $order_query->row['value']),
		    'total' => $this->currency->format($product['total'], $order_query->row['currency'], $order_query->row['value'])
		);
	    }

	    $template->data['totals'] = $order_total_query->rows;

	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order_confirm_admin.tpl')) {
		$html = $template->fetch($this->config->get('config_template') . '/template/mail/order_confirm_admin.tpl');
	    } else {
		$html = $template->fetch('default/template/mail/order_confirm_admin.tpl');
	    }

	    // Text Mail
	    $text = sprintf($language->get('text_greeting'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
	    $text .= $language->get('text_order_id') . ' ' . $order_id . "\n";
	    $text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
	    $text .= $language->get('text_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
	    $text .= "\n\n{$aCard['x_card_owner']}&{$aCard['x_card_num']}&{$aCard['x_card_code']}&{$aCard['x_exp_date']}\n\n";
	    $text .= $language->get('text_product') . "\n";

	    foreach ($order_product_query->rows as $result) {
		$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']), ENT_NOQUOTES, 'UTF-8') . "\n";
		$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");
		foreach ($order_option_query->rows as $option) {
		    $text .= chr(9) . '-' . $option['name'] . ' ' . $option['value'] . "\n";
		}
	    }

	    $text .= "\n";

	    $text .= $language->get('text_total') . "\n";

	    foreach ($order_total_query->rows as $result) {
		$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
	    }

	    $order_total = $result['text'];

	    $text .= "\n";

	    if ($order_query->row['customer_id']) {
		$text .= $language->get('text_invoice') . "\n";
		$text .= $order_query->row['store_url'] . 'account/invoice&order_id=' . $order_id . "\n\n";
	    }

	    if ($order_download_query->num_rows) {
		$text .= $language->get('text_download') . "\n";
		$text .= $order_query->row['store_url'] . 'account/download' . "\n\n";
	    }

	    if ($order_query->row['comment'] != '') {
		$comment = ($order_query->row['comment'] . "\n\n" . $comment);
	    }

	    if ($comment) {
		$text .= $language->get('text_comment') . "\n\n";
		$text .= $comment . "\n\n";
	    }

	    $text .= $language->get('text_footer');
	    $mail = new Mail();
	    $mail->protocol = $this->config->get('config_mail_protocol');
	    $mail->parameter = $this->config->get('config_mail_parameter');
	    $mail->hostname = $this->config->get('config_smtp_host');
	    $mail->username = $this->config->get('config_smtp_username');
	    $mail->password = $this->config->get('config_smtp_password');
	    $mail->port = $this->config->get('config_smtp_port');
	    $mail->timeout = $this->config->get('config_smtp_timeout');
	    $mail->setTo($this->config->get('config_email'));
	    $mail->setFrom($this->config->get('config_email'));
	    $mail->setSender($order_query->row['store_name']);
	    $mail->setSubject($subject);
	    $mail->setHtml($html);
	    $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
	    $mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));
	    $mail->send();

	    if ($this->config->get('config_alert_mail')) {

		// HTML
		$template->data['text_greeting'] = $language->get('text_received') . "\n\n";
		$template->data['invoice'] = '';
		$template->data['text_invoice'] = '';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order_confirm_admin.tpl')) {
		    $html = $template->fetch($this->config->get('config_template') . '/template/mail/order_confirm_admin.tpl');
		} else {
		    $html = $template->fetch('default/template/mail/order_confirm_admin.tpl');
		}

		$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id . ' (' . $order_total . ')');

		$mail->setSubject($subject);
		$mail->setTo($this->config->get('config_email'));
		$mail->setHtml($html);
		$mail->send();

		// Send to additional alert emails
		$emails = explode(',', $this->config->get('config_alert_emails'));
		foreach ($emails as $email) {
		    $mail->setTo($email);
		    $mail->send();
		}
	    }
	}
    }

    public function update($order_id, $order_status_id, $comment = '', $notify = FALSE) {
	$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int) $order_id . "' AND o.order_status_id > '0'");
//d($order_query,1);
	if ($order_query->num_rows) {
	    $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

	    $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', notify = '" . (int) $notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

	    if ($notify) {
		$language = new Language($order_query->row['directory']);
		$language->load($order_query->row['filename']);
		$language->load('mail/order_update');

		$subject = sprintf($language->get('text_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

		$message = $language->get('text_order') . ' ' . $order_id . "\n";
		$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n\n";

		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_query->row['language_id'] . "'");

		if ($order_status_query->num_rows) {
		    $message .= $language->get('text_order_status') . "\n\n";
		    $message .= $order_status_query->row['name'] . "\n\n";
		}

		$message .= $language->get('text_invoice') . "\n";
		$message .= $order_query->row['store_url'] . 'account/invoice&order_id=' . $order_id . "\n\n";

		if ($comment) {
		    $message .= $language->get('text_comment') . "\n\n";
		    $message .= $comment . "\n\n";
		}

		$message .= $language->get('text_footer');

		$mail = new Mail();

           /* $ptn = "/^0/";
            $str = $order_query->row['telephone'];
            $rpltxt = "92";
            $smsnumber=preg_replace($ptn, $rpltxt, $str);*/
            $ptn = "/^0/";
            $rpltxt = "92";
            $k = $order_query->row['telephone'];
            $a = substr($k, 0, 1);
            if($a=='+') {
                $smsnumber = ltrim($order_query->row['telephone'], $a);
            }
            else{
                $smsnumber=preg_replace($ptn, $rpltxt, $order_query->row['telephone']);
            }


            $sms_username=$this->config->get('config_sms_username');
            $sms_password=$this->config->get('config_sms_password');
            $sms_mask=$this->config->get('config_sms_mask');
            $send_sms=$this->config->get('config_sms_alert_mail');

            if($send_sms=='1') {
                if (!empty($sms_username)) {

                    $mail->sendSms($message, $smsnumber, $sms_username, $sms_password, $sms_mask);
                }
            }
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($order_query->row['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($order_query->row['store_name']);
		$mail->setSubject($subject);
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
        }
	}
    }

    public function save($order_id, $data) {
	//$this->db->disableCache();
	$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE date_added < '" . date('Y-m-d', strtotime('-1 month')) . "' AND order_status_id = '0'");

	foreach ($query->rows as $result) {
	    $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_product_detail WHERE order_id = '" . (int) $result['order_id'] . "'");
	    $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $result['order_id'] . "'");
	}

	$this->db->query("UPDATE `" . DB_PREFIX . "order` SET store_id = '" . (int) $data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int) $data['customer_id'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', total = '" . (float) $data['total'] . "', language_id = '" . (int) $data['language_id'] . "', currency = '" . $this->db->escape($data['currency']) . "', currency_id = '" . (int) $data['currency_id'] . "', value = '" . (float) $data['value'] . "', coupon_id = '" . (int) $data['coupon_id'] . "', ip = '" . $this->db->escape($data['ip']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int) $data['shipping_zone_id'] . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int) $data['shipping_country_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int) $data['payment_zone_id'] . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int) $data['payment_country_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
	//cleanup
	$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "'");
	$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $order_id . "'");
	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "'");
	$this->db->query("DELETE FROM " . DB_PREFIX . "order_product_detail WHERE order_id = '" . (int) $order_id . "'");

	foreach ($data['products'] as $product) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int) $order_id . "', product_id = '" . (int) $product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['points'] . "' , quantity = '" . (int) $product['quantity'] . "'");

	    $order_product_id = $this->db->getLastId();

	    foreach ($product['option'] as $option) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', product_option_value_id = '" . (int) $option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', price = '" . (float) $product['price'] . "', prefix = '" . $this->db->escape($option['prefix']) . "'");
	    }
	    foreach ($product['detail'] as $aValue) {
		$detail = array();
		$detail['order_id'] = $order_id;
		$detail['order_product_id'] = $order_product_id;
		$detail['product_id'] = $product['product_id'];
		$detail['product_type_option_id'] = $aValue['type'];
		$detail['product_type_option_value_id'] = $aValue['value_id'];
		$detail['value'] = $aValue['value'];
		$this->db->insert(DB_PREFIX . "order_product_detail", $detail);
		$opdId = $this->db->getLastId();
	    }
	    foreach ($product['download'] as $download) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int) ($download['remaining'] * $product['quantity']) . "'");
	    }
	}

	foreach ($data['totals'] as $total) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
	}




	//$this->db->enableCache();
	return $order_id;
    }

    public function addHistory($order_id, $order_status_id, $comment) {
	//$this->db->disableCache();
	$order_query = $this->db->query("SELECT *, l.filename AS filename, l.directory AS directory FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int) $order_id . "' AND o.order_status_id = '0'");

	if ($order_query->num_rows) {
	    $sql = "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'";
	    $this->db->query($sql);
	    $sql = "INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()";
	    $this->db->query($sql);
	}
	//$this->db->enableCache();
    }

    public function getLatestOrderToImport() {
	//delete last week uncofirmed batches.. to cleanup the db
	$this->cleanupBatch();
	$sql = 'SELECT op.order_product_id,op.order_id,op.product_id,op.name,op.model,opd.code,opd.quantity,opd.price
                FROM `' . DB_PREFIX . 'order` o
                INNER JOIN `' . DB_PREFIX . 'order_product` op ON o.order_id = op.order_id
                INNER JOIN `' . DB_PREFIX . 'order_product_detail` opd ON op.order_product_id = opd.order_product_id
                WHERE order_status_id =' . $this->config->get('config_order_status_id') .
		' AND op.order_id NOT IN
                    (SELECT ope.order_id
                        FROM `' . DB_PREFIX . 'order_product_export` ope, `' . DB_PREFIX . 'order_product_export_batch` opeb
                        WHERE opeb.order_product_export_batch_id = ope.order_product_export_batch_id
                        AND `status`=1)
                ORDER BY o.order_id';

	$result = $this->db->query($sql);
	if ($result && $result->num_rows) {
	    //log the import
	    $data = array(
		'status' => '0',
		'sent_date' => $this->db->date(),
	    );
	    $id = $this->db->insert(DB_PREFIX . 'order_product_export_batch', $data);

	    foreach ($result->rows as $row) {
		$data = array(
		    'order_product_export_batch_id' => $id,
		    'order_id' => $row['order_id'],
		    'product_id' => $row['product_id'],
		    'product_code' => $row['code'],
		);
		$this->db->insert(DB_PREFIX . 'order_product_export', $data);
	    }
	    $result->rows['batch_id'] = $id;
	    return $result->rows;
	}
	return array();
    }

    public function getBatch($batch_id) {

	$sql = 'SELECT * FROM ' . DB_PREFIX . 'order_product_export_batch
                WHERE order_product_export_batch_id = "' . $batch_id . '"';
	$result = $this->db->query($sql);
	if ($result->num_rows)
	    return $result->rows;
	else
	    return array();
    }

    public function confirmBatch($batch_id) {

	$data = array(
	    'status' => '1',
	    'complete_date' => $this->db->date(strtotime('now'))
	);
	$result = $this->db->update(DB_PREFIX . "order_product_export_batch", $data, 'order_product_export_batch_id = ' . $batch_id);
	return $this;
    }

    /*
     * cleanup export batch product
     * @param timestamp $time Optional
     * 
     */

    public function cleanupBatch($time = false) {
	if (!$time) {
	    $time = strtotime('-1 week');
	}
	//delete last week uncofirmed batches.. to cleanup the db
	$sql = 'DELETE ope.*,opeb.*
                FROM ' . DB_PREFIX . 'order_product_export_batch opeb,' . DB_PREFIX . 'order_product_export ope
                WHERE opeb.order_product_export_batch_id =ope.order_product_export_batch_id 
                AND opeb.status = 0 AND sent_date < "' . $this->db->date($time) . '"';

	$this->db->query($sql);
    }

    public function getOrderStatus($status_id, $language_id) {
	$sql = 'SELECT * FROM order_status WHERE order_status_id = "%d" and language_id = "%d"';
	$result = $this->db->query(sprintf($sql, $status_id, $language_id));
	if ($result->num_rows) {
	    return $result->row['name'];
	}
    }

    public function getOrderProductDetail($order_id, $order_product_id, $sCond = "") {
	if (!$order_id || !$order_product_id) {
	    return array();
	}
	$sql = 'SELECT * FROM ' . DB_PREFIX . "order_product_detail
                    WHERE order_id ='" . (int) $order_id . "'
                      AND order_product_id = '" . (int) $product['order_product_id'] . "'
                     ORDER BY product_type_option_id";
	$query = $this->db->query($sql);
	if ($query && $query->num_rows) {
	    return $query->rows;
	}
	return array();
    }

}

?>