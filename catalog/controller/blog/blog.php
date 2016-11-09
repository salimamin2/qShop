<?php
class ControllerBlogBlog extends Controller {

    public function index() {
        $this->language->load('blog/blog');
        $this->load->model('blog/blog');
        $this->load->model('tool/image');

        $author = array();
        $heading_title =  $this->language->get('heading_title');
        if(isset($this->request->get['author_id']) && $this->request->get['author_id']){
            $author = $this->model_blog_blog->getAuthorId($this->request->get['author_id']);
            $author['name'] = $author['first_name'].' '.$author['last_name'];
            $heading_title =  $author['name'];

            if ($author['im'] && file_exists(DIR_IMAGE . $author['im'])) {
                $image = $author['im'];
            } else {
                $image = 'no_image.jpg';
            }
            $author['im'] = $this->model_tool_image->resize($image,$this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            $this->data['aAuthor'] = $author;
        }

        $this->document->title = html_entity_decode($heading_title);

        $this->document->breadcrumbs = array( );
        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home',array(),true),
            'text' => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );
        $this->document->breadcrumbs[] = array(
            'href' => ($author?makeUrl('blog/blog',array(),true):'javascript:void(0)'),
            'text' => $this->language->get('heading_title'),
            'separator' => ($author?$this->language->get('text_separator'):false)
        );
        if($author){
            $this->document->breadcrumbs[] = array(
                'href' => 'javascript:void(0)',
                'text' => $author['name'],
                'separator' => false
            );
        }

        $this->document->loadKnow = false;

        $this->document->title = $heading_title;
        $this->data['heading_title'] = $heading_title;
        $this->data['text_category_editorail'] = $this->language->get('text_category_editorail');
        $this->data['text_category_bytime'] = $this->language->get('text_category_bytime');
        $this->data['text_readmore'] = $this->language->get('text_readmore');
        $this->data['text_noposts'] = $this->language->get('text_noposts');
        $this->data['text_more_authors'] = $this->language->get('text_more_authors');

        $this->data['text_recent'] = $this->language->get('text_recent');
        $this->data['text_week'] = $this->language->get('text_week');
        $this->data['text_month'] = $this->language->get('text_month');

        $this->data['text_author'] = $this->language->get('text_author');


        $blogCategories=$this->model_blog_blog->getCategories();
        foreach($blogCategories as $bCategory){
            $this->data['categories'][]=array(
                'id'=>$bCategory['id'],
                'name'=>$bCategory['name'],
                'href'=> makeUrl('blog/blog', array('blog_category_id=' . $bCategory['id']), true)
            );
        }

        $this->data['featured_authors'] = array();
        if(!isset($this->request->get['author_id'])) {
            $authors = $this->model_blog_blog->getAuthors();

            foreach($authors as $aAuthors){

                $image = '';
                if ($aAuthors['image'] && file_exists(DIR_IMAGE . $aAuthors['image'])) {
                    $image = $aAuthors['image'];
                } else {
                    $image = 'no_image.jpg';
                }

                $this->data['featured_authors'][]=array(
                    'id'=>$aAuthors['author_id'],
                    'image' => $this->model_tool_image->resize($image,$this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                    'username'=>$aAuthors['first_name'].' '.$aAuthors['last_name'],
                    'href'=> makeUrl('blog/blog', array('author_id=' . $aAuthors['author_id']), true)
                );
            }
        }
        $this->data['more_authors']= makeUrl('blog/blog_authors', array(),true);
        $this->data['posted_recently']= makeUrl('blog/blog', array('posted=DAY'), true);
        $this->data['posted_week']= makeUrl('blog/blog', array('posted=WEEK'), true);
        $this->data['posted_month']= makeUrl('blog/blog', array('posted=MONTH'), true);

        if(isset($this->request->get['posted'])){
            $blogPosts = $this->model_blog_blog->getPosts('created_at','DESC');
        }

        else if($this->request->get['blog_category_id']){
            $blogPosts = $this->model_blog_blog->getPostsById($this->request->get['blog_category_id']);
        }
        else if($this->request->get['author_id']){
            $blogPosts = $this->model_blog_blog->getPostsByAuthor($this->request->get['author_id']);
        }
        else{
            $blogPosts = $this->model_blog_blog->getPosts();
        }


        foreach($blogPosts as $aPost){

            $thumb = 'no_image.jpg';
            if ($aPost['thumb'] && file_exists(DIR_IMAGE . $aPost['thumb'])) {
                $thumb = $aPost['thumb'];
            }
             $image = 'no_image.jpg';
            if ($aPost['image'] && file_exists(DIR_IMAGE . $aPost['image'])) {
                $image = $aPost['image'];
            }


            $this->data['posts'][]=array(
                'post_id'=>$aPost['blog_post_id'],
                'category_id'=>$aPost['blog_category_id'],
                'name'=>$aPost['title'],
                'thumb' => $this->model_tool_image->resize($thumb,$this->config->get('config_image_related_width'), $this->config->get('config_image_related_height')),
                'image' => $this->model_tool_image->resize($image,$this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
                'meta_link'=>$aPost['meta_link'],
                'href'=> makeUrl('blog/post', array('blog_category_id=' . $aPost['blog_category_id'],'blog_post_id='.$aPost['blog_post_id']), true)
            );
        }

        $this->data['breadcrumbs'] = $this->document->breadcrumbs;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/blog.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/blog/blog.tpl';
        } else {
            $this->template = 'default/template/blog/blog.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right'
        );
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));

    }
}

?>