<?php
class ControllerBlogPost extends Controller {

    public function index() {
        $this->language->load('blog/blog');
        $this->load->model('blog/blog');
        $this->load->model('tool/image');

        $this->document->breadcrumbs = array( );
        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('common/home',array(),true),
            'text' => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );
        $this->document->breadcrumbs[] = array(
            'href' => makeUrl('blog/blog',array(),true),
            'text' => $this->language->get('heading_title'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_posted'] = $this->language->get('text_posted');


        if($this->request->get['blog_post_id']) {

            $blogPosts = $this->model_blog_blog->getPostsDescriptions($this->request->get['blog_post_id']);

            $thumb = '';
            if ($blogPosts['thumb'] && file_exists(DIR_IMAGE . $blogPosts['thumb'])) {
                $thumb = $this->model_tool_image->resize($blogPosts['thumb'],1400, 468);
            }
            $image = '';
            if ($blogPosts['image'] && file_exists(DIR_IMAGE . $blogPosts['image'])) {
                $image = HTTPS_IMAGE . $blogPosts['image'];
                $this->document->meta['image'] = HTTP_IMAGE . $blogPosts['image'];
            }
            $this->document->breadcrumbs[] = array(
                'href' => '',
                'text' => $blogPosts['title'],
                'separator' => FALSE
            );
            $this->data['heading_title'] = $blogPosts['title'];

            $ts = $blogPosts['date_craeted'];
            $date=date("j F Y",strtotime($ts));

            $this->data['posts'] = array(
                'post_id' => $blogPosts['post_id'],
                'category_id' => $blogPosts['category_id'],
                'title' => $blogPosts['title'],
                'author_link' => makeUrl('blog/blog',array('author_id='.$blogPosts['author_id']),true),
                'href' => makeUrl('blog/post',array('blog_post_id='.$blogPosts['post_id']),true),
                'author' => $blogPosts['first_name'].' ' .$blogPosts['last_name'],
                'description'=>html_entity_decode($blogPosts['description']),
                'meta_title'=>$blogPosts['meta_title'],
                'meta_link'=>$blogPosts['meta_link'],
                'meta_keywords'=>$blogPosts['meta_keywords'],
                'meta_description'=>$blogPosts['meta_description'],
                'thumb' => $thumb,
                'image' => $image,
                'date_craeted'=>$date
            );

            $this->document->title = (isset($blogPosts['meta_title']) && $blogPosts['meta_title'] != '') ? $blogPosts['meta_title'] : $blogPosts['title'];
            $this->document->description = $blogPosts['meta_description'];
            $this->document->keywords = $blogPosts['meta_keywords'];

            //$this->document->title = $blogPosts['title'] . ' :: ' . $this->language->get('heading_title');

            $this->data['blog_page'] = makeUrl('blog/blog', array(), true);

        }


        $this->data['breadcrumbs'] = $this->document->breadcrumbs;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/post_description.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/blog/post_description.tpl';
        } else {
            $this->template = 'default/template/blog/post_description.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right'
        );
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));

    }
}

?>