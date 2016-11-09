<?php

/**
 * Interface of cache driver strategy
 *
 * @access public
 * @author Qasim Shabbir, <qasimshabbir@gmail.com>
 * @link http://q-sols.com
 * @license GPL
 * @version 1.0
 */
class Cache {

    const DRIVER_FILE = 1;
    const DRIVER_APC = 2;
    const DRIVER_MEM = 2;

    /**
     * Class Instances
     *
     * @var array
     */
    private static $__instances = array();

    public function __construct($configName = null, $engine = null, $configs = array()) {
	if (empty($configName)) {
	    $configName = CACHE_NAME;
	}
	if (empty($engine)) {
	    $engine = Cache::DRIVER_FILE;
	}
	if (empty(self::$__instances) || !isset(self::$__instances[$configName])) {
	    $_this = self::getInstance($configName, $engine, $configs);
	}
    }

    /**
     * Get Instance of a cache engine
     *
     * Factory Interface for Cache Engine.
     *
     * @param config $configName
     * @param string $engine default file
     * @param array $configs
     * @return object
     */
    public static function &getInstance($configName = null, $engine = null, $configs = array()) {
	if (empty($configName)) {
	    $configName = CACHE_NAME;
	}

	if (empty($engine)) {
	    $engine = Cache::DRIVER_FILE;
	}

	if (isset(self::$__instances[$configName])) {
	    return self::$__instances[$configName];
	}

	if (empty(self::$__instances)) {
	    $default = true;
	}

	$engine = strtolower($engine);

	switch ($engine) {
	    case Cache::DRIVER_APC:
		self::$__instances[$configName] = APCCacheDriver::getInstance($configs);
		break;
	    case Cache::DRIVER_FILE:
	    default:
		self::$__instances[$configName] = FileCacheDriver::getInstance($configs);
		break;
	}
	return self::$__instances[$configName];
    }

    /**
     * Static wrapper to cache set method
     *
     * @param string $key
     * @param mixed $data
     * @param array $options, array('expire' => 10), expire in seconds
     * @param string $configName
     * @return boolean
     */
    public function set($key, $data, $options = array(), $configName = null) {
	$_this = self::getInstance($configName);
	try {
	    return $_this->set($key, $data, $options);
	} catch (CacheException $ex) {
	    //Registry::getInstance()->log->write(__METHOD__.':'.$ex);
	    return false;
	}
    }

    /**
     * Static Wrapper to cache get method
     *
     * @param string $key
     * @param array $options
     * @param string $configName
     * @return mixed
     */
    public function get($key, $options = array(), $configName = null) {

	$_this = self::getInstance($configName);
	try {
	    return $_this->get($key, $options);
	} catch (CacheException $ex) {
	    //Registry::getInstance()->log->write(__METHOD__.':'.$ex);
	    return false;
	}
    }

    /**
     * Static wrapper to cache delete mathod
     *
     * @param string $key
     * @param array $options
     * @param string $configName
     * @return boolean
     */
    public function delete($key, $options = array(), $configName = null) {
	$_this = self::getInstance($configName);
	//refreshing page data
	FPC::instance()->refresh($key);
	try {
	    return $_this->delete($key, $options);
	} catch (CacheException $ex) {
	    //Registry::getInstance()->log->write(__METHOD__.':'.$ex);
	    return false;
	}
    }

    /**
     * Static wrapper to cache clear mathod
     *
     * @param boolean $expired if true then only delete expired cache otherwise all
     * @param string $configName
     * @return boolean
     */
    public function clear($configName = null, $expired = true) {
	$_this = self::getInstance($configName);

	if (!$expired) //refreshing the full page cache.
	    FPC::instance()->refresh();
	try {
	    return $_this->clear(null, $expired);
	} catch (CacheException $ex) {
	    //Registry::getInstance()->log->write(__METHOD__.':'.$ex);
	    return false;
	}
    }

    /**
     * Static wrapper to cache exists mathod
     *
     * @param string $key
     * @param array $options
     * @param string $configName
     * @return boolean
     */
    public function exists($key, $configName = null) {
	$_this = self::getInstance($configName);
	return $_this->exists($key);
    }

    /**
     * Static wrapper to cache exists mathod
     *
     * @param string $key
     * @param array $options
     * @param string $configName
     * @return boolean
     */
    public function getTime($key, $configName = null) {
	$_this = self::getInstance($configName);
	return $_this->getTime($key);
    }

    public function deleteAll() {
	$files = glob(DIR_CACHE . '*');

	if ($files) {
	    foreach ($files as $file) {
		unlink($file);
	    }
	}
    }

}

/* end of class Cache */

?>