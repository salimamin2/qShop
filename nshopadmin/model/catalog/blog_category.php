<?php
        class ModelCatalogBlogCategory extends ARModel {
            public static $_table = 'blog_category';
            public static $_id_column = 'id';
            //fields
            protected $_fields = array(
                'id',
                'language_id',
                'name',
                'seo_keyword',
                'meta_title',
                'meta_link',
                'meta_keywords',
                'meta_description',
                'status',
                'sort_order',
                'date_added',
                'date_modified',
                'is_deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at'
            );

            public function getBlogCategory($category_id) {

                $category = Make::a('catalog/blog_category')
                    ->select('*')
                    ->find_one($category_id);

                return $category;
            }
            public function editCategory($data){


                try {
                    $this->orm->beginTransaction();

//d($data,1);
                    $oModel = Make::a('catalog/blog_category')->find_one($data['blog_categor_id']);
                    foreach ($data['category_description'] as $language_id => $value) {


                        $oModel->name = $value['name'];
                        $oModel->language_id = $language_id;
                        $oModel->seo_keyword = $data['seo_keyword'];
                        $oModel->meta_title = $value['meta_title'];
                        $oModel->meta_link = $value['meta_link'];
                        $oModel->meta_keywords = $value['meta_keywords'];
                        $oModel->meta_description = $value['meta_description'];
                        $oModel->status = $value['status'];
                        $oModel->sort_order = $value['sort_order'];
                        $oModel->date_modified = date('Y-m-d H:i:s');
                        $oModel->save();
                        if ($oModel->hasErrors()) {
                            throw new Exception(CHtml::errorSummary($oModel));
                        }

                    }

                    ORM::raw_execute("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blog_category_id=" . (int) $data['blog_categor_id'] . "'");

                    if ($data['seo_keyword']) {
                        $oOrm = ORM::for_table('url_alias')->create();
                        $oOrm->group = "blog_category";
                        $oOrm->query = "blog_category_id=" . $data['blog_categor_id'];
                        $oOrm->keyword = $data['seo_keyword'];
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
            public function addBlogCategory($data){

                try {

                    $this->orm->beginTransaction();
                   // d($data,1);
                    foreach ($data['category_description'] as $language_id => $value) {
                        $oModel = Make::a('catalog/blog_category')->create();

                        $oModel->name = $value['name'];
                        $oModel->language_id = $language_id;
                        $oModel->seo_keyword = $data['seo_keyword'];
                        $oModel->meta_title = $value['meta_title'];
                        $oModel->meta_link = $value['meta_link'];
                        $oModel->meta_keywords = $value['meta_keywords'];
                        $oModel->meta_description = $value['meta_description'];
                        $oModel->status = $value['status'];
                        $oModel->sort_order = $value['sort_order'];
                        $oModel->date_modified = date('Y-m-d H:i:s');
                        $oModel->save();

                        if ($oModel->hasErrors()) {
                            throw new Exception(CHtml::errorSummary($oModel));
                        }
                    }


                    if ($data['seo_keyword']) {
                        $oOrm = ORM::for_table('url_alias')->create();
                        $oOrm->group = "blog_category";
                        $oOrm->query = "blog_category_id=" . $oModel->id;
                        $oOrm->keyword = $data['seo_keyword'];
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
            public function deleteBlogCategory($category_ids) {
                try {
                    d($category_ids);

                    foreach ($category_ids['selected'] as $category_id) {

                        $oModel = Make::a('catalog/blog_category')->find_one($category_id);
                        if ($oModel) {
                            //d($category_id,1);
                            ORM::raw_execute("DELETE FROM blog_category WHERE id = ?", array($category_id));
                            ORM::raw_execute("DELETE FROM url_alias WHERE query = 'blog_category_id=" . (int) $category_id . "'");


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
