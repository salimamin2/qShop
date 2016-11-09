<?php
include_once('init_app.php');

// Front Controller 
$controller = new Front();


//Shortcodes 
$controller->addPreAction(new Action('common/shortcode'));

// Maintenance Mode
$controller->addPreAction(new Action('common/maintenance/check'));

// SEO URL's
$controller->addPreAction(new Action('common/seo_url'));
$controller->addPreAction(new Action('common/log_activity'));

// Router
// d($request);
if (isset($request->get['no-layout']) || ($request->isAjax())) {
    $route = '';
    if (isset($request->get['act'])) {
	$route = $request->get['act'];
    } else if(isset($request->get['_act_'])){
	$route = $request->get['_act_'];
    }
    $action = new Action($route);
} else {
    $action = new Action('layout');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>