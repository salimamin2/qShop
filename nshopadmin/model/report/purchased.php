<?php
class ModelReportPurchased extends Model {
	public function getProductPurchasedReport($start = 0, $limit = 20,$data) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}

		$sql1="SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.total + op.tax) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_id = o.order_id) WHERE o.order_status_id > '0'";

        if (isset($data['filter_country_id']) && $data['filter_country_id']) {
            $sql1 .= " AND o.shipping_country_id = '" . (int)$data['filter_country_id'] . "'";
        }
        $sql1.=" GROUP BY model ORDER BY total DESC LIMIT " . (int)$start . "," . (int)$limit;
        $query = $this->db->query($sql1);
		return $query->rows;
	}
	
	public function getTotalOrderedProducts($data) {
      	$sql1="SELECT * FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
        if (isset($data['filter_country_id']) && $data['filter_country_id']) {
            $sql1 .= " WHERE o.shipping_country_id = '" . (int)$data['filter_country_id'] . "'";
        }
        $sql1 .=" GROUP BY model";
        $query = $this->db->query($sql1);
		return $query->num_rows;
	}
}
?>