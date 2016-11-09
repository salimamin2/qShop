<?php
class ModelSaleReturn extends ARModel {

    public static $_table = 'return';
    public static $_id_column = 'return_id';
    public $keyword;
    protected $_fields = array(
        'return_id',
        'customer_id',
        'firstname',
        'lastname',
        'email',
        'telephone',
        'product',
        'quantity',
        'order_id',
        'date_ordered',
        'return_reason_id',
        'return_status_id',
        'date_added',
        'product_id',
        'model',
        'comment'
    );

    public function getReturn() {
        $category_data = array();

        $returnItems = Make::a('sale/return')
            ->table_alias('r')
            ->join('return_status','rs.return_status_id=r.return_status_id','rs')
            ->find_many(true);

        foreach ($returnItems as $returnItem) {
            $category_data[] = array(
                'return_id' => $returnItem['return_id'],
                'order_id' => $returnItem['order_id'],
                'product' => $returnItem['product'],
                'firstname' => $returnItem['firstname'],
                'lastname' => $returnItem['lastname'],
                'return_name' => $returnItem['name'],
                'date_added' => $returnItem['date_added'],
                'date_ordered' => $returnItem['date_ordered'],
                'model' => $returnItem['model']
            );

//            $category_data = array_merge($category_data, $this->getCategories($returnItem['return_id']));
        }
        return $category_data;
    }

    public function getEdit($return_id){
        $query = ORM::for_table('return')
            ->where('return_id', $return_id)
            ->find_many(true);
        return $query;
    }

    public function getUpdate($id, $action){
        $query = ARModel::factory('sale/return')->find_one($id);
            $query->return_action_id = $action;
            $query->save();
        return $query;
    }

    public function getHistory($id, $checkedValue, $status, $comment){
        $query = ORM::for_table('return_history')->create();
            $query->return_id = $id;
            $query->return_status_id = $status;
            $query->comment = $comment;
            $query->notify = $checkedValue;
            $query->date_added = date('Y-m-d');
            $query->save();
        return $query;

    }

    public function getHistoryList(){
        $history_data = array();

        $returnItems = ORM::for_table('return_history')
            ->table_alias('rh')
            ->select('rh.return_id')
            ->select('rh.return_status_id')
            ->select('rh.notify')
            ->select('rh.comment')
            ->select('rh.date_added')
            ->select('rs.name')
            ->select('r.customer_id')
            ->join('return_status','rs.return_status_id=rh.return_status_id','rs')
            ->join('return','r.return_id=rh.return_id','r')
            ->order_by_asc('rh.return_history_id')
            ->find_many(true);
        return $returnItems;
    }

    public function getHistoryListLast(){
        $history_data = array();

        $returnItems = ORM::for_table('return_history')
            ->table_alias('rh')
            ->select('rh.return_history_id')
            ->select('rh.return_id')
            ->select('rh.return_status_id')
            ->select('rh.notify')
            ->select('rh.comment')
            ->select('rh.date_added')
            ->select('rs.name')
            ->select('r.customer_id')
            ->join('return_status','rs.return_status_id=rh.return_status_id','rs')
            ->join('return','r.return_id=rh.return_id','r')
            ->order_by_desc('rh.return_history_id')
            ->limit(1)
            ->find_many(true);
        return $returnItems;
    }

    public function getReturnReason() {
        //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_reason");
        $query = ORM::for_table('return_reason')
            ->find_many(true);
        return $query;
    }
    public function getReturnAction() {
        $query = ORM::for_table('return_action')
            ->find_many(true);
        return $query;
    }
    public function getReturnStatus() {
        $query = ORM::for_table('return_status')
            ->find_many(true);
        return $query;
    }
    public function getProductsByKeyword(){
        //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description");
        $query = $this->db->query("SELECT p.`model`, pd.* FROM " . DB_PREFIX . "product_description"." AS pd INNER JOIN product AS p ON p.`product_id` = pd.`product_id`");
        return $query->rows;
    }
}
//class ModelSaleReturn extends Model {
//
//	public function getReturnReason() {
//        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_reason");
//
//        return $query->rows;
//    }
//	public function getReturnAction() {
//        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_action");
//
//        return $query->rows;
//    }
//	public function getReturnStatus() {
//        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_status");
//
//        return $query->rows;
//    }
//    public function getProductsByKeyword(){
//    	//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description");
//    	$query = $this->db->query("SELECT p.`model`, pd.* FROM " . DB_PREFIX . "product_description"." AS pd INNER JOIN product AS p ON p.`product_id` = pd.`product_id`");
//        return $query->rows;
//    }
//
//}
?>