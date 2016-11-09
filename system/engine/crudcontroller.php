<?php

abstract class CRUDController extends Controller {
    const VIEW=1;
    const FORM=2;
    protected $error = array();

    /* @var $model Model */
    protected $model; //instance of Model class

    public function _pre() {
        $this->init();
        $this->document->title = __('heading_title');
        switch ($this->getMethod()) {
            case 'update':
                $this->model = $this->getModel()->find_one($this->request->get[$this->getModel()->get_id_column_name()]);
                 break;
            case 'delete':
                if (isset($this->request->get['id'])) {
                    $this->model = $this->getModel()->find_one($this->request->get[$this->getModel()->get_id_column_name()]);
                    break;
                }
            default:
                $this->model = $this->getModel()->create();
        }
    }

    public function index() {
        $this->getList();
    }

    public function insert() {

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            d($this->request->post);
            $this->model->setFields($this->request->post);

            $this->model->save();
            if ($this->model->hasErrors()) {
                $this->error = array_merge($this->error, $this->model->getErrors());
            } else {
                $this->session->data['success'] = __('text_insert_success');


                $this->redirect(makeUrl($this->getAlias()) . $this->getUrl());
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
                $this->data['model'] = $this->model;
                $this->session->data['success'] = __('text_update_success');


                $this->redirect(makeUrl($this->getAlias()) . $this->getUrl());
            }
        }

        $this->getForm();
    }

    //partial method implementation should override by child class
    protected function getForm() {
        $this->data['heading_title'] = $this->language->get('heading_title');

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

        $url = $this->getUrl();
        $this->data['url'] = $url;
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl($this->getAlias()) . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get[$this->getModel()->get_id_column_name()])) {
            $this->data['action'] = makeUrl($this->getAlias() . '/insert') . $url;
        } else {
            $this->data['action'] = makeUrl($this->getAlias() . '/update') . '&' . $this->getModel()->get_id_column_name() . '=' . $this->request->get[$this->getModel()->get_id_column_name()] . $url;
        }

        $this->data['cancel'] = makeUrl($this->getAlias()) . $url;


        $this->data['model'] = $this->model;


        $this->template = $this->getAlias() . '_form.tpl';
//        $this->children = array(
//            'common/header',
//            'common/footer'
//        );

        $this->document->addScript('view/javascript/jquery/jquery.validate.min.js', Document::POS_END);
        $this->document->addScriptInline(
                'jQuery.validator.addMethod("greaterThan", function(value, element, params) {
                    if (!/Invalid|NaN/.test(new Date(value))) {
                        return new Date(value) >= new Date($(params).val());
                    }
                    return isNaN(value) && isNaN($(params).val()) || (parseFloat(value) >= parseFloat($(params).val())); 
                },"Must be greater than {0}.");'
                , Document::POS_READY
        );
        $this->document->addScriptInline('$(document.forms[0]).validate(' . json_encode($this->model->getRules()) . ');', Document::POS_READY);
    }

    //partial method implementation should override by child class
    protected function getList() {
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = $this->model->getDefaultSort();
        }

        if (isset($this->request->get['order'])) {
            $order = strtoupper($this->request->get['order']);
        } else {
            $order = strtoupper($this->model->getDefaultOrder());
        }

        $url = $this->getUrl();
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => makeUrl($this->getAlias()) . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['insert'] = makeUrl($this->getAlias() . '/insert') . $url;
        $this->data['delete'] = makeUrl($this->getAlias() . '/delete') . $url;

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_action'] = $this->language->get('column_action');

        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_delete'] = $this->language->get('button_delete');

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
        $this->data['model'] = $this->model;

        $this->data['criteria'] = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $this->data['sort'] = $sort;
        $this->data['order'] = ($order == 'DESC' ? ARModel::ORDER_DESC : ARModel::ORDER_ASC);

        $this->template = $this->getAlias() . '_list.tpl';
//        $this->children = array(
//            'common/header',
//            'common/footer'
//        );
    }

    public function delete() {
        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $id) {
                $this->model = $this->getModel()->find_one($id);
                if (!$this->validateDelete() || !$this->model->delete()) {
                    // $this->error['warning'] = $this->model->title . ' not deleted';
                }
            }
            if (!$this->error) {
                $this->session->data['success'] = sprintf(__('text_delete_success'), count($this->request->post['selected']));

                $this->redirect(makeUrl($this->getAlias()) . $this->getUrl());
            }
        }

        $this->getList();
    }

    protected function validatePermission($action='modify', $permissionAlias='') {
        if (!$permissionAlias) {
            $permissionAlias = $this->getAlias();
        }

        if (!$this->user->hasPermission($action, $permissionAlias)) {
            $this->error['warning'] = __('error_permission');
            return false;
        }
        return true;
    }

    protected function getUrl() {
        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        return $url;
    }

    protected function applyFilter(ORMWrapper $model) {
        //adding filter
        if (isset($this->request->get['filter'])) {

            $filters = $this->getFilter();
            if ($filters) {
                foreach ($filters as $field => $value) {
                    $model->where($field, $value);
                }
            }
            $inFilters = $this->getInFilter();
            if ($inFilters) {
                foreach ($inFilters as $field => $value) {
                    $model->where_in($field, $value);
                }
            }
            $rangeFilters = $this->getRangeFilter();

            if ($rangeFilters) {
                foreach ($rangeFilters as $field => $value) {
                    $model->where_raw($field, $value);
                }
            }

            $likeFilters = $this->getLikeFilter();
            if ($likeFilters) {
                foreach ($likeFilters as $field => $value) {
                    $model->where_like($field, $value);
                }
            }
        }

        $this->getPagination($model);

        return $model;
    }

    protected function getFilter() {
        $filter = array();
        if (isset($this->request->get['filter']['eq']) && count($this->request->get['filter']['eq']) > 0) {
            foreach ($this->request->get['filter']['eq'] as $field => $value) {
                if (!is_array($value)) {
                    $filter[$field] = str_replace('&', '&amp;', $value);
                }
            }
        }
        return $filter;
    }

    protected function getLikeFilter() {
        $filter = array();
        if (isset($this->request->get['filter']['lk']) && count($this->request->get['filter']['lk']) > 0) {
            foreach ($this->request->get['filter']['lk'] as $field => $value) {
                if (!is_array($value)) {
                    $filter[$field] = '%' . str_replace('&', '&amp;', strtolower($value)) . '%';
                }
            }
        }
        return $filter;
    }

    protected function getInFilter() {
        $filter = array();
        if (isset($this->request->get['filter']['in']) && count($this->request->get['filter']['in']) > 0) {
            foreach ($this->request->get['filter']['in'] as $field => $value) {
                $filter[$field] = explode(',', $value);
            }
        }
        return $filter;
    }

    protected function getRangeFilter() {
        $filter = array();
        if (isset($this->request->get['filter']['range']) && count($this->request->get['filter']['range']) > 0) {
            foreach ($this->request->get['filter']['range'] as $field => $value) {
                if (is_array($value)) {
                    $lhs = $field . ' BETWEEN ? and ?';
                    $filter[$lhs] = array(QS::formatSQLDate($value['from']), QS::formatSQLDate($value['to']));
                }
            }
        }
        return $filter;
    }

    protected function getPagination($orm) {
        $oModel = clone $orm;
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $url = $this->getUrl();
        $pagination = new Pagination();
        $pagination->total = $oModel->count();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = makeUrl($this->getAlias()) . $url . '&page={page}';
        $oModel->resetSelect();

        $this->data['pagination'] = $pagination->render();
    }

    abstract function init();
}

?>