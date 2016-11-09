<?php

class ModelSettingStore extends ARModel {

    public static $_table = 'store';
    //fields
    protected $_fields = array(
	'id',
	'name',
	'url',
	'title',
	'meta_description',
	'template',
	'country_id',
	'zone_id',
	'language',
	'currency',
	'tax',
	'customer_group_id',
	'customer_price',
	'customer_approval',
	'guest_checkout',
	'account_id',
	'checkout_id',
	'stock_display',
	'stock_check',
	'stock_checkout',
	'order_status_id',
	'logo',
	'icon',
	'image_thumb_width',
	'image_thumb_height',
	'image_popup_width',
	'image_popup_height',
	'image_category_width',
	'image_category_height',
	'image_product_width',
	'image_product_height',
	'image_additional_width',
	'image_additional_height',
	'image_related_width',
	'image_related_height',
	'image_cart_width',
	'image_cart_height',
	'ssl',
	'catalog_limit',
	'cart_weight',
    );

    public function init() {
	$this->setRuleMessage(__('please enter same password again'), 'insert,update', 'password_confirm', 'equalTo');
	//setting up default values
	$this->set('timezone', 'Asia/Karachi');
	$this->password_confirm = '';
	parent::init();
    }

    public function getStores() {
         $oStores = Make::a('setting/store')
                        ->find_many(true);
        return $oStores;
    }

}

?>