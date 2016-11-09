<?php

class ControllerInformationSitemap extends Controller {

    public function index() {
	$this->language->load('information/sitemap');

	$this->document->title = $this->language->get('heading_title');

	$this->document->breadcrumbs = array();

	$page = 1;
	if (isset($this->request->get['page']) && $this->request->get['page']) {
	    $page = $this->request->get['page'];
	}
	$limit = 50;
	$start = ($page - 1) * $limit;

	$this->data['heading_title'] = $this->language->get('text_category');

	$aResults = $this->getCategories($start, $limit);
	$this->data['categories'] = $aResults[0];
	$total = $aResults[1];
	$pageTotal = ($start + ($limit));
	$this->data['total'] = $total;
	$this->data['start'] = ($start + 1);
	$this->data['limit'] = $pageTotal > $total ? $total : $pageTotal;

	$pagination = new Pagination();
	$pagination->total = $total;
	$pagination->page = $page;
	$pagination->limit = $limit;
	$pagination->text = null;
	$pagination->url = makeUrl('information/sitemap', array(), true) . '&page={page}';
	$pagination->enable_np = true;
	$pagination->num_links = 10000;
	$pagination->list_type = "ol";
	$pagination->wrapper = false;
	$pagination->active_class = "current";
	$pagination->active_wrapper = false;
	$pagination->prev_class = "previous";
	$pagination->next_links = "next i-next";
	$pagination->prev_links = "previous i-previous";
	$this->data['pagination'] = $pagination->render();

	$this->data['product_link'] = makeUrl('information/sitemap/products', array(), true);
	$this->data['page_link'] = makeUrl('information/sitemap/pages', array(), true);

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/sitemap.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/information/sitemap.tpl';
	} else {
	    $this->template = 'default/template/information/sitemap.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function products() {
	$this->language->load('information/sitemap');

	$this->document->title = $this->language->get('heading_title');

	$this->document->breadcrumbs = array();

	$page = 1;
	if (isset($this->request->get['page']) && $this->request->get['page']) {
	    $page = $this->request->get['page'];
	}
	$limit = 50;
	$start = ($page - 1) * $limit;

	$this->data['heading_title'] = $this->language->get('text_product');

	$oOrm = Make::a('catalog/product')->table_alias('p')
		->left_outer_join('product_description', 'pd.product_id=p.product_id', 'pd')
		->where('p.status', 1)
		->where_gt('p.quantity', 0)
		->where_lte('p.date_available', date('Y-m-d'));
	$oCount = clone $oOrm;
	$aResults = $oOrm
		->offset($start)
		->limit($limit)
		->find_many(true);

	$this->data['products'] = $aResults;
	$total = $oCount->count();
	$pageTotal = ($start + ($limit));
	$this->data['total'] = $total;
	$this->data['start'] = ($start + 1);
	$this->data['limit'] = $pageTotal > $total ? $total : $pageTotal;

	$pagination = new Pagination();
	$pagination->total = $total;
	$pagination->page = $page;
	$pagination->limit = $limit;
	$pagination->text = null;
	$pagination->url = makeUrl('information/sitemap/products', array(), true) . '&page={page}';
	$pagination->enable_np = true;
	$pagination->num_links = 10000;
	$pagination->list_type = "ol";
	$pagination->wrapper = false;
	$pagination->active_class = "current";
	$pagination->active_wrapper = false;
	$pagination->prev_class = "previous";
	$pagination->next_links = "next i-next";
	$pagination->prev_links = "previous i-previous";
	$this->data['pagination'] = $pagination->render();

	$this->data['category_link'] = makeUrl('information/sitemap', array(), true);
	$this->data['page_link'] = makeUrl('information/sitemap/pages', array(), true);

	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/sitemap.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/information/sitemap.tpl';
	} else {
	    $this->template = 'default/template/information/sitemap.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function pages() {
	$this->language->load('information/sitemap');
	$this->load->model('catalog/information');

	$this->document->title = $this->language->get('heading_title');

	$this->document->breadcrumbs = array();

	$limit = 50;
	if (isset($this->request->get['page']) && $this->request->get['page']) {
	    $page = $this->request->get['page'];
	}
	$limit = 10;
	$start = ($page - 1) * $limit;

	$this->data['heading_title'] =$this->language->get('text_information');

	$aResults = $this->model_catalog_information->getInformationList($start, $limit);
	//d($aResults);
	$this->data['informations'] = array();

	foreach ($aResults[0] as $result) {
	    $this->data['informations'][] = array(
		'title' => $result['title'],
		'href' => makeUrl('information/information', array('&information_id=' . $result['information_id']), array(), true)
	    );
	}
	
	$total = $aResults[1];
	$pageTotal = ($start + ($limit));
	$this->data['total'] = $total;
	$this->data['start'] = ($start + 1);
	$this->data['limit'] = $pageTotal > $total ? $total : $pageTotal;

	$pagination = new Pagination();
	$pagination->total = $total;
	$pagination->page = $page;
	$pagination->limit = $limit;
	$pagination->text = null;
	$pagination->url = makeUrl('information/sitemap/pages', array(), true) . '&page={page}';
	$pagination->enable_np = true;
	$pagination->num_links = 10000;
	$pagination->list_type = "ol";
	$pagination->wrapper = false;
	$pagination->active_class = "current";
	$pagination->active_wrapper = false;
	$pagination->prev_class = "previous";
	$pagination->next_links = "next i-next";
	$pagination->prev_links = "previous i-previous";
	$this->data['pagination'] = $pagination->render();


	$this->data['product_link'] = makeUrl('information/sitemap/products', array(), true);
	$this->data['category_link'] = makeUrl('information/sitemap', array(), true);


	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/sitemap.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/information/sitemap.tpl';
	} else {
	    $this->template = 'default/template/information/sitemap.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    protected function getCategories($start, $limit) {
	$aArray = array();
	$results = Make::a('catalog/category')->create()->getMatrix($start, $limit);
//        d($results,true);
	$html = '';
	//negetive counter setup to add root tree row
	$j = -1;

	$iMax = $results['levels'];
	$iRows = count($results['matrix']);
	$aOld = array();
	//$i = 0;
	// Itrating each rows 
	// Expected Array(0 => array(level0 => menu string, level1 => menu string ...), 1 => ...  )
	foreach ($results['matrix'] as $key => $aValues) {
	    // Get 1 column Columns i-e: level0
	    // Expecting menu string = id||link||type||order||name||attributes
	    $top = true;
	    if (empty($aOld) || !isset($aOld['level0']) || !in_array($aValues['level0'], $aOld['level0'])) {
		$top = false;
		$sValue = $aValues['level0'];
		$iLevel = 0;
		// convert string to array
		$aValue = explode('||', $sValue);
		// navigation class level wise
		// generating html

		$html .= '<li class="level-0">';
		// getting link by type
		$link = makeUrl('product/category', array('path=' . $aValue[0]), true);
		// 
		$metalink = QS::getMetaLink($aValue[3], $aValue[2]);
		$html .= '<a href="' . $link . '" title="' . $metalink . '" alt="' . $metalink . '">';
		$html .= $aValue[2];
		$html .= '</a>';
		$html .= '</li>' . "\n";
	    }

	    // check and itrate through children 
	    if (isset($aValues['level1']) && $aValues['level1']) {
		for ($k = 1; $k <= $iMax; $k++) {
		    if (isset($aValues['level' . $k]) && $aValues['level' . $k]) {
			// get child menu string
			$sChild = $aValues['level' . $k];
			$aChild = explode('||', $sChild);
			// create child html
			$bCloseUl = false;
			$cls = 'level-' . $k;
			$html .= '<li class="' . $cls . '" style="padding-left:' . (20 * $k) . 'px;">';
			$path = $aValue[0];
			for ($p = 1; $p <= $k; $p++) {
			    $sPath = $aValues['level' . $p];
			    $aPath = explode('||', $sPath);
			    $path = '_' . $aPath[0];
			}
			$link = makeUrl('product/category', array('path=' . $path), true);
			$metalink = QS::getMetaLink($aChild[3], $aChild[2]);
			$html .= '<a href="' . $link . '" title="' . $metalink . '" alt="' . $metalink . '">';
			$html .= $aChild[2];
			$html .= '</a>';
			// check if it is last level or not have child then end,
			$html .= '</li>' . "\n";
		    }
		}
	    }
	}
	return array($html, $results['total']);
    }

}

?>