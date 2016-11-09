<?php

class Session {

    var $data;

    public function __construct() {
	ini_set('session.use_cookies', 'On');
	ini_set('session.use_trans_sid', 'Off');
	session_set_cookie_params(0, '/');
	$this->init();
    }

    public function __set($name, $value) {
	$_SESSION[$name] = $value;
    }

    public function &__get($name) {
	return $_SESSION[$name];
    }

    public function __isset($name) {
	return isset($_SESSION[$name]);
    }

    public function validate($session_id) {
	return !empty($session_id) && preg_match('/^[a-zA-Z0-9]{26}$/', $session_id);
    }

    public function init($session_id = null) {
	//write and close current session
	if (session_id()) {
	    $this->destroy();
	    if (!$session_id)
		session_regenerate_id(true);
	}

	if ($session_id) {
	    session_id($session_id);
	}
	session_name(SESSION_NAME);
	session_start();
	// get the desired session data
	$this->data = & $_SESSION;
	return $this;
    }

    public function destroy() {
	$a = session_id();
	if ($a == '')
	    session_start();
	session_unset(); //destroys variables
	//session_destroy(); //destroys session;
    }

    public function getSessionData($session_name = '', $session_save_handler = 'files') {
	$session_data = array();

	if ($session_name == '') {
	    $session_name = md5($this->config->get('config_name'));
	}
	// did we get told what the old session id was? we can't continue it without that info
	if (array_key_exists($session_name, $_COOKIE)) {
	    //save current session id
	    $session_id = $_COOKIE[$session_name];
	    $old_session_id = session_id();

	    //write and close current session
	    session_write_close();

	    // grab old save handler, and switch to files
	    $old_session_save_handler = ini_get('session.save_handler');
	    ini_set('session.save_handler', $session_save_handler);

	    // now we can switch the session over, capturing the old session name
	    $old_session_name = session_name($session_name);
	    session_id($session_id);
	    session_start();

	    // get the desired session data
	    $session_data = $_SESSION;

	    // close this session, switch back to the original handler, then restart the old session
	    session_write_close();
	    ini_set('session.save_handler', $old_session_save_handler);
	    session_name($old_session_name);
	    session_id($old_session_id);
	    session_start();
	}

	// now return the data we just retrieved
	return $session_data;
    }

    public function __destruct() {
	session_write_close();
    }

}

?>