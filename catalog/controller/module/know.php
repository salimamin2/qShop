<?php
class ControllerModuleknow extends Controller {
    protected function index()
    {
        $this->language->load('module/know');

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_more'] = $this->language->get('text_more');

       // $this->data['comments'] = unserialize($this->config->get('know_comments'));
       // $this->data['information_page'] = unserialize($this->config->get('know_information_page'));
      //  $aData = array_combine($this->data['comments'], $this->data['information_page']);
        $aData =  unserialize($this->config->get('know_data'));
        foreach($aData as $key=>$val){
            $this->data['aData'][]=array(
                'url'  => $val['link'],//makeUrl('information/information',array('information_id=' . $val),true),
                'text'=> $val['comment']
            );
        }
        /*foreach($this->data['information_page'] as $information_id) {
            $this->data['more'][] = array(
                 'url' => HTTPS_SERVER . 'index.php?route=information/information&information_id=' . $information_id,
                'information_id'=> $information_id
        );
        }*/

        $more_know = $this->data['aData'];
        shuffle($more_know);
        $this->data['information_page']=$more_know[0];
        $this->id = 'testimonial';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/know.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/know.tpl';
        } else {
            $this->template = 'default/template/module/know.tpl';
        }

        $this->render();
    }
}
?>