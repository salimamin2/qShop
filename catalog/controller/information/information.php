<?php

class ControllerInformationInformation extends Controller {

    public function index() {
	$this->language->load('information/information');
	$this->load->model('catalog/information');
	$this->document->breadcrumbs = array();
	$this->document->breadcrumbs[] = array(
	    'href' => makeUrl('common/home', array(), true),
	    'text' => $this->language->get('text_home'),
	    'separator' => $this->language->get('text_separator')
	);
	if (isset($this->request->get['information_id'])) {
	    $information_id = $this->request->get['information_id'];
	} else {
	    $information_id = 0;
	}
	if (isset($this->request->get['box'])) {
	    $this->data['box'] = $this->request->get['box'];
	} else {
	    $this->data['box'] = false;
	}

	$information_info = $this->model_catalog_information->getInformation($information_id);
	if ($information_info) {
	    $this->document->title = ( isset($information_info['meta_title']) && $information_info['meta_title'] != '' ) ? $information_info['meta_title'] : $information_info['title'];
	    $this->document->breadcrumbs[] = array(
		'href' => makeUrl('information/information', array('information_id=' . $this->request->get['information_id']), array(), true),
		'text' => $information_info['title'],
		'separator' => false
	    );
	    $this->data['heading_title'] = $information_info['title'];
	    $this->data['meta_title'] = $information_info['meta_title'];
	    $this->data['leftcolumn'] = $information_info['leftcolumn'];
	    $this->data['button_continue'] = $this->language->get('button_continue');
	    $this->data['description'] = html_entity_decode($information_info['description']);
	    $this->data['show_title'] = $information_info['show_title'];
	    $this->data['show_recommended'] = $information_info['show_recommended'];
	    $this->data['continue'] = makeUrl('common/home', array(), true);
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/information.tpl';
	    } else {
			$this->template = 'default/template/information/information.tpl';
	    }

	    $this->data['blog_page'] = makeUrl('blog/blog', array(), true);

	    $this->response->setOutput($this->render(), $this->config->get('config_compression'));
	} else {
        $this->language->load('error/not_found');
//        $this->document->layout_col = "col2-left-layout";

        $this->document->title = $this->language->get('heading_title');

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href'      => HTTP_SERVER . 'common/home',
            'text'      => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );

        if (isset($this->request->get['act'])) {
            $this->document->breadcrumbs[] = array(
                'href'      => HTTP_SERVER . '' . $this->request->get['act'],
                'text'      => __('Not Found'),
                'separator' => FALSE
            );
        }

        $this->data['image'] = HTTP_IMAGE . "404.jpg";
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['home'] = makeUrl('common/home',array(),true);
        $this->data['account'] = makeUrl('account/account',array(),true,true);

        $this->data['text_error'] = $this->language->get('text_error');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
		    $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
	    } else {
		    $this->template = 'default/template/error/not_found.tpl';
	    }
	}
    }

    public function term_condition() {
	$this->language->load('information/information');
	if (isset($this->request->get['box'])) {
	    $this->data['box'] = $this->request->get['box'];
	} else {
	    $this->data['box'] = false;
	}
	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['continue'] = makeUrl('common/home', array(), true);
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/term_condition.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/information/term_condition.tpl';
	} else {
	    $this->template = 'default/template/information/term_condition.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function privacy() {
	$this->language->load('information/information');
	if (isset($this->request->get['box'])) {
	    $this->data['box'] = $this->request->get['box'];
	} else {
	    $this->data['box'] = false;
	}
	$this->data['button_continue'] = $this->language->get('button_continue');
	$this->data['continue'] = makeUrl('common/home', array(), true);
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/privacy.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/information/privacy.tpl';
	} else {
	    $this->template = 'default/template/information/privacy.tpl';
	}

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function loadInfo() {
	$this->load->model('catalog/information');
	if (isset($this->request->get['information_id'])) {
	    $information_id = $this->request->get['information_id'];
	} else {
	    $information_id = 0;
	}
	$information_info = $this->model_catalog_information->getInformation($information_id);
	if ($information_info) {
	    $output = '<html dir="ltr" lang="en">' . "\n";
	    $output .= '<head>' . "\n";
	    $output .= '  <title>' . $information_info['title'] . '</title>' . "\n";
	    $output .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
	    $output .= '</head>' . "\n";
	    $output .= '<body>' . "\n";
	    $output .= '  <br /><br /><h2>' . $information_info['title'] . '</h2>' . "\n";
	    $output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
	    $output .= '  </body>' . "\n";
	    $output .= '</html>' . "\n";
	    $this->response->setOutput($output);
	}
    }

    public function loadInformation() {
        $params = $this->request->get['params'];
        $this->load->model('catalog/information');
        if(isset($params['id']) && $params['id']) {
            $information_info = $this->model_catalog_information->getInformation($params['id']);
            if ($information_info) {
                $this->data['description'] = html_entity_decode($information_info['description']);
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/static_information.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/information/static_information.tpl';
                } else {
                    $this->template = 'default/template/information/static_information.tpl';
                }
            }
            $this->response->setOutput($this->render(), $this->config->get('config_compression'));
        }
    }

}

?>