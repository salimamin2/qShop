<?php
class ModelReportOrder extends Model {
	public function getOrderReport($data = array()) {
		$sql = "SELECT
                        o.order_id,
                        o.invoice_id,
                        o.invoice_prefix,
                        o.store_id,
                        o.store_name,
                        o.store_url,
                        o.customer_id,
                        concat(o.firstname,' ',o.lastname) AS customer_name,
                        o.customer_group_id,
                        cg.name AS customer_group,
                        o.telephone,
                        o.fax,
                        o.email,
                        concat(o.shipping_firstname,' ',o.shipping_lastname) AS shipping_name,
                        o.shipping_company,
                        concat(o.shipping_address_1,' ',o.shipping_address_2) AS shipping_address,
                        o.shipping_city,
                        o.shipping_postcode,
                        o.shipping_zone,
                        o.shipping_country,
                        o.shipping_method,
                        concat(o.payment_firstname,' ',o.payment_lastname) AS payment_name,
                        o.payment_company,
                        concat(o.payment_address_1,' ',o.payment_address_2) AS payment_address,
                        o.payment_city,
                        o.payment_postcode,
                        o.payment_zone,
                        o.payment_country,
                        o.payment_method,
                        o.comment,
                        o.value,
                        o.total,
                        o.order_status_id,
                        os.name AS order_status,
                        o.currency,
                        o.coupon_id,
                        cup.code AS coupon_code,
                        cup.discount,
                        o.date_added,
                        o.ip,
                        op.product_id,
                        op.name AS product_name,
                        op.model AS product_model,
                        op.price AS product_price,
                        op.quantity,
                        oo.name AS option_name,
                        oo.value AS option_value,
                        concat(oo.prefix,oo.price) AS option_price
                        FROM `order` o
                        LEFT JOIN `order_product` op ON o.order_id = op.order_id
                        LEFT JOIN `order_option` oo ON op.order_product_id = oo.order_product_id
                        LEFT JOIN `customer_group` cg ON o.customer_group_id = cg.customer_group_id
                        LEFT JOIN `order_status` os ON o.order_status_id = os.order_status_id
                        LEFT JOIN `coupon` cup ON o.coupon_id = cup.coupon_id";

		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
        if (isset($data['filter_country_id']) && $data['filter_country_id']) {
            $sql .= " AND o.shipping_country_id = '" . (int)$data['filter_country_id'] . "'";
        }
        $date_start = date('Y-m-d',strtotime($data['filter_date_start']));
        $date_end = date('Y-m-d',strtotime($data['filter_date_end']));
		
		$sql .= " AND (DATE(o.date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(o.date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (isset($data['filter_customer'])) {
			$sql .= " AND o.customer_id ='". (int)$data['filter_customer']."'";
        }

        if (isset($data['filter_customer_group'])) {
            $sql .= " AND o.customer_group_id ='". (int)$data['filter_customer_group']."'";
        }

        $sql .= " GROUP BY o.order_id";
        $query = $this->db->query($sql);
		$results[0] = $query->num_rows;
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		$query = $this->db->query($sql);
		
                $results[1] = $query->rows;
                return $results;
	}
        public function getCustomers(){
            $sql = "SELECT DISTINCT customer_id,concat(firstname,' ',lastname) customer_name FROM `order` ORDER BY customer_name";
            $query = $this->db->query($sql);
            return $query->rows;
        }
}
?>