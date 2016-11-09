<?php

class ControllerAccountHistory extends Controller {

    public function index() {
	if (!$this->customer->isLogged()) {
	    $this->session->data['redirect'] = makeUrl('account/history', array(), true, true);
	    $this->redirect(makeUrl('account/login', array(), true, true));
	}
	$this->language->load('account/history');
	$this->document->layout_col = "col2-left-layout";
	$this->document->title = $this->language->get('heading_title');
	$this->document->breadcrumbs = array();
	$this->document->breadcrumbs[] = array('href' => makeUrl('common/home', array(), true), 'text' => $this->language->get('text_home'), 'separator' => $this->language->get('text_separator'));
	$this->document->breadcrumbs[] = array('href' => makeUrl('account/account', array(), true, true), 'text' => $this->language->get('text_account'), 'separator' => $this->language->get('text_separator'));
	$this->document->breadcrumbs[] = array('href' => makeUrl('account/history', array(), true, true), 'text' => $this->language->get('text_history'), 'separator' => FALSE);
	$this->load->model('account/order');
	$order_total = $this->model_account_order->getTotalOrders();
	$this->data['heading_title'] = $this->language->get('heading_title');
	$this->data['text_order'] = $this->language->get('text_order');
	$this->data['text_status'] = $this->language->get('text_status');
	$this->data['text_date_added'] = $this->language->get('text_date_added');
	$this->data['text_customer'] = $this->language->get('text_customer');
	$this->data['text_products'] = $this->language->get('text_products');
	$this->data['text_total'] = $this->language->get('text_total');
	$this->data['text_error'] = $this->language->get('text_error');
	$this->data['button_view'] = $this->language->get('button_view');
	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['action'] = makeUrl('account/history', array(), true, true);
	if (isset($this->request->get['page'])) {
	    $page = $this->request->get['page'];
	} else {
	    $page = 1;
	}
	$this->data['orders'] = array();
	if ($order_total) {
	    $results = $this->model_account_order->getOrders(( $page - 1 ) * 20, 20);
	    foreach ($results as $result) {
		$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
		$this->data['orders'][] = array(
		    'order_id' => $result['order_id'],
		    'name' => $result['firstname'] . ' ' . $result['lastname'],
		    'status' => $result['status'],
		    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
		    'products' => $product_total,
		    'total' => $this->currency->format($result['total'], $result['currency'], $result['value']),
		    'href' => makeUrl('account/invoice', array('order_id=' . $result['order_id']), true, true),
		    'reorder' => makeUrl('account/invoice/addCart', array('order_id=' . $result['order_id']), true, true)
		);
	    }
	}
	$pagination = new Pagination();
	$pagination->total = $order_total;
	$pagination->page = $page;
	$pagination->limit = 20;
	$pagination->text = $this->language->get('text_pagination');
	$pagination->url = makeUrl('account/history', array(), true, true) . '&page=%s';
	$this->data['pagination'] = $pagination->render();
	$this->data['continue'] = makeUrl('common/home', array(), true);
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/history.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/account/history.tpl';
	} else {
	    $this->template = 'default/template/account/history.tpl';
	}
	$this->response->setOutput($this->render(), $this->config->get('config_compression'));

    }

}

?>
