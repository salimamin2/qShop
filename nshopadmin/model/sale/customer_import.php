<?php
require_once DIR_APPLICATION . 'model/import.php';
class ModelSaleCustomerImport extends ModelImport {
        protected $csv;
        public $records_to_import=-1;
        public $records_contains_error=0;
        function __construct($registry){
            parent::__construct($registry);
        }
        public function import($data)
        {
            $this->db->query("TRUNCATE import_customer;");
            if(parent::import($data)){
                return $this->importToCustomer($this->db,$data);
            }else{
                return false;
            }
        }
        protected function validateImport($database){
            $sql = "SELECT count(*) as cnt FROM import_customer ic
                    LEFT JOIN  customer c on ic.acc_num = c.account_number
                    WHERE ic.email !='' AND c.account_number is null";
            $results = $database->query($sql);
            if($database->getError())
            {
                $this->error[]=$database->getError();
                return false;
            }
            $this->records_to_import = $results->rows[0]['cnt'];
            if(!$results->rows[0]['cnt']){
                $this->error[]="Nothing to import. Records are already sync";
                return false;
            }
            $sql = "SELECT count(*) as cnt FROM import_customer ic
                    WHERE ic.email !=''";
            $results = $database->query($sql);
            $this->records_contains_error = $results->rows[0]['cnt'];
            return true;
        }
        protected function importToCustomer($database, $data)
        {
            if(!$this->validateImport($database)){
                return false;
            }
            $sql=<<<__QUERY
                START TRANSACTION;\n
                /*1. inserting in customer*/
                INSERT INTO customer
                    (user_id,firstname, email, telephone, fax, password,
                        customer_group_id,date_added,account_number,is_sync)
                    SELECT u.user_id,ic.contact_name,ic.email,phone,
                            ic.fax,ic.password,cg.customer_group_id,NOW(),
                            ic.acc_num,ic.is_sync from import_customer ic
                    LEFT JOIN  customer c on ic.acc_num = c.account_number
                    INNER JOIN user u on u.ref_acc_mgr_code = ic.acc_mgr
                    LEFT JOIN customer_group cg ON cg.ref_acc_type_id=ic.acc_type
                    WHERE ic.email !='' AND c.account_number is null;\n

                UPDATE import_customer ic,customer c
			SET ic.customer_id = c.customer_id
                WHERE c.account_number = ic.acc_num and c.is_sync=-1;
                /*2. inserting in customer_attributes */
                INSERT INTO customer_attributes
                    (customer_id,web_flag,tax_id,web_id,account_number,created_at)
                    SELECT ic.customer_id,ic.web_flag,ic.tax_id,ic.web_id,ic.acc_num,now()
                    FROM import_customer ic,customer c
                    WHERE c.customer_id= ic.customer_id and c.is_sync=-1;\n

                /*3. inserting in Customer Address */
                INSERT INTO address
                    (customer_id,company,firstname,address_1,address_2,
                     postcode,city,country_id,zone_id,created_at)
                    SELECT ic.customer_id,ic.company_name,ic.contact_name,
                           ic.address1,ic.address2,ic.zip,ic.city,ct.country_id,
                           z.zone_id,now()
                    FROM import_customer ic
                    INNER JOIN customer c ON c.customer_id= ic.customer_id and c.is_sync=-1
                    LEFT JOIN country ct ON ic.country = ct.iso_code_2
                    LEFT JOIN zone z ON z.country_id = ct.country_id and z.code=ic.state
                    WHERE ic.customer_id is not null AND ct.country_id is not null;\n



                /*5. updateing in customer_attributes */
                UPDATE customer_attributes ca, import_customer ic,customer c SET
                       ca.web_flag = ic.web_flag
                       ,ca.tax_id = ic.tax_id
                       ,ca.account_number = ic.acc_num
                       ,ca.web_id = ic.web_id
                  WHERE
                    c.customer_id = ic.customer_id AND c.is_sync=0 AND
                    ca.customer_id= c.customer_id;\n

                /*6. update address*/
                UPDATE address a,import_customer ic,customer c,country ct,zone z SET
                    company = ic.company_name
                    ,a.firstname = ic.contact_name
                    ,address_1 = ic.address1
                    ,address_2 = ic.address2
                    ,postcode = ic.zip
                    ,a.city = ic.city
                    ,a.country_id = ct.country_id
                    ,a.zone_id= z.zone_id
		WHERE
		    c.customer_id= ic.customer_id and c.is_sync=0
		AND a.customer_id = c.customer_id
		AND ic.country = ct.iso_code_2
		AND z.country_id = ct.country_id
		AND z.code=ic.state;\n

                /*4. updateing in customer*/
                UPDATE customer c, import_customer ic,user u,customer_group cg SET
                        c.user_id = u.user_id
                        ,c.firstname = ic.contact_name
                        ,c.email = ic.email
                        ,c.telephone = ic.phone
                        ,c.fax = ic.fax
                        ,c.password = ic.password
                        ,c.customer_group_id = cg.customer_group_id
                        ,c.account_number = ic.acc_num
                        ,c.is_sync = 1
                  WHERE c.account_number = ic.acc_num AND c.is_sync=0
                  AND   u.ref_acc_mgr_code = ic.acc_mgr
                  AND   cg.ref_acc_type_id = ic.acc_type;\n

                /*Finally all is done and synchronized*/
                UPDATE customer SET is_sync=1
                WHERE is_sync=-1;\n
            
__QUERY;
            $database->query($sql);
            if($database->getError())
            {
               $database->query("ROLLBACK;");
               $this->error[]=$database->getError();
               return false;
            }else{
               $database->query("COMMIT;");
               return true;
            }
        }
       
        public function saveMapping($mappingName,$mapping){
            if(!is_array($mapping))
                return false;
            $path = DIR_CACHE."mapping/customer/";
            
            if(!file_put_contents($path.$mappingName.'.dat',serialize($mapping)))
            {
                $this->log->write("Mapping not saved: $mappingName");
                $this->log->write("Mapping Data: $mapping");
                $this->log->write(get_back_trace());
                return false;
            }
            return true;
        }
        public function getMappingFiles(){
            $path = DIR_CACHE."mapping/customer/";
            $arMapping=array();
            foreach(glob($path."*.dat") as $file)
            {
                $arMapping[]=basename($file, ".dat");
            }
            return $arMapping;
        }
        public function getMapping($mappingName){
            $path = DIR_CACHE."mapping/customer/";
            if(!is_file($path.$mappingName.'.dat')){
                return false;
            }
            $mapping = file_get_contents($path.$mappingName.".dat");
            if($mapping)
            {
                $mapping = unserialize($mapping);
            }
            return $mapping;

        }
	function storeProductsIntoDatabase( &$database, &$products ) 
	{

		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		// start transaction, remove products
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_description` WHERE language_id=$languageId;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_to_category`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_to_store`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."manufacturer_to_store`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query` LIKE 'product_id=%';\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_related`;\n";
		$this->import( $database, $sql );
		
		// store or update manufacturers
		$manufacturerIds = array();
		$ok = $this->storeManufacturersIntoDatabase( $database, $products, $manufacturerIds );
		if (!$ok) {
			$database->query( 'ROLLBACK;' );
			return FALSE;
		}
		
		// get weight classes
		$weightClassIds = $this->getWeightClassIds( $database );
		
		// get length classes
		$lengthClassIds = $this->getLengthClassIds( $database );
		
		// generate and execute SQL for storing the products
		foreach ($products as $product) {
			$productId = $product[0];
			$productName = addslashes($product[1]);
			$categories = $product[2];
			$quantity = $product[3];
			$model = addslashes($product[5]);
			$manufacturerName = $product[6];
			$manufacturerId = ($manufacturerName=="") ? 0 : $manufacturerIds[$manufacturerName];
			$imageName = $product[7];
			$shipping = $product[9];
			$shipping = ((strtoupper($shipping)=="YES") || (strtoupper($shipping)=="Y")) ? 1 : 0;
			$price = trim($product[10]);
			$dateAdded = $product[12];
			$dateModified = $product[13];
			$dateAvailable = $product[14];
			$weight = ($product[15]=="") ? 0 : $product[15];
			$unit = $product[16];
			$weightClassId = (isset($weightClassIds[$unit])) ? $weightClassIds[$unit] : 0;
			$status = $product[17];
			$status = ((strtoupper($status)=="TRUE") || (strtoupper($status)=="YES") || (strtoupper($status)=="ENABLED")) ? 1 : 0;
			$taxClassId = $product[20];
			$viewed = $product[21];
			$productDescription = addslashes($product[23]);
			$stockStatusId = $product[24];
			$meta = $product[25];
			$length = $product[26];
			$width = $product[27];
			$height = $product[28];
			$keyword = $product[29];
			$lengthUnit = $product[30];
			$lengthClassId = (isset($lengthClassIds[$lengthUnit])) ? $lengthClassIds[$lengthUnit] : 0;
			$sku = $product[31];
			$location = $product[32];
			$storeIds = $product[33];
			$related = $product[34];
			$sql  = "INSERT INTO `".DB_PREFIX."product` (`product_id`,`quantity`,`sku`,`location`,";
			$sql .= "`stock_status_id`,`model`,`manufacturer_id`,`image`,`shipping`,`price`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";
			$sql .= "`tax_class_id`,`viewed`,`length`,`width`,`height`,`length_class_id`) VALUES ";
			$sql .= "($productId,$quantity,'$sku','$location',";
			$sql .= "$stockStatusId,'$model',$manufacturerId,'$imageName',$shipping,$price,";
			$sql .= ($dateAdded=='NOW()') ? "$dateAdded," : "'$dateAdded',";
			$sql .= ($dateModified=='NOW()') ? "$dateModified," : "'$dateModified',";
			$sql .= ($dateAvailable=='NOW()') ? "$dateAvailable," : "'$dateAvailable',";
			$sql .= "$weight,$weightClassId,$status,";
			$sql .= "$taxClassId,$viewed,$length,$width,$height,'$lengthClassId');";
			$sql2 = "INSERT INTO `".DB_PREFIX."product_description` (`product_id`,`language_id`,`name`,`description`,`meta_description`) VALUES ";
			$sql2 .= "($productId,$languageId,'$productName','$productDescription','$meta');";
			$database->query($sql);
			$database->query($sql2);
			if (count($categories) > 0) {
				$sql = "INSERT INTO `".DB_PREFIX."product_to_category` (`product_id`,`category_id`) VALUES ";
				$first = TRUE;
				foreach ($categories as $categoryId) {
					$sql .= ($first) ? "\n" : ",\n";
					$first = FALSE;
					$sql .= "($productId,$categoryId)";
				}
				$sql .= ";";
				$database->query($sql);
			}
			if ($keyword) {
				$sql4 = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('product_id=$productId','$keyword');";
				$database->query($sql4);
			}
			foreach ($storeIds as $storeId) {
				$sql6 = "INSERT INTO `".DB_PREFIX."product_to_store` (`product_id`,`store_id`) VALUES ($productId,$storeId);";
				$database->query($sql6);
			}
			if (count($related) > 0) {
				$sql = "INSERT INTO `".DB_PREFIX."product_related` (`product_id`,`related_id`) VALUES ";
				$first = TRUE;
				foreach ($related as $relatedId) {
					$sql .= ($first) ? "\n" : ",\n";
					$first = FALSE;
					$sql .= "($productId,$relatedId)";
				}
				$sql .= ";";
				$database->query($sql);
			}
		}
		
		// final commit
		$database->query("COMMIT;");
		return TRUE;
	}


	


	function uploadProducts( &$reader, &$database ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		$data = $reader->sheets[1];
		$products = array();
		$product = array();
		$isFirstRow = TRUE;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$productId = trim(isset($row[1]) ? $row[1] : "");
			if ($productId=="") {
				continue;
			}
			$name = isset($row[2]) ? $row[2] : "";
			$name = htmlentities( $name, ENT_QUOTES, $this->detect_encoding($name) );
			$categories = isset($row[3]) ? $row[3] : "";
			$sku = isset($row[4]) ? $row[4] : "0";
			$location = isset($row[5]) ? $row[5] : "0";
			$quantity = isset($row[6]) ? $row[6] : "0";
			$model = isset($row[7]) ? $row[7] : "";
			$manufacturer = isset($row[8]) ? $row[8] : "";
			$imageName = isset($row[9]) ? $row[9] : "";
			$shipping = isset($row[10]) ? $row[10] : "yes";
			$price = isset($row[11]) ? $row[11] : "0.00";
			$dateAdded = (isset($row[12]) && (is_string($row[12])) && (strlen($row[12])>0)) ? $row[12] : "NOW()";
			$dateModified = (isset($row[13]) && (is_string($row[13])) && (strlen($row[13])>0)) ? $row[13] : "NOW()";
			$dateAvailable = (isset($row[14]) && (is_string($row[14])) && (strlen($row[14])>0)) ? $row[14] : "NOW()";
			$weight = isset($row[15]) ? $row[15] : "";
			$unit = isset($row[16]) ? $row[16] : "";
			$length = isset($row[17]) ? $row[17] : "";
			$width = isset($row[18]) ? $row[18] : "";
			$height = isset($row[19]) ? $row[19] : "";
			$measurementUnit = isset($row[20]) ? $row[20] : "";
			$status = isset($row[21]) ? $row[21] : "false";
			$taxClassId = isset($row[22]) ? $row[22] : "0";
			$viewed = isset($row[23]) ? $row[23] : "0";
			$langId = isset($row[24]) ? $row[24] : "1";
			if ($langId!=$languageId) {
				continue;
			}
			$keyword = isset($row[25]) ? $row[25] : "";
			$description = isset($row[26]) ? $row[26] : "";
			$description = htmlentities( $description, ENT_QUOTES, $this->detect_encoding($description) );
			$meta = isset($row[27]) ? $row[27] : "";
			$meta = htmlentities( $meta, ENT_QUOTES, $this->detect_encoding($meta) );
			$additionalImageNames = isset($row[28]) ? $row[28] : "";
			$stockStatusId = isset($row[29]) ? $row[29] : "";
			$storeIds = isset($row[30]) ? $row[30] : "";
			$related = isset($row[31]) ? $row[31] : "";
			$product = array();
			$product[0] = $productId;
			$product[1] = $name;
			$categories = trim( $this->clean($categories, FALSE) );
			$product[2] = ($categories=="") ? array() : explode( ",", $categories );
			if ($product[2]===FALSE) {
				$product[2] = array();
			}
			$product[3] = $quantity;
			$product[5] = $model;
			$product[6] = $manufacturer;
			$product[7] = $imageName;
			$product[9] = $shipping;
			$product[10] = $price;
			$product[12] = $dateAdded;
			$product[13] = $dateModified;
			$product[14] = $dateAvailable;
			$product[15] = $weight;
			$product[16] = $unit;
			$product[17] = $status;
			$product[20] = $taxClassId;
			$product[21] = $viewed;
			$product[22] = $languageId;
			$product[23] = $description;
			$product[24] = $stockStatusId;
			$product[25] = $meta;
			$product[26] = $length;
			$product[27] = $width;
			$product[28] = $height;
			$product[29] = $keyword;
			$product[30] = $measurementUnit;
			$product[31] = $sku;
			$product[32] = $location;
			$storeIds = trim( $this->clean($storeIds, FALSE) );
			$product[33] = ($storeIds=="") ? array() : explode( ",", $storeIds );
			if ($product[33]===FALSE) {
				$product[33] = array();
			}
			$product[34] = ($related=="") ? array() : explode( ",", $related );
			if ($product[34]===FALSE) {
				$product[34] = array();
			}
			$products[$productId] = $product;
		}
		return $this->storeProductsIntoDatabase( $database, $products );
	}


	function storeCategoriesIntoDatabase( &$database, &$categories ) 
	{
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);

		// start transaction, remove categories
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."category`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."category_description` WHERE language_id=$languageId;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."category_to_store`;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."url_alias` WHERE `query` LIKE 'category_id=%';\n";
		$this->import( $database, $sql );
		
		// generate and execute SQL for inserting the categories
		foreach ($categories as $category) {
			$categoryId = $category[0];
			$imageName = $category[1];
			$parentId = $category[2];
			$sortOrder = $category[3];
			$dateAdded = $category[4];
			$dateModified = $category[5];
			$meta = $category[9];
			$keyword = $category[10];
			$storeIds = $category[11];
			$sql2 = "INSERT INTO `".DB_PREFIX."category` (`category_id`, `image`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ";
			$sql2 .= "( $categoryId, '$imageName', $parentId, $sortOrder, ";
			$sql2 .= ($dateAdded=='NOW()') ? "$dateAdded," : "'$dateAdded',";
			$sql2 .= ($dateModified=='NOW()') ? "$dateModified" : "'$dateModified'";
			$sql2 .= " );";
			$database->query( $sql2 );
			$sql3 = "INSERT INTO `".DB_PREFIX."category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`) VALUES ";
			$languageId = $category[6];
			$name = addslashes($category[7]);
			$description = addslashes($category[8]);
			$sql3 .= "( $categoryId, $languageId, '$name', '$description', '$meta' );";
			$database->query( $sql3 );
			if ($keyword) {
				$sql5 = "INSERT INTO `".DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('category_id=$categoryId','$keyword');";
				$database->query($sql5);
			}
			foreach ($storeIds as $storeId) {
				$sql6 = "INSERT INTO `".DB_PREFIX."category_to_store` (`category_id`,`store_id`) VALUES ($categoryId,$storeId);";
				$database->query($sql6);
			}
		}
		
		// final commit
		$database->query( "COMMIT;" );
		return TRUE;
	}


	function uploadCategories( &$reader, &$database ) 
	{
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);
		
		$data = $reader->sheets[0];
		$categories = array();
		$isFirstRow = TRUE;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$categoryId = trim(isset($row[1]) ? $row[1] : "");
			if ($categoryId=="") {
				continue;
			}
			$parentId = isset($row[2]) ? $row[2] : "0";
			$name = isset($row[3]) ? $row[3] : "";
			$name = htmlentities( $name, ENT_QUOTES, $this->detect_encoding($name) );
			$sortOrder = isset($row[4]) ? $row[4] : "0";
			$imageName = trim(isset($row[5]) ? $row[5] : "");
			$dateAdded = (isset($row[6]) && (is_string($row[6])) && (strlen($row[6])>0)) ? $row[6] : "NOW()";
			$dateModified = (isset($row[7]) && (is_string($row[7])) && (strlen($row[7])>0)) ? $row[7] : "NOW()";
			$langId = isset($row[8]) ? $row[8] : "1";
			if ($langId != $languageId) {
				continue;
			}
			$keyword = isset($row[9]) ? $row[9] : "";
			$description = isset($row[10]) ? $row[10] : "";
			$description = htmlentities( $description, ENT_QUOTES, $this->detect_encoding($description) );
			$meta = isset($row[11]) ? $row[11] : "";
			$meta = htmlentities( $meta, ENT_QUOTES, $this->detect_encoding($meta) );
			$storeIds = isset($row[12]) ? $row[12] : "";
			$category = array();
			$category[0] = $categoryId;
			$category[1] = $imageName;
			$category[2] = $parentId;
			$category[3] = $sortOrder;
			$category[4] = $dateAdded;
			$category[5] = $dateModified;
			$category[6] = $languageId;
			$category[7] = $name;
			$category[8] = $description;
			$category[9] = $meta;
			$category[10] = $keyword;
			$storeIds = trim( $this->clean($storeIds, FALSE) );
			$category[11] = ($storeIds=="") ? array() : explode( ",", $storeIds );
			if ($category[11]===FALSE) {
				$category[11] = array();
			}
			$categories[$categoryId] = $category;
		}
		return $this->storeCategoriesIntoDatabase( $database, $categories );
	}



	function storeDiscountsIntoDatabase( &$database, &$discounts )
	{
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `".DB_PREFIX."product_discount`;\n";
		$this->import( $database, $sql );

		// find existing customer groups from the database
		$sql = "SELECT * FROM `".DB_PREFIX."customer_group`";
		$result = $database->query( $sql );
		$maxCustomerGroupId = 0;
		$customerGroups = array();
		foreach ($result->rows as $row) {
			$customerGroupId = $row['customer_group_id'];
			$name = $row['name'];
			if (!isset($customerGroups[$name])) {
				$customerGroups[$name] = $customerGroupId;
			}
			if ($maxCustomerGroupId < $customerGroupId) {
				$maxCustomerGroupId = $customerGroupId;
			}
		}

		// add additional customer groups into the database
		foreach ($discounts as $discount) {
			$name = $discount['customer_group'];
			if (!isset($customerGroups[$name])) {
				$maxCustomerGroupId += 1;
				$sql  = "INSERT INTO `".DB_PREFIX."customer_group` (`customer_group_id`, `name`) VALUES "; 
				$sql .= "($maxCustomerGroupId, '$name')";
				$sql .= ";\n";
				$database->query($sql);
				$customerGroups[$name] = $maxCustomerGroupId;
			}
		}

		// store product discounts into the database
		$productDiscountId = 0;
		$first = TRUE;
		$sql = "INSERT INTO `".DB_PREFIX."product_discount` (`product_discount_id`,`product_id`,`customer_group_id`,`quantity`,`priority`,`price`,`date_start`,`date_end` ) VALUES "; 
		foreach ($discounts as $discount) {
			$productDiscountId += 1;
			$productId = $discount['product_id'];
			$name = $discount['customer_group'];
			$customerGroupId = $customerGroups[$name];
			$quantity = $discount['quantity'];
			$priority = $discount['priority'];
			$price = $discount['price'];
			$dateStart = $discount['date_start'];
			$dateEnd = $discount['date_end'];
			$sql .= ($first) ? "\n" : ",\n";
			$first = FALSE;
			$sql .= "($productDiscountId,$productId,$customerGroupId,$quantity,$priority,$price,'$dateStart','$dateEnd')";
		}
		if (!$first) {
			$database->query($sql);
		}

		$database->query("COMMIT;");
		return TRUE;
	}


	function uploadDiscounts( &$reader, &$database ) 
	{
		$data = $reader->sheets[4];
		$discounts = array();
		$i = 0;
		$isFirstRow = TRUE;
		foreach ($data['cells'] as $row) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$productId = trim(isset($row[1]) ? $row[1] : "");
			if ($productId=="") {
				continue;
			}
			$customerGroup = trim(isset($row[2]) ? $row[2] : "");
			if ($customerGroup=="") {
				continue;
			}
			$quantity = isset($row[3]) ? $row[3] : "0";
			$priority = isset($row[4]) ? $row[4] : "0";
			$price = isset($row[5]) ? $row[5] : "0";
			$dateStart = isset($row[6]) ? $row[6] : "0000-00-00";
			$dateEnd = isset($row[7]) ? $row[7] : "0000-00-00";
			$discounts[$i] = array();
			$discounts[$i]['product_id'] = $productId;
			$discounts[$i]['customer_group'] = $customerGroup;
			$discounts[$i]['quantity'] = $quantity;
			$discounts[$i]['priority'] = $priority;
			$discounts[$i]['price'] = $price;
			$discounts[$i]['date_start'] = $dateStart;
			$discounts[$i]['date_end'] = $dateEnd;
			$i += 1;
		}
		return $this->storeDiscountsIntoDatabase( $database, $discounts );
	}



	function clearCache() {
            $this->cache->delete('customers');
	}


	function upload( $filename ) {
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
		set_error_handler('errorHandlerForExport',E_ALL);
		$database =& $this->db;
		require_once 'Spreadsheet/Excel/Reader.php';
		ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		//set_time_limit( 60 );
		$reader=new Spreadsheet_Excel_Reader();
		$reader->setUTFEncoder('iconv');
		$reader->setOutputEncoding('UTF-8');
		$reader->read($filename);
		$ok = $this->validateUpload( $reader );
		if (!$ok) {
			return FALSE;
		}
		$this->clearCache();
		$ok = $this->uploadImages( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		$ok = $this->uploadCategories( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		$ok = $this->uploadProducts( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		$ok = $this->uploadOptions( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		$ok = $this->uploadSpecials( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		$ok = $this->uploadDiscounts( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		return $ok;
	}



	function getStoreIdsForCategories( &$database ) {
		$sql =  "SELECT category_id, store_id FROM `".DB_PREFIX."category_to_store` cs;";
		$storeIds = array();
		$result = $database->query( $sql );
		foreach ($result->rows as $row) {
			$categoryId = $row['category_id'];
			$storeId = $row['store_id'];
			if (!isset($storeIds[$categoryId])) {
				$storeIds[$categoryId] = array();
				if (!in_array($storeId,$storeIds[$categoryId])) {
					$storeIds[$categoryId][] = $storeId;
				}
			}
		}
		return $storeIds;
	}


	function populateCategoriesWorksheet( &$worksheet, &$database, $languageId, &$boxFormat, &$textFormat )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('category_id')+1);
		$worksheet->setColumn($j,$j++,strlen('parent_id')+1);
		$worksheet->setColumn($j,$j++,max(strlen('name'),32)+1);
		$worksheet->setColumn($j,$j++,strlen('sort_order')+1);
		$worksheet->setColumn($j,$j++,max(strlen('image_name'),12)+1);
		$worksheet->setColumn($j,$j++,max(strlen('date_added'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_modified'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('seo_keyword'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('description'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('meta'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('store_ids'),16)+1);
		
		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'category_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'parent_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat );
		$worksheet->writeString( $i, $j++, 'image_name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_added', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_modified', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_keyword', $boxFormat );
		$worksheet->writeString( $i, $j++, 'description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta', $boxFormat );
		$worksheet->writeString( $i, $j++, 'store_ids', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual categories data
		$i += 1;
		$j = 0;
		$storeIds = $this->getStoreIdsForCategories( $database );
		$query  = "SELECT c.* , cd.*, ua.keyword FROM `".DB_PREFIX."category` c ";
		$query .= "INNER JOIN `".DB_PREFIX."category_description` cd ON cd.category_id = c.category_id ";
		$query .= " AND cd.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."url_alias` ua ON ua.query=CONCAT('category_id=',c.category_id) ";
		$query .= "ORDER BY c.`parent_id`, `sort_order`, c.`category_id`;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->write( $i, $j++, $row['category_id'] );
			$worksheet->write( $i, $j++, $row['parent_id'] );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['name'],ENT_QUOTES,'UTF-8') );
			$worksheet->write( $i, $j++, $row['sort_order'] );
			$worksheet->write( $i, $j++, $row['image'] );
			$worksheet->write( $i, $j++, $row['date_added'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_modified'], $textFormat );
			$worksheet->write( $i, $j++, $row['language_id'] );
			$worksheet->writeString( $i, $j++, ($row['keyword']) ? $row['keyword'] : '' );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['description'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['meta_description'],ENT_QUOTES,'UTF-8') );
			$storeIdList = '';
			$categoryId = $row['category_id'];
			if (isset($storeIds[$categoryId])) {
				foreach ($storeIds[$categoryId] as $storeId) {
					$storeIdList .= ($storeIdList=='') ? $storeId : ','.$storeId;
				}
			}
			$worksheet->write( $i, $j++, $storeIdList, $textFormat );
			$i += 1;
			$j = 0;
		}
	}


	function getStoreIdsForProducts( &$database ) {
		$sql =  "SELECT product_id, store_id FROM `".DB_PREFIX."product_to_store` ps;";
		$storeIds = array();
		$result = $database->query( $sql );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$storeId = $row['store_id'];
			if (!isset($storeIds[$productId])) {
				$storeIds[$productId] = array();
				if (!in_array($storeId,$storeIds[$productId])) {
					$storeIds[$productId][] = $storeId;
				}
			}
		}
		return $storeIds;
	}


	function populateProductsWorksheet( &$worksheet, &$database, &$imageNames, $languageId, &$priceFormat, &$boxFormat, &$weightFormat, &$textFormat )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('name'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('categories'),12)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('sku'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('location'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('quantity'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('model'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('manufacturer'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('image_name'),12)+1);;
		$worksheet->setColumn($j,$j++,max(strlen('shipping'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_added'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_modified'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_available'),10)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('weight'),6)+1,$weightFormat);
		$worksheet->setColumn($j,$j++,max(strlen('unit'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('length'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('width'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('height'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('length'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('status'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('tax_class_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('viewed'),5)+1);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('seo_keyword'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('description'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('meta'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('additional image names'),24)+1);
		$worksheet->setColumn($j,$j++,max(strlen('stock_status_id'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('store_ids'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('related_ids'),16)+1,$textFormat);

		// The product headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'categories', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sku', $boxFormat );
		$worksheet->writeString( $i, $j++, 'location', $boxFormat );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat );
		$worksheet->writeString( $i, $j++, 'model', $boxFormat );
		$worksheet->writeString( $i, $j++, 'manufacturer', $boxFormat );
		$worksheet->writeString( $i, $j++, 'image_name', $boxFormat );
		$worksheet->writeString( $i, $j++, "requires\nshipping", $boxFormat );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_added', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_modified', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_available', $boxFormat );
		$worksheet->writeString( $i, $j++, 'weight', $boxFormat );
		$worksheet->writeString( $i, $j++, 'unit', $boxFormat );
		$worksheet->writeString( $i, $j++, 'length', $boxFormat );
		$worksheet->writeString( $i, $j++, 'width', $boxFormat );
		$worksheet->writeString( $i, $j++, 'height', $boxFormat );
		$worksheet->writeString( $i, $j++, "length\nunit", $boxFormat );
		$worksheet->writeString( $i, $j++, "status\nenabled", $boxFormat );
		$worksheet->writeString( $i, $j++, 'tax_class_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'viewed', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_keyword', $boxFormat );
		$worksheet->writeString( $i, $j++, 'description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta', $boxFormat );
		$worksheet->writeString( $i, $j++, 'additional image names', $boxFormat );
		$worksheet->writeString( $i, $j++, 'stock_status_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'store_ids', $boxFormat );
		$worksheet->writeString( $i, $j++, 'related_ids', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual products data
		$i += 1;
		$j = 0;
		$storeIds = $this->getStoreIdsForProducts( $database );
		$query  = "SELECT ";
		$query .= "  p.product_id,";
		$query .= "  pd.name,";
		$query .= "  GROUP_CONCAT( DISTINCT CAST(pc.category_id AS CHAR(11)) SEPARATOR \",\" ) AS categories,";
		$query .= "  p.sku,";
		$query .= "  p.location,";
		$query .= "  p.quantity,";
		$query .= "  p.model,";
		$query .= "  m.name AS manufacturer,";
		$query .= "  p.image AS image_name,";
		$query .= "  p.shipping,";
		$query .= "  p.price,";
		$query .= "  p.date_added,";
		$query .= "  p.date_modified,";
		$query .= "  p.date_available,";
		$query .= "  p.weight,";
		$query .= "  wc.unit,";
		$query .= "  p.length,";
		$query .= "  p.width,";
		$query .= "  p.height,";
		$query .= "  p.status,";
		$query .= "  p.tax_class_id,";
		$query .= "  p.viewed,";
		$query .= "  pd.language_id,";
		$query .= "  ua.keyword,";
		$query .= "  pd.description, ";
		$query .= "  pd.meta_description, ";
		$query .= "  p.stock_status_id, ";
		$query .= "  mc.unit AS length_unit, ";
		$query .= "  GROUP_CONCAT( DISTINCT CAST(pr.related_id AS CHAR(11)) SEPARATOR \",\" ) AS related ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "LEFT JOIN `".DB_PREFIX."product_description` pd ON p.product_id=pd.product_id ";
		$query .= "  AND pd.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."product_to_category` pc ON p.product_id=pc.product_id ";
		$query .= "LEFT JOIN `".DB_PREFIX."url_alias` ua ON ua.query=CONCAT('product_id=',p.product_id) ";
		$query .= "LEFT JOIN `".DB_PREFIX."manufacturer` m ON m.manufacturer_id = p.manufacturer_id ";
		$query .= "LEFT JOIN `".DB_PREFIX."weight_class_description` wc ON wc.weight_class_id = p.weight_class_id ";
		$query .= "  AND wc.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."length_class_description` mc ON mc.length_class_id=p.length_class_id ";
		$query .= "  AND mc.language_id=$languageId ";
		$query .= "LEFT JOIN `".DB_PREFIX."product_related` pr ON pr.product_id=p.product_id ";
		$query .= "GROUP BY p.product_id ";
		$query .= "ORDER BY p.product_id, pc.category_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$worksheet->write( $i, $j++, $productId );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['name'],ENT_QUOTES,'UTF-8') );
			$worksheet->write( $i, $j++, $row['categories'], $textFormat );
			$worksheet->writeString( $i, $j++, $row['sku'] );
			$worksheet->writeString( $i, $j++, $row['location'] );
			$worksheet->write( $i, $j++, $row['quantity'] );
			$worksheet->writeString( $i, $j++, $row['model'] );
			$worksheet->writeString( $i, $j++, $row['manufacturer'] );
			$worksheet->writeString( $i, $j++, $row['image_name'] );
			$worksheet->write( $i, $j++, ($row['shipping']==0) ? "no" : "yes", $textFormat );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->write( $i, $j++, $row['date_added'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_modified'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_available'], $textFormat );
			$worksheet->write( $i, $j++, $row['weight'], $weightFormat );
			$worksheet->writeString( $i, $j++, $row['unit'] );
			$worksheet->write( $i, $j++, $row['length'] );
			$worksheet->write( $i, $j++, $row['width'] );
			$worksheet->write( $i, $j++, $row['height'] );
			$worksheet->writeString( $i, $j++, $row['length_unit'] );
			$worksheet->write( $i, $j++, ($row['status']==0) ? "false" : "true", $textFormat );
			$worksheet->write( $i, $j++, $row['tax_class_id'] );
			$worksheet->write( $i, $j++, $row['viewed'] );
			$worksheet->write( $i, $j++, $row['language_id'] );
			$worksheet->writeString( $i, $j++, ($row['keyword']) ? $row['keyword'] : '' );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['description'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['meta_description'],ENT_QUOTES,'UTF-8') );
			$names = "";
			if (isset($imageNames[$productId])) {
				$first = TRUE;
				foreach ($imageNames[$productId] AS $name) {
					if (!$first) {
						$names .= ",\n";
					}
					$first = FALSE;
					$names .= $name;
				}
			}
			$worksheet->writeString( $i, $j++, $names );
			$worksheet->write( $i, $j++, $row['stock_status_id'] );
			$storeIdList = '';
			if (isset($storeIds[$productId])) {
				foreach ($storeIds[$productId] as $storeId) {
					$storeIdList .= ($storeIdList=='') ? $storeId : ','.$storeId;
				}
			}
			$worksheet->write( $i, $j++, $storeIdList, $textFormat );
			$worksheet->write( $i, $j++, $row['related'], $textFormat );
			$i += 1;
			$j = 0;
		}
	}


	function populateOptionsWorksheet( &$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, $textFormat )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('option'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('option_value'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('quantity'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('subtract'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('prefix'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('sort_order'),5)+1);
		
		// The options headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'option', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'option_value', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'subtract', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'prefix', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat  );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual options data
		$i += 1;
		$j = 0;
		$query  = "SELECT DISTINCT p.product_id, ";
		$query .= "  pod.name AS option_name, ";
		$query .= "  po.sort_order AS option_sort_order, ";
		$query .= "  povd.name AS option_value, ";
		$query .= "  pov.quantity AS option_quantity, ";
		$query .= "  pov.subtract AS option_subtract, ";
		$query .= "  pov.price AS option_price, ";
		$query .= "  pov.prefix AS option_prefix, ";
		$query .= "  pov.sort_order AS sort_order ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "INNER JOIN `".DB_PREFIX."product_description` pd ON p.product_id=pd.product_id ";
		$query .= "  AND pd.language_id=$languageId ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option` po ON po.product_id=p.product_id ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_description` pod ON pod.product_option_id=po.product_option_id ";
		$query .= "  AND pod.product_id=po.product_id ";
		$query .= "  AND pod.language_id=$languageId ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_value` pov ON pov.product_option_id=po.product_option_id ";
		$query .= "INNER JOIN `".DB_PREFIX."product_option_value_description` povd ON povd.product_option_value_id=pov.product_option_value_id ";
		$query .= "  AND povd.language_id=$languageId ";
		$query .= "ORDER BY product_id, option_sort_order, sort_order;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->write( $i, $j++, $languageId );
			$worksheet->writeString( $i, $j++, $row['option_name'] );
			$worksheet->writeString( $i, $j++, $row['option_value'] );
			$worksheet->write( $i, $j++, $row['option_quantity'] );
			$worksheet->write( $i, $j++, ($row['option_subtract']==0) ? "false" : "true", $textFormat );
			$worksheet->write( $i, $j++, $row['option_price'], $priceFormat );
			$worksheet->writeString( $i, $j++, $row['option_prefix'], $textFormat );
			$worksheet->write( $i, $j++, $row['sort_order'] );
			$i += 1;
			$j = 0;
		}
	}


	function populateSpecialsWorksheet( &$worksheet, &$database, &$priceFormat, &$boxFormat, &$textFormat )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('product_id')+1);
		$worksheet->setColumn($j,$j++,strlen('customer_group')+1);
		$worksheet->setColumn($j,$j++,strlen('priority')+1);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_start'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_end'),19)+1,$textFormat);
		
		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'customer_group', $boxFormat );
		$worksheet->writeString( $i, $j++, 'priority', $boxFormat );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_start', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_end', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual product specials data
		$i += 1;
		$j = 0;
		$query  = "SELECT ps.*, cg.name FROM `".DB_PREFIX."product_special` ps ";
		$query .= "LEFT JOIN `".DB_PREFIX."customer_group` cg ON cg.customer_group_id=ps.customer_group_id ";
		$query .= "ORDER BY ps.product_id, cg.name";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->write( $i, $j++, $row['name'] );
			$worksheet->write( $i, $j++, $row['priority'] );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->write( $i, $j++, $row['date_start'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_end'], $textFormat );
			$i += 1;
			$j = 0;
		}
	}


	function populateDiscountsWorksheet( &$worksheet, &$database, &$priceFormat, &$boxFormat, &$textFormat )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('product_id')+1);
		$worksheet->setColumn($j,$j++,strlen('customer_group')+1);
		$worksheet->setColumn($j,$j++,strlen('quantity')+1);
		$worksheet->setColumn($j,$j++,strlen('priority')+1);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_start'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_end'),19)+1,$textFormat);
		
		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'customer_group', $boxFormat );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat );
		$worksheet->writeString( $i, $j++, 'priority', $boxFormat );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_start', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_end', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );
		
		// The actual product discounts data
		$i += 1;
		$j = 0;
		$query  = "SELECT pd.*, cg.name FROM `".DB_PREFIX."product_discount` pd ";
		$query .= "LEFT JOIN `".DB_PREFIX."customer_group` cg ON cg.customer_group_id=pd.customer_group_id ";
		$query .= "ORDER BY pd.product_id, cg.name";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->write( $i, $j++, $row['name'] );
			$worksheet->write( $i, $j++, $row['quantity'] );
			$worksheet->write( $i, $j++, $row['priority'] );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->write( $i, $j++, $row['date_start'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_end'], $textFormat );
			$i += 1;
			$j = 0;
		}
	}

	function download() {
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
		set_error_handler('errorHandlerForExport',E_ALL);
		$database =& $this->db;
		$languageId = $this->getDefaultLanguageId($database);

		// We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
		require_once "Spreadsheet/Excel/Writer.php";
		
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(DIR_CACHE);
		$workbook->setVersion(8); // Use Excel97/2000 Format
		$priceFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
		$boxFormat =& $workbook->addFormat(array('Size' => 10,'vAlign' => 'vequal_space' ));
		$weightFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
		$textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@" ));
		
		// sending HTTP headers
		$workbook->send('backup_categories_products.xls');
		
		// Creating the categories worksheet
		$worksheet =& $workbook->addWorksheet('Categories');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateCategoriesWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Get all additional product images
		$imageNames = array();
		$query  = "SELECT DISTINCT ";
		$query .= "  p.product_id, ";
		$query .= "  pi.product_image_id AS image_id, ";
		$query .= "  pi.image AS filename ";
		$query .= "FROM `".DB_PREFIX."product` p ";
		$query .= "INNER JOIN `".DB_PREFIX."product_image` pi ON pi.product_id=p.product_id ";
		$query .= "ORDER BY product_id, image_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$imageId = $row['image_id'];
			$imageName = $row['filename'];
			if (!isset($imageNames[$productId])) {
				$imageNames[$productId] = array();
				$imageNames[$productId][$imageId] = $imageName;
			}
			else {
				$imageNames[$productId][$imageId] = $imageName;
			}
		}
		
		// Creating the products worksheet
		$worksheet =& $workbook->addWorksheet('Products');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateProductsWorksheet( $worksheet, $database, $imageNames, $languageId, $priceFormat, $boxFormat, $weightFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the options worksheet
		$worksheet =& $workbook->addWorksheet('Options');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateOptionsWorksheet( $worksheet, $database, $languageId, $priceFormat, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the specials worksheet
		$worksheet =& $workbook->addWorksheet('Specials');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateSpecialsWorksheet( $worksheet, $database, $priceFormat, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Creating the discounts worksheet
		$worksheet =& $workbook->addWorksheet('Discounts');
		$worksheet->setInputEncoding ( 'UTF-8' );
		$this->populateDiscountsWorksheet( $worksheet, $database, $priceFormat, $boxFormat, $textFormat );
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Let's send the file
		$workbook->close();
		exit;
	}
        public function detectLineEnding($file)
        {
            $s = file_get_contents($file);
            if( empty($s) ) return null;

            if( substr_count( $s,  "\r\n" ) ) return '\r\n'; //Win
            if( substr_count( $s,  "\r" ) )   return '\r';   //Mac
            return '\n'; //Unix
        }

}
?>