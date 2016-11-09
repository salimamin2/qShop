<?php

class ControllerReportOnline extends Controller {

    public function index() {
        $this->load->language('report/online');

        $this->document->title = $this->language->get('heading_title');

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('d-m-Y');
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('d-m-Y');
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = '';
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = '';
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

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . $this->request->get['filter_customer'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home',
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'report/online' . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->load->model('report/online');

        $this->data['orders'] = array();

        $data = array(
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_email' => $filter_email,
            'filter_customer' => $filter_customer,
            'start' => ($page - 1) * 100,
            'limit' => 100
        );

        $online_customer_total = $this->model_report_online->getOnlineReportTotal($data);

        $results = $this->model_report_online->getOnlineReport($data);
        foreach ($results as $result) {
            if (stripos($result['url'], HTTP_CATALOG . 'checkout/success') !== FALSE) {
                $url = HTTP_CATALOG . 'checkout/success';
            } else {

                $url = str_replace(HTTP_CATALOG . '', '', $result['url']);
            }

            $this->data['online_customers'][] = array(
                'date' => date('m/d/Y h:i:s', strtotime($result['session_date'])),
                'customer_id' => $result['customer_id'],
                'customer' => $result['customer'],
                'url' => $url,
                'ip' => $result['ip'],
                'activity' => makeUrl('report/online/activity',array('id=' . $result['customer_session_id'],'no-layout=1'),true),
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_date'] = $this->language->get('column_date');
        $this->data['column_customer'] = $this->language->get('column_customer');
        $this->data['column_url'] = $this->language->get('column_url');
        $this->data['column_ip'] = $this->language->get('column_ip');

        $this->data['entry_date_start'] = $this->language->get('entry_date_start');
        $this->data['entry_date_end'] = $this->language->get('entry_date_end');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_customer'] = $this->language->get('entry_customer');

        $this->data['button_filter'] = $this->language->get('button_filter');


        $url = '';

        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
        }

        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . $this->request->get['filter_email'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . $this->request->get['filter_customer'];
        }

        $pagination = new Pagination();
        $pagination->total = $online_customer_total;
        $pagination->page = $page;
        $pagination->limit = 100;
        $pagination->style_links = "dataTables_paginate paging_bootstrap";
        $pagination->style_results = "dataTables_info";
        $pagination->list_class = "pagination pagination-sm";
        $pagination->links_wrapper = true;
        $pagination->results_wrapper = true;
        $pagination->wrapper_class = "col-md-6";
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = HTTPS_SERVER . 'report/online' . $url . '&page={page}';

        $this->data['pagination'] = $pagination->render();

        $this->data['filter_date_start'] = $filter_date_start;
        $this->data['filter_date_end'] = $filter_date_end;
        $this->data['filter_email'] = $filter_email;
        $this->data['filter_customer'] = $filter_customer;

        $this->template = 'report/online.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function activity() {

        $this->load->language('report/online');
        $this->load->model('report/online');

        if (isset($this->request->get['id'])) {
            $id = $this->request->get['id'];
        } else {
            return;
        }
        $result = $this->model_report_online->getOnlineReport(array('filter_id' => $this->request->get['id']));
        if (!$result) {
            return;
        }

        if (strtotime($result[0]['session_date']) < strtotime('2010-11-08')) {
            $this->data['activity'] = $this->getFromFile($result);
        } else {
            $this->data['activity'] = $this->getFromDb($result);
        }
        $this->data['base'] = (HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER;

        $this->template = 'report/online_activity.tpl';
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    function _cleansing($field) {
        $server = str_replace('admin/', '', HTTPS_SERVER);
        $remove = array(
            $server . 'index.php?',
        );
        //$field = preg_replace('/\n/','',$field);
        return str_replace($remove, '', $field);
    }

    function getFromFile($result) {
        $path = DIR_LOGS . "sessions/";
        $result = $result[0];
        $filename = date('Ymd', strtotime($result['session_date'])) . "." . $result['session_id'];
        $text = file_get_contents($path . $filename);

        if ($text) {
            if (stripos($filename, '2010-11-08') === false) {
                $text = explode(";\n", $text);
            } else {
                $text = explode("2010", $text);
            }

            $str = "<table id='tablesorter' cellspacing=0 cellpadding = 0 border=1 width='100%'>\n";
            $str .= "<thead><tr><td>Sno.</td><td>Date</td><t>Remote Host</td><td>Page</td><td>Referer</td><td>Client Browser</td><td>Client OS</td></tr></thead>\n";
            $str .="<tbody>\n";
            foreach ($text as $row => $line) {
                if ($row == 0)
                    continue;
                $data = explode("|", $line);
                $str .= '<tr class="tooltip-target" id="data-target-' . $row . '"><td>' . $row . '</td>';
                foreach ($data as $c => $field) {
                    if ($c > 5) {
                        if ($c == 6) {
                            $str .= ' <div class="tooltip-content" id="data-content-' . $row . '">';
                            $str .= '<pre>' . $field;
                        }
                        else if ($c == 7)
                            $str.= $field . '</pre></div>';
                    }else {
                        $field = $this->_cleansing($field);
                        $str.= "<td>" . (($c == 0 && stripos($filename, '2010-11-05') !== FALSE) ? '2010' : '') . $field . "</td>";
                    }
                }
                $str .="</tr>\n";
            }
            $str .="</tbody></table>";
        }
        return $str;
    }

    function getFromDb($result) {
        $this->load->model('report/online');
        $result = $result[0];
        $session_date = date('Y-m-d', strtotime($result['session_date']));
        $data = array('filter_ip' => $result['ip'],
            'filter_from_date' => $session_date);
        $details = $this->model_report_online->getOnlineActivity($data);
        $str = "<table id='tablesorter' cellspacing=0 cellpadding = 0 border=1 width='100%'>\n";
        $str .= "<thead><tr><td>Sno.</td><td width='10%'>Date</td><td width='6%'>Customer</td><td width='10%'>Remote Host</td><td>Page</td><td>Referer</td><td>Client Browser</td><td>Client OS</td></tr></thead>\n";
        $str .="<tbody>\n";
        if ($details) {
            foreach ($details as $row => $data) {
                $str .= '<tr class="tooltip-target" id="data-target-' . $row . '"><td>' . ($row + 1)
                        . '<div class="tooltip-content" id="data-content-' . $row . '"><pre>' . $data['data'] . '</pre></div></td>';
                $str.= "<td>" . $this->_cleansing($data['created_at']) . "</td>";
                $str.= "<td>";
                if ($data['customer_id']) {
                    $str.= '<a href="' . HTTPS_SERVER . 'sale/customer/update&customer_id=' . $data['customer_id'] . '">' . $data['customer'] . '</a>';
                } else {
                    $str.= $data['customer'];
                }
                $str.= "</td>";
                $str.= "<td>" . $this->_cleansing($data['remote_host']) . "</td>";
                $str.= "<td>" . $this->_cleansing($data['page']) . "</td>";
                $str.= "<td>" . $this->_cleansing($data['referer']) . "</td>";
                $str.= "<td>" . $this->_cleansing($data['user_agent']) . "</td>";
                $str.= "<td>" . $this->_cleansing($data['os']) . "</td>";
                $str .="</tr>\n";
            }
        } else {
            $str.="<tr><td align='center' colspan='8' class='red'> No activity found </td></tr>";
        }
        $str .="</tbody>\n</table>\n";
        return $str;
    }
    
    public function customer() {
        $this->load->model('report/online');
        $data = $this->model_report_online->getCustomers();
        $result = array('results' => $data);
        $this->load->library('json');
        $this->response->setOutput(Json::encode($result));
    }

}

?>