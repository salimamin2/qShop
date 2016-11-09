<?php

class ModelUserUserGroup extends ARModel {

    public static $_table = 'user_group';
     //fields
        protected  $_fields=array(
                'id',
                'name',
                'permission',
            );

        //validation rules
         protected  $_rules=array(
             'insert|update'=>array(
                 'rules'=>array(
                     'name'=>array('required'=>true,'minlength'=>5,'maxlength'=>64),
                     //'permission'=>'safe',
                ),
             ),);

    public function getPermission() {
        return unserialize($this->get('permission'));
    }

    public function setPermission($field, $permission) {
        $this->orm->permission = serialize($permission);
    }

    public function addPermission($type, $page) {
        $data = $this->getPermission();
        $data[$type][] = $page;
    }


    public function getUserGroups($data = array()) {
        /* @var $user_group ORM */
        $user_group = ORM::for_table('user_group')->create();
        $sort = "name";
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $user_group->order_by_desc($sort);
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start']) {
                $user_group->offset($data['start']);
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $user_group->limit($data['limit']);
        }

        return $user_group->find_many(true);
    }

    public function getTotalUserGroups() {
        return ORM::for_table('user_group')->count();
    }

    public function getUsers() {
        return $this->has_many('user/user');
    }
}

?>