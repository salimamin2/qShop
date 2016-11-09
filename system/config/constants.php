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
define('DIR_SYSTEM', DIR_ROOT . '/system/');
define('DIR_DATABASE', DIR_ROOT . '/system/database/');
define('DIR_CONFIG', DIR_ROOT . '/system/config/');
define('DIR_IMAGE', DIR_ROOT . '/image/');
define('DIR_CACHE', DIR_ROOT . '/files/cache/');
define('DIR_DOWNLOAD', DIR_ROOT . '/download/');
define('DIR_LOGS', DIR_ROOT . '/files/logs/');
define('DIR_UPLOAD', DIR_ROOT . '/upload/');

// DIR
/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
define('ENV_DEV', '1');
define('ENV_LOCAL', '2');
define('ENV_TEST', '3');
define('ENV_LIVE', '4');

/* End of file constants.php */

/* Menu Positions Constants */
define('MENU_POSITION_TOP', 'top_menu');
define('MENU_POSITION_MAIN', 'main_menu');
define('MENU_POSITION_LEFT', 'left_menu');
define('MENU_POSITION_RIGHT', 'right_menu');
define('MENU_POSITION_FOOTER', 'footer_menu');
define('MENU_POSITION_BOTTOM', 'bottom_menu');
define('MENU_POSITION_BOTTOM_MID', 'bottom_middle_menu');

/* Location: ./application/config/constants.php */

/** Product Related Type Constants */
define('PRODUCT_RELATED',1);
define('PRODUCT_CROSS_SELL',2);
define('PRODUCT_UP_SELL',3);

?>