<?php

class ModelAccountOrder extends Model {

    public function getOrder($order_id,$customer_id = false) {
	$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (!$customer_id ? $this->session->data['hdn_customer_id'] : $customer_id) . "' AND order_status_id > '0'");
//d($order_query,1);
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

	    $order_data = array(
		'order_id' => $order_query->row['order_id'],
		'invoice_id' => $order_query->row['invoice_id'],
		'invoice_prefix' => $order_query->row['invoice_prefix'],
		'customer_id' => $order_query->row['customer_id'],
		'firstname' => $order_query->row['firstname'],
		'lastname' => $order_query->row['lastname'],
		'telephone' => $order_query->row['telephone'],
		'fax' => $order_query->row['fax'],
		'email' => $order_query->row['email'],
		'shipping_firstname' => $order_query->row['shipping_firstname'],
		'shipping_lastname' => $order_query->row['shipping_lastname'],
		'shipping_company' => $order_query->row['shipping_company'],
		'shipping_address_1' => $order_query->row['shipping_address_1'],
		'shipping_address_2' => $order_query->row['shipping_address_2'],
		'shipping_postcode' => $order_query->row['shipping_postcode'],
		'shipping_city' => $order_query->row['shipping_city'],
		'shipping_zone_id' => $order_query->row['shipping_zone_id'],
		'shipping_zone' => $order_query->row['shipping_zone'],
		'shipping_zone_code' => $shipping_zone_code,
		'shipping_country_id' => $order_query->row['shipping_country_id'],
		'shipping_country' => $order_query->row['shipping_country'],
		'shipping_iso_code_2' => $shipping_iso_code_2,
		'shipping_iso_code_3' => $shipping_iso_code_3,
		'shipping_address_format' => $order_query->row['shipping_address_format'],
		'shipping_method' => $order_query->row['shipping_method'],
		'payment_firstname' => $order_query->row['payment_firstname'],
		'payment_lastname' => $order_query->row['payment_lastname'],
		'payment_company' => $order_query->row['payment_company'],
		'payment_address_1' => $order_query->row['payment_address_1'],
		'payment_address_2' => $order_query->row['payment_address_2'],
		'payment_postcode' => $order_query->row['payment_postcode'],
		'payment_city' => $order_query->row['payment_city'],
		'payment_zone_id' => $order_query->row['payment_zone_id'],
		'payment_zone' => $order_query->row['payment_zone'],
		'payment_zone_code' => $payment_zone_code,
		'payment_country_id' => $order_query->row['payment_country_id'],
		'payment_country' => $order_query->row['payment_country'],
		'payment_iso_code_2' => $payment_iso_code_2,
		'payment_iso_code_3' => $payment_iso_code_3,
		'payment_address_format' => $order_query->row['payment_address_format'],
		'payment_method' => $order_query->row['payment_method'],
		'comment' => $order_query->row['comment'],
		'total' => $order_query->row['total'],
		'order_status_id' => $order_query->row['order_status_id'],
		'language_id' => $order_query->row['language_id'],
		'currency_id' => $order_query->row['currency_id'],
		'currency' => $order_query->row['currency'],
		'value' => $order_query->row['value'],
		'coupon_id' => $order_query->row['coupon_id'],
		'date_modified' => $order_query->row['date_modified'],
		'date_added' => $order_query->row['date_added'],
		'ip' => $order_query->row['ip']
	    );

	    return $order_data;
	} else {
	    return FALSE;
	}
    }

    public function getOrders($start = 0, $limit = 20) {
		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, 
			o.total, o.currency, o.value FROM `" . DB_PREFIX . "order` o 
			LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) 
			WHERE customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' 
			AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC");
		return $query->rows;
    }

    public function getOrderProducts($order_id) {
	$query = $this->db->query("SELECT op.*,p.image FROM " . DB_PREFIX . "order_product op INNER JOIN " . DB_PREFIX . "product p ON p.product_id=op.product_id WHERE op.order_id = '" . (int) $order_id . "'");

	return $query->rows;
    }

    public function getOrderCatProducts() {
	$sql = "SELECT count(*) as count FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON o.order_id =op.order_id Where o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id = 5";
	$qCount = $this->db->query($sql);
	$sql = "SELECT op.product_id,sp.category_name,sp.category_id,op.name,op.model,op.price,sp.uom,sp.tax_class_id, o.date_added
                        FROM " . DB_PREFIX . "order_product op
                        INNER JOIN
                        (SELECT p.product_id, pc.category_id,cd.name as category_name,p.uom,p.tax_class_id
                        FROM " . DB_PREFIX . "product p
                        LEFT JOIN " . DB_PREFIX . "product_to_category pc ON p.product_id = pc.product_id
                        LEFT JOIN " . DB_PREFIX . "category_description cd ON cd.category_id = pc.category_id
                        AND p.status = 1) sp ON op.product_id = sp.product_id
                        LEFT JOIN `" . DB_PREFIX . "order` o ON o.order_id = op.order_id 
                        WHERE o.order_status_id = 5 AND o.customer_id = '" . (int) $this->customer->getId() . "'";
	if ($qCount->row['count'] > 100)
	    $sql .=" AND o.date_added BETWEEN date_sub(NOW(),interval 1 year) AND NOW()";
	$sql .=" ORDER BY sp.category_id";
	$query = $this->db->query($sql);
	return $query->rows;
    }

    public function getOrderOptions($order_id, $order_product_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

	return $query->rows;
    }

    public function getProductOptions($order_id, $order_product_id) {
        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "' ORDER BY product_option_id");
        $aOption = array();
        foreach ($product_option_query->rows as $product_option) {
            $aOption[$product_option['product_option_id']] = $product_option['product_option_value_id'];
        }

        return $aOption;
    }

    public function getOrderDetailOptions($order_id, $order_product_id) {
	$sSql = "SELECT opd.*, po.name type_name FROM " . DB_PREFIX . "order_product_detail opd
		 LEFT JOIN  " . DB_PREFIX . "product_type_option po ON po.product_type_option_id = opd.product_type_option_id
		 WHERE opd.order_id = '" . (int) $order_id . "' AND opd.order_product_id = '" . (int) $order_product_id . "' ORDER BY product_type_option_id";
	$query = $this->db->query($sSql);

	return $query->rows;
    }

    public function getOrderTotals($order_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");

	return $query->rows;
    }

    public function getOrderHistories($order_id) {
	$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND oh.notify = '1' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added");

	return $query->rows;
    }

    public function getOrderDownloads($order_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $order_id . "' ORDER BY name");

	return $query->rows;
    }

    public function getTotalOrders() {
	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int) $this->customer->getId() . "' AND order_status_id > '0'");

	return $query->row['total'];
    }

    public function getTotalOrderProductsByOrderId($order_id) {
	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

	return $query->row['total'];
    }

}

?>