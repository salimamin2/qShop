<?php
/**
 * Cache Driver storing data in file system
 *
 * @access public
 * @license GPL
 * @author
 * @link
 * @version 1.0
 */
class FileCacheDriver implements CacheDriver
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
        $this->config($configs);
        // run garbage collection
        if (rand(1, $this->_configs['gc']) === 1) {
            $this->gc();
        }
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
        $default = array('path' => DIR_CACHE, 'prefix' => 'qscache.','suffix'=>'.cache', 'expire' => 3600, 'gc'=>4800);
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
        // check is writable
        if (!is_writable($this->_configs['path'])) {
            throw new CacheException($this->_configs['path'].' must be writable!');
            return false;
        }

        // Prepare data for writing
        if (!empty($options['expire'])) {
            $expire = $options['expire'];
        } else {
            $expire = $this->_configs['expire'];
        }

        if (is_string($expire)) {
            $expire = strtotime($expire);
        } else {
            $expire = time() + $expire;
        }

        $data = serialize(array('expire' => $expire, 'data' => $data));

        $fileName = $this->_configs['path'] . $this->_configs['prefix'] . $key;
        // Write data to files
        if ($this->file_force_contents($fileName, $data)) {
            return true;
        } else {
        	  throw new CacheException($fileName.' cant store value!');
            return false;
        }
    }

    /**
     * Gets data from cache
     *
     * @param string $groupName Name of group
     * @param string $key key of data
     * @return mixed
     */
    public function get($key,$options=array()){
        $fileName = $this->_configs['path'] . $this->_configs['prefix'] . $key;

        if (!file_exists($fileName)) {
            return false;
        }

        if (!is_readable($fileName)) {
            throw new CacheException($fileName.' not readable!');
            return false;
        }

        $data = file_get_contents($fileName);
        if ($data === false) {
            return false;
        }

        $data = unserialize($data);

        if ($data['expire'] < time()) {
            $this->delete($key);
            return false;
        }

        return $data['data'];
    }

   /**
     * Delete a cache data
     *
     * @param string $key
     * @param array $options
     * @return boolean
     */
    function delete($key, $options = array()) {
        $fileName = $this->_configs['path'] . $this->_configs['prefix'] . $key;
        if(strpos($key,'*') === 'true'){        //check if multiple allowed
           $entries = glob($fileName);
           if (!is_array($entries)) {
                return false;
           }
           foreach ($entries as $item) {
              if (!is_file($item) || !is_writable($item)) {
                  continue;
              }
              if(!unlink($item)){
                  throw new CacheException($item.' cant delete!');
                  return false;
              }
            }
            return true;
        }else{ //if single item available
          if (!file_exists($fileName) || !is_writable($fileName)) {
                throw new CacheException($fileName.' cant delete!');
                return false;
          }
        }
        return unlink($fileName);
    }

    /**
     * Clear cache data
     *
     * @param boolean $expired if true then only delete expired cache
     * @return booelan
     */
    public function clear($expired = true){
        $entries = glob($this->_configs['path'] . $this->_configs['prefix'] . "*");
        if (!is_array($entries)) {
            return false;
        }

        foreach ($entries as $item) {
            if (!is_file($item) || !is_writable($item)) {
                continue;
            }

            if ($expired) {
                $expire = file_get_contents($item, null, null, 20, 11);

                $strpos = strpos($expire, ';');
                if ($strpos !== false) {
                    $expire = substr($expire, 0, $strpos);
                }

                if ($expire > time()) {
                    continue;
                }
            }

            if (!unlink($item)) {
                throw new CacheException($item.' cant delete!');
                return false;
            }
        }

        return true;
    }



    /**
     * Gets last modification time of specified cache data
     *
     * @param string $key key
     * @return int
     */
    public function getTime($key){
        $item = $this->_configs['path'] . $this->_configs['prefix'] .$key;
        $expire = file_get_contents($item, null, null, 20, 11);
        $strpos = strpos($expire, ';');
        if ($strpos !== false) {
            $expire = substr($expire, 0, $strpos);
        }
        return $expire;
    }

    /**
     * Check if cache data exists
     *
     * @param string $groupName Name of group
     * @param string $key key
     * @return boolean
     */
    public function exists($key){
        $fileName = $this->_configs['path'] . $this->_configs['prefix'] . $key;
        return is_file($fileName);
    }

    /**
     * Creates path to file/directory
     *
     * @param string $groupName
     * @param string $key
     * @return string
     */
    protected function file_force_contents($file,$content){
        $ok = file_put_contents($file, $content,LOCK_EX);
        @chmod($file, 0777);
        return true;
    }

    protected function gc(){
        $files = glob($this->_configs['path']. 'cache.*');

        if ($files) {
            foreach ($files as $file) {
                $time = substr(strrchr($file, '.'), 1);
                if ($time+$this->expire   < time()) {
                    unlink($file);
                }
            }
        };
    }
    function __destruct() {
        foreach ($this as $index => $value) unset($this->$index);
    }

} /* end of class FileCacheDriver */
?>