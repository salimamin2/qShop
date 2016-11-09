<?php

/**
 * Description of setting
 *
 * @author Moiz Shabbir <moiz.sf@gmail.com>
 */
class ModelSettingPageLayout extends ARModel {

    public static $_table = 'page_layout';
    //fields
    protected $_fields = array(
	'id',
	'page',
	'page_id',
	'layout',
	'params',
    );
    //validation rules
    protected $_rules = array(
	'insert' => array(
	    'rules' => array(
		'page_id' => array('required' => true),
		'page' => array('required' => true),
		'layout' => array('required' => true)
	    ),
	)
    );

    public function getUser() {
	return $this->belongs_to('user/user', 'created_by_id');
    }

    public function init() {
	//TODO:initialize model
	parent::init();
    }

    public function validateDelete() {
	//TODO:validate delete
    }

}

?>