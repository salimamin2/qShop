<?php
class ModelSaleCoupon extends Model {
	public function addCoupon($data) {
		$oOrm = ORM::for_table('coupon')->create();
        $oOrm->code = $data['code'];
        $oOrm->discount = $data['discount'];
        $oOrm->type = $data['type'];
        $oOrm->total = $data['total'];
        $oOrm->logged = $data['logged'];
        $oOrm->shipping = $data['shipping'];
        $oOrm->date_start = date('Y-m-d',strtotime($data['date_start']));
        $oOrm->date_end = date('Y-m-d',strtotime($data['date_end']));
        $oOrm->uses_total = $data['uses_total'];
        $oOrm->uses_customer = $data['uses_customer'];
        $oOrm->status = $data['status'];
        $oOrm->date_added = date('Y-m-d H:i:s');
        $oOrm->save();

        $coupon_id = $oOrm->id();
        foreach ($data['coupon_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET coupon_id = '" . (int)$coupon_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
      	}
		if (isset($data['coupon_product'])) {
      		foreach ($data['coupon_product'] as $product_id) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
      		}			
		}
	}
	
	public function editCoupon($coupon_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "coupon SET code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape(date('Y-m-d',strtotime($data['date_start']))) . "', date_end = '" . $this->db->escape(date('Y-m-d',strtotime($data['date_end']))) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "' WHERE coupon_id = '" . (int)$coupon_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");

      	foreach ($data['coupon_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET coupon_id = '" . (int)$coupon_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
      	}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		if (isset($data['coupon_product'])) {
      		foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
      		}
		}		
	}
	
	public function deleteCoupon($coupon_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");		
	}
	
	public function getCoupon($coupon_id) {
            
            $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
            //d($query);
            return $query->row;
	}
	
	public function getCoupons() {
		$sql = "SELECT c.coupon_id, cd.name, c.code, c.discount, c.date_start, c.date_end, c.status FROM " . DB_PREFIX . "coupon c LEFT JOIN " . DB_PREFIX . "coupon_description cd ON (c.coupon_id = cd.coupon_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        $sql .= " ORDER BY cd.name";

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getCouponDescriptions($coupon_id) {
		$coupon_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_description_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}
		
		return $coupon_description_data;
	}

	public function getCouponProducts($coupon_id) {
		$coupon_product_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}
		
		return $coupon_product_data;
	}
	
	public function getTotalCoupons() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon");
		
		return $query->row['total'];
	}

    public function getAllCoupons() {
        $oOrm = ORM::for_table('coupon')
                    ->find_many(true);
        return $oOrm;
    }
}
?>