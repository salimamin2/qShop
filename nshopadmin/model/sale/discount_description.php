<?php 
class ModelSaleDiscountDescription extends ARModel {

    public static $_table = 'discount_description';
    public static $_id_column = 'discount_id';
    //fields
    protected $_fields = array(
        'discount_id',
        'language_id',
        'name',
        'description',
        'is_deleted'
    );

    protected $_rules = array(
    'insert|update' => array(
        'rules' => array(
            'name' => array('required' => true),
            'description' => array('required' => true)
        )
    ));
}