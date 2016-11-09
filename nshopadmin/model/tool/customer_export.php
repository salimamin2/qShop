<?php
require_once DIR_APPLICATION . 'model/export.php';
class ModelToolCustomerExport extends ModelExport {

    function __construct($registry){
        parent::__construct($registry);
    }
    public function getCustomers(){
        $sql = "INSERT INTO import_customer
            SELECT c.account_number AS `acc_num`,
                   a.company AS `company_name`,
                   a.city AS `city`,
                   z.name AS `state`,
                   a.postcode AS `zip`,
                   cg.ref_acc_type_code AS `acc_type`,
                   c.firstname AS `contact_name`,
                   c.password AS `password`,
                   c.email AS `email`,
                   ca.web_flag AS `web_flag`,
                   a.address_1 AS `address1`,
                   a.address_2 AS `address2`,
                   c.telephone AS `phone` ,
                   c.fax AS `fax` ,
                   ca.tax_id AS `tax_id` ,
                   co.name AS `country`   ,
                   c.user_id AS `acc_mgr`   ,
                   ca.web_id AS `web_id`   ,
                   c.is_sync AS `is_sync`    ,
                   c.customer_id AS `customer_id`
            FROM customer c
            LEFT JOIN customer_attributes as ca ON c.customer_id = ca.customer_id
            LEFT JOIN address as a ON a.customer_id = c.customer_id
            LEFT JOIN zone z ON a.zone_id=z.zone_id
            LEFT JOIN customer_group cg ON c.customer_group_id = cg.customer_group_id
            LEFT JOIN country co ON a.country_id = co.country_id
            WHERE c.is_sync = 0";
        $ins = $this->db->query($sql);
        if($ins){
            $sql = "SELECT * FROM import_customer";
            $query = $this->db->query($sql);
            $success = parent::csv_from_mysql_resource($query->rows);

            return $success;
        }
        return mysql_error();
    }
}
?>