<?php

class ModelAccountWishlist extends ARModel {

    public static $_table = 'wishlist';
    public static $_id_column = 'wishlist_id';
    public $reg;

    //fields
    protected $_fields = array(
    	'wishlist_id',
    	'customer_id',
    	'product_id',
    	'product_option_id',
    	'product_option_value_id',
    	'product_detail_id',
    	'comments',
    	'date_added',
    	'is_deleted'
    );

    public function init() {
        //setting up default values
        $this->reg = Registry::getInstance();
        parent::init();
    }


    public function addWishlist($data) {
    	$oOrm = ORM::for_table(self::$_table)->create();
    	$oOrm->product_id = $data['product_id'];
    	$oOrm->customer_id = $data['customer_id'];
    	if(isset($data['product_option_id']) && $data['product_option_id']) {
    		$oOrm->product_option_id = $data['product_option_id'];
    	}

    	if (isset($data['product_option_value_id']) && $data['product_option_value_id']) {
		    $oOrm->product_option_value_id = $data['product_option_value_id'];
		}
		if (isset($data['product_detail_id']) && $data['product_detail_id']) {
		    $oOrm->product_detail_id = $data['product_detail_id'];
		}

		$oOrm->date_added = date('Y-m-d H:i:s');
		$oOrm->save();
		return $oOrm->id();
    }

    public function updateWishlist($wishlist_id, $comment) {
		$this->db->query("UPDATE `" . DB_PREFIX . "wishlist` SET comments = '" . $this->db->escape($comment) . "' WHERE wishlist_id = '" . (int) $wishlist_id . "'");
    }

    public function deleteWishlist($wishlist_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "wishlist` WHERE wishlist_id = " . (int) $wishlist_id . "");
	//d(mysql_error());
    }

    public function getWishlist($customer_id,$start,$limit) {
        $oModel = ORM::for_table(self::$_table)
                    ->table_alias('w')
                    ->left_outer_join('product',array('p.product_id','=','w.product_id'),'p')
                    ->left_outer_join('product_description',array('pd.product_id','=','w.product_id'),'pd')
                    ->where('w.customer_id',$customer_id)
                    ->order_by_desc('w.wishlist_id');
        if ($start < 0) {
            $start = 0;
        }
        $aModel = $oModel->offset($start)
                    ->limit((int) $limit)->find_many(true);
                    
        $iCount = ORM:: for_table(self::$_table)->where('customer_id',$customer_id)->count();
		return array($iCount,$aModel);
    }

    public function getWishlistById($wishlist_id) {
	// $sql = "SELECT * FROM wishlist WHERE wishlist_id = " . $wishlist_id;
	// $query = $this->db->query($sql);
	// return $query->row;
    	return ORM::for_table(self::$_table)->find_one($wishlist_id,true);
    }

    public function getWishlistByProduct($product_id, $customer_id) {
	// $sql = "SELECT * FROM wishlist WHERE product_id = " . $product_id ." AND customer_id = " . $customer_id;
	// $query = $this->db->query($sql);
	// return $query->row;
    	return ORM::for_table(self::$_table)
    				->where('product_id',$product_id)
    				->where('customer_id',$customer_id)
    				->find_one(null,true);
    }

}

?>