<?php
class ControllerReportOrder extends Controller {
	public function index() {  
		$this->load->language('report/order');

		$this->document->title = $this->language->get('heading_title');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('d-m-Y', strtotime('-7 day'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('d-m-Y');
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$filter_customer_group = $this->request->get['filter_customer_group'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}
        if (isset($this->request->get['filter_country_id'])) {
            $filter_country_id = $this->request->get['filter_country_id'];
        } else {
            $filter_country_id = 0;
        }

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
        if (isset($this->request->get['filter_country_id'])) {
            $url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
        }
						
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'report/order' . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('report/order');
        $this->load->model('localisation/country');

        $this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['orders'] = array();
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_order_status_id' => $filter_order_status_id,
            'filter_country_id' => $filter_country_id,
			'start'                  => ($page - 1) * 10,
			'limit'                  => 10
		);
                if(isset($filter_customer))
                    $data['filter_customer'] = $filter_customer;
                if(isset($filter_customer_group))
                    $data['filter_customer_group'] = $filter_customer_group;
		
        	$results = $this->model_report_order->getOrderReport($data);
		
		foreach ($results[1] as $result) {
			$this->data['orders'][] = array(
                        'order_id' => $result['order_id'],
                        'invoice_prefix' => $result['invoice_prefix'],
                        'store_name' => $result['store_name'],
                        'customer_name' => $result['customer_name'],
                        'customer_group' => $result['customer_group'],
                        'telephone' => $result['telephone'],
                        'fax' => $result['fax'],
                        'email' => $result['email'],
                        'shipping_name' => $result['shipping_name'],
                        'shipping_company' => $result['shipping_company'],
                        'shipping_address' => $result['shipping_address'],
                        'shipping_zone' => $result['shipping_zone'],
                        'shipping_country' => $result['shipping_country'],
                        'shipping_method' => $result['shipping_method'],
                        'payment_name' => $result['payment_name'],
                        'payment_company' => $result['payment_company'],
                        'payment_address' => $result['payment_address'],
                        'payment_city' => $result['payment_city'],
                        'payment_postcode' => $result['payment_postcode'],
                        'payment_zone' => $result['payment_zone'],
                        'payment_country' => $result['payment_country'],
                        'payment_method' => $result['payment_method'],
                        'comment' => $result['comment'],
                        'currency_value' => $result['value'],
                        'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                        'order_status' => $result['order_status'],
                        'currency' => $result['currency'],
                        'coupon_code' => $result['coupon_code'],
                        'discount' => $result['discount'],
                        'date' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                        'ip' => $result['ip'],
                        'product_name' => $result['product_name'],
                        'product_model' => $result['product_model'],
                        'product_price' => $result['product_price'],
                        'quantity' => $result['quantity'],
                        'option_name' => $result['option_name'],
                        'option_value' => $result['option_value'],
                        'option_price' => $result['option_price']
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
                $this->data['text_select_customer'] = $this->language->get('text_select_customer');
		$this->data['text_select_customer_group'] = $this->language->get('text_select_customer_group');

                $this->data['column_order_id'] = $this->language->get('column_order_id');
                $this->data['column_invoice_prefix'] = $this->language->get('column_invoice_prefix');
                $this->data['column_store_name'] = $this->language->get('column_store_name');
                $this->data['column_customer_name'] = $this->language->get('column_customer_name');
                $this->data['column_customer_group'] = $this->language->get('column_customer_group');
                $this->data['column_telephone'] =  $this->language->get('column_telephone');
                $this->data['column_fax'] =  $this->language->get('column_fax');
                $this->data['column_email'] =  $this->language->get('column_email');
                $this->data['column_shipping_name'] =  $this->language->get('column_shipping_name');
                $this->data['column_shipping_company'] = $this->language->get('column_shipping_company');
                $this->data['column_shipping_address'] =  $this->language->get('column_shipping_address');
                $this->data['column_shipping_zone'] =  $this->language->get('column_shipping_zone');
                $this->data['column_shipping_country'] =  $this->language->get('column_shipping_country');
                $this->data['column_shipping_method'] = $this->language->get('column_shipping_method');
                $this->data['column_payment_name'] =  $this->language->get('column_payment_name');
                $this->data['column_payment_company'] = $this->language->get('column_payment_company');
                $this->data['column_payment_address'] =  $this->language->get('column_payment_address');
                $this->data['column_payment_city'] =  $this->language->get('column_payment_city');
                $this->data['column_payment_postcode'] =  $this->language->get('column_payment_postcode');
                $this->data['column_payment_zone'] =  $this->language->get('column_payment_zone');
                $this->data['column_payment_country'] =  $this->language->get('column_payment_country');
                $this->data['column_payment_method'] =  $this->language->get('column_payment_method');
                $this->data['column_comment'] =  $this->language->get('column_comment');
                $this->data['column_currency_value'] =  $this->language->get('column_currency_value');
                $this->data['column_order_status'] =  $this->language->get('column_order_status');
		$this->data['column_total'] = $this->language->get('column_total');
                $this->data['column_currency'] =  $this->language->get('column_currency');
                $this->data['column_coupon_code'] =  $this->language->get('column_coupon_code');
                $this->data['column_discount'] =  $this->language->get('column_discount');
                $this->data['column_date'] =  $this->language->get('column_date');
                $this->data['column_ip'] =  $this->language->get('column_ip');
                $this->data['column_product_name'] =  $this->language->get('column_product_name');
                $this->data['column_product_model'] =  $this->language->get('column_product_model');
                $this->data['column_product_price'] =  $this->language->get('column_product_price');
                $this->data['column_quantity'] = $this->language->get('column_quantity');
                $this->data['column_option_name'] = $this->language->get('column_option_name');
                $this->data['column_option_value'] = $this->language->get('column_option_value');
                $this->data['column_option_price'] = $this->language->get('column_option_price');
	
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
                $this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');
                $this->data['button_export'] = $this->language->get('button_export');
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

                $customers = $this->model_report_order->getCustomers();
                $this->data['customers'] = array();
                foreach($customers as $customer){
                    $this->data['customers'][] = array(
                        'value' => $customer['customer_id'],
                        'text' => $customer['customer_name']
                    );
                }
                $this->load->model('sale/customer_group');

                $customers = $this->model_sale_customer_group->getCustomerGroups();
                $this->data['customer_groups'] = array();
                foreach($customers as $customer){
                    $this->data['customer_groups'][] = array(
                        'value' => $customer['customer_group_id'],
                        'text' => $customer['name']
                    );
                }


		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
        if (isset($this->request->get['filter_country_id'])) {
            $url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
        }

        $aUrl = explode("&",$url);
        array_shift($aUrl);
        $aUrl[] = "no-layout=1";
		$this->data['action'] = makeUrl('report/order/export',$aUrl,true);
		$pagination = new Pagination();
		$pagination->total = $results[0];
		$pagination->page = $page;
		$pagination->limit = 10;
        $pagination->style_links = "dataTables_paginate paging_bootstrap";
        $pagination->style_results = "dataTables_info";
        $pagination->list_class = "pagination pagination-sm";
        $pagination->links_wrapper = true;
        $pagination->results_wrapper = true;
        $pagination->wrapper_class = "col-md-6";
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'report/order' . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;	
                if(isset($filter_customer))
                    $this->data['filter_customer'] = $filter_customer;
                if(isset($filter_customer_group))
                    $this->data['filter_customer_group'] = $filter_customer_group;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
        $this->data['filter_country_id'] = $filter_country_id;
		 
		$this->template = 'report/order.tpl';
		
		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}
        public function export(){
            $this->load->language('report/order');
            $this->load->model('report/order');
                $column_order_id = $this->language->get('column_order_id');
                $column_invoice_prefix = $this->language->get('column_invoice_prefix');
                $column_store_name = $this->language->get('column_store_name');
                $column_customer_name = $this->language->get('column_customer_name');
                $column_customer_group = $this->language->get('column_customer_group');
                $column_telephone =  $this->language->get('column_telephone');
                $column_fax =  $this->language->get('column_fax');
                $column_email =  $this->language->get('column_email');
                $column_shipping_name =  $this->language->get('column_shipping_name');
                $column_shipping_company = $this->language->get('column_shipping_company');
                $column_shipping_address =  $this->language->get('column_shipping_address');
                $column_shipping_zone =  $this->language->get('column_shipping_zone');
                $column_shipping_country =  $this->language->get('column_shipping_country');
                $column_shipping_method = $this->language->get('column_shipping_method');
                $column_payment_name =  $this->language->get('column_payment_name');
                $column_payment_company = $this->language->get('column_payment_company');
                $column_payment_address =  $this->language->get('column_payment_address');
                $column_payment_city =  $this->language->get('column_payment_city');
                $column_payment_postcode =  $this->language->get('column_payment_postcode');
                $column_payment_zone =  $this->language->get('column_payment_zone');
                $column_payment_country =  $this->language->get('column_payment_country');
                $column_payment_method =  $this->language->get('column_payment_method');
                $column_comment =  $this->language->get('column_comment');
                $column_currency_value =  $this->language->get('column_currency_value');
                $column_order_status =  $this->language->get('column_order_status');
		$column_total = $this->language->get('column_total');
                $column_currency =  $this->language->get('column_currency');
                $column_coupon_code =  $this->language->get('column_coupon_code');
                $column_discount =  $this->language->get('column_discount');
                $column_date =  $this->language->get('column_date');
                $column_ip =  $this->language->get('column_ip');
                $column_product_name =  $this->language->get('column_product_name');
                $column_product_model =  $this->language->get('column_product_model');
                $column_product_price =  $this->language->get('column_product_price');
                $column_quantity = $this->language->get('column_quantity');
                $column_option_name = $this->language->get('column_option_name');
                $column_option_value = $this->language->get('column_option_value');
                $column_option_price = $this->language->get('column_option_price');
                
                if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d', time());
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$filter_customer_group = $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => 0,
			'limit'                  => 10000
		);
                if(isset($filter_customer))
                    $data['filter_customer'] = $filter_customer;
                if(isset($filter_customer_group))
                    $data['filter_customer_group'] = $filter_customer_group;

        	$results = $this->model_report_order->getOrderReport($data);
                $orders =array();
		foreach ($results[1] as $result) {
			$orders[] = array(
                        'order_id' => $result['order_id'],
                        'invoice_prefix' => $result['invoice_prefix'],
                        'store_name' => $result['store_name'],
                        'customer_name' => $result['customer_name'],
                        'customer_group' => $result['customer_group'],
                        'telephone' => $result['telephone'],
                        'fax' => $result['fax'],
                        'email' => $result['email'],
                        'shipping_name' => $result['shipping_name'],
                        'shipping_company' => $result['shipping_company'],
                        'shipping_address' => $result['shipping_address'],
                        'shipping_zone' => $result['shipping_zone'],
                        'shipping_country' => $result['shipping_country'],
                        'shipping_method' => $result['shipping_method'],
                        'payment_name' => $result['payment_name'],
                        'payment_company' => $result['payment_company'],
                        'payment_address' => $result['payment_address'],
                        'payment_city' => $result['payment_city'],
                        'payment_postcode' => $result['payment_postcode'],
                        'payment_zone' => $result['payment_zone'],
                        'payment_country' => $result['payment_country'],
                        'payment_method' => $result['payment_method'],
                        'comment' => $result['comment'],
                        'currency_value' => $result['value'],
                        'total' => $this->currency->format($result['total'], $this->config->get('config_currency')),
                        'order_status' => $result['order_status'],
                        'currency' => $result['currency'],
                        'coupon_code' => $result['coupon_code'],
                        'discount' => $result['discount'],
                        'date' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                        'ip' => $result['ip'],
                        'product_name' => $result['product_name'],
                        'product_model' => $result['product_model'],
                        'product_price' => $result['product_price'],
                        'quantity' => $result['quantity'],
                        'option_name' => $result['option_name'],
                        'option_value' => $result['option_value'],
                        'option_price' => $result['option_price']
			);
		}

            $output = "<table>
              <thead>
                <tr>
                        <td>". $column_order_id."</td>
                        <td>". $column_invoice_prefix."</td>
                        <td>". $column_store_name."</td>
                        <td>". $column_customer_name."</td>
                        <td>". $column_customer_group."</td>
                        <td>". $column_telephone."</td>
                        <td>". $column_fax."</td>
                        <td>". $column_email."</td>
                        <td>". $column_shipping_name."</td>
                        <td>". $column_shipping_company."</td>
                        <td>". $column_shipping_address."</td>
                        <td>". $column_shipping_zone."</td>
                        <td>". $column_shipping_country."</td>
                        <td>". $column_shipping_method."</td>
                        <td>". $column_payment_name."</td>
                        <td>". $column_payment_company."</td>
                        <td>". $column_payment_address."</td>
                        <td>". $column_payment_city."</td>
                        <td>". $column_payment_postcode."</td>
                        <td>". $column_payment_zone."</td>
                        <td>". $column_payment_country."</td>
                        <td>". $column_payment_method."</td>
                        <td>". $column_comment."</td>
                        <td>". $column_currency_value."</td>
                        <td>". $column_order_status."</td>
                        <td>". $column_total."</td>
                        <td>". $column_currency."</td>
                        <td>". $column_coupon_code."</td>
                        <td>". $column_discount."</td>
                        <td>". $column_date."</td>
                        <td>". $column_ip."</td>
                        <td>". $column_product_name."</td>
                        <td>". $column_product_model."</td>
                        <td>". $column_product_price."</td>
                        <td>". $column_quantity."</td>
                        <td>". $column_option_name."</td>
                        <td>". $column_option_value."</td>
                        <td>". $column_option_price."</td>
                   </tr>
              </thead>
              <tbody> ";
              if ($orders) {
                foreach ($orders as $order) {
                $output .="<tr>
                        <td>". $order['order_id']."</td>
                        <td>". $order['invoice_prefix']."</td>
                        <td>". $order['store_name']."</td>
                        <td>". $order['customer_name']."</td>
                        <td>". $order['customer_group']."</td>
                        <td>". $order['telephone']."</td>
                        <td>". $order['fax']."</td>
                        <td>". $order['email']."</td>
                        <td>". $order['shipping_name']."</td>
                        <td>". $order['shipping_company']."</td>
                        <td>". $order['shipping_address']."</td>
                        <td>". $order['shipping_zone']."</td>
                        <td>". $order['shipping_country']."</td>
                        <td>". $order['shipping_method']."</td>
                        <td>". $order['payment_name']."</td>
                        <td>". $order['payment_company']."</td>
                        <td>". $order['payment_address']."</td>
                        <td>". $order['payment_city']."</td>
                        <td>". $order['payment_postcode']."</td>
                        <td>". $order['payment_zone']."</td>
                        <td>". $order['payment_country']."</td>
                        <td>". $order['payment_method']."</td>
                        <td>". $order['comment']."</td>
                        <td>". $order['currency_value']."</td>
                        <td>". $order['order_status']."</td>
                        <td>". $order['total']."</td>
                        <td>". $order['currency']."</td>
                        <td>". $order['coupon_code']."</td>
                        <td>". $order['discount']."</td>
                        <td>". $order['date']."</td>
                        <td>". $order['ip']."</td>
                        <td>". $order['product_name']."</td>
                        <td>". $order['product_model']."</td>
                        <td>". $order['product_price']."</td>
                        <td>". $order['quantity']."</td>
                        <td>". $order['option_name']."</td>
                        <td>". $order['option_value']."</td>
                        <td>". $order['option_price']."</td>
                </tr>";
                }
                $output .="</tbody></table>";
              }
              header("Content-type: application/octet-stream");
              header("Content-Disposition: attachment;filename=Order_Report.xls");
              header("Pragma: no-cache");
              header("Expires: 0");
//                d($output,true);
              echo $output;
        }
}
?>