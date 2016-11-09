<?php 

class ModelSaleDiscountProduct extends ARModel {

    public static $_table = 'discount_product';
    public static $_id_column = 'discount_product_id';
    //fields
    protected $_fields = array(
    	'discount_product_id',
        'discount_id',
        'product_id',
        'is_deleted'
    );
}