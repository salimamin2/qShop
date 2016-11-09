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
 * Description of QS
 *
 * @author Qasim Shabbir <qasim@q-sols.com>
 */
final class QS {

    //shortcut helpers

    public static function app() {
        return Registry :: getInstance();
    }

    public static function getModel($model) {
        return self :: app()->load->model($model);
    }

    public static function importModel($model) {
        return self :: getModel($model);
    }

    public static function importLibrary($library) {
        return self :: app()->load->library($library);
    }

    public static function importHelper($library) {
        return self :: app()->load->helper($library);
    }

    /*
     * import("package.ClassName");
     * import("another.package.*"); //this will import everything in the folder
     */

    function import($path = "") {
        if ($path == "") {
            //no parameter returns the file import info tree;
            $report = $_SESSION['imports'];
            foreach ($report as & $item)
                $item = array_flip($item);
            return $report;
        }
        $current = str_replace("\\", "/", getcwd()) . "/";
        $path = $current . str_replace(".", "/", $path);
        if (substr($path, - 1) != "*")
            $path .= ".class.php";
        $imports = & $_SESSION['imports'];
        if (!is_array($imports))
            $imports = array();
        $control = & $imports[$_SERVER['SCRIPT_FILENAME']];
        if (!is_array($control))
            $control = array();
        foreach (glob($path) as $file) {
            $file = str_replace($current, "", $file);
            if (is_dir($file))
                import($file . ".*");
            if (substr($file, - 10) != ".class.php")
                continue;
            if ($control[$file])
                continue;
            $control[$file] = count($control);
            require_once ( $file );
        }
    }

    public static function getConfig($key) {
        return QS :: app()->config->get($key);
    }

    public static function formatDate($date, $format = null) {
        if ($date && strtotime($date) > 0) {
            if (is_null($format))
                $format = QS :: getConfig('config_date_format') ? QS :: getConfig('config_date_format') : 'm/d/Y';
            if (strstr($date, ' ') && strstr($date, ',')) {
                list($d, $mY) = explode(' ', $date);
                list($month, $y) = explode(',', $mY);
                $m = date('m', strtotime($date));
                $date = $y . '-' . $m . '-' . $d;
            }
            $oDate = new DateTime($date);
            //d(array($oDate,$date,$format));
            $date = $oDate->format($format);
            //d($date,true);
            return $date;
        }
        return '';
    }

    public static function formatPHPDate($date, $format = null) {
        if ($date && strtotime($date) > 0) {
            if (is_null($format))
                $format = QS :: getConfig('config_date_format_php') ? QS :: getConfig('config_date_format_php') : 'd-m-Y';
            if (strstr($date, ' ') && strstr($date, ',')) {
                list($d, $mY) = explode(' ', $date);
                list($month, $y) = explode(',', $mY);
                $m = date('m', strtotime($date));
                $date = $y . '-' . $m . '-' . $d;
            }
            $oDate = new DateTime($date);
            $date = $oDate->format($format);
            return $date;
        }
        return '';
    }

    public static function formatSQLDate($date) {
        return self :: formatDate($date, 'Y-m-d');
    }

    public static function formatSQLDateTime($date) {
        return self :: formatDate($date, 'Y-m-d H:i:s');
    }

    // ------------------------------------------------------------------------

    /**
     * Tests for file writability
     *
     * is_writable() returns TRUE on Windows servers when you really can't write to
     * the file, based on the read-only attribute.  is_writable() is also unreliable
     * on Unix servers if safe_mode is on.
     *
     * @access	private
     * @return	void
     */
    public static function is_really_writable($file) {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR == '/' AND @ ini_get("safe_mode") == FALSE) {
            return is_writable($file);
        }
        // For windows servers and safe_mode "on" installations we'll actually
        // write a file then read it.  Bah...
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand(1, 100) . mt_rand(1, 100));
            if (( $fp = @ fopen($file, FOPEN_WRITE_CREATE)) === FALSE) {
                return FALSE;
            }
            fclose($fp);
            @ chmod($file, DIR_WRITE_MODE);
            @ unlink($file);
            return TRUE;
        } elseif (( $fp = @ fopen($file, FOPEN_WRITE_CREATE)) === FALSE) {
            return FALSE;
        }
        fclose($fp);
        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * Loads the main config.php file
     *
     * This function lets us grab the config file even if the Config class
     * hasn't been instantiated yet
     *
     * @access	private
     * @return	array
     */
    public static function get_config($replace = array()) {
        return Registry :: getInstance()->config;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the specified config item
     *
     * @access	public
     * @return	mixed
     */
    public static function config_item($item) {
        $config = $this->get_config();
        if (!isset($config[$item])) {
            return FALSE;
        }
        return $config[$item];
    }

    // ------------------------------------------------------------------------
    // ------------------------------------------------------------------------

    /**
     * Error Logging Interface
     *
     * We use this as a simple mechanism to access the logging
     * class and send messages to be logged.
     *
     * @access	public
     * @return	void
     */
    public static function log_message($level = 'error', $message, $php_error = FALSE) {
        $_log = Registry :: getInstance()->log;
        $_log->write_log($level, $message, $php_error);
    }

    // ------------------------------------------------------------------------

    /**
     * Set HTTP Status Header
     *
     * @access	public
     * @param	int		the status code
     * @param	string
     * @return	void
     */
    public static function set_status_header($code = 200, $text = '') {
        $stati = array(200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 304 => 'Not Modified', 305 => 'Use Proxy', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported');
        if ($code == '' OR ! is_numeric($code)) {
            show_error('Status codes must be numeric', 500);
        }
        if (isset($stati[$code]) AND $text == '') {
            $text = $stati[$code];
        }
        if ($text == '') {
            show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
        }
        $server_protocol = ( isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;
        if (substr(php_sapi_name(), 0, 3) == 'cgi') {
            header("Status: {$code} {$text}", TRUE);
        } elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0') {
            header($server_protocol . " {$code} {$text}", TRUE, $code);
        } else {
            header("HTTP/1.1 {$code} {$text}", TRUE, $code);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Remove Invisible Characters
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public static function remove_invisible_characters($str) {
        static $non_displayables;
        if (!isset($non_displayables)) {
            // every control character except newline (dec 10), carriage return (dec 13), and horizontal tab (dec 09),
            $non_displayables = array('/%0[0-8bcef]/',
                // url encoded 00-08, 11, 12, 14, 15
                '/%1[0-9a-f]/',
                // url encoded 16-31
                '/[\x00-\x08]/',
                // 00-08
                '/\x0b/', '/\x0c/',
                // 11, 12
                '/[\x0e-\x1f]/'
                    // 14-31
            );
        }
        do {
            $cleaned = $str;
            $str = preg_replace($non_displayables, '', $str);
        } while ($cleaned != $str);
        return $str;
    }

    //for debuging

    public static function d($mParam, $bExit = 0, $bVarDump = 0, $echoInFile = 0) {
        ob_start();
        print self :: get_back_trace("\n");
        if (!$bVarDump) {
            print_r($mParam);
        } else {
            var_dump($mParam);
        }
        $sStr = htmlspecialchars(ob_get_contents());
        ob_clean();
        if ($echoInFile) {
            file_put_contents(DIR_LOGS . 'd_' . date('d-m-Y') . '.log', $sStr, FILE_APPEND);
        } else {
            echo '<hr><pre>';
            echo $sStr;
            echo '</pre><hr>';
        }
        if ($bExit)
            exit;
    }

    public static function get_back_trace($NL = "\n") {
        $dbgTrace = debug_backtrace();
        $dbgMsg = "Trace[";
        foreach ($dbgTrace as $dbgIndex => $dbgInfo) {
            if ($dbgIndex > 0 && isset($dbgInfo['file'])) {
                $dbgMsg .= "\t at $dbgIndex  " . $dbgInfo['file'] . " (line {$dbgInfo['line']}) -> {$dbgInfo['function']}(" . count($dbgInfo['args']) . ")$NL";
            }
        }
        $dbgMsg .= "]" . $NL;
        return $dbgMsg;
    }

    /**
     * Convert a string to camel case, optionally capitalizing the first char and optionally setting which characters are
     * acceptable.
     *
     * First, take existing camel case and add a space between each word so that it is in Title Form; note that
     *   consecutive capitals (acronyms) are considered a single word.
     * Second, capture all contigious words, capitalize the first letter and then convert the rest into lower case.
     * Third, strip out all the non-desirable characters (i.e, non numerics).
     *
     * EXAMPLES:
     * $str = 'Please_RSVP: b4 you-all arrive!';
     *
     * To convert a string to camel case:
     *  strtocamel($str); // gives: PleaseRsvpB4YouAllArrive
     *
     * To convert a string to an acronym:
     *  strtocamel($str, true, 'A-Z'); // gives: PRBYAA
     *
     * To convert a string to first-lower camel case without numerics but with underscores:
     *  strtocamel($str, false, 'A-Za-z_'); // gives: please_RsvpBYouAllArrive
     *
     * @param  string  $str              text to convert to camel case.
     * @param  bool    $capitalizeFirst  optional. whether to capitalize the first chare (e.g. "camelCase" vs. "CamelCase").
     * @param  string  $allowed          optional. regex of the chars to allow in the final string
     *
     * @return string camel cased result
     *
     * @author Sean P. O. MacCath-Moran   www.emanaton.com
     */
    public static function strtocamel($str, $capitalizeFirst = true, $allowed = 'A-Za-z0-9') {
        /*return preg_replace(
                array(
            '/([A-Z][a-z])/e', // all occurances of caps followed by lowers
            '/([a-zA-Z])([a-zA-Z]*)/e', // all occurances of words w/ first char captured separately
            '/[^' . $allowed . ']+/e', // all non allowed chars (non alpha numerics, by default)
            '/^([a-zA-Z])/e' // first alpha char
                ), array(
            '" ".$1', // add spaces
            'strtoupper("$1").strtolower("$2")', // capitalize first, lower the rest
            '', // delete undesired chars
            'strto' . ($capitalizeFirst ? 'upper' : 'lower') . '("$1")' // force first char to upper or lower
                ), $str
        );*/
        $sRefine = preg_replace_callback('/([A-Z][a-z])/', function($match){ return " ".$match[1]; }, $str);
        $sRefine = preg_replace_callback('/([a-zA-Z])([a-zA-Z]*)/', function($match){ return strtoupper($match[1]).strtolower($match[2]); }, $sRefine);
        $sRefine = preg_replace_callback('/[^' . $allowed . ']+/', function($match){ return ""; }, $sRefine);
        $sRefine = preg_replace_callback('/^([a-zA-Z])/m', function($match) use ($capitalizeFirst){ return ($capitalizeFirst ?strtoupper($match[1]) : strtolower($match[1])); }, $sRefine);
        return $sRefine;
    }

    public static function makeUrl($url, $params = array(), $bPermit = false, $ssl = false) {
        $http = '';
        if (!$bPermit) {
            $db = Registry::getInstance()->get('db');
            $user = Registry::getInstance()->get('user');
            if ($user)
                $aPermission = $user->getPermission();
            $bUrl = false;
            $sAction = '';
            $ignore = array('common/home', 'common/home_user', 'common/layout', 'common/login', 'common/logout', 'error/not_found', 'error/permission', 'common/footer', 'common/header', 'common/menu');

            $aUrl = explode('/', $url);
            if (count($aUrl) == 3) {
                $sAction = array_pop($aUrl);
                $sController = join('/', $aUrl);
            } elseif (count($aUrl) == 2) {
                $sController = $url;
            }

            if (!in_array($sController, $ignore)) {
                if (in_array($sController, $aPermission['access'])) {

                    if ($sController != 'user/user' && $sAction && !in_array($sController, $aPermission['modify'])) {
                        $bUrl = false;
                    } else {
                        $bUrl = true;
                    }
                }
            } else {
                $bUrl = true;
            }
            $http = HTTPS_SERVER;
            if ($bUrl || !$url) {
                $http = HTTPS_SERVER;
            } else {
                $url = 'error/permission';
            }
        } else {
            if (!stristr($_SERVER['SCRIPT_NAME'], 'nshopadmin')) {
                Registry::getInstance()->get('load')->model("tool/seo_url");
                if ($params && is_array($params)) {
                    $url .= '?' . join('&', $params);
                } else if (is_string($params)) {
                    $url = $url . '?' . $params;
                }
                if ($ssl) {
                    $href = Registry::getInstance()->get('load')->model_tool_seo_url->rewrite(HTTPS_SERVER . $url);
                } else {
                    $href = Registry::getInstance()->get('load')->model_tool_seo_url->rewrite(HTTP_SERVER . $url);
                }
                return $href;
            } else {
                if ($ssl) {
                    $http = HTTPS_SERVER;
                } else {
                    $http = HTTP_SERVER;
                }
            }
        }
        if ($params && is_array($params)) {
            $url .= '?' . join('&', $params);
        } else if (is_string($params)) {
            $url = $url . '?' . $params;
        }
        $href = $http . $url;
        return $href;
    }

    /**
     * Delete all files in dir and remove dir
     *
     * @Param string $dir  path of dir
     *
     */
    public static function removeDir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public static function __($sWord, $sModule = '') {
        $lang = Registry :: getInstance()->get('language');
        if ($lang->get($sWord) == $sWord && $sModule) {
            $lang->load($sModule);
        }
        return $lang->get($sWord);
    }

    /**
     *
     * @param string $email        (Email address to whom mail will send)
     * @param object|string $from  (User Model | email from whom mail will send)
     * @param array $data          (array of subject and message e.g. array('subject'=>string,'message'=>string);
     * @return boolean
     */
    public static function mailTo($email, $from, $data) {
        if (is_object($from)) {
            $from_header = $from->display_name . "<" . $from->email . ">";
        } else {
            $from_header = $from;
        }
        // Sending Mail
        $subject = $data['subject'];
        $message = $data['message'];
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: " . $from_header . "\r\n";
        //d(array($to,$subject,$message,$header),true);
        if (mail($email, $subject, $message, $headers)) {
            return true;
        }
        return false;
    }

    /**
     *
     * Format to mobile number
     * @param int $phone (mobile number unformated)
     * @return string    (formated mobile number)
     */
    public static function formatIntMobileNo($phone) {
        $phone = str_replace(" ", "", $phone);
        //remove Sapces
        $phone = ereg_replace("[^0-9]", "", $phone);
        //Remove any characters other than digits
        $mobLength = strlen($phone);

        //For UK numbers
        if (substr($phone, 0, 4) == '0044') {
            $phone = substr($phone, 2, $mobLength);
        } else if (substr($phone, 0, 3) == '+44') {
            $phone = substr($phone, 1, $mobLength);
        } else if (substr($phone, 0, 3) == '044') {
            $phone = substr($phone, 1, $mobLength);
        } else if (substr($phone, 0, 2) == '07') {
            $phone = '44' . substr($phone, 1, $mobLength);
        } else if (substr($phone, 0, 1) == '7') {
            $phone = '44' . substr($phone, 0, $mobLength);
        }
        //For International Numbers
        if (substr($phone, 0, 1) == '+') {
            $phone = substr($phone, 1, $mobLength);
        } else if (substr($phone, 0, 2) == '00') {
            $phone = substr($phone, 2, $mobLength);
        } else if (substr($phone, 0, 1) == '0') {
            $phone = '44' . substr($phone, 1, $mobLength);
        }
        return $phone;
    }

    /**
     *
     * Get All Category lists
     * @return array $aCategories (All category list that have category id as key)
     */
    public static function getCategoryList() {
        $registry = Registry::getInstance();

        if (!$aCategories = $registry->get('category_list')) {
            $registry->get('load')->model('catalog/category');
            $aResults = $registry
                    ->model_catalog_category
                    ->getAllCategories();
            foreach ($aResults as $result) {
                $aCategories[$result['category_id']] = $result;
            }
            $registry->set('category_list', $aCategories);
        }

        return $aCategories;
    }

    /**
     * Email to customer who abandoned their cart
     * @param model $oCart    customer_cart orm object
     * @param array $aData    subject and message
     * @return string $html   Html of email message
     */
    public static function emailCartAbandoned($oCart, $aData) {
        // Get Customer from abandon cart data
        $oModel = Make::a('report/customer')
                ->where('customer_id', $oCart->customer_id)
                ->find_one();

        // patterns to be replace by dynamic data define in message setting
        $pattern = array(
            '/#customer#/',
            '/#date_added#/',
            '/#cart_products#/',
            '/#cart_link#/',
            '/#store#/',
            '/#store_email#/',
        );

        // convert string cart data to array
        $aCart = unserialize($oCart->cart);

        // generate Product Table
        $sCart = '<table width="100%" border="1" style="border-collapse:collapse;" cellpadding="5">
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Delivery Date</th>
                    <th>Quantity</th>
                </tr>';

        if ($aCart['cart'] && isset($aCart['cart'][$aCart['country_id']])) { // check cart have data
            // loading necessary modules to get Products
            self::app()->load->model('catalog/product');
            self::app()->load->model('tool/image');

            foreach ($aCart['cart'][$aCart['country_id']] as $iKey => $aProduct) { // Loop through cart, country that customer last abandon 
                $aKey = QS::parseCartKey($iKey, $aCart['option_value']); // parse the key of the cart

                $product_id = $aKey['product_id'];
                $quantity = $aProduct['quantity'];
                //$premium = $aProduct['premium'];
                $delivery_date = date('d M Y', $aKey['delivery_date']);
                //$options = $aKey['option'];
                // Get product
                $result = self::app()->model_catalog_product->getProductById($product_id);
                if ($result) { // check product exsists
                    // product image if not present use default image
                    if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                        $image = $result['image'];
                    } else {
                        $image = 'no_image.jpg';
                    }
                    // row of product with delivery and quantity 
                    $sCart .= '<tr>
                    <td align="center" width="15%">
                    <img src="' . self::app()->model_tool_image->resize($image, self::app()->config->get('config_image_cart_width'), self::app()->config->get('config_image_cart_height')) . '" alt="' . ($result['alt_title'] ? $result['alt_title'] : $result['name']) . '" />
                    </td>
                    <td align="left" width="40%">' . $result['name'] . '</td><td align="center" width="15%">' . $delivery_date . '</td>
                            <td align="center" width="10%">' . $quantity . '</td>
                            </tr>';
                    // check if addon product is there
                    if (isset($aProduct['addon_product']) && $aProduct['addon_product']) {
                        foreach ($aProduct['addon_product'] as $id => $price) { // loop addon products
                            // get addon product
                            $addon_product = self::app()->model_catalog_product->getProductById($id);
                            //self::d($addon_product);
                            if ($addon_product) { // check addon product exsists
                                // addon product image if not present use default image
                                if ($addon_product['image'] && file_exists(DIR_IMAGE . $addon_product['image'])) {
                                    $addon = $addon_product['image'];
                                } else {
                                    $addon = 'no_image.jpg';
                                }

                                // row of addon product with delivery and quantity 
                                $sCart .= '<tr>
                            <td align="center" width="15%">
                            <img src="' . self::app()->model_tool_image->resize($addon, self::app()->config->get('config_image_cart_width'), self::app()->config->get('config_image_cart_height')) . '" alt="' . ($addon_product['alt_title'] ? $addon_product['alt_title'] : $addon_product['name']) . '" />
                            </td>
                            <td align="left" width="40%">' . $addon_product['name'] . '</td>
                            <td align="center" width="15%">' . $delivery_date . '</td>
                            <td align="center" width="10%">1</td>
                            </tr>';
                                // self::d($sCart);
                            }
                        }
                    }
                }
            }
        }
        // closing table tag
        $sCart .='</table>';

        // array of dynamic variable that needs to be replace
        $replacement = array(
            $oModel->firstname . ' ' . $oModel->lastname,
            $oCart->date_added,
            $sCart,
            HTTP_CATALOG . '/checkout/cart',
            self::app()->config->get('config_name'),
            self::app()->config->get('config_email')
        );

        // matching and replacing subject and message string 
        $subject = preg_replace($pattern, $replacement, $aData['subject']);
        $message = preg_replace($pattern, $replacement, $aData['message']);

        // decode to html in message
        $html = html_entity_decode($message, ENT_QUOTES, 'UTF-8');

        // start to send email
        $mail = new Mail();
        $mail->protocol = self::app()->config->get('config_mail_protocol');
        $mail->parameter = self::app()->config->get('config_mail_parameter');
        $mail->hostname = self::app()->config->get('config_smtp_host');
        $mail->username = self::app()->config->get('config_smtp_username');
        $mail->password = self::app()->config->get('config_smtp_password');
        $mail->port = self::app()->config->get('config_smtp_port');
        $mail->timeout = self::app()->config->get('config_smtp_timeout');
        $mail->setTo($oModel->email); // set customer email
        $mail->setFrom(self::app()->config->get('config_email')); // Administrator email address from config
        $mail->setSender(self::app()->config->get('config_name')); // Site name from config
        $mail->setSubject($subject); // setting subject
        $mail->setHtml($html); // setting html
        // self::d($mail);
        $mail->send();

        return $html;
    }

    /**
     * cart key to be parse     
     * --- Copy Method only for cron orignal define library/cart
     * @param string $key       encoded key created in library/cart class
     * @param string $part      define the part of the key
     * @return array or string  
     */
    public static function parseCartKey($key, $part = 'all') {
        $array1 = explode(':', $key);
        $array2 = explode('|', $array1[0]);
        $array3 = explode('--', $array2[0]);
        $product_id = $array3[1];
        $delivery_date = $array2[1];
        if (isset($array1[1])) {
            $options = explode('.', $array1[1]);
        } else {
            $options = array();
        }

        switch ($part) {
            case 'product_id':
                return $product_id;
                break;
            case 'delivery_date':
                return $delivery_date;
                break;
            case 'options':
                return $options;
                break;
            default:
                return array(
                    'product_id' => $product_id,
                    'delivery_date' => $delivery_date,
                    'option' => $options
                );
                break;
        }
    }

    public static function generateImage($product_id, $aOptions, $file_path) {
        $width = 400;
        $height = 600;
        //d($aOptions,true);
        $file_name = '';
        if ($product_id && $aOptions) {
            $file_name = $file_path . 'shirt_' . $product_id . '_' . join('_', $aOptions) . '.jpg';
            $layers = array();
            foreach ($aOptions as $iType => $iValue) {
                if ($iType == 1) {
                    $img_top = self::getOptionImage($product_id, $iType, $iValue, 'top');
                    if ($img_top)
                        $layers[] = imagecreatefrompng($img_top);
                    $img_body = self::getOptionImage($product_id, $iType, $iValue, 'body');
                    if ($img_body)
                        $layers[] = imagecreatefrompng($img_body);
                } else if ($iType == 2) {
                    $img_hand = self::getOptionImage($product_id, $iType, $iValue, 'hand');
                    if ($img_hand)
                        $layers[] = imagecreatefrompng($img_hand);
                } else if ($iType == 6) {
                    $img_collar = self::getOptionImage($product_id, $iType, $iValue, 'collar');
                    if ($img_collar)
                        $layers[] = imagecreatefrompng($img_collar);
                }
                $img = self::getOptionImage($product_id, $iType, $iValue);
                if ($img)
                    $layers[] = imagecreatefrompng($img);
            }
            //d($layers, true);
            $image = imagecreatetruecolor($width, $height);
            // make $base_image transparent
            imagealphablending($image, false);
            $col = imagecolorallocatealpha($image, 255, 255, 255, 127);
            imagefilledrectangle($image, 0, 0, $width, $height, $col);
            imagealphablending($image, true);
            imagesavealpha($image, true);

            $j = 100;
            for ($i = 0; $i < count($layers); $i++) {
                //$src_white = imageColorAllocate($layers[$i],255,255,255);
                //imageColorTransparent($layers[$i]);
                $aImage = imagecopy($image, $layers[$i], 0, 0, 0, 0, $width, $height);
                $j -= 20;
            }
            //echo $image;
            //imagealphablending($image, false);
            //imagesavealpha($image, true);
            //d(DIR_IMAGE . $file_name, true);
            header('Content-type: image/jpeg');
            imagejpeg($image, DIR_IMAGE . $file_name, 100);
            imagedestroy($image);
            //for ($i = 0; $i < count($layers); $i++) {
            //    imagedestroy($layers[$i]);
            //}
        }
    }

    public static function getOptionImage($iProduct, $iType, $iOption, $sParts = '') {
        $sImage = 'data/options/5' . '_' . $iType . '_' . $iOption;
        if ($sParts) {
            $sImage .='_' . $sParts;
        }
        $sImage .= '_main.png';
        if (file_exists(DIR_IMAGE . $sImage)) {
            //  d(array(DIR_IMAGE. $sImage));
            return DIR_IMAGE . $sImage;
        }

        return false;
    }

    /**
     *
     * Add current country and/or category to the link where defined
     * @param string $link (link to convert)
     * @param array $aCountry (country data)
     * @param array $aCategory (category data)
     * @return string  (link with current country and/or category)
     */
    public static function metaLink($link, $aCountry = false, $aCategory = false) {

        $registry = Registry::getInstance();

        // Get Category description from request
        $sCategory = '';
        $sCountry = '';
        if (stristr($link, '#category') !== false) {
            if (!$aCategory) {
                if ($registry->get('request')->get['path']) {
                    $aPath = explode('_', $registry->get('request')->get['path']);
                    $aResults = self::getCategoryList();
                    $aCategory = $aResults[end($aPath)];
                }
                $sCategory = $aCategory['name'];
            }
        }
        return str_replace(array('#country#', '#category#'), array($sCountry, $sCategory), $link);
    }

    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    public static function DT_output($columns, $data) {
        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                // Is there a formatter?
                if (isset($column['formatter'])) {
                    $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
                } else {
                    $row[$column['dt']] = $data[$i][$columns[$j]['db']];
                }
            }

            $out[] = $row;
        }

        return $out;
    }

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    public static function DT_pluck($a, $prop) {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            $out[] = $a[$i][$prop];
        }

        return $out;
    }

    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    public static function DT_order($request, $columns) {
        $orderBy = array();

        if (isset($request['order']) && count($request['order'])) {


            $dtColumns = QS::DT_pluck($columns, 'dt');
            $k = 0;
            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                            'ASC' :
                            'DESC';

                    $orderBy[$k][0] = $column['db'];
                    $orderBy[$k][1] = $dir;
                }
                $k++;
            }
        }

        return $orderBy;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    public static function DT_filter($oModel, $request, $columns) {
        $dtColumns = QS::DT_pluck($columns, 'dt');
        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];
            $aWhere = array();
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                if ($requestColumn['searchable'] == 'true') {
                    //$binding = SSP::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                    $aWhere[] = 'LOWER(' . $column['db'] . ')' . ' LIKE "%' . strtolower($str) . '%"';
                }
            }
        }

        // Individual column filtering
        for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
            $requestColumn = $request['columns'][$i];
            $columnIdx = array_search($requestColumn['data'], $dtColumns);
            $column = $columns[$columnIdx];

            $str = $requestColumn['search']['value'];

            if ($requestColumn['searchable'] == 'true' &&
                    $str != '') {
//		$binding = SSP::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
//		$columnSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
                $aWhere[] = 'LOWER(' . $column['db'] . ') LIKE "%' . strtolower($str) . '%"';
//		$oModel = $oModel->where_like('LOWER(' . $column['db'] . ')', '%' . strtolower($str) . '%');
            }
        }

        if (!empty($aWhere)) {
            $oModel = $oModel->where_raw(join(' OR ', $aWhere), array());
        }
        return $oModel;
    }

    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $sql_details SQL connection details - see sql_connect()
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @return array          Server-side processing response array
     */
    public static function DT_simple($request, $oModel, $columns, $table, $bGroup = false) {
        // Build the SQL query string from the request
        $aOrder = QS::DT_order($request, $columns);

        // Main query to actually get the data
        $oModel = QS::DT_filter($oModel, $request, $columns);
        $oCount = clone $oModel;
        if ($aOrder) {
            foreach ($aOrder as $order) {
                $oModel = $oModel->order_by($order[0], $order[1]);
            }
        }
        if (isset($request['start']) && $request['length'] != -1) {
            $oModel = $oModel->offset($request['start'])->limit($request['length']);
        }
        $aData = $oModel->find_many(true);
        // Data set length after filtering
        if($bGroup){            
            $aResults = $oCount->select_expr('count(*)','count')->find_many(true);
            $recordsFiltered = count($aResults);
        } else {
            $recordsFiltered = $oCount->count();
        }
        //d(ORM::get_last_query());

        $recordsTotal = Make::a($table)->count();

        /*
         * Output
         */
        return array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => QS::DT_output($columns, $aData)
        );
    }

    public static function uploadFile($files, $directory) {

        $json = array();
        if(isset($files['image'])){
            $fileImage=$files['image'];
        }
        else{
            $fileImage=$files['image1'];
        }


        if (isset($fileImage) && $fileImage['tmp_name']) {
            if ((strlen(utf8_decode($fileImage['name'])) < 3) || (strlen(utf8_decode($fileImage['name'])) > 255)) {
                $json['error'] = __('error_filename');
            }

            $directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $directory), '/');

            if (!is_dir($directory)) {
//                    $json['error'] = __('error_directory');
                mkdir($directory);
            }


            if ($fileImage['size'] > 900000) {
                $json['error'] = __('error_file_size');
            }

            $allowed = array(
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/x-png',
                'image/gif',
                'application/x-shockwave-flash'
            );

            if (!in_array($fileImage['type'], $allowed)) {
                $json['error'] = __('error_file_type');
            }

            $allowed = array(
                '.jpg',
                '.jpeg',
                '.gif',
                '.png',
                '.flv'
            );

            if (!in_array(strtolower(strrchr($fileImage['name'], '.')), $allowed)) {
                $json['error'] = __('error_file_type');
            }


            if ($fileImage['error'] != UPLOAD_ERR_OK) {
                $json['error'] = 'error_upload_' . $fileImage['error'];
            }
        } else {
            $json['error'] = __('error_file');
        }
        if (!isset($json['error'])) {
            $dir = $directory . '/' . basename($fileImage['name']);
            if (@move_uploaded_file($fileImage['tmp_name'], $dir)) {
                $json['success'] = __('text_uploaded');
            } else {
                $json['error'] = __('error_uploaded');
            }
        }
        return $json;
    }

    public static function uploadThumbFile($files, $directory) {

        $json = array();
        if (isset($files['image1']) && $files['image1']['tmp_name']) {
            if ((strlen(utf8_decode($files['image1']['name'])) < 3) || (strlen(utf8_decode($files['image1']['name'])) > 255)) {
                $json['error'] = __('error_filename');
            }

            $directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $directory), '/');

            if (!is_dir($directory)) {
//                    $json['error'] = __('error_directory');
                mkdir($directory);
            }


            if ($files['image1']['size'] > 900000) {
                $json['error'] = __('error_file_size');
            }

            $allowed = array(
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/x-png',
                'image/gif',
                'application/x-shockwave-flash'
            );

            if (!in_array($files['image1']['type'], $allowed)) {
                $json['error'] = __('error_file_type');
            }

            $allowed = array(
                '.jpg',
                '.jpeg',
                '.gif',
                '.png',
                '.flv'
            );

            if (!in_array(strtolower(strrchr($files['image1']['name'], '.')), $allowed)) {
                $json['error'] = __('error_file_type');
            }


            if ($files['image1']['error'] != UPLOAD_ERR_OK) {
                $json['error'] = 'error_upload_' . $files['image1']['error'];
            }
        } else {
            $json['error'] = __('error_file');
        }
        if (!isset($json['error'])) {
            $dir = $directory . '/' . basename($files['image1']['name']);
            if (@move_uploaded_file($files['image1']['tmp_name'], $dir)) {
                $json['success'] = __('text_uploaded');
            } else {
                $json['error'] = __('error_uploaded');
            }
        }
        return $json;
    }

    public static function getMetaLink($meta_link, $name, $category = false) {
        return $meta_link ? self::metaLink($meta_link, false, $category) : html_entity_decode($name);
    }

    public static function getPages() {
        $aPages = array();
        $aPages['home'] = 'Home Page';

        $aFolders = array(
            'account',
            'checkout',
            'product',
        );
        $aIgnoreFiles = array(
            'login',
            'guest_step_2',
            'process_bar',
            'left_bar'
        );
        foreach ($aFolders as $sFolder) {
            $dir = DIR_CATALOG . 'view/theme/' . self::app()->config->get('config_template') . '/template/' . $sFolder . '/*.tpl';
            $files = glob($dir);

            foreach ($files as $file) {
                $name = basename($file);
                $name = str_replace('.tpl', '', $name);
                if (!in_array($name, $aIgnoreFiles)) {
                    $formatedName = explode('_', strtolower($name));
                    for ($i = 0; $i < count($formatedName); $i++) {
                        $formatedName[$i] = strtoupper(substr($formatedName[$i], 0, 1)) . substr($formatedName[$i], 1);
                    }
                    $formatedName = implode(' ', $formatedName);
                    $aPages[$name] = $formatedName;
                }
            }
        }
        $aResults = ORM::for_table('information')
                ->table_alias('i')
                ->left_outer_join('information_description', 'i.information_id=id.information_id', 'id')
                ->where('i.status', 1)
                ->where('id.language_id', self::app()->config->get('config_language_id'))
                ->find_many();
        foreach ($aResults as $oModel) {
            $aPages[$oModel->information_id] = $oModel->title;
        }

        return $aPages;
    }

    public static function getLayouts($layout_id = null) {
        // Layouts
        $aLayouts = array(
            1 => array(
                'id' => 1,
                'name' => 'One Column',
                'image' => 'full.jpg'
            ),
            2 => array(
                'id' => 2,
                'name' => 'Two Column Right',
                'image' => 'two_column_right.jpg'
            ),
            3 => array(
                'id' => 3,
                'name' => 'Two Column Left',
                'image' => 'two_column_left.jpg'
            ),
            4 => array(
                'id' => 4,
                'name' => 'Three Column',
                'image' => 'three_column.jpg'
            )
        );
        if ($layout_id != null) {
            return $aLayouts[$layout_id];
        }
        return $aLayouts;
    }

}

?>
