<?php 
class ControllerCatalogProductType extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/product_type');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/product_type');

        $this->getList();
    }

    public function insert() {
        $this->load->language('catalog/product_type');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/product_type');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $product_type_id = $this->model_catalog_product_type->add($this->request->post);
            if($product_type_id){
                $this->session->data['success'] = $this->language->get('text_success');
                $url = $this->makeUrl();
                $this->redirect(HTTPS_SERVER . 'catalog/product_type/update&token=' . $this->session->data['token'] . '&product_type_id=' . $product_type_id . $url);
            }else{
                $this->error['warning'] = __('Internal Server Error');
            }
        }

        $this->getForm();
    }

    public function update() {
        $this->load->language('catalog/product_type');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/product_type');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_product_type->edit($this->request->get['product_type_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->makeUrl();

            $this->redirect(HTTPS_SERVER . 'catalog/product_type/update&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] );
        }
        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/product_type');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/product_type');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_type_id) {
                $this->model_catalog_product_type->delete($product_type_id);
            }

            $this->session->data['success'] = $this->language->get('text_deleted');

            $url = $this->makeUrl();
            


            $this->redirect(HTTPS_SERVER . 'catalog/product_type&token=' . $this->session->data['token'] . $url);
        }

        $this->getList();
    }
    public function makeUrl(){
            if (isset($this->request->get['filter_title'])) {
                $url .= '&filter_title=' . $this->request->get['filter_title'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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
            return $url;

    }

    public function copy() {
        $this->load->language('catalog/product_type');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('catalog/product_type');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $product_type_id) {
                $this->model_catalog_product_type->copy($product_type_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->makeUrl();

            $this->redirect(HTTPS_SERVER . 'catalog/product_type&token=' . $this->session->data['token'] . $url);
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
            $sort = 'title';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['filter_title'])) {
            $filter_title = $this->request->get['filter_title'];
        } else {
            $filter_title = NULL;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = NULL;
        }

        $url = $this->makeUrl();

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
                'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
                'text'      => $this->language->get('text_home'),
                'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
                'href'      => HTTPS_SERVER . 'catalog/product_type&token=' . $this->session->data['token'] . $url,
                'text'      => $this->language->get('heading_title'),
                'separator' => ' :: '
        );

        $this->data['insert'] = HTTPS_SERVER . 'catalog/product_type/insert&token=' . $this->session->data['token'] . $url;
        $this->data['copy'] = HTTPS_SERVER . 'catalog/product_type/copy&token=' . $this->session->data['token'] . $url;
        $this->data['delete'] = HTTPS_SERVER . 'catalog/product_type/delete&token=' . $this->session->data['token'] . $url;

        $this->data['product_types'] = array();

        $data = array(
                'filter_title'	  => $filter_title,
                'filter_status'   => $filter_status,
                'sort'            => $sort,
                'order'           => $order,
                'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
                'limit'           => $this->config->get('config_admin_limit')
        );


        $results = $this->model_catalog_product_type->getList($data);
        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                    'text' => $this->language->get('text_edit'),
                    'href' => HTTPS_SERVER . 'catalog/product_type/update&token=' . $this->session->data['token'] . '&product_type_id=' . $result['product_type_id'] . $url
            );


            $this->data['product_types'][] = array(
                    'product_type_id' => $result['product_type_id'],
                    'title'       => $result['title'],
                    'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                    'total'       => $result['total'],
                    'selected'   => isset($this->request->post['selected']) && in_array($result['product_type_id'], $this->request->post['selected']),
                    'action'     => $action
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');

        $this->data['column_image'] = $this->language->get('column_image');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['column_total'] = $this->language->get('column_total');
        $this->data['column_action'] = $this->language->get('column_action');
        $this->data['column_code'] = $this->language->get('column_code');

        $this->data['button_copy'] = $this->language->get('button_copy');
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

        $url = $this->makeUrl();

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . $this->request->get['filter_title'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['sort_title'] = HTTPS_SERVER . 'catalog/product_type&token=' . $this->session->data['token'] . '&sort=title' . $url;
        $this->data['sort_status'] = HTTPS_SERVER . 'catalog/product_type&token=' . $this->session->data['token'] . '&sort=status' . $url;

        $url = $this->makeUrl();

        $pagination = new Pagination();
        $pagination->total = $product_type_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = HTTPS_SERVER . 'catalog/product_type&token=' . $this->session->data['token'] . $url . '&page={page}';

        $this->data['pagination'] = $pagination->render();

        $this->data['filter_title'] = $filter_title;
        $this->data['filter_status'] = $filter_status;

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'catalog/product_type_list.tpl';

	
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function getForm() {

        $this->data['entry_option'] = $this->language->get('entry_option');
        $this->data['entry_option_value'] = $this->language->get('entry_option_value');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_add_option'] = $this->language->get('button_add_option');
        $this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
        $this->data['button_add_discount'] = $this->language->get('button_add_discount');
        $this->data['button_add_special'] = $this->language->get('button_add_special');
        $this->data['button_add_image'] = $this->language->get('button_add_image');
        $this->data['button_remove'] = $this->language->get('button_remove');
        
        $this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['tab_option'] = $this->language->get('tab_option');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $this->data['error_title'] = $this->error['title'];
        } else {
            $this->data['error_title'] = '';
        }


        $url = $this->makeUrl();

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $this->data['warning'] = $this->session->data['warning'];

            unset($this->session->data['warning']);
        } else {
            $this->data['warning'] = '';
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
                'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
                'text'      => $this->language->get('text_home'),
                'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
                'href'      => HTTPS_SERVER . 'catalog/product_type&token=' . $this->session->data['token'] . $url,
                'text'      => $this->language->get('heading_title'),
                'separator' => ' :: '
        );

        if (!isset($this->request->get['product_type_id'])) {
            $this->data['action'] = HTTPS_SERVER . 'catalog/product_type/insert&token=' . $this->session->data['token'] . $url;
        } else {
            $this->data['action'] = HTTPS_SERVER . 'catalog/product_type/update&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] . $url;
        }

        $this->data['action_remove_option'] = HTTPS_SERVER . 'catalog/product_type/removeOption&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] . $url;
        $this->data['action_remove_option_value'] = HTTPS_SERVER . 'catalog/product_type/removeOptionValue&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] . $url;
        $this->data['cancel'] = HTTPS_SERVER . 'catalog/product_type&token=' . $this->session->data['token'] . $url;
        $this->data['cancel_option'] = HTTPS_SERVER . 'catalog/product_type/update&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] . $url;

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->request->get['product_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $product_type_info = $this->model_catalog_product_type->get($this->request->get['product_type_id']);
            $this->data['product_type_id'] = $this->request->get['product_type_id'];
        } else {
            $this->data['product_type_id'] = '';
        }
        
        $this->load->model('localisation/language');

        $this->data['languages'] = $this->model_localisation_language->getLanguages();
        $this->data['language_id'] = $this->config->get('config_language_id');
        if (isset($this->request->post['title'])) {
            $this->data['title'] = $this->request->post['title'];
        } elseif (isset($product_type_info)) {
            $this->data['title'] = $product_type_info['title'];
        } else {
            $this->data['title'] = "";
        }

        if (isset($this->request->post['status'])) {
            $this->data['status'] = $this->request->post['status'];
        } elseif (isset($product_type_info)) {
            $this->data['status'] = $product_type_info['status'];
        } else {
            $this->data['status'] = array();
        }
        
//        if (isset($this->request->post['product_type_option'])) {
//            $this->data['product_type_options'] = $this->request->post['product_type_option'];
//        } elseif (isset($product_type_info)) {
//            $this->data['product_type_options'] = $this->model_catalog_product_type->getProductTypeOption($this->request->get['product_type_id']);
//        } else {
//            $this->data['product_type_options'] = array();
//        }
        $this->data['product_type_options'] = $this->model_catalog_product_type->getProductTypeOptions($this->request->get['product_type_id']);
        
//        d($this->data['product_type_options']);
        
        $this->template = 'catalog/product_type_form.tpl';
        
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/product_type')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            if (!isset($this->error['warning'])) {
                $this->error['warning'] = $this->language->get('error_required_data');
            }
            return FALSE;
        }
    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/product_type')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        foreach ($this->request->post['selected'] as $product_type_id) {
            if(!$this->model_catalog_product_type->validate_delete($product_type_id)) {
                $this->error['warning'] = 'Product Type is not Empty';
            }
        }
        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function validateCopy() {
        if (!$this->user->hasPermission('modify', 'catalog/product_type')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function getOptionValues() {
        $option_id = $this->request->post['option_id'];
        $languae_id = $this->request->post['language_id'];
        $option_name = $this->request->post['option_name'];
        
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        
        $this->load->model('catalog/product_type');
        $option_values = $this->model_catalog_product_type->getProductTypeOptionValues($option_id);
        $output='';
        $output .= '<label>Option Values for <b>' . $option_name . '</b><input type="hidden" name="product_type_option_id" value="'.$option_id.'" /></label>';
        $output .= '<table class="tablesorter display" id="tbl_option_value">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<td style="padding:5px;">Language</td><td>Option Value</td><td>Sort Order</td><td>Action</td>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        if($option_values) {
        foreach($option_values as $option_value) {
            $output .= '<tr id="' . $option_value['product_type_option_value_id'] . '_' . $option_value['language_id'] . '">';
            $output .= '<td style="padding:5px;" id="lang_' . $option_value['product_type_option_value_id'] . '_' . $option_value['language_id'] . '">' . $option_value['lang'] . '<input type="hidden" name="option_id" value="'.$option_id.'" /></td>';
            $output .= '<td id="name_' . $option_value['product_type_option_value_id'] . '_' . $option_value['language_id'] . '">' . $option_value['name'] . '</td>';
            $output .= '<td id="sort_' . $option_value['product_type_option_value_id'] . '_' . $option_value['language_id'] . '">' . $option_value['sort_order'] . '</td>';
            $output .= '<td>';
            $output .= '[';
            $output .= '<a onclick="editOptionValue(\'' . $option_value['product_type_option_value_id'] . '_' . $option_value['language_id'] .'\');" ><span>Edit</span></a>';
            $output .= '] [';
            $output .= '<a onclick="location = \'' . HTTPS_SERVER . 'catalog/product_type/removeOptionValue&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] . $url . '&product_type_option_value_id=' . $option_value['product_type_option_value_id'] . '\';" ><span>Remove</span></a>';
            $output .= ']';
            $output .= '</td>';
            $output .= '</tr>';
        }
        } else {
            $output .= '<tr><td colspan="4">No values defined.</td></tr>';
        }
        $output .= '</tbody>';
        $output .= '<tfoot>';
        $output .= '<tr class="filter">';
        $output .= '<td>';
        $output .= '<select name="product_type_option_value[new][language_id]">';
        foreach($languages as $language):
        $output .= '<option value="' . $language['language_id'] . '">' . $language['name'] . '</option>';
        endforeach;
        $output .= '</select>';
        $output .= '</td>';
        $output .= '<td><input type="text" name="product_type_option_value[new][name]" value="" /></td>';
        $output .= '<td><input type="text" name="product_type_option_value[new][sort_order]" value="" /></td>';
        $output .= '<td><a onclick="$(\'#form_option\').submit();" class="button"><span>Add New</span></a></td>';
        $output .= '</tr>';
        $output .= '</tfoot>';
        $output .= '</table>';
        $this->response->setOutput($output, $this->config->get('config_compression'));
    }

    public function removeOption() {
        $this->load->model('catalog/product_type');
        $status = $this->model_catalog_product_type->removeProductTypeOption($this->request->get['product_type_id'],$this->request->get['product_type_option_id']);
        if($status['success']) {
            $this->session->data['success'] = $status['success'];
        } else {
            $this->session->data['warning'] = $status['warning'];
        }
        $url = $this->makeUrl();
        $this->redirect(HTTPS_SERVER . 'catalog/product_type/update&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] . $url);
    }
    
    public function removeOptionValue() {
        $this->load->model('catalog/product_type');
        $status = $this->model_catalog_product_type->removeProductTypeOptionValue($this->request->get['product_type_option_value_id']);
        if($status['success']) {
            $this->session->data['success'] = $status['success'];
        } else {
            $this->session->data['warning'] = $status['warning'];
        }
        $url = $this->makeUrl();
        $this->redirect(HTTPS_SERVER . 'catalog/product_type/update&token=' . $this->session->data['token'] . '&product_type_id=' . $this->request->get['product_type_id'] . $url);
    }
}
?>