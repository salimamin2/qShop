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

?>
<?php

// ------------------------------------------------------------------------

/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		CodeIgniter
 * @subpackage	codeigniter
 * @category	Common Functions
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/
 */
// ------------------------------------------------------------------------

/**
 * Determines if the current version of PHP is greater then the supplied value
 *
 * Since there are a few places where we conditionally test for PHP > 5
 * we'll set a static variable.
 *
 * @access	public
 * @param	string
 * @return	bool	TRUE if the current version is $version or higher
 */
function is_php($version = '5.0.0') {
    static $_is_php;
    $version = (string) $version;

    if (!isset($_is_php[$version])) {
	$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
    }

    return $_is_php[$version];
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
function is_really_writable($file) {
    return QS::is_really_writable($file);
}

// ------------------------------------------------------------------------

/**
 * Class registry
 *
 * This function acts as a singleton.  If the requested class does not
 * exist it is instantiated and set to a static variable.  If it has
 * previously been instantiated the variable is returned.
 *
 * @access	public
 * @param	string	the class name being requested
 * @param	string	the directory where the class should be found
 * @param	string	the class name prefix
 * @return	object
 */
function load_class($class, $directory = 'library', $prefix = '') {
    if (Registry::getInstance()->has($class)) {
	return Registry::getInstance()->get($class);
    } else {
	return Registry::getInstance()->load->$directory($class);
    }
}

// --------------------------------------------------------------------

/**
 * Keeps track of which libraries have been loaded.  This function is
 * called by the load_class() function above
 *
 * @access	public
 * @return	array
 */
function is_loaded($class = '') {
    static $_is_loaded = array();

    if ($class != '') {
	$_is_loaded[strtolower($class)] = $class;
    }

    return $_is_loaded;
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
function &get_config($replace = array()) {
    return QS::get_config($replace);
}

// ------------------------------------------------------------------------

/**
 * Returns the specified config item
 *
 * @access	public
 * @return	mixed
 */
function config_item($item) {
    return QS::config_item($item);
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
function log_message($level = 'error', $message, $php_error = FALSE) {
    QS::log_message($level, $message, $php_error);
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
function set_status_header($code = 200, $text = '') {
    QS::set_status_header($code, $text);
}

// --------------------------------------------------------------------

/**
 * Exception Handler
 *
 * This is the custom exception handler that is declaired at the top
 * of Codeigniter.php.  The main reason we use this is to permit
 * PHP errors to be logged in our own log files since the user may
 * not have access to server logs. Since this function
 * effectively intercepts PHP errors, however, we also need
 * to display errors based on the current error_reporting level.
 * We do that with the use of a PHP error template.
 *
 * @access	private
 * @return	void
 */
function _exception_handler($severity, $message, $filepath, $line) {
    // We don't bother with "strict" notices since they tend to fill up
    // the log file with excess information that isn't normally very helpful.
    // For example, if you are running PHP 5 and you use version 4 style
    // class functions (without prefixes like "public", "private", etc.)
    // you'll get notices telling you that these have been deprecated.
    if ($severity == E_STRICT) {
	return;
    }

    $_error = & load_class('Exceptions', 'core');

    // Should we display the error? We'll get the current error_reporting
    // level and add its bits with the severity bits to find out.
    if (($severity & error_reporting()) == $severity) {
	$_error->show_php_error($severity, $message, $filepath, $line);
    }

    // Should we log the error?  No?  We're done...
    if (config_item('log_threshold') == 0) {
	return;
    }

    $_error->log_exception($severity, $message, $filepath, $line);
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
function remove_invisible_characters($str) {
    return QS::remove_invisible_characters($str);
}

//for debuging
function d($mParam, $bExit = 0, $bVarDump = 0, $echoInFile = 0) {

    QS::d($mParam, $bExit, $bVarDump, $echoInFile);
}

function get_back_trace($NL = "\n") {
    QS::get_back_trace($NL);
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
function strtocamel($str, $capitalizeFirst = true, $allowed = 'A-Za-z0-9') {
    return QS::strtocamel($str, $capitalizeFirst, $allowed);
}

/**
 * Implodes a single dimensional associative array with various formatting options / modifiers.
 *
 * @param array $array single dimensional array to implode
 * @param array $overrideOptions is an key->value array with the following valid values:
 * - inner_glue           =>  string to connect keys to values with
 * - outer_glue           =>  string to connect keys-value pairs together
 * - prepend              =>  string to attach to the front of the final result
 * - append               =>  string to attach to the end of the final result
 * - skip_empty           =>  bool if true then do not include entries with values that evaluate to false
 * - prepend_inner_glue   =>  bool if true then stick the inner_glue on to the front of all key-value pairs
 * - append_inner_glue    =>  bool if true then stick the inner_glue on to the end of all key-value pairs
 * - prepend_outer_glue   =>  bool if true then stick the outer_glue on to the front of the return string
 * - append_outer_glue    =>  bool if true then stick the outer_glue on to the end of the return string
 * - urlencode            =>  bool if true then urlencode() all returned values
 * - part                 =>  string setting what part(s) of the key-value pairs to return; valid values:
 *   - both   ->  display both the key and the value
 *   - key    ->  display the key and NOT the value; inner_glue will not display except with prepend/append
 *   - value  ->  display the value and NOT the key; inner_glue will not display except with prepend/append
 *
 * @author Sean P. O. MacCath-Moran -- http://emanaton.com/code/php/implode_assoc
 *
 * @example
 *  $titleParts = array('Type'=>'Image', 'Size'=>'16 Meg', 'Description'=>'',
 *                      'Author'=>'Sean P. O. MacCath-Moran', 'Site'=>'www.emanaton.com');
 *  echo implode_assoc($titleParts, array('inner_glue'=>': ', 'outer_glue'=>' || ',
 *                                        'skip_empty'=>true));
 *      Type: Image || Size: 16 Meg || Arther: Sean P. O. MacCath-Moran || Site: www.emanaton.com
 *
 * $htmlArgs = array('href'=>'http://www.emanaton.com/', 'title'=>'emanaton dot com', 'style'=>'',
 *                   'class'=>'promote siteLink');
 * echo implode_assoc($htmlArgs, array('inner_glue'=>'="', 'outer_glue'=>'" ', 'skip_empty'=>true,
 *  'append_outer_glue'=>true, 'prepend'=>'<a ', 'append'=>'>'));
 *     <a href="http://www.emanaton.com/" title="emanaton dot com" class="promote siteLink" >
 *
 * $getArgs = array('page'=>'2', 'id'=>'alpha1', 'module'=>'acl', 'controller'=>'role', 'action'=>'',
 *                  'homepage'=>'http://www.emanaton.com/');
 * echo implode_assoc($getArgs, array('skip_empty'=>true, 'urlencode'=>true));
 *     page=2&id=alpha1&module=acl&controller=role&template=default&value=http%3A%2F%2Fwww.emanaton.com%2F
 *
 * @return string of the imploded key-value pairs
 */
function implode_assoc($array, $overrideOptions = array()) {

    // These default options set the defaults but are over-written by matching values from $overrideOptions
    $options = array(
	'inner_glue' => '=',
	'outer_glue' => '&',
	'prepend' => '',
	'append' => '',
	'skip_empty' => false,
	'prepend_inner_glue' => false,
	'append_inner_glue' => false,
	'prepend_outer_glue' => false,
	'append_outer_glue' => false,
	'urlencode' => false,
	'part' => 'both' //'both', 'key', or 'value'
    );

    // Use values from $overrideOptions that match keys in $options and then extract those values into
    // the current workspace.
    foreach ($overrideOptions as $key => $val) {
	if (isset($options[$key])) {
	    $options[$key] = $val;
	}
    }
    extract($options);

    // $output holds the imploded results of the key-value pairs
    $output = array();

    // Create a collection of the inner key-value pairs and glue them as indicated by the $options
    foreach ($array as $key => $item) {
	// If not skipping empty values OR if the item evaluates to true.
	// i.e. If $skip_empty is true then check to see if the array item's value evaluates to true.
	if (!$skip_empty || $item) {
	    if (is_object($item)) {
		$item = serialize($item);
	    }
	    $output[] = ($prepend_inner_glue ? $inner_glue : '') .
		    ($part != 'value' ? $key : '') . // i.e. show the $key if $part is 'both' or 'key'
		    ($part == 'both' ? $inner_glue : '') .
		    // i.e. show the $item if $part is 'both' or 'value' and optionally urlencode $item
		    ($part != 'key' ? ($urlencode ? urlencode($item) : $item) : '') .
		    ($append_inner_glue ? $inner_glue : '')
	    ;
	}
    }

    return
	    $prepend .
	    ($prepend_outer_glue ? $outer_glue : '') .
	    implode($outer_glue, $output) .
	    ($append_outer_glue ? $outer_glue : '') .
	    $append
    ;
}

function php_get_browser($agent = NULL) {
    $agent = $agent ? $agent : $_SERVER['HTTP_USER_AGENT'];
    $yu = array();
    $q_s = array("#\.#", "#\*#", "#\?#");
    $q_r = array("\.", ".*", ".?");
    $brows = parse_ini_file(DIR_SYSTEM . "php_browscap.ini", true);
    $hu = array("browser" => $agent, 'version' => 'Un-known', 'platform' => 'Un-known');
    foreach ($brows as $k => $t) {
	if (fnmatch($k, $agent)) {
	    $yu['browser_name_pattern'] = $k;
	    $pat = preg_replace($q_s, $q_r, $k);
	    $yu['browser_name_regex'] = strtolower("^$pat$");
	    foreach ($brows as $g => $r) {
		if (isset($t['Parent']) && $t['Parent'] == $g) {
		    foreach ($brows as $a => $b) {
			if (isset($r['Parent']) && $r['Parent'] == $a) {
			    $yu = array_merge($yu, $b, $r, $t);
			    foreach ($yu as $d => $z) {
				$l = strtolower($d);
				$hu[$l] = $z;
			    }
			}
		    }
		}
	    }
	    break;
	}
    }
    return $hu;
}

if (!function_exists('fnmatch')) {

    function fnmatch($pattern, $string) {
	return @preg_match(
			'/^' . strtr(addcslashes($pattern, '/\\.+^$(){}=!<>|'), array('*' => '.*', '?' => '.?')) . '$/i', $string
	);
    }

}
if (!function_exists('get_called_class')) {

    function get_called_class($bt = false, $l = 1) {
	if (!$bt)
	    $bt = debug_backtrace();
	if (!isset($bt[$l]))
	    throw new Exception("Cannot find called class -> stack level too deep.");
	if (!isset($bt[$l]['type'])) {
	    throw new Exception('type not set');
	} else
	    switch ($bt[$l]['type']) {
		case '::':
		    $lines = file($bt[$l]['file']);
		    $i = 0;
		    $callerLine = '';
		    do {
			$i++;
			$callerLine = $lines[$bt[$l]['line'] - $i] . $callerLine;
		    } while (stripos($callerLine, $bt[$l]['function']) === false);
		    preg_match('/([a-zA-Z0-9\_]+)::' . $bt[$l]['function'] . '/', $callerLine, $matches);
		    if (!isset($matches[1])) {
			// must be an edge case.
			throw new Exception("Could not find caller class: originating method call is obscured.");
		    }
		    switch ($matches[1]) {
			case 'self':
			case 'parent':
			    return get_called_class($bt, $l + 1);
			default:
			    return $matches[1];
		    }
		// won't get here.
		case '->': switch ($bt[$l]['function']) {
			case '__get':
			    // edge case -> get class of calling object
			    if (!is_object($bt[$l]['object']))
				throw new Exception("Edge case fail. __get called on non object.");
			    return get_class($bt[$l]['object']);
			default: return $bt[$l]['class'];
		    }

		default: throw new Exception("Unknown backtrace method type");
	    }
    }

}

function makeUrl($url, $params = array(), $bPermit = false, $ssl = false) {
    return QS::makeUrl($url, $params, $bPermit, $ssl);
}

/* ---- Encode string to base 64 and use in URL ------ */

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

/* ----- Decode url base 64 to string ------------- */

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

/* * ********* Language shortcut method **************** */

function __($sWord, $sModule = '') {
    return QS::__($sWord, $sModule);
}

/* * ******** Add Current Country to Link title ************* */

function metaLink($link, $aCountry = false, $aCategory = false) {
    return QS::metaLink($link, $aCountry, $aCategory);
}

/* End of file Common.php */
/* Location: ./system/core/Common.php */

?>