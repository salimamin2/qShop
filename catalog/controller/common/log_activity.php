<?php
/* 
 * NOTICE OF LICENSE
 * 
 *  This source file is subject to the Open Software License (OSL 3.0)
 *  that is bundled with this package in the file LICENSE.txt.
 *  It is also available through the world-wide-web at this URL:
 *  http://opensource.org/licenses/osl-3.0.php
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to license@q-sols.com so we can send you a copy immediately.
 * 
 * 
 *  @copyright   Copyright (c) 2010 Q-Solutions. (www.q-sols.com)
 *  @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Description of log_activity
 *
 * @author Qasim Shabbir <qasim@q-sols.com>
 */ 
 

class ControllerCommonLogActivity extends Controller {
    public function index(){
        if(!$this->validate()){
            return;
        }
        $path = DIR_LOGS."sessions/";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        $page .= (!empty($_SERVER['QUERY_STRING'])?"?{$_SERVER['QUERY_STRING']}":"");
        $referrer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        $datetime = date('Y-m-d H:i:s');
        $remotehost = @getHostByAddr($ipaddress);
        $aBrowser = php_get_browser();
        
        $useragent = $aBrowser['browser']." ".$aBrowser['version'];
        $os = $aBrowser['platform'];
        ob_start();
            print_r($_POST);
            $sPost = ob_get_contents();
            print_r($_SESSION);
            $sSession = ob_get_contents();
        ob_end_clean();
        if (isset($this->session->data['customer_id']))
            $customer_id = $this->session->data['customer_id'];
        else
            $customer_id = 0;
        $sPost = $sPost? substr_replace($sPost,"Post",0,5) : '';
        $sSession = $sSession? substr_replace($sSession,"Session",0,5): '';
        $this->load->model('tool/logactivity');
        
        $this->model_tool_logactivity->addSession(array(
            'session_id'=>session_id(),
            'customer_id'=>$customer_id,
            'ip'=>$ipaddress,
            'remote_host'=>$ipaddress. '<' . $remotehost. '>',
            'page'=>$page,
            'referer'=>$referrer,
            'user_agent'=>$useragent,
            'os'=>$os,
            'data'=>$sPost.'|'.$sSession.';',
        ));
        /*
        $sql = 'call sp_add_session("';
        $sql .=  session_id().'",'.$customer_id.',"'.$ipaddress. '<' . $remotehost. '>"';
        $sql .= ',"'.$page.'","'.$referrer.'","'.$useragent.'","'.$os.'","'.$sPost.'|'.$sSession.';")';
        $this->db->query($sql);
         * 
         */
        
    }
    function _addSession($data=array()){
        
    }
    function validate(){
        if(isset($_REQUEST['act']) && $_REQUEST['act']=='payment/payworks/callback'){
            return false;
        }
        $subject = $_SERVER['HTTP_USER_AGENT'];
        $bots = array("'bot'i",
                      "'crawl'i",
                      "'googlebot'i",
                      "'gulper'i",
                      "'jeevestemoa'i",
                      "'linkwalker'i",
                      "'livebot'i",
                      "'msnbot'i",
                      "'slurp'i",
                      "'spider'i",
                      "'validator'i",
                      "'webaltbot'i",
                      "'wget'i"
                );
        if(preg_replace($bots,'',$subject)!=$subject){
            return false;
        }
        return true;
    }
}

?>
