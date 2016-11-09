<?php
class ModelImport extends Model
{
    public $error=array();
    function __construct($registry){
       parent::__construct($registry);
       $this->load->library('csvimport');
    }
    public function getColumns($table){
        return $this->db->driver->getColumns($table);
    }
    public function getCsvHeader($data)
    {
        $arHeaders = CSV::getHeaderFields( $this->db, $data['file_name'], $data['encoding'], $data['field_separate_char'], $data['field_enclose_char'], $data['field_escape_char'] );
        if( empty( $arHeaders ) ) {
            exit( 'Cannot retrieve headers columns of the CSV file' );
        }
        return $arHeaders;
    }
    public function getCsvExample($data){
        $arExamples = CSV::getExamples( $this->db, $data['file_name'], $data['encoding'], $data['field_separate_char'], $data['field_enclose_char'], $data['field_escape_char'] );
        if( empty( $arExamples ) ) {
            exit( 'Cannot retrieve example data of the CSV file (first data line)' );
        }
        return $arExamples;
    }
    protected function detectEncoding( $str ) {
        // auto detect the character encoding of a string
        return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
    }
    public function getEncodings(){
        $fQuickCSV = new QuickCsvImport($this->db);
        return $fQuickCSV->getEncodings();
    }
    public function import($data){
        if(!file_exists($data['file_name'])){
            $this->error[] = "Nothing to import. File not found";
            return false;
        }
        $fQuickCSV = new QuickCsvImport($this->db);

        if(isset($data['line_seperator']))
            $fQuickCSV->line_separate_char = $data['line_seperator'];

        if(isset($data['table_name'])){
            $fQuickCSV->table_name = $data['table_name'];
        }
        else
            $fQuickCSV->table_name = sprintf("temp_%s_%d", date("d_m_Y_H_i_s"), rand(1, 100));

        if(isset($data['use_header']))
            $fQuickCSV->use_csv_header = $data['use_header'];
        else
            $fQuickCSV->use_csv_header = false;

        $fQuickCSV->file_name = $data['file_name']; //$file_name;


        $fQuickCSV->make_temporary = false;
        $fQuickCSV->table_exists = true;
        if(isset($data['separator']))
            $fQuickCSV->field_separate_char = $data['separator'];
        else
            $fQuickCSV->field_separate_char = ',';

        if(isset($data['encoding']))
            $fQuickCSV->encoding = $data['encoding'];
        else
            $fQuickCSV->encoding = 'utf8';

        if(isset($data['enclose_char']))
            $fQuickCSV->field_enclose_char = $data['enclose_char'];
        else
            $fQuickCSV->field_enclose_char = '"';
        if(isset($data['escape_char']))
            $fQuickCSV->field_escape_char = $data['escape_char'];
        else
            $fQuickCSV->field_escape_char = '\\';

        if(isset($data['columns'])){
            $fQuickCSV->arr_csv_columns = $data['columns'];
        }
        d($data);
        $fQuickCSV->import();

        if( !empty($fQuickCSV->error) ) {
            $this->error[] = $fQuickCSV->error;
        }

        if( 0 == $fQuickCSV->rows_count )
        {
            $this->error[] = 'Imported rows count is 0.';
        }
       return (count($this->error)==0);
    }

}


?>
