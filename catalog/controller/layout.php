<?php

class ControllerLayout extends Controller {

    function curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public function index() {
        $this->load->model('catalog/information');

        $this->data['pageUrl'] = $this->curPageURL(); 
        
        $men_menu = $this->model_catalog_information->getInformation(66);
        $women_menu = $this->model_catalog_information->getInformation(69);
        $this->data['men_menu'] = html_entity_decode($men_menu['description'], ENT_QUOTES, 'UTF-8');
        $this->data['women_menu'] = html_entity_decode($women_menu['description'], ENT_QUOTES, 'UTF-8');

        $this->data['title'] = $this->config->get('config_title') . ' | ' . $this->document->getTitle();

        if (isset($this->request->get['act']) && $this->request->get['act'] != 'common/home')
            $content = $this->request->get['act'];
        else {
            $content = 'common/home';
        }
        $this->load->model('tool/seo_url');

        $aRoute = array('common', 'home');
        if (isset($this->request->get['act'])) {
            $aRoute = explode('/', $this->request->get['act']);
        }

        $this->data['route'] = $aRoute;

        if ( ( $this->request->server['REQUEST_METHOD'] == 'POST' ) && isset ( $this->request->post['currency_code'] )) {
          $this->currency->set($this->request->post['currency_code']);
          unset ( $this->session->data['shipping_methods'] );
          unset ( $this->session->data['shipping_method'] );
          if ( isset ( $this->request->post['redirect'] )) {
            $this->redirect($this->request->post['redirect']);
          }
          else {
            $this->redirect(makeUrl('common/home',array(),true));
          }
        }

        $this->data['actual_link'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //d($this->data['actual_link']);

        $this->data['content'] = $content;
        $this->data['base'] = HTTP_SERVER;
        if (isset($this->request->server['HTTPS']) && ( ( $this->request->server['HTTPS'] == 'on' ) || ( $this->request->server['HTTPS'] == '1' ))) {
            $server = HTTPS_IMAGE;
            $this->data['base'] = HTTPS_SERVER;
        } else {
            $server = HTTP_IMAGE;
        }
        $this->data['charset'] = __('charset');
        $this->data['lang'] = __('code');
        $this->data['direction'] = __('direction');
        $layout = 'layout.tpl';


        $this->data['heading_title'] = __('heading_title');

        // Contact Details
        $this->data['email'] = $this->config->get('config_email');
        $this->data['telephone'] = $this->config->get('config_telephone');
        $this->data['facebook_page'] = $this->config->get('config_facebook_page');
        $this->data['twitter_page'] = $this->config->get('config_twitter_page');
        $this->data['pinterest_page'] = $this->config->get('config_pinterest_page');
        $this->data['linkedin_page'] = $this->config->get('config_linkedin_page');
        $this->data['instagram_page'] = $this->config->get('config_instagram_page');
        $this->data['googleplus_page'] = $this->config->get('config_googleplus_page');
        $this->data['address'] = html_entity_decode($this->config->get('config_address'), ENT_QUOTES, 'UTF-8');

        $this->data['theme'] = $this->config->get('config_template');
        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
            $this->data['logo'] = $server . $this->config->get('config_logo');
        } else {
            $this->data['logo'] = '';
        }

        $this->data['mobile_logo'] = $server . 'data/mobile-logo.png';
        $this->data['icon'] = $this->config->get('config_icon');
        $this->data['bodyClass'] = '';

        $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/all_style.css');
        $this->document->addScript('catalog/view/javascript/all.js', Document::POS_END);
        $this->document->addScript('catalog/view/javascript/jquery/owl.carousel.js', Document::POS_END);

        $this->document->addScript('catalog/view/javascript/jquery/date.js', Document::POS_END);
        $this->document->addScriptInline('jQuery.noConflict();', Document::POS_HEAD);

        if ($this->config->get('google_analytics_status')) {
            $this->data['google_analytics'] = html_entity_decode($this->config->get('google_analytics_code'), ENT_QUOTES, 'UTF-8');
        } else {
            $this->data['google_analytics'] = '';
        }

        if (isset($this->session->data['error_warning'])) {
            $this->data['error'] = $this->session->data['error_warning'];
            unset($this->session->data['error_warning']);
        } else {
            $this->data['error'] = '';
        }

        $aLikeProducts = array();
        if($this->customer->isLogged()){
            $aWishlists = Make::a('account/wishlist')->create()->getWishlist($this->customer->getId());
            foreach ($aWishlists as $aWishlist) {
                $aLikeProducts[] = $aWishlist['product_id'];
            }
        }

        $this->document->addScriptInline('var aLikeProducts = '.json_encode($aLikeProducts).';');

        $this->data['welcome'] = html_entity_decode($this->config->get('config_description_' . $this->config->get('config_language_id')));

        $this->data['cutomer_login'] = $this->customer->isLogged();

        $this->data['account'] = makeUrl('account/account', array(), true, true);
        $this->data['login_url'] = makeUrl('account/login', array(), true, true);
        $this->data['logout_url'] = makeUrl('account/logout', array(), true, true);

        $aUserMenu = array();
        $aUserMenu[] = array(
            'name' => __('text_account'),
            'href' => makeUrl('account/account', array(), true, true),
            'select' => $content == 'account/account'
        );
        if ($this->customer->isLogged()) {
            $this->data['customer_logged'] = true;
            $aUserMenu[] = array(
                'name' => __("Wishlist"),
                'href' => makeUrl('account/wishlist', array(), true, true),
                'select' => $content == 'account/wishlish'
            );
            $aUserMenu[] = array(
                'name' => __('text_logout'),
                'href' => makeUrl('account/logout', array(), true, true),
                'select' => $content == 'account/logout'
            );
        } else {
            $this->data['customer_logged'] = false;
            $aUserMenu[] = array(
                'name' => __('text_login'),
                'href' => makeUrl('account/login', array(), true, true),
                'select' => $content == 'account/login'
            );
            $aUserMenu[] = array(
                'name' => __('text_create'),
                'href' => makeUrl('account/create', array(), true, true),
                'select' => $content == 'account/create'
            );
        }

        $this->data['blog'] = makeUrl('blog/blog',array(),true,true);

        $this->data['aUserMenu'] = $aUserMenu;
        $this->data['home'] = makeUrl('', array(), true);
        $this->data['store'] = $this->config->get('config_name');
        $this->load->helper_obj('menu');
        $this->load->helper_obj('catalog');
        $this->data['menu_header']   = $this->helper_menu->getMenuHtml('top-menu');
        $this->data['menu_topmenu']  = $this->helper_menu->getMenuHtml('top-quick-menu');
        $this->data['menu_footer']   = $this->helper_menu->getMenuHtml('footer-menu');
        $this->data['menu_footer_1'] = $this->helper_menu->getMenuHtml('footer-menu-1');
        $this->data['menu_footer_2'] = $this->helper_menu->getMenuHtml('footer-menu-2');
        $this->data['menu_footer_3'] = $this->helper_menu->getMenuHtml('footer-menu-3');
        $this->data['menu_footer_4'] = $this->helper_menu->getMenuHtml('footer-menu-4');
        $this->data['left_menu']     = $this->helper_menu->getMenuHtml('left-menu');
        $this->data['mobile_menu']   = $this->helper_menu->getMenuHtml('mobile-menu');

        $this->data['keyword'] = '';
        if (isset($this->request->get['keyword']) && $this->request->get['keyword']) {
           $this->data['keyword'] = $this->request->get['keyword'];
        }

        $this->data['customer_service_link'] = makeUrl('information/information?information_id=70', array(), true);

        $aPageLayouts = Make::a('setting/page_layout')->find_many();
        $iRoutes = count($aRoute);
        $iInformation = $this->request->get['information_id'];
        $iPageStatus = false;
        $this->data['left_col'] = FALSE;
        $this->data['right_col'] = FALSE;
        $aGet = $this->request->get;

        foreach ($aPageLayouts as $oMod) {
            
            if (!is_numeric($oMod->page_id)) {
                if (isset($aRoute[$iRoutes - 1]) && $aRoute[$iRoutes - 1] == $oMod->page_id) {
                    if ($oMod->params == '') {
                        $iPageStatus = true;
                    }
                }
            } else {
                
                if (isset($aRoute[$iRoutes - 1]) && $aRoute[$iRoutes - 1] == 'information' && $iInformation == $oMod->page_id) {
                    if ($oMod->params == '') {
                        $iPageStatus = true;
                        unset($aGet['information_id']);
                    }
                }
            }
            if ($oMod->params && !empty($aGet)) {
                $bParams = true;
                $aParams = explode('&', $oMod->params);
                foreach ($aParams as $sParam) {
                    $aVal = explode('=', $sParam);
                    if (!isset($aGet[$aVal[0]]) || $aGet[$aVal[0]] != $aVal[1]) {
                        $bParams = false;
                    }
                }
                if ($bParams) {
                    $iPageStatus = true;
                }
            }
            if ($iPageStatus) {
                if ($oMod->layout == 2) {
                    $this->data['right_col'] = TRUE;
                }
                if ($oMod->layout == 3) {
                    $this->data['left_col'] = TRUE;
                }
                if ($oMod->layout == 4) {
                    $this->data['left_col'] = TRUE;
                    $this->data['right_col'] = TRUE;
                }
                break;
            }
        }

        $this->data['success_main'] = '';
        if (isset($this->session->data['success_main']) && $this->session->data['success_main']) {
            $this->data['success_main'] = $this->session->data['success_main'];
            unset($this->session->data['success_main']);
        }

        $this->data['error_main'] = '';
        if (isset($this->session->data['error_main']) && $this->session->data['error_main']) {
            $this->data['error_main'] = $this->session->data['error_main'];
            unset($this->session->data['error_main']);
        }

//    $this->data['notice_main'] = '';
//    if (isset($this->session->data['notice_main']) && $this->session->data['notice_main']) {
//        $this->data['notice_main'] = $this->session->data['notice_main'];
//        unset($this->session->data['notice_main']);
//    }


        $this->data['currency_code'] = $this->currency->getCode();
        $this->load->model('localisation/currency');
        $this->data['currencies'] = array( );
        $results = $this->model_localisation_currency->getCurrencies();
        //d($results);
        foreach ( $results as $result ) {   
          if ( $result['status'] ) {
            $this->data['currencies'][] = array( 'title' => $result['title'], 'code' => $result['code'], 'symbol_left' => $result['symbol_left'], 'symbol_right' => $result['symbol_right'] );
          }
        }

        $this->data['language_code'] = $this->session->data['language'];
        $this->load->model('localisation/language');
        $this->data['languages'] = array();
        $results = $this->model_localisation_language->getLanguages();
        foreach ($results as $result) {
            if ($result['status']) {
                $this->data['languages'][$result['code']] = array(
                    'name' => $result['name'],
                    'code' => $result['code'],
                    'image' => $result['image']
                );
            }
        }

        $this->data['advanced'] = makeUrl('product/search', array(), true);
        $this->data['newsletter_action'] = makeUrl('common/newsletter/insert', array('no-layout=1'), true);

        $zone_id = 'FALSE';
        if (isset($this->request->post['zone_id'])) {
            $zone_id = $this->request->post['zone_id'];
        }

        $this->document->addScriptInline("
    jQuery(document).on('change','.country_id',function(e) {
        var zone = '" . $zone_id . "';
        if(zone == 'FALSE') {
            zone = jQuery('.zone').val();
        }
        var val = jQuery(this).val();
        jQuery.ajax({
            url: 'layout/zone',
            type: 'GET',
            data: {country_id: val,zone_id:zone},
            beforeSend: function() {
               jQuery('.zone_id').after('<span class=\"ajax-loader fixed-loader\"></span>');
            },
            success: function(res) {
                jQuery('.zone_id').html(res);
                jQuery('.ajax-loader').remove();
            }
        });
    });
    jQuery(document).on('submit','#newsletter-validate-detail',function(e){
	    var obj = jQuery(this);
	    e.preventDefault();
	    jQuery.ajax({
    		url: obj.attr('action'),
    		data: obj.serialize(),
    		type: 'post',
    		dataType: 'json',
    		beforeSend: function(){
                jQuery('.validation-advice').remove();
    		},
    		success: function(data){
    		    jQuery('.validation-advice').remove();
    		    if(typeof data.error == 'undefined'){
                    obj.before('<div class=\'alert alert-success\'>'+data.success+'</div>');
    			} else {
                    obj.before('<div class=\'alert alert-warning\'>'+data.error+'</div>');
    			}
    		    setTimeout(function(){jQuery('.alert').remove();},3000);
    		    return false;
    		}
	    });
	    return false;
	});", Document::POS_END);

        $this->document->addScriptInline('
	jQuery(".country_id").trigger("change");
        var controller = "' . $this->request->get['_act_'] . '";
	var href = window.location.pathname;
    var query = window.location.search.replace("?","").split("&");
	if(controller != ""){
	    aHref= href.split("&");
	    aHref.splice(0, 1);
	    var url = controller;
	    if(aHref.length > 0 && aHref[0].indexOf("path") != -1) {
	      url += "&"+aHref[0];
        }
        else if(query.length > 0 && query[0] != "" && query[0].indexOf("path") != -1) {
            url += "&"+query[0];
        }
	    jQuery(".nav .accordion a[href*=\'"+url+"\']").parent().addClass("current");
	    jQuery("#nav a[href*=\'"+url+"\']").parent().addClass("active");
	} else {
	    jQuery(".nav .accordion > li:first").addClass("current");
	    jQuery("#nav > li:first").addClass("active");
	}
	', Document::POS_READY);

        /* if(isset($this->session->data['error'])){
          $this->data['error'] = $this->session->data['error'];
          unset($this->session->data['error']);
          } else {
          $this->data['error'] = '';
          } */

//	if (isset($this->session->data['success'])) {
//	    $this->data['success'] = $this->session->data['success'];
//	    unset($this->session->data['success']);
//	} else {
//	    $this->data['success'] = '';
//	}
        //parent::layoutPosition();
        $this->id = 'layout';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $layout)) {
            $this->template = $this->config->get('config_template') . '/template/' . $layout;
        } else {
            $this->template = 'default/template/' . $layout;
        }

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function zone() {
        $output = '<option value="">' . $this->language->get('text_select') . '</option>';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '"';

            if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
                $output .= ' selected="selected"';
            }

            $output .= '>' . $result['name'] . '</option>';
        }

        if (!$results) {
            $output .= "<option value='0' " . (!$this->request->get['zone_id'] ? 'selected' : '') . ">" . $this->language->get('text_none') . "</option>";
        }
        $this->response->setOutput($output, $this->config->get('config_compression'));
    }

}

?>