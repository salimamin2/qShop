<?php
class ModelCatalogProduct extends ARModel {
    public static $_table = 'product';
    public static $_id_column = 'product_id';
    public $reg;
    //fields
    protected $_fields = array(
        'product_id',
        'model',
        'sku',
        'location',
        'quantity',
        'quantity_bundle',
        'stock_status_id',
        'image',
        'manufacturer_id',
        'shipping',
        'price',
        'tax_class_id',
        'date_available',
        'weight',
        'weight_class_id',
        'length',
        'width',
        'height',
        'measurement_class_id',
        'length_class_id',
        'status',
        'viewed',
        'minimum',
        'subtract',
        'sort_order',
        'product_type_id',
        'date_added',
    );
    public function init() {
        //setting up default values
        $this->reg = Registry::getInstance();
        parent::init();
    }
    public function getProduct($product_id, $apply_status = true) {
        $customer_id = 0;
        if ($this->reg->customer->isLogged()) {
            $customer_id = $this->reg->customer->getId();
        }
        $oModel = Make::a('catalog/product')->table_alias('p')
                ->distinct()
                ->select('*')
                ->select('p.product_id')
                ->select('pd.name', 'name')
                ->select('p.image')
                ->select('m.name', 'manufacturer')
                ->select('m.image', 'manufacturer_image')
                ->select('pd.description', 'description')
                ->select('pd.meta_title')
                ->select('ss.name', 'stock')
                ->left_outer_join('manufacturer', 'p.manufacturer_id = m.manufacturer_id', 'm')
                ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd')
                ->left_outer_join('stock_status', '(p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'ss');
                if ($customer_id != 0) {
                    $oModel = $oModel->left_outer_join('wishlist','w.product_id = p.product_id AND w.customer_id = ' . $customer_id,'w')
                                       ->select('w.wishlist_id');
                }
                $oModel = $oModel->where('p.product_id', (int) $product_id)
                //->where_gt('p.quantity', 0)
                ->where_lte('p.date_available', 'NOW()');
        if ($apply_status)
            $oModel = $oModel->where('p.status', 1);
        $oMod = $oModel->find_one();
        return $oMod;
    }
    public function getProducts() {
        $oModel = Make::a('catalog/product')->table_alias('p')
                ->distinct()
                ->select('*')
                ->select('pd.name', 'name')
                ->select('p.image')
                ->select('pd.meta_title')
                ->select('m.name', 'manufacturer')
                ->select('m.image', 'manufacturer_image')
                ->select('ss.name', 'stock')
                ->select('wcd.unit', 'weight_class')
                ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd')
                ->left_outer_join('manufacturer', 'p.manufacturer_id = m.manufacturer_id', 'm')
                ->left_outer_join('stock_status', '(p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'ss')
                ->left_outer_join('weight_class_description', '(p.weight_class_id = wcd.weight_class_id AND wcd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'wcd')
                //->where_gt('p.quantity', 0)
                ->where_lte('p.date_available', 'NOW()')
                ->where('p.status', 1);
        return $oModel->find_many(true);
    }
    public function getProductsByCategoryId($category_id, $special = false) {
        if ($this->reg->customer->isLogged()) {
            $customer_id = $this->reg->customer->getId();
            $customer_group_id = $this->reg->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->reg->config->get('config_customer_group_id');
        }
        $results = Make::a('catalog/product_index')->table_alias('pi')
                ->distinct()
                ->select('p.*')
                ->select('pd.*')
                ->select('pi.price', 'filter_price')
                ->left_outer_join('product', 'p.product_id = pi.product_id', 'p')
                ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd');
                if (isset($customer_id) && $customer_id != 0) {
                    $results = $results->left_outer_join('wishlist','w.product_id = p.product_id AND w.customer_id = ' . $customer_id,'w')
                                       ->select('w.wishlist_id');
                }
                $results = $results->where('pi.category_id', (int) $category_id)
                //->where_gt('p.quantity', 0)
                ->where_lte('p.date_available', date('Y-m-d'))
                ->where('p.status', 1);
        return $results;
    }
    public function getProductsByCountryId($country_id) {
        $zone_id = $this->reg->session->data['zone_id'];
        return Make::a('catalog/product')->table_alias('p')
                        ->distinct()
                        ->select('p.*')
                        ->select('pd.*')
                        ->select('pd.name', 'name')
                        ->select('p.image')
                        ->select('pd.meta_title')
//			->select('m.name', 'manufacturer')
//			->select('m.image', 'manufacturer_image')
                        ->select('ss.name', 'stock')
                        ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd')
                        ->left_outer_join('product_to_store', '(p.product_id = p2s.product_id AND p2s.store_id = ' . (int) $this->reg->config->get('config_store_id') . ')', 'p2s')
                        ->left_outer_join('manufacturer', 'p.manufacturer_id = m.manufacturer_id', 'm')
                        ->left_outer_join('stock_status', '(p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'ss')
                        ->where_gt('p.quantity', 0)
                        ->where_lte('p.date_available', 'NOW()')
                        ->where('p.status', 1);
    }
    public function getProductsByZoneId($sort = 'p.price', $order = 'ASC', $start = 0, $limit = 20) {
        $oModel = Make::a('catalog/product')->table_alias('p')
                ->distinct()
                ->select('p.*')
                ->select('pd.*')
                ->select('pd.name', 'name')
                ->select('p.image')
                ->select('pd.meta_title')
//		->select('m.name', 'manufacturer')
//		->select('m.image', 'manufacturer_image')
                ->select('ss.name', 'stock')
                ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd')
                ->left_outer_join('product_to_store', '(p.product_id = p2s.product_id AND p2s.store_id = ' . (int) $this->reg->config->get('config_store_id') . ')', 'p2s')
                ->left_outer_join('manufacturer', 'p.manufacturer_id = m.manufacturer_id', 'm')
                ->left_outer_join('stock_status', '(p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'ss')
                //->where_gt('p.quantity', 0)
                ->where_lte('p.date_available', 'NOW()')
                ->where('p.status', 1);
        $oOrm = clone $oModel;
        $sort_data = array(
            'p.price',
            'pd.name',
            'p.date_added',
            'rating'
        );
        if (in_array($sort, $sort_data)) {
            $sSort = 'pd.name';
        } else {
            $sSort = 'p.price';
        }
        if ($order == 'DESC') {
            $sOrder = "DESC";
        } else {
            $sOrder = "ASC";
        }
        if ($start < 0) {
            $start = 0;
        }
        $oModel = $oModel->order_by($sSort, $sOrder)
                ->offset($start)
                ->limit((int) $limit);
        return array($oOrm->count(), $oModel->find_many(true));
    }
    public function getTotalProductsByCategories() {
        $oModel = ORM::for_table('product_to_category')->table_alias('c')
                ->select_expr('count(p.product_id)', 'total')
                ->select('c.category_id')
                ->left_outer_join('product', 'c.product_id = p.product_id', 'p')
                ->where_gt('p.quantity', 0)
                ->where_lte('p.date_available', 'NOW()')
                ->where('p.status', 1)
                ->group_by('c.category_id');
        return $oModel->find_many(true);
    }
    public function getProductsByManufacturerId($manufacturer_id, $sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 20, $category_id = 0) {
        $oModel = Make::a('catalog/product')->table_alias('p')
                ->select('p.*')
                ->select('pd.name', 'name')
                ->select('p.image')
                ->select('pd.meta_title')
//		->select('m.name', 'manufacturer')
//		->select('m.image', 'manufacturer_image')
                ->select('ss.name', 'stock')
                ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd')
                ->left_outer_join('product_to_store', '(p.product_id = p2s.product_id AND p2s.store_id = ' . (int) $this->reg->config->get('config_store_id') . ')', 'p2s')
                ->left_outer_join('manufacturer', 'p.manufacturer_id = m.manufacturer_id', 'm')
                ->left_outer_join('stock_status', '(p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'ss')
                ->where_gt('p.quantity', 0)
                ->where_lte('p.date_available', date('Y-m-d h:i:s'))
                ->where('p.status', 1)
                ->where('m.manufacturer_id', (int) $manufacturer_id);
        if ($category_id) {
            $oModel = $oModel->inner_join('product_to_category', '(p.product_id = p2c.product_id AND category_id =  ' . (int) $category_id . ')', 'p2c');
        }
        $oOrm = clone $oModel;
        $sort_data = array(
            'p.price',
            'pd.name',
            'p.date_added',
            // 'rating'
        );
        $sSort = $sort;
        if (!in_array($sort, $sort_data)) {
            $sSort = 'pd.name';
        }
        if ($order == 'DESC') {
            $sOrder = "DESC";
        } else {
            $sOrder = "ASC";
        }
        if ($start < 0) {
            $start = 0;
        }
        
        $oModel = $oModel->order_by($sSort, $sOrder);
        if($limit){
            $oModel = $oModel->offset($start)
                ->limit((int) $limit);
        }   
        // d($oModel->find_many(),true);
        return array($oOrm->count(), $oModel->find_many(true));
    }
    public function getProductsByTag($tag, $category_id = 0, $sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 20) {
        if ($tag) {
            $keywords = explode(" ", $tag);
            $query = '';
            foreach ($keywords as $keyword) {
                $query .= " OR LCASE(pt.tag) = '" . $this->reg->db->escape(strtolower($keyword)) . "'";
            }
            $query .= ")";
            $oModel = Make::a('catalog/product')->table_alias('p')
                    ->select('p.*')
                    ->select('pd.name', 'name')
                    ->select('p.image')
                    ->select('pd.meta_title')
//		    ->select('m.name', 'manufacturer')
//		    ->select('m.image', 'manufacturer_image')
                    ->select('ss.name', 'stock')
                    ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd')
                    ->left_outer_join('product_to_store', '(p.product_id = p2s.product_id AND p2s.store_id = ' . (int) $this->reg->config->get('config_store_id') . ')', 'p2s')
//		    ->inner_join('manufacturer', 'p.manufacturer_id = m.manufacturer_id', 'm')
                    ->left_outer_join('stock_status', '(p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'ss')
                    ->left_outer_join('product_tags', '(p.product_id = pt.product_id AND pt.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pt')
                    ->where_gt('p.quantity', 0)
                    ->where_lte('p.date_available', 'NOW()')
                    ->where('p.status', 1)
                    ->where_raw('(LCASE(pt.tag) = "?"' . $query, $this->reg->db->escape(strtolower($tag)))
                    ->group_by('p.product_id');
            if ($category_id) {
                $data = array();
                $string = rtrim($this->getPath($category_id), ',');
                foreach (explode(',', $string) as $category_id) {
                    $data[] = "category_id = '" . (int) $category_id . "'";
                }
                $oModel = $oModel->where_raw('p.product_id IN (SELECT product_id FROM product_to_category WHERE ' . implode(" OR ", $data) . ')');
            }
            $oOrm = clone $oModel;
            $sort_data = array(
                'p.price',
                'pd.name',
                'p.date_added',
                'rating'
            );
            if (in_array($sort, $sort_data)) {
                $sSort = 'pd.name';
            } else {
                $sSort = 'p.price';
            }
            if ($order == 'DESC') {
                $sOrder = "DESC";
            } else {
                $sOrder = "ASC";
            }
            if ($start < 0) {
                $start = 0;
            }
            $oModel = $oModel->order_by($sSort, $sOrder)
                    ->offset($start)
                    ->limit((int) $limit);
            $products = array();
            $aResults = $oModel->find_many(true);
            foreach ($aResults as $key => $value) {
                $products[$value['product_id']] = $this->getProduct($value['product_id']);
            }
            return array($oOrm->count(), $products);
        }
        return array(0, array());
    }
    public function getProductsByKeyword($keyword) {
        $oModel = Make::a('catalog/product_index')->table_alias('pi');
        if ($keyword) {
            $oModel = $oModel
                    ->select('p.*')
                    ->select('pd.name', 'name')
                    ->select('p.image')
                    ->select('pd.meta_title')
                    ->select('ss.name', 'stock')
                    ->left_outer_join('product', 'p.product_id = pi.product_id', 'p')
                    ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd')
                    ->left_outer_join('product_tags', array('p.product_id', '=', 'pt.product_id'), 'pt')
                    ->left_outer_join('stock_status', '(p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'ss')
                    ->where_gt('p.quantity', 0)
                    ->where_lte('p.date_available', 'NOW()')
                    ->where('p.status', 1);
            $query = '(LCASE(pd.name) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"';
            $query .= ' OR LCASE(pd.description) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"';
            $query .= ' OR LCASE(p.model) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"';
            $query .= ' OR LCASE(pt.tag) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"';
            $query .= ' )';
            $oModel = $oModel->where_raw($query);
        }
        return $oModel;
    }
    public function getProductsByKeywordAuto($keyword) {
        if ($keyword) {
            $oModel =Make::a('catalog/product')->where_raw('SELECT pd.* from product p INNER JOIN product_description pd ON pd.product_id=p.product_id p.product_id WHERE (LCASE(pd.name) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"');
//d($oModel->toArray());
            $query = '(LCASE(pd.name) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"';
            $query .= ' OR LCASE(pd.description) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"';
            $query .= ' OR LCASE(p.model) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"';
            $query .= ' OR LCASE(pt.tag) LIKE "%' . $this->reg->db->escape(strtolower($keyword)) . '%"';
            $query .= ' )';
            $oModel = $oModel->where_raw($query);
        }
        return $oModel;
    }
    public function getPath($category_id) {
        $string = $category_id . ',';
        $results = Make::a('catalog/category')->create()->getCategories($category_id);
        foreach ($results as $result) {
            $string .= $this->getPath($result['category_id']);
        }
        return $string;
    }
    public function getLatestProducts($limit) {
        $sql = "SELECT *,
                p.product_id,
                pd.name AS name,
                p.image, m.name AS manufacturer,
                m.image AS manufacturer_image,
                ss.name AS stock
                FROM " . DB_PREFIX . "product p
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id)";
        $sql .= " WHERE p.status = '1'
                    AND p.date_available <= NOW()
                    AND pd.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
                    AND ss.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
                    AND p.quantity > 0
                ORDER BY p.date_added DESC
                LIMIT " . (int) $limit;
        $query = $this->reg->db->query($sql);
        return $query->rows;
    }
    public function getLatestProductsByCategoryId($limit, $category_id) {
        $product_data = $this->reg->cache->get('product.latest.' . $this->reg->config->get('config_language_id') . '.' . (int) $this->reg->config->get('config_store_id') . '.' . $this->reg->session->data['country_id'] . '.' . $limit);
        if (!$product_data) {
            $sql = "SELECT *,
            p.price,
            pd.name AS name,
            p.image,
            m.name AS manufacturer,
            m.image AS manufacturer_image,
            ss.name AS stock
            FROM " . DB_PREFIX . "product p
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
            LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id = p.product_id)
            LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id)
            WHERE p.status = '1'
                AND p2c.category_id = '" . (int) $category_id . "'
                AND p.date_available <= NOW()
                AND p.quantity > 0
                AND pd.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
                AND p2s.store_id = '" . (int) $this->reg->config->get('config_store_id') . "'
                AND ss.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
            ORDER BY p2c.category_id, p.date_added DESC
            LIMIT " . (int) $limit;
            //d($sql);
            $query = $this->reg->db->query($sql);
            $product_data = $query->rows;
            $this->reg->cache->set('product.latest.' . $this->reg->config->get('config_language_id') . '.' . (int) $this->reg->config->get('config_store_id') . '.' . $this->reg->session->data['country_id'] . '.' . $limit, $product_data);
        }
        return $product_data;
    }
    public function getPopularProducts($limit) {
        $sql = "SELECT *,
                pd.name AS name,
                p.image,
                m.name AS manufacturer,
                m.image AS manufacturer_image,
                ss.name AS stock
                FROM " . DB_PREFIX . "product p
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id)
                WHERE p.status = '1'
                    AND p.date_available <= NOW()
                    AND pd.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
                    AND p2s.store_id = '" . (int) $this->reg->config->get('config_store_id') . "'
                    AND p.quantity > 0
                    AND ss.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
                    ORDER BY p.viewed, p.date_added DESC
                    LIMIT " . (int) $limit;
        $query = $this->reg->db->query($sql);
        return $query->rows;
    }
    public function getFeaturedProducts($limit, $random = false) {
        $aProducts = unserialize($this->reg->config->get('featured_product'));
        //d($aProducts);
        //$aRandKeys = array_rand($aProducts, $limit);
        //for($i=0;$i<count($aProducts);$i++){
        $sql2 = "SELECT p.*, pd.*, m.name AS manufacturer
                        FROM " . DB_PREFIX . "product p
                        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
			LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
                        WHERE p.product_id IN (" . join(',', $aProducts) . ")
                            AND p.status = '1'
                            AND p.quantity > 0
                            AND p.date_available <= NOW()
                            AND pd.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
						LIMIT " . $limit;
        $product_query = $this->reg->db->query($sql2);
        if ($product_query->num_rows) {
            $product_data = $product_query->rows;
        }
        // }
        if ($random) {
            $results = array_rand($product_data, $limit);
            $aResult = array();
            foreach ($results as $iResult) {
                $aResult[] = $product_data[$iResult];
            }
        } else {
            $aResult = $product_data;
        }
        return $aResult;
    }
    public function getBestSellerProducts($limit) {
        $product_data = array();
        $aProducts = unserialize($this->reg->config->get('bestseller_product'));
        $sql2 = "SELECT p.*, pd.*
                        FROM " . DB_PREFIX . "product p
                        LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
						WHERE 1 ";
        if ($aProducts) {
            $sql2 .= " AND p.product_id IN (" . join(',', $aProducts) . ")";
        }
        $sql2 .= " 
                            AND p.status = '1'
                            AND p.quantity > 0
                            AND p.date_available <= NOW()
                            AND pd.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
                        ORDER BY RAND()
                        LIMIT " . $limit;
        $product_query = $this->reg->db->query($sql2);
        if ($product_query->num_rows) {
            $product_data = $product_query->rows;
        }
        return $product_data;
    }
    public function updateViewed($product_id) {
        $this->reg->db->query("UPDATE " . DB_PREFIX . "product SET viewed = viewed + 1 WHERE product_id = '" . (int) $product_id . "'");
    }
    public function getProductOptions($product_id) {
        $product_option_data = array();
        $product_option_query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "' ORDER BY sort_order");
        foreach ($product_option_query->rows as $product_option) {
            $product_option_value_data = array();
            $product_option_value_query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int) $product_option['product_option_id'] . "' ORDER BY sort_order");
            foreach ($product_option_value_query->rows as $product_option_value) {
                $product_option_value_description_query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_description WHERE product_option_value_id = '" . (int) $product_option_value['product_option_value_id'] . "' AND language_id = '" . (int) $this->reg->config->get('config_language_id') . "'");
                $product_option_value_data[] = array_merge($product_option_value, array(
                    'name' => $product_option_value_description_query->row['name']
                ));
            }
            $product_option_description_query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_option_description WHERE product_option_id = '" . (int) $product_option['product_option_id'] . "' AND language_id = '" . (int) $this->reg->config->get('config_language_id') . "'");
            $product_option_data[] = array_merge($product_option, array(
                'name' => $product_option_description_query->row['name'],
                'option_value' => $product_option_value_data,
            ));
        }
        return $product_option_data;
    }
    public function getProductImages($product_id) {
        $query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "' ORDER BY sort_order");
        return $query->rows;
    }
    public function getThumbProductImages($product_id) {
        $query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");
        return $query->rows;
    }
    public function getProductImagesByType($product_id, $type) {
        $query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");
        return $query->rows;
    }
    public function getProductTags($product_id) {
        $query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_tags WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->reg->config->get('config_language_id') . "'");
        return $query->rows;
    }
    public function getProductDiscount($product_id) {
        if ($this->reg->customer->isLogged()) {
            $customer_group_id = $this->reg->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->reg->config->get('config_customer_group_id');
        }
        $sSql = "SELECT *
                FROM " . DB_PREFIX . "product_discount
                WHERE product_id = '" . (int) $product_id . "'
                    AND customer_group_id = '" . (int) $customer_group_id . "'
                    AND quantity = 1
                    AND ((date_start = '0000-00-00' OR date_start < NOW())
                    AND (date_end = '0000-00-00' OR date_end > NOW()))
                    ORDER BY priority ASC, price ASC
                    LIMIT 1";
        $query = $this->reg->db->query($sSql);
        if ($query->num_rows) {
            return $query->row;
        } else {
            return FALSE;
        }
    }
    public function getProductDiscounts($product_id) {
        if ($this->reg->customer->isLogged()) {
            $customer_group_id = $this->reg->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->reg->config->get('config_customer_group_id');
        }
        $sSql = "SELECT *
                FROM " . DB_PREFIX . "product_discount
                WHERE product_id = '" . (int) $product_id . "'
                    AND customer_group_id = '" . (int) $customer_group_id . "'
                    AND quantity > 1
                    AND ((date_start = '0000-00-00' OR date_start < NOW())
                    AND (date_end = '0000-00-00' OR date_end > NOW()))
                    ORDER BY quantity ASC, priority ASC, price ASC";
        $query = $this->reg->db->query($sSql);
        return $query->rows;
    }
    public function getProductSpecial($product_id) {
        if ($this->reg->customer->isLogged()) {
            $customer_group_id = $this->reg->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->reg->config->get('config_customer_group_id');
        }
        $sSql = "SELECT * FROM product_special
                WHERE product_id = '" . (int) $product_id . "'
                    AND customer_group_id = '" . (int) $customer_group_id . "'
                    AND ((date_start = '0000-00-00' OR date_start < NOW())
                    AND (date_end = '0000-00-00' OR date_end > NOW()))
                ORDER BY priority ASC, price ASC
                LIMIT 1";
        $query = $this->reg->db->query($sSql);
        if ($query->num_rows) {
            return $query->row;
        } else {
            return FALSE;
        }
    }
    public function getProductSpecials() {
        if ($this->reg->customer->isLogged()) {
            $customer_group_id = $this->reg->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->reg->config->get('config_customer_group_id');
        }
        $results = Make::a('catalog/product_index')->table_alias('pi')
                ->distinct()
                ->select('p.*')
                ->select('pd.*')
                ->select('pi.price', 'filter_price')
                ->select('ss.name', 'stock')
                ->left_outer_join('product', 'p.product_id = pi.product_id', 'p')
                ->left_outer_join('product_description', '(p.product_id = pd.product_id AND pd.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'pd')
                ->left_outer_join('stock_status', '(p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . (int) $this->reg->config->get('config_language_id') . ')', 'ss')
                ->left_outer_join('product_special', 'p.product_id = ps.product_id', 'ps')
                ->where_gt('p.quantity', 0)
                ->where_lte('p.date_available', date('Y-m-d'))
                ->where('p.status', 1)
                ->where_raw('ps.customer_group_id = ? AND ((ps.date_start =  ? OR ps.date_start < ?) AND (ps.date_end = ? OR ps.date_end > ?))', array($customer_group_id, '0000-00-00', date('Y-m-d'), '0000-00-00', date('Y-m-d')));
//        QS::d($results,true);
        return $results;
    }
    public function getTotalProductSpecials() {
        if ($this->reg->customer->isLogged()) {
            $customer_group_id = $this->reg->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->reg->config->get('config_customer_group_id');
        }
        $sSql = "SELECT
                COUNT(Distinct p.product_id) AS total
                FROM " . DB_PREFIX . "product p
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int) $this->reg->config->get('config_language_id') . "')
                LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)
                LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
                LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id)
                WHERE p.status = '1'
                    AND p.date_available <= NOW()
                    AND ps.customer_group_id = '" . (int) $customer_group_id . "'
                    AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())
                        AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";
        //d($sSql);
        $query = $this->reg->db->query($sSql);
        if (isset($query->row['total'])) {
            return $query->row['total'];
        } else {
            return 0;
        }
    }
    public function getProductRelated($product_id, $iType = PRODUCT_RELATED, $limit = 2) {
        $product_data = array();
        $sql = "SELECT * FROM " . DB_PREFIX . "product_related pr
				LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = pr.related_id)
				WHERE pr.product_id = '" . (int) $product_id . "' AND pr.type=" . $iType . " AND p.status = 1 LIMIT " . $limit;
//	d($sql);
        $product_related_query = $this->reg->db->query($sql);
        //d($product_related_query->rows);
        $product = '';
        foreach ($product_related_query->rows as $result) {
            $product = $this->getProduct($result['related_id'], true);
            if ($product) {
                $product_data[$result['related_id']] = $product;
            }
        }
        return $product_data;
    }
    public function getCategories($product_id) {
        $query = $this->reg->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");
        return $query->rows;
    }
    public function update($aData, $product_id) {
        $this->reg->db->update(DB_PREFIX . "product", $aData, "product_id = '" . (int) $product_id . "'");
    }
    public function getProductColors($product_id) {
        /* $sql = "SELECT DISTINCT pd.product_id,ptovd.product_type_option_value_id, ptovd.name,";
          $sql .= "(SELECT hex_code FROM `color` WHERE `name` = ptovd.name LIMIT 1) AS color";
          $sql .= " FROM `product_detail` pd";
          $sql .= " INNER JOIN `product_detail_description` pdd ON pd.product_detail_id = pdd.product_detail_id AND pdd.product_type_option_id=2";
          $sql .= " INNER JOIN `product_type_option_value_description` ptovd ON ptovd.product_type_option_value_id = pdd.product_type_option_value_id";
          $sql .= " WHERE product_id=" . (int) $product_id;
          $sql .= " ORDER BY ptovd.name";
          $query = $this->reg->db->query($sql);
          return $query->rows;/ */
        return array();
    }
    public function getProductSizesThroughColor($product_id, $product_type_option_value_id, $product_type_option_id = 1) {
        /*
          $sql = "SELECT pd.product_id, pd.product_detail_id, price";
          $sql .= " ,ptovd.product_type_option_value_id, ptovd.name";
          $sql .= " FROM `product_detail` pd";
          $sql .= " INNER JOIN `product_detail_description` pdd ON pdd.product_detail_id = pd.product_detail_id AND pdd.product_type_option_id = 2 AND pdd.product_type_option_value_id = " . (int) $product_type_option_value_id;
          $sql .= " INNER JOIN `product_detail_description` pdd2 ON pdd2.product_detail_id = pd.product_detail_id AND pdd2.product_type_option_id = " . (int) $product_type_option_id;
          $sql .= " INNER JOIN `product_type_option_value_description` ptovd ON ptovd.product_type_option_value_id = pdd2.product_type_option_value_id AND ptovd.language_id = " . $this->reg->config->get('config_language_id') . " AND ptovd.product_type_id = " . (int) $product_type_option_id;
          $sql .= " INNER JOIN `product_type_option_value` ptov ON ptov.product_type_option_value_id = pdd2.product_type_option_value_id AND ptov.product_type_option_id = " . (int) $product_type_option_id;
          $sql .= " WHERE pd.product_id = " . (int) $product_id;
          $sql .= " ORDER BY ptov.sort_order, ptovd.name";
          $query = $this->reg->db->query($sql); */
        //return $query->rows;
        return array();
    }
    public function getProductSlabs() {
        $sql = "SELECT * FROM `product_price` order by quantity, priority";
        $query = $this->reg->db->query($sql);
        return $query->rows;
    }
    public function getProductSlabThroughQty($qty) {
        $sql = "SELECT * FROM product_price WHERE quantity <= " . $qty . " ORDER BY quantity DESC, priority ASC LIMIT 1";
        $query = $this->reg->db->query($sql);
        return $query->row;
    }
    public function getProductImageDataThroughColor($product_id, $product_type_option_value_id) {
        $sql = "SELECT DISTINCT pds.name, p.product_id,p.model, pd.color_code,CONCAT('data/',p.model,'_',pd.color_code,'_p.jpg') AS image";
        $sql .= " FROM `product` p";
        $sql .= " INNER JOIN `product_description` pds ON p.product_id = pds.product_id";
        $sql .= " INNER JOIN `product_detail` pd ON p.product_id = pd.product_id";
        $sql .= " INNER JOIN `product_detail_description` pdd ON pd.product_detail_id = pdd.product_detail_id AND pdd.product_type_option_id = 2 AND pdd.product_type_option_value_id='" . (int) $product_type_option_value_id . "'";
        $sql .= " WHERE p.product_id = '" . (int) $product_id . "'";
        $query = $this->reg->db->query($sql);
        return $query->row;
    }
    public function getProductsDetailOptionArray($product_id) {
        $sql = "SELECT DISTINCT pd.product_id, pdd.product_type_option_id, pto.sort_order, ptod.name";
        $sql .= " FROM `product_detail` pd";
        $sql .= " INNER JOIN `product_detail_description` pdd ON pd.product_detail_id = pdd.product_detail_id";
        $sql .= " INNER JOIN `product_type_option` pto ON pto.product_type_option_id = pdd.product_type_option_id";
        $sql .= " INNER JOIN `product_type_option_description` ptod ON pto.product_type_option_id = ptod.product_type_option_id AND ptod.language_id = " . $this->reg->config->get('config_language_id');
        $sql .= " WHERE pd.product_id=" . (int) $product_id;
        $sql .= " ORDER BY pto.sort_order";
        $query = $this->reg->db->query($sql);
        $rows = $query->rows;
        $product_options = array();
        foreach ($rows as $row) {
            $sql = "SELECT DISTINCT pd.product_id, pdd.product_type_option_value_id,ptov.sort_order, ptovd.name";
            $sql .= " FROM `product_detail` pd";
            $sql .= " INNER JOIN `product_detail_description` pdd ON pd.product_detail_id = pdd.product_detail_id AND pdd.product_type_option_id = " . (int) $row['product_type_option_id'];
            $sql .= " INNER JOIN `product_type_option_value` ptov ON ptov.product_type_option_value_id = pdd.product_type_option_value_id";
            $sql .= " INNER JOIN `product_type_option_value_description` ptovd ON ptovd.product_type_option_value_id  = ptov.product_type_option_value_id";
            $sql .= " WHERE pd.product_id=" . (int) $product_id;
            $sql .= " ORDER BY `sort_order`";
            $query = $this->reg->db->query($sql);
            $values = array();
            foreach ($query->rows as $result) {
                $values[] = array(
                    'option_value_id' => $result['product_type_option_value_id'],
                    'option_value_name' => $result['name']
                );
            }
            $product_options[] = array(
                'option_id' => $row['product_type_option_id'],
                'option_name' => $row['name'],
                'values' => $values
            );
        }
        return $product_options;
    }
    public function getMinPriceProduct($product_id) {
        $sql = "SELECT *";
        $sql .= " FROM `product_detail`";
        $sql .= " WHERE product_id =" . (int) $product_id;
        $sql .= " ORDER BY price, `code`";
        $sql .= " LIMIT 1";
        $query = $this->reg->db->query($sql);
        $product_detail = $query->row;
        $sql = "SELECT *";
        $sql .= " FROM `product_detail_description`";
        $sql .= " WHERE product_detail_id = " . $product_detail['product_detail_id'];
        $query = $this->reg->db->query($sql);
        $detail_values = $query->rows;
        $options = array();
        foreach ($detail_values as $value) {
            $options[] = array(
                'option_id' => $value['product_type_option_id'],
                'option_value_id' => $value['product_type_option_value_id']
            );
        }
        $detail = array(
            'product_detail_id' => $product_detail['product_detail_id'],
            'code' => $product_detail['code'],
            'price' => $this->getMinPriceMarkup($product_detail['price']),
            'quantity' => $product_detail['quantity'],
            'options' => $options
        );
        return $detail;
    }
    public function getMinPriceMarkup($price) {
        $sql = "SELECT MIN(percent) AS percent  FROM product_price";
        $query = $this->reg->db->query($sql);
        return round($price * (1 + ($query->row['percent'] / 100)), 2);
    }
    public function getProductImage($product_id) {
        $sSQl = "SELECT * FROM product p
      LEFT JOIN product_description pd ON pd.product_id = p.product_id
       WHERE p.product_id = " . $product_id;
        $query = $this->reg->db->query($sSQl);
        return $query->row;
    }
    public function getLengthClass($length_class_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON lc.length_class_id = lcd.length_class_id WHERE lc.length_class_id = '" . $length_class_id . "' AND lc.is_deleted = 0";
        $query = $this->reg->db->query($sql);
        return $query->row;
    }
    public function getWeightClass($weight_class_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "weight_class lc LEFT JOIN " . DB_PREFIX . "weight_class_description lcd ON lc.weight_class_id = lcd.weight_class_id WHERE lc.weight_class_id = '" . $weight_class_id . "' AND lc.is_deleted = 0";
        $query = $this->reg->db->query($sql);
        return $query->row;
    }
    public function getDetailOptions($id) {
        if (!$id)
            return false;
        $sql = "SELECT DISTINCT pdd.product_type_option_id, pdd.product_type_option_value_id, pto.name AS option_name, ptovd.name AS value_name FROM " . DB_PREFIX . "product_detail pd LEFT JOIN
                        " . DB_PREFIX . "product_detail_description pdd ON pd.product_detail_id = pdd.product_detail_id LEFT JOIN
                        " . DB_PREFIX . "product_type_option_description pto ON pto.product_type_option_id = pdd.product_type_option_id AND pto.language_id = '" . (int) $this->reg->config->get('config_language_id') . "' LEFT JOIN
                        " . DB_PREFIX . "product_type_option_value_description ptovd ON ptovd.product_type_option_value_id = pdd.product_type_option_value_id
                        WHERE pd.product_id = " . (int) $id . "
                        ORDER BY pdd.product_type_option_id";
        $result = $this->reg->db->query($sql);
        $aOptions = array();
        if ($result && $result->num_rows) {
            $option_id = 0;
            $i = 0;
            for ($ind = 0; $ind < $result->num_rows; $ind++) {
                if ($option_id != $result->rows[$ind]['product_type_option_id']) {
                    $aOptions[$i] = array(
                        'id' => $result->rows[$ind]['product_type_option_id'],
                        'name' => $result->rows[$ind]['option_name']
                    );
                }
                $aOptions[$i]['values'][] = array(
                    'id' => $result->rows[$ind]['product_type_option_value_id'],
                    'name' => $result->rows[$ind]['value_name'],
                );
                $option_id = $result->rows[$ind]['product_type_option_id'];
                if ($option_id != $result->rows[$ind + 1]['product_type_option_id']) {
                    $i++;
                }
            }
        }
        return $result->rows;
    }
    /**
     * 
     * @param int $iType
     * @param string $sKeyword
     * @param string $sSort
     * @param string $sOrder
     * @param int $iStart
     * @param int $iLimit
     * @return array
     */
    public function getProductsByType($iType, $sKeyword = '', $sSort = 'p.sort_order', $sOrder = 'ASC', $iStart = false, $iLimit = false) {
        $sSql = "SELECT p.*, 
            pd.name AS name, 
	    pc.category_id,
	    c.name category_name,
            pd.meta_title AS meta_title, 
            ss.name AS stock
            FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)  
            LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_category pc ON (pc.product_id = p.product_id) 
            LEFT JOIN " . DB_PREFIX . "category_description c ON (pc.category_id = c.category_id) 
            WHERE p.status = '1' 
                AND p.date_available <= NOW() 
                AND pd.language_id = '" . (int) $this->reg->config->get('config_language_id') . "' 
                AND ss.language_id = '" . (int) $this->reg->config->get('config_language_id') . "'
                AND p.product_type_id = '" . (int) $iType . "'";
        if ($sKeyword) {
            $sSql .= " AND pd.name LIKE '%" . $this->reg->db->escape($sKeyword) . "%'";
        }
        $sort_data = array(
            'pd.name',
            'p.sort_order',
            'special',
            'p.price',
            'rating'
        );
        if (in_array($sSort, $sort_data)) {
            if ($sSort == 'pd.name') {
                $sSql .= " ORDER BY LCASE(" . $sSort . ")";
            } else {
                $sSql .= " ORDER BY " . $sSort;
            }
        } else {
            $sSql .= " ORDER BY p.sort_order";
        }
        if ($sOrder == 'DESC') {
            $sSql .= " DESC";
        } else {
            $sSql .= " ASC";
        }
        $count_query = $this->reg->db->query($sSql);
        $iCount = $count_query->num_rows;
        if ($iStart === false && $iLimit) {
            if ($iStart < 0) {
                $iStart = 0;
            }
            $sSql .= " LIMIT " . (int) $iStart . "," . (int) $iLimit;
        }
        //d($sSql);
        $query = $this->reg->db->query($sSql);
        return array($iCount, $query->rows);
    }
    public function getTotalProductsByType($iType) {
        $sSql = "SELECT count(*) total
            FROM " . DB_PREFIX . "product p 
            WHERE p.status = '1' 
                AND p.date_available <= NOW() 
                AND p.product_type_id = '" . (int) $iType . "'";
        $count_query = $this->reg->db->query($sSql);
        $iCount = $count_query->row['total'];
        return $iCount;
    }
    /**
     * 
     * @param int $iProductType
     * @return array
     */
    public function getDetailTypes($iProductType) {
        $sSql = 'SELECT * FROM `product_type_option` WHERE product_type_id = ' . (int) $iProductType;
        $result = $this->reg->db->query($sSql);
        return $result->rows;
    }
    public function getProductDetails($product_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'product_type_option_value v
                LEFT JOIN product_type_option_value_description vd ON (vd.product_type_option_value_id = v.product_type_option_value_id AND language_id=' . (int) $this->reg->config->get('config_language_id') . ')
            WHERE v.product_id = ' . (int) $product_id;
        $sql .= ' ORDER BY v.sort_order';
        $result = $this->reg->db->query($sql);
        $aResults = array();
        foreach ($result->rows as $aRow) {
            $aResults[$aRow['product_type_option_id']][] = $aRow;
        }
        return $aResults;
    }
    public function getProductStockStatus($product_id,$date = false) {
        $oOrm = ORM::for_table('product')
                    ->table_alias('p')
                    ->select_expr('SUM(op.quantity) AS quantity,SUM(op.quantity) + p.quantity AS total_qty')
                    ->inner_join('order_product',array('op.product_id','=','p.product_id'),'op')
                    ->inner_join('order',array('o.order_id','=','op.order_id'),'o')
                    // ->where_not_equal('o.order_status_id',$this->reg->config->get('config_failed_order_status'))
                    ->where_not_equal('o.order_status_id',$this->reg->config->get('config_return_order_status'))
                    ->where('p.product_id',$product_id);
        if($date) {
            $oOrm = $oOrm->where_raw('DATE(o.date_added) = ?',array(date('Y-m-d',strtotime($date))));
        }
        $aOrm = $oOrm->find_many(true);
        return $aOrm;
    }
    public function saveProductFavourite($data) {
        $oOrm = ORM::for_table('product_favourite')
                    ->where('product_id',$data['product_id']);
        if($data['customer_id'])
            $oOrm = $oOrm->where('customer_id',$data['customer_id']);
        else
            $oOrm = $oOrm->where('ip_address',$data['ip_address']);
        $oOrm = $oOrm->find_one();
        if(!$oOrm)
            $oOrm = ORM::for_table('product_favourite')->create();
        $oOrm->product_id = $data['product_id'];
        $oOrm->customer_id = $data['customer_id'];
        $oOrm->ip_address = $data['ip_address'];
        $oOrm->save();
    }
    public function getRecommendedProducts($customer_id){
        $oModel = Make::a('catalog/product')->table_alias('p')
            ->distinct()
            ->select('op.product_id')
            ->select('op.name', 'name')
            ->select('op.price')
            ->select('p.image')
            ->select('p.thumb')
            ->select('p.model')
            ->left_outer_join('order_product', '(op.product_id=p.product_id)', 'op')
            ->left_outer_join('product_to_category', 'ptc.product_id=op.product_id', 'ptc')
            ->left_outer_join('order', 'o.order_id=op.order_id', 'o')
            ->where('o.customer_id', $customer_id)
            ->order_by('op.product_id')
            ->limit('10');
      //  $oModel = Make::a('catalog/product')->where_raw('SELECT op.product_id,op.name,op.price,p.image,p.thumb,p.model FROM product pINNER JOIN order_product op ON op.product_id=p.product_id INNER JOIN product_to_category ptc ON ptc.product_id=op.product_id INNER JOIN `order` o ON o.order_id=op.order_id WHERE o.customer_id="23" ORDER BY p.product_id LIMIT 10');
        //d($oModel);
        return $oModel->find_many(true);
    }
    public function addEmailForAvailabilityProduct($data) {
        $this->reg->db->query("INSERT INTO " . DB_PREFIX . "product_alert_emails SET email ='". $data['email'] ."',product_id='". (int) $data['product_id']. "', status='0', date_added=NOW(), date_updated=NOW()");
    }
}
?>
