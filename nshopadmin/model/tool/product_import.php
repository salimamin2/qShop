<?php
require_once DIR_APPLICATION . 'model/import.php';
class ModelToolProductImport extends ModelImport {
        protected $csv;
        public $records_to_import=-1;
        public $records_contains_error=0;
        protected $mapping;
        function __construct($registry){
            parent::__construct($registry);
        }
        public function import($data)
        {
            
            $this->db->query("TRUNCATE ".$data['table_name'].";\n");
            if(parent::import($data)){
                if($data['table_name'] != 'import_product_desc'){
                    return $this->importProduct($this->db,$data);
                }else{
                    return $this->importProductDesc($this->db,$data);
                }
            }else{
                return false;
            }
        }
        protected function validateImport($database){
            $sql = "SELECT count(*) as cnt FROM import_product ip
                    LEFT JOIN  product p on ip.ItemNum = p.product_id
                    WHERE p.product_id is null";
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
            $sql = "SELECT count(*) as cnt FROM import_product ip
                        WHERE ItemNum is null";
            $results = $database->query($sql);
            $this->records_contains_error = $results->rows[0]['cnt'];
            return true;
        }
        protected function importProduct($database, $data)
        {
            if(!$this->validateImport($database)){
                return false;
            }
            //Step 1: Insert or Update on Product Table

            $sql=<<<SQL
                START TRANSACTION;\n
                /*create insert view*/

               DROP TEMPORARY TABLE IF EXISTS vw_add_product;\n

                CREATE TEMPORARY TABLE vw_add_product as
                 SELECT ip.*
                    FROM import_product ip
                    LEFT JOIN product p on ip.ItemNum = p.product_id
                    WHERE p.product_id is null;\n

                /*create update view*/
                DROP TEMPORARY TABLE IF EXISTS vw_update_product;\n

                CREATE TEMPORARY TABLE vw_update_product as
                 SELECT ip.*
                    FROM import_product ip
                    INNER JOIN product p on ip.ItemNum = p.product_id;\n

                /*1. inserting in product*/
               INSERT INTO product
                    (product_id,model,sku,location,stock_status_id,image,
                        shipping,price,weight,length,width,height,status,date_added,
                        date_modified,uom,case_qty,ea_per_um,min_order_qty,max_order_qty,
                        is_hazmat,is_clearance)
                     SELECT
                        ItemNum,
                        ItemGroup,
                        Barcode,
                        Country,
                        IF(IsAvailable='J',1,2),
                        CONCAT(ItemNum,".jpg") as image,
                        1,
                        SRP,
                        Weight,
                        Depth,
                        Width,
                        Height,
                        IF(Active='J',1,0),
                        DateAdded,
                        DateModified,
                        UM,
                        CaseQty,
                        EaPerUM,
                        MinOrdQty,
                       	QtyLimitSell,
                        IF(Hazmat='J',1,0),
                        IF(IsSurplus='J',1,0)

                    FROM vw_add_product;\n


                /*4. updateing in product*/
                UPDATE product p, vw_update_product ip SET
                        model=ItemGroup,
                        sku=Barcode,
                        location=Country,
                        stock_status_id=IF(IsAvailable='J',1,2),
                        p.image = CONCAT(ItemNum,".jpg"),
                        price = SRP,
                        p.weight = ip.Weight,
                        p.length = Depth,
                        p.width = ip.Width,
                        p.height = ip.Height,
                        p.status = IF(Active='J',1,0),
                        p.date_added= DateAdded,
                        p.date_modified = DateModified,
                        uom = UM,
                        case_qty = CaseQty,
                        ea_per_um = EaPerUM,
                        min_order_qty = MinOrdQty,
                       	max_order_qty = QtyLimitSell,
                        is_hazmat = IF(Hazmat='J',1,0),
                        is_clearance = IF(IsSurplus='J',1,0)
                  WHERE p.product_id = ip.ItemNum;\n

                /*5. inserting in product_description*/
                INSERT INTO product_description
                    (product_id,language_id,name)
                     SELECT
                        ItemNum,
                        '1',
                        Description
                    FROM vw_add_product;\n

                /*6. updateing in product_description*/
                UPDATE product_description pd, vw_update_product ip SET
                        pd.name = ip.Description
                  WHERE pd.product_id = ip.ItemNum;\n
               
SQL;
            //Step 2: Add Product Discount
            $sql .= $this->importProductDiscount($database,$sql);
            //Step 3: Adjust Product Category
            //add all category for new products
            $sql .="INSERT INTO product_to_category (product_id,category_id)
                        SELECT  p.product_id,c.category_id
                        FROM product p,vw_update_product ip, category c
                        WHERE p.product_id=ip.ItemNum AND c.ref_category_code = ip.ItemGroup;\n";

            //delete all category entry related to updated product
            $sql .= "DELETE pc from product_to_category pc,(SELECT DISTINCT p.product_id,p.model from product p, category c,import_product ip
                    WHERE p.product_id = ip.ItemNum and ip.ItemGroup = c.ref_category_code order by product_id) a
                    WHERE pc.product_id = a.product_id;\n";

            //Step 4: Add Product description

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

        protected function importProductDesc($database, $data)
        {
            //Step 1:Update on Product Description Table

            $sql=<<<SQL
                START TRANSACTION;\n
                /*create update view*/
                DROP TEMPORARY TABLE IF EXISTS vw_update_product_desc;\n

                CREATE TEMPORARY TABLE vw_update_product_desc as
                 SELECT ipd.*
                    FROM import_product_desc ipd
                    INNER JOIN product_description pd on ipd.ItemNum = pd.product_id;\n

                /*1. updateing in product_description*/
                UPDATE product_description pd, vw_update_product_desc ipd SET
                        pd.description=ipd.LongText
                  WHERE pd.product_id = ipd.ItemNum;\n

SQL;
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

        private function importProductDiscount($database,$sql){
            //Reset product discount entries which contain current product to import
            $sql .= "DELETE pd from product_discount pd, import_product ip
                            WHERE ip.ItemNum = pd.product_id;\n";

            //Customer Group
            $this->load->model('sale/customer_group');
            $groups = $this->model_sale_customer_group->getCustomerGroups();

            foreach($groups as $group){
                $columns = $database->driver->getColumns('import_product',$group['ref_acc_type_code']);
                if(!$columns)
                    continue;

                $tsql = "SELECT ItemNum,".implode(',',$columns)."
                    FROM  import_product ip,product p
                    WHERE ip.ItemNum = p.product_id";

                $result = $database->query($tsql);
                //customer group not found
                $data = $result->rows;

                //findout breakleve
                $count = $this->countBreakLevel($columns);
                for($i=0;$i<$count+1;$i++){
                    foreach($data as $row){
                        $quantity = 1;
                        $price = $row[$group['ref_acc_type_code'].'Price'];
                        if($i){
                            $quantity = $row[$group['ref_acc_type_code'].'BreakLevel'.$i];
                            $price = $row[$group['ref_acc_type_code'].'Level'.$i.'Price'];
                        }
                        //if no discount avaiable
                        if($quantity==0 && $price==0)
                            continue;
                        $sql.="INSERT INTO product_discount
                                (product_id,customer_group_id,quantity,priority,
                                    price,date_start,date_end) VALUES(
                                '{$row['ItemNum']}',
                                '{$group['customer_group_id']}',
                                '{$quantity}',
                                $i,
                                '{$price}',
                                'NOW()',
                                'DATE_ADD(NOW(),INTERVAL 1 YEAR)'
                                );\n";
                    }//end foreach
                }//break level - product discount rows
            }//end group
            return $sql;
        }
        private function countBreakLevel($columns)
        {
            $count = 0;
            foreach($columns as $col){
                if(stripos($col,'break'))
                    $count++;
            }
            return $count;
        }
        public function saveMapping($mappingName,$mapping){
            if(!is_array($mapping))
                return false;
            $path = DIR_CACHE."mapping/item/";
            
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
            $path = DIR_CACHE."mapping/item/";
            $arMapping=array();
            foreach(glob($path."*.dat") as $file)
            {
                $arMapping[]=basename($file, ".dat");
            }
            return $arMapping;
        }
        public function getMapping($mappingName){
            if(!$this->mapping){
                $path = DIR_CACHE."mapping/item/";
                if(!is_file($path.$mappingName.'.dat')){
                    return false;
                }
                $mapping = file_get_contents($path.$mappingName.".dat");
                if($mapping)
                {
                    $mapping = unserialize($mapping);
                }
                $this->mapping = $mapping;
            }
            return $this->mapping;

        }
	
	function clearCache() {
            $this->cache->delete('items');
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