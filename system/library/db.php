<?php

final class DB {

    private $driver;
    private $isLog;
    private $log;
    private $cache_on = FALSE;
    protected $callingClass;
    private $CACHE; // The cache class object

    public function __construct($driver, $hostname, $username, $password, $database) {
        global $registry;
        if (file_exists(DIR_DATABASE . $driver . '.php')) {
            require_once(DIR_DATABASE . $driver . '.php');
        } else {
            exit('Error: Could not load database file ' . $driver . '!');
        }

        $this->driver = new $driver($hostname, $username, $password, $database);
        $this->isLog = DB_LOG;
        $this->log = 0;
        $this->cache_on = DB_CACHE;
    }

    public function query($sql, $disableCache=NULL) {
        //$this->callingClass = $this->getCalledClass();
        // Is query caching enabled?  If the query is a "read type"
        // we will load the caching class and return the previously
        // cached query if it exists
        if (!is_null($disableCache) && $disableCache == TRUE) {
            $this->cache_on = false;
        }

        if ($this->cache_on && stristr($sql, 'SELECT')) {
            if ($this->_cache_init()) {
                if (FALSE !== ($cache = $this->CACHE->get($sql))) {
                    return $cache;
                }
            }
        }

        if ($this->isLog) {
            Timer::reset();
            Timer::start();
        }

        $result = $this->driver->query($sql);

        if ($this->isLog) {
            Timer::stop();
            //Log for Query having execution time greater than 3 sec.
            if (Timer::get() > 1) {
                $log = "[" . date('Y-m-d H:i:s') . "][" . Timer::get() . "s] " . $sql . "\n";
                $file = DIR_LOGS . 'sql-log.txt';
                $handle = fopen($file, 'a+');
                fwrite($handle, $log);
                fclose($handle);
            }
        }
        // Is query caching enabled?  If so, we'll serialize the
        // result object and save it to a cache file.
        if ($this->cache_on && $this->_cache_init()) {
            // Reset these since cached objects can not utilize resource IDs.
            $this->CACHE->set($sql, $result);
        }
        return $result;
    }

    public function escape($value) {
        return $this->driver->escape($value);
    }

    public function countAffected() {
        return $this->driver->countAffected();
    }

    public function getLastId() {
        return $this->driver->getLastId();
    }

    public function setLog($log) {
        $this->log = $log;
    }

    public function doLogged($isLog) {
        $this->isLog = $isLog;
    }

    private function _cache_init() {
        global $registry;
        //d($this->callingClass);
        /* @var $registry Registry */
        if (is_object($this->CACHE) && class_exists('Cache')) {
            return TRUE;
        }

        if (!($this->CACHE = $registry->get('cache'))) {

            if (!class_exists('Cache')) {
                if (!@include(DIR_SYSTEM . 'library/cache.php')) {
                    $this->cache_on = false;
                    return $this->cache_on;
                }
            }
            $this->CACHE = new Cache();
        }
        //validate the model-- calling class

        return true;
    }

    /*
     * @return DB
     */

    public function disableCache() {
        $this->cache_on = false;
        return $this;
    }

    /*
     * @return DB
     */

    public function enableCache() {
        $this->cache_on = DB_CACHE;
        return $this;
    }

    /** Performs insert of one row. Accepts values to insert as an array:
     *    'column1' => 'value1'
     *    'column2' => 'value2'
     *    ...
     * @param string  $sTable    table name
     * @param array   $aValues   column and values to insert
     * @param boolean $bEscape true - method escapes values (with "), false - not escapes
     * @return int last ID (or 0 on error)
     */
    function insert($sTable, $aValues, $bEscape=true, $aColumns=array()) {
        return $this->driver->insert($sTable, $aValues, $bEscape, $aColumns);
    }

    /** Performs insert of multiple rows. Accepts values to insert as an array:
     *    1 => array('column1' => 'value1'),
     *    2 => array('column2' => 'value2'),
     *    ...
     * @param string  $sTable    table name
     * @param array   $aValues   column and values to insert
     * @param boolean $bEscape true - method escapes values (with "), false - not escapes
     * @return int last ID (or 0 on error)
     */
    function bulkInsert($sTable, $aValues, $bEscape=true) {
        return $this->driver->bulkInsert($sTable, $aValues, $bEscape);
    }

    /** Performs update of rows.
     * @param string $sTable  table name
     * @param array  $aValues array of column=>new_value
     * @param string $sCond   condition (without WHERE)
     * @param boolean $bEscape true - method escapes values (with "), false - not escapes
     * @return boolean true - update successfule, false - error
     */
    function update($sTable, $aValues, $sCond, $bEscape=true, $aColumns=array()) {
        return $this->driver->update($sTable, $aValues, $sCond, $bEscape, $aColumns);
    }

    /** Format date as string for MySQL
     * @param int $timestamp datetime as timestamp (current time if omitted)
     * @return string fomratted datetime
     */
    function date($timestamp = 0) {
        return $this->driver->date($timestamp);
    }

    public function getError() {
        return $this->driver->getError();
    }

    public function getColumns($table, $sCond="") {
        return $this->driver->getColumns($table, $sCond);
    }

}

?>