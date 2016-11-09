<?php
/**
 * @version		$Id: livezilla.php 846 2010-03-21 13:24:24Z mic $
 * @package		FileZilla - Module 4 OpenCart
 * @copyright	(C) 2010 mic [ http://osworx.net ]. All Rights Reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

class ControllerModuleLiveZilla extends Controller
{
	private $_name;
	private $_type		= 'module';
	private $_langId;
	private $_param		= array();
	private $_version	= '1.0.6';

	/**
	 * main function
	 */
	protected function index() {
		$this->getName();
		$this->id = $this->_name;

		if( $this->checkVisibility() ) {
			$this->data['version']	= $this->_version;

			$this->getLanguage();
			$this->getParams();
			$this->getTemplate();
			$this->getFooter();

			$this->render();
		}
	}

	/**
	 * gets the module name out of the class
	 */
	private function getName() {
        $this->_fName	= str_replace( 'ControllerModule', '', get_class( $this ) );
		$this->_name	= strtolower( $this->_fName );
    }

	/**
	 * get locale, active languages
	 */
	private function getLocaleLangs() {
		$this->load->model( 'localisation/language' );
		$this->data['localeLangs'] = $this->model_localisation_language->getLanguages();
	}

	/**
	 * get language vars
	 */
	private function getLanguage() {
		$this->language->load( $this->_type .'/'. $this->_name );
		$this->getLocaleLangs();
		$this->getLangId();

		// standard params
		$this->data['heading_title']	= $this->language->get( 'heading_title' );
	}

	/**
	 * checks the language id
	 * - in older OpenCart versions (1.3.x) the language id if fetched by another method
	 */
	private function getLangId() {
		if( strlen( ( $lngId = $this->config->get( 'config_language_id' ) ) == 0 ) ) {
			// no id, we are using OC 1.3.x
			$lngId = (int) $this->language->getId();
		}

		// backup to be save
		if( !$lngId ) {
			$lngId = '1';
		}

		$this->_langId = $lngId;
	}

	/**
	 * get params
	 */
	private function getParams() {
		// module specific
		$this->data['code']		= html_entity_decode( $this->config->get( $this->_name . '_code' ), ENT_QUOTES );
		$this->data['header']	= $this->config->get( $this->_name . '_header' );

		// langauge specific
		$params = array( 'title' );

		foreach( $params as $parm ) {
			$this->data[$parm] = html_entity_decode( $this->getParam( $parm . $this->_langId, '', true ), ENT_QUOTES );
		}

		// check if something is defined, else use backup
		if( !$this->data['title'] ) {
			$this->data['title'] = $this->data['heading_title'];
		}
		if( !$this->data['header'] ) {
			$this->data['title'] = '';
		}

		unset( $params );
	}

	/**
	 * get a value either from request or config
	 * @param string	$parm	the parameter value to fetch
	 * @param string	$add	additional parameter value as suffix
	 * @param bool		$ret	optional: return value
	 * @return mixed
	 */
	private function getParam( $parm, $add = '', $ret = false ) {
		$name = $this->_name .'_'. $parm . ( $add ? '_' . $add : '' );

		if( isset( $this->request->post[$name] ) ) {
			if( $ret ) {
				return $this->request->post[$name];
			}else{
				$this->_param[$parm] = $this->request->post[$name];
			}
		}else{
			if( $ret ) {
				return $this->config->get( $name );
			}else{
				$this->_param[$parm] = $this->config->get( $name );
			}
		}
	}

	/**
	 * get template datas
	 */
	private function getTemplate() {
		$tmpl = '/template/' . $this->_type .'/'. $this->_name . '.tpl';

		if( file_exists( DIR_TEMPLATE . $this->config->get( 'config_template' ) . $tmpl ) ) {
			$this->template = $this->config->get( 'config_template' ) . $tmpl;
		}else{
			$this->template = 'default' . $tmpl;
		}
	}

	/**
	 * checks if this module shall be displayed, see module settings
	 */
	 private function checkVisibility() {
		global $registry;

		$request	= $registry->get( 'request' );
		if( !empty( $request->get['act'] ) ) {
			$route		= $request->get['act'];

			$visibility	= trim( $this->config->get( $this->_name . '_visibility'), ',.' );

			// visibility is a comma delimited string, build an array
			if( $visibility ) {
				$visibility = explode( ',', $visibility );

				if( in_array( $route, $visibility ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * constructs the footer
	 *
	 * Note: displaying this footer is mandatory, removing violates the license!
	 * If you do not want to display the footer, contact the author.
	 */
	private function getFooter() {
		$this->data['oxfooter']	= "\n" . '<!-- Module ' . $this->_fName .' v.'. $this->_version . ' by http://osworx.net -->' . "\n";
	}
}