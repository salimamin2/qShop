<?php
require_once DIR_APPLICATION . 'model/export.php';
class ModelSaleCustomerExport extends ModelExport {

    function __construct($registry){
        parent::__construct($registry);
    }
    public function getCustomers(){
        $sql = "SELECT *
                FROM customer c
                LEFT JOIN customer_group cg ON c.customer_group_id = cg.customer_group_id
                LEFT JOIN customer_attributes ca ON ca.customer_id = ca.customer_id
		LEFT JOIN address a ON c.customer_id=a.customer_id
                WHERE is_sync = 0";
        $query = $this->db->query($sql);
        parent::csv_from_mysql_resource($query->rows);
        // takes a database resource returned by a query
    }
}
?>