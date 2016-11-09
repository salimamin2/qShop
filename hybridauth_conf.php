<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2011 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$registry = Registry::getInstance();

$config = $registry->get('config');

$_hybrd_config =
	array(
		// set on "base_url" the relative url that point to HybridAuth Endpoint
		// IMPORTANT: If the "index.php" is removed from the URL (http://codeigniter.com/user_guide/general/urls.html) the
		// "/index.php/" part __MUST__ be prepended to the base_url.
		'base_url' => makeUrl('module/hybrid_auth/endpoint',array(),true),

		'providers' => array (
			// openid providers
			'OpenID' => array (
				'enabled' => $config->get('hybrid_open_id')?TRUE:FALSE
			),

			'Yahoo' => array (
				'enabled' => $config->get('hybrid_yahoo_status')?TRUE:FALSE
			),

			'AOL'  => array (
				'enabled' => $config->get('hybrid_aol_status')?TRUE:FALSE
			),

			'Google' => array (
				'enabled' => $config->get('hybrid_google_status')?TRUE:FALSE,
				'keys'    => array ( 'id' => $config->get('hybrid_google_id'), 'secret' => $config->get('hybrid_google_secret') ),
				'scope'   => $config->get('hybrid_google_scope')
			),

			'Facebook' => array (
				'enabled' => $config->get('hybrid_fb_status')?TRUE:FALSE,
				'keys'    => array ( 'id' => $config->get('hybrid_fb_id'), 'secret' => $config->get('hybrid_fb_secret') ),

				// A comma-separated list of permissions you want to request from the user. See the Facebook docs for a full list of available permissions: http://developers.facebook.com/docs/reference/api/permissions.
				'scope'   => $config->get('hybrid_fb_scope'),

				// The display context to show the authentication page. Options are: page, popup, iframe, touch and wap. Read the Facebook docs for more details: http://developers.facebook.com/docs/reference/dialogs#display. Default: page
				'display' => $config->get('hybrid_fb_display')
			),

			'Twitter' => array (
				'enabled' => $config->get('hybrid_twitter_status')?TRUE:FALSE,
				'keys'    => array ( 'key' => $config->get('hybrid_twitter_id'), 'secret' => $config->get('hybrid_twitter_secret') )
			),

			// windows live
			'Live' => array (
				'enabled' => $config->get('hybrid_live_status')?TRUE:FALSE,
				'keys'    => array ( 'id' => $config->get('hybrid_live_id'), 'secret' => $config->get('hybrid_live_secret') )
			),

			'MySpace' => array (
				'enabled' => $config->get('hybrid_myspace_status')?TRUE:FALSE,
				'keys'    => array ( 'key' => $config->get('hybrid_myspace_id'), 'secret' => $config->get('hybrid_myspace_secret') )
			),

			'LinkedIn' => array (
				'enabled' => $config->get('hybrid_linkedin_status')?TRUE:FALSE,
				'keys'    => array ( 'key' => $config->get('hybrid_linkedin_id'), 'secret' => $config->get('hybrid_linkedin_secret') )
			),

			'Foursquare' => array (
				'enabled' => $config->get('hybrid_foursquare_status')?TRUE:FALSE,
				'keys'    => array ( 'id' => $config->get('hybrid_foursquare_id'), 'secret' => $config->get('hybrid_foursquare_secret') )
			),
		),

		// if you want to enable logging, set 'debug_mode' to TRUE then provide a writable file by the web server on "debug_file"
		'debug_mode' => 0,

		'debug_file' => DIR_SYSTEM.'logs/hybridauth.log',
	);
