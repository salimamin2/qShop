<?php

/*
 * NOTICE OF LICENSE
 * 
 *  This source file is subject to the Open Software License (OSL 3.0)
 *  that is bundled with this package in the file LICENSE.txt.
 *  It is also available through the world-wide-web at this URL:
 *  http://opensource.org/licenses/osl-3.0.php
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to license@q-sols.com so we can send you a copy immediately.
 * 
 * 
 *  @copyright   Copyright (c) 2010 Q-Solutions. (www.q-sols.com)
 *  @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<?php

class ModelSettingProductPrice extends Model {

    public function update($data) {
        $sql = "DELETE FROM `product_price`";
        $this->db->query($sql);
        
        $percentage = 100;
        foreach($data['product_price'] as $percent) {
            $sql = "INSERT INTO `product_price`";
            $sql .= " SET quantity = " . (int) $percent['quantity'];
            $sql .= " , percent = " . (int) $percent['percent'];
            $sql .= " , priority = " . (int) $percent['priority'];
            
            $this->db->query($sql);
            if($percentage > $percent['percent']) {
                $percentage = $percent['percent'];
            }
        }
        
        $sql  = "SELECT p.product_id, MIN(pd.price) AS price";
        $sql .= " FROM `product` p ";
        $sql .= " INNER JOIN `product_detail` pd ON p.product_id = pd.product_id";
        $sql .= " WHERE p.status = 1 AND pd.status = 1 ";
        $sql .= " GROUP BY p.product_id";
        
        $query = $this->db->query($sql);
        $products = $query->rows;
        foreach($products as $product) {
            $sql = "UPDATE product set price = '" . round($product['price'] * (1+($percentage/100)),2) . "' where product_id = '" . (int)$product['product_id'] . "'";
            
            $this->db->query($sql);
        }
    }
    
    public function getPrices() {
        $sql = "SELECT * FROM product_price";
        $query = $this->db->query($sql);
        return $query->rows;
    }
}

?>