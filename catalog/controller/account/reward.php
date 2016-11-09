<?php

class ControllerAccountReward extends Controller {

    public function index() {
	if (!$this->customer->isLogged()) {
	    $this->session->data['redirect'] = makeUrl('account/reward', array(), true, true);

	    $this->redirect(makeUrl('account/login', array(), true, true));
	}

	$this->load->language('account/reward');

	$this->document->setTitle($this->language->get('heading_title'));

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'text' => $this->language->get('text_home'),
	    'href' => makeUrl('common/home', array(), true, true),
	    'separator' => false
	);

	$this->document->breadcrumbs[] = array(
	    'text' => $this->language->get('text_account'),
	    'href' => makeUrl('account/account', array(), true, true),
	    'separator' => $this->language->get('text_separator')
	);

	$this->document->breadcrumbs[] = array(
	    'text' => $this->language->get('text_reward'),
	    'href' => makeUrl('account/reward', array(), true, true),
	    'separator' => $this->language->get('text_separator')
	);

	$this->load->model('account/reward');

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['column_date_added'] = $this->language->get('column_date_added');
	$this->data['column_description'] = $this->language->get('column_description');
	$this->data['column_points'] = $this->language->get('column_points');

	$this->data['text_total'] = $this->language->get('text_total');
	$this->data['text_empty'] = $this->language->get('text_empty');

	$this->data['button_continue'] = $this->language->get('button_continue');

	if (isset($this->request->get['page'])) {
	    $page = $this->request->get['page'];
	} else {
	    $page = 1;
	}

	$this->data['rewards'] = array();

	$data = array(
	    'sort' => 'date_added',
	    'order' => 'DESC',
		//	'start' => ($page - 1) * 3,
		//	'limit' => 3
	);

	//	$reward_total = $this->model_account_reward->getTotalRewards($data);

	$results = $this->model_account_reward->getRewards($data);

	foreach ($results as $result) {
	    $this->data['rewards'][] = array(
		'order_id' => $result['order_id'],
		'points' => $result['points'],
		'description' => $result['description'],
		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
		'href' => makeUrl('account/order/info', array('order_id=' . $result['order_id']), true, true)
	    );
	}
	
	$this->data['total'] = (int) $this->customer->getRewardPoints();

	$this->data['continue'] = makeUrl('account/account', array(), true, true);

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/reward.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/account/reward.tpl';
	} else {
	    $this->template = 'default/template/account/reward.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

}

?>