<?php

final class Front {

    protected $registry;
    protected $pre_action = array();
    protected $error;

    public function __construct() {
	$this->registry = Registry::getInstance();
    }

    public function addPreAction($pre_action) {
	$this->pre_action[] = $pre_action;
    }

    public function dispatch($action, $error) {
	$this->error = $error;

	foreach ($this->pre_action as $pre_action) {
	    $result = $this->execute($pre_action);

	    if ($result) {
		$action = $result;
		break;
	    }
	}

	while ($action) {
	    $action = $this->execute($action);
	}
    }

    private function execute($action) {
	$file = $action->getFile();
	$class = $action->getClass();
	$method = $action->getMethod();
	$args = $action->getArgs();
	$route = $action->getRoute();

	$action = '';

	if (file_exists($file)) {
	    require_once($file);

	    $controller = new $class();
	  
	    if (is_callable(array($controller, $method))) {
		$controller->setMethod($method);
		$controller->setAlias($route);
		$controller->_pre();
		$action = call_user_func_array(array($controller, $method), $args);
		
		$controller->_post($method);
	    } else {
		$action = $this->error;

		$this->error = '';
	    }
	} else {
	    $action = $this->error;

	    $this->error = '';
	}
	return $action;
    }

}

?>