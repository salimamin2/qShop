<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of product_export_batch
 *
 * @author qasim
 */

class ModelSaleProductExportBatch extends Model {

    public static $tableName = 'order_product_export_batch';
    public function save($data)
    {
        /*@var db Crystal_Config_Reader */
        $db = $this->cdb;
        if(in_array('product_export_batch_id',$data)){
            //run update routine
            $db->update(self::$tableName,$data)
                        ->where('product_export_batch_id='.$data['product_export_batch_id'])
                        ->execute();
        }else{
            //run insert routine
            $db->insert(self::$tableName,$data)->execute();
            $id = $db->last_insert_id();
        }
        
    }
    public function get($id=0){
        $db = $this->cdb;
        if(!$id)
            return $db->get(self::$tableName)->fetch_all();
        else
            return $db->get(self::$tableName)
                    ->where('product_export_batch_id='.$id)->fetch_row();
    }

}
?>
