<?php

final class IMySQL {

	private $link;
    protected $log;
    protected $error;
    protected $errorNo;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		$this->log = new Log('db_mysqli.log');
		$this->link = new \mysqli($hostname, $username, $password, $database, $port);

		if ($this->link->connect_error) {
			trigger_error('Error: Could not make a database link (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
			exit();
		}

		$this->link->set_charset("utf8");
		$this->link->query("SET SQL_MODE = ''");
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

		$query = $this->link->query($sql);

		if (!$this->link->errno) {
			if ($query instanceof \mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;

				$query->close();

				return $result;
			} else {
				return true;
			}
		} else {
			$this->error = $this->link->error;
            $this->errorCode = $this->link->errno;


            if ($this->errorCode > 0) {
                $this->log->write($sql);
                $this->log->write($this->error);
                $this->log->write(get_back_trace());
            }
            return false;
			//trigger_error('Error: ' . $this->link->error  . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
		}
	}

	public function escape($value) {
		return $this->link->real_escape_string($value);
	}

	public function countAffected() {
		return $this->link->affected_rows;
	}

	public function getError() {
        return $this->error;
    }

	public function getLastId() {
		return $this->link->insert_id;
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
            return $this->getLastId();

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
            return $this->getLastId();

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

	public function __destruct() {
		$this->link->close();
	}
}