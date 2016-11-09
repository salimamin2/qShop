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
interface CacheDriver
{

    /**
     * Sets data to cache
     *
     * @param string $key Identifier of data
     * @param mixed $data Data
     * @param array $options of configuraiton
     * @return boolean
     */
    public function set($key, $data,$options = array());

    /**
     * Gets data from cache
     *
     * @param string $key Identifier of data
     * @param array $options of configuraiton
     * @return mixed
     */
    public function get($key,$options = array());

    /**
     * Clears cache of specified identifier of group
     *
     * @param string $key Identifier
     * @param array $options of configuraiton
     * @return boolean
     */
    public function delete($key,$options = array());


    /**
     * Clears all cache generated by this class with this driver
     *
     * @return boolean
     */
    public function clear();

    /**
     * Gets last modification time of specified cache data
     *
     * @param string $key Identifier
     * @return int
     */
    public function getTime($key);

    /**
     * Check if cache data exists
     *
     * @param string $key Identifier
     * @return boolean
     */
    public function exists($key);

} /* end of interface CacheDriver */
class CacheException extends Exception{

}
?>