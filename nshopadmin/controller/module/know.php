<?php
class ControllerModuleKnow extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('module/know');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $aData = $this->request->post;
            $aData['know_data'] = serialize($this->request->post['know_data']);
            Make::a('setting/setting')->create()->editSetting('did_you_know', $aData);

            $this->data['success'] = $this->language->get('text_success');
            $this->redirect(makeUrl('module/know'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_left'] = $this->language->get('text_left');
        $this->data['text_right'] = $this->language->get('text_right');
        $this->data['text_home'] = $this->language->get('text_home');

        $this->data['entry_limit'] = $this->language->get('entry_limit');
        $this->data['entry_position'] = $this->language->get('entry_position');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'href'      => HTTPS_SERVER . 'common/home&token=' . $this->session->data['token'],
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->document->breadcrumbs[] = array(
            'href'      => HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token'],
            'text'      => $this->language->get('text_module'),
            'separator' => ' :: '
        );

        $this->document->breadcrumbs[] = array(
            'href'      => HTTPS_SERVER . 'module/know&token=' . $this->session->data['token'],
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['action'] = HTTPS_SERVER . 'module/know&token=' . $this->session->data['token'];

        $this->data['cancel'] = HTTPS_SERVER . 'extension/module&token=' . $this->session->data['token'];

        $this->data['know_status'] = $this->config->get('know_status');
        $this->data['know_sort_order'] = $this->config->get('know_sort_order');

        $this->data['aData'] = unserialize($this->config->get('know_data'));

        //d(array($this->data['aData'],$this->data['know_sort_order'],$this->data['know_status'] ));

        $this->data['informations'] = array();
        $this->load->model('catalog/information');

        $results = $this->model_catalog_information->getInformations();

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => HTTPS_SERVER . 'catalog/information/update&token=' . $this->session->data['token'] . '&information_id=' . $result['information_id'] . $url
            );

            $this->data['informations'][] = array(
                'information_id' => $result['information_id'],
                'title'      => $result['title'],
                'leftcolumn' => $result['leftcolumn'],
                'sort_order' => $result['sort_order'],
                'selected'   => isset($this->request->post['selected']) && in_array($result['information_id'], $this->request->post['selected']),
                'action'     => $action
            );
        }
//d($this->data['informations']);

        $this->template = 'module/did_you_know.tpl';

        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/know')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function deleteModuleKnow(){
        //d($this->request->post);

        $this->load->model('setting/setting');
            $aData['know_status'] = $this->config->get('know_status');
            $aData['know_sort_order'] = $this->config->get('know_sort_order');

            $aData['know_data'] = unserialize($this->config->get('know_data'));
            unset($aData['know_data'][$this->request->post['id']]);
            $aData['know_data'] = serialize(array_values($aData['know_data']));
            Make::a('setting/setting')->create()->editSetting('did_you_know', $aData);
      // $this->load->model('catalog/information');

       // $this->model_catalog_information->deleteModuleKnow($this->request->post['key'],$this->request->post['key_id'],$aResultsComments,$aResultsinformation_page);

        echo json_encode(array('success' => true));
    }
}
?>