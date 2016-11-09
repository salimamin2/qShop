<?php

define('DIR_ADMIN', 'nshopadmin');
define('DIR_ROOT', dirname(dirname(__FILE__)));
define('HTTP_ROOT', $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME'])));
// HTTP
define('HTTP_CATALOG', 'http://' . HTTP_ROOT);
define('HTTP_SERVER', HTTP_CATALOG . '/' . DIR_ADMIN . '/');
define('HTTP_IMAGE', HTTP_CATALOG . '/image/');

// HTTPS
define('HTTPS_SERVER', 'http://' . HTTP_ROOT . '/' . DIR_ADMIN . '/');
define('HTTPS_IMAGE', 'http://' . HTTP_ROOT . '/image/');

// DIR
define('DIR_APPLICATION', DIR_ROOT . '/' . DIR_ADMIN . '/');
define('DIR_LOCAL_APP', DIR_ROOT . '/local/' . DIR_ADMIN . '/');
define('DIR_LANGUAGE', DIR_ROOT . '/' . DIR_ADMIN . '/language/');
define('DIR_TEMPLATE', DIR_ROOT . '/' . DIR_ADMIN . '/view/template/');
define('DIR_CATALOG', DIR_ROOT . '/catalog/');
require_once(DIR_ROOT . '/system/config/constants.php');
//constraints
define('DB_LOG', 0);
define('DB_CACHE', 0);
define('LOG_TRESHOLD', '1');
define('LOG_DATE_FMT', 'Y-m-d G:i:s');
define('PAGE_CACHE', 0);
define('SESSION_NAME','sticherry');
define('DB_SOFT_DELETE',0);

define('CACHE_NAME', 'sticherry_');
//if APC available
//define('CACHE_DRIVER','Cache::DRIVER_APC');
define('CACHE_DRIVER', 'Cache::DRIVER_FILE');

define('ENV', ENV_TEST);
// DB
require_once(DIR_ROOT . '/system/config/db.php');

?>