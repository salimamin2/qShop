<?php
class ControllerBlogBlogAuthors extends Controller {

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

        $this->document->title = $this->language->get('heading_title_author');
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_category_editorail'] = $this->language->get('text_category_editorail');
        $this->data['text_category_bytime'] = $this->language->get('text_category_bytime');
        $this->data['text_readmore'] = $this->language->get('text_readmore');
        $this->data['text_noposts'] = $this->language->get('text_noposts');

        $this->data['text_recent'] = $this->language->get('text_recent');
        $this->data['text_week'] = $this->language->get('text_week');
        $this->data['text_month'] = $this->language->get('text_month');

        $this->data['text_author'] = $this->language->get('text_author');



        $authors=$this->model_blog_blog->getAllAuthors();

        foreach($authors as $bAuthors){

            $image = '';
            if ($bAuthors['image'] && file_exists(DIR_IMAGE . $bAuthors['image'])) {
                $image = HTTPS_IMAGE . $bAuthors['image'];
            }

            $authorsTotalPosts=$this->model_blog_blog->getAuthorsPosts($bAuthors['author_id']);

            $this->data['authors'][]=array(
                'author_id'=>$bAuthors['author_id'],
                'name'=>$bAuthors['first_name'].' '.$bAuthors['last_name'],
                'description'=>$bAuthors['description'],
                'fb_link'=>$bAuthors['fb_link'],
                'twitter_link'=>$bAuthors['twitter_link'],
                'total_posts'=>$authorsTotalPosts,
                'image' => $image,
                'href' => makeUrl('blog/blog',array('author_id=' .$bAuthors['author_id']),true)
            );

        }
        $this->data['blog_page'] = makeUrl('blog/blog',array(),true);
        $this->data['breadcrumbs'] = $this->document->breadcrumbs;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/blog/blog_authors_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/blog/blog_authors_list.tpl';
        } else {
            $this->template = 'default/template/blog/blog_authors_list.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right'
        );
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));

    }
}

?>