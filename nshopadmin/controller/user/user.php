<?php

class ControllerUserUser extends CRUDController {

    public static $status;

    public function getLanguageAlias() {
	return 'user/user';
    }

    public function init() {
	$this->load->language($this->getLanguageAlias());
	$this->setModel(Make::a('user/user'));
	$this->setAlias('user/user');
        $this->status = array(
          $this->language->get('text_disabled'),
          $this->language->get('text_enabled')
        );
    }

    protected function getList() {
	parent::getList();

	$this->data['users'] = array();
	$url = isset($url) ? $url : '';

	$results = $this->applyFilter($this->getModel())
		->offset($this->data['criteria']['start'])
		->limit($this->data['criteria']['limit'])
		->order_by($this->data['criteria']['sort'], $this->data['criteria']['order'])
		->find_many();
	//d($results,true);
	foreach ($results as $oUser) {
        $action = array();
	    $result = $oUser->toArray();
	    $action[] = array(
		'text' => __('text_edit'),
		'icon' => 'icon-pencil',
        'class' => 'btn-info',
		'href' => makeUrl('user/user/update') . '&id=' . $result['id'] . $url
	    );

	    $this->data['users'][] = array(
		'user_id' => $result['id'],
		'username' => $result['username'],
		'user_group' => $oUser->getUserGroup()->find_one()->name,
		'status' => $this->status[$result['status']],
		'created_at' => date(__('date_format_short'), strtotime($result['created_at'])),
		'selected' => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
		'action' => $action
	    );
	}


	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_no_results'] = $this->language->get('text_no_results');

	$this->data['column_action'] = $this->language->get('column_action');

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    protected function getForm() {
	parent::getForm();


	$this->data['entry_acc_mgr'] = $this->language->get('entry_acc_mgr');
	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
	$this->data['entry_email'] = $this->language->get('entry_email');
	$this->data['entry_user_group'] = $this->language->get('entry_user_group');
	$this->data['entry_status'] = $this->language->get('entry_status');
	$this->data['entry_captcha'] = $this->language->get('entry_captcha');

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_cancel'] = $this->language->get('button_cancel');

	$this->data['tab_general'] = $this->language->get('tab_general');

	$user_group = ARModel::factory('user/user_group')->find_many();
	$this->data['user_groups'] = CHtml::listData($user_group, 'id', 'name');

//	if (isset($this->request->post['status'])) {
//	    $this->data['status'] = $this->request->post['status'];
//	} elseif ($this->model->status) {
//	    $this->data['status'] = $this->model->fastatus;
//	} else {
//	    $this->data['status'] = 0;
//	}

    $this->data['status'] = $this->status;

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    protected function validateForm() {
	if ($this->validatePermission()) {
	    //TODO: Validation for insert or update record
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

    protected function validateDelete() {
	if ($this->validatePermission()) {
	    //TODO: Validation for insert or update record
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>