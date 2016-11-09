<?php 
class ModelPaymentHBL extends Model {
  	public function getMethod($address) {
		$this->load->language('payment/hbl');
		
		if ($this->config->get('hbl_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('twocheckout_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$this->config->get('hbl_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
      		  	$status = TRUE;
      		} else {
     	  		$status = FALSE;
			}	
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'hbl',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('hbl_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
        public function debug($sDebug,$id=false){
            if(!$id){
                $sSql = "INSERT INTO hbl_api_debug SET ";
                $sSql.= " `request_body` = '".mysql_escape_string($sDebug)."'";
            }else{
                $sSql = "UPDATE hbl_api_debug SET ";
                $sSql.= " `response_body` = '".mysql_escape_string($sDebug)."'";
                $sSql.= " WHERE debug_id='{$id}'";
            }
            $this->db->query($sSql);
            return  $this->db->getLastId();
        }
}
?>