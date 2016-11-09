<?php
/**
 * Cache Driver storing data in APC Cache
 *
 * @access public
 * @license GPL
 * @author
 * @link
 * @version 1.0
 */
class APCCacheDriver implements CacheDriver
{
     /**
     * Class Instances
     *
     * @var array
     */
    private static $__instance;

     /**
     * Runtime Configuration Data
     *
     * @var array
     */
    protected $_configs = array();


    /**
     * Class Constructor
     *
     * @param array $configs
     */
    private function __construct($configs = array())
    {
        if (!extension_loaded('apc')) {
            throw new CacheException('The apc extension must be loaded for using this driver !');
        }
        $this->config($configs);

    }
    /**
     * Get Instance of Class
     *
     * @param string $name
     * @param array $configs
     * @return object
     * @static
     */
    public static function &getInstance($configs = array()){
        if (is_null(self::$__instance)) {
            self::$__instance = new self($configs);
        }
        return self::$__instance;
    }
    /**
     * Set Configuration
     *
     * default: array('path' => './cache/', 'prefix' => 'qscache_', 'expire' => 3600, 'gc' => 4800)
     *
     * @param array $configs
     * @return object self instance
     */
    public function config($configs = array()) {
    	// default path modified to work with cache
        $default = array('path' => './cache/', 'prefix' => 'qscache.', 'expire' => 3600, 'gc'=>4800);
        $this->_configs = array_merge($default, $configs);
        return $this;
    }

    /**
     * Sets data to cache
     *
     * @param string $key key of data
     * @param mixed $data Data
     * @param array $options, array('expire' => 10), expire in seconds
     * @return boolean
     */
    public function set($key, $data,$options = array()){

        // Prepare data for writing
        if (!empty($options['expire'])) {
            $expire = $options['expire'];
        } else {
            $expire = $this->_configs['expire'];
        }

//        if (is_string($expire)) {
//            $expire = strtotime($expire);
//        } else {
//            $expire = time() + $expire;
//        }
//        d(array($key,$data,$expire));
        return apc_store($key,$data,$expire);
    }

    /**
     * Gets data from cache
     *
     * @param string $groupName Name of group
     * @param string $key key of data
     * @return mixed
     */
    public function get($key,$option = array()){
        return apc_fetch($key);
    }

   /**
     * Delete a cache data
     *
     * @param string $key
     * @param array $options
     * @return boolean
     */
    function delete($key, $options = array()) {
        if(strpos($key,"*") === true){
          $key = str_replace('*','',$key);
          $pattern = '#^$key#';
          $iterator = new APCIterator('user',$pattern, APC_ITER_KEY);
          foreach ($iterator as $key => $data) {
            apc_delete($key);
          }
        }else{
            return apc_delete($key);
        }
    }

    /**
     * Clear cache data
     *
     * @param boolean $expired if true then only delete expired cache // not implemented
     * @return booelan
     */
      public function clear($expired=true)
      {
        $iterator = new APCIterator('user',NULL, APC_ITER_KEY);
        foreach ($iterator as $key => $data) {
          apc_delete($key);
        }
      }



    /**
     * Gets last modification time of specified cache data
     *
     * @param string $key key
     * @return int
     */
    public function getTime($key){
        return false;
    }

    /**
     * Check if cache data exists
     *
     * @param string $groupName Name of group
     * @param string $key key
     * @return boolean
     */
    public function exists($key){
        return apc_exists($key);
    }


    protected function gc(){
        $this->clear();
    }
    
    function __destruct() {
        foreach ($this as $index => $value) unset($this->$index);
    }

} /* end of class FileCacheDriver */
?>