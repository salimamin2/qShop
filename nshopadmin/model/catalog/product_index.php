<?php

class ModelCatalogProductIndex extends ARModel {

    public static $_table = 'product_index';
    //public static $_id_column = 'product_id';
    public $reg;
    //fields
    protected $_fields = array(
        'id',
        'product_id',
        'price',
        'status',
        'tags',
        'category_id',
        'category_name',
        'manufacturer_id',
        'manufacturer_name',
        'option_id_1',
        'option_name_1',
        'option_value_id_1',
        'option_value_1',
        'option_id_2',
        'option_name_2',
        'option_value_id_2',
        'option_value_2',
        'option_id_3',
        'option_name_3',
        'option_value_id_3',
        'option_value_3',
        'option_id_4',
        'option_name_4',
        'option_value_id_4',
        'option_value_4',
        'option_id_5',
        'option_name_5',
        'option_value_id_5',
        'option_value_5',
        'option_id_6',
        'option_name_6',
        'option_value_id_6',
        'option_value_6',
        'option_id_7',
        'option_name_7',
        'option_value_id_7',
        'option_value_7',
        'option_id_8',
        'option_name_8',
        'option_value_id_8',
        'option_value_8',
        'option_id_9',
        'option_name_9',
        'option_value_id_9',
        'option_value_9',
        'option_id_10',
        'option_name_10',
        'option_value_id_10',
        'option_value_10',
        'option_id_11',
        'option_name_11',
        'option_value_id_11',
        'option_value_11',
        'option_id_12',
        'option_name_12',
        'option_value_id_12',
        'option_value_12',
        'option_id_13',
        'option_name_13',
        'option_value_id_13',
        'option_value_13',
        'option_id_14',
        'option_name_14',
        'option_value_id_14',
        'option_value_14',
        'option_id_15',
        'option_name_15',
        'option_value_id_15',
        'option_value_15',
        'option_id_16',
        'option_name_16',
        'option_value_id_16',
        'option_value_16',
        'option_id_17',
        'option_name_17',
        'option_value_id_17',
        'option_value_17',
        'option_id_18',
        'option_name_18',
        'option_value_id_18',
        'option_value_18',
        'option_id_19',
        'option_name_19',
        'option_value_id_19',
        'option_value_19',
        'option_id_20',
        'option_name_20',
        'option_value_id_20',
        'option_value_20'
    );

    public function init() {
        //setting up default values
        $this->reg = Registry::getInstance();
        parent::init();
    }

    public function insertProducts() {
        $aResults = array();
        $sSql = "SELECT 
                p.product_id,
                IFNULL(s.price, p.price) AS price,
                p.status,
                (SELECT GROUP_CONCAT(tag  SEPARATOR ',') tag
                    FROM product_tags pt 
                    WHERE pt.product_id = p.product_id AND pt.language_id = '" . (int) QS::app()->config->get('config_language_id') . "') AS tags,
                ptc.category_id,
                cd.name AS category_name,
                p.`manufacturer_id`,
                m.`name` AS manufacturer_name
                FROM product p
                   LEFT JOIN product_to_category ptc ON ptc.`product_id` = p.`product_id`
                   LEFT JOIN category_description cd ON cd.`category_id`= ptc.`category_id` AND cd.language_id = '" . (int) QS::app()->config->get('config_language_id') . "'
                   LEFT JOIN product_special s ON p.`product_id` = s.`product_id` AND (s.`date_start` < '" . date('Y-m-d') . "' OR s.`date_start` = '0000-00-00') AND (s.`date_end` > '" . date('Y-m-d') . "' OR s.`date_end` = '0000-00-00' ) AND s.customer_group_id = '" . (int) QS::app()->config->get('config_customer_group_id') . "'
                   LEFT JOIN manufacturer m ON m.`manufacturer_id` = p.`manufacturer_id`";
        $oQuery = QS::app()->db->query($sSql);
        if ($oQuery->num_rows) {
            foreach ($oQuery->rows as $aRow) {
                $sSql = "SELECT 
                        pod.product_option_id as option_id,
                        pod.name as option_name,
                        povd.product_option_value_id as option_value_id,
                        povd.name as option_value
                        FROM product_option_description pod
                        LEFT JOIN product_option_value pov ON pod.product_option_id = pov.`product_option_id`
                        LEFT JOIN product_option_value_description povd ON pov.`product_option_value_id` = povd.`product_option_value_id`
                        WHERE pod.product_id = " . (int) $aRow['product_id'];
                $oQ = QS::app()->db->query($sSql);
                for ($j = 0; $j < 20; $j++) {
                    $aVal = array();
                    if ($oQ->num_rows && isset($oQ->rows[$j])) {
                        $aVal = $oQ->rows[$j];
                    }
                    $aRow['option_id_' . ($j + 1)] = isset($aVal['option_id']) ? $aVal['option_id'] : '';
                    $aRow['option_name_' . ($j + 1)] = isset($aVal['option_name']) ? $aVal['option_name'] : '';
                    $aRow['option_value_id_' . ($j + 1)] = isset($aVal['option_value_id']) ? $aVal['option_value_id'] : '';
                    $aRow['option_value_' . ($j + 1)] = isset($aVal['option_value']) ? $aVal['option_value'] : '';
                }

                $aResults[] = '("' . join('","', $aRow) . '")';
            }
        }
        ORM::raw_execute('Truncate table ' . self::$_table);
        $aCols[] = "product_id";
        $aCols[] = "price";
        $aCols[] = "status";
        $aCols[] = "tags";
        $aCols[] = "category_id";
        $aCols[] = "category_name";
        $aCols[] = "manufacturer_id";
        $aCols[] = "manufacturer_name";
        for ($i = 1; $i <= 20; $i++) {
            $aCols[] = "option_id_" . $i;
            $aCols[] = "option_name_" . $i;
            $aCols[] = "option_value_id_" . $i;
            $aCols[] = "option_value_" . $i;
        }
        $sSql = "INSERT INTO " . self::$_table . " (";
        $sSql .= join(',', $aCols);
        $sSql .= ") VALUES ";
        $sSql .= join(',', $aResults);
        //d($sSql);
        ORM::raw_execute($sSql);
    }

}

?>