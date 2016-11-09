<?php

class ModelSaleOrder extends Model {

    public function addOrder($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', email = '" . $this->db->escape($data['email']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int) $data['shipping_zone_id'] . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int) $data['shipping_country_id'] . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int) $data['payment_zone_id'] . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int) $data['payment_country_id'] . "', ip = '" . $this->db->escape('0.0.0.0') . "', total = '" . $this->db->escape(preg_replace("/[^0-9.]/", '', $data['total'])) . "', date_modified = NOW()");

        $order_id = $this->db->getLastId();

        if (isset($data['product'])) {
            foreach ($data['product'] as $product) {
                if ($product['product_id']) {
                    $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id='" . (int) $product['product_id'] . "'");

                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int) $order_id . "', product_id = '" . (int) $product['product_id'] . "', name = '" . $this->db->escape($product_query->row['name']) . "', model = '" . $this->db->escape($product_query->row['model']) . "', price = '" . $this->db->escape(preg_replace("/[^0-9.]/", '', $product['price'])) . "', total = '" . $this->db->escape(preg_replace("/[^0-9.]/", '', $product['total'])) . "', quantity = '" . $this->db->escape($product['quantity']) . "'");
                }
            }
        }
    }

    public function editOrder($order_id, $data) {
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET telephone = '" . $this->db->escape($data['telephone']) . "', email = '" . $this->db->escape($data['email']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int) $data['shipping_zone_id'] . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int) $data['shipping_country_id'] . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int) $data['payment_zone_id'] . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int) $data['payment_country_id'] . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        if (isset($data['product'])) {
            foreach ($data['product'] as $product) {

                if ($product['product_id']) {
                    $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id='" . (int) $product['product_id'] . "'");

                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int) $order_id . "', product_id = '" . (int) $product['product_id'] . "', name = '" . $this->db->escape($product_query->row['name']) . "', model = '" . $this->db->escape($product_query->row['model']) . "', price = '" . $this->db->escape(preg_replace("/[^0-9.]/", '', $product['price'])) . "', total = '" . $this->db->escape(preg_replace("/[^0-9.]/", '', $product['total'])) . "', quantity = '" . $this->db->escape($product['quantity']) . "'");
                }
            }
        }

        foreach ($data['totals'] as $key => $value) {
            $this->db->query("UPDATE " . DB_PREFIX . "order_total SET text = '" . $this->db->escape($value) . "' WHERE order_total_id = '" . (int) $key . "'");
        }
    }

    public function deleteOrder($order_id) {
        if ($this->config->get('config_stock_subtract')) {
            $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int) $order_id . "'");

            if ($order_query->num_rows) {
                $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

                foreach ($product_query->rows as $product) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int) $product['quantity'] . ") WHERE product_id = '" . (int) $product['product_id'] . "'");

                    $option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'");

                    foreach ($option_query->rows as $option) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int) $product['quantity'] . ") WHERE product_option_value_id = '" . (int) $option['product_option_value_id'] . "' AND subtract = '1'");
                    }
                    $order_detail_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_detail WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'");
                    foreach ($order_detail_query->rows as $option) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_detail SET quantity = (quantity + " . (int) $product['quantity'] . ") WHERE product_detail_id = '" . (int) $option['product_detail_id'] . "'");
                    }
                }
            }
        }

        $this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product_detail WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product_detail_options WHERE order_id = '" . (int) $order_id . "'");
    }

    public function addOrderHistory($order_id, $data) {
        //d($order_id,1);

        $Porder_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product`  WHERE order_id = '" . (int) $order_id . "'");
        $Ostatus = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_history`  WHERE order_id = '" . (int) $order_id . "'AND order_status_id='".$this->config->get('config_return_order_status')."'");


        if($Ostatus->row['order_status_id']!=$this->config->get('config_return_order_status')) {
            foreach ($Porder_query->rows as $Aorder) {

                if ($data['order_status_id'] == $this->config->get('config_return_order_status')) {

                    $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity =quantity+ '" . (int)$Aorder['quantity'] . "' WHERE product_id = '" . (int)$Aorder['product_id'] . "'");

                }
            }
        }


        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int) $data['notify'] : 0) . "', notify_manufacturer = '" . (isset($data['notify_manufacturer']) ? (int) $data['notify_manufacturer'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

        $order_query = '';
        if ($data['notify']) {
            $order_query = $this->db->query("SELECT *, os.name AS status FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int) $order_id . "'");

            if ($order_query->num_rows) {
                $language = new Language($order_query->row['directory']);
                $language->load($order_query->row['filename']);
                $language->load('mail/order');

                $this->load->model('setting/store');

                $subject = sprintf($language->get('text_subject'), $order_query->row['store_name'], $order_id);

                $message = $language->get('lsm_logo');
                $message = $language->get('text_order') . ' ' . $order_id . "\n";
                $message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n\n";
                $message .= $language->get('text_order_status') . "\n\n";
                $message .= $order_query->row['status'] . "\n\n";
                $message .= $language->get('text_invoice') . "\n";
                $message .= html_entity_decode($order_query->row['store_url'] . 'account/invoice&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";

                if ($data['comment']) {
                    $message .= $language->get('text_comment') . "\n\n";
                    $message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
                }

                $message .= $language->get('text_footer');

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
                $mail->setText(html_entity_decode(nl2br($message), ENT_QUOTES, 'UTF-8'));
                $mail->send();
            }
        }
        // Notify Manufacturer
        if($data['notify_manufacturer']){
            if(!$order_query){
                $order_query = $this->db->query("SELECT *, os.name AS status FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int) $order_id . "'");
            }
            $language = new Language($order_query->row['directory']);
            $language->load($order_query->row['filename']);
            $language->load('mail/order_confirm');
            
            // HTML Mail
            $template = new Template();

            $template->data['title'] = sprintf($language->get('text_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

            $template->data['text_order_detail'] = $language->get('text_order_detail');
            $template->data['text_order_id'] = $language->get('text_order_id');
            $template->data['text_invoice'] = $language->get('text_invoice');
            $template->data['text_customer'] = $language->get('text_customer');
            $template->data['text_date_added'] = $language->get('text_date_added');
            $template->data['text_telephone'] = $language->get('text_telephone');
            $template->data['text_email'] = $language->get('text_email');
            $template->data['text_ip'] = $language->get('text_ip');
            $template->data['text_fax'] = $language->get('text_fax');
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

            $template->data['products'] = array();
            $order_product_query = $this->db->query("SELECT op.*, p.image, m.name manufacturer_name, m.manufacturer_id, m.email FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = op.product_id LEFT JOIN manufacturer m ON m.manufacturer_id=p.manufacturer_id WHERE op.order_id = '" . (int) $order_id . "'");
            $manufacturer = array();
            $mTotal = 0;
            $this->load->model('tool/image');
            foreach ($order_product_query->rows as $aProduct) {
                $option_data = array();
                $detail_data = array();

                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $aProduct['order_product_id'] . "'");

                foreach ($order_option_query->rows as $option) {
                    $option_data[] = array(
                    'name' => $option['name'],
                    'value' => $option['value']
                    );
                }
                $sSql = "SELECT opd.*, po.name type_name FROM " . DB_PREFIX . "order_product_detail opd
                 LEFT JOIN  " . DB_PREFIX . "product_type_option po ON po.product_type_option_id = opd.product_type_option_id
                 WHERE opd.order_id = '" . (int) $order_id . "' AND opd.order_product_id = '" . (int) $aProduct['order_product_id'] . "' ORDER BY po.product_type_option_id";
                $order_detail_query = $this->db->query($sSql);
                //$order_detail_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_detail WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'  ORDER BY product_type_option_id");
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

                if ($aProduct['image'] && file_exists(DIR_IMAGE . $aProduct['image'])) {
                    $image = $aProduct['image'];
                } else {
                    $image = 'no_image.jpg';
                }

                if(!isset($manufacturer[$aProduct['manufacturer_id']])){
                    $manufacturer[$aProduct['manufacturer_id']] = array(
                        'email' => $aProduct['email'],
                        'name' => $aProduct['manufacturer_name'],
                        'products' => array(),
                        'totals' => array(
                            
                        )
                    );
                }
                $manufacturer[$aProduct['manufacturer_id']]['totals']= array(
                    'sub-total' => array(
                            'title' => 'Sub-Total:', 
                            'value' => $mTotal,
                            'text' => $this->currency->format($mTotal, $order_query->row['currency'], $order_query->row['value'])
                        )
                    );
                $manufacturer[$aProduct['manufacturer_id']]['products'][] = array(
                    'name' => $aProduct['name'],
                    'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
                    'model' => $aProduct['model'],
                    'option' => $option_data,
                    'detail' => $detail_data,
                    'quantity' => $aProduct['quantity'],
                    'price' => $this->currency->format($aProduct['price'], $order_query->row['currency'], $order_query->row['value']),
                    'total' => $this->currency->format($aProduct['total'], $order_query->row['currency'], $order_query->row['value'])
                );
            }
            foreach ($manufacturer as $manufactur) {
                if($manufactur['email'] == ''){
                        continue;
                }
                $subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);

                $template->data['text_greeting'] = sprintf($language->get('text_received'),$manufactur['name']) . "\n\n";
                $template->data['invoice'] = '';
                $template->data['text_invoice'] = '';
                $template->data['products'] = $manufactur['products'];
                $template->data['totals'] = $manufactur['totals'];

                $html = $template->fetch('mail/order_confirm_manufacturer.tpl');

                $manufacturer_mail = new Mail();
                $manufacturer_mail->protocol = $this->config->get('config_mail_protocol');
                $manufacturer_mail->parameter = $this->config->get('config_mail_parameter');
                $manufacturer_mail->hostname = $this->config->get('config_smtp_host');
                $manufacturer_mail->username = $this->config->get('config_smtp_username');
                $manufacturer_mail->password = $this->config->get('config_smtp_password');
                $manufacturer_mail->port = $this->config->get('config_smtp_port');
                $manufacturer_mail->timeout = $this->config->get('config_smtp_timeout');
                $manufacturer_mail->setTo($manufactur['email']);
                $manufacturer_mail->setFrom($this->config->get('config_email'));
                $manufacturer_mail->setSender($order_query->row['store_name']);
                $manufacturer_mail->setSubject($subject);
                $manufacturer_mail->setHtml($html);
                $manufacturer_mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));
                $manufacturer_mail->send();
            }
        }
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");

        if ($order_query->num_rows) {
            $reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}
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
                'invoice_no' => $order_query->row['invoice_prefix'].$order_query->row['invoice_id'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'customer_group_id' => $order_query->row['customer_group_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'telephone' => $order_query->row['telephone'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],
                'reward' => $reward,
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

    public function getOrders($data = array()) {
        $sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS name, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status, o.date_added, o.total, o.currency, o.value, o.payment_method FROM `" . DB_PREFIX . "order` o";

        if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
        }

        if (isset($data['filter_payment_method']) && !is_null($data['filter_payment_method'])) {
            $sql .= " AND o.payment_method = '" . (float) $data['filter_payment_method'] . "'";
        }

        $sort_data = array(
            'o.order_id',
            'name',
            'status',
            'o.date_added',
            'o.total',
            'o.payment_method'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY o.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
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
    public function getPendingOrders($data = array()) {
        $sql = "SELECT os.name AS o_status,o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS `name`,o.date_added, o.total, o.currency, o.value, o.payment_method FROM " . DB_PREFIX . "`order` o INNER JOIN " . DB_PREFIX . "order_status os ON o.`order_status_id`=os.order_status_id WHERE os.name ='Pending'";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function generateInvoiceId($order_id) {
        $query = $this->db->query("SELECT MAX(invoice_id) AS invoice_id FROM `" . DB_PREFIX . "order`");

        if ($query->row['invoice_id']) {
            $invoice_id = (int) $query->row['invoice_id'] + 1;
        } elseif ($this->config->get('config_invoice_id')) {
            $invoice_id = $this->config->get('config_invoice_id');
        } else {
            $invoice_id = 1;
        }

        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_id = '" . (int) $invoice_id . "', invoice_prefix = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

        return $this->config->get('config_invoice_prefix') . $invoice_id;
    }

    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }
    public function getOrderShippedProducts($order_id,$product_id) {
        $sql="SELECT count(osp.`shipped_qty`) AS shipped_qty FROM " . DB_PREFIX . "order_shipment_product osp INNER JOIN" . DB_PREFIX . " order_shipment os ON osp.order_shipment_id=os.order_shipment_id  WHERE os.order_id = '" . (int) $order_id . "' AND osp.product_id='". (int) $product_id ."'";

        $query = $this->db->query($sql);
        if($query->num_rows) {
            return $query->row['shipped_qty'];
        }
    }

    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->rows;
    }

    public function getOrderProductDetailOptions($order_id, $order_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_detail op LEFT JOIN product_type_option po ON po.product_type_option_id = op.product_type_option_id WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "' ORDER BY op.product_type_option_id");

        return $query->rows;
    }

    public function getOrderTotals($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");

        return $query->rows;
    }

    public function getOrderHistory($order_id) {
        $query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added");

        return $query->rows;
    }

    public function getOrderDownloads($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int) $order_id . "' ORDER BY name");

        return $query->rows;
    }

    public function getTotalOrders($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

        if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
            $sql .= " WHERE order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE order_status_id > '0'";
        }

        if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
            $sql .= " AND order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
            $sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
            $sql .= " AND total = '" . (float) $data['filter_total'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrdersByStoreId($store_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int) $store_id . "'");

        return $query->row['total'];
    }

    public function getOrderHistoryTotalByOrderStatusId($order_status_id) {
        $query = $this->db->query("SELECT oh.order_id FROM " . DB_PREFIX . "order_history oh LEFT JOIN `" . DB_PREFIX . "order` o ON (oh.order_id = o.order_id) WHERE oh.order_status_id = '" . (int) $order_status_id . "' AND o.order_status_id > '0' GROUP BY order_id");

        return $query->num_rows;
    }

    public function getTotalOrdersByOrderStatusId($order_status_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int) $order_status_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByLanguageId($language_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int) $language_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByCurrencyId($currency_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int) $currency_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalSales() {
        $query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalSalesByYear($year) {
        $query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND YEAR(date_added) = '" . (int) $year . "'");

        return $query->row['total'];
    }

    public function getOrderHistories($order_id) {
//      if ($start < 0) {
//          $start = 0;
//      }
//
//      if ($limit < 1) {
//          $limit = 10;
//      }
//
//      $query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);
//
//      return $query->rows;

        $oOrm = ORM::for_table('order_history')
                    ->table_alias('oh')
                    ->select_expr("oh.date_added, os.name AS status, oh.comment, oh.notify, oh.notify_manufacturer")
                    ->left_outer_join('order_status',array('oh.order_status_id','=','os.order_status_id'),'os')
                    ->where('oh.order_id',$order_id)
                    ->where('os.language_id',$this->config->get('config_language_id'))
                    ->order_by_asc('oh.date_added')
                    ->find_many(true);
        return $oOrm;
    }

    public function getTotalOrderHistories($order_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

        return $query->row['total'];
    }

    public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

        return $query->row['total'];
    }
    public function getOrderVoucherByVoucherId($voucher_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

        return $query->row;
    }
    public function addOrderShipped($data,$products_shipping){
        $sql="INSERT INTO `" . DB_PREFIX . "order_shipment` SET order_id = '" . (int) $data['OrderID'] . "', shipment_date = '" . $data['shipment_date'] . "', tracking_no = '" . $this->db->escape($data['shipment_tracking']) . "', comments = '" . $this->db->escape($data['shipment_comments']) . "', date_added = NOW()";
        $this->db->query($sql);
        $order_shipped_id = $this->db->getLastId();

        if (isset($products_shipping)) {
            foreach ($products_shipping as $product_id=>$qty) {
                $sql="INSERT INTO " . DB_PREFIX . "order_shipment_product SET order_shipment_id = '" . (int) $order_shipped_id . "', product_id = '" . (int) $product_id . "', shipped_qty = '" . (int) $qty . "', date_added = NOW()";
                $this->db->query($sql);
            }
        }
    }

    public function getShippedOrdersbyOrderId($order_id) {
        $sql = "SELECT os.*, osp.* ,pd.`name` 
                FROM
                  order_shipment os
                  LEFT JOIN order_shipment_product osp
                  ON osp.`order_shipment_id` = os.order_shipment_id
                  LEFT JOIN product_description pd
                  ON osp.`product_id` = pd.product_id
                  LEFT JOIN product_description prd
                  ON osp.`product_id` = prd.product_id
                WHERE order_id = '".$order_id."'";
        $query = $this->db->query($sql);
        return $query->rows;
    }

}

?>