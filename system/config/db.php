<?php

// DB
define('DB_DRIVER', 'imysql');
if (ENV == ENV_DEV) {
    define('DB_HOSTNAME', '192.168.0.10');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_DATABASE', 'qshop');
    define('DB_PREFIX', '');
} else if (ENV == ENV_LOCAL) {
    define('DB_HOSTNAME', '192.168.0.10');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_DATABASE', 'qshop');
    define('DB_PREFIX', '');
} else if (ENV == ENV_TEST) {
    define('DB_HOSTNAME', '192.168.0.10');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_DATABASE', 'qshop');
    define('DB_PREFIX', '');
} else if (ENV == ENV_LIVE) {
    define('DB_HOSTNAME', '192.168.0.10');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_DATABASE', 'qshop');
    define('DB_PREFIX', '');
}
define('DB_DSN', 'mysql' . ':host=' . DB_HOSTNAME . ';dbname=' . DB_DATABASE);
?>