<?php

class ModelSaleCustomer extends Model {

    public function addCustomer($data) {
        $sql = "INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape(trim($data['firstname'])) . "', lastname = '" . $this->db->escape(trim($data['lastname'])) . "', email = '" . $this->db->escape(trim($data['email'])) . "', telephone = '" . $this->db->escape(trim($data['telephone'])) . "', fax = '" . $this->db->escape(trim($data['fax'])) . "', newsletter = '" . (int) $data['newsletter'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', password = '" . $this->db->escape(md5($data['password'])) . "', status = '" . (int) $data['status'] . "', lcn = '" . $this->db->escape($data['lcn']) . "', date_added = NOW()";
        $this->db->query($sql);
        $customer_id = $this->db->getLastId();
        if (isset($data['addresses'])) {
            foreach ($data['addresses'] as $address) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', firstname = '" . $this->db->escape(trim($address['firstname'])) . "', lastname = '" . $this->db->escape(trim($address['lastname'])) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape(trim($address['address_1'])) . "', address_2 = '" . $this->db->escape(trim($address['address_2'])) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape(trim($address['postcode'])) . "', country_id = '" . (int) $address['country_id'] . "', zone_id = '" . (int) $address['zone_id'] . "'");
            }
        }
    }

    public function editCustomer($customer_id, $data) {
        $sql = "UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape(trim($data['firstname'])) . "', lastname = '" . $this->db->escape(trim($data['lastname'])) . "', email = '" . $this->db->escape(trim($data['email'])) . "', telephone = '" . $this->db->escape(trim($data['telephone'])) . "', fax = '" . $this->db->escape(trim($data['fax'])) . "', newsletter = '" . (int) $data['newsletter'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', status = '" . (int) $data['status'] . "', lcn = '" . $this->db->escape($data['lcn']) . "' WHERE customer_id = '" . (int) $customer_id . "'";
        $this->db->query($sql);
        if ($data['password']) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE customer_id = '" . (int) $customer_id . "'");
        }
        $this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");
        if (isset($data['addresses'])) {
            foreach ($data['addresses'] as $address) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', firstname = '" . $this->db->escape(trim($address['firstname'])) . "', lastname = '" . $this->db->escape(trim($address['lastname'])) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape(trim($address['address_1'])) . "', address_2 = '" . $this->db->escape(trim($address['address_2'])) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape(trim($address['postcode'])) . "', country_id = '" . (int) $address['country_id'] . "', zone_id = '" . (int) $address['zone_id'] . "'");
            }
        }
    }

    public function getAddressesByCustomerId($customer_id,$limit = false) {
        $address_data = array();
        $sql = "SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'";
        if($limit) {
            $sql .= " LIMIT " . (int) $limit;
        }
        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $result['country_id'] . "'");
            if ($country_query->num_rows) {
                $country = $country_query->row['name'];
                $iso_code_2 = $country_query->row['iso_code_2'];
                $iso_code_3 = $country_query->row['iso_code_3'];
                $address_format = $country_query->row['address_format'];
            } else {
                $country = '';
                $iso_code_2 = '';
                $iso_code_3 = '';
                $address_format = '';
            }
            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $result['zone_id'] . "'");
            if ($zone_query->num_rows) {
                $zone = $zone_query->row['name'];
                $code = $zone_query->row['code'];
            } else {
                $zone = '';
                $code = '';
            }
            $address_data[] = array('address_id' => $result['address_id'], 'firstname' => $result['firstname'], 'lastname' => $result['lastname'], 'company' => $result['company'], 'address_1' => $result['address_1'], 'address_2' => $result['address_2'], 'postcode' => $result['postcode'], 'city' => $result['city'], 'zone_id' => $result['zone_id'], 'zone' => $zone, 'zone_code' => $code, 'country_id' => $result['country_id'], 'country' => $country, 'iso_code_2' => $iso_code_2, 'iso_code_3' => $iso_code_3, 'address_format' => $address_format);
        }
        return $address_data;
    }

    public function deleteCustomer($customer_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
        return $query->row;
    }

    public function getCustomerProfile($customer_id) {
        $sql = "SELECT c.customer_id,c.firstname,c.lastname,c.email,c.telephone,c.status,g.name AS group_name,c.approved FROM " . DB_PREFIX . "customer c";
        $sql .= " INNER JOIN " . DB_PREFIX . "customer_group g ON g.customer_group_id = c.customer_group_id";
        $sql .= " WHERE c.customer_id = '" . (int) $customer_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getCustomers($data = array()) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group, cr.name AS country_name FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) LEFT JOIN " . DB_PREFIX . "address ad ON (ad.address_id = c.address_id) LEFT JOIN " . DB_PREFIX . "country cr ON (cr.country_id = ad.country_id) ";
        $implode = array();
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
        }
        if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
            $implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
        }
        if (isset($data['filter_country_id']) && !is_null($data['filter_country_id'])) {
            $implode[] = "cr.country_id = '" . $this->db->escape($data['filter_country_id']) . "'";
        }
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }
        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        }
        if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }
        if (isset($data['is_customer'])) {
            $implode[] = "is_customer = " . $this->db->escape($data['is_customer']);
        }
        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }
        $sort_data = array('cg.name', 'c.email', 'customer_group', 'c.status', 'c.date_added');
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY cg.name";
        }
        if (isset($data['order']) && ( $data['order'] == 'DESC' )) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getCustomersCountry($customer_id){
        $sql1="SELECT ct.`name` FROM" . DB_PREFIX . " customer c INNER JOIN " . DB_PREFIX . " address ad ON c.address_id=ad.address_id INNER JOIN " . DB_PREFIX . " country ct ON ct.country_id=ad.country_id WHERE c.customer_id='".(int) $customer_id ."'";

        $query = $this->db->query($sql1);

        return $query->row['name'];
    }
    public function approve($customer_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int) $customer_id . "'");
    }

    public function getCustomersByNewsletter() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE newsletter = '1' ORDER BY firstname, lastname, email");
        return $query->rows;
    }

    public function getCustomersByKeyword($keyword) {
        if ($keyword) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LCASE(CONCAT(firstname, ' ', lastname)) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' OR email LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' ORDER BY firstname, lastname, email");
            return $query->rows;
        } else {
            return array();
        }
    }

    public function getCustomersByProduct($product_id) {
        if ($product_id) {
            $query = $this->db->query("SELECT DISTINCT `email` FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int) $product_id . "' AND o.order_status_id <> '0'");
            return $query->rows;
        } else {
            return array();
        }
    }

    public function getAddresses($keyword) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");
        return $query->rows;
    }

    public function getTotalCustomers($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";
        $implode = array();
        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "email = '" . $this->db->escape($data['filter_email']) . "'";
        }
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }
        if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }
        if (isset($data['is_customer'])) {
            $implode[] = "is_customer = " . $this->db->escape($data['is_customer']);
        }
        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }
        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function getTotalCustomersAwaitingApproval() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE status = '0' OR approved = '0'");
        return $query->row['total'];
    }

    public function getCustomersAwaitingApproval() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE status = '0' OR approved = '0'");
        return $query->rows;
    }

    public function getCustomersUpdated() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE (status = '1' OR approved = '1')");
        if ($query->num_rows > 0)
            return $query->rows;
        else
            return false;
    }

    public function getTotalAddressesByCustomerId($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");
        return $query->row['total'];
    }

    public function getTotalAddressesByCountryId($country_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int) $country_id . "'");
        return $query->row['total'];
    }

    public function getTotalAddressesByZoneId($zone_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int) $zone_id . "'");
        return $query->row['total'];
    }

    public function getTotalCustomersByCustomerGroupId($customer_group_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int) $customer_group_id . "'");
        return $query->row['total'];
    }

    public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

			$this->language->load('mail/customer');

			if ($order_id) {
				$this->load->model('sale/order');

				$order_info = $this->model_sale_order->getOrder($order_id);

				if ($order_info) {
					$store_name = $order_info['store_name'];
				} else {
					$store_name = $this->config->get('config_name');
				}
			} else {
				$store_name = $this->config->get('config_name');
			}

			$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
			$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_reward_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function deleteReward($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getRewards($customer_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalRewards($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomerRewardsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

    public function getTotalCustomersByEmail($email,$customer_id) {
        $oOrm = ORM::for_table('customer')
                    ->where('email',$email);
        if($customer_id != 0) {
            $oOrm = $oOrm->where_not_equal('customer_id',$customer_id);
        }
        $oOrm = $oOrm->count();
        return $oOrm;
    }

}
?>