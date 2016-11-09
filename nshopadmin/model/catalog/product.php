<?php

class ModelCatalogProduct extends ARModel {

    public static $_table = 'product';
    public static $_id_column = 'product_id';
    //fields
    protected $_fields = array(
        'product_id',
        'model',
        'sku',
        'location',
        'quantity',
        'stock_status_id',
        'image',
        'thumb',
        'manufacturer_id',
        'shipping',
        'price',
        'price_standard',
        'price_custom',
        'tax_class_id',
        'date_available',
        'weight',
        'weight_class_id',
        'length',
        'width',
        'height',
        'length_class_id',
        'status',
        'date_added',
        'date_modified',
        'viewed',
        'sort_order',
        'subtract',
        'minimum',
        'cost',
        'is_deleted',
        'product_type_id',
        'related_category',
        'material',
        'points',
        'delivery_days',
        'delivery_days_standard',
        'delivery_days_custom',
        'cutoff_time'
    );

    public function addProduct($data) {
        try {
            //d($data,1);
            $oModel = Make::a('catalog/product')->create();
            $sRelatedCategory = serialize($data['product_related_category']);
            unset($data['product_related_category']);
            $oModel->setFields($data);
            if ($data['date_available'] && strtotime($data['date_available']) > 0) {
                $oModel->date_available = date('Y-m-d', strtotime($data['date_available']));
            } else {
                $oModel->date_available = '0000-00-00';
            }
            $oModel->date_added = date('Y-m-d H:i:s');
            $oModel->related_category = $sRelatedCategory;
            $oModel->size_chart = $data['size_chart'];
            $oModel->save();
            if ($oModel->hasErrors()) {
                throw new Exception('Error saving product.');
            }
            $product_id = $oModel->product_id;

            foreach ($data['product_description'] as $language_id => $value) {
                $oDesc = Make::a('catalog/product_description')->create();
                $value['language_id'] = (int) $language_id;

                $oDesc->setFields($value);
                $oDesc->product_id = (int) $product_id;
                $oDesc->save();
                if ($oDesc->hasErrors()) {
                    throw new Exception('Error saving product.');
                }
            }

            if (isset($data['product_option'])) {
                $this->addProductOption($product_id,$data['product_option']);
            }

            if (isset($data['product_discount'])) {
                try{
                    foreach ($data['product_discount'] as $value) {
                        $start_date = '';
                        $end_date = '';
                        if (trim($value['date_start']) != "") {
                            $start_date = date('Y-m-d', strtotime($value['date_start']));
                        }
                        if (trim($value['date_end']) != "") {
                            $end_date = date('Y-m-d', strtotime($value['date_end']));
                        }

                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int) $product_id . "',
    		        customer_group_id = '" . (int) $value['customer_group_id'] . "', quantity = '" . (int) $value['quantity'] . "',
                    priority = '" . (int) $value['priority'] . "', price = '" . (float) $value['price'] . "',
                    date_start = '" . $start_date . "', date_end = '" . $end_date . "'");
                    }
                } catch (PDOException $e) {
                    throw new Exception($e->getMessage());
                }
            }
            if (isset($data['product_special'])) {
                try{
                    foreach ($data['product_special'] as $value) {
                        $start_date = '';
                        $end_date = '';
                        if (trim($value['date_start']) != "") {
                            $start_date = date('Y-m-d', strtotime($value['date_start']));
                        }
                        if (trim($value['date_end']) != "") {
                            $end_date = date('Y-m-d', strtotime($value['date_end']));
                        }

                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int) $product_id . "',
    		    customer_group_id = '" . (int) $value['customer_group_id'] . "', priority = '" . (int) $value['priority'] . "',
    		    price = '" . (float) $value['price'] . "', date_start = '" . $start_date . "',
    		    date_end = '" . $end_date . "'");
                    }
                } catch (PDOException $e) {
                    throw new Exception($e->getMessage());
                }
            }

            if (isset($data['product_image'])) {
                foreach ($data['product_image'] as $image) {
                    try{
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . QS::app()->db->escape($image['image']) . "', sort_order = " . (int) $image['sort_order']);
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }

            if (isset($data['product_download'])) {
                foreach ($data['product_download'] as $download_id) {
                    try{
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int) $product_id . "', download_id = '" . (int) $download_id . "'");
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }

            $oManufacturer = ORM::for_table('manufacturer')->select('name','manufacturer_name')->where('manufacturer_id',$data['manufacturer_id'])->find_one();
            $data['manufacturer_name'] = $oManufacturer->manufacturer_name;
            $data['tags'] = $data['product_tags'][QS::app()->config->get('config_language_id')];
            if (isset($data['product_category'])) {
                foreach ($data['product_category'] as $category_id) {
                    try{
                        $this->modifyProductIndex($product_id,$category_id,$data);
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            } else {
                try{
                    $this->modifyProductIndex($product_id,0,$data);
                } catch (PDOException $e) {
                    throw new Exception($e->getMessage());
                }
            }

            if (isset($data['product_related'])) {
                foreach ($data['product_related'] as $related_id) {
                    try{
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "',type = '" . PRODUCT_RELATED . "'");
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $related_id . "', related_id = '" . (int) $product_id . "',type = '" . PRODUCT_RELATED . "'");
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }

            if (isset($data['product_up_sell'])) {
                foreach ($data['product_up_sell'] as $upsell_id) {
                    try{
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $upsell_id . "',type = '" . PRODUCT_UP_SELL . "'");
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }


            if (isset($data['product_cross_sell'])) {
                foreach ($data['product_cross_sell'] as $cross_sell_id) {
                    try{
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $cross_sell_id . "', type = '" . PRODUCT_CROSS_SELL . "'");
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }

            if (isset($data['product_reward'])) {
                foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
                    try{
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $product_reward['points'] . "'");
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }

            if ($data['keyword']) {
                try{
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "url_alias SET `group` = 'product', `query` = 'product_id=" . (int) $product_id . "', `keyword` = '" . QS::app()->db->escape($data['keyword']) . "'");
                } catch (PDOException $e) {
                    throw new Exception($e->getMessage());
                }
            }
            if ($data['product_detail']) {
                //$this->addProductDetails($data);
                $this->updateQuantity($product_id, $quantity);
            }

            if (isset($data['product_group'])) {
                foreach ($data['product_group'] as $group_product_id) {
                    try{
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_to_group SET product_id = '" . (int) $product_id . "', group_product_id = '" . (int) $group_product_id . "'");
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            }

            foreach ($data['product_tags'] as $language_id => $value) {
                $tags = explode(',',$value);
                foreach ($tags as $tag) {
                    if($tag != '') {
                        try{
                            ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_tags SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $language_id . "', tag = '" . QS::app()->db->escape(trim($tag)) . "'");
                        } catch (PDOException $e) {
                            throw new Exception($e->getMessage());
                        }
                    }
                }
            }  

            QS::app()->cache->delete('product');
        } catch (Exception $e) {
            d($e ,1 );
            return array('error' => $e->getMessage());
        }
        return $product_id;
    }

    public function addProductOption($product_id, $data){
        try{
       // d(array($product_id, $data));
            foreach($data as $iGroup => $aOption){
                $product_option_id = ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', sort_order = '" . (int) $aOption['sort_order'] . "', parent_id = '0',  product_option_type_id = '" . (int) $aOption['product_option_type_id'] . "'");
                $sSql = "INSERT INTO " . DB_PREFIX . "product_option_description SET product_option_id = '" . (int) $product_option_id . "', product_id = '" . (int) $product_id . "', language_id = '" . (int) $aOption['language_id'] . "', name = '" . $aOption['name'] . "'";
                ORM::raw_execute($sSql);
               // d($sSql);
                if($aOption['option_value']){
                    foreach($aOption['option_value'] as $aValue){
                        $sSql = "INSERT INTO " . DB_PREFIX . "product_option_value SET product_id = '" . (int) $product_id . "', product_option_id = '" . (int) $product_option_id . "', quantity = '" . $aValue['quantity'] . "', subtract = '" . $aValue['subtract'] . "',  prefix = '" . $aValue['prefix'] . "',  thumb = '" . $aValue['thumb'] . "',  image = '" . $aValue['image'] . "',  price = '" . $aValue['price'] . "',  sort_order = '" . $aValue['sort_order'] . "',  min_size = '" . $aValue['min_size'] . "',  max_size = '" . $aValue['max_size'] . "'";
                        $product_option_value_id = ORM::raw_execute($sSql);
                       // d($sSql);
                        $sSql = "INSERT INTO " . DB_PREFIX . "product_option_value_description SET product_option_value_id = '" . (int) $product_option_value_id . "', help = '" .  $aValue['help'] . "', language_id = '" . (int) $aValue['language_id'] . "', name = '" . $aValue['name'] . "'";
                        $bRow = ORM::raw_execute($sSql);
                       // d($sSql);
                    }
                }
                if(isset($aOption['child_option']) && !empty($aOption['child_option'])){
                    foreach ($aOption['child_option'] as $aChild) {
                        $product_child_option_id = ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', sort_order = '" . (int) $aChild['sort_order'] . "', parent_id = '".(int) $product_option_id."',  product_option_type_id = '" . (int) $aChild['product_option_type_id'] . "'");
                        $sSql = "INSERT INTO " . DB_PREFIX . "product_option_description SET product_option_id = '" . (int) $product_child_option_id . "', product_id = '" . (int) $product_id . "', language_id = '" . (int) $aChild['language_id'] . "', name = '" . $aChild['name'] . "'";
                       // d($sSql);
                        ORM::raw_execute($sSql);
                        
                        if($aOption['option_value']){
                            foreach($aChild['option_value'] as $aValue){
                                $sSql = "INSERT INTO " . DB_PREFIX . "product_option_value SET product_id = '" . (int) $product_id . "', product_option_id = '" . (int) $product_child_option_id . "', quantity = '" . $aValue['quantity'] . "', subtract = '" . $aValue['subtract'] . "',  prefix = '" . $aValue['prefix'] . "',  thumb = '" . $aValue['thumb'] . "',  image = '" . $aValue['image'] . "',  price = '" . $aValue['price'] . "',  sort_order = '" . $aValue['sort_order'] . "',  min_size = '" . $aValue['min_size'] . "',  max_size = '" . $aValue['max_size'] . "'";
                                $product_option_value_id = ORM::raw_execute($sSql);
                               // d($sSql);
                                $sSql = "INSERT INTO " . DB_PREFIX . "product_option_value_description SET product_option_value_id = '" . (int) $product_option_value_id . "', help = '" .  $aValue['help'] . "', language_id = '" . (int) $aValue['language_id'] . "', name = '" . $aValue['name'] . "'";
                               // d($sSql);
                                ORM::raw_execute($sSql);
                            }
                        }
                    }
                }
            }
            //d('done',1);
        } catch(PDOException $ex){
            d($ex,1);
        } catch(Exception $ex){
            d($ex,1);
        }
    }

    public function editProduct($product_id, $data) {
        try {
            if($data['quantity'] > '0'){
                $aProducts = Make::a('catalog/product')->raw_query("SELECT product_id,email FROM product_alert_emails WHERE status = '0' GROUP BY product_id,email")->find_many(true);

                if($aProducts){
                    foreach($aProducts as $products){
                        QS::app()->load->language('catalog/product');
                        $product_info = Make::a('catalog/product_description')->where('product_id', $products['product_id'])->find_one();

                        $message  = QS::app()->language->get('text_dear') . "\n\n";
                        $message  .= QS::app()->language->get('text_products').' '. $product_info->name. "\n\n";
                        $message .= QS::app()->language->get('text_welcome_text')."\n\n";
                        $message .= QS::app()->config->get('config_url').'product/product?product_id='.$products['product_id'];

                        $mail = new Mail();

                        $mail->protocol = QS::app()->config->get('config_mail_protocol');
                        $mail->parameter = QS::app()->config->get('config_mail_parameter');
                        $mail->hostname = QS::app()->config->get('config_smtp_host');
                        $mail->username = QS::app()->config->get('config_smtp_username');
                        $mail->password = QS::app()->config->get('config_smtp_password');
                        $mail->port = QS::app()->config->get('config_smtp_port');
                        $mail->timeout = QS::app()->config->get('config_smtp_timeout');
                        $mail->setTo($products['email']);
                        $mail->setFrom(QS::app()->config->get('config_email'));
                        $mail->setSender(QS::app()->config->get('config_owner'));
                        $mail->setSubject(html_entity_decode($product_info->name.': '.QS::app()->language->get('text_mail_subject'), ENT_QUOTES, 'UTF-8'));
                        $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                        $mail->send();
                        $update_query="UPDATE " . DB_PREFIX . "product_alert_emails SET status='1', date_updated=NOW()  WHERE email= '" . $products['email'] . "' AND product_id = '" . (int) $products['product_id'] . "'";

                        ORM::raw_execute($update_query);

                    }
                }
            }
            $aProducts_url = Make::a('catalog/product')->raw_query("SELECT keyword FROM url_alias WHERE keyword='". $data['keyword'] ."'")->find_many(true);
            if($aProducts_url){
                $rand_num=rand(0,9);
                $data['keyword']=$data['keyword'].''.$product_id.$rand_num;
            }
            $oModel = Make::a('catalog/product')->find_one($product_id);
            $sRelatedCategory = serialize($data['product_related_category']);
            unset($data['product_related_category']);
            $oModel->setFields($data);
            if ($data['date_available'] && strtotime($data['date_available']) > 0) {
                $oModel->date_available = date('Y-m-d', strtotime($data['date_available']));
            } else {
                $oModel->date_available = null;
            }
            $oModel->date_modified = date('Y-m-d H:i:s');
            $oModel->size_chart = $data['size_chart'];
            $oModel->related_category = $sRelatedCategory;
            $oModel->save();
            if ($oModel->hasErrors()) {
                throw new Exception("Error Editing Product");
            }

            $product_id = $oModel->product_id;

            foreach ($data['product_description'] as $language_id => $value) {
                $oDesc = Make::a('catalog/product_description')->where('product_id', $product_id)->where('language_id', $language_id)->find_one();
                if (!$oDesc) {
                    $oDesc = Make::a('catalog/product_description')->create();
                    $oDesc->product_id = (int) $product_id;
                    $oDesc->langauge_id = (int) $language_id;
                }
                $value['language_id'] = $language_id;
                $oDesc->setFields($value);
                $oDesc->save();
                if ($oDesc->hasErrors()) {
                    throw new Exception('Error saving product.');
                }
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "'");

            if (isset($data['product_discount'])) {
                foreach ($data['product_discount'] as $value) {
                    $start_date = '';
                    $end_date = '';
                    if (trim($value['date_start']) != "") {
                        $start_date = date('Y-m-d', strtotime($value['date_start']));
                    }
                    if (trim($value['date_end']) != "") {
                        $end_date = date('Y-m-d', strtotime($value['date_end']));
                    }
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int) $product_id . "',
                    customer_group_id = '" . (int) $value['customer_group_id'] . "', quantity = '" . (int) $value['quantity'] . "',
                    priority = '" . (int) $value['priority'] . "', price = '" . (float) $value['price'] . "',
                    date_start = '" . $start_date . "', date_end = '" . $end_date . "'");
                }
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "'");

            if (isset($data['product_special'])) {
                foreach ($data['product_special'] as $value) {
                    $start_date = '';
                    $end_date = '';
                    if (trim($value['date_start']) != "") {
                        $start_date = date('Y-m-d', strtotime($value['date_start']));
                    }
                    if (trim($value['date_end']) != "") {
                        $end_date = date('Y-m-d', strtotime($value['date_end']));
                    }

                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int) $product_id . "',
		    customer_group_id = '" . (int) $value['customer_group_id'] . "', priority = '" . (int) $value['priority'] . "',
		    price = '" . (float) $value['price'] . "', date_start = '" . $start_date . "',
		    date_end = '" . $end_date . "'");
                }
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $product_id . "'");

            if (isset($data['product_reward'])) {
                foreach ($data['product_reward'] as $customer_group_id => $value) {
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $value['points'] . "'");
                }
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");

            if (isset($data['product_image'])) {
                foreach ($data['product_image'] as $image) {
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . QS::app()->db->escape($image['image']) . "', sort_order = " . (int) $image['sort_order']);
                }
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int) $product_id . "'");

            if (isset($data['product_download'])) {
                foreach ($data['product_download'] as $download_id) {
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int) $product_id . "', download_id = '" . (int) $download_id . "'");
                }
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");

            $oManufacturer = ORM::for_table('manufacturer')->select('name','manufacturer_name')->where('manufacturer_id',$data['manufacturer_id'])->find_one();
            $data['manufacturer_name'] = $oManufacturer->manufacturer_name;
            $data['tags'] = $data['product_tags'][QS::app()->config->get('config_language_id')];
            if (isset($data['product_category'])) {
                foreach ($data['product_category'] as $category_id) {
                    try{
                        $this->modifyProductIndex($product_id,$category_id,$data);
                        ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
                    } catch (PDOException $e) {
                        throw new Exception($e->getMessage());
                    }
                }
            } else {
                try{
                    $this->modifyProductIndex($product_id,0,$data);
                } catch (PDOException $e) {
                    throw new Exception($e->getMessage());
                }
            }


            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "' OR related_id = '" . (int) $product_id . "'");

            if (isset($data['product_related'])) {
                foreach ($data['product_related'] as $related_id) {
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "',type = '" . PRODUCT_RELATED . "'");
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $related_id . "', related_id = '" . (int) $product_id . "',type = '" . PRODUCT_RELATED . "'");
                }
            }

            if (isset($data['product_cross_sell'])) {
                foreach ($data['product_cross_sell'] as $related_id) {
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "',type = '" . PRODUCT_CROSS_SELL . "'");
                }
            }

            if (isset($data['product_up_sell'])) {
                foreach ($data['product_up_sell'] as $related_id) {
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "',type = '" . PRODUCT_UP_SELL . "'");
                }
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'product_id=" . (int) $product_id . "'");

            if ($data['keyword']) {
                ORM::raw_execute("INSERT INTO " . DB_PREFIX . "url_alias SET `group` = 'product', `query` = 'product_id=" . (int) $product_id . "', `keyword` = '" . QS::app()->db->escape($data['keyword']) . "'");
            }

            ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_tags WHERE product_id = '" . (int) $product_id . "'");

            foreach ($data['product_tags'] as $language_id => $value) {
                $tags = explode(',', $value);
                foreach ($tags as $tag) {
                    ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_tags SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $language_id . "', tag = '" . QS::app()->db->escape(trim($tag)) . "'");
                }
            }

            QS::app()->cache->delete('product');
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
        return $product_id;
    }

    public function updateProductDetails($product_id, $data) {
        
    }

    public function deleteProductDetails($option_id) {
        
    }

    public function modifyProductIndex($product_id,$category_id,$aData){
        try{
            $oModel = Make::a('catalog/product_index')->where('product_id',$product_id)->where('category_id',$category_id)->find_one();
            if(!$oModel){
                $oModel = Make::a('catalog/product_index')->create();
            } else {
                $aOptions = $this->getProductOptionsValues($product_id)->find_many(1);
                for ($j = 0; $j < 20; $j++) {
                    $aVal = array();
                    if ($aOptions && isset($aOptions[$j])) {
                        $aVal = $aOptions[$j];
                    }
                    $aData['option_id_' . ($j + 1)] = isset($aVal['option_id']) ? $aVal['option_id'] : '';
                    $aData['option_name_' . ($j + 1)] = isset($aVal['option_name']) ? $aVal['option_name'] : '';
                    $aData['option_value_id_' . ($j + 1)] = isset($aVal['option_value_id']) ? $aVal['option_value_id'] : '';
                    $aData['option_value_' . ($j + 1)] = isset($aVal['option_value']) ? $aVal['option_value'] : '';
                }
            }
            $aCategory = Make::a('catalog/category_description')->select('name','category_name')->where('category_id',$category_id)->find_one();
            $aData['category_name'] = $aCategory->category_name;
            $aData['product_id'] = $product_id;
            $aData['category_id'] = $category_id;
            $aData['category_id'] = $category_id;
            $oModel->setFields($aData);
            $oModel->save();
            return true;
        } catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function copyProduct($prod_id) {
//	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
//        $oProd = Make::a('catalog/product')
//                    ->table_alias('p')
//                    ->left_outer_join('product_description',array('p.product_id','=','pd.product_id'),'pd')
//                    ->where('p.product_id',$product_id)
//                    ->where('pd.language_id',QS::app()->config->get('config_language_id'))
//                    ->find_many(true);
        $oProd = $this->getProduct($prod_id);

        if (!empty($oProd)) {
            $data = array();
            $oModel = Make::a('catalog/product')->find_one($prod_id);
            $data = $oProd;
            $data['name'] = $data['name'].' -copy';
            $oDesc = $oModel->getDescriptions();
            foreach ($oDesc as $desc) {
                unset($desc['id']);
                $desc['name'] = $desc['name'].' -copy';
                $data['product_description'][$desc['language_id']] = $desc;
            }
            $data = array_merge($data, array('product_option' => $oModel->getProductOptions()));

            $data['keyword'] = '';

            $data['product_image'] = array();

            $results = $oModel->getProductImages();

            foreach ($results as $result) {
                $data['product_image'][] = $result['image'];
            }

            $data = array_merge($data, array('product_discount' => $oModel->getProductDiscounts()));
            $data = array_merge($data, array('product_special' => $oModel->getProductSpecials()));
            $data = array_merge($data, array('product_download' => $oModel->getProductDownloads()));
            $data = array_merge($data, array('product_category' => $oModel->getProductCat()));
            $data = array_merge($data, array('product_reward' => $oModel->getProductRewards()));
            $data = array_merge($data, array('product_related' => $oModel->getProductRelated(PRODUCT_RELATED)));
            $data = array_merge($data, array('product_cross_sell' => $oModel->getProductRelated(PRODUCT_CROSS_SELL)));
            $data = array_merge($data, array('product_up_sell' => $oModel->getProductRelated(PRODUCT_UP_SELL)));
            $data = array_merge($data, array('product_tags' => $oModel->getProductTags()));
            $data = array_merge($data, array('product_option' => $oModel->getProductGroupOptions()));
            unset($data['product_id']);
            unset($data['id']);
            $this->addProduct($data);
        }
    }

    public function deleteProduct($product_id) {
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_option_description WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_option_value_description WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int) $product_id . "'");
        ORM::raw_execute("DELETE FROM " . DB_PREFIX . "product_tags WHERE product_id='" . (int) $product_id . "'");
        QS::app()->cache->delete('product');
    }

    public function deleteProductImage($prod_image_id) {
        ORM::raw_execute('DELETE FROM product_image WHERE product_image_id = ?', array($prod_image_id));
    }

    public function getProducts($data = array()) {
        return Make::a('catalog/product')
                        ->table_alias('p')
                        ->join('product_description', 'p.product_id=pd.product_id AND pd.language_id=' . (int) QS::app()->config->get('config_language_id'), 'pd')
                        ->where('status', 1);
    }

    public function addFeatured($data) {
        ORM::raw_execute("DELETE FROM product_featured");

        if (isset($data['featured_product'])) {
            foreach ($data['featured_product'] as $product_id) {
                ORM::raw_execute("INSERT INTO " . DB_PREFIX . "product_featured SET product_id = '" . (int) $product_id . "'");
            }
        }
    }

    public function getFeaturedProducts() {
        $aProducts = Make::a('catalog/product')->raw_query("SELECT product_id FROM product_featured")->find_many(true);

        $featured = array();

        foreach ($aProducts as $product) {
            $featured[] = $product['product_id'];
        }
        return $featured;
    }

    public function getProductsByKeyword($keyword) {
        return array();
    }

    public function getProductsByCategoryId($category_id) {
        $oProducts = Make::a('catalog/product')
                ->table_alias('p')
                ->select_expr('p.product_id,d.name,p.model')
                ->inner_join('product_to_category', array('c.product_id', '=', 'p.product_id'), 'c')
                ->inner_join('product_description', array('p.product_id', '=', 'd.product_id'), 'd')
                ->where('c.category_id', $category_id)
                ->find_many(true);
        return $oProducts;
    }

    public function getProductDescriptions() {
        $oModel = $this->has_many('catalog/product_description', 'product_id');
        return $oModel;
    }

    public function getDescriptions() {
        return $this->has_many('product_description', 'product_id')->find_many(true);
    }

    public function getProductRewards() {
        return $this->has_many('product_reward', 'product_id')->find_many(true);
    }

    public function getProductOptionsValues($product_id){
        $aOptions = ORM::for_table('product_option_description')->table_alias('pod')
        ->select('pod.product_option_id', 'option_id')
        ->select('pod.name', 'option_name')
        ->select('povd.product_option_value_id', 'option_value_id')
        ->select('povd.name', 'option_value')
        ->left_outer_join('product_option_value','pod.product_option_id = pov.product_option_id','pov')
        ->left_outer_join('product_option_value_description','pov.product_option_value_id = povd.product_option_value_id','povd')
        ->where('pod.product_id',(int) $product_id);
        return $aOptions;
    }

    public function getProductOptions() {
        return $this->has_many('product_option', 'product_id')->find_many(true);
    }
    public function getProductGroupOptions() {
        $aGroupOptions = ORM::for_table('product_option')
        ->table_alias('po')
        ->left_outer_join('product_option_description','po.product_option_id=pod.product_option_id','pod')
        ->where('pod.language_id',QS::app()->config->get('config_language_id'))
        ->where('po.parent_id',0)
        ->where('po.product_id',$this->product_id)
        ->find_many();
        $aOptions=array();
        //$aGroup = array();
        foreach ($aGroupOptions as $i=>$oGroup) {
            $aGroup = $oGroup->as_array();
            $aValues = ORM::for_table('product_option_value')
            ->table_alias('v')
            ->left_outer_join('product_option_value_description','v.product_option_value_id=vd.product_option_value_id','vd')
            ->where('v.product_option_id',$oGroup->product_option_id)
            ->where('v.product_id',$oGroup->product_id)
            ->where('vd.language_id',QS::app()->config->get('config_language_id'))
            ->find_many(true);
            /*if($aGroup['product_option_type_id'] == 4 && (empty($aGroup) || !in_array($oOption->product_option_id,$aGroup))){
                //$aOptions[0][] = $aValue;
                $aGroup[] = $oOption->product_option_id;
            } */
            $aGroup['option_value']=$aValues;
            $aOptions[$oGroup->product_option_id] = $aGroup; 
            $aChildOptions = ORM::for_table('product_option')
            ->table_alias('o')
            ->left_outer_join('product_option_description','o.product_option_id=od.product_option_id','od')
            ->where('o.parent_id',$oGroup->product_option_id)
            ->where('o.product_id',$oGroup->product_id)
            ->where('od.language_id',QS::app()->config->get('config_language_id'))
            ->find_many();
            foreach($aChildOptions as $oOption){
                $aOption = $oOption->as_array();
                $aChildValues = ORM::for_table('product_option_value')
                ->table_alias('v')
                ->left_outer_join('product_option_value_description','v.product_option_value_id=vd.product_option_value_id','vd')
                ->where('v.product_option_id',$oOption->product_option_id)
                ->where('v.product_id',$oOption->product_id)
                ->where('vd.language_id',QS::app()->config->get('config_language_id'))
                ->find_many(true);
                $aOption['option_value']=$aChildValues;
                $aOptions[$oOption->parent_id]['child_option'][] = $aOption;    
            }
                       
        }
        return $aOptions;
    }
    public function getProductOptionsDescription() {
        return $this->has_many('product_option_description', 'product_id')->find_many(true);
    }
    public function getProductOptionValues() {
        return $this->has_many('product_option_value', 'product_id')->find_many(true);
    }
    public function getProductOptionValuesDescription() {
        return $this->has_many('product_option_value_description', 'product_id')->find_many(true);
    }

    public function getProductImages() {
        return $this->has_many('product_image', 'product_id')->find_many(true);
    }

    public function getProductDiscounts() {
        return $this->has_many('product_discount', 'product_id')->find_many(true);
    }

    public function getProductSpecials() {
        return $this->has_many('product_special', 'product_id')->find_many(true);
    }

    public function getProductDownloads() {
        return $this->has_many('product_to_download', 'product_id')->find_many(true);
    }

    public function getProductStores($product_id) {
        return array();
    }

    public function getProductCategories() {
        return $this->has_many('product_to_category', 'product_id')->find_many(true);
    }

    public function getProductCat() {
        return $this->has_many('product_to_category', 'product_id','pc')->select('pc.category_id')->find_many(true);
    }

    public function getProductRelated($type_id) {
        return $this->has_many('product_related', 'pr.product_id')->table_alias('pr')
                        ->select_expr('pr.product_id,pr.related_id,pr.type,pd.name,p.model')
                        ->inner_join('product', array('p.product_id', '=', 'pr.related_id'), 'p')
                        ->inner_join('product_description', array('p.product_id', '=', 'pd.product_id'), 'pd')
                        ->where('pr.type', $type_id)
                        ->find_many(true);
    }

    public function getProductTags() {
        $oTags = $this->has_many('product_tags', 'product_id')->find_many(true);
        $result = array();
        foreach ($oTags as $tag) {
            $lang = $tag['language_id'];
            if (!isset($result[$lang])) {
                $result[$lang] = $tag['tag'];
            } else {
                $result[$lang] .= "," . $tag['tag'];
            }
        }
        return $result;
    }

    public function getTotalProducts($data = array()) {
        $count = Make::a('catalog/product')
                ->table_alias('p')
                ->inner_join('product_description', array('d.product_id', '=', 'p.product_id'), 'd')
                ->where('d.language_id', QS::app()->config->get('config_language_id'))
                ->count();
        return $count;
    }

    public function getTotalProductsByStockStatusId($stock_status_id) {
        $count = ORM::for_table('product')
                ->where('stock_status_id', $stock_status_id)
                ->count();
        return $count;
    }

    public function getTotalProductsByImageId($image_id) {
        return array();
    }

    public function getTotalProductsByTaxClassId($tax_class_id) {
        return array();
    }

    public function getTotalProductsByWeightClassId($weight_class_id) {
        return array();
    }

    public function getTotalProductsByLengthClassId($length_class_id) {
        return array();
    }

    public function getTotalProductsByOptionId($option_id) {
        return array();
    }

    public function getTotalProductsByDownloadId($download_id) {
        return array();
    }

    public function getProductDetails($product_id) {
        return array();
    }

    /**
     * 
     * @param int $iProductType
     * @return array
     */
    public function getDetailTypes($iProductType) {
        
    }

    public function updateQuantity($id, $quantity) {
        if (!$id || !is_int($quantity)) {
            return false;
        }
        $oModel = Make::a('catalog/product')->find_one($id);
        $oModel->quantity = (int) $quantity;
        $oModel->save();
        return $oModel->id;
    }

    public function getKeyword($product) {
        if (!is_array($product))
            return '';
        if (!empty($product['keyword'])) {
            return $product['keyword'];
        } else {
            $oDesc = $this->getProductDescriptions($product['product_id'])->where('language_id', QS::app()->config->get('config_language_id'))->find_one();
            return $this->friendlyUrl($oDesc->name);
        }
    }

    /* takes the input, scrubs bad characters */

    function friendlyURL($input, $replace = '-', $remove_words = true, $words_array = array('a', 'and', 'the', 'an', 'it', 'is', 'with', 'can', 'of', 'why', 'not')) {
        //make it lowercase, remove punctuation, remove multiple/leading/ending spaces
        $return = trim(ereg_replace(' +', ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input))));

        //remove words, if not helpful to seo
        //i like my defaults list in remove_words(), so I wont pass that array
        if ($remove_words) {
            $return = $this->removeBadSEOWords($return, $replace, $words_array);
        }

        //convert the spaces to whatever the user wants
        //usually a dash or underscore..
        //...then return the value.
        $keyword = str_replace(' ', $replace, $return);
        return $this->getUniqueKeyword($keyword);
    }

    /* takes an input, scrubs unnecessary words */

    function removeBadSEOWords($input, $replace, $words_array = array(), $unique_words = true) {
        //separate all words based on spaces
        $input_array = explode(' ', $input);

        //create the return array
        $return = array();

        //loops through words, remove bad words, keep good ones
        foreach ($input_array as $word) {
            //if it's a word we should add...
            if (!in_array($word, $words_array) && ($unique_words ? !in_array($word, $return) : true)) {
                $return[] = $word;
            }
        }

        //return good words separated by dashes
        return implode($replace, $return);
    }

    function getUniqueKeyword($keyword) {
        $iTotal = Make::a('catalog/product')->raw_query("SELECT COUNT(*) as total FROM url_alias WHERE keyword LIKE '{$keyword}_%'")->count();
        return $iTotal ? $keyword . '_' . $iTotal : $keyword;
    }

    function getProduct($product_id) {
        $oProd = Make::a('catalog/product')
                ->table_alias('p')
                ->inner_join('product_description', array('d.product_id', '=', 'p.product_id'), 'd')
                ->where('p.product_id', $product_id)
                ->where('d.language_id', QS::app()->config->get('config_language_id'))
                ->find_one();
        if ($oProd !== FALSE) {
            return $oProd->toArray();
        } else {
            return array();
        }
    }
    public function getProductUrlAlias($data){
        $sql1=Make::a('catalog/product')->raw_query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int) $data['product_id']."'")->find_one();
        if($sql1){
            $keyword=$sql1->toArray();

            return $keyword['keyword'];

        }

    }

}

?>