<?php
class ControllerSaleReturn extends CRUDController {

    protected $error = array();
    protected $status;

    public function getLanguageAlias() {
        return 'sale/return';
    }

    public function init() {
        $this->load->language($this->getLanguageAlias());
        $this->setAlias('sale/return');
        $this->setModel(Make::a('sale/return'));
        $this->status = array(
            $this->language->get('text_disabled'),
            $this->language->get('text_enabled')
        );

        $this->data['rReturnList'] = makeUrl('sale/return/ajaxList');
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['return_add_link'] = HTTPS_SERVER . 'sale/return/add';
        $this->data['return_edit_link'] = HTTPS_SERVER . 'sale/return/edit';
    }

    /*... Return List in return-list ...*/
    protected function getList() {
        parent::getList();

        $this->data['returnItems'] = array();

        $results = $this->model->getReturn();
        //d($results);
        foreach ($results as $result) {
            $this->data['returnItems'][] = array(
                'return_id' => $result['return_id'],
                'order_id' => $result['order_id'],
                'product' => $result['product'],
                'firstname' => $result['firstname'],
                'lastname' => $result['lastname'],
                'return_name' => $result['return_name'],
                'date_added' => $result['date_added'],
                'date_ordered' => $result['date_ordered'],
                'model' => $result['model']
            );

        }

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    /*... fetch data from return table using datagrid ...*/
    public function ajaxList() {
        $this->load->language('sale/return');

        $aStatus = array(__('text_disabled'), __('text_enabled'));
        $columns = array(
            array('db' => 'r.return_id', 'dt' => 0, 'formatter' => function($d, $row) {
                return '<input type="checkbox" value="' . $row['return_id'] . '" name="selected[]" />';
            }),
            array('db' => 'r.return_id', 'dt' => 1, 'formatter' => function($d, $row) {
                return $row['return_id'];
            }),
            array('db' => 'r.order_id','dt' => 2,'formatter' => function($d,$row) {
                return $row['order_id'];
            }),
            array('db' => 'r.firstname', 'db' => 'r.lastname', 'dt' => 3, 'formatter' => function($d,$row){
                return $row['firstname']." ".$row['lastname'];
            }),
            array('db' => 'r.product', 'dt' => 4, 'formatter' => function($d, $row){
                return $row['product'];
            }),
            array('db' => 'r.model', 'dt' => 5, 'formatter' => function($d, $row){
                return $row['model'];
            }),
            array('db' => 'rd.name', 'dt' => 6, 'formatter' => function($d, $row) {
                return $row['name'];
            }),
            array('db' => 'r.date_added', 'dt' => 7,
                'formatter' => function($d, $row) {
                    return $row['date_added'];
                }),
            array('db' => 'r.date_modified', 'dt' => 8,
                'formatter' => function($d, $row) {
                    return $row['data_modified'];
                }),
            array('db' => 'r.return_id','dt' => 9,
                'formatter' => function($d, $row) {
                    return '<a class="btn btn-info btn-sm" href="' . makeUrl('sale/return/update', array('return_id=' . $row['return_id'])) . '"><i class="icon-pencil"></i></a>';
                })

        );

        $oModel = Make::a('sale/return')->table_alias('r')
            ->select('rd.*')
            ->select('r.*')
            ->join('return_status','rd.return_status_id=r.return_status_id','rd');

        //d($oModel);

        echo json_encode(
            QS::DT_simple($this->request->get, $oModel, $columns, 'sale/return', true)
        );
    }

    /*... Getform ...*/
    protected function getForm() {
        parent::getForm();
//
//        $user_group = ARModel::factory('user/user_group')->find_many();
//        $this->data['user_groups'] = CHtml::listData($user_group, 'id', 'name');
        $this->data['model_return_action'] = Make::a('sale/return')->create()->getReturnAction();
        $this->data['model_return_status'] = Make::a('sale/return')->create()->getReturnStatus();
        $this->data['returnAction'] = makeUrl('sale/return/returnaction');
        $this->data['model'] = $oModel = $this->model;
        $this->data['status'] = $this->status;
        $returnHistoryGetList =  Make::a('sale/return')->create()->getHistoryList();

        foreach ($returnHistoryGetList as $data) {
            $this->data['historyItems'][] = array(
                'return_id' => $data['return_id'],
                'status' => $data['name'],
                'comment' => $data['comment'],
                'customer_id' => $data['customer_id'],
                'date_added' => $data['date_added']
            );

        }
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    /*... add & list data from return-history table ...*/
    public function returnaction() {

        $this->load->language('sale/return');
        $this->load->model('sale/return');
        $this->document->title = $this->language->get('return_edit');

        if (isset($this->request->post['return_id'])) {
            $return_id = $this->request->post['return_id'];
            $return_action = $this->request->post['return_action'];
            $return_status = $this->request->post['return_status'];
            $comment = $this->request->post['comment'];
            $checkedValue = $this->request->post['checkedValue'];
            $results = Make::a('sale/return')->create()->getUpdate($return_id, $return_action);
            $resultHistory = Make::a('sale/return')->create()->getHistory($return_id, $return_action, $return_status, $comment, $checkedValue);
            $returnHistoryGetListLast =  Make::a('sale/return')->create()->getHistoryListLast();
        } else {
            $return_id = 0;
        }
        echo json_encode($returnHistoryGetListLast);
    }
}

?>