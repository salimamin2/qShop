<?php

final class User {

    private $user_id;
    private $username;
    private $permission = array();
    private $display_name;
    private $user_group_id;

    public function __construct() {
	$this->request = Registry::getInstance()->request;
	$this->session = Registry::getInstance()->session;

	if (isset($this->session->data['user_id'])) {
	    $user = ARModel::factory('user/user')->find_one((int) $this->session->data['user_id']);

	    if ($user) {
		$this->init($user);
	    } else {
		$this->logout();
	    }
	}
    }

    /**
     * @assert ($user) instanceof ModelUserUser
     */
    public function init($user) {
	$this->user_id = $user->id;
	$this->username = $user->username;
	$this->display_name = $user->firstname . ' ' . $user->lastname;
	$this->user_group_id = $user->user_group_id;

	if ($user->ip !== $this->request->server['REMOTE_ADDR']) {
	    $user->setScenario('login');
	    $user->ip = $this->request->server['REMOTE_ADDR'];
	    $user->save();
	}
	/* @var $user_group ModelUserUserGroup */
	$user_group = $user->getUserGroup()->find_one();
	foreach ($user_group->getPermission() as $key => $value) {
	    $this->permission[$key] = $value;
	}
    }

    /**
     * @assert ('aaa','password') == FALSE
     * @assert ('admin', 'e10adc3949ba59abbe56e057f20f883e') == TRUE
     */
    public function login($username, $password) {
	$user = ARModel::factory('user/user')
		->where('username', $username)
		->where('password', self::encrypt($password))
		->find_one();

	if ($user) {
	    $this->session->data['user_id'] = $user->id;
	    $this->init($user);
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

    public function logout() {
	unset($this->session->data['user_id']);

	$this->user_id = '';
	$this->username = '';
    }

    public function hasPermission($key, $value) {
	if (isset($this->permission[$key])) {
	    return in_array($value, $this->permission[$key]);
	} else {
	    return FALSE;
	}
    }

    public function isLogged() {
	return $this->user_id;
    }

    public function getId() {
	return $this->user_id;
    }

    public function getUserName() {
	return $this->username;
    }

    public function getDisplayName() {
	return $this->display_name;
    }

    public function getPermission() {
	return $this->permission;
    }

    public function getUserGroupId() {
	return $this->user_group_id;
    }

    public function getTemplateType() {
	return $this->template_type;
    }

    public static function encrypt($password) {
	return md5($password);
    }

}

?>