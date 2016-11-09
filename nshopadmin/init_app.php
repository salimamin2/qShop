<?php

// Configuration
require_once('config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = Registry::getInstance();

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
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");

foreach ($query->rows as $setting) {
    $config->set($setting['key'], $setting['value']);
}

// Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

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
        if ($showError)
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

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Session
$registry->set('session', new Session());

// Cache
$_config = array(
    'prefix' => 'nissa_',
    'expire' => strtotime('1 week'),
    'gc' => strtotime('1 month')
);
$cache = new Cache(CACHE_NAME, constant(CACHE_DRIVER), $_config);
$registry->set('cache', $cache);

// Document
$registry->set('document', new Document());

// Language
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

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

$language = new Language($languages[$config->get('config_admin_language')]['directory']);

$language->load($languages[$config->get('config_admin_language')]['filename']);
$registry->set('language', $language);

// Currency
$registry->set('currency', new Currency());

// Weight
$registry->set('weight', new Weight());

// Length
$registry->set('length', new Length());

// User
$registry->set('user', new User());

// Setting Default Date Format
if (!$config->get('config_date_format')) {
    $config->set('config_date_format', 'dd-mm-yyyy');
    $config->set('config_date_format_php', 'd-m-Y');
}
?>