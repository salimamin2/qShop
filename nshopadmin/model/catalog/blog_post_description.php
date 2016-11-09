<?php
class ModelCatalogBlogPostDescription extends ARModel
{

    public static $_table = 'blog_post_description';
    public static $_id_column = 'blog_post_id';
    //fields
    protected $_fields = array(
        'blog_post_id',
        'language_id',
        'title',
        'description',
        'meta_title',
        'meta_link',
        'meta_keywords',
        'meta_description',
        'is_deleted',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );
}