<?php

final class Loader {

    protected $registry;

    public function __construct() {
	$this->registry = Registry::getInstance();
    }

    public function __get($key) {
	return $this->registry->get($key);
    }

    public function __set($key, $value) {
	$this->registry->set($key, $value);
    }

    public function library($library) {
	$file = DIR_SYSTEM . 'library/' . $library . '.php';

	if (file_exists($file)) {
	    include_once($file);
	} else {
	    exit('Error: Could not load library ' . $library . '!');
	}
    }

    public function model($model) {
	$file = DIR_APPLICATION . 'model/' . $model . '.php';
	$class = 'Model' . strtocamel($model);

	if ($this->registry->has('model_' . str_replace('/', '_', $model))) {
	    return $class;
	}

	if (file_exists($file)) {
	    include_once($file);
	    $aModel = new $class;
	    if ($aModel instanceof Model) {
		$this->registry->set('model_' . str_replace('/', '_', $model), $aModel);
	    }
	} else {
	    exit('Error: Could not load model ' . $model . '!');
	}
	return $class;
    }

    public function database($driver, $hostname, $username, $password, $database, $prefix = NULL, $charset = 'UTF8') {
	$file = DIR_SYSTEM . 'database/' . $driver . '.php';
	$class = 'Database' . strtocamel($driver);

	if (file_exists($file)) {
	    include_once($file);

	    $this->registry->set(str_replace('/', '_', $driver), new $class());
	} else {
	    exit('Error: Could not load database ' . $driver . '!');
	}
    }

    public function helper($helper) {
	$file = DIR_SYSTEM . 'helper/' . $helper . '.php';

	if (file_exists($file)) {
	    include_once($file);
	} else {
	    exit('Error: Could not load helper ' . $helper . '!');
	}
    }

    public function helper_obj($helper) {
	$file = DIR_SYSTEM . 'helper/' . $helper . '.php';
	$class = 'Helper' . strtocamel($helper);
	if ($this->registry->has('helper_' . str_replace('/', '_', $helper))) {
	    return;
	}
	if (file_exists($file)) {
	    include_once($file);

	    $this->registry->set('helper_' . str_replace('/', '_', $helper), new $class($this->registry));
	} else {
	    exit('Error: Could not load helper ' . $helper . '!');
	}
    }

    public function config($config) {
	$this->config->load($config);
    }

    public function language($language) {
	$this->language->load($language);
    }

}

?>