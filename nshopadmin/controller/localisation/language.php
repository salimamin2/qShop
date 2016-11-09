<?php

class ControllerLocalisationLanguage extends CRUDController {

    protected $error = array();
    private $page_alias = 'localisation/language';
    protected $status;

    public function init() {
	$this->load->language($this->page_alias);
	$this->setModel(Make::a($this->page_alias));
	$this->setAlias($this->page_alias);
	$this->status = array(__('text_disabled'), __('text_enabled'));
    }

    public function insert() {

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	    $this->error = $this->model->addLanguage($this->request->post);

	    if ($this->error) {
		$this->error = $this->error;
	    } else {
		$this->session->data['success'] = __('text_insert_success');
		$this->redirect(makeUrl($this->getAlias()));
	    }
	}

	$this->getForm();
    }

    public function update() {
	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

	    $this->model->setFields($this->request->post);
	    $this->model->save();
	    if ($this->model->hasErrors()) {
		$this->error = array_merge($this->error, $this->model->getErrors());
	    } else {
		$this->cache->delete('language');
		$this->data['model'] = $this->model;
		$this->session->data['success'] = __('text_update_success');
		$this->redirect(makeUrl($this->getAlias()) . $this->getUrl());
	    }
	}

	$this->getForm();
    }

    public function delete() {
	if (isset($this->request->post['selected'])) {
	    $this->model = $this->getModel()->create();
	    foreach ($this->request->post['selected'] as $id) {
		if ($this->validateDelete()) {
		    $this->model->deleteLanguage($id);
		    // $this->error['warning'] = $this->model->title . ' not deleted';
		} else {
		    $this->error['warning'] = $this->model->name . ' could not deleted';
		}
	    }
	    if (!$this->error) {
		$this->session->data['success'] = sprintf(__('text_delete_success'), count($this->request->post['selected']));

		$this->redirect(self::makeUrl($this->getAlias()) . $this->getUrl());
	    }
	}

	$this->getList();
    }

    protected function getForm() {
	parent::getForm();
	$this->data['aStatus'] = $this->status;
	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    protected function getList() {
	parent::getList();

	$this->data['languages'] = array();

	$results = Make::a('localisation/language')->find_many(true);

	foreach ($results as $result) {
	    $action = array();

	    $action[] = array(
		'text' => $this->language->get('text_edit'),
		'icon' => 'icon-pencil',
        'class' => 'btn-info',
		'href' => makeUrl('localisation/language/update', array('language_id=' . $result['language_id']))
	    );

	    $this->data['languages'][] = array(
		'language_id' => $result['language_id'],
		'name' => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : NULL),
		'code' => $result['code'],
		'sort_order' => $result['sort_order'],
		'selected' => isset($this->request->post['selected']) && in_array($result['language_id'], $this->request->post['selected']),
		'action' => $action
	    );
	}

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