<?php

class ControllerModuleFrontSlideshow extends Controller {

    private $error = array();

    const IMAGE_PATH = 'data/frontss/';

    public function index() {

	$this->load->language('module/front_slideshow');

	$this->document->title = $this->language->get('heading_title');

	$this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $oData['frontss_data'] = $this->config->get('frontss_data');
            $oData['frontss_status'] = $this->request->post['frontss_status'];
            $oData['frontss_position'] = $this->request->post['frontss_position'];
            Make::a('setting/setting')->create()->editSetting('frontss',$oData);
        }

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_enabled'] = $this->language->get('text_enabled');
	$this->data['text_disabled'] = $this->language->get('text_disabled');
	$this->data['text_image_manager'] = $this->language->get('text_image_manager');

	$this->data['entry_image'] = $this->language->get('entry_image');
	$this->data['entry_link'] = $this->language->get('entry_link');
	$this->data['entry_status'] = $this->language->get('entry_status');
	$this->data['entry_position'] = $this->language->get('entry_position');

	$this->data['button_save'] = $this->language->get('button_save');
	$this->data['button_add_row'] = $this->language->get('button_add_row');
	$this->data['button_cancel'] = $this->language->get('button_cancel');

	if (isset($this->error['warning'])) {
	    $this->data['error_warning'] = $this->error['warning'];
	} else {
	    $this->data['error_warning'] = '';
	}

	$this->document->breadcrumbs = array();

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'common/home',
	    'text' => $this->language->get('text_home'),
	    'separator' => FALSE
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'extension/module',
	    'text' => $this->language->get('text_module'),
	    'separator' => ' :: '
	);

	$this->document->breadcrumbs[] = array(
	    'href' => HTTPS_SERVER . 'module/front_slideshow',
	    'text' => $this->language->get('heading_title'),
	    'separator' => ' :: '
	);

	$this->load->model('tool/image');

	$this->data['action'] = HTTPS_SERVER . 'module/front_slideshow';

	$this->data['cancel'] = HTTPS_SERVER . 'extension/module&token=' . $this->request->get['token'];


	$this->document->addStyle('view/stylesheet/jquery.fileupload-ui.css');


	$this->document->addScript('view/javascript/jquery/tmpl.min.js', Document::POS_END);
	$this->document->addScript('view/javascript/jquery/load-image.min.js', Document::POS_END);
	$this->document->addScript('view/javascript/jquery/canvas-to-blob.min.js', Document::POS_END);

	$this->document->addScript('view/javascript/jquery/jquery.fileupload-process.js', Document::POS_END);
	$this->document->addScript('view/javascript/jquery/jquery.fileupload-image.js', Document::POS_END);
	$this->document->addScript('view/javascript/jquery/jquery.fileupload-ui.js', Document::POS_END);


	if (isset($this->request->post['frontss_status'])) {
	    $this->data['frontss_status'] = $this->request->post['frontss_status'];
	} else {
	    $this->data['frontss_status'] = $this->config->get('frontss_status');
	}

	if (isset($this->request->post['frontss_position'])) {
	    $this->data['frontss_position'] = $this->request->post['frontss_position'];
	} else {
	    $this->data['frontss_position'] = $this->config->get('frontss_position');
	}

	$this->data['frontss'] = unserialize($this->config->get('frontss_data'));

	$this->data['image_url'] = HTTPS_IMAGE;

	$this->data['positions'] = array();
	$this->data['positions'][] = array(
	    'position' => 'left',
	    'title' => $this->language->get('text_left'),
	);
	$this->data['positions'][] = array(
	    'position' => 'right',
	    'title' => $this->language->get('text_right'),
	);
	$this->data['positions'][] = array(
	    'position' => 'home',
	    'title' => $this->language->get('text_home'),
	);

    $this->document->addScriptInline("
        $(document).on('click','.edit_frontss',function(e) {
            var id = $(this).attr('rel');
            var title = $('#frontss_title_'+id).val();
            var link = $('#frontss_link_'+id).val();
            $.ajax({
                url: '".makeUrl('module/front_slideshow/edit')."',
                type: 'POST',
                data: {row: id,title: title,link: link},
                dataType: 'json',
                success: function(res) {
                    if(typeof res.success != 'undefined') {
                        $('.alert-success').html('Success: You have modified FrontPage Slideshow module').removeClass('hide');
                    }
                    else {
                        $('.alert-danger').html('An error occurred while editing.Please try again');
                    }
                }
            });
        });
    ",Document::POS_END);

	$this->template = 'module/front_slideshow.tpl';

	$this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    public function insert() {
	$this->load->language('module/front_slideshow');
	$aResults = unserialize($this->config->get('frontss_data'));
	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
	    $this->load->model('setting/setting');
	    $datas = $this->request->post;

	    $bSuccess = true;
	    if (!move_uploaded_file($_FILES['files']['tmp_name'][0], DIR_IMAGE . self::IMAGE_PATH . $this->request->files['files']['name'][0])) {
		$this->error['warning'] = __('Error: Uploading image');
		$bSuccess = false;
	    }
	    if ($bSuccess) {
		$datas['frontss_data'][$this->request->post['row']]['image'] = self::IMAGE_PATH . $this->request->files['files']['name'][0];
		
		if(!empty($aResults)){
		    $aData = $datas;
		    $aData['frontss_data'] = array_merge($datas['frontss_data'], $aResults);
		} else {
		    $aData = $datas;
		}
		    
		$aData['frontss_data'] = serialize($aData['frontss_data']);
		Make::a('setting/setting')->create()->editSetting('frontss', $aData);

		$oFiles = new stdClass();
		$aFiles = array();
		$aFile = $datas['frontss_data'][$this->request->post['row']];
		if ($aFile['image']) {
		    $aFiles[] = array(
            'row' => $this->request->post['row'],
			'name' => $aFile['image'],
			'size' => 100,
			'thumbnailUrl' => HTTP_IMAGE . $aFile['image'],
			'url' => HTTP_IMAGE . $aFile['image'],
			'title' => html_entity_decode($aFile['title']),
			'link' => $aFile['link'],
			'deleteUrl' => makeUrl('module/front_slideshow/delete', array('id=' . $this->request->post['row'])),
			'deleteType' => 'DELETE',
		    );
		}
		$oFiles->files = $aFiles;
//		$this->response->setOutput(json_encode($oFiles));
            echo json_encode($oFiles);
		exit();
	    }
	}
	if (!empty($this->error)) {
        echo json_encode(array('error' => join('<br />', $this->error)));
	    exit();
	}

	$oFiles = new stdClass();
	$aFiles = array();
	foreach ($aResults as $i => $aFile) {
	    if ($aFile['image']) {
		$aFiles[] = array(
            'row' => $i,
		    'name' => $aFile['image'],
		    'size' => 100,
		    'thumbnailUrl' => HTTP_IMAGE . $aFile['image'],
		    'url' => HTTP_IMAGE . $aFile['image'],
		    'title' => html_entity_decode($aFile['title']),
		    'link' => $aFile['link'],
		    'deleteUrl' => makeUrl('module/front_slideshow/delete', array('id=' . $i)),
		    'deleteType' => 'DELETE',
		);
	    }
	}
	    $oFiles->files = $aFiles;
//	$this->response->setOutput(json_encode($oFiles));
        echo json_encode($oFiles);
    }

    public function edit() {
        $this->load->model('setting/setting');
        $row = $this->request->post['row'];
        $aResults = unserialize($this->config->get('frontss_data'));

        if(!empty($aResults)) {
            $aResults[$row]['title'] = $this->request->post['title'];
            $aResults[$row]['link'] = $this->request->post['link'];

            $aData['frontss_data'] = serialize($aResults);
            $aData['frontss_status'] = $this->config->get('frontss_status');
            $aData['frontss_position'] = $this->config->get('frontss_position');
            Make::a('setting/setting')->create()->editSetting('frontss', $aData);
            echo json_encode(array('success' => 'edit successfully completed'));
        }
        else {
            echo json_encode(array());
        }

    }

    public function delete() {
	if (isset($this->request->get['id']) && $this->request->get['id'] != "") {
	    $this->load->model('setting/setting');
	    $aResults = unserialize($this->config->get('frontss_data'));
        unlink(DIR_IMAGE.$aResults['image']);
	    unset($aResults[$this->request->get['id']]);
	    $sData['frontss_data'] = serialize($aResults);
        $sData['frontss_status'] = $this->config->get('frontss_status');
        $sData['frontss_position'] = $this->config->get('frontss_position');
	    Make::a('setting/setting')->create()->editSetting('frontss', $sData);
	}
    }

    private function validate() {
	if (!$this->user->hasPermission('modify', 'module/front_slideshow')) {
	    $this->error['warning'] = $this->language->get('error_permission');
	}
	$allowed = array(
	    '.jpg',
	    '.jpeg',
	    '.gif',
	    '.png',
	    '.flv'
	);

	if (!in_array(strtolower(strrchr($this->request->files['files']['name'][0], '.')), $allowed)) {
	    $this->error['warning'] = $this->language->get('error_image_allowed');
	}

	if ((int) $this->request->files['files']['size'][0] > 900000) {
	    $this->error['warning'] = $this->language->get('error_image_size');
	}

	$item = $this->request->post['frontss_data'][$this->request->post['row']];

	if (empty($item['title']) || empty($item['link'])) {
	    $this->error['warning'] = $this->language->get('error_required');
	}

	if (!$this->error) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}

?>