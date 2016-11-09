<?php

class ModelLocalisationLanguage extends ARModel {

    public static $_table = 'language';
    public static $_id_column = 'language_id';
//fields
    protected $_fields = array(
	'language_id',
	'name',
	'code',
	'locale',
	'image',
	'directory',
	'filename',
	'sort_order',
	'status',
	'is_deleted'
    );
    //validation rules
    protected $_rules = array(
	'insert|update' => array(
	    'rules' => array(
		'name' => array('required' => true, 'minlength' => 3, 'maxlength' => 100),
		'code' => array('required' => true, 'minlength' => 1),
		'locale' => array('required' => true),
		'image' => array('required' => true),
		'directory' => array('required' => true),
		'filename' => array('required' => true),
	    ),
    ));

    public function addLanguage($data) {
	try {
	    $oModel = Make::a('localisation/language')->create();
	    $oModel->setFields($data);
	    $oModel->save();
	    if ($oModel->hasErrors()) {
		throw new Exception($oModel->getErrors());
	    }
	    $iLanguage = $oModel->language_id;

	    // Category
	    $aResults = Make::a('catalog/category_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/category_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    $this->cache->delete('category');

	    // Coupon
	    $aResults = Make::a('catalog/coupon_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/coupon_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    // Download
	    $aResults = Make::a('catalog/download_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/download_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    // Information
	    $aResults = Make::a('catalog/information_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/information_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    $this->cache->delete('information');

	    // Length
	    $aResults = Make::a('catalog/length_class_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/length_class_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    $this->cache->delete('length_class');

	    // Order Status
	    $aResults = Make::a('catalog/order_status')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/order_status')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    $this->cache->delete('order_status');

	    // Product
	    $aResults = Make::a('catalog/product_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/product_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    $this->cache->delete('product');

	    // Product Option
	    $aResults = Make::a('catalog/product_option_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/product_option_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    // Product Option Value
	    $aResults = Make::a('catalog/product_option_value_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/product_option_value_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    // Stock Status
	    $aResults = Make::a('catalog/stock_status')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/stock_status')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    $this->cache->delete('stock_status');

	    // Store
	    $aResults = Make::a('catalog/store_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/store_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    $this->cache->delete('store');

	    // Weight Class
	    $aResults = Make::a('catalog/weight_class_description')
		    ->where('language_id', (int) $this->config->get('config_language_id'))
		    ->find_many(true);

	    foreach ($aResults as $aResult) {
		$oModel = Make::a('catalog/weight_class_description')->create();
		$oModel->setFields($aResult);
		$oModel->language_id = $iLanguage;
		$oModel->save();
	    }

	    $this->cache->delete('weight_class');
	} catch (Exception $e) {
	    return $e->getMessage();
	}
    }

    public function deleteLanguage($language_id) {

	ORM::raw_execute("DELETE FROM language WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('language');

	ORM::raw_execute("DELETE FROM store_description WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('store');

	ORM::raw_execute("DELETE FROM category_description WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('category');

	ORM::raw_execute("DELETE FROM coupon_description WHERE language_id = '" . (int) $language_id . "'");

	ORM::raw_execute("DELETE FROM download_description WHERE language_id = '" . (int) $language_id . "'");
	ORM::raw_execute("DELETE FROM information_description WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('information');

	ORM::raw_execute("DELETE FROM length_class_description WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('length_class');

	ORM::raw_execute("DELETE FROM order_status WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('order_status');

	ORM::raw_execute("DELETE FROM product_description WHERE language_id = '" . (int) $language_id . "'");
	ORM::raw_execute("DELETE FROM product_option_description WHERE language_id = '" . (int) $language_id . "'");
	ORM::raw_execute("DELETE FROM product_option_value_description WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('product');

	ORM::raw_execute("DELETE FROM stock_status WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('stock_status');

	ORM::raw_execute("DELETE FROM weight_class_description WHERE language_id = '" . (int) $language_id . "'");

	$this->cache->delete('weight_class');
    }

    public function getLanguages() {
        $oLangs = Make::a('localisation/language')->find_many(true);
        return $oLangs;
    }

}

?>