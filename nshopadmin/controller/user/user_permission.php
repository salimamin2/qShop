<?php

class ControllerUserUserPermission extends CRUDController {
    /* @var $model ModelUserUserGroup */

    public function getLanguageAlias() {
        return 'user/user_group';
    }

    public function init() {
        $this->load->language('user/user_group');
        $this->setModel(ARModel::factory('user/user_group'));
        $this->setAlias('user/user_permission');
    }

    public function getList() {
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

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



        $this->data['user_groups'] = array();

        $data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $user_group_total = $this->model->getTotalUserGroups();

        $results = $this->model->getUserGroups($data);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' =>  $this->language->get('text_edit') ,
                'icon' => 'icon-pencil',
                'class' => 'btn-info',
                'href' => makeUrl($this->getAlias() . '/update') . '&id=' . $result['id'] . $url
            );

            $this->data['user_groups'][] = array(
                'id' => $result['id'],
                'name' => $result['name'],
                'selected' => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
                'action' => $action
            );
        }

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
        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['sort_name'] = makeUrl($this->getAlias()) . '&sort=name' . $url;

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $user_group_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = makeUrl($this->getAlias()) . $url . '&page={page}';

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'user/user_group_list.tpl';
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function getForm() {
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_access'] = $this->language->get('entry_access');
        $this->data['entry_modify'] = $this->language->get('entry_modify');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['tab_general'] = $this->language->get('tab_general');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['aTemplate'] = array('', 'Admin', 'User');

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

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

        if (!isset($this->request->get['id'])) {
            $this->data['action'] = makeUrl($this->getAlias() . '/insert') . $url;
        } else {
            $this->data['action'] = makeUrl($this->getAlias() . '/update') . '&id=' . $this->request->get['id'] . $url;
        }

        $this->data['cancel'] = makeUrl($this->getAlias()) . $url;

        if (isset($this->request->get['id']) && $this->request->server['REQUEST_METHOD'] != 'POST') {
            $user_group_info = $this->model->as_array();
            $user_group_info['permission'] = $this->model->getPermission();
        }

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } elseif (isset($user_group_info)) {
            $this->data['name'] = $user_group_info['name'];
        } else {
            $this->data['name'] = '';
        }
        $this->data['model'] = $this->model;

        $ignore = array(
            'common/home',
            'common/home_user',
            'common/layout',
            'common/login',
            'common/logout',
            'common/permission',
            'error/not_found',
            'error/permission',
            'common/footer',
            'common/header',
            'common/menu'
        );

        $this->data['permissions'] = array();

        $files = glob(DIR_APPLICATION . 'controller/*/*.php');

        foreach ($files as $file) {
            $data = explode('/', dirname($file));

            $permission = end($data) . '/' . basename($file, '.php');

            if (!in_array($permission, $ignore)) {
                $this->data['permissions'][] = $permission;
            }
        }

        if (isset($this->request->post['permission'])) {
            $this->data['access'] = $this->request->post['permission']['access'];
        } elseif (isset($user_group_info['permission']['access'])) {
            $this->data['access'] = $user_group_info['permission']['access'];
        } else {
            $this->data['access'] = array();
        }

        if (isset($this->request->post['permission'])) {
            $this->data['modify'] = $this->request->post['permission']['modify'];
        } elseif (isset($user_group_info['permission']['modify'])) {
            $this->data['modify'] = $user_group_info['permission']['modify'];
        } else {
            $this->data['modify'] = array();
        }

        $this->template = 'user/user_group_form.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    protected function validateForm() {

        if ($this->validatePermission() &&
                ((strlen(utf8_decode($this->request->post['name'])) < 3) ||
                (strlen(utf8_decode($this->request->post['name'])) > 64))) {
            $this->error['name'] = $this->language->get('error_name');
        }
        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    protected function validateDelete() {
        if ($this->validatePermission()) {
            if ($this->model)
                $user_total = $this->model->getUsers()->count();

            if ($user_total) {
                $this->error['warning'] = sprintf($this->language->get('error_user'), $user_total);
            }
        }
        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>