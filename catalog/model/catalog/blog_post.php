<?php
class ModelCatalogBlogPost extends ARModel {
    public static $_table = 'blog_post';
    public static $_id_column = 'blog_post_id';
    public $reg;

    //fields
    protected $_fields = array(
    	'blog_post_id',
    	'image',
    	'thumb',
    	'sort_order',
    	'status',
    	'is_deleted',
    	'date_added',
    	'date_modified'
    );

    public function init() {
		//setting up default values
		$this->reg = Registry::getInstance();
		parent::init();
    }

    public function getBlog($blog_id) {
        return ORM::for_table('blog_post')
                    ->table_alias('b')
                    ->inner_join('blog_post_description',array('b.blog_post_id','=','bd.blog_post_id'),'bd')
                    ->where('bd.language_id',$this->reg->config->get('config_language_id'))
                    ->where('b.blog_post_id',$blog_id)
                    ->where('b.status',1)
                    ->find_one(null,true);
    }

    public function getBlogByCategory($category) {
        return ORM::for_table('blog_post')
                    ->table_alias('b')
                    ->inner_join('blog_post_description',array('b.blog_post_id','=','bd.blog_post_id'),'bd')
                    ->inner_join('blog_category',array('bc.id','=','b.blog_category_id'),'bc')
                    ->where('bd.language_id',$this->reg->config->get('config_language_id'))
                    ->where('bc.id',$category)
                    ->where('b.status',1)
                    ->where('bc.status',1)
                    ->order_by_desc('b.created_at')
                    ->find_one(null,true);
    }

    public function getLatestBlogs($limit = 4,$category_id = false) {
        $aOrm =  ORM::for_table('blog_post')
                    ->table_alias('b')
                    ->inner_join('blog_post_description',array('b.blog_post_id','=','bd.blog_post_id'),'bd')
                    ->where('bd.language_id',$this->reg->config->get('config_language_id'))
                    ->where('b.status',1);
        if($category_id)
            $aOrm = $aOrm->where('b.blog_category_id',$category_id);
        $aOrm = $aOrm->order_by_desc('date_added')
                     ->limit($limit)
                     ->find_many(true);
        return $aOrm;
    }
}