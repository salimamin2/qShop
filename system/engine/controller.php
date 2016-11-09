<?php

abstract class Controller {

    protected $id;
    protected $template;
    protected $children = array();
    protected $data = array();
    protected $output;
    protected $registry;
    protected $_model = null;
    protected $_alias;
    protected $_method; //instance of ORMWrapper class;

    public function __construct() {
	$this->registry = Registry::getInstance();
	$this->session->data['token'] = '';
    }

    public function _pre() {
	
    }

    public function _post() {
	
    }

    public function __get($key) {
	return $this->registry->get($key);
    }

    public function __set($key, $value) {
	$this->registry->set($key, $value);
    }

    protected function forward($route, $args = array()) {
	return new Action($route, $args);
    }

    protected function redirect($url) {
		header('Location: ' . str_replace('&amp;', '&', $url));
		exit();
    }

    protected function render($return = FALSE) {
		foreach ($this->children as $child) {
		    $action = new Action($child);
		    $file = $action->getFile();
		    $class = $action->getClass();
		    $method = $action->getMethod();
		    $args = $action->getArgs();

		    if (file_exists($file)) {
			require_once($file);

			$controller = new $class();

			$controller->index();

			$this->data[$controller->id] = $controller->output;
		    } else {
			exit('Error: Could not load controller ' . $child . '!');
		    }
		}
		$fetch = $this->fetch($this->template);
		
		if ($return) {
		    return $fetch;
		} 
	    $this->output = $fetch;
    }

    protected function fetch($filename) {
		$file = DIR_TEMPLATE . $filename;

		if (file_exists($file)) {
		    extract($this->data);
		    ob_start();
		    require($file);
		    $content = ob_get_contents();
		    if (!defined('DIR_ADMIN')) {
				$content = $this->shortcodes->do_shortcode($content);	
			}
		    if (!$this->isChild && PAGE_CACHE) {
				FPC::instance()->setPage($content);
			}
		    ob_end_clean();
		    return $content;
		} else {
		    exit('Error: Could not load template ' . $file . '!');
		}
    }

    public function loadTemplate($module, $view) {
	if (!$module && !$view)
	    return false;

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . "/template/{$module}/{$view}")) {
	    return $this->config->get('config_template') . "/template/{$module}/{$view}";
	} else {
	    return "default/template/{$module}/{$view}";
	}
    }

    public function loadFetch($alias) {
	$aFile = explode('/', $alias);
	return $this->fetch($this->loadTemplate($aFile[0], $aFile[1] . '.tpl'));
    }

    public function getModel() {
	return $this->_model;
    }

    public function setModel(ORMWrapper $model) {
	$this->_model = $model;
    }

    public function setAlias($alias) {
	$this->_alias = $alias;
    }

    public function getAlias() {
	return $this->_alias;
    }

    public function setMethod($method) {
	$this->_method = $method;
    }

    public function getMethod() {
	return $this->_method;
    }

    public function load($alias, $params = array()) {
	//d($alias);
	$action = new Action($alias);
	$file = $action->getFile();
	$class = $action->getClass();
	$method = $action->getMethod();
	$args = $action->getArgs();
	$route = $action->getRoute();
	$this->request->get['params'] = $params;
	if (file_exists($file)) {
	    require_once($file);
	    $controller = new $class();
	    if (is_callable(array($controller, $method))) {
		$controller->setMethod($method);
		$controller->setAlias($route);
		$controller->_pre();
		$action = call_user_func_array(array($controller, $method), $args);
		$controller->_post($method);
		return $controller->output;
	    } else {
		$this->session->data['dev_error'] = 'Error: Action not found!';
		// $this->redirect(makeUrl('error/not_found', array(), true));
	    }
	} else {
	    $this->session->data['dev_error'] = 'Error: Could not load controller ' . $alias . '!';
	    // $this->redirect(makeUrl('error/not_found', array(), true));
	}
    }

}

?>