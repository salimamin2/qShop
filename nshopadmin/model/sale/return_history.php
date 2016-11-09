<?php

class ModelSaleReturnHistory extends ARModel
{

    public static $_table = 'return_history';
    public static $_id_column = 'return_history_id';
    public $keyword;
    protected $_fields = array(
        'return_history_id',
        'return_id',
        'return_status_id',
        'notify',
        'comment',
        'date_added'
    );
}
?>