<?php
class ModelCatalogBlogAuthor extends ARModel
{
    public static $_table = 'blog_author';
    public static $_id_column = 'author_id';
    //fields
    protected $_fields = array(
        'author_id',
        'first_name',
        'last_name',
        'description',
        'image',
        'fb_link',
        'twitter_link',
        'status',
        'sort_order',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );
    public function addPostAuthor($data){
        $result = array();
        try {
            $this->orm->beginTransaction();
            $oModel = Make::a('catalog/blog_author')->create();
            $oModel->first_name = $data['first_name'];
            $oModel->last_name = $data['last_name'];
            $oModel->description = $data['description'];
            $oModel->image=QS::app()->db->escape($data['image']);
            $oModel->thumb=QS::app()->db->escape($data['thumb']);
            $oModel->fb_link = $data['fb_link'];
            $oModel->twitter_link = $data['twitter_link'];
            $oModel->status = $data['status'];
            $oModel->sort_order = $data['sort_order'];
            $oModel->created_at= date('Y-m-d H:i:s');
            $oModel->created_by=$data['user_id'];
            $oModel->save();
            if($data['keyword'] != '') {
                $oOrm = ORM::for_table('url_alias')->create();
                $oOrm->group = "blog_authors";
                $oOrm->query = "author_id=" . $oModel->author_id;
                $oOrm->keyword = $data['keyword'];
                $oOrm->save();
            }
            $result['success'] = "Success";
            $this->orm->commit();
        } catch (Exception $e) {
            $this->orm->rollback();
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function editBlogAuthors($data){

        try {
            $this->orm->beginTransaction();
            $oModel = Make::a('catalog/blog_author')->find_one($data['author_id']);
            $oModel->first_name = $data['first_name'];
            $oModel->last_name = $data['last_name'];
            $oModel->description = $data['description'];
            $oModel->image=QS::app()->db->escape($data['image']);
            $oModel->thumb=QS::app()->db->escape($data['thumb']);
            $oModel->fb_link = $data['fb_link'];
            $oModel->twitter_link = $data['twitter_link'];
            $oModel->status = $data['status'];
            $oModel->sort_order = $data['sort_order'];
            $oModel->image=QS::app()->db->escape($data['image']);
            $oModel->updated_at= date('Y-m-d H:i:s');
            $oModel->updated_by=$data['user_id'];
            $oModel->save();

            ORM::raw_execute("DELETE FROM url_alias WHERE query = 'author_id=" . $data['author_id'] . "'");
            if ($data['keyword']) {
                $oOrm = ORM::for_table('url_alias')->create();
                $oOrm->group = "blog_authors";
                $oOrm->query = "author_id=" . $data['author_id'];
                $oOrm->keyword = $data['keyword'];
                $oOrm->save();
            }
            $result['success'] = "Success";
            $this->orm->commit();
        } catch (Exception $e) {
            $this->orm->rollback();
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function deleteAuthors($author_ids) {
        $aResult = array();
        try {
            foreach ($author_ids['selected'] as $a_id) {
                $oModel = Make::a('catalog/blog_author')->find_one($a_id);
                if ($oModel) {
                    ORM::raw_execute("DELETE FROM blog_author WHERE author_id = ?", array($a_id));
                    ORM::raw_execute("DELETE FROM url_alias WHERE query = 'author_id=" . $a_id . "'");
                    $oModel->delete();
                    if ($oModel->hasErrors()) {
                        throw new Exception("error in delete");
                    }
                }
            }
            $aResult['success'] = true;
        } catch (Exception $e) {
            $aResult['error'] = $e->getMessage();
        }
        return $aResult;
    }
}
?>