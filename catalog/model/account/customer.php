<?php

class ModelAccountCustomer extends Model {

    public function addCustomer($data) {
	$aCustomer = $this->getNewsletterCustomerByEmail($data['email']);
	if ($aCustomer) {
	    $sSql = "UPDATE " . DB_PREFIX . "customer
                SET store_id = '" . (int) $this->config->get('config_store_id') . "',
                    firstname = '" . $this->db->escape(trim($data['firstname'])) . "',
                    lastname = '" . $this->db->escape(trim($data['lastname'])) . "',
                    email = '" . strtolower(trim($this->db->escape($data['email']))) . "',
                    telephone = '" . $this->db->escape(trim($data['telephone'])) . "',
                    fax = '" . $this->db->escape(trim($data['fax'])) . "',
                    password = '" . $this->db->escape(md5($data['password'])) . "',
                    newsletter = '" . $this->db->escape($data['newsletter']) . "',
                    customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "',
                    status = '1',
                    is_customer = '1',
                    date_added = NOW()
                WHERE customer_id = '" . (int) $aCustomer['customer_id'] . "'";
	    $this->db->query($sSql);
	    $customer_id = $aCustomer['customer_id'];
	} else {
	    $sSql = "INSERT INTO " . DB_PREFIX . "customer
                SET store_id = '" . (int) $this->config->get('config_store_id') . "',
                    firstname = '" . $this->db->escape(trim($data['firstname'])) . "',
                    lastname = '" . $this->db->escape(trim($data['lastname'])) . "',
                    email = '" . strtolower(trim($this->db->escape($data['email']))) . "',
                    telephone = '" . $this->db->escape(trim($data['telephone'])) . "',
                    fax = '" . $this->db->escape(trim($data['fax'])) . "',
                    password = '" . $this->db->escape(md5($data['password'])) . "',
                    newsletter = '" . $this->db->escape($data['newsletter']) . "',
                    customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "',
                    status = '1',
                    date_added = NOW()";
	    $this->db->query($sSql);

	    $customer_id = $this->db->getLastId();
	}
	/*
	  $sSql = "INSERT INTO " . DB_PREFIX . "address
	  SET customer_id = '" . (int) $customer_id . "',
	  firstname = '" . $this->db->escape($data['firstname']) . "',
	  lastname = '" . $this->db->escape($data['lastname']) . "',
	  company = '" . $this->db->escape($data['company']) . "',
	  address_1 = '" . $this->db->escape($data['address_1']) . "',
	  address_2 = '" . $this->db->escape($data['address_2']) . "',
	  city = '" . $this->db->escape($data['city']) . "',
	  postcode = '" . $this->db->escape($data['postcode']) . "',
	  country_id = '" . (int) $data['country_id'] . "',
	  zone_id = '" . (int) $data['zone_id'] . "'";
	  $this->db->query($sSql);

	  $address_id = $this->db->getLastId();

	  $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'"); */
	return $customer_id;
    }

    public function addAddress($customer_id, $data, $isDefault = true) {
	$sSql = "INSERT INTO " . DB_PREFIX . "address
                SET customer_id = '" . (int) $customer_id . "',
                    firstname = '" . $this->db->escape(trim($data['firstname'])) . "',
                    lastname = '" . $this->db->escape(trim($data['lastname'])) . "',
                    company = '" . $this->db->escape(trim($data['company'])) . "',
                    address_1 = '" . $this->db->escape(trim($data['address_1'])) . "',
                    address_2 = '" . $this->db->escape(trim($data['address_2'])) . "',
                    city = '" . $this->db->escape(trim($data['city'])) . "',
                    postcode = '" . $this->db->escape($data['postcode']) . "',
                    country_id = '" . (int) $data['country_id'] . "',
                    zone_id = '" . (int) $data['zone_id'] . "'";


	$this->db->query($sSql);


	$addess_id = $this->db->getLastId();
	if ($isDefault) {
	    $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");
	}
	return $addess_id;
    }

    public function editCustomer($data) {
        $sSql = "UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape(trim($data['firstname'])) . "', lastname = '" . $this->db->escape(trim($data['lastname'])) . "', email = '" . strtolower($this->db->escape(trim($data['email']))) . "', telephone = '" . $this->db->escape(trim($data['telephone'])) . "', fax = '" . $this->db->escape(trim($data['fax'])) . "'";
        if(trim($data['password']) != ''){
            $sSql .= ", password = '" . $this->db->escape(md5($data['password'])) . "'";
        }
        $sSql .= ", newsletter = '". (int) (isset($data['newsletter']) ? 1 : 0) ."' WHERE customer_id = '" . (int) $this->customer->getId() . "'";

    	$this->db->query($sSql);
    }

    public function editPassword($email, $password) {
	$this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5($password)) . "' WHERE email = '" . $this->db->escape($email) . "'");
    }

    public function editNewsletter($newsletter) {
	$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int) $newsletter . "' WHERE customer_id = '" . (int) $this->customer->getId() . "'");
    }

    public function addPasswordPing($email, $password_ping) {
	$this->db->query("UPDATE " . DB_PREFIX . "customer SET password_ping = '" . $this->db->escape($password_ping) . "' WHERE LOWER(email) = '" . strtolower($this->db->escape($email)) . "' AND is_customer = 1");
    }

    public function getCustomer($customer_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");

	return $query->row;
    }

    public function getCustomerByEmail($email) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "'");

	return $query->row;
    }

    public function getCustomerByPing($password_ping) {
	$this->db->disableCache();
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE password_ping = '" . $this->db->escape($password_ping) . "' AND is_customer = 1");
	$this->db->enableCache();
	return $query->row;
    }

    public function getTotalCustomersByEmail($email) {
	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "'");
	return $query->row['total'];
    }

    public function checkCustomerEmail($email,$customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "' AND customer_id != '" . (int) $customer_id . "'");
        return $query->row['total'];
    }

    public function addNewsletter($data) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . strtolower($this->db->escape($data['email'])) . "'", true);
	if ($query && isset($query->row['customer_id'])) {
	    $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 1 WHERE customer_id = '" . (int) $query->row['customer_id'] . "'");
	} else {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET  email = '" . strtolower($this->db->escape(@$data['email'])) . "', newsletter = 1 , is_customer = 0, status = '1', date_added = NOW()");
	}
    }

    public function getUser($user_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int) $user_id . "'");

	return $query->row;
    }

    public function getCustomerGroup($customer_group_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int) $customer_group_id . "'");

	return $query->row;
    }

    public function getNewsletterCustomerByEmail($email) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . strtolower($this->db->escape($email)) . "' AND is_customer = 0");
	return $query->row;
    }

    public function getTotalCustomersByEmailNewsletter($email) {
	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "' AND newsletter = 0");

	return $query->row['total'];
    }

    public function addGuestCustomer($data, $shippng = false) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . strtolower($this->db->escape($data['email'])) . "' AND is_customer = 1", true);
	$isNew = false;
	if ($query && isset($query->row['customer_id'])) {
	    $customer_id = $query->row['customer_id'];
	} else {
	    $customer_id = $this->addCustomer($data);
	    $this->addAddress($customer_id, $data);
	    if ($shippng)
		$this->addAddress($customer_id, $shippng, false);
	    $isNew = true;
	}
	return array($customer_id, $isNew);
    }

    public function getCustomerOrderCount($customer_id){
        $sql = "SELECT COUNT(order_id) AS total FROM " . DB_PREFIX . "`order` 
                WHERE customer_id = '" . $this->db->escape($customer_id) . "' AND order_status_id > 0";
                //d($sql);
        $query = $this->db->query($sql);
        //d($query);
        return $query->row['total'];

    }
}

?>