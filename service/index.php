<?php
require_once('../config.php');
require_once('../init_app.php');
//setting up user for session working
$registry->set('user', new User($registry));
/** Sample aplication showing how to route with RestServer */
$_GET["uri"] = isset($_GET["uri"])?$_GET["uri"]:$_POST["uri"];
$server = new RestServer($registry,$_GET["uri"]);// Using a parameter as we won't have url rewrite here
$server->requireAuth(true);
$server->setAuth(false);
$server->setIgnoreMap(
        array(
          '/login'
        )
);

/**
* Follows the method addMap(METHOD,URL,CONTROLLER)
* METHOD area the http methods like GET, POST, DELETE, PUT, OPTIONS...
* URL is a regular expression pearl compatible for the url pattern
* CONTROLLER is the RestController class to deal with the url, 
* may specify the method to call, or execute will be called.
*/
$aMaps=ArrayToXML::toArray(simplexml_load_file('rest_mapping.xml'));
$aMaps = $aMaps['map'];
foreach($aMaps as $aMap){
    $server->addMap($aMap['method'],$aMap['uri'],$aMap['action']);
}

echo $server->execute();
?>
