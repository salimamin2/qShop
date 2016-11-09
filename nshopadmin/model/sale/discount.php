<?php

class ModelSaleDiscount extends ARModel {

    public static $_table = 'discount';
    public static $_id_column = 'discount_id';
    //fields
    protected $_fields = array(
        'discount_id',
        'type',
        'discount',
        'customer_group_id',
        'shipping',
        'total',
        'date_start',
        'date_end',
        'status',
        'sort_order',
        'date_added',
        'is_deleted'
    );

    protected $_rules = array(
    'insert|update' => array(
        'rules' => array(
            'discount' => array('required' => true),
        )
    ));

    public function addDiscount($data) {
        try {
            $this->orm->beginTransaction();
            $data['date_start'] = date('Y-m-d',strtotime($data['date_start']));
            $data['date_end'] = date('Y-m-d',strtotime($data['date_end']));
            $descriptions = $data['discount_description'];
            unset($data['discount_description']);
            $this->setFields($data);
            $this->date_added = date('Y-m-d H:i:s');
            $this->save();
            if ($this->hasErrors()) {
                throw new Exception(CHtml::errorSummary($this));
            }
            $discount_id = $this->id();

            foreach ($descriptions as $language_id => $value) {
                $oOrm = Make::a('sale/discount_description')->create();
                $oOrm->setFields($value);
                $oOrm->discount_id = $discount_id;
                $oOrm->language_id = $language_id;
                $oOrm->save();
                if ($oOrm->hasErrors()) {
                    throw new Exception(CHtml::errorSummary($oOrm));
                }
            }

            if (isset($data['discount_product'])) {
                foreach ($data['discount_product'] as $product_id) {
                    $oOrm = Make::a('sale/discount_product')->create();
                    $oOrm->discount_id = $discount_id;
                    $oOrm->product_id = $product_id;
                    $oOrm->save();
                    if ($oOrm->hasErrors()) {
                        throw new Exception(CHtml::errorSummary($oOrm));
                    }
                }
            }
            $this->orm->commit();
            $res['success'] = true;
        }
        catch(Exception $e) {
            $res['error'] = $e->getMessage();
            $this->orm->rollback();
        }
        return $res;
    }

    public function editDiscount($discount_id, $data) {
        try {
            $this->orm->beginTransaction();
            $data['date_start'] = date('Y-m-d',strtotime($data['date_start']));
            $data['date_end'] = date('Y-m-d',strtotime($data['date_end']));
            $descriptions = $data['discount_description'];
            unset($data['discount_description']);
            $oModel = Make::a('sale/discount')->find_one($discount_id);
            if(!$oModel)
                throw new Exception('Unknown error occurred');
            $oModel->setFields($data);
            $oModel->date_added = date('Y-m-d H:i:s');
            $oModel->save();
            if ($oModel->hasErrors()) {
                throw new Exception(CHtml::errorSummary($this));
            }
            ORM::raw_execute("DELETE FROM discount_description WHERE discount_id = ?",array($discount_id));
            foreach ($descriptions as $language_id => $value) {
                $oOrm = Make::a('sale/discount_description')->create();
                $oOrm->setFields($value);
                $oOrm->discount_id = $discount_id;
                $oOrm->language_id = $language_id;
                $oOrm->save();
                if ($oOrm->hasErrors()) {
                    throw new Exception(CHtml::errorSummary($oOrm));
                }
            }
            ORM::raw_execute("DELETE FROM discount_product WHERE discount_id = ?",array($discount_id));
            if (isset($data['discount_product'])) {
                foreach ($data['discount_product'] as $product_id) {
                    $oOrm = Make::a('sale/discount_product')->create();
                    $oOrm->discount_id = $discount_id;
                    $oOrm->product_id = $product_id;
                    $oOrm->save();
                    if ($oOrm->hasErrors()) {
                        throw new Exception(CHtml::errorSummary($oOrm));
                    }
                }
            }
            $this->orm->commit();
            $res['success'] = true;
        }
        catch(Exception $e) {
            $res['error'] = $e->getMessage();
            $this->orm->rollback();
        }
        return $res;
    }

    public function deleteDiscount($discount_id) {
        ORM::raw_execute("DELETE FROM discount WHERE discount_id = ?",array($discount_id));
        ORM::raw_execute("DELETE FROM discount_description WHERE discount_id = ?",array($discount_id));
        ORM::raw_execute("DELETE FROM discount_product WHERE discount_id = ?",array($discount_id));
    }

    public function getDiscount($discount_id) {
        return ORM::for_table('discount')->where('discount_id',$discount_id)->find_one(null,true);
    }

    public function getDiscounts() {
        return ORM::for_table('discount')
                    ->select_expr('d.discount_id,d.customer_group_id ,cg.name as cgname,d.sort_order,dd.name,
                        d.discount,d.date_start,d.date_end,d.status')
                    ->table_alias('d')
                    ->left_outer_join('discount_description',array('d.discount_id','=','dd.discount_id'),'dd')
                    ->left_outer_join('customer_group',array('d.customer_group_id','=','cg.customer_group_id'),'cg')
                    ->where('dd.language_id',QS::app()->config->get('config_language_id'))
                    ->find_many(true);
    }   

    public function getDiscountDescriptions($discount_id) {
        $discount_description_data = array();
        $aDesc = ORM::for_table('discount_description')->where('discount_id',$discount_id)->find_many(true);
        foreach ($aDesc as $result) {
            $discount_description_data[$result['language_id']] = array(
                'name' => $result['name'],
                'description' => $result['description']
            );
        }
        return $discount_description_data;
    }

    public function getDiscountProducts($discount_id) {
        $discount_product_data = array();
        $aProds = ORM::for_table('discount_product')->where('discount_id',$discount_id)->find_many(true);
        foreach ($aProds as $result) {
            $discount_product_data[] = $result['product_id'];
        }
        return $discount_product_data;
    }

    public function getTotalDiscount() {
        return ORM::for_table('discount')->where('is_deleted',0)->count();
    }

}

?>