<?php

class ModelCatalogProductDescription extends ARModel
{

    public static $_table = 'product_description';
    public static $_id_column = 'id';
    public $reg;
    //fields
    protected $_fields = array(
        'id',
        'product_id',
        'language_id',
        'name',
        'meta_title',
        'meta_keywords',
        'img_alt',
        'meta_link',
        'meta_descript',
        'meta_description',
        'description',
        'is_deleted'
    );

//    public function init()
//    {
//        //setting up default values
//        $this->reg = Registry::getInstance();
//        parent::init();
//    }

    public function getData($name)
    {
        $products = ORM::for_table('product_description')
            ->where('name', $name)
            ->find_one();

        return $data = $products->product_id;
    }

}

?>