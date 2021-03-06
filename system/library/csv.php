<?php

final class CSV
{

  //returns array of CSV file fields names
  public static function getHeaderFields($db, $file_name, $encoding='utf8', $separator=',', $enclose_char='"', $escape_char='\\')
  {
    
    return self::loadLine($db, $file_name, 1, $encoding, $separator, $enclose_char, $escape_char);
  }

  public static function getExamples($db, $file_name, $encoding='utf8', $separator=',', $enclose_char='"', $escape_char='\\')
  {
    
    return self::loadLine($db, $file_name, 2, $encoding, $separator, $enclose_char, $escape_char);
  }

  public static function getLine($file_name, $line_num=1)
  {
    $line = '';
    $fpointer = fopen($file_name, "r");
    if ($fpointer)
    {
      for($i=1; $i<=$line_num; $i++)
      {
        $line = fgets($fpointer); //get a line which number is equal to $line_num
      }
    }
    return $line;
  }

  public static function loadLine($db, $file_name, $line_num, $encoding='utf8', $separator=',', $enclose_char='"', $escape_char='\\')
  {
    $arrColumns = array();
    $line = self::getLine($file_name, $line_num);
    //remove end_of_line parameter
    $line = str_replace(PHP_EOL, "", $line);
    
    if( !empty($line) )
    {
      if($separator=="\\t")
        $arrColumns = self::csv_explode("\t",$line,$enclose_char);
      else{
        $arrColumns = self::csv_explode($separator,$line,$enclose_char);
      }
    }
    return $arrColumns;
  }
  static function  csv_explode($delim, $str, $enclose='"', $preserve=false){
      $resArr = array();
      $n = 0;
      $expEncArr = explode($enclose, $str);
      
      foreach($expEncArr as $EncItem){
        if($n++%2){
          array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
        }else{
          $expDelArr = explode($delim, $EncItem);
          array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
          $resArr = array_merge($resArr, $expDelArr);
        }
      }
      return $resArr;
  }

  

  //gets count of each possible CSV separator
  //returns most appropriative separator (by max count in the string)
  public static function trySeparators($line, $default=null)
  {
    $csv_separators = array(1=>",", ";", "|", "\\", "/", "#", "!", "*", "-");
    $max_similar = 0;
    $index       = 0;
    foreach($csv_separators as $i=>$sep)
    {
      $count = substr_count($line, $sep);
      if($max_similar<$count)
      {
        $max_similar=$count;
        $index = $i;
      }
    }
    if($index>0)
    {
      return $csv_separators[$index];
    }
    elseif( !empty( $default ) )
    {
      return $default;
    }
    
    return null;
  }
  private static function error($error, $str, $m, $offset, $moving_offset, $csv_line) {
    if ($error === "unexpected quote in unquoted field") {
      $raw = _("Found an unexpected quote character in field %d of csv line %d (text line %d).".
               " The first 50 chars from the start of the field are '%s'.");
    } else if ($error === "unexpected quote in quoted field") {
      $raw = _("Found an unescaped quote in quoted field %d of csv line %d (text line %d).".
               " The first 50 chars from the start of the field are '%s'.");
    } else if ($error === "unexpected text after end quote in quoted field") {
      $raw = _("Unexpected test after end quote in quoted field %d of csv line %d (text line %d).".
               " The first 50 chars from the start of the field are '%s'.");
    } else {
      die("impossible");
    }

    $t = sprintf($raw,
                 sizeof($m[$csv_line])+1,
                 $csv_line,
                 sizeof(explode("\n", substr($str, 0, $offset))),
                 addcslashes(substr($str, $offset, 50), "\t\r\n'\\")
                 );
    throw new Exception($t);
  }

  /* returns
   * array(start_line1 => Array(field1, field2,...),
   *       start_line2 =>
   *       ...);
   *
   * Faily speed-important, therefore:
   * -With few function calls.
   * -Never copy the entire (potentially MB-long) string
   * -Never operate on the whole string (so therefore we use no pregs)
   *
   * This implementation parses a 1MB csv file in under a second,
   * which is obviously slow, but should be fast enough. My first try
   * was using regexp_max with regexp's offset parameter, which failed
   * horribly speed-wise.
   *
   * The basic idea is to eat one char at a time, and append fields
   * and lines to the matrix as we encounter separators and newlines.
   *
   * Not using PHP's fgetcsvdue to http://bugs.php.net/bug.php?id=50686
   */
  public static function parse($str, $sep=',') {
    if ($str === "") {
      return Array();
    }

    $str .= "\r\n";
    $offset = 0;
    $item = "";
    $len = strlen($str);
    $csv_line = 1;
    $m = Array($csv_line => Array());
    $force_empty_field = false;

    $moving_offset = 0;
    while ($moving_offset < $len) {
      $c = $str[$moving_offset];
      if ($c === $sep) {
        //separator
        $m[$csv_line][] = $item;
        $item = "";
        $force_empty_field = true;
        $offset = ++$moving_offset;
      } else if ($c === "\n") {
        //newline
        if ($str[$moving_offset -1] === "\r") {
          //The \r belonged to the newline
          $item = substr($item, 0, -1);
        }
        if ($item !== "" || $force_empty_field) {
          $m[$csv_line][] = $item;
          $item = "";
        }
        $offset = ++$moving_offset;

        //end of string
        if ($offset === $len) {
          return $m;
        }

        $m[++$csv_line] = Array();
        $force_empty_field = false;
      } else if ($c === '"') {
        //quoted item

        if ($item !== "") {
          //throws exception
          self::error("unexpected quote in unquoted field",
                      $str, $m, $offset, $moving_offset, $csv_line);
        }

        //eat quote
        $moving_offset++;

        //read until end quote
        while (true) {
          $c = $str[$moving_offset];

          if ($moving_offset >= $len) {
            //throws exception
            self::error("unexpected quote in quoted field",
                        $str, $m, $offset, $moving_offset, $csv_line);
          }
          if ($c === '"') {
            if ($str[$moving_offset+1] === '"') {
              //escaped quote
              $item .= '"';
              $moving_offset += 2; //eat doubled quotes
            } else {
              //end of item
              $moving_offset++; //eat end quote
              break;
            }
          } else {
            $item .= $c;
            $moving_offset++;
          }
        }

        //eat separator
        if ($str[$moving_offset] === $sep) {
          $force_empty_field = true;
          $moving_offset++;
        } else if ( ($str[$moving_offset] === "\r" && $str[$moving_offset+1] === "\n")
                    || $str[$moving_offset] === "\n") {
          $force_empty_field = false;
        } else {
          self::error("unexpected text after end quote in quoted field",
                      $str, $m, $offset, $moving_offset, $csv_line);
        }

        //add field and reset for next field
        $m[$csv_line][] = $item;
        $item = "";
        $offset = $moving_offset;
      } else {
        $moving_offset++;
        $item .= $c;
      }
    }

    die("impossible, since the last char is a \n, and the newline handling should catch that");
  }

  public static function matrix_to_csv(Array $matrix, $sep=',') {
    $str = "";
    foreach ($matrix as $row) {
      if ($str !== "") {
        $str .= "\r\n"; //not just \n, according to RFC 4180
      }

      $row = array_map(Array(__CLASS__, "escape"), $row);
      $row_str = implode($sep, $row);
      $str .= $row_str;
    }

    return $str;
  }
}