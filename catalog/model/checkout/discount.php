<?php

class ModelCheckoutDiscount extends Model {

    public function getDiscount($products) {
        // Initialize discount array(); 
        $aDiscounts = array();
        $discount_applied = false;
        // getting all discount that are active today
        $sql = "SELECT * FROM " . DB_PREFIX . "discount d
                LEFT JOIN " . DB_PREFIX . "discount_description dd ON (d.discount_id = dd.discount_id)
                WHERE dd.language_id = '" . (int) $this->config->get('config_language_id') . "'
                      AND ((date_start = '0000-00-00' OR date_start <= CURDATE())
                      AND (date_end = '0000-00-00' OR date_end >= CURDATE()))
                      AND d.status = '1'
                      AND d.is_deleted = 0
                ORDER BY d.sort_order ASC";

       

        $discount_query = $this->db->query($sql);
        //d($this->customer->getCustomerGroupId());
        if ($discount_query->num_rows) {
            // d($discount_query->rows);
            // d($this->customer->getCustomerGroupId());
            foreach ($discount_query->rows as $row) {
                // Status to apply discount:
                $status = false;
                //reset($products);

                $aDiscount = array();

                // Check the Is Customer Login
                // Check if discount apply on customer or visitor and on which customer group
                if (!$row['customer_group_id'] ||
                        ($this->customer->isLogged() &&
                        $row['customer_group_id'] == $this->customer->getCustomerGroupId())) {

                    $discount_product_data = array();
                    $query = "SELECT * FROM " . DB_PREFIX . "discount_product WHERE discount_id = '" . (int) $row['discount_id'] . "'";
                    //d($query);
                    $discount_product_query = $this->db->query($query);

                    if ($discount_product_query->rows > 0) {

                        foreach ($discount_product_query->rows as $result) {
                            $discount_product_data[] = $result['product_id'];
                        }
                        //$status = true;
                    }

                    //Discount on Total
                    if (!$discount_applied) {

                        $iQuantity = 0;
                        $iTotal = 0;
                       

                        foreach ($products as $product) {
                            $iQuantity += $product['quantity'];

                            if (empty($discount_product_data)) {

                                $iTotal += $product['total'];
                                //d("iTotal : ".$iTotal);
                                $status = true;
                            } else {
                                if(in_array($product['product_id'], $discount_product_data)) {
                                    $iTotal += $product['total'];
                                    //d("iTotal : ".$iTotal);
                                    $status = true;
                                }
                            }
                        }


                        /*
                          foreach ($products as $product) {
                          $iQuantity += $product['quantity'];
                          $iTotal += $product['total'];
                          if (empty($discount_product_data) || in_array($product['product_id'], $discount_product_data)) {

                          $status = true;
                          }
                          }





                          if (!$row['quantity'] || ($iQuantity && $row['quantity'] >= $iQuantity)) {
                          $status = true;
                          } */


                        // apply discount
                        if ($status && (!$row['total'] || ($iTotal && $row['total'] <= $iTotal))) {
                            $status = true;
                        } else {
                            $status = false;
                        }


                        // d("status : ".$status);

                        if ($status) {
                            $aDiscounts[] = array(
                                'id' => $row['discount_id'],
                                'customer_group_id' => $row['customer_group_id'],
                                'apply_discount' => ((int) $row['discount']) > 0,
                                'discount' => $row['discount'],
                                'total' => $iTotal,
                                'type' => $row['type'],
                                'shipping' => $row['shipping'],
                                'name' => $row['name'],
                                'product_id' => $product['product_id'],
                            );
                            $discount_applied = true;
                        }
                    }//if discount applicable
                }//endif customer login
            }//foreach discount
        }
        
        return $aDiscounts;
    }

//end function
}

//end class
?>