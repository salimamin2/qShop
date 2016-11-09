<?php

class ControllerSaleShipment extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('sale/shipment');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('sale/order');

        $this->getList();
    }
    public function details() {
        $this->load->language('sale/shipment');
        $this->load->model('sale/order');

        $this->document->title = $this->language->get('heading_title_details');
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'sale/shipment' . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );
        
        $this->document->breadcrumbs[] = array(
            'text' => $this->language->get('heading_title_details'),
            'separator' => ' :: '
        );

        $order_id = $this->request->get['order_id'];
        $aResults = $this->model_sale_order->getShippedOrdersbyOrderId($order_id);
        $aShipment = array();
        foreach ($aResults as $results) {
            $aShipment[$results['order_shipment_id']]['details'] = array(
                'order_shipment_id' => $results['order_shipment_id'],
                'shipment_date' => $results['shipment_date'],
                'tracking_no' => $results['tracking_no'],
                'products' => $results['products'],
                'comments' => $results['comments']
                );
            $aShipment[$results['order_shipment_id']]['products'][] = array(
                'product_id' => $results['product_id'],
                'name' => $results['name'],
                'shipped_qty' => $results['shipped_qty']
                );
        }
        $this->data['aShipment'] = $aShipment;
        $this->data['order_id'] = $order_id;
        $this->data['link_order'] = HTTPS_SERVER . 'sale/order/info&token=&order_id='.$order_id;

        $this->template = 'sale/shipmentdetails.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
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
            'href' => HTTPS_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'sale/shipment' . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['shipments'] = array();

        $data = array(
            'filter_order_id' => $filter_order_id,
            'filter_name' => $filter_name,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_date_added' => $filter_date_added,
            'filter_total' => $filter_total,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $results = $this->model_sale_order->getPendingOrders($data);
        foreach ($results as $result) {

            $this->data['shipments'][] = array(
                'order_id' => $result['order_id'],
                'name' => $result['name'],
                'status' => $result['o_status'],
                'payment_method' => $result['payment_method'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'total' => $this->currency->format($result['total'], $result['currency'], $result['value']),
                'selected' => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected'])
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
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_save'] = $this->language->get('button_save');

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

        $this->data['filter_order_id'] = $filter_order_id;
        $this->data['filter_name'] = $filter_name;
        $this->data['filter_order_status_id'] = $filter_order_status_id;
        $this->data['filter_date_added'] = $filter_date_added;
        $this->data['filter_total'] = $filter_total;

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['link_details'] = HTTPS_SERVER . 'sale/shipment/details';

        $this->template = 'sale/shipment.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }
    public function getOrderProducts(){
        $this->load->model('sale/order');
        $order_products = $this->model_sale_order->getOrderProducts($this->request->post['order_id']);
        $aResults = array();
        foreach ($order_products as $products) {
            $order_shipped_products = $this->model_sale_order->getOrderShippedProducts($this->request->post['order_id'],$products['product_id']);
                $aResults['options'][] = array(

                    'id' => $products['product_id'],

                    'name' => $products['name'],

                    'quantity_ordered' => $products['quantity'],

                    'quantity_shipped' => ($order_shipped_products)?$order_shipped_products:'0',

                    'quantity_remaining' => $products['quantity']-$order_shipped_products

                );
        }
        $this->response->setOutput(json_encode($aResults), $this->config->get('config_compression'));
    }
    public function addPartialShipments(){
        $products = explode(',', $this->request->post['ProductID']);
        $qty = explode(',', $this->request->post['quantity']);
        $products_shipping=array_combine($products,$qty);
        $this->load->model('sale/order');
        $this->model_sale_order->addOrderShipped($this->request->post,$products_shipping);
        $this->response->setOutput(json_encode(array('success' => 'Order successfully shipped Partially')));
    }


}

?>