<?php
final class Log {
	private $filename;
	
	public function __construct($filename) {
		$this->filename = $filename;
	}
	
	public function write($message) {
		$file = DIR_LOGS . $this->filename;
		$message = $this->_format($message);
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
			
		fclose($handle); 
	}
	
	public function write_payment($message, $filename = "log"){
		$file = DIR_LOGS . $filename.'-' . date('Ymd').'.log';
		$message = $this->_format($message);
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
			
		fclose($handle); 
	}
        private function _format($value){
            $sReturn = $value;
            if(is_object($value) || is_array($value)){
                ob_start();
                    print_r($value);
                    $sReturn = ob_get_contents();
                ob_end_clean();
            }
            return $sReturn;
        }
}
?>