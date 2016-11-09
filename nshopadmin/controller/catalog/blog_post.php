<?php
class ControllerCatalogBlogPost extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/blog_post');
        $this->document->title = $this->language->get('heading_title');
        $this->load->model('catalog/blog_post');
        $this->getList();
    }

    private function getList() {
        $this->load->language('catalog/blog_post');
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'catalog/blog_post&token=' . $this->session->data['token'],
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['insert'] = makeUrl('catalog/blog_post/insert');
        $this->data['copy'] = makeUrl('catalog/blog_post/copy');
        $this->data['delete'] = makeUrl('catalog/blog_post/delete');
        $this->data['sblogCateList'] = makeUrl('catalog/blog_post/ajaxList');

        $this->data['products'] = array();

        $this->load->model('tool/image');

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');

        $this->data['column_image'] = $this->language->get('column_image');
        $this->data['column_id'] = $this->language->get('column_id');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['column_action'] = $this->language->get('column_action');
        $this->data['column_code'] = $this->language->get('column_code');

        $this->data['button_copy'] = $this->language->get('button_copy');
        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_delete'] = $this->language->get('button_delete');
        $this->data['button_filter'] = $this->language->get('button_filter');

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } elseif (isset($this->session->data['warning'])) {
            $this->data['error_warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $this->data['error_warning'] = '';
        }


        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }



        $this->template = 'catalog/blog_post_list.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }
    public function ajaxList() {
        $this->load->language('catalog/blog_post');
        $this->load->model('catalog/blog_post');

        $aStatus = array(__('text_disabled'), __('text_enabled'));
        $columns = array(
            array('db' => 'c.blog_post_id', 'dt' => 0, 'formatter' => function($d, $row) {
                return '<input type="checkbox" value="' . $row['blog_post_id'] . '" name="selected[]" />';
            }),
            array('db' => 'c.blog_post_id', 'dt' => 1,'formatter' => function($d,$row) {
                return $row['blog_post_id'];
            }),    
            array('db' => 'title', 'dt' => 2),
            array('db' => 'first_name', 'dt' => 3,'formatter' => function($d,$row) {
                return $row['first_name'] . ' ' . $row['last_name'];
            }),
            array('db' => 'c.sort_order', 'dt' => 4,'formatter' => function($d,$row) { return $row['sort_order']; }),
            array('db' => 'c.blog_post_id', 'dt' => 5,
                'formatter' => function($d, $row) {
                    return '<a class="btn btn-info btn-sm" href="' . makeUrl('catalog/blog_post/update', array('post_id=' . $row['blog_post_id'])) . '"><i class="icon-pencil"></i></a>';
                })
        );

        $oModel = Make::a('catalog/blog_post')
            ->table_alias('c')
            // ->select_expr('c.*,cd.*,ca.*')
            ->select_expr('ca.first_name,ca.last_name,c.blog_post_id,c.image,c.sort_order,c.status,cd.*')
            ->left_outer_join('blog_post_description', array('c.blog_post_id', '=', 'cd.blog_post_id'), 'cd')
            ->left_outer_join('blog_author', array('c.author_id', '=', 'ca.author_id'), 'ca')
            ->where('cd.language_id', (int) $this->config->get('config_language_id'));


        echo json_encode(
            QS::DT_simple($this->request->get, $oModel, $columns, 'catalog/blog_post')
        );
    }
    public function update() {

        $this->load->model('catalog/blog_post');

        $this->load->language('catalog/blog_post');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $oCategories = Make::a('catalog/blog_post')->create();
            $data = $this->request->post;
            $data['user_id'] = $this->user->getId();
            $oModel = $oCategories->editPostCategory($data);


            if (!$oModel) {
                throw new Exception("Error Editing category");
            }
            else{
                $this->session->data['success']=$this->language->get('text_success');
                $this->redirect(makeUrl('catalog/blog_post'));
            }

        }
        $this->getForm();
    }

    public function insert() {
        $this->load->language('catalog/blog_post');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->request->post['user_id']=$this->user->getId();
            $oCategories = Make::a('catalog/blog_post')->create();
            $oModel = $oCategories->addPostCategory($this->request->post);

                $this->session->data['success']=$this->language->get('text_success');
                $this->redirect(makeUrl('catalog/blog_post'));

        }

        $this->getForm();
    }
    public function getForm() {
        $this->load->language('catalog/blog_post');
        $this->load->model('catalog/blog_post');
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->document->title = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if(isset($this->error['description'])){
            $this->data['error_description'] = $this->error['description'];
        } else {
            $this->data['error_description'] = '';
        }

        if(isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }
        else {
            $this->data['success'] = "";
        }


        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'catalog/blog_post&token=' . $this->session->data['token'] . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['post_id'])) {
            $this->data['action'] = makeUrl('catalog/blog_post/insert');
        } else {
            $this->data['post_id'] = $this->request->get['post_id'];
            $this->data['action'] = makeUrl('catalog/blog_post/update', array('post_id=' . $this->request->get['post_id']));
        }

        $this->data['languages'] = Make::a('localisation/language')->find_many(true);

        $this->data['cancel'] = makeUrl('catalog/blog_post');

        $this->data['token'] = $this->session->data['token'];

        $oModel = $this->model;

        $this->data['model'] = $oModel;
        $this->load->model('tool/image');
        $this->data['aStatus'] = $this->aStatus;
        
        $this->data['preview_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

        $oBlog = Make::a('catalog/blog_post')
            ->table_alias('p')
            ->select_expr('p.*,pd.*,pa.author_id,pa.first_name,pa.last_name')
            ->inner_join('blog_post_description', array('pd.blog_post_id', '=', 'p.blog_post_id'), 'pd')
            ->inner_join('blog_author', array('p.author_id', '=', 'pa.author_id'), 'pa')
            ->where('pd.blog_post_id', $this->request->get['post_id'])
            ->find_many(true);
        if($oBlog){
            foreach($oBlog as $i => $row) {
                if($i == 0)
                    $blogInfo = $row;
                // $this->data['blogs'][$row['language_id']] = $row; 
                $blogDesc[$row['language_id']] = $row;
            }
        }

        foreach ($this->data['languages'] as $language) {
            $array = array();
            $language_id = $language['language_id'];
            $value = $this->request->post['description'][$language_id];

            if(isset($value['title'])){
                $array['title'] = $value['title'];
            }
            else if(isset($blogDesc[$language_id]['title'])) {
                $array['title'] = $blogDesc[$language_id]['title'];
            }
            else{
                $array['title'] = '';
            }

            if(isset($value['description'])){
                $array['description'] = $value['description'];
            }
            else if(isset($blogDesc[$language_id]['description'])) {
                $array['description'] = $blogDesc[$language_id]['description'];
            }
            else{
                $array['description'] = '';
            }

            if(isset($value['meta_title'])){
                $array['meta_title']=$value['meta_title'];
            }
            else if(isset($blogDesc[$language_id]['meta_title'])) {
                $array['meta_title'] = $blogDesc[$language_id]['meta_title'];
            }
            else{
                $array['meta_title'] = '';
            }

            if(isset($value['meta_link'])){
                $array['meta_link']=$value['meta_link'];
            }
            else if(isset($blogDesc[$language_id]['meta_link'])) {
                $array['meta_link'] = $blogDesc[$language_id]['meta_link'];
            }
            else{
                $array['meta_link'] = '';
            }

            if(isset($value['meta_keywords'])){
                $array['meta_keywords']=$value['meta_keywords'];
            }
            else if(isset($blogDesc[$language_id]['meta_keywords'])) {
                $array['meta_keywords'] = $blogDesc[$language_id]['meta_keywords'];
            }
            else{
                $array['meta_keywords'] = '';
            }

            if(isset($value['meta_description'])){
                $array['meta_description']=$value['meta_description'];
            }
            else if(isset($blogDesc[$language_id]['meta_description'])) {
                $array['meta_description'] = $blogDesc[$language_id]['meta_description'];
            }
            else{
                $array['meta_description'] = '';
            }

            $this->data['blogs'][$language_id] = $array;
        }

        if (isset($this->request->post['blog']['blog_category_id'])) {
            $this->data['blog_category_id'] = $this->request->post['blog']['blog_category_id'];
        }
        else if(isset($blogInfo['blog_category_id'])) {
            $this->data['blog_category_id'] = $blogInfo['blog_category_id'];
        }
        else {
            $this->data['blog_category_id'] = '';
        }

        if (isset($this->request->post['blog']['author_id'])) {
            $this->data['author_id'] = $this->request->post['blog']['author_id'];
        }
        else if(isset($blogInfo['author_id'])) {
            $this->data['author_id'] = $blogInfo['author_id'];
        }
        else {
            $this->data['author_id'] = '';
        }

        if (isset($this->request->post['blog']['sort_order'])) {
            $this->data['sort_order'] = $this->request->post['blog']['sort_order'];
        }
        else if(isset($blogInfo['sort_order'])) {
            $this->data['sort_order'] = $blogInfo['sort_order'];
        }
        else {
            $this->data['sort_order'] = '';
        }

        if (isset($this->request->post['blog']['status'])) {
            $this->data['status'] = $this->request->post['blog']['status'];
        }
        else if(isset($blogInfo['status'])) {
            $this->data['status'] = $blogInfo['status'];
        }
        else {
            $this->data['status'] = '';
        }

        if (isset($this->request->post['blog']['featured'])) {
            $this->data['featured'] = $this->request->post['blog']['featured'];
        }
        else if(isset($blogInfo['featured'])) {
            $this->data['featured'] = $blogInfo['featured'];
        }
        else {
            $this->data['featured'] = '';
        }

        if (isset($this->request->post['image'])) {
            $this->data['image'] = $this->request->post['image'];
        } 
        elseif (isset($blogInfo['image'])) {
            $this->data['image'] = $blogInfo['image'];
        } 
        else {
            $this->data['image'] = '';
        }

        if (isset($this->request->post['thumb'])) {
            $this->data['thumb'] = HTTPS_IMAGE . $this->request->post['thumb'];
        } 
        elseif (isset($blogInfo['thumb'])) {
            $this->data['thumb'] = $blogInfo['thumb'];
        } 
        else {
            $this->data['thumb'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image'])) {
            $this->data['preview'] = HTTPS_IMAGE . $this->request->post['image'];
        }
        elseif(isset($blogInfo['image']) && $blogInfo['image'] != '' && file_exists(DIR_IMAGE . $blogInfo['image'])) {
            $this->data['preview'] = $this->model_tool_image->resize($blogInfo['image'], 100, 100);
        }
        else {
            $this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        if (isset($this->request->post['thumb'])) {
            $this->data['preview_thumb'] = HTTPS_IMAGE.'' . $this->request->post['thumb'];
        }
        elseif(isset($blogInfo['thumb']) && $blogInfo['thumb'] != '' && file_exists(DIR_IMAGE . $blogInfo['thumb'])) {
            $this->data['preview_thumb'] = HTTPS_IMAGE.''.$blogInfo['thumb'];
        }
        else {
            $this->data['preview_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        $this->data['blog_author']= Make::a('catalog/blog_author')
                                        ->table_alias('c')
                                        ->select_expr('c.*')
                                        ->find_many(true);

        $this->data['blog_category']= Make::a('catalog/blog_category')
                                            ->table_alias('c')
                                            ->select_expr('c.*')
                                            ->where('c.language_id', (int) $this->config->get('config_language_id'))
                                            ->find_many(true);

        $Description = Make::a('catalog/blog_post')->raw_query("SELECT keyword FROM url_alias WHERE query = 'blog_post_id=".$this->request->get['post_id']."'");

        if (isset($this->request->post['keyword'])) {
            $this->data['seo_keyword'] = $this->request->post['keyword'];
        }
        else if($Description){
            $Rdescription=$Description->toArray();
            $this->data['seo_keyword']=$Rdescription[0]['keyword'];
        }
        else {
            $this->data['seo_keyword'] = '';
        }


        $this->data['directory'] = "blog";


        $this->document->addScriptInline("
        $('#fileupload').fileupload({
            url: '".makeUrl("common/filemanager/upload")."',
            dataType: 'json',
            dropZone: $(this).parent(),
            done: function(e,data) {

                var result = data.result;
                if(!result.hasOwnProperty('error')) {
                    $('#preview').attr('src','".HTTP_IMAGE."data/blog/'+data.files[0].name);
                    $('input[name=image]').val('data/blog/'+data.files[0].name);
                }
                else {
                    alert(result.error);
                }
            }
        });",Document::POS_READY);

        $this->document->addScriptInline("
        $('#fileupload1').fileupload({
            url: '".makeUrl("common/filemanager/uploadThumb")."',
            dataType: 'json',
            dropZone: $(this).parent(),
            done: function(e,data) {
            
                var result = data.result;
                if(!result.hasOwnProperty('error')) {
                    $('#preview1').attr('src','".HTTP_IMAGE."data/blog/'+data.files[0].name);
                    $('input[name=thumb]').val('data/blog/'+data.files[0].name);
                }
                else {
                    alert(result.error);
                }
            }
        });",Document::POS_READY);


        $this->template = 'catalog/blog_post_form.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validateForm() {
        $this->load->language('catalog/blog_post');

        if (!$this->user->hasPermission('modify', 'catalog/blog_post')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }


        foreach ($this->request->post['description'] as $language_id => $value) {
            if ((strlen(utf8_decode($value['title'])) < 2) || (strlen(utf8_decode($value['title'])) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');

            }
            if ($value['description']=='') {
                $this->error['description'][$language_id] = 'Enter description';

            }
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
    public function delete() {
        $this->load->language('catalog/blog_post');

        $this->document->title = $this->language->get('heading_title');
        if (isset($this->request->post['selected'])) {
            $oCategories = Make::a('catalog/blog_post')->create();
            $oModel = $oCategories->deletePostCategory($this->request->post);
            if(isset($result)) {
                $this->error = $this->language->get('text_error');
            }
            else {
                $this->session->data['success'] = $this->language->get('text_deleted');
                $this->redirect(makeUrl('catalog/blog_post'));
            }
        }

        $this->getList();
    }

}


?>