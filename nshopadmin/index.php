<?php

require 'init_app.php';
// Front Controller
$controller = new Front();
// Login
//$controller->addPreAction(new Action('common/login'));
// Permission
$controller->addPreAction(new Action('error/permission'));

// Router
$newAction = 'layout';
if ($registry->user->isLogged()) {
    if (isset($request->get['no-layout']) || ($request->isAjax())) {
		$route = '';
		if (isset($request->get['act'])) {
		    $route = $request->get['act'];
		} else if (isset($request->get['_act_'])) {
		    $route = $request->get['_act_'];
		}
		if ($route) {
		    $newAction = $route;
		}
    }
} else {
    $newAction = 'common/login';
}
if ($newAction) {
    $action = new Action($newAction);
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();

?>