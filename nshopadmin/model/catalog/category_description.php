<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 05/03/14
 * Time: 12:44 PM
 */

class ModelCatalogCategoryDescription Extends ARModel {

    public static $_table = 'category_description';
    protected $_fields = array(
        'id',
        'category_id',
        'language_id',
        'name',
        'meta_title',
        'meta_link',
        'img_alt',
        'meta_keywords',
        'meta_description',
        'description',
        'is_deleted'
    );

    protected $_rules = array(
        'insert|update' => array(
            'rules' => array(
                'language_id' => array('required' => true),
                'category_id' => array('required' => true),
                'name' => array('required' => true,'maxlength' => 100)
            )
        ));
} 