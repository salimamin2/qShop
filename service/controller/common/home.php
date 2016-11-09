<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of login
 *
 * @author qasim
 */
class Service_Controller_Common_Home extends Controller  implements RestController{
    //put your code here

    public function execute(RestServer $server)
    {
            return $server;
    }
    public function login(RestServer $server){
        $aData = $server->getRequest()->getPost();
        
        if(!$aData)
        {
            //required data is missing
            $server->getResponse()->setStatus(400);
            return $server;
        }

        if(!$this->validate($aData)){
            //invalid credentails
            $server->getResponse()->setStatus(401);
            return $server;
        }
        //successfully logged in
        $aResponse =array ('key'=>session_id());
        $server->getResponse()->setResponse(ArrayToXML::toXML($aResponse));
        return $server;
    }
    public function permission() {
        if (isset($this->request->get['act'])) {
            $route = '';

            $part = explode('/', $this->request->get['act']);

            if (isset($part[0])) {
                $route .= $part[0];
            }

            if (isset($part[1])) {
                $route .= '/' . $part[1];
            }

            $ignore = array(
                    'common/home',
                    'common/login',
                    'common/logout',
                    'error/not_found',
                    'error/permission',
                    'error/token'
            );
            if (!in_array($route, $ignore)) {
                if (!$this->user->hasPermission('access', $route)) {
                    return $this->forward('error/permission');
                }
            }
        }
    }
    private function validate($aData) {
        if (isset($aData['username']) && isset($aData['password']) && !$this->user->login($aData['username'], $aData['password'])) {
            return false;
        }
        return true;
    }
}
?>
