<?php

spl_autoload_register('autoLoader');
// Error Reporting
// error_reporting(E_ALL & ~E_NOTICE);

// Check Version
if (version_compare(phpversion(), '5.1.0', '<') == TRUE) {
    exit('PHP5.1+ Required');
}

// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {

    function clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[clean($key)] = clean($value);
            }
        } else {
            $data = stripslashes($data);
        }

        return $data;
    }

    $_GET = clean($_GET);
    $_POST = clean($_POST);
    $_COOKIE = clean($_COOKIE);
}

if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}


// Windows IIS Compatibility  
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['SCRIPT_FILENAME'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

// Common
require_once(DIR_SYSTEM . 'engine/common.php');
require_once(DIR_SYSTEM . 'library/config.php');
require_once(DIR_SYSTEM . 'library/db.php');
/**
 *
 * @param string $className Class or Interface name automatically
 *              passed to this function by the PHP Interpreter
 */
function autoLoader($className) {

    //Directories added here must be
    //relative to the script going to use this file.
    //New entries can be added to this list
    $directories = array(
        DIR_SYSTEM,
        DIR_SYSTEM . 'library/',
        DIR_SYSTEM . 'library/filter/',
	DIR_SYSTEM . 'cache/',
        DIR_SYSTEM . 'helper/',
        DIR_SYSTEM . 'engine/',
        DIR_SYSTEM . 'exception/',
        DIR_SYSTEM . 'database/',
        DIR_SYSTEM . 'service/',
        DIR_SYSTEM . 'session/',
        DIR_APPLICATION . 'model/',
    );

    //Add your file naming formats here
    $fileNameFormats = array(
        '%s.php',
            //'%s.class.php',
            //'class.%s.php',
            //'%s.inc.php'
    );

    // this is to take care of the PEAR style of naming classes
    $path = DIR_ROOT . '/' . strtolower(str_ireplace('_', '/', $className));
    if (is_file($path . '.php')) {
        include_once $path . '.php';
        return;
    }
    $path = DIR_ROOT . '/' . str_ireplace('_', '/', $className);

    if (is_file($path . '.php')) {
        include_once $path . '.php';
        return;
    }
    $found = false;
    foreach ($directories as $directory) {
        foreach ($fileNameFormats as $fileNameFormat) {
            $path = $directory . strtolower(sprintf($fileNameFormat, strtolower($className)));
            if (is_file($path)) {
                require_once $path;
                if (class_exists($className) || interface_exists($className)) {
                    $found = true;
                    return;
                }
            }
        }
    }


    if (!$found) {
        //run without lowering the case for external library
        foreach ($directories as $directory) {
            foreach ($fileNameFormats as $fileNameFormat) {
                $path = $directory . sprintf($fileNameFormat, $className);
                if (is_file($path)) {
                    require_once $path;
                    if (class_exists($className) || interface_exists($className)) {
                        $found = true;
                        return;
                    }
                }
            }
        }
    }
}
?>