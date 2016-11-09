<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of layout
 *
 * @author Hatim
 */
class ControllerLayout extends Controller {

    public function index() {
	$this->data['title'] = $this->document->title;

	if (isset($this->request->get['act']) || isset($this->request->get['_act_']))
	    $this->data['content'] = isset($this->request->get['act']) ? $this->request->get['act'] : $this->request->get['_act_'];
	else {
	    $this->data['content'] = 'common/home';
	}

	$this->data['base'] = (HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER;
	$this->data['charset'] = __('charset');
	$this->data['lang'] = __('code');
	$this->data['direction'] = __('direction');
	$this->data['links'] = $this->document->links;
	$this->data['styles'] = $this->document->styles;
	$this->data['scripts'] = $this->document->scripts;
	$this->data['breadcrumbs'] = $this->document->breadcrumbs;

	$this->data['heading_title'] = __('heading_title');
	$this->data['isAdmin'] = ($this->user->getUserGroupId() == 1) ? true : false;

	if (!$this->user->isLogged()) {
	    $this->data['logged'] = '';
	    $this->data['home'] = makeUrl('common/login');
	} else {
	    $this->data['logged'] = sprintf(__('text_logged'), $this->user->getDisplayName());
	    $this->data['news'] = makeUrl('catalog/news');
	    $this->data['home'] = makeUrl('common/home');
	    $this->data['backup'] = makeUrl('tool/backup');
	    $this->data['category'] = makeUrl('catalog/category');
	    $this->data['country'] = makeUrl('localisation/country');
	    $this->data['currency'] = makeUrl('localisation/currency');
	    $this->data['coupon'] = makeUrl('sale/coupon');
	    $this->data['customer'] = makeUrl('sale/customer');
	    $this->data['customer_group'] = makeUrl('sale/customer_group');
	    $this->data['download'] = makeUrl('catalog/download');
	    $this->data['error_log'] = makeUrl('tool/error_log');
	    $this->data['feed'] = makeUrl('extension/feed');
        $this->data['voucher'] = makeUrl('sale/voucher');
        $this->data['voucher_theme'] = makeUrl('sale/voucher_theme');


	    $this->data['geo_zone'] = makeUrl('localisation/geo_zone');
	    $this->data['information'] = makeUrl('catalog/information');
	    $this->data['language'] = makeUrl('localisation/language');
	    $this->data['testimonial'] = makeUrl('catalog/testimonial');
	    $this->data['logout'] = makeUrl('common/logout');
	    $this->data['home'] = makeUrl('common/home');
	    $this->data['manufacturer'] = makeUrl('catalog/manufacturer');
	    $this->data['module'] = makeUrl('extension/module');
	    $this->data['order'] = makeUrl('sale/order');
        $this->data['shipment'] = makeUrl('sale/shipment');
	    $this->data['order_status'] = makeUrl('localisation/order_status');
	    $this->data['payment'] = makeUrl('extension/payment');
	    $this->data['product'] = makeUrl('catalog/product');
        $this->data['blog_post'] = makeUrl('catalog/blog_post');
        $this->data['blog_category'] = makeUrl('catalog/blog_category');
        $this->data['blog_author'] = makeUrl('catalog/blog_author');
	    $this->data['report_purchased'] = makeUrl('report/purchased');
	    $this->data['report_sale'] = makeUrl('report/sale');
	    $this->data['report_viewed'] = makeUrl('report/viewed');
	    $this->data['review'] = makeUrl('catalog/review');
	    $this->data['shipping'] = makeUrl('extension/shipping');
	    $this->data['setting'] = makeUrl('setting/setting');
	    $this->data['store'] = HTTP_CATALOG;
	    $this->data['stock_status'] = makeUrl('localisation/stock_status');
	    $this->data['tax_class'] = makeUrl('localisation/tax_class');
	    $this->data['total'] = makeUrl('extension/total');
	    $this->data['user'] = makeUrl('user/user');
	    $this->data['user_group'] = makeUrl('user/user_permission');
	    $this->data['weight_class'] = makeUrl('localisation/weight_class');
	    $this->data['length_class'] = makeUrl('localisation/length_class');
	    $this->data['zone'] = makeUrl('localisation/zone');
	    $this->data['product_type'] = makeUrl('catalog/product_type');
	    $this->data['menu'] = makeUrl('catalog/menu');
	    $this->data['option'] = makeUrl('catalog/product_option');
	    $this->data['clear_cache'] = makeUrl('setting/setting/deleteCache');
	    $this->data['report_sale'] = makeUrl('report/sale');
	    $this->data['report_order'] = makeUrl('report/order');
	    $this->data['report_viewed'] = makeUrl('report/viewed');
	    $this->data['report_purchased'] = makeUrl('report/purchased');
	    $this->data['report_online'] = makeUrl('report/online');
	    $this->data['report_reward'] = makeUrl('report/customer_reward');
	    $this->data['filemanager'] = makeUrl('common/filemanager');
	    $this->data['contact'] = makeUrl('sale/contact');
	    $this->data['seo_manager'] = makeUrl('setting/seo_management');
	    $this->data['discount'] = makeUrl('sale/discount');
	    $this->data['return'] = makeUrl('sale/return');
	    $this->data['clear_fpc_cache'] = makeUrl('setting/setting/deleteFPCCache');
	}
	//add javascript
	//jQuery
	$this->document->addScript('view/javascript/jquery/jquery.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/jstree/jquery.tree.min.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/ui/jquery-ui-1.8.20.custom.min.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/bootstrap.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/tabs.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/flot/jquery.flot.min.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/flot/jquery.flot.min.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/flot/jquery.flot.pie.min.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/flot/curvedLines.min.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/flot/jquery.flot.tooltip.min.js', Document::POS_HEAD);
	$this->document->addScript('view/javascript/jquery/modernizr.js', Document::POS_HEAD);
	$this->document->addStyle('view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.20.custom-min.css');
	$this->document->addStyle('view/stylesheet/jquery.fileupload.css');

	$this->document->addScript('view/javascript/jquery/ui/jquery.ui.widget.js', Document::POS_END);
	$this->document->addScript('view/javascript/jquery/jquery.fileupload.js', Document::POS_END);

	$this->document->addScriptInline('
	    //-----------------------------------------
	    // Confirm Actions (delete, uninstall)
	    //-----------------------------------------
	    var routeAct = "' . $this->request->get['_act_'] . '";
	    var imageBaseUrl = "' . HTTP_IMAGE . '";
	    var oAjaxDataGrid = {};
	    var oDataGrid = {};
	    function initWysiwyg(obj){
		if(typeof obj == "undefined"){
		    obj = "[data-rel=wyswyg]";
		}
		$(obj).wysihtml5({"size":"sm", html : true,"color": true,"allowedClasses":true});
	    }
	    $(document).ready(function() {
		// Signin - Button
		
		$(".form-signin-body-right input").click(function() {
		    $(".form-signin").submit();
		});

		// Signin - Enter Key

		$(".form-signin input").keydown(function(e) {
		    if (e.keyCode == 13) {
			$(".form-signin").submit();
		    }
		});
		
		initWysiwyg();

		// Confirm Delete
		$("#form").submit(function() {
		    if ($(this).attr("action").indexOf("delete", 1) != -1) {
		    if($("input[name^=selected]").length > 0 && $("input[name^=selected]:checked").length == 0){
		    	alert("'.__('text_select_delete').'");
		    	return false;
		    }
			if (!confirm("' . __('text_confirm') . '")) {
			    return false;
			}
		    }
		});

		$("#clear_cache").click(function() {
		    if(!confirm("' . __('text_clear_confirm') . '")) {
		        return false;
            }
		});

		// Confirm Uninstall
		$("a").click(function() {
		    if ($(this).attr("href") != null && $(this).attr("href").indexOf("uninstall", 1) != -1) {
			if (!confirm("' . __('text_confirm') . '")) {
			    return false;
			}
		    }

		});
	    });', Document::POS_HEAD);

        $this->document->addScriptInline("
            $('[data-provide=\"datepicker-inline\"]').datepicker({
                format : '".($this->config->get('config_date_format') != '' ? $this->config->get('config_date_format') : 'dd-mm-yyyy')."',
                autoclose: true
            }).on('show',function(e) {
                if($(e.currentTarget).val() == '".str_replace(array('d','m','y'),0,$this->config->get('config_date_format'))."') {
                    $(e.currentTarget).val('');
                    $(this).datepicker('setDate',new Date());
                }
            });
        ",Document::POS_READY);


	$this->document->addScript("view/javascript/jquery/wysihtml5-0.3.0.js", Document::POS_END);
	$this->document->addScript("view/javascript/jquery/bootstrap-wysihtml5.js", Document::POS_END);
	$this->document->addScript("view/javascript/jquery/jquery.dataTables.js", Document::POS_END);
	$this->document->addScript("view/javascript/jquery/bootstrap-datepicker.js", Document::POS_END);
	$this->document->addScript("view/javascript/jquery/DT_bootstrap.js", Document::POS_END);
	$this->document->addScript("view/javascript/jquery/DT_pipeline_pagination.js", Document::POS_END);
	$this->document->addScript("view/javascript/jquery/scriptbreaker-multiple-accordion-1-min.js", Document::POS_END);
	$this->document->addScript("view/javascript/jquery/jquery.slugify.js", Document::POS_END);
	$this->document->addScript("view/javascript/jquery/script.js", Document::POS_END);

	$this->data['stores'] = Make::a('setting/store')->find_many(true);

	$this->data['text_footer'] = __('text_footer');
	$this->id = 'layout';
	$this->template = 'layout.tpl';

	$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

}

?>