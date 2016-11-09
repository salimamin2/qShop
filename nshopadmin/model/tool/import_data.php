<?php

class ModelToolImportData extends Model {

    public function restore($sql) {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);

            if ($sql) {
                $this->db->query($sql);
            }
        }
    }

    public function getTables() {
        $table_data = array();

        $query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

        foreach ($query->rows as $result) {
            $table_data[] = $result['Tables_in_' . DB_DATABASE];
        }

        return $table_data;
    }

    public function import($file) {
        //LOAD CSV File
        $sql = "TRUNCATE TABLE `_import_data`";
        $result = $this->db->query($sql);
        $sql = "LOAD DATA LOCAL INFILE '".addslashes($file)."' INTO TABLE `_import_data` fields terminated by ',' enclosed by '\"' lines terminated by '\n' IGNORE 1 LINES";
        $query = $this->db->query($sql);

        //Adding Manufacturer Data
        $sql = "SELECT DISTINCT manufacturer FROM `_import_data`";
        $query = $this->db->query($sql);
        $manufacturers = $query->rows;
        if($manufacturers) {
            foreach($manufacturers as $manufacturer) {
                $sql = "SELECT * FROM `manufacturer` WHERE name = '" . $manufacturer['manufacturer']."'";
                $query = $this->db->query($sql);
                $data = $query->row;
                if(!$data) {
                    $sql = "INSERT INTO `manufacturer` (name) VALUES('".$manufacturer['manufacturer']."')";
                    $this->db->query($sql);
                    $manufacturer_id = $this->db->getLastId();

                    $sql = "INSERT INTO `manufacturer_to_store` (manufacturer_id,store_id) VALUES(".$manufacturer_id.",0)";
                    $this->db->query($sql);
                }
            }

            $sql = "UPDATE `_import_data` a, `manufacturer` b SET a.manufacturer_id = b.manufacturer_id WHERE a.manufacturer = b.name";
            $this->db->query($sql);
        }

        //Adding Category Data
        $sql = "SELECT DISTINCT category FROM `_import_data`";
        $query = $this->db->query($sql);
        $categories = $query->rows;
        if($categories) {
            foreach($categories as $category) {
                $sql = "SELECT * FROM `category_description` WHERE name = '" . $category['ctegory'] . "'";
                $query = $this->db->query($sql);
                $data = $query->row;
                if(!$data) {
                    $sql = "INSERT INTO `category` (category_id,date_added) VALUES(NULL,NOW())";
                    $this->db->query($sql);
                    $category_id = $this->db->getLastId();

                    $sql = "INSERT INTO `category_description` (category_id,language_id,name) VALUES(".$category_id.",1,'".$category['category']."')";
                    $this->db->query($sql);

                    $sql = "INSERT INTO `category_to_store` (category_id,store_id) VALUES(".$category_id.",0)";
                    $this->db->query($sql);
                } else {
                    $category_id = $data['category_id'];
                }
            }

            $sql = "UPDATE `_import_data` a, `category_description` b SET a.category_id = b.category_id WHERE a.category = b.name";
            $this->db->query($sql);
        }

        //Adding Product Type Data
        $sql = "SELECT DISTINCT product_type FROM `_import_data`";
        $query = $this->db->query($sql);
        $results = $query->rows;
        if($results) {
            foreach($results as $result) {
                $sql = "SELECT * FROM `product_type` WHERE title = '".$result['product_type']."'";
                $query = $this->db->query($sql);
                $data = $query->row;
                if(!$data) {
                    // Add product Type
                    $sql = "INSERT INTO `product_type` (product_type_id,title) VALUES(NULL,'".$result['product_type']."')";
                    $this->db->query($sql);
                    $product_type_id = $this->db->getLastId();
                } else {
                    $product_type_id = $data['product_type_id'];
                }

                $sql = "UPDATE `_import_data` SET product_type_id = '" . $product_type_id . "' WHERE product_type = '" . $result['product_type'] . "'";
                $this->db->query($sql);

                $sql = "SELECT pto.product_type_id, pto.product_type_option_id, pto.sort_order, ptod.name";
                $sql .= " FROM `product_type_option` pto";
                $sql .= " INNER JOIN `product_type_option_description` ptod ON pto.product_type_option_id = ptod.product_type_option_id";
                $sql .= " WHERE ptod.name='Size' AND pto.product_type_id = '".$product_type_id."'";
                $query = $this->db->query($sql);
                $data = $query->row;
                if(!$data) {
                    //Add Poduct Type Size
                    $sql = "INSERT INTO `product_type_option` (product_type_option_id,product_type_id,sort_order) VALUES(NULL,'".$product_type_id."',1)";
                    $this->db->query($sql);
                    $product_type_option_id = $this->db->getLastId();

                    $sql = "INSERT INTO `product_type_option_description` (product_type_option_id,language_id,product_type_id,name) VALUES(".$product_type_option_id.",1,".$product_type_id.",'Size')";
                    $this->db->query($sql);
                } else {
                    $product_type_option_id = $data['product_type_option_id'];
                }

                $sql = "SELECT DISTINCT inventory_size as size FROM `_import_data` WHERE product_type_id = " . $product_type_id;
                $query = $this->db->query($sql);
                $results = $query->rows;
                if($results) {
                    foreach($results as $result) {
                        $sql = "SELECT ptov.product_type_id, ptov.product_type_option_id, ptov.product_type_option_value_id, ptov.sort_order, ptovd.name";
                        $sql .= " FROM `product_type_option_value` ptov";
                        $sql .= " INNER JOIN `product_type_option_value_description` ptovd ON ptov.product_type_option_value_id = ptovd.product_type_option_value_id";
                        $sql .= " WHERE ptov.product_type_id = ".$product_type_id." AND ptov.product_type_option_id = ".$product_type_option_id." AND ptovd.name = '".$result['size']."'";
                        $query = $this->db->query($sql);
                        $data = $query->row;
                        if(!$data) {
                            $sql = "INSERT INTO `product_type_option_value` (product_type_option_value_id,product_type_option_id,product_type_id)";
                            $sql .= " VALUES(NULL,'".$product_type_option_id."','".$product_type_id."')";
                            $this->db->query($sql);
                            $product_type_option_value_id = $this->db->getLastId();

                            $sql = "INSERT INTO `product_type_option_value_description` (product_type_option_value_id,language_id,product_type_id,name)";
                            $sql .= " VALUES(".$product_type_option_value_id.",1,".$product_type_id.",'".$result['size']."')";
                            $this->db->query($sql);
                        } else {
                            $product_type_option_value_id = $data['product_type_option_value_id'];
                        }

                        $sql = "UPDATE `_import_data` SET product_type_size_id = '" . $product_type_option_id . "', product_type_size_value_id = '" . $product_type_option_value_id . "' WHERE product_type_id = '" . $product_type_id . "' AND inventory_size = '".$result['size']."'";
                        $this->db->query($sql);
                    }
                }

                $sql = "SELECT pto.product_type_id, pto.product_type_option_id, pto.sort_order, ptod.name";
                $sql .= " FROM `product_type_option` pto";
                $sql .= " INNER JOIN `product_type_option_description` ptod ON pto.product_type_option_id = ptod.product_type_option_id";
                $sql .= " WHERE ptod.name='Color' AND pto.product_type_id = '".$product_type_id."'";
                $query = $this->db->query($sql);
                $data = $query->row;
                if(!$data) {
                    //Add Poduct Type Color
                    $sql = "INSERT INTO `product_type_option` (product_type_option_id,product_type_id,sort_order) VALUES(NULL,'".$product_type_id."',2)";
                    $this->db->query($sql);
                    $product_type_option_id = $this->db->getLastId();

                    $sql = "INSERT INTO `product_type_option_description` (product_type_option_id,language_id,product_type_id,name) VALUES(".$product_type_option_id.",1,".$product_type_id.",'Color')";
                    $this->db->query($sql);
                } else {
                    $product_type_option_id = $data['product_type_option_id'];
                }

                $sql = "SELECT DISTINCT inventory_color as color FROM `_import_data` WHERE product_type_id = " . $product_type_id;
                $query = $this->db->query($sql);
                $results = $query->rows;
                if($results) {
                    foreach($results as $result) {
                        $sql = "SELECT ptov.product_type_id, ptov.product_type_option_id, ptov.product_type_option_value_id, ptov.sort_order, ptovd.name";
                        $sql .= " FROM `product_type_option_value` ptov";
                        $sql .= " INNER JOIN `product_type_option_value_description` ptovd ON ptov.product_type_option_value_id = ptovd.product_type_option_value_id";
                        $sql .= " WHERE ptov.product_type_id = ".$product_type_id." AND ptov.product_type_option_id = ".$product_type_option_id." AND ptovd.name = '".$result['color']."'";
                        $query = $this->db->query($sql);
                        $data = $query->row;
                        if(!$data) {
                            $sql = "INSERT INTO `product_type_option_value` (product_type_option_value_id,product_type_option_id,product_type_id)";
                            $sql .= " VALUES(NULL,'".$product_type_option_id."','".$product_type_id."')";
                            $this->db->query($sql);
                            $product_type_option_value_id = $this->db->getLastId();

                            $sql = "INSERT INTO `product_type_option_value_description` (product_type_option_value_id,language_id,product_type_id,name)";
                            $sql .= " VALUES(".$product_type_option_value_id.",1,".$product_type_id.",'".$result['color']."')";
                            $this->db->query($sql);
                        } else {
                            $product_type_option_value_id = $data['product_type_option_value_id'];
                        }

                        $sql = "UPDATE `_import_data` SET product_type_color_id = '" . $product_type_option_id . "', product_type_color_value_id = '" . $product_type_option_value_id . "' WHERE product_type_id = '" . $product_type_id . "' AND inventory_color = '".str_replace(array("\r\n", "\r", "\n"), '', $result['color'])."'";
                        $this->db->query($sql);
                    }
                }

            }
        }


        //Adding Product Data
        $sql = "SELECT DISTINCT manufacturer_id, product_type_id, product_model, product_name FROM `_import_data`";
        $query = $this->db->query($sql);
        $products = $query->rows;
        if($products) {
            foreach($products as $product) {
                //Adding Product
                $product_type_id = $product['product_type_id'];
                $sql = "SELECT p.product_id, p.model, p.manufacturer_id, pd.name, p.product_type_id";
                $sql .= " FROM `product` p";
                $sql .= " INNER JOIN `product_description` pd ON p.product_id = pd.product_id";
                $sql .= " WHERE p.model = '".$product['product_model']."' AND p.manufacturer_id = '".$product['manufacturer_id']."' AND pd.name = '".$product['product_name']."' AND p.product_type_id = '".$product['product_type_id']."'";
                $query = $this->db->query($sql);
                $data = $query->row;
                if(!$data) {
                    $sql = "INSERT INTO `product` (model,quantity,stock_status_id,manufacturer_id,status,date_added,product_type_id)";
                    $sql .= " values ('".$product['product_model']."','100','1','".$product['manufacturer_id']."','1',NOW(),'".$product_type_id."')";
                    $this->db->query($sql);
                    $product_id = $this->db->getLastId();

                    $sql = "UPDATE `_import_data` SET product_id = " . $product_id . " WHERE product_model='" . addslashes($product['product_model']) . "' AND product_name = '" . addslashes($product['product_name']) . "' AND manufacturer_id = '" . $product['manufacturer_id'] . "' AND product_type_id = '" . $product['product_type_id'] . "'";
                    $this->db->query($sql);

                    //Adding product Description
                    $sql = "SELECT product_name, product_description, product_keyword FROM  `_import_data` WHERE product_id = " . $product_id . " LIMIT 1";
                    $query = $this->db->query($sql);
                    $pd = $query->row;
                    if($pd) {
                        $sql = "INSERT INTO `product_description` SET product_id = " . $product_id . ", language_id = 1, name = '" . addslashes($pd['product_name']) . "',";
                        $sql .= " description = '" . addslashes($pd['product_description']). "',";
                        $sql .= " meta_keywords = '" . addslashes($pd['product_keyword']). "'";
                        $this->db->query($sql);
                    } else {
                        d($sql);
                    }

                    //Adding Product to Store
                    $sql = "INSERT INTO `product_to_store` SET product_id = " . $product_id . ", store_id=0";
                    $this->db->query($sql);
                    
                    //Adding Product to category
                    $sql = "INSERT IGNORE INTO `product_to_category` SET product_id = " . $product_id . ", category_id=" . $category_id;
                    $this->db->query($sql);
                } else {
                    $product_id = $data['product_id'];

                    $sql = "UPDATE `_import_data` SET product_id = " . $product_id . " WHERE product_model='" . addslashes($product['product_model']) . "' AND product_name = '" . addslashes($product['product_name']) . "' AND manufacturer_id = '" . $product['manufacturer_id'] . "' AND product_type_id = '" . $product['product_type_id'] . "'";
                    $this->db->query($sql);

                }
            }
        }

        //Adding Product Detail Data
        $sql = "SELECT DISTINCT manufacturer_id, product_type_id, product_id FROM `_import_data`";
        $query = $this->db->query($sql);
        $products = $query->rows;
        if($products) {
            foreach($products as $product) {
                //Adding Product Detail
                $sql = "SELECT product_id, inventory_code, inventory_color_code, inventory_price, product_type_size_id, product_type_size_value_id, product_type_color_id, product_type_color_value_id";
                $sql .= " FROM `_import_data`";
                $sql .= " WHERE manufacturer_id = '" . $product['manufacturer_id'] . "'";
                $sql .= " AND product_type_id = '" . $product['product_type_id'] . "'";
                $sql .= " AND product_id = '" . $product['product_id'] . "'";
                $query = $this->db->query($sql);
                $details = $query->rows;
                if($details) {
                    foreach($details as $detail) {
                        if(strlen($detail['inventory_color_code']) == 1) {
                            $inventory_color_code = str_pad($detail['inventory_color_code'],2,"0",STR_PAD_LEFT);
                        } else {
                            $inventory_color_code = $detail['inventory_color_code'];
                        }
                        $sql = "SELECT * FROM `product_detail` WHERE product_id = '" . $product['product_id'] . "' AND code = '".addslashes($detail['inventory_code'])."'";
                        $query = $this->db->query($sql);
                        $data = $query->row;
                        if(!$data) {
                            $sql = "INSERT INTO `product_detail` SET product_id = ".$product['product_id'].", code = '".addslashes($detail['inventory_code'])."', price = '".$detail['inventory_price']."', color_code = '".$inventory_color_code."', quantity = 100, status=1";
                            $this->db->query($sql);
                            $product_detail_id = $this->db->getLastId();
                        }
                        $sql = "INSERT INTO `product_detail_description` SET product_detail_id = ".$product_detail_id.", product_type_option_id = ".$detail['product_type_size_id'].", product_type_option_value_id = ".$detail['product_type_size_value_id'].", modified_at=NOW()";
                        $this->db->query($sql);

                        $sql = "INSERT INTO `product_detail_description` SET product_detail_id = ".$product_detail_id.", product_type_option_id = ".$detail['product_type_color_id'].", product_type_option_value_id = ".$detail['product_type_color_value_id'].", modified_at=NOW()";
                        $this->db->query($sql);
                    }
                }
            }
        }

        //Adding Color
        $sql = "INSERT IGNORE INTO `color` (hex_code,NAME) SELECT DISTINCT RPAD(inventory_hex_code,6,'0') AS hex_code, inventory_color FROM `_import_data`";
        $this->db->query($sql);
        
    }
}

?>