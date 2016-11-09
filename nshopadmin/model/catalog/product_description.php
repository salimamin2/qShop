<?php

class ModelCatalogProductDescription Extends ARModel {

    public static $_table = 'product_description';
    protected $_fields = array(
	'id',
	'product_id',
	'language_id',
	'name',
	'meta_title',
    'meta_link',
    'img_alt',
    'meta_descript',
	'meta_keywords',
	'meta_description',
	'description'
    );
    protected $_rules = array(
	'insert|update' => array(
	    'rules' => array(
		'language_id' => array('required' => true),
		'name' => array('required' => true, 'maxlength' => 100)
	    )
    ));

}
