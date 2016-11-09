<?php

/**
 * RestResponse hold the response in a RestServer
 */
class RestResponse {

    private $rest;
    private $headers;
    private $response;
    protected $status;
    protected $contentType;
    protected $codes;

    /**
     * Constructor of RestServer
     * @param RestServer $rest
     */
    function __construct($rest=null) {
        $this->rest = $rest;
        $this->status = 200;
        $this->contentType = 'application/xml';
        
        $this->codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );
    }

    function setStatus($status) {
        if (in_array($status, array_keys($this->codes))) {
            $this->status = $status;
        } else {
            $this->status = 505;
        }
    }

    function setContentType($contentType) {
        $this->contentType = $contentType;
    }

    /**
     * Set the response to null
     * @return RestResponse
     */
    public function cleanResponse() {
        $this->response = null;
        return $this;
    }

    /**
     * Adds a header to the response
     * @param string $header
     * @return RestResponse
     */
    public function addHeader($header) {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * Clean the headers set on the response
     * @return RestResponse
     */
    public function cleanHeader() {
        $this->headers = Array();
        return $this;
    }

    /**
     * Show the headers
     */
    public function showHeader() {
        if (count($this->headers) >= 1) {
            foreach ($this->headers as $value) {
                header($value);
            }
        } else {
            
            $status_header = 'HTTP/1.1 ' . $this->status . ' ' . $this->getStatusCodeMessage();
            // set the status
            header($status_header);
            // set the content type
            header('Content-Type: ' . $this->contentType);
        }
        return $this;
    }

    /**
     * Check if headers were sent
     * @return bool
     */
    public function headerSent() {
        return headers_sent();
    }

    /**
     * Set the response
     * @param mixed $response
     * @return RestResponse
     */
    public function setResponse($response) {
        $this->response = $response;
        return $this;
    }

    /**
     * Add a string to the response, only work if response is a string
     * @param string $response
     * @return RestResponse
     */
    public function addResponse($response) {
        $this->response .= $response;
        return $this;
    }

    /**
     * Return the reponse set
     * @return mixed $response;
     */
    public function getResponse() {
        if (!empty($this->response))
            return $this->response;
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($this->status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templatized in a real-world solution
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                    <html>
                            <head>
                                    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                                    <title>' . $this->status . ' ' . $this->getStatusCodeMessage($this->status) . '</title>
                            </head>
                            <body>
                                    <h1>' . $this->getStatusCodeMessage($this->status) . '</h1>
                                    <p>' . $message . '</p>
                                    <hr />
                                    <address>' . $signature . '</address>
                            </body>
                    </html>';

            return $body;
        }
    }

    public function getStatusCodeMessage($status="") {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        if(empty($status))
            $status = $this->status;

        return (isset($this->codes[$status])) ? $this->codes[$status] : '';
    }

    public function setError($sMsg){
        $this->setStatus(404);
        $this->setContentType('text/html');
        $this->setResponse($sMsg);
    }

}
?>