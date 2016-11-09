<?php

/*
 * Full Page Cache developed by Alexandre PIEL
 * alexandre.piel@gmail.com
 */
require_once(DIR_SYSTEM . 'library/db.php');

class FPC {

    protected static $instance;
    protected $_key;
    protected $_page;
    protected $_isSEO = false;
    protected $_forceRefresh = false;
    protected $_routes = array(
        'product/product',
        'product/category',
    );
    protected $_patternStart = '<!-- fpc %';
    protected $_patternVar = '% -->';
    protected $_patternEnd = '<!-- fpc end -->';
    protected $_globalMemoryFolder = 'fpc';
    protected $_ignoreRoute = array();
    protected $db;

    public static function instance() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    public function setIgnoreRoute($ignorePaths) {
        $this->_ignoreRoute = $ignorePaths;
        return $this;
    }

    public function __construct() {
        $this->_initMemory();
        $this->_initSession();
        // Database
        $this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    }

    protected function _initSession() {
        session_start();
        if (!isset($_SESSION['fpc'])) {
            $_SESSION['fpc'] = array(
                'data' => array(),
            );
        }
    }

    protected function _initMemory() {
        $this->_globalMemoryFolder = DIR_CACHE . $this->_globalMemoryFolder;
        if (!file_exists($this->_globalMemoryFolder)) {
            mkdir($this->_globalMemoryFolder, 0777, true);
        }
    }

    public function run() {
        if (defined('DIR_ADMIN')
                || isset($_POST['currency_code'])
                || isset($_POST['language_code'])
        ) {
            return; //should not run for admin
        }
        //initializatino of country
        // $this->getCountryFromUrl();
        //$language = isset($_SESSION['language']) ? $_SESSION['language'] : $_COOKIE['language'];
        //$currency = isset($_SESSION['currency']) ? $_SESSION['currency'] : $_COOKIE['currency'];
        // $country = isset($_SESSION['country_id']) ? $_SESSION['country_id'] : $_COOKIE['country_id'];
		$language='en';
		$currency='AED';
        $this->_translateSEOUrl();
        //add effect for currency change in dynamic fpc blocks.

        $route = isset($_REQUEST['act'])?$_REQUEST['act']:false;
		
        if (!$route && $this->_isHomePage()) {
            $route = 'common/home';
        }
		
		
        //check cache allowed logic
        if ($route && $this->_validRoute($route) && $language && $currency) { 
            $key = $this->_globalMemoryFolder . '/page/'
                     . $language . '/' . $currency;

            $this->_key = $key;
            if ($route === 'product/product' && isset($_REQUEST['product_id'])) {
                $this->_key = $key . '/product/' . $_REQUEST['product_id'];
            } else if ($route === 'product/category' && isset($_REQUEST['path'])) {
                $this->_key = $key . '/category/' . $_REQUEST['path'];
            }
        }
        if (isset($this->_key)) {
            $device = $this->detectDevice();
            $this->_key = $this->_cleanKey($this->_key.'/' . $_SERVER['REQUEST_URI'] . '/'.$device.'.php');
			//_d($this->_key,1);
            $this->_loadPage();
        }
    }

    protected function _loadPage() {
        $this->_page = $this->_getGlobalData($this->_key);
        if ($this->_page) {
            $page = $this->_page;
            if (!$this->_forceRefresh) {
                //var_dump('use cache');
                echo $page;
                exit();
            }
        }
    }

    protected function _getGlobalData($key) {
        $ret = null;
		//_d($key,1);
        if (file_exists($key)) {
            ob_start();
            include($key);
            $ret = ob_get_contents();
            ob_end_clean();
        }
        return $ret;
    }

    protected function _setGlobalData($key, $value) {
		$dir = dirname($key);
		//_d($key,1);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($key, $value);
    }

    protected function _getUserData($key) {
        $ret = false;
        if (isset($_SESSION['fpc']['data'][$key])) {
            $ret = $_SESSION['fpc']['data'][$key];
        }
        return $ret;
    }

    protected function _setUserData($key, $data) {
        $_SESSION['fpc']['data'][$key] = $data;
    }

    public function userBlock($key) {
        $block = $this->_getUserData($key);
        if ($block === false) {
            $this->_forceRefresh = true;
        } else {
            echo $block;
        }
    }

    public function setPage($page) {
        $page = str_replace('?>', '<?php echo \'?>\'; ?>', $page);
        $page = str_replace('<?xml', '<?php echo \'<?xml\'; ?>', $page);
        //d('here',1);
        while (true) {
            $pStart = strpos($page, $this->_patternStart);
            $pVar = strpos($page, $this->_patternVar);
            $pEnd = strpos($page, $this->_patternEnd);
            if ($pStart !== false && $pVar !== false && $pEnd !== false) {
                $varStart = $pStart + strlen($this->_patternStart);
                $blockStart = $pVar + strlen($this->_patternVar);
                $blockEnd = $pEnd - $blockStart;

                $start = substr($page, 0, $pStart);
                $var = substr($page, $varStart, $pVar - $varStart);
                $block = substr($page, $blockStart, $blockEnd);
                $end = substr($page, $pEnd + strlen($this->_patternEnd));
				
                $this->_setUserData($var, $block);
                //d(array($start,$var,$block,$end),1);
                $page = $start . '<?php ' . __CLASS__ . '::instance()->userBlock(\'' . $var . '\'); ?>' . $end; // or may be something else
            } else {
                break;
            }
        }
        if ($this->_key) {
			
            $this->_setGlobalData($this->_key, $page);
        }
    }

    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    protected function _delDir($path) {
        $rmdir = glob($path);
        foreach ($rmdir as $dir) {
            self::delTree($dir);
        }
    }

    protected function _delDirCategory($category_id) {
        $this->_delDir($this->_globalMemoryFolder . '/page/*/*/category/' . $category_id . '*');
        $this->_delDir($this->_globalMemoryFolder . '/page/*/*/category/*_' . $category_id);
        $this->_delDir($this->_globalMemoryFolder . '/page/*/*/category/*_' . $category_id . '_*');
    }

    public function refresh($type) {
        if ($type === 'product' || $type === 'category') {
            global $registry;
            $fileLastUpdate = $this->_globalMemoryFolder . 'lastUpdate';
            $lastUpdate = '0000-00-00 00:00:00';
            if (file_exists($fileLastUpdate)) {
                $lastUpdate = file_get_contents($fileLastUpdate);
            }
            $newLastUpdate = $lastUpdate;
            $query = $this->db->query('SELECT product_id, date_modified FROM ' . DB_PREFIX . 'product WHERE date_modified>"' . $lastUpdate . '"');
            $products = array();
            foreach ($query->rows as $product) {
                $products[] = $product['product_id'];
                $this->_delDir($this->_globalMemoryFolder . '/page/*/*/product/' . $product['product_id']);
                if ($product['date_modified'] > $newLastUpdate) {
                    $newLastUpdate = $product['date_modified'];
                }
            }
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'product_to_category;');
            foreach ($query->rows as $relation) {
                if (in_array($relation['product_id'], $products)) {
                    $this->_delDirCategory($relation['category_id']);
                }
            }
            $query = $this->db->query('SELECT category_id, date_modified FROM ' . DB_PREFIX . 'category WHERE date_modified>"' . $lastUpdate . '"');
            foreach ($query->rows as $category) {
                $this->_delDirCategory($category['category_id']);
                if ($category['date_modified'] > $newLastUpdate) {
                    $newLastUpdate = $category['date_modified'];
                }
            }
            file_put_contents($fileLastUpdate, $newLastUpdate);
        } else if (!$type) {
            $this->_delDir($this->_globalMemoryFolder);
        }
    }

    public function addToCart($data) {
        if (isset($data['total'])) {
            $block = $this->_getUserData('cart');
            if ($block !== false) {
                $newBlock = $data['total'];
            }
            $this->_setUserData('cart', $newBlock);
        }
    }

    protected function _translateSEOUrl() {
        if (isset($_REQUEST['act'])){
            return;
        }
        if (isset($_REQUEST['_act_'])) {
            $parts = explode('/', $_REQUEST['_act_']);
            foreach ($parts as $part) {
                $sql = "SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'";
                $query = $this->db->query($sql);
                if ($query->num_rows) {
                    if ($query->num_rows == 1) {
                        $this->_checkKeyword($query->row['query']);
                    } else {
//                        d(array($this->session->data['seo_route'], $query->rows));
                        foreach ($query->rows as $row) {
                            if (isset($_SESSION['seo_route']) && $row['query'] == $_SESSION['seo_route']) {
                                $this->_checkKeyword($row['query']);
                            }
                        }//foreach rows
                    }
                }//if seo record found
            }//foreach parts
        }//if _route_ exists
        if (isset($_REQUEST['product_id'])) {
            $_REQUEST['act'] = 'product/product';
        } elseif (isset($_REQUEST['path'])) {
            $_REQUEST['act'] = 'product/category';
        } elseif (isset($_REQUEST['manufacturer_id'])) {
            $_REQUEST['act'] = 'product/manufacturer';
        } elseif (isset($_REQUEST['information_id'])) {
            $_REQUEST['act'] = 'information/information';
        } elseif(isset($_REQUEST['blog_post_id'])) {
            $_REQUEST['act'] = 'blog/post';
        } elseif(isset($_REQUEST['blog_category_id'])) {
            $_REQUEST['act'] = 'blog/blog';
        } elseif(isset($_REQUEST['author_id'])) {
            $_REQUEST['act'] = 'blog/blog';
        }

        if (!isset($_REQUEST['act'])) {
            $_REQUEST['act'] = isset($_REQUEST['_act_'])?$_REQUEST['_act_']:"";
        }
    }

    protected function _checkKeyword($query) {
        $url = explode('=', $query);
        if ($url[0] == 'product_id') {
            $_REQUEST['product_id'] = $url[1];
        } elseif ($url[0] == 'category_id') {
            if (!isset($_REQUEST['path'])) {
                $_REQUEST['path'] = $url[1];
            } else {
                $_REQUEST['path'] .= '_' . $url[1];
            }
        } elseif ($url[0] == 'manufacturer_id') {
            $_REQUEST['manufacturer_id'] = $url[1];
        } elseif ($url[0] == 'information_id') {
            $_REQUEST['information_id'] = $url[1];
        } elseif ($url[0] == 'blog_post_id') {
            $_REQUEST['blog_post_id'] = $url[1];
        } elseif ($url[0] == 'blog_category_id') {
            $_REQUEST['blog_category_id'] = $url[1];
        } elseif ($url[0] == 'manufacturer_cat_id') {
            $_REQUEST['manufacturer_cat_id'] = $url[1];
        } elseif ($url[0] == 'author_id') {
            $_REQUEST['author_id'] = $url[1];
        } else {
            $_REQUEST['act'] = $url[0];
        }
    } 

    protected function _validRoute($route) {

        $aRoute = explode('/', $route);
		//_d($aRoute,1);
        foreach ($this->_ignoreRoute as $path) {
            $aPath = explode('/', $path);
            
            if ($aPath[1] == '*' && $aPath[0] == $aRoute[0]) {
                return false;
            } else if ($aPath[1] != '*' && $route == $path) {
                return false;
            }
        }
        return true;
    }


    private function _isHomePage() {
		//_d((empty($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING']=='XDEBUG_PROFILE')?'T':'F');
        return ((empty($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING']=='XDEBUG_PROFILE') && stripos($_SERVER['SCRIPT_NAME'], 'index.php') !== false);
    }
	private function _cleanKey($key){
		return str_replace(array('\/\/','index.','?','=','&nbsp;','&'), array('\/','index','-','-','-','_'), $key);
	}

    public function detectDevice(){
        //Detect Site is open in Mobile or Tablet
        $tablet_browser = 0;
        $mobile_browser = 0;
        if(!isset($_SERVER['HTTP_USER_AGENT'])){
            return 'file';
        }
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
           return 'mobile';
        }
        else if ($mobile_browser > 0) {
           // do something for mobile devices
           return 'mobile';
        }
        return 'file';
    }

}

//for debuging
//$ignoreRoute and PAGE_CACHE must be defined in config
if (PAGE_CACHE) {
    FPC::instance()->setIgnoreRoute($ignoreRoute)->run();
}

function _d($mParam, $bExit = 0, $bVarDump = 0) {
    echo '<hr><pre>';
    ob_start();
    print _get_back_trace("\n");
    if (!$bVarDump) {
        print_r($mParam);
    } else {
        var_dump($mParam);
    }
    $sStr = htmlspecialchars(ob_get_contents());
    ob_clean();
    echo $sStr;
    echo '</pre><hr>';
    if ($bExit)
        exit;
}

function _get_back_trace($NL = "\n") {
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

