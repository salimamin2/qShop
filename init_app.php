<?php

//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);

// Configuration
require_once('config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = Registry::getInstance();

// Cache
$_config = array(
     'prefix' => 'nissa_',
     'expire' => strtotime('1 week'),
     'gc' => strtotime('1 month')
);
$cache = new Cache(CACHE_NAME,constant(CACHE_DRIVER),$_config);
$registry->set('cache',$cache);

// Loader
$loader = new Loader();
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$registry->set('db', $db);

// Settings
$settings = $cache->get('config_settings');
if(!$settings){
  $sSql = "SELECT * FROM " . DB_PREFIX . "setting ORDER BY `key`";
  $query = $db->query($sSql);
  $settings=array();
  foreach ($query->rows as $setting) {
  	$config->set($setting['key'], $setting['value']);
  }
  $cache->set('config_settings',$config->getAll());
}else{
    $config->setAll($settings);
}

//// Store
//$query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE url = '" . $db->escape('http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "' OR url = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
//
//foreach ($query->row as $key => $value) {
//    $config->set('config_' . $key, $value);
//}

// Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

//  Shortcodes
$shortcodes = new Shortcodes($registry);
$registry->set('shortcodes', $shortcodes);

// Error Handler
function error_handler($errno, $errstr, $errfile, $errline) {
    global $config, $log;
    $isEmail = false;
    $showError = false;
    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            $isEmail = true;
            $showError = true;
            break;
        default:
            $error = 'Unknown';
            $isEmail = true;
            $showError = true;
            break;
    }

    if ($config->get('config_error_display') || ENV == ENV_DEV) {
      if($showError)
        echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }

    if ($config->get('config_error_log') || ENV == ENV_LIVE | ENV_TEST) {
        $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
    }

    if ($config->get('config_error_email') && $isEmail) {
        $mail = new Mail();
        $mail->protocol = $config->get('config_mail_protocol');
        $mail->hostname = $config->get('config_smtp_host');
        $mail->username = $config->get('config_smtp_username');
        $mail->password = $config->get('config_smtp_password');
        $mail->port = $config->get('config_smtp_port');
        $mail->timeout = $config->get('config_smtp_timeout');
        $mail->setTo($config->get('config_error_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($order_query->row['store_name'] . "Error Email");
        $mail->setSubject("Error occurs at " . $order_query->row['store_name']);
        $message = 'PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline;
        $message.= "\n" . get_back_trace();
        $mail->setText($message);
        $mail->send();
    }

    return TRUE;
}

//Detect Site is open in Mobile or Tablet
$tablet_browser = 0;
$mobile_browser = 0;
 
if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $tablet_browser++;
}
 
if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $mobile_browser++;
}
 
if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
    $mobile_browser++;
}
 
$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
$mobile_agents = array(
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
    'newt','noki','palm','pana','pant','phil','play','port','prox',
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
    'wapr','webc','winw','winw','xda ','xda-');
 
if (in_array($mobile_ua,$mobile_agents)) {
    $mobile_browser++;
}
 
if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
    $mobile_browser++;
    //Check for tablets on opera mini alternative headers
    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
      $tablet_browser++;
    }
}
 
if ($tablet_browser > 0) {
   // do something for tablet devices
   $config->set('config_template', 'mobile');
}
else if ($mobile_browser > 0) {
   // do something for mobile devices
   $config->set('config_template', 'mobile');
}

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

$session = new Session();
$registry->set('session', $session);

// Document
$registry->set('document', new Document());

// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language");

foreach ($query->rows as $result) {
    $languages[$result['code']] = array(
        'language_id' => $result['language_id'],
        'name' => $result['name'],
        'code' => $result['code'],
        'locale' => $result['locale'],
        'directory' => $result['directory'],
        'filename' => $result['filename']
    );
}

$detect = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && ($request->server['HTTP_ACCEPT_LANGUAGE'])) {
    $browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);

    foreach ($browser_languages as $browser_language) {
        foreach ($languages as $key => $value) {
            $locale = explode(',', $value['locale']);

            if (in_array($browser_language, $locale)) {
                $detect = $key;
            }
        }
    }
}

if (isset($_GET['language']) && array_key_exists($_GET['language'], $languages)) {
    $code = $_GET['language'];
    /* } elseif (isset($session->data['language']) && array_key_exists($session->data['language'], $languages)) {
      $code = $session->data['language']; */
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages)) {
    $code = $request->cookie['language'];
} elseif ($detect) {
    $code = $detect;
} else {
    $code = $config->get('config_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
    $session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
    setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Language
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);
$registry->set('language', $language);

// Customer
$registry->set('customer', new Customer());

// Currency
$registry->set('currency', new Currency());

// Tax
$registry->set('tax', new Tax());

// Weight
$registry->set('weight', new Weight());

// Length
$registry->set('length', new Length());

// Cart
$registry->set('cart', new Cart());
?>