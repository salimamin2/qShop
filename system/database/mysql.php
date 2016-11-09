<?php
final class MySQL {

    private $connection;
    protected $error;
    protected $errorNo;
    protected $log;
    protected $hostname;
    protected $username;
    protected $password;
    protected $database;

    public function __construct($hostname, $username, $password, $database) {
        // $this->log = new Log('db_mysql.log');
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }
    public function connect()
    {

        if (!$this->connection = mysql_connect($this->hostname, $this->username, $this->password)) {
            exit('Error: Could not make a database connection using ' . $this->username . '@' . $this->hostname);
        }

        if (!mysql_select_db($this->database, $this->connection)) {
            exit('Error: Could not connect to database ' . $this->database);
        }

        mysql_query("SET NAMES 'utf8'", $this->connection);
        mysql_query("SET CHARACTER SET utf8", $this->connection);
        mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $this->connection);
        mysql_query("SET SQL_MODE = ''", $this->connection);

    }

    protected function multipleQuery($sql) {
        foreach (explode(";\n", $sql) as $tsql) {
            $tsql = trim($tsql);
            if ($tsql) {
                if (!$this->query($tsql))
                    break;
            }
        }
        if ($this->error) {
            return false;
        }
        return true;
    }

    public function query($sql) {
        $cnt = explode(";\n", $sql);
        if (count($cnt) > 1) {
            return $this->multipleQuery($sql);
        }

        $resource = mysql_query($sql, $this->connection);

        if ($resource) {
            if (is_resource($resource)) {
                $i = 0;

                $data = array();

                while ($result = mysql_fetch_assoc($resource)) {
                    $data[$i] = $result;

                    $i++;
                }

                mysql_free_result($resource);

                $query = new stdClass();
                $query->row = isset($data[0]) ? $data[0] : array();
                $query->rows = $data;
                $query->num_rows = $i;

                unset($data);

                return $query;
            } else {
                return TRUE;
            }
        } else {
            $this->error = mysql_error($this->connection);
            $this->errorCode = mysql_errno($this->connection);


            if ($this->errorCode > 0) {
                QS::d(array($sql,$this->error,get_back_trace()),0,0,1);
            }
            return false;
            //exit('Error: ' . mysql_error($this->connection) . '<br />Error No: ' . mysql_errno($this->connection) . '<br />' . $sql);
        }
    }

    public function escape($value) {
        if (is_array($value))
            return array_map(array($this, 'escape'), $value);
        return mysql_real_escape_string($value, $this->connection);
    }

    public function countAffected() {
        return mysql_affected_rows($this->connection);
    }

    public function getLastId() {
        return mysql_insert_id($this->connection);
    }

    public function __destruct() {
        mysql_close($this->connection);
    }

    public function getError() {
        return $this->error;
    }

    public function getColumns($sTable, $sCond="") {
        $arrFields = array();
        if (empty($sTable)) {
            return false;
        }

        $result = $this->query("SHOW TABLES LIKE '" . $sTable . "'");

        if (0 == $result->num_rows) {
            return false;
        }
        $sql = "SHOW COLUMNS FROM " . $sTable;
        if ($sCond) {
            $sql .= " LIKE '{$sCond}%'";
        }

        $result = $this->query($sql);
        if ($result) {
            foreach ($result->rows as $row) {
                $arrFields[] = trim($row['Field']);
            }
        }
        return $arrFields;
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
       if(!($sTable && $aValues))
            return false;
       $sSets = '';
       
       foreach ($aValues as $sCol => $sValue) {
            
            if($aColumns && !in_array($sCol,$aColumns)){
                continue;
            }
            if ($bEscape)
                $sSets .= '`' . $sCol . '` = "' . $this->escape($sValue) . '", ';
            else
                $sSets .= '`' . $sCol . '` = ' . $sValue . ', ';
        }
        $sSets = substr($sSets,0,- 2); //replace trailing ','
        $sSql = 'INSERT INTO `' . $sTable . '` SET ' . $sSets;

        if ($this->query($sSql))
            return $this->getLastID();

        d(array(mysql_error(),$sSql),true);
        return 0;
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
        if (!is_array($aValues))
            return false;
        foreach ($aValues as $aVal) {
            if(!$aColunms)
                $sCols = '`' . implode('`, `', array_keys($aVal)) . '`';
            else
                $sCols = '`' . implode('`, `', $aColunms) . '`';
            if ($bEscape) {
                $aValues = $this->escape($aValues);
                $aArr[] = '"' . implode('","', array_values($aVal)) . '"';
            }
            else
                $aArr[] = implode(',', array_values($aVal));
        }
        $sVals = join('),(', $aArr);
        $sSql = 'INSERT INTO `' . $sTable . '` ' .
                '        (' . $sCols . ')' .
                ' VALUES (' . $sVals . ')';

        if ($this->query($sSql))
            return $this->getLastID();

        return 0;
    }

    /** Performs update of rows.
     * @param string $sTable  table name
     * @param array  $aValues array of column=>new_value
     * @param string $sCond   condition (without WHERE)
     * @param boolean $bEscape true - method escapes values (with "), false - not escapes
     * @return boolean true - update successfule, false - error
     */
    function update($sTable, $aValues, $sCond, $bEscape=true,$aColumns=array()) {
        if (!is_array($aValues))
            return false;

        $sSets = '';
        foreach ($aValues as $sCol => $sValue) {
            if($aColumns && !in_array($sCol,$aColumns)){
                continue;
            }
            if ($bEscape)
                $sSets .= '`' . $sCol . '` = "' . $this->escape($sValue) . '", ';
            else
                $sSets .= '`' . $sCol . '` = ' . $sValue . ', ';
        }
        $sSets = substr($sSets,0,-2); //replace trailing ','
        $sSql = 'UPDATE `' . $sTable . '` SET ' . $sSets . ' WHERE ' . $sCond;
        if(!$ok = $this->query($sSql)){
           // d($sSql,true);
            
        }

        return $ok;
    }

    /** Format date as string for MySQL
     * @param int $timestamp datetime as timestamp (current time if omitted)
     * @return string fomratted datetime
     */
    function date($timestamp = 0) {
        $timestamp = $timestamp ? $timestamp : time();
        return date('Y-m-d H:i:s', $timestamp);
    }

}
?>