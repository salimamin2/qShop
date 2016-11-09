<?php

class ControllerSaleOrder extends Controller {

    private $error = array();

    public function index() {
	$this->load->language('sale/order');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('sale/order');

	$this->getList();
    }

    public function insert() {
	$this->load->language('sale/order');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('sale/order');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

	    $this->load->model('sale/customer');

	    $customer_info = $this->model_sale_customer->getCustomer($this->request->post['customer']);

	    if ($customer_info) {
		$this->request->post['firstname'] = $customer_info['firstname'];
		$this->request->post['lastname'] = $customer_info['lastname'];
	    }

	    $this->model_sale_order->addOrder($this->request->post);

	    $this->session->data['success'] = $this->language->get('text_success');

	    $this->redirect(HTTPS_SERVER . 'sale/order&token=' . $this->request->get['token']);
	}

	$this->getForm();
    }

    public function update() {
	$this->load->language('sale/order');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('sale/order');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	    $this->model_sale_order->editOrder($this->request->get['order_id'], $this->request->post);

	    $this->session->data['success'] = $this->language->get('text_success');

	    $this->redirect(HTTPS_SERVER . 'sale/order&token=' . $this->request->get['token']);
	}

	$this->getForm();
    }

    public function delete() {
	$this->load->language('sale/order');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('sale/order');

	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
	    foreach ($this->request->post['selected'] as $order_id) {
		$this->model_sale_order->deleteOrder($order_id);
	    }

	    $this->session->data['success'] = $this->language->get('text_deleted');

	    $url = '';

	    if (isset($this->request->get['filter_order_id'])) {
		$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
	    }

	    if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . $this->request->get['filter_name'];
	    }

	    if (isset($this->request->get['filter_order_status_id'])) {
		$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
	    }

	    if (isset($this->request->get['filter_date_added'])) {
		$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
	    }

	    if (isset($this->request->get['filter_total'])) {
		$url .= '&filter_total=' . $this->request->get['filter_total'];
	    }

	    if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	    }

	    if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
	    }

	    if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
	    }

	    $this->redirect(HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . $url);
	}

	$this->getList();
    }

    private function getList() {
	if (isset($this->request->get['page'])) {
	    $page = $this->request->get['page'];
	} else {
	    $page = 1;
	}

	if (isset($this->request->get['sort'])) {
	    $sort = $this->request->get['sort'];
	} else {
	    $sort = 'o.order_id';
	}

	if (isset($this->request->get['order'])) {
	    $order = $this->request->get['order'];
	} else {
	    $order = 'DESC';
	}

	if (isset($this->request->get['filter_order_id'])) {
	    $filter_order_id = $this->request->get['filter_order_id'];
	} else {
	    $filter_order_id = NULL;
	}

	if (isset($this->request->get['filter_name'])) {
	    $filter_name = $this->request->get['filter_name'];
	} else {
	    $filter_name = NULL;
	}

	if (isset($this->request->get['filter_order_status_id'])) {
	    $filter_order_status_id = $this->request->get['filter_order_status_id'];
	} else {
	    $filter_order_status_id = NULL;
	}

	if (isset($this->request->get['filter_date_added'])) {
	    $filter_date_added = $this->request->get['filter_date_added'];
	} else {
	    $filter_date_added = NULL;
	}

	if (isset($this->request->get['filter_total'])) {
	    $filter_total = $this->request->get['filter_total'];
	} else {
	    $filter_total = NULL;
	}

	$url = '';

	if (isset($this->request->get['filter_order_id'])) {
	    $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
	}

	if (isset($this->request->get['filter_name'])) {
	    $url .= '&filter_name=' . $this->request->get['filter_name'];
	}

	if (isset($this->request->get['filter_order_status_id'])) {
	    $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
	}

	if (isset($this->request->get['filter_date_added'])) {
	    $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
	}

	if (isset($this->request->get['filter_total'])) {
	    $url .= '&filter_total=' . $this->request->get['filter_total'];
	}

	if (isset($this->request->get['page'])) {
	    $url .= '&page=' . $this->request->get['page'];
	}

	if (isset($this->request->get['sort'])) {
	    $url .= '&sort=' . $this->request->get['sort'];
	}

	if (isset($this->request->get['order'])) {
	    $url .= '&order=' . $this->request->get['order'];
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . $url,
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->data['invoice'] = HTTPS_SERVER . 'sale/order/invoice&no-layout=1';
	$this->data['insert'] = HTTPS_SERVER . 'sale/order/insert&token=' . $this->session->data['token'] . $url;
	$this->data['delete'] = HTTPS_SERVER . 'sale/order/delete&token=' . $this->session->data['token'] . $url;

	$this->data['orders'] = array();

	$data = array(
	    'filter_order_id' => $filter_order_id,
	    'filter_name' => $filter_name,
	    'filter_order_status_id' => $filter_order_status_id,
	    'filter_date_added' => $filter_date_added,
	    'filter_total' => $filter_total,
	    'sort' => $sort,
	    'order' => $order,
	    'payment_method' => $payment_method,
	    'start' => ($page - 1) * $this->config->get('config_admin_limit'),
	    'limit' => $this->config->get('config_admin_limit')
	);

	$order_total = $this->model_sale_order->getTotalOrders($data);

	$results = $this->model_sale_order->getOrders($data);
	
	foreach ($results as $result) {
	    $action = array();

	    $action[] = array(
		'text' => $this->language->get('text_view'),
		'icon' => ' icon-eye-open ',
		'class' => 'btn-warning', 
		'href' => HTTPS_SERVER . 'sale/order/info&token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url
	    );

	    $this->data['orders'][] = array(
		'order_id' => $result['order_id'],
		'name' => $result['name'],
		'status' => $result['status'],
		'payment_method' => $result['payment_method'],
		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
		'total' => $this->currency->format($result['total'], $result['currency'], $result['value']),
		'selected' => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
		'action' => $action
	    );
	}

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_no_results'] = $this->language->get('text_no_results');
	$this->data['text_missing_orders'] = $this->language->get('text_missing_orders');

	$this->data['column_order'] = $this->language->get('column_order');
	$this->data['column_name'] = $this->language->get('column_name');
	$this->data['column_status'] = $this->language->get('column_status');
	$this->data['column_date_added'] = $this->language->get('column_date_added');
	$this->data['column_total'] = $this->language->get('column_total');
	$this->data['column_action'] = $this->language->get('column_action');

	$this->data['button_invoices'] = $this->language->get('button_invoices');
	$this->data['button_insert'] = $this->language->get('button_insert');
	$this->data['button_delete'] = $this->language->get('button_delete');
	$this->data['button_filter'] = $this->language->get('button_filter');

	$this->data['token'] = $this->session->data['token'];

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	if (isset($this->session->data['success'])) {
	    $this->data['success'] = $this->session->data['success'];

	    unset($this->session->data['success']);
	} else {
	    $this->data['success'] = '';
	}

	$url = '';

	if (isset($this->request->get['filter_order_id'])) {
	    $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
	}

	if (isset($this->request->get['filter_name'])) {
	    $url .= '&filter_name=' . $this->request->get['filter_name'];
	}

	if (isset($this->request->get['filter_order_status_id'])) {
	    $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
	}

	if (isset($this->request->get['filter_date_added'])) {
	    $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
	}

	if (isset($this->request->get['filter_total'])) {
	    $url .= '&filter_total=' . $this->request->get['filter_total'];
	}

	if ($order == 'ASC') {
	    $url .= '&order=DESC';
	} else {
	    $url .= '&order=ASC';
	}

	if (isset($this->request->get['page'])) {
	    $url .= '&page=' . $this->request->get['page'];
	}

	$this->data['sort_order'] = HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . '&sort=o.order_id' . $url;
	$this->data['sort_name'] = HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . '&sort=name' . $url;
	$this->data['sort_status'] = HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . '&sort=status' . $url;
	$this->data['sort_date_added'] = HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . '&sort=o.date_added' . $url;
	$this->data['sort_total'] = HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . '&sort=o.total' . $url;

	$url = '';

	if (isset($this->request->get['filter_order_id'])) {
	    $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
	}

	if (isset($this->request->get['filter_name'])) {
	    $url .= '&filter_name=' . $this->request->get['filter_name'];
	}

	if (isset($this->request->get['filter_order_status_id'])) {
	    $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
	}

	if (isset($this->request->get['filter_date_added'])) {
	    $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
	}

	if (isset($this->request->get['filter_total'])) {
	    $url .= '&filter_total=' . $this->request->get['filter_total'];
	}

	if (isset($this->request->get['sort'])) {
	    $url .= '&sort=' . $this->request->get['sort'];
	}

	if (isset($this->request->get['order'])) {
	    $url .= '&order=' . $this->request->get['order'];
	}

	$pagination = new Pagination();
	$pagination->total = $order_total;
	$pagination->page = $page;
	$pagination->limit = $this->config->get('config_admin_limit');
	$pagination->text = $this->language->get('text_pagination');
	$pagination->url = HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . $url . '&page={page}';

	$this->data['pagination'] = $pagination->render();

	$this->data['filter_order_id'] = $filter_order_id;
	$this->data['filter_name'] = $filter_name;
	$this->data['filter_order_status_id'] = $filter_order_status_id;
	$this->data['filter_date_added'] = $filter_date_added;
	$this->data['filter_total'] = $filter_total;

	$this->load->model('localisation/order_status');

	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

	$this->data['sort'] = $sort;
	$this->data['order'] = $order;
	$this->data['link_shipment'] = HTTPS_SERVER . 'sale/shipment/details&order_id=';

	$this->template = 'sale/order_list.tpl';
	
	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function getForm() {
	$this->load->model('sale/order');

	if (isset($this->request->get['order_id'])) {
	    $order_id = $this->request->get['order_id'];
	} else {
	    $order_id = 0;
	}

	$order_info = $this->model_sale_order->getOrder($order_id);

	if ($order_info) {
	    $this->load->language('sale/order');

	    $this->document->title = $this->language->get('heading_title');

	    $this->data['heading_title'] = $this->language->get('heading_title');

	    $this->data['text_wait'] = $this->language->get('text_wait');
	    $this->data['text_none'] = $this->language->get('text_none');
	    $this->data['text_default'] = $this->language->get('text_default');

	    $this->data['column_product'] = $this->language->get('column_product');
	    $this->data['column_model'] = $this->language->get('column_model');
	    $this->data['column_quantity'] = $this->language->get('column_quantity');
	    $this->data['column_price'] = $this->language->get('column_price');
	    $this->data['column_total'] = $this->language->get('column_total');
	    $this->data['column_download'] = $this->language->get('column_download');
	    $this->data['column_filename'] = $this->language->get('column_filename');
	    $this->data['column_remaining'] = $this->language->get('column_remaining');
	    $this->data['column_date_added'] = $this->language->get('column_date_added');
	    $this->data['column_status'] = $this->language->get('column_status');
	    $this->data['column_notify'] = $this->language->get('column_notify');
	    $this->data['column_notify_manufacturer'] = $this->language->get('column_notify_manufacturer');
	    $this->data['column_comment'] = $this->language->get('column_comment');

	    $this->data['entry_order_id'] = $this->language->get('entry_order_id');
	    $this->data['entry_invoice_id'] = $this->language->get('entry_invoice_id');
	    $this->data['entry_customer'] = $this->language->get('entry_customer');
	    $this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
	    $this->data['entry_firstname'] = $this->language->get('entry_firstname');
	    $this->data['entry_lastname'] = $this->language->get('entry_lastname');
	    $this->data['entry_email'] = $this->language->get('entry_email');
	    $this->data['entry_ip'] = $this->language->get('entry_ip');
	    $this->data['entry_telephone'] = $this->language->get('entry_telephone');
	    $this->data['entry_fax'] = $this->language->get('entry_fax');
	    $this->data['entry_store_name'] = $this->language->get('entry_store_name');
	    $this->data['entry_store_url'] = $this->language->get('entry_store_url');
	    $this->data['entry_date_added'] = $this->language->get('entry_date_added');
	    $this->data['entry_comment'] = $this->language->get('entry_comment');
	    $this->data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
	    $this->data['entry_payment_method'] = $this->language->get('entry_payment_method');
	    $this->data['entry_total'] = $this->language->get('entry_total');
	    $this->data['entry_order_status'] = $this->language->get('entry_order_status');
	    $this->data['entry_company'] = $this->language->get('entry_company');
	    $this->data['entry_address_1'] = $this->language->get('entry_address_1');
	    $this->data['entry_address_2'] = $this->language->get('entry_address_2');
	    $this->data['entry_city'] = $this->language->get('entry_city');
	    $this->data['entry_postcode'] = $this->language->get('entry_postcode');
	    $this->data['entry_zone'] = $this->language->get('entry_zone');
	    $this->data['entry_zone_code'] = $this->language->get('entry_zone_code');
	    $this->data['entry_country'] = $this->language->get('entry_country');
	    $this->data['entry_status'] = $this->language->get('entry_status');
	    $this->data['entry_notify'] = $this->language->get('entry_notify');
	    $this->data['entry_notify_manufacturer'] = $this->language->get('entry_notify_manufacturer');
	    $this->data['entry_append'] = $this->language->get('entry_append');
	    $this->data['entry_add_product'] = $this->language->get('entry_add_product');

	    $this->data['button_invoice'] = $this->language->get('button_invoice');
	    $this->data['button_save'] = $this->language->get('button_save');
	    $this->data['button_cancel'] = $this->language->get('button_cancel');
	    $this->data['button_generate'] = $this->language->get('button_generate');
	    $this->data['button_add_history'] = $this->language->get('button_add_history');

	    $this->data['tab_order'] = $this->language->get('tab_order');
	    $this->data['tab_product'] = $this->language->get('tab_product');
	    $this->data['tab_history'] = $this->language->get('tab_history');
	    $this->data['tab_payment'] = $this->language->get('tab_payment');
	    $this->data['tab_shipping'] = $this->language->get('tab_shipping');

	    $this->data['text_generate'] = $this->language->get('text_generate');
	    $this->data['text_reward_add'] = $this->language->get('text_reward_add');
	    $this->data['text_reward_remove'] = $this->language->get('text_reward_remove');

	    $this->data['token'] = $this->session->data['token'];

	    $url = '';

	    if (isset($this->request->get['filter_order_id'])) {
		$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
	    }

	    if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . $this->request->get['filter_name'];
	    }

	    if (isset($this->request->get['filter_order_status_id'])) {
		$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
	    }

	    if (isset($this->request->get['filter_date_added'])) {
		$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
	    }

	    if (isset($this->request->get['filter_total'])) {
		$url .= '&filter_total=' . $this->request->get['filter_total'];
	    }

	    if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	    }

	    if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
	    }

	    if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
	    }

	    $this->document->breadcrumbs = array();

	    $this->document->breadcrumbs[] = array(
		'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
		'text' => $this->language->get('text_home'),
		'separator' => FALSE
	    );

	    $this->document->breadcrumbs[] = array(
		'href' => HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . $url,
		'text' => $this->language->get('heading_title'),
		'separator' => ' :: '
	    );

	    if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
	    } else {
		$order_info = array();
	    }

	    if (isset($this->request->get['order_id'])) {
		$this->data['invoice'] = HTTPS_SERVER . 'sale/order/invoice&order_id=' . (int) $this->request->get['order_id'] . '&no-layout=1';
		$this->data['order_id'] = $this->request->get['order_id'];
		$this->data['action'] = HTTPS_SERVER . 'sale/order/update&token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url;
	    } else {
		$this->data['invoice'] = FALSE;
		$this->data['order_id'] = 0;
		$this->data['action'] = HTTPS_SERVER . 'sale/order/insert&token=' . $this->session->data['token'] . $url;
	    }

	    $this->data['cancel'] = HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . $url;

	    $this->data['order_id'] = $this->request->get['order_id'];

	    if ($order_info['invoice_id']) {
		$this->data['invoice_id'] = $order_info['invoice_prefix'] . $order_info['invoice_id'];
	    } else {
		$this->data['invoice_id'] = '';
	    }

	    // These only change for insert, not edit. To be added later
	    $this->data['ip'] = $order_info['ip'];
	    $this->data['store_name'] = $order_info['store_name'];
	    $this->data['store_url'] = $order_info['store_url'];
	    $this->data['comment'] = nl2br($order_info['comment']);
	    $this->data['firstname'] = $order_info['firstname'];
	    $this->data['lastname'] = $order_info['lastname'];
	    //


	    if ($order_info['customer_id']) {
		$this->data['customer'] = HTTPS_SERVER . 'sale/customer/update&token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'];
	    } else {
		$this->data['customer'] = '';
	    }

	    $this->load->model('sale/customer_group');

	    $customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

	    if ($customer_group_info) {
		$this->data['customer_group'] = $customer_group_info['name'];
	    } else {
		$this->data['customer_group'] = '';
	    }

	    if (isset($this->request->post['email'])) {
		$this->data['email'] = $this->request->post['email'];
	    } elseif (isset($order_info['email'])) {
		$this->data['email'] = $order_info['email'];
	    } else {
		$this->data['email'] = '';
	    }

	    if (isset($this->request->post['telephone'])) {
		$this->data['telephone'] = $this->request->post['telephone'];
	    } elseif (isset($order_info['telephone'])) {
		$this->data['telephone'] = $order_info['telephone'];
	    } else {
		$this->data['telephone'] = '';
	    }

	    if (isset($this->request->post['fax'])) {
		$this->data['fax'] = $this->request->post['fax'];
	    } elseif (isset($order_info['fax'])) {
		$this->data['fax'] = $order_info['fax'];
	    } else {
		$this->data['fax'] = '';
	    }

	    if (isset($this->request->post['date_added'])) {
		$this->data['date_added'] = $this->request->post['date_added'];
	    } elseif (isset($order_info['date_added'])) {
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
	    } else {
		$this->data['date_added'] = date($this->language->get('date_format_short'), time());
	    }

	    if (isset($this->request->post['shipping_method'])) {
		$this->data['shipping_method'] = $this->request->post['shipping_method'];
	    } elseif (isset($order_info['shipping_method'])) {
		$this->data['shipping_method'] = $order_info['shipping_method'];
	    } else {
		$this->data['shipping_method'] = '';
	    }

	    if (isset($this->request->post['payment_method'])) {
		$this->data['payment_method'] = $this->request->post['payment_method'];
	    } elseif (isset($order_info['payment_method'])) {
		$this->data['payment_method'] = $order_info['payment_method'];
	    } else {
		$this->data['payment_method'] = '';
	    }

	    // Not Shown variable, but needed for totals
	    if (isset($order_info['currency'])) {
		$this->data['currency'] = $order_info['currency'];
	    } else {
		$this->data['currency'] = $this->config->get('config_currency');
	    }

	    // Not Shown variable, but needed for totals
	    if (isset($order_info['value'])) {
		$this->data['value'] = $order_info['value'];
	    } else {
		$this->data['value'] = '1.0000';
	    }

	    $this->load->model('localisation/order_status');

	    $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

	    if ($order_status_info) {
		$this->data['order_status'] = $order_status_info['name'];
	    } else {
		$this->data['order_status'] = 0;
	    }

	    if (isset($this->request->post['total'])) {
		$this->data['total'] = $this->request->post['total'];
	    } elseif (isset($order_info['total'])) {
		$this->data['total'] = $this->currency->format($order_info['total'], $this->data['currency'], $this->data['value']);
	    } else {
		$this->data['total'] = '';
	    }

	    if (isset($this->request->post['shipping_firstname'])) {
		$this->data['shipping_firstname'] = $this->request->post['shipping_firstname'];
	    } elseif (isset($order_info['shipping_firstname'])) {
		$this->data['shipping_firstname'] = $order_info['shipping_firstname'];
	    } else {
		$this->data['shipping_firstname'] = '';
	    }

	    if (isset($this->request->post['shipping_lastname'])) {
		$this->data['shipping_lastname'] = $this->request->post['shipping_lastname'];
	    } elseif (isset($order_info['shipping_lastname'])) {
		$this->data['shipping_lastname'] = $order_info['shipping_lastname'];
	    } else {
		$this->data['shipping_lastname'] = '';
	    }

	    if (isset($this->request->post['shipping_company'])) {
		$this->data['shipping_company'] = $this->request->post['shipping_company'];
	    } elseif (isset($order_info['shipping_company'])) {
		$this->data['shipping_company'] = $order_info['shipping_company'];
	    } else {
		$this->data['shipping_company'] = '';
	    }

	    if (isset($this->request->post['shipping_address_1'])) {
		$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
	    } elseif (isset($order_info['shipping_address_1'])) {
		$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
	    } else {
		$this->data['shipping_address_1'] = '';
	    }

	    if (isset($this->request->post['shipping_address_2'])) {
		$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
	    } elseif (isset($order_info['shipping_address_2'])) {
		$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
	    } else {
		$this->data['shipping_address_2'] = '';
	    }

	    if (isset($this->request->post['shipping_city'])) {
		$this->data['shipping_city'] = $this->request->post['shipping_city'];
	    } elseif (isset($order_info['shipping_city'])) {
		$this->data['shipping_city'] = $order_info['shipping_city'];
	    } else {
		$this->data['shipping_city'] = '';
	    }

	    if (isset($this->request->post['shipping_postcode'])) {
		$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
	    } elseif (isset($order_info['shipping_postcode'])) {
		$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
	    } else {
		$this->data['shipping_postcode'] = '';
	    }

	    if (isset($this->request->post['shipping_zone'])) {
		$this->data['shipping_zone'] = $this->request->post['shipping_zone'];
	    } elseif (isset($order_info['shipping_zone'])) {
		$this->data['shipping_zone'] = $order_info['shipping_zone'];
	    } else {
		$this->data['shipping_zone'] = '';
	    }

	    if (isset($this->request->post['shipping_zone_id'])) {
		$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
	    } elseif (isset($order_info['shipping_zone_id'])) {
		$this->data['shipping_zone_id'] = $order_info['shipping_zone_id'];
	    } else {
		$this->data['shipping_zone_id'] = '';
	    }

	    if (isset($this->request->post['shipping_country'])) {
		$this->data['shipping_country'] = $this->request->post['shipping_country'];
	    } elseif (isset($order_info['shipping_country'])) {
		$this->data['shipping_country'] = $order_info['shipping_country'];
	    } else {
		$this->data['shipping_country'] = '';
	    }

	    if (isset($this->request->post['shipping_country_id'])) {
		$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
	    } elseif (isset($order_info['shipping_country_id'])) {
		$this->data['shipping_country_id'] = $order_info['shipping_country_id'];
	    } else {
		$this->data['shipping_country_id'] = '';
	    }

	    if (isset($this->request->post['payment_firstname'])) {
		$this->data['payment_firstname'] = $this->request->post['payment_firstname'];
	    } elseif (isset($order_info['payment_firstname'])) {
		$this->data['payment_firstname'] = $order_info['payment_firstname'];
	    } else {
		$this->data['payment_firstname'] = '';
	    }

	    if (isset($this->request->post['payment_lastname'])) {
		$this->data['payment_lastname'] = $this->request->post['payment_lastname'];
	    } elseif (isset($order_info['payment_lastname'])) {
		$this->data['payment_lastname'] = $order_info['payment_lastname'];
	    } else {
		$this->data['payment_lastname'] = '';
	    }

	    if (isset($this->request->post['payment_company'])) {
		$this->data['payment_company'] = $this->request->post['payment_company'];
	    } elseif (isset($order_info['payment_company'])) {
		$this->data['payment_company'] = $order_info['payment_company'];
	    } else {
		$this->data['payment_company'] = '';
	    }

	    if (isset($this->request->post['payment_address_1'])) {
		$this->data['payment_address_1'] = $this->request->post['payment_address_1'];
	    } elseif (isset($order_info['payment_address_1'])) {
		$this->data['payment_address_1'] = $order_info['payment_address_1'];
	    } else {
		$this->data['payment_address_1'] = '';
	    }

	    if (isset($this->request->post['payment_address_2'])) {
		$this->data['payment_address_2'] = $this->request->post['payment_address_2'];
	    } elseif (isset($order_info['payment_address_2'])) {
		$this->data['payment_address_2'] = $order_info['payment_address_2'];
	    } else {
		$this->data['payment_address_2'] = '';
	    }

	    if (isset($this->request->post['payment_city'])) {
		$this->data['payment_city'] = $this->request->post['payment_city'];
	    } elseif (isset($order_info['payment_city'])) {
		$this->data['payment_city'] = $order_info['payment_city'];
	    } else {
		$this->data['payment_city'] = '';
	    }

	    if (isset($this->request->post['payment_postcode'])) {
		$this->data['payment_postcode'] = $this->request->post['payment_postcode'];
	    } elseif (isset($order_info['payment_postcode'])) {
		$this->data['payment_postcode'] = $order_info['payment_postcode'];
	    } else {
		$this->data['payment_postcode'] = '';
	    }

	    if (isset($this->request->post['payment_zone'])) {
		$this->data['payment_zone'] = $this->request->post['payment_zone'];
	    } elseif (isset($order_info['payment_zone'])) {
		$this->data['payment_zone'] = $order_info['payment_zone'];
	    } else {
		$this->data['payment_zone'] = '';
	    }

	    if (isset($this->request->post['payment_zone_id'])) {
		$this->data['payment_zone_id'] = $this->request->post['payment_zone_id'];
	    } elseif (isset($order_info['payment_zone_id'])) {
		$this->data['payment_zone_id'] = $order_info['payment_zone_id'];
	    } else {
		$this->data['payment_zone_id'] = '';
	    }

	    if (isset($this->request->post['payment_country'])) {
		$this->data['payment_country'] = $this->request->post['payment_country'];
	    } elseif (isset($order_info['payment_country'])) {
		$this->data['payment_country'] = $order_info['payment_country'];
	    } else {
		$this->data['payment_country'] = '';
	    }

	    if (isset($this->request->post['payment_country_id'])) {
		$this->data['payment_country_id'] = $this->request->post['payment_country_id'];
	    } elseif (isset($order_info['payment_country_id'])) {
		$this->data['payment_country_id'] = $order_info['payment_country_id'];
	    } else {
		$this->data['payment_country_id'] = '';
	    }

	    $this->load->model('localisation/country');

	    $this->data['countries'] = $this->model_localisation_country->getCountries();

        $oCat = Make::a('catalog/category')->create();
	    $this->data['categories'] = $oCat->getCategories(0);

        $oProd = Make::a('catalog/product')->create();
	    $this->data['products'] = $oProd->getProducts();

	    $this->data['order_products'] = array();

	    if (isset($this->request->get['order_id'])) {
		$order_products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
	    } else {
		$order_products = array();
	    }

	    foreach ($order_products as $order_product) {
		$option_data = array();

		$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);

		foreach ($options as $option) {
		    $option_data[] = array(
			'name' => $option['name'],
			'value' => $option['value']
		    );
		}
		$option_detail = $this->model_sale_order->getOrderProductDetailOptions($this->request->get['order_id'], $order_product['order_product_id']);

		$detail_data = array();
		foreach ($option_detail as $option) {
		    $detail_data[] = array(
			'value_id' => $option['product_type_option_value_id'],
			'type' => $option['product_type_option_id'],
			'value' => $option['value'],
			'name' => $option['name']
		    );
		}

		$this->data['order_products'][] = array(
		    'product_id' => $order_product['product_id'],
		    'name' => $order_product['name'],
		    'model' => $order_product['model'],
		    'option' => $option_data,
		    'detail' => $detail_data,
		    'quantity' => $order_product['quantity'],
		    'price' => $this->currency->format($order_product['price'], $order_info['currency'], $order_info['value']),
		    'raw_price' => $order_product['price'],
		    'total' => $this->currency->format($order_product['total'], $order_info['currency'], $order_info['value']),
		    'raw_total' => $order_product['total'],
		    'href' => HTTPS_SERVER . 'catalog/product/update&token=' . $this->session->data['token'] . '&product_id=' . $order_product['product_id']
		);
	    }
	    if (isset($this->request->get['order_id'])) {
		$this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
	    } else {
		$this->data['totals'] = array();
	    }

	    $this->data['histories'] = array();

	    if (isset($this->request->get['order_id'])) {
		$results = $this->model_sale_order->getOrderHistory($this->request->get['order_id']);
	    } else {
		$results = array();
	    }

	    foreach ($results as $result) {
		$this->data['histories'][] = array(
		    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
		    'status' => $result['status'],
		    'comment' => nl2br($result['comment']),
		    'notify' => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
		    'notify_manufacturer' => $result['notify_manufacturer'] ? $this->language->get('text_yes') : $this->language->get('text_no')
		);
	    }

	    $this->data['downloads'] = array();

	    if (isset($this->request->get['order_id'])) {
		$results = $this->model_sale_order->getOrderDownloads($this->request->get['order_id']);
	    } else {
		$results = array();
	    }

	    foreach ($results as $result) {
		$this->data['downloads'][] = array(
		    'name' => $result['name'],
		    'filename' => $result['mask'],
		    'remaining' => $result['remaining']
		);
	    }

	    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

	    if (isset($order_info['order_status_id'])) {
		$this->data['order_status_id'] = $order_info['order_status_id'];
	    } else {
		$this->data['order_status_id'] = 0;
	    }

	    $this->load->model('setting/extension');

	    $payment_methods = $this->model_setting_extension->getInstalled('payment');

	    foreach ($payment_methods as $payment_method) {
		$this->load->language('payment/' . $payment_method);
		$this->data['payment_methods'][] = array(
		    'code' => $payment_method,
		    'name' => $this->language->get('heading_title')
		);
	    }

	    $shipping_methods = $this->model_setting_extension->getInstalled('shipping');

	    foreach ($shipping_methods as $shipping_method) {
		$this->load->language('shipping/' . $shipping_method);
		$this->data['shipping_methods'][] = array(
		    'code' => $shipping_method,
		    'name' => $this->language->get('heading_title')
		);
	    }

	    $this->data['token'] = $this->session->data['token'];

	    $this->template = 'sale/order_form.tpl';
	    
	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
	} else {
	    $this->load->language('error/not_found');

	    $this->document->title = $this->language->get('heading_title');

	    $this->data['heading_title'] = $this->language->get('heading_title');

	    $this->data['text_not_found'] = $this->language->get('text_not_found');

	    $this->document->breadcrumbs = array();

	    $this->document->breadcrumbs[] = array(
		'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
		'text' => $this->language->get('text_home'),
		'separator' => FALSE
	    );

	    $this->document->breadcrumbs[] = array(
		'href' => HTTPS_SERVER . 'error/not_found&token=' . $this->session->data['token'],
		'text' => $this->language->get('heading_title'),
		'separator' => ' :: '
	    );

	    $this->template = 'error/not_found.tpl';
	    
	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
    }

    public function generate() {
	$this->load->model('sale/order');

	$json = array();

	if (isset($this->request->get['order_id'])) {
	    $json['invoice_id'] = $this->model_sale_order->generateInvoiceId($this->request->get['order_id']);
	}

	$this->load->library('json');

	$this->response->setOutput(Json::encode($json));
    }

    public function history() {
	$this->load->language('sale/order');

	$this->data['error'] = '';
	$this->data['success'] = '';

	$this->load->model('sale/order');

	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
	    if (!$this->user->hasPermission('modify', 'sale/order')) {
		$this->data['error'] = $this->language->get('error_permission');
	    }

	    if (!$this->data['error']) {
		$this->model_sale_order->addOrderHistory($this->request->get['order_id'], $this->request->post);
		if ($this->request->post['order_status_id'] == $this->config->get('config_reward_status')) {
		    $order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		    $this->load->model('sale/customer');
		    $reward_total = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);
		    if (!$reward_total && ($order_info['reward'] > 0)) {

			$this->model_sale_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $this->request->get['order_id'], $order_info['reward'], $this->request->get['order_id']);
			$this->data['success'] = $this->language->get('text_reward_added');
		    } else {
			$json['error'] = $this->language->get('error_action');
		    }
		} else {
		    $this->data['success'] = $this->language->get('text_success');
		}
	    }
	}

	$this->data['text_no_results'] = $this->language->get('text_no_results');

	$this->data['column_date_added'] = $this->language->get('column_date_added');
	$this->data['column_status'] = $this->language->get('column_status');
	$this->data['column_notify'] = $this->language->get('column_notify');
	$this->data['column_notify_manufacturer'] = $this->language->get('column_notify_manufacturer');
	$this->data['column_comment'] = $this->language->get('column_comment');

//	if (isset($this->request->get['page'])) {
//	    $page = $this->request->get['page'];
//	} else {
//	    $page = 1;
//	}

	$this->data['histories'] = array();

	$results = $this->model_sale_order->getOrderHistories($this->request->get['order_id']);

	foreach ($results as $result) {
	    $this->data['histories'][] = array(
		'notify' => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
		'notify_manufacturer' => $result['notify_manufacturer'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
		'status' => $result['status'],
		'comment' => nl2br($result['comment']),
		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
	    );
	}
//	$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);

//	$pagination = new Pagination();
//	$pagination->total = $history_total;
//	$pagination->page = $page;
//	$pagination->limit = 10;
//	$pagination->text = $this->language->get('text_pagination');
//	$pagination->url = makeUrl('sale/order/history',array('order_id=' . $this->request->get['order_id'],'page={page}','no-layout=1'));
//
//	$this->data['pagination'] = $pagination->render();

	$this->template = 'sale/order_history.tpl';
//    QS::d($this->data);

        $this->response->setOutput($this->render((isset($this->request->get['no-layout']) ? true: '')), $this->config->get('config_compression'));
    }

    private function validateForm() {
	if (!$this->user->hasPermission('modify', 'sale/order')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

    private function validateDelete() {
	if (!$this->user->hasPermission('modify', 'sale/order')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

    public function info() {
	$this->load->model('sale/order');
	$this->load->model('tool/image');
	$this->load->model('catalog/product');

	if (isset($this->request->get['order_id'])) {
	    $order_id = $this->request->get['order_id'];
	} else {
	    $order_id = 0;
	}

	$order_info = $this->model_sale_order->getOrder($order_id);

	if ($order_info) {
	    $this->load->language('sale/order');

	    $this->document->setTitle($this->language->get('heading_title'));

	    $this->data['heading_title'] = $this->language->get('heading_title');

	    $this->data['text_order_id'] = $this->language->get('text_order_id');
	    $this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
	    $this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
	    $this->data['text_store_name'] = $this->language->get('text_store_name');
	    $this->data['text_store_url'] = $this->language->get('text_store_url');
	    $this->data['text_customer'] = $this->language->get('text_customer');
	    $this->data['text_customer_group'] = $this->language->get('text_customer_group');
	    $this->data['text_email'] = $this->language->get('text_email');
	    $this->data['text_telephone'] = $this->language->get('text_telephone');
	    $this->data['text_fax'] = $this->language->get('text_fax');
	    $this->data['text_total'] = $this->language->get('text_total');
	    $this->data['text_reward'] = $this->language->get('text_reward');
	    $this->data['text_order_status'] = $this->language->get('text_order_status');
	    $this->data['text_comment'] = $this->language->get('text_comment');
	    $this->data['text_ip'] = $this->language->get('text_ip');
	    $this->data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
	    $this->data['text_user_agent'] = $this->language->get('text_user_agent');
	    $this->data['text_accept_language'] = $this->language->get('text_accept_language');
	    $this->data['text_date_added'] = $this->language->get('text_date_added');
	    $this->data['text_date_modified'] = $this->language->get('text_date_modified');
	    $this->data['text_firstname'] = $this->language->get('text_firstname');
	    $this->data['text_lastname'] = $this->language->get('text_lastname');
	    $this->data['text_company'] = $this->language->get('text_company');
	    $this->data['text_company_id'] = $this->language->get('text_company_id');
	    $this->data['text_tax_id'] = $this->language->get('text_tax_id');
	    $this->data['text_address_1'] = $this->language->get('text_address_1');
	    $this->data['text_address_2'] = $this->language->get('text_address_2');
	    $this->data['text_city'] = $this->language->get('text_city');
	    $this->data['text_postcode'] = $this->language->get('text_postcode');
	    $this->data['text_zone'] = $this->language->get('text_zone');
	    $this->data['text_zone_code'] = $this->language->get('text_zone_code');
	    $this->data['text_country'] = $this->language->get('text_country');
	    $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
	    $this->data['text_payment_method'] = $this->language->get('text_payment_method');
	    $this->data['text_download'] = $this->language->get('text_download');
	    $this->data['text_wait'] = $this->language->get('text_wait');
	    $this->data['text_generate'] = $this->language->get('text_generate');
	    $this->data['text_reward_add'] = $this->language->get('text_reward_add');
	    $this->data['text_reward_remove'] = $this->language->get('text_reward_remove');
	    $this->data['text_country_match'] = $this->language->get('text_country_match');
	    $this->data['text_country_code'] = $this->language->get('text_country_code');
	    $this->data['text_high_risk_country'] = $this->language->get('text_high_risk_country');
	    $this->data['text_distance'] = $this->language->get('text_distance');
	    $this->data['text_free_mail'] = $this->language->get('text_free_mail');
	    $this->data['text_carder_email'] = $this->language->get('text_carder_email');
	    $this->data['text_high_risk_username'] = $this->language->get('text_high_risk_username');
	    $this->data['text_high_risk_password'] = $this->language->get('text_high_risk_password');
	    $this->data['text_bin_match'] = $this->language->get('text_bin_match');
	    $this->data['text_bin_country'] = $this->language->get('text_bin_country');
	    $this->data['text_bin_name_match'] = $this->language->get('text_bin_name_match');
	    $this->data['text_bin_name'] = $this->language->get('text_bin_name');
	    $this->data['text_bin_phone_match'] = $this->language->get('text_bin_phone_match');
	    $this->data['text_bin_phone'] = $this->language->get('text_bin_phone');
	    $this->data['text_customer_phone_in_billing_location'] = $this->language->get('text_customer_phone_in_billing_location');
	    $this->data['text_ship_forward'] = $this->language->get('text_ship_forward');
	    $this->data['text_city_postal_match'] = $this->language->get('text_city_postal_match');
	    $this->data['text_ship_city_postal_match'] = $this->language->get('text_ship_city_postal_match');
	    $this->data['text_score'] = $this->language->get('text_score');
	    $this->data['text_explanation'] = $this->language->get('text_explanation');
	    $this->data['text_risk_score'] = $this->language->get('text_risk_score');
	    $this->data['text_queries_remaining'] = $this->language->get('text_queries_remaining');
	    $this->data['text_maxmind_id'] = $this->language->get('text_maxmind_id');
	    $this->data['text_error'] = $this->language->get('text_error');

	    $this->data['column_image'] = $this->language->get('column_image');
	    $this->data['column_product'] = $this->language->get('column_product');
	    $this->data['column_model'] = $this->language->get('column_model');
	    $this->data['column_quantity'] = $this->language->get('column_quantity');
	    $this->data['column_price'] = $this->language->get('column_price');
	    $this->data['column_total'] = $this->language->get('column_total');
	    $this->data['column_download'] = $this->language->get('column_download');
	    $this->data['column_filename'] = $this->language->get('column_filename');
	    $this->data['column_remaining'] = $this->language->get('column_remaining');

	    $this->data['entry_order_status'] = $this->language->get('entry_order_status');
	    $this->data['entry_notify'] = $this->language->get('entry_notify');
	    $this->data['entry_notify_manufacturer'] = $this->language->get('entry_notify_manufacturer');
	    $this->data['entry_comment'] = $this->language->get('entry_comment');

	    $this->data['button_invoice'] = $this->language->get('button_invoice');
	    $this->data['button_cancel'] = $this->language->get('button_cancel');
	    $this->data['button_add_history'] = $this->language->get('button_add_history');

	    $this->data['tab_order'] = $this->language->get('tab_order');
	    $this->data['tab_payment'] = $this->language->get('tab_payment');
	    $this->data['tab_shipping'] = $this->language->get('tab_shipping');
	    $this->data['tab_product'] = $this->language->get('tab_product');
	    $this->data['tab_history'] = $this->language->get('tab_history');
	    $this->data['tab_fraud'] = $this->language->get('tab_fraud');

	    $this->data['token'] = $this->session->data['token'];

	    $url = '';

	    if (isset($this->request->get['filter_order_id'])) {
		$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
	    }

	    if (isset($this->request->get['filter_customer'])) {
		$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
	    }

	    if (isset($this->request->get['filter_order_status_id'])) {
		$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
	    }

	    if (isset($this->request->get['filter_total'])) {
		$url .= '&filter_total=' . $this->request->get['filter_total'];
	    }

	    if (isset($this->request->get['filter_date_added'])) {
		$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
	    }

	    if (isset($this->request->get['filter_date_modified'])) {
		$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
	    }

	    if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
	    }

	    if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
	    }

	    if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	    }

	    $this->document->breadcrumbs = array();

	    $this->document->breadcrumbs[] = array(
		'text' => $this->language->get('text_home'),
		'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
		'separator' => false
	    );

	    $this->document->breadcrumbs[] = array(
		'text' => $this->language->get('heading_title'),
		'href' => HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . $url,
		'separator' => ' :: '
	    );

	    $this->data['invoice'] = HTTPS_SERVER . 'sale/order/invoice&order_id=' . (int) $this->request->get['order_id'] . '&no-layout=1';
	    $this->data['cancel'] = HTTPS_SERVER . 'sale/order&token=' . $this->session->data['token'] . $url;

	    $this->data['order_id'] = $this->request->get['order_id'];

	    if (isset($order_info['invoice_no']) && $order_info['invoice_no']) {
		$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
	    } else {
		$this->data['invoice_no'] = '';
	    }

	    $this->data['store_name'] = $order_info['store_name'];
	    $this->data['store_url'] = $order_info['store_url'];
	    $this->data['firstname'] = $order_info['firstname'];
	    $this->data['lastname'] = $order_info['lastname'];

	    if ($order_info['customer_id']) {
		$this->data['customer'] = HTTPS_SERVER . 'sale/customer/update&token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'];
	    } else {
		$this->data['customer'] = '';
	    }

	    $this->load->model('sale/customer_group');

	    $customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

	    if ($customer_group_info) {
		$this->data['customer_group'] = $customer_group_info['name'];
	    } else {
		$this->data['customer_group'] = '';
	    }

	    $this->data['email'] = $order_info['email'];
	    $this->data['telephone'] = $order_info['telephone'];
	    $this->data['fax'] = $order_info['fax'];
	    $this->data['comment'] = nl2br($order_info['comment']);
	    $this->data['shipping_method'] = $order_info['shipping_method'];
	    $this->data['payment_method'] = $order_info['payment_method'];
	    $this->data['total'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value']);

	    if ($order_info['total'] < 0) {
		$this->data['credit'] = $order_info['total'];
	    } else {
		$this->data['credit'] = 0;
	    }

	    $this->load->model('sale/customer');

	    //$this->data['credit_total'] = $this->model_sale_customer->getTotalTransactionsByOrderId($this->request->get['order_id']);

	    $this->data['reward'] = $order_info['reward'];

	    $this->data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);


	    $this->load->model('localisation/order_status');

	    $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

	    if ($order_status_info) {
		$this->data['order_status'] = $order_status_info['name'];
	    } else {
		$this->data['order_status'] = '';
	    }

	    $this->data['ip'] = $order_info['ip'];
	    $this->data['forwarded_ip'] = $order_info['ip'];
	    $this->data['user_agent'] = isset($order_info['user_agent'])?$order_info['user_agent']:'';
	    $this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
	    $this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
	    $this->data['payment_firstname'] = $order_info['payment_firstname'];
	    $this->data['payment_lastname'] = $order_info['payment_lastname'];
	    $this->data['payment_company'] = $order_info['payment_company'];
	    $this->data['payment_company_id'] = isset($order_info['payment_company_id'])?$order_info['payment_company_id']:'';
	    $this->data['payment_tax_id'] = isset($order_info['payment_tax_id'])?$order_info['payment_tax_id']:'';
	    $this->data['payment_address_1'] = $order_info['payment_address_1'];
	    $this->data['payment_address_2'] = $order_info['payment_address_2'];
	    $this->data['payment_city'] = $order_info['payment_city'];
	    $this->data['payment_postcode'] = $order_info['payment_postcode'];
	    $this->data['payment_zone'] = $order_info['payment_zone'];
	    $this->data['payment_zone_code'] = $order_info['payment_zone_code'];
	    $this->data['payment_country'] = $order_info['payment_country'];
	    $this->data['shipping_firstname'] = $order_info['shipping_firstname'];
	    $this->data['shipping_lastname'] = $order_info['shipping_lastname'];
	    $this->data['shipping_company'] = $order_info['shipping_company'];
	    $this->data['shipping_address_1'] = $order_info['shipping_address_1'];
	    $this->data['shipping_address_2'] = $order_info['shipping_address_2'];
	    $this->data['shipping_city'] = $order_info['shipping_city'];
	    $this->data['shipping_postcode'] = $order_info['shipping_postcode'];
	    $this->data['shipping_zone'] = $order_info['shipping_zone'];
	    $this->data['shipping_zone_code'] = $order_info['shipping_zone_code'];
	    $this->data['shipping_country'] = $order_info['shipping_country'];

	    $this->data['products'] = array();

	    $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

        $oProduct = Make::a('catalog/product')->create();
	    foreach ($products as $product) {
		$aProduct = $oProduct->getProduct($product['product_id']);

		$option_data = array();
		$option_detail = array();

		$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

		foreach ($options as $option) {
		    if ($option['type'] != 'file') {
			$option_data[] = array(
			    'name' => $option['name'],
			    'value' => $option['value'],
			    'type' => $option['type']
			);
		    } else {
			$option_data[] = array(
			    'name' => $option['name'],
			    'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
			    'type' => $option['type'],
			    'href' => HTTPS_SERVER . 'sale/order/download&token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&order_option_id=' . $option['order_option_id']
			);
		    }
		}

		$option_detail = $this->model_sale_order->getOrderProductDetailOptions($this->request->get['order_id'], $product['order_product_id']);

		$detail_data = array();
		$aDetails = array();
		foreach ($option_detail as $option) {
		    $detail_data[] = array(
			'type' => $option['product_type_option_id'],
			'value_id' => $option['product_type_option_value_id'],
			'value' => $option['value'],
			'name' => $option['name']
		    );
		    if ($option['product_type_option_id'] != 7 && $option['product_type_option_value_id'] != -1) {
			$aDetails[] = $option['product_type_option_value_id'];
		    }
		}

		if (!empty($detail_data)) {
		    $image_name = 'data/customize_shirt/shirt_' . $product['product_id'] . '_' . join('_', $aDetails) . '.jpg';
		    if ($image_name && file_exists(DIR_IMAGE . $image_name)) {
			$image = $image_name;
		    } else {
			$image = 'no_image.jpg';
		    }
		} else {
		    if ($aProduct['image'] && file_exists(DIR_IMAGE . $aProduct['image'])) {
			$image = $aProduct['image'];
		    } else {
			$image = 'no_image.jpg';
		    }
		}

		$this->data['products'][] = array(
		    'order_product_id' => $product['order_product_id'],
		    'product_id' => $product['product_id'],
		    'image' => $this->model_tool_image->resize($image, 150, 150),
		    'name' => $product['name'],
		    'model' => $product['model'],
		    'option' => $option_data,
		    'detail' => $detail_data,
		    'quantity' => $product['quantity'],
		    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency'], $order_info['value']),
		    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency'], $order_info['value']),
		    'href' => HTTPS_SERVER . 'catalog/product/update&product_id=' . $product['product_id']
		);
	    }

	    $this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

	    $this->data['downloads'] = array();

	    foreach ($products as $product) {
		$results = $this->model_sale_order->getOrderDownloads($this->request->get['order_id'], $product['order_product_id']);

		foreach ($results as $result) {
		    $this->data['downloads'][] = array(
			'name' => $result['name'],
			'filename' => $result['mask'],
			'remaining' => $result['remaining']
		    );
		}
	    }

	    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

	    $this->data['order_status_id'] = $order_info['order_status_id'];


	    $this->template = 'sale/order_info.tpl';
	    
	    $this->response->setOutput($this->render());
	} else {
	    $this->language->load('error/not_found');

	    $this->document->setTitle($this->language->get('heading_title'));

	    $this->data['heading_title'] = $this->language->get('heading_title');

	    $this->data['text_not_found'] = $this->language->get('text_not_found');

	    $this->document->breadcrumbs = array();

	    $this->document->breadcrumbs[] = array(
		'text' => $this->language->get('text_home'),
		'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
		'separator' => false
	    );

	    $this->document->breadcrumbs[] = array(
		'text' => $this->language->get('heading_title'),
		'href' => HTTPS_SERVER . 'error/not_found&token=' . $this->session->data['token'],
		'separator' => ' :: '
	    );

	    $this->template = 'error/not_found.tpl';
	    
	    $this->response->setOutput($this->render());
	}
    }

    public function invoice() {
	$this->load->language('sale/order');
//	$this->load->model('catalog/product');

	$this->data['title'] = $this->language->get('heading_title');

	if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
	    $this->data['base'] = HTTPS_SERVER;
	} else {
	    $this->data['base'] = HTTP_SERVER;
	}

	$this->data['direction'] = $this->language->get('direction');
	$this->data['language'] = $this->language->get('code');

	$this->data['text_invoice'] = $this->language->get('text_invoice');

	$this->data['text_order_id'] = $this->language->get('text_order_id');
	$this->data['text_invoice_id'] = $this->language->get('text_invoice_id');
	$this->data['text_date_added'] = $this->language->get('text_date_added');
	$this->data['text_telephone'] = $this->language->get('text_telephone');
	$this->data['text_fax'] = $this->language->get('text_fax');
	$this->data['text_to'] = $this->language->get('text_to');
	$this->data['text_ship_to'] = $this->language->get('text_ship_to');

	$this->data['column_product'] = $this->language->get('column_product');
	$this->data['column_model'] = $this->language->get('column_model');
	$this->data['column_quantity'] = $this->language->get('column_quantity');
	$this->data['column_price'] = $this->language->get('column_price');
	$this->data['column_total'] = $this->language->get('column_total');
	$this->data['column_comment'] = $this->language->get('column_comment');

	$this->data['logo'] = DIR_IMAGE . $this->config->get('config_logo');

	$this->load->model('sale/order');
	$this->load->model('tool/image');

	$this->data['orders'] = array();

	$orders = array();

	if (isset($this->request->post['selected'])) {
	    $orders = $this->request->post['selected'];
	} elseif (isset($this->request->get['order_id'])) {
	    $orders[] = $this->request->get['order_id'];
	}

	foreach ($orders as $order_id) {
	    $order_info = $this->model_sale_order->getOrder($order_id);
	    if ($order_info) {
		if ($order_info['invoice_id']) {
		    $invoice_id = $order_info['invoice_prefix'] . $order_info['invoice_id'];
		} else {
		    $invoice_id = '';
		}

		if ($order_info['shipping_address_format']) {
		    $format = $order_info['shipping_address_format'];
		} else {
		    $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array(
		    '{firstname}',
		    '{lastname}',
		    '{company}',
		    '{address_1}',
		    '{address_2}',
		    '{city}',
		    '{postcode}',
		    '{zone}',
		    '{zone_code}',
		    '{country}'
		);

		$replace = array(
		    'firstname' => $order_info['shipping_firstname'],
		    'lastname' => $order_info['shipping_lastname'],
		    'company' => $order_info['shipping_company'],
		    'address_1' => $order_info['shipping_address_1'],
		    'address_2' => $order_info['shipping_address_2'],
		    'city' => $order_info['shipping_city'],
		    'postcode' => $order_info['shipping_postcode'],
		    'zone' => $order_info['shipping_zone'],
		    'zone_code' => $order_info['shipping_zone_code'],
		    'country' => $order_info['shipping_country']
		);

		$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		if ($order_info['payment_address_format']) {
		    $format = $order_info['payment_address_format'];
		} else {
		    $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = array(
		    '{firstname}',
		    '{lastname}',
		    '{company}',
		    '{address_1}',
		    '{address_2}',
		    '{city}',
		    '{postcode}',
		    '{zone}',
		    '{zone_code}',
		    '{country}'
		);

		$replace = array(
		    'firstname' => $order_info['payment_firstname'],
		    'lastname' => $order_info['payment_lastname'],
		    'company' => $order_info['payment_company'],
		    'address_1' => $order_info['payment_address_1'],
		    'address_2' => $order_info['payment_address_2'],
		    'city' => $order_info['payment_city'],
		    'postcode' => $order_info['payment_postcode'],
		    'zone' => $order_info['payment_zone'],
		    'zone_code' => $order_info['payment_zone_code'],
		    'country' => $order_info['payment_country']
		);

		$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		$product_data = array();

		$products = $this->model_sale_order->getOrderProducts($order_id);
        $oProduct = Make::a('catalog/product')->create();
		foreach ($products as $product) {
		    $aProduct = $oProduct->getProduct($product['product_id']);

		    $option_data = array();
		    $option_detail = array();

		    $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

		    foreach ($options as $option) {
			if ($option['type'] != 'file') {
			    $option_data[] = array(
				'name' => $option['name'],
				'value' => $option['value'],
				'type' => $option['type']
			    );
			} else {
			    $option_data[] = array(
				'name' => $option['name'],
				'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
				'type' => $option['type'],
				'href' => HTTPS_SERVER . 'sale/order/download&token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&order_option_id=' . $option['order_option_id']
			    );
			}
		    }

		    $option_detail = $this->model_sale_order->getOrderProductDetailOptions($order_id, $product['order_product_id']);

		    $detail_data = array();
		    $aDetails = array();
		    foreach ($option_detail as $option) {
			$detail_data[] = array(
			    'type' => $option['product_type_option_id'],
			    'value_id' => $option['product_type_option_value_id'],
			    'value' => $option['value'],
			    'name' => $option['name']
			);
			if ($option['product_type_option_id'] != 7 && $option['product_type_option_value_id'] != -1)
			    $aDetails[] = $option['product_type_option_value_id'];
		    }

		    if (!empty($detail_data)) {
			$image_name = 'data/customize_shirt/shirt_' . $product['product_id'] . '_' . join('_', $aDetails) . '.jpg';
			if ($image_name && file_exists(DIR_IMAGE . $image_name)) {
			    $image = $image_name;
			} else {
			    $image = 'no_image.jpg';
			}
		    } else {
			if ($aProduct['image'] && file_exists(DIR_IMAGE . $aProduct['image'])) {
			    $image = $aProduct['image'];
			} else {
			    $image = 'no_image.jpg';
			}
		    }

		    $product_data[] = array(
			'name' => $product['name'],
			'model' => $product['model'],
			'thumb' => $this->model_tool_image->resize($image, 200, 200),
			'option' => $option_data,
			'detail' => $detail_data,
			'quantity' => $product['quantity'],
			'price' => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
			'total' => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
		    );
		}

		$total_data = $this->model_sale_order->getOrderTotals($order_id);

		$this->data['orders'][] = array(
		    'order_id' => $order_id,
		    'invoice_id' => $invoice_id,
		    'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
		    'store_name' => $order_info['store_name'],
		    'store_url' => rtrim($order_info['store_url'], '/'),
		    'address' => nl2br($this->config->get('config_address')),
		    'telephone' => $this->config->get('config_telephone'),
		    'fax' => $this->config->get('config_fax'),
		    'email' => $this->config->get('config_email'),
		    'shipping_address' => $shipping_address,
		    'payment_address' => $payment_address,
		    'customer_email' => $order_info['email'],
		    'ip' => $order_info['ip'],
		    'customer_telephone' => $order_info['telephone'],
		    'comment' => $order_info['comment'],
		    'product' => $product_data,
		    'total' => $total_data
		);
	    }
	}

	$this->template = 'sale/order_invoice.tpl';

	$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    public function category() {
	$this->load->model('catalog/product');

	if (isset($this->request->get['category_id'])) {
	    $category_id = $this->request->get['category_id'];
	} else {
	    $category_id = 0;
	}

	$product_data = array();

	$results = $this->model_catalog_product->getProductsByCategoryId($category_id);

	foreach ($results as $result) {
	    $product_data[] = array(
		'product_id' => $result['product_id'],
		'name' => $result['name'],
		'model' => $result['model']
	    );
	}

	$this->load->library('json');

	$this->response->setOutput(Json::encode($product_data));
    }

    public function addReward() {
	$this->language->load('sale/order');

	$json = array();

	if (!$this->user->hasPermission('modify', 'sale/order')) {
	    $json['error'] = $this->language->get('error_permission');
	} elseif (isset($this->request->get['order_id'])) {
	    $this->load->model('sale/order');

	    $order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

	    if ($order_info && $order_info['customer_id']) {
		$this->load->model('sale/customer');

		$reward_total = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

		if (!$reward_total && ($order_info['reward'] > 0)) {
		    $this->model_sale_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $this->request->get['order_id'], $order_info['reward'], $this->request->get['order_id']);

		    $json['success'] = $this->language->get('text_reward_added');
		} else {
		    $json['error'] = $this->language->get('error_action');
		}
	    } else {
		$json['error'] = $this->language->get('error_action');
	    }
	}

	$this->response->setOutput(json_encode($json));
    }

    public function removeReward() {
	$this->language->load('sale/order');

	$json = array();

	if (!$this->user->hasPermission('modify', 'sale/order')) {
	    $json['error'] = $this->language->get('error_permission');
	} elseif (isset($this->request->get['order_id'])) {
	    $this->load->model('sale/order');

	    $order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

	    if ($order_info && $order_info['customer_id']) {
		$this->load->model('sale/customer');

		$this->model_sale_customer->deleteReward($this->request->get['order_id']);

		$json['success'] = $this->language->get('text_reward_removed');
	    } else {
		$json['error'] = $this->language->get('error_action');
	    }
	}

	$this->response->setOutput(json_encode($json));
    }

    public function zone() {
	$output = '<select name="' . $this->request->get['type'] . '_id">';

	$this->load->model('localisation/zone');

	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

	$selected_name = '';

	foreach ($results as $result) {
	    $output .= '<option value="' . $result['zone_id'] . '"';

	    if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
		$output .= ' selected="selected"';
		$selected_name = $result['name'];
	    }

	    $output .= '>' . $result['name'] . '</option>';
	}

	if (!$results) {
	    $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
	}

	$output .= '</select>';
	$output .= '<input type="hidden" id="' . $this->request->get['type'] . '_name" name="' . $this->request->get['type'] . '" value="' . $selected_name . '" />';

	$this->response->setOutput($output, $this->config->get('config_compression'));
    }

}

?>