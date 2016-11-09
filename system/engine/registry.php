<?php

final class Registry {

    private static $instance;
    private $data = array();

    private function __construct() {
        
    }

    public function get($key) {
        return (isset($this->data[$key]) ? $this->data[$key] : NULL);
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    public function __get($key) {
        return $this->get($key);
    }

    public function __set($key, $value) {
        $this->set($key, $value);
    }

    public function has($key) {
        return isset($this->data[$key]);
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    // Prevent users to clone the instance
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

}

?>