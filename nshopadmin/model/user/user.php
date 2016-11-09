<?php

class ModelUserUser extends ARModel {

    public static $_table = 'user';
    //fields
    protected $_fields = array(
	'id',
	'user_group_id',
	'username',
	'email',
	'password',
	'firstname',
	'lastname',
	'date_added',
	'ip',
	'status'
    );
    //validation rules
    protected $_rules = array(
	'insert' => array(
	    'rules' => array(
		'username' => array('required' => true, 'minlength' => 5, 'maxlength' => 50),
		'password' => array('required' => true, 'minlength' => 5, 'maxlength' => 50),
		'password_confirm' => array('required' => true, 'minlength' => 5, 'maxlength' => 50, 'equalTo' => '#password'),
		'email' => array('required' => true, 'email' => true),
		'firstname' => array('required' => true, 'maxlength' => 50),
		'lastname' => array('required' => true, 'maxlength' => 50),
		'status' => array('required' => true),
	    ),
	), 'update' => array(
	    'rules' => array(
		'username' => array('required' => true,'minlength' => 5, 'maxlength' => 50),
		'password' => array('minlength' => 5, 'maxlength' => 50),
		'password_confirm' => array('minlength' => 5, 'maxlength' => 50, 'equalTo' => '#password'),
		'email' => array('required' => true, 'email' => true),
		'firstname' => array('required' => true, 'maxlength' => 50),
		'lastname' => array('required' => true, 'maxlength' => 50),
		'status' => array('required' => true),
	    ),
	), 'login' => array(
	    'rules' => array(
		'username' => array('required' => true, 'minlength' => 5, 'maxlength' => 50),
		'password' => array('required' => true, 'minlength' => 5, 'maxlength' => 50),
	    )), 'forgot' => array(
	    'rules' => array(
		'email' => array('required' => true, 'email' => true),
    )));

    public function init() {
	$this->setRuleMessage(__('please enter same password again'), 'insert,update', 'password_confirm', 'equalTo');
	//setting up default values
//	$this->set('timezone', 'Asia/Karachi');
	$this->password_confirm = '';
	parent::init();
    }

    public function getUsers($data = array()) {
	$user = ORM::for_table('user')->create();
	$sort_data = array(
	    'username',
	    'status',
	    'date_added'
	);
	$sort = 'username';
	if (isset($data['sort']) && (!empty($data['sort']))) {
	    $sort = $data['sort'];
	}
	if (isset($data['order']) && ($data['order'] == 'DESC')) {
	    $user->order_by_desc($sort);
	}


	if (isset($data['start']) || isset($data['limit'])) {
	    if ($data['start'] < 0) {
		$data['start'] = 0;
	    } else {
		$user->offset($data['start']);
	    }

	    if ($data['limit'] < 1) {
		$data['limit'] = 20;
	    }
	}
	$user->limit($data['limit']);
	return $user->find_many(true);
    }

    public function getTotalUsers() {
	return ORM::for_table('user')->count();
    }

    public function getTotalUsersByGroupId($user_group_id) {
	return ARModel::factory('user/user')->where('user_group_id', $user_group_id)->count();
    }

    public function getUserGroup() {
	return $this->belongs_to('user/user_group');
    }

    public function afterValidate() {
	parent::afterValidate();
	unset($this->password_confirm);
    }

    public function setFields($values, $safe_only = true) {
        $old = $this->password;
        parent::setFields($values, $safe_only);
        if ($this->is_dirty('password')) {
            if(trim($this->password) != "") {
                $this->password = md5($this->password);
            }
            else {
                $this->password = $old;
            }
        }
    }
}

?>