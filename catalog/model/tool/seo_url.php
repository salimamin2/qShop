<?php

class ModelToolSeoUrl extends Model {

    public function rewrite($link) {
        if ($this->config->get('config_seo_url')) {
            $url_data = parse_url(str_replace('&amp;', '&', $link));
            $url = '';
            $aParam = array();
            $path = preg_replace('/' . BASE_PATH . '/','',$url_data['path'],1);
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($path) . "'");
            if ($query->num_rows) {
                $aParam[] = $query->row['keyword'];
            }
            $data = array();
            parse_str($url_data['query'], $data);
            foreach ($data as $key => $value) {
                if (($key == 'product_id') || ($key == 'manufacturer_id') || ($key == 'information_id') || 
                    ($key == 'news_id') || ($key == 'blog_post_id') || ($key == 'blog_category_id') || ($key == 'author_id') || ($key == 'manufacturer_cat_id')) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int) $value) . "'");

                    if ($query->num_rows) {
                        $aParam[] = $query->row['keyword'];
                        unset($data[$key]);
                    }
                }
                elseif ($key == 'path') {
                    $categories = explode('_', $value);
                    $aParam = array();
                    $categories = array_reverse($categories);
                    foreach ($categories as $category) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int) $category . "'");

                        if ($query->num_rows) {
                            $aParam[] = $query->row['keyword'];
                        }
                        else {
                            $aParam = array();
                            break;
                        }
                    }
                    $aParam = array_reverse($aParam);
                    unset($data[$key]);
                }
                elseif ($key == 'act') {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $value . "'");
                    if ($query->num_rows) {
                        $aParam[] = $query->row['keyword'];
                    }
                }
            }
            $url = join("/",$aParam);

            if ($url) {
                unset($data['action']);

                $query = '';

                if ($data) {
                    foreach ($data as $key => $value) {
                        if(is_array($value)) {
                            foreach($value as $k => $v) {
                                if(is_array($v)) {
                                    foreach($v as $i => $j) {
                                        $query .= '&' .$key . "[" . $k . "][" . $i . "]=".$j;
                                    }
                                }
                                else {
                                    $param['key'] = $key."[".$k."]";
                                    $param['value'] = $v;
                                    $query .= '&' . $param['key'] . '=' . $param['value'];
                                }
                            }
                        }
                        else {
                            $query .= '&' . $key . '=' . $value;
                        }
                    }
                    if ($query) {
                        $query = '?' . trim($query, '&');
                    }
                }
                if(stristr($link,HTTP_SERVER)) {
                    $new_url = HTTP_SERVER . $url;
                }
                else {
                    $new_url = HTTPS_SERVER . $url;
                }
                return $new_url . $query;
//                return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
            }
            else {
                return $link;
            }
        }
        else {
            return $link;
        }
    }

}

?>