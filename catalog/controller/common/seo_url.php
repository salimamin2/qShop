<?php

class ControllerCommonSeoUrl extends Controller {

    public function index() {
		if (isset($this->request->get['_act_'])) {
		    $parts = explode('/', $this->request->get['_act_']);
		    $bChange = false;
		    foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
				if ($query->num_rows) {
				    $url = explode('=', $query->row['query']);
				    if ($url[0] == 'product_id') {
					    $this->request->get['product_id'] = $url[1];
				    }
		            elseif ($url[0] == 'category_id') {
		                if (!isset($this->request->get['path'])) {
		                    $this->request->get['path'] = $url[1];
		                } else {
		                    $this->request->get['path'] .= '_' . $url[1];
		                }

				    }
		            elseif ($url[0] == 'manufacturer_id') {
					    $this->request->get['manufacturer_id'] = $url[1];
				    }
		            elseif ($url[0] == 'information_id') {
					    $this->request->get['information_id'] = $url[1];
				    }
		            elseif ($url[0] == 'news_id') {
					    $this->request->get['news_id'] = $url[1];
				    }
		            elseif ($url[0] == 'blog_post_id') {
					    $this->request->get['blog_post_id'] = $url[1];
				    }
		            elseif ($url[0] == 'blog_category_id') {
					    $this->request->get['blog_category_id'] = $url[1];
				    }
				    elseif ($url[0] == 'manufacturer_cat_id') {
					    $this->request->get['manufacturer_cat_id'] = $url[1];
				    }
		            elseif ($url[0] == 'author_id') {
					    $this->request->get['author_id'] = $url[1];
				    }
		            else {
					    $this->request->get['act'] = $url[0];
				    }
				    $bChange = true;
				}
		    }


		    if ($bChange) {
				if (isset($this->request->get['product_id'])) {
				    $this->request->get['act'] = 'product/product';
				} elseif (isset($this->request->get['path'])) {
				    $this->request->get['act'] = 'product/category';
				} elseif (isset($this->request->get['manufacturer_id'])) {
				    $this->request->get['act'] = 'product/manufacturer';
				} elseif (isset($this->request->get['information_id'])) {
				    $this->request->get['act'] = 'information/information';
				} elseif (isset($this->request->get['news_id'])) {
				    $this->request->get['act'] = 'information/news';
				}
		    }
		    if (!isset($this->request->get['act'])) {
				$this->request->get['act'] = $this->request->get['_act_'];
		    }
	    	if (!isset($this->request->get['no-layout']) && !($this->request->isAjax()))
				return $this->forward('layout');
		}
    }
}

?>