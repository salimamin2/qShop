<?php

class ModelCatalogBlogPost extends ARModel {

    public static $_table = 'blog_post';
    public static $_id_column = 'blog_post_id';
    //fields
    protected $_fields = array(
        'blog_post_id',
        'blog_category_id',
        'author_id',
        'image',
        'thumb',
        'status',
        'sort_order',
        'date_added',
        'date_modified',
        'is_deleted',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'featured'
    );
    public function getostCategory($category_id) {

        $category = Make::a('catalog/blog_post')
            ->select('*')
            ->find_one($category_id);

        return $category;
    }
    public function addPostCategory($data){

        try {

            $this->orm->beginTransaction();
            $oModel = Make::a('catalog/blog_post')->create();
            $aDescriptions = $data['blog_description'];
            $oModel->setFields($data['blog']);
            $oModel->created_at = date('Y-m-d H:i:s');
            $oModel->created_by = $data['user_id'];
            $oModel->image = QS::app()->db->escape($data['image']);
            $oModel->thumb = QS::app()->db->escape($data['thumb']);
            $oModel->save();
            if($oModel->hasErrors()) {
                throw new Exception(CHtml::errorSummary($oModel));
            }

            if ($data['keyword']) {
                $oOrm = ORM::for_table('url_alias')->create();
                $oOrm->group = "blog_post";
                $oOrm->query = "blog_post_id=" . $oModel->blog_post_id;
                $oOrm->keyword = $data['keyword'];
                $oOrm->save();
            }

            foreach ($data['description'] as $language_id => $value) {
                $PostCate = Make::a('catalog/blog_post_description')->create();
                $PostCate->setFields($value);
                $PostCate->language_id = $language_id;
                $PostCate->blog_post_id = $oModel->blog_post_id;
                $PostCate->created_at= date('Y-m-d H:i:s');
                $PostCate->created_by= $data['user_id'];
                $PostCate->save();
                if($PostCate->hasErrors()) {
                    throw new Exception(CHtml::errorSummary($PostCate));
                }
            }
            $result['success'] = "Success";
            $this->orm->commit();
        } catch (Exception $e) {
            $this->orm->rollback();
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function editPostCategory($data){
        try {
            $this->orm->beginTransaction();
            $oModel = Make::a('catalog/blog_post')->find_one($data['post_id']);
            if(!$oModel)
                throw new Exception('Unknown error occurred');
            $oModel->setFields($data['blog']);
            $oModel->updated_at = date('Y-m-d H:i:s');
            $oModel->updated_by = $data['user_id'];
            $oModel->image= QS::app()->db->escape($data['image']);
            $oModel->thumb= QS::app()->db->escape($data['thumb']);
            $oModel->save();

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blog_post_id=" . (int) $data['post_id'] . "'");

            if ($data['keyword']) {
                $oOrm = ORM::for_table('url_alias')->create();
                $oOrm->group = "blog_post";
                $oOrm->query = "blog_post_id=" . $data['post_id'];
                $oOrm->keyword = $data['keyword'];
                $oOrm->save();
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "blog_post_description WHERE blog_post_id =" . (int) $data['post_id'] . "");
            foreach ($data['description'] as $language_id => $value) {
                $PostCate = Make::a('catalog/blog_post_description')->create();
                $PostCate->setFields($value);
                $PostCate->language_id = $language_id;
                $PostCate->blog_post_id = $oModel->blog_post_id;
                $PostCate->created_at= date('Y-m-d H:i:s');
                $PostCate->created_by= $data['user_id'];
                $PostCate->save();
                if($PostCate->hasErrors()) {
                    throw new Exception(CHtml::errorSummary($PostCate));
                }
            }
            $result['success'] = "Success";
            $this->orm->commit();
        } catch (Exception $e) {
            $this->orm->rollback();
            $result['error'] = $e->getMessage();
        }
        return $result;
    }
    
    public function deletePostCategory($category_ids) {
        try {


            foreach ($category_ids['selected'] as $category_id) {

                $oModel = Make::a('catalog/blog_post')->find_one($category_id);
                if ($oModel) {

                    ORM::raw_execute("DELETE FROM blog_post WHERE blog_post_id = ?", array($category_id));
                    ORM::raw_execute("DELETE FROM url_alias WHERE query = 'blog_post_id=" . (int) $category_id . "'");
                    ORM::raw_execute("DELETE FROM blog_post_description WHERE blog_post_id = ?", array($category_id));


                    $oModel->delete();
                    if ($oModel->hasErrors()) {
                        throw new Exception("error in delete");
                    }
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}

?>