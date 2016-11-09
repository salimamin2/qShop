<?php

class ModelCatalogProductIndex extends ARModel {

    public static $_table = 'product_index';
    //public static $_id_column = 'product_id';
    public $reg;
    //fields
    protected $_fields = array(
        'id',
        'product_id',
        'price',
        'status',
        'tags',
        'category_id',
        'category_name',
        'manufacturer_id',
        'manufacturer_name',
        'option_id_1',
        'option_name_1',
        'option_value_id_1',
        'option_value_1',
        'option_id_2',
        'option_name_2',
        'option_value_id_2',
        'option_value_2',
        'option_id_3',
        'option_name_3',
        'option_value_id_3',
        'option_value_3',
        'option_id_4',
        'option_name_4',
        'option_value_id_4',
        'option_value_4',
        'option_id_5',
        'option_name_5',
        'option_value_id_5',
        'option_value_5',
        'option_id_6',
        'option_name_6',
        'option_value_id_6',
        'option_value_6',
        'option_id_7',
        'option_name_7',
        'option_value_id_7',
        'option_value_7',
        'option_id_8',
        'option_name_8',
        'option_value_id_8',
        'option_value_8',
        'option_id_9',
        'option_name_9',
        'option_value_id_9',
        'option_value_9',
        'option_id_10',
        'option_name_10',
        'option_value_id_10',
        'option_value_10',
        'option_id_11',
        'option_name_11',
        'option_value_id_11',
        'option_value_11',
        'option_id_12',
        'option_name_12',
        'option_value_id_12',
        'option_value_12',
        'option_id_13',
        'option_name_13',
        'option_value_id_13',
        'option_value_13',
        'option_id_14',
        'option_name_14',
        'option_value_id_14',
        'option_value_14',
        'option_id_15',
        'option_name_15',
        'option_value_id_15',
        'option_value_15',
        'option_id_16',
        'option_name_16',
        'option_value_id_16',
        'option_value_16',
        'option_id_17',
        'option_name_17',
        'option_value_id_17',
        'option_value_17',
        'option_id_18',
        'option_name_18',
        'option_value_id_18',
        'option_value_18',
        'option_id_19',
        'option_name_19',
        'option_value_id_19',
        'option_value_19',
        'option_id_20',
        'option_name_20',
        'option_value_id_20',
        'option_value_20'
    );

    public function init() {
        //setting up default values
        $this->reg = Registry::getInstance();
        parent::init();
    }
    
}

?>