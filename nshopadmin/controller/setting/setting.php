<?php

/**
 * Description of setting
 *
 * @author Moiz Shabbir <moiz.sf@gmail.com>
 */
class ControllerSettingSetting extends CRUDController {

    public function getLanguageAlias() {
        return 'setting/setting';
    }

    public function init() {
        $this->data = $this->load->language($this->getLanguageAlias());
        $this->setModel(Make::a('setting/setting'));
        $this->setAlias('setting/setting');
    }

    public function index() {
        $this->getForm();
    }

    public function getForm() {
        parent::getForm();
        $this->document->addScript('../mcimagemanager/js/mcimagemanager.js', Document::POS_HEAD);
        $this->data['arrYesNo'][0] = $this->language->get('text_no');
        $this->data['arrYesNo'][1] = $this->language->get('text_yes');

        $this->data['arrCountries'] = CHtml::listData(ORM::for_table('country')->where('status', 1)->order_by_asc('name')->find_many(), 'country_id', 'name');
        $this->data['arrCurrencies'] = CHtml::listData(ORM::for_table('currency')->where('status', 1)->order_by_asc('title')->find_many(), 'code', 'title');
        $this->data['arrLengths'] = CHtml::listData(ORM::for_table('length_class_description')->where('language_id', $this->config->get('config_language_id'))->order_by_asc('title')->find_many(), 'unit', 'title');
        $this->data['arrWeights'] = CHtml::listData(ORM::for_table('weight_class_description')->where('language_id', $this->config->get('config_language_id'))->order_by_asc('title')->find_many(), 'unit', 'title');
        $this->data['arrInformations'] = CHtml::listData(ORM::for_table('information_description')->where('language_id', $this->config->get('config_language_id'))->order_by_asc('title')->find_many(), 'information_id', 'title');
        $this->data['arrOrderStatus'] = CHtml::listData(ORM::for_table('order_status')->where('language_id', $this->config->get('config_language_id'))->order_by_asc('name')->find_many(), 'order_status_id', 'name');
       // $this->data['arrReturnOrderStatus'] = CHtml::listData(ORM::for_table('order_status')->where('language_id', $this->config->get('config_language_id'))->order_by_asc('name')->find_many(), 'config_return_order_status', 'name');
        $this->data['arrStockStatus'] = CHtml::listData(ORM::for_table('stock_status')->where('language_id', $this->config->get('config_language_id'))->order_by_asc('name')->find_many(), 'stock_status_id', 'name');
        $this->data['arrCustomerGroups'] = CHtml::listData(ORM::for_table('customer_group')->order_by_asc('name')->find_many(), 'customer_group_id', 'name');

        $oCategories = Make::a('catalog/category')->create();
        $this->data['categories'] = $oCategories->getCategories(0);

        $this->data['arrMailProtocol']['smtp'] = 'SMTP';
        $this->data['arrMailProtocol']['mail'] = 'MAIL';
        $this->data['arrMailProtocol']['sendgrid'] = 'Send Grid';

        $oLanguage = Make::a('localisation/language')->create();
        $languages = $oLanguage->getLanguages();
        $aLangs = array();
        foreach ($languages as $language) {
            $aLangs[$language['code']] = $language['name'];
        }

        $this->data['languages'] = $languages;
        $this->data['arrLanguages'] = $aLangs;

        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
            $this->data['preview_logo'] = HTTPS_IMAGE . $this->config->get('config_logo');
        } else {
            $this->data['preview_logo'] = HTTPS_IMAGE .'no_image.jpg';
        }

        if (isset($this->request->post['config_icon'])) {
            $this->data['config_icon'] = $this->request->post['config_icon'];
        } else {
            $this->data['config_icon'] = $this->config->get('config_icon');
        }

        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
            $this->data['preview_icon'] = HTTPS_IMAGE . $this->config->get('config_icon');
        } else {
            $this->data['preview_icon'] = HTTPS_IMAGE .'no_image.jpg';
        }

        $ignore = array(
            'common/login',
            'common/logout',
            'error/not_found',
            'error/permission'
        );

        $this->data['tokens'] = array();

        $files = glob(DIR_APPLICATION . 'controller/*/*.php');

        foreach ($files as $file) {
            $data = explode('/', dirname($file));

            $token = end($data) . '/' . basename($file, '.php');

            if (!in_array($token, $ignore)) {
                $this->data['tokens'][] = $token;
            }
        }

        $settings = $this->getModel()->find_many(true);

        foreach ($settings as $setting) {
            if ($setting['key'] == 'config_category_brand') {
                $this->data[$setting['key']] = unserialize($setting['value']);
                $this->model->{$setting['key']} = unserialize($setting['value']);
            } elseif ($setting['key'] == 'config_category_home') {
                $this->data[$setting['key']] = unserialize($setting['value']);
                $this->model->{$setting['key']} = unserialize($setting['value']);
            } elseif ($setting['key'] == 'config_category_new') {
                $this->data[$setting['key']] = unserialize($setting['value']);
                $this->model->{$setting['key']} = unserialize($setting['value']);
            } elseif ($setting['key'] == 'config_token_ignore') {
                $this->data[$setting['key']] = unserialize($setting['value']);
                $this->model->{$setting['key']} = unserialize($setting['value']);
            } elseif ($setting['key'] == 'config_category_left_menu') {
                $this->data[$setting['key']] = unserialize($setting['value']);
                $this->model->{$setting['key']} = unserialize($setting['value']);
            } else {
                $this->data[$setting['key']] = $setting['value'];
                $this->model->{$setting['key']} = $setting['value'];
            }
        }

        $this->data['arrTemplates'] = array();

        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
        foreach ($directories as $directory) {
            $this->data['arrTemplates'][basename($directory)] = basename($directory);
        }
        $this->data['arrLayouts'] = QS::getLayouts();
        $this->data['arrPages'] = QS::getPages();
        $aModels = Make::a('setting/page_layout')->find_many();
        $this->data['aModelPages'] = $aModels;

        $this->document->addScriptInline("
            $('.fileupload').fileupload({
                url: '" . QS::makeUrl('common/filemanager/upload') . "',
                dataType: 'json',
                dropZone: $(this).parent(),
                autoUpload: true,
                done: function(e,data) {
                    var type = $(this).attr('rel');
                    var result = data.result;
                    if(!result.hasOwnProperty('error')) {
                        $('#preview_'+type).attr('src','" . HTTP_IMAGE . "data/'+data.files[0].name);
                        $('input[name=config_'+type+']').val('data/'+data.files[0].name);
                    }
                    else {
                        alert(result.error);
                    }
                }
            });
            $('.chg-layout').each(function(){
                showLayout(this);
            });
            $('#config_template').change(function(){
                $('#template').load('setting/setting/template&template=' + encodeURIComponent($(this).val()))
            });
            $(document).on('change','.chg-layout',function(){
                showLayout(this);
            });
            $(document).on('change','.chg-page',function(){
                var id = $('option:selected',this).attr('data-id');
                var parent = $(this).parents('tr');
                $('.hdn-page-id',parent).val(id);
            });
            $(document).on('click','#add-row',function(){
                var html = $('#layout-table tfoot').html();
                var count = $('#layout-table tbody tr').length;
                html = html.replace(/\\[-1\\]/g,'['+count+']');
                $('#layout-table tbody').append(html);
            });
            function showLayout(obj){
                var img = $('option:selected',obj).attr('data-image');
                var imagePath = '" . HTTPS_IMAGE . "';
                var parent = $(obj).parents('tr');
                if(typeof img != 'undefined'){
                    $('.img-layout',parent).attr('src',imagePath+'layout/'+img);
                } else {
                    $('.img-layout',parent).attr('src',imagePath+'no_image.jpg');
                }
            }
            ", Document::POS_READY);

        $this->data['sUrl'] = QS::makeUrl('setting/setting/zone');
        $this->template = $this->getAlias() . '.tpl';
        $this->response->setOutput($this->render(), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['config_name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->request->post['config_url']) {
            $this->error['url'] = $this->language->get('error_url');
        }

        if ((strlen(utf8_decode($this->request->post['config_owner'])) < 3) || (strlen(utf8_decode($this->request->post['config_owner'])) > 64)) {
            $this->error['owner'] = $this->language->get('error_owner');
        }

        if ((strlen(utf8_decode($this->request->post['config_address'])) < 3) || (strlen(utf8_decode($this->request->post['config_address'])) > 256)) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if (!$this->request->post['config_title']) {
            $this->error['title'] = $this->language->get('error_title');
        }

        $pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';

        if ((strlen(utf8_decode($this->request->post['config_email'])) > 96) || (!preg_match($pattern, $this->request->post['config_email']))) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((strlen(utf8_decode($this->request->post['config_telephone'])) < 3) || (strlen(utf8_decode($this->request->post['config_telephone'])) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
            $this->error['image_thumb'] = $this->language->get('error_image_thumb');
        }

        if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
            $this->error['image_popup'] = $this->language->get('error_image_popup');
        }

        if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
            $this->error['image_category'] = $this->language->get('error_image_category');
        }

        if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
            $this->error['image_product'] = $this->language->get('error_image_product');
        }

        if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
            $this->error['image_additional'] = $this->language->get('error_image_additional');
        }

        if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
            $this->error['image_related'] = $this->language->get('error_image_related');
        }

        if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
            $this->error['image_cart'] = $this->language->get('error_image_cart');
        }

        if (!$this->request->post['config_error_filename']) {
            $this->error['error_filename'] = $this->language->get('error_error_filename');
        }

        if (!$this->request->post['config_admin_limit']) {
            $this->error['admin_limit'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['config_catalog_limit']) {
            $this->error['catalog_limit'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['config_cross_sell_limit']) {
            $this->error['cross_sell_limit'] = $this->language->get('error_limit');
        }

        if (empty($this->request->post['config_cutoff_time'])) {
            $this->error['cutoff_time'] = $this->language->get('error_cutoff');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            if (!isset($this->error['warning'])) {
                $this->error['warning'] = $this->language->get('error_required_data');
            }
            return FALSE;
        }
    }

    protected function validateForm() {
        if ($this->validatePermission()) {
            //TODO: Validation for insert or update record
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    protected function validateDelete() {
        if ($this->validatePermission()) {
            //TODO: Validation for delete record
        } else {
            $this->error['warning'] = __('Error: You donot have permission to delete record');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function insert() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            try {
                $aLayouts = array();

                if (isset($this->request->post['config_token_ignore'])) {
                    $this->request->post['config_token_ignore'] = serialize($this->request->post['config_token_ignore']);
                }

                if (isset($this->request->post['config_category_new'])) {
                    $this->request->post['config_category_new'] = serialize($this->request->post['config_category_new']);
                }

                if (isset($this->request->post['config_category_left_menu'])) {
                    $this->request->post['config_category_left_menu'] = serialize($this->request->post['config_category_left_menu']);
                }
                if (isset($this->request->post['config_sms_username'])) {
                    $this->request->post['config_sms_username'] = $this->request->post['config_sms_username'];
                }

                if (isset($this->request->post['config_sms_password'])) {
                    $this->request->post['config_sms_password'] = $this->request->post['config_sms_password'];
                }

                if (isset($this->request->post['config_sms_mask'])) {
                    $this->request->post['config_sms_mask'] = $this->request->post['config_sms_mask'];
                }

                if (isset($this->request->post['config_sms_alert_mail'])) {
                    $this->request->post['config_sms_alert_mail'] = $this->request->post['config_sms_alert_mail'];
                }

                if (isset($this->request->post['config_category_home'])) {
                    $this->request->post['config_category_home'] = serialize($this->request->post['config_category_home']);
                }

                if (isset($this->request->post['config_category_brand'])) {
                    $this->request->post['config_category_brand'] = serialize($this->request->post['config_category_brand']);
                }

                if (isset($this->request->post['layout'])) {
                    $aLayouts = $this->request->post['layout'];
                    unset($this->request->post['layout']);
                    unset($aLayouts[-1]);
                }


                $dels = ORM::for_table('setting')->where('group', 'config')->find_many();
                foreach ($dels as $d) {
                    $d->delete();
                }

                foreach ($this->request->post as $key => $value) {
                    $oSetting = ORM::for_table('setting')->create();
                    $oSetting->group = 'config';
                    $oSetting->key = $key;
                    $oSetting->value = $value;
                    $oSetting->save();
//                    if ($oSetting->hasErrors()) {
//                        throw new Exception('Error saving settings');
//                    }
                }

                foreach ($aLayouts as $key => $aVal) {

                    if (isset($aVal['id'])) {
                        $oModel = Make::a('setting/page_layout')->find_one($aVal['id']);
                        unset($aVal['id']);
                    } else {
                        $oModel = Make::a('setting/page_layout')->create();
                    }
                    $oModel->setFields($aVal);
                    $oModel->save();

                    if ($oModel->hasErrors()) {
                        throw new Exception(CHtml::errorSummary($oModel));
                    }
                }
                $this->session->data['success'] = __('text_success');
                $this->redirect(QS::makeUrl($this->getAlias()) . $this->getUrl());
            } catch (Exception $e) {
                $this->error['warning'] = $e->getMessage();
            }
        }
        $this->getForm();
    }

    public function zone() {
        $output = '';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '"';

            if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
                $output .= ' selected="selected"';
            }

            $output .= '>' . $result['name'] . '</option>';
        }

        if (!$results) {
            $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
        }

        $this->response->setOutput($output, $this->config->get('config_compression'));
    }

    public function template() {
        $template = basename($this->request->get['template']);

        if (file_exists(DIR_IMAGE . 'templates/' . $template . '.png')) {
            $image = HTTPS_IMAGE . 'templates/' . $template . '.png';
        } else {
            $image = HTTPS_IMAGE . 'no_image.jpg';
        }

        $this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
    }

    public function deleteCache() {
        $this->cache->deleteAll();
        $this->session->data['success'] = __('Successfully cleared cache');
        $this->redirect(makeUrl('setting/setting'));
    }

    public function deleteFPCCache() {
        FPC::delTree(DIR_CACHE . 'fpc');
        $this->session->data['success'] = __('Successfully cleared FPC Cache');
        $this->redirect(makeUrl('setting/setting'));
    }

}

?>