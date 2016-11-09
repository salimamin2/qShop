<?php
class ControllerCatalogBlogAuthor extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('catalog/blog_author');
        $this->document->title = $this->language->get('heading_title');
        $this->load->model('catalog/blog_author');
        $this->getList();
    }

    private function getList() {
        $this->load->language('catalog/blog_author');
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'catalog/blog_author&token=' . $this->session->data['token'],
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['insert'] = makeUrl('catalog/blog_author/insert');
        $this->data['delete'] = makeUrl('catalog/blog_author/delete');
        $this->data['blogauthorList'] = makeUrl('catalog/blog_author/ajaxList');

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
        $this->data['column_model'] = $this->language->get('column_model');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
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



        $this->template = 'catalog/blog_author_list.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }
    public function ajaxList() {
        $this->load->language('catalog/blog_author');

        $aStatus = array(__('text_disabled'), __('text_enabled'));
        $columns = array(
            array('db' => 'author_id', 'dt' => 0, 'formatter' => function($d, $row) {
                return '<input type="checkbox" value="' . $row['author_id'] . '" name="selected[]" />';
            }),
            array('db' => 'author_id', 'dt' => 1,'formatter' => function($d,$row) {
                return $row['author_id'];
            }),
            array('db' => 'first_name', 'dt' => 2),
            array('db' => 'last_name', 'dt' => 3),
            array('db' => 'sort_order', 'dt' => 4),
            array('db' => 'author_id', 'dt' => 5,
                'formatter' => function($d, $row) {
                    return '<a class="btn btn-info btn-sm" href="' . makeUrl('catalog/blog_author/update', array('author_id=' . $row['author_id'])) . '"><i class="icon-pencil"></i></a>';
                })
        );

        $oModel = Make::a('catalog/blog_author')
            ->table_alias('c')
            ->select_expr('c.*');

        echo json_encode(
            QS::DT_simple($this->request->get, $oModel, $columns, 'catalog/blog_author')
        );
    }
    public function insert() {
        $value = array();
        $this->load->language('catalog/blog_author');
        $this->document->title = $this->language->get('heading_title');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')&& $this->validateForm()) {
            $this->request->post['user_id']=$this->user->getId();
            $oAuthors = Make::a('catalog/blog_author')->create();
            $aResult = $oAuthors->addPostAuthor($this->request->post);
            $value = $this->request->post;
            if(isset($aResult['error'])){
                $this->data['warning'] = $aResult['error'];
            } else {
                $this->session->data['success']=$this->language->get('text_success');
                $this->redirect(makeUrl('catalog/blog_author'));
            }
        }

        $this->getForm();
    }
    public function update() {
        $value = array();
        $this->load->model('catalog/blog_author');

        $this->load->language('catalog/blog_author');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $oCategories = Make::a('catalog/blog_author')->create();
            $aResult = $oCategories->editBlogAuthors($this->request->post);

            $value = $this->request->post;
            if (isset($aResult['error'])) {
                $this->data['warning'] = $aResult['error'];
            }
            else{
                $this->session->data['success']=$this->language->get('text_success');
                $this->redirect(makeUrl('catalog/blog_author'));
            }

        }
        $this->getForm();
    }
    public function delete() {
        $this->load->language('catalog/blog_author');

        $this->document->title = $this->language->get('heading_title');
        if (isset($this->request->post['selected'])) {
            $oCategories = Make::a('catalog/blog_author')->create();
            $aResult = $oCategories->deleteAuthors($this->request->post);
            if(isset($aResult['error'])) {
                $this->error['warning'] = $aResult['error'];//$this->language->get('text_error');
            }
            else {
                $this->session->data['success'] = $this->language->get('text_deleted');
                $this->redirect(makeUrl('catalog/blog_author'));
            }
        }

        $this->getList();
    }
    public function getForm() {
        $this->load->language('catalog/blog_author');
        $this->load->model('catalog/blog_author');
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

        if (isset($this->error['first_name'])) {
            $this->data['error_first_name'] = $this->error['first_name'];
        } else {
            $this->data['error_first_name'] = '';
        }
        if(isset($this->error['last_name'])){

            $this->data['error_last_name'] = $this->error['last_name'];
        } else {
            $this->data['error_last_name'] = '';
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
            'href' => HTTPS_SERVER . 'catalog/blog_author&token=' . $this->session->data['token'] . $url,
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['author_id'])) {
            $this->data['action'] = makeUrl('catalog/blog_author/insert');
        } else {
            $this->data['author_id'] = $this->request->get['author_id'];
            $this->data['action'] = makeUrl('catalog/blog_author/update', array('author_id=' . $this->request->get['author_id']));
        }


        $this->data['cancel'] = makeUrl('catalog/blog_author');

        $this->data['token'] = $this->session->data['token'];

        $oModel = $this->model;

        $this->data['model'] = $oModel;
        $this->load->model('tool/image');
        $this->data['aStatus'] = $this->aStatus;
        if(isset($this->request->get['author_id'])){
            $oProd = Make::a('catalog/blog_author')
                ->table_alias('p')
                ->select_expr('p.*')
                ->where('p.author_id', $this->request->get['author_id'])
                ->find_one();
            if($oProd){
                $value=$oProd->toArray();
            }
            $oKeyword = ORM::for_table('url_alias')->where('query','author_id='.$this->request->get['author_id'])->find_one();
            if($oKeyword){
                $this->data['seo_keyword']=$oKeyword->keyword;
            }
        }

        $this->data['author_id']=$value['author_id'];
        $this->data['first_name']=$value['first_name'];
        $this->data['last_name']=$value['last_name'];
        $this->data['description']=$value['description'];
        $this->data['fb_link']=$value['fb_link'];
        $this->data['twitter_link']=$value['twitter_link'];
        $this->data['sort_order']=$value['sort_order'];
        $this->data['status']=$value['status'];
        $aSeo = ORM::for_table('url_alias')->where('group','blog_authors')->where('query','author_id=' + $value['author_id'])->find_one(null,true);
        $this->data['seo_keyword'] = ($aSeo ? $aSeo['keyword'] : '');


        if (isset($this->request->post['image'])) {
            $this->data['image'] = $this->request->post['image'];
        } elseif (isset($value['image'])) {
            $this->data['image'] =$value['image'];
        } else {
            $this->data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($value['image']) && file_exists(DIR_IMAGE . $value['image'])) {
            $this->data['preview'] = $this->model_tool_image->resize($value['image'], 100, 100);
        } else {
            $this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }
        if (isset($value) && $value['thumb'] && file_exists(DIR_IMAGE . $value['thumb'])) {
            $this->data['preview_thumb'] = $this->model_tool_image->resize($value['thumb'], 100, 100);
        } else {
            $this->data['preview_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
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



        $this->template = 'catalog/blog_author_form.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }
    private function validateForm() {
        $this->load->language('catalog/blog_author');

        if (!$this->user->hasPermission('modify', 'catalog/blog_author')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((strlen(utf8_decode($this->request->post['first_name'])) < 1) || (strlen(utf8_decode($this->request->post['first_name'])) > 255)) {
            $this->error['first_name']= $this->language->get('error_first_name');
        }
        if ((strlen(utf8_decode($this->request->post['last_name'])) < 1) || (strlen(utf8_decode($this->request->post['last_name'])) > 255)) {
            $this->error['last_name']= $this->language->get('error_last_name');
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
}
?>