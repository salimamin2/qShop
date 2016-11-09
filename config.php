<?php

define('DIR_ROOT', dirname(__FILE__));

define('HTTP_ROOT', $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/');
// HTTP
//define('HTTP_SERVER', 'http://' . HTTP_ROOT . '/' );
define('HTTP_SERVER', 'http://' . HTTP_ROOT);
define('HTTP_CATALOG', 'http://' . HTTP_ROOT);
//define('HTTP_IMAGE', 'http://' . HTTP_ROOT . '/image/');
define('HTTP_IMAGE', 'http://' . HTTP_ROOT . 'image/');

// HTTPS
//define('HTTPS_SERVER', 'http://' . HTTP_ROOT . '/' );
//define('HTTPS_IMAGE', 'http://' . HTTP_ROOT . '/image/');
define('HTTPS_SERVER', 'http://' . HTTP_ROOT);
define('HTTPS_IMAGE', 'http://' . HTTP_ROOT . 'image/');
// DIR
define('DIR_APPLICATION', DIR_ROOT . '/catalog/');
define('DIR_LOCAL_APP', DIR_ROOT . '/local/catalog/');
define('DIR_LANGUAGE', DIR_ROOT . '/catalog/language/');
define('DIR_TEMPLATE', DIR_ROOT . '/catalog/view/theme/');
require_once(DIR_ROOT . '/system/config/constants.php');

define('DB_LOG', 0);
define('DB_CACHE', 0);
define('DB_SOFT_DELETE', 0);
define('LOG_TRESHOLD', '1');
define('LOG_DATE_FMT', 'Y-m-d G:i:s');
define('SESSION_NAME','hazari');
//full page cache class will ignore this routes
$ignoreRoute = array(
    'checkout/*',
    'account/*',
    'payment/*',
    'information/contact',
    'product/product/calendar',
    'product/product'
);
define('PAGE_CACHE', 0);
define('CACHE_NAME', 'clovebuy_');

// if APC available
define('CACHE_DRIVER', 'Cache::DRIVER_FILE');

define('ENV', ENV_TEST);
define('BASE_PATH','\/clovebuy\/');
// DB
require_once(DIR_CONFIG . 'db.php');
//require_once(DIR_SYSTEM . 'fpc.php');

?>