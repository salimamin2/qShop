<?php

/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 10/31/2016
 * Time: 6:06 PM
 */
class ModelAccountReturn extends ARModel
{
    public static $_table = 'return';
    public static $_id_column = 'return_id';
    //fields
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


//    public function init() {
//
//        //setting up default values
//        $this->reg = Registry::getInstance();
//        parent::init();
//
//    }




}