<?php

include_once 'RestAction.php';
include_once 'RestController.php';
include_once 'RestView.php';
include_once 'RestRequest.php';
include_once 'RestResponse.php';

/**
 * Class RestServer
 * Is the front controller for mapping URL to controllers and dealing with Request/Response and Headers
 * Made with Restful webservices in mind.
 * By Diogo Souza da Silva <manifesto@manifesto.blog.br>
 */
class RestServer {

    private $auth = true;
    private $requireAuth = false;
    private $response;
    private $request;
    private $baseUrl;
    private $query;
    private $qPart;
    private $map;
    private $ignoreMap;
    private $params;
    private $stack;
    protected $registry;

    /** Contructor of RestServer
     * @param string $query Optional query to be treat as the URL
     * @return RestServer $rest;
     */
    public function __construct($registry,$query=null) {
        $this->registry = $registry;
        $this->request = new RestRequest($this); // Request handler
        $this->response = new RestResponse($this); // Response holder
        
        if (isset($_SERVER["HTTP_HOST"]))
            $this->baseUrl = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]);

        // If will use custom URI or HTTP requested URI
        if ($query === null)
            $this->query = $this->getRequest()->getRequestURI();
        else
            $this->query = $query;
        
        $this->getRequest()->setURI($this->query);

        $this->qPart = explode("/", $this->query);
    }
    public function setIgnoreMap($aMap){
        $this->ignoreMap=$aMap;
        return $this;
    }
    /**
     * Sets a parameter in a global scope that can be recovered at any request.
     * @param mixed $key The identifier of the parameter
     * @param mixed $value The content of the parameter
     * @return RestServer $this
     */
    public function setParameter($key, $value) {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Return the specified parameter
     * @param mixed $key The parameter identifier
     */
    public function getParameter($key) {
        return $this->params[$key];
    }

    /**
     * Set the URL to be handle or part of it
     * @param mixed $value The url
     * @param int $key Optional, the part of the url to change
     * @return RestServer $this
     */
    public function setQuery($value, $k=null) {
        if ($k !== null) {
            $this->qPart[$k] = $value;
        } else {
            $this->query = $value;
            $this->qPart = explode("/", $value);
        }
        return $this;
    }

    /**
     * Maps a Method and URL for a Class
     * @param string $method The method to be associated
     * @param string $uri The URL to be accossiated
     * @param string $class The name of the class to be called, it must implement RestAction
     * @return RestServer $this
     */
    public function addMap($method, $uri, $class) {
        $this->map[$method][$uri] = $class;
        return $this;
    }

    /**
     * Checks if is authenticated
     * @return boolean $auth;
     */
    public function isAuth() {
        return $this->auth;
    }

    /**
     * Sets authentication status
     * @param boolean $auth Status
     * @return RestServer
     */
    public function setAuth($bool) {
        $this->auth = $bool;
        return $this;
    }

    /**
     * Get the URL or part of it, depreciated by RestRequest::getURI();
     * */
    public function getQuery($k=null) {
        if ($k !== null) {
            return $this->qPart[$k];
        }
        
        return $this->query;
    }

    /**
     * Get the baseurl, based on website location (eg. localhost/website or website.com/);
     * @return string;
     * */
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     * Get the Response handler object
     * @return RestResponse
     */
    public function getResponse() {
        return $this->response;
    }

    /** Get the Request handler object
     * @return RestRequest
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Get the class for specified method and uri
     * @param string $method
     * @param string $uri
     * @return string
     */
    public function getMap($method, $uri) {
       
        $maps = $this->map[$method];
        if (count($maps) < 1)
            return false;
        foreach ($maps as $map => $class) {
            
            if (preg_match("%^" . $map . "$%", $uri)) {
                return $class;
            }
        }
        return false;
    }



    /**
     * Unauthorize the request
     * $return RestServer
     */
    public function unAuth() {
        $session = $this->registry->get('session');
        $aData = $this->request->getPost();
        $id = $aData['key'];
        if($session->validate($id)){
            $session->init($id);
            $this->auth=true;
            return true;
        }
        $this->response->setStatus(401);
        $this->auth=false;
        return false;
    }

    private function testAuth() {
        if ($this->requireAuth === false)
            return true;
        foreach($this->ignoreMap as $map){
            if (preg_match("%^" . $map . "$%", $this->query)) {
                return true;
            }
        }
        if (!$this->auth) {
            return $this->unAuth();
        }
        return true;
    }
    

    /**
     * Sets if authentication is required
     * @param boolean $isRequered Status to request, if null is given nothing changes
     * @return boolean if is required;
     */
    public function requireAuth($bol=null) {
        if ($bol !== null)
            $this->requireAuth = $bol;
        return $this->requireAuth;
    }

    /**
     * Return last class name from RestServer stack trace
     * return string
     */
    public function lastClass() {
        $i = count($this->stack);
        return $this->stack[$i - 1];
    }

    /**
     * Run the Server to handle the request and prepare the response
     * @return string $responseContent
     */
    public function execute() {
        
        if (!$this->testAuth())
            return $this->show(); // If auth is required and its not ok, response is 401
            // This is the class name to call
        
        $responseClass = $this->getMap($this->getRequest()->getMethod(), $this->getQuery());
        

        if (!$responseClass) { // If no class was found, response is 404
            $this->getResponse()->cleanHeader();
            $this->getResponse()->addHeader("HTTP/1.1 404 NOT FOUND");
            $this->getResponse()->setResponse("HTTP/1.1 404 NOT FOUND");
            return $this->show();
        }

        // In case a specific method should be called
        $parts = explode("::", $responseClass);
        if (isset($parts[0]))
            $oReponse = new $parts[0]($this->registry);
        else
            $oReponse = null;

        if (isset($parts[1]))
            $responseMethod = $parts[1];
        else
            $responseMethod = null;

        $this->call($oReponse, $responseMethod); // Call the class

        return $this->show(); // Return response content
    }

    private function call($class, $method=null) {
        $this->stack[] = get_class($class);
        if ($class instanceof RestView) { // If is a view, call Show($restServer)
            if ($method == null)
                $method = "show";
            $class = $class->$method($this);
        } else if ($class instanceof RestController) {  //If is a controller, call execute($restServer)
            if ($method == null)
                $method = "execute";
            $class = $class->$method($this);
        }

        if ($class instanceof RestAction
                && get_class($class) != $this->lastClass()) {
            return $this->call($class); // May have another class to follow the request
        }

        return $this;
    }

    private function show() {
        $this->testAuth(); // Test authentication
        
        if (!$this->getResponse()->headerSent()) {
            $this->getResponse()->showHeader(); // Call headers, if no yet
        }

        return $this->getResponse()->getResponse(); // Return response content;
    }
}
?>