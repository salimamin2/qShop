<?php
class ModelExport extends Model
{
    public $error;
    function __construct($registry){
       parent::__construct($registry);
    }
    public function csv_from_mysql_resource($resource)
    {
        $filename = DIR_UPLOAD_DATA."customer\export.csv";
        if(is_file($filename)){
            unlink($filename);
        }
        if (file_exists($filename)):
            if(!is_writable($filename)):
                return "The file: $filename is not writable";
            endif;
        elseif( !is_writable( getcwd() ) ):
            return "you cannot create files in this directory.  Check the permissions";
        endif;

        //open the file for APPENDING
        //add the "t" terminator for windows
        //if using a mac then set the ini directive
        $fh = fopen($filename, "at");
        //Lock the file for the write operation
        flock($fh, LOCK_EX);


            $output = "";
            $headers_printed = false;

            foreach($resource as $row)
            {
                    // print out column names as the first row
                    if(!$headers_printed)
                    {
                            $output .= join(',', array_keys($row)) ."\n";
                            $headers_printed = true;
                    }

                    // remove newlines from all the fields and
                    // surround them with quote marks
                    foreach ($row as &$value)
                    {
                            $value = str_replace("\r\n", "", $value);
                            $value = "\"" . $value . "\"";
                    }

                    $output .= join(',', $row)."\n";

            }

            fwrite($fh, $output ."\n",strlen($output));
            //close the file handle outside the loop
            //this releases the lock too
            fclose($fh);
            // send output
           return 1;
    }

}


?>
