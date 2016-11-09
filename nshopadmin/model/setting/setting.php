<?php

/**
 * Description of setting
 *
 * @author Moiz Shabbir <moiz.sf@gmail.com>
 */
class ModelSettingSetting extends ARModel {

    public static $_table = 'setting';
    //fields
    protected $_fields = array(
        'id',
        'group',
        'key',
        'value',
    );
    //validation rules
    protected $_rules = array(
        'insert' => array(
            'rules' => array(
                'config_name' => array('required' => true, 'minlength' => 5, 'maxlength' => 50),
                'config_url' => array('required' => true, 'minlength' => 10, 'maxlength' => 250),
                'config_owner' => array('required' => true),
                'config_address' => array('required' => true),
                'config_email' => array('required' => true),
                'config_telephone' => array('required' => true),
                'config_title' => array('required' => true),
                'config_admin_limit' => array('required' => true, 'maxlength' => 3),
                'config_catalog_limit' => array('required' => true, 'maxlength' => 3),
                'config_image_thumb_width' => array('required' => true),
                'config_image_thumb_height' => array('required' => true),
                'config_image_popup_width' => array('required' => true),
                'config_image_popup_height' => array('required' => true),
                'config_image_category_width' => array('required' => true),
                'config_image_category_height' => array('required' => true),
                'config_image_product_width' => array('required' => true),
                'config_image_product_height' => array('required' => true),
                'config_image_additional_width' => array('required' => true),
                'config_image_additional_height' => array('required' => true),
                'config_image_related_width' => array('required' => true),
                'config_image_related_height' => array('required' => true),
                'config_image_cart_width' => array('required' => true),
                'config_image_cart_height' => array('required' => true),
                'config_image_blog_width' => array('required' => true),
                'config_image_blog_height' => array('required' => true),
                'config_error_filename' => array('required' => true),
            ),
        )
    );
    protected $_labels = array(
        'authorizenet_aim_login' => 'Login ID',
        'authorizenet_aim_key' => 'Transaction Key',
        'authorizenet_aim_server' => 'Transaction Server',
        'config_name' => 'Company Name',
        'config_url' => 'Company Website:<br /><span class="help">Include the full URL to your site. Example: http://www.yourdomain.com</span>',
        'config_owner' => 'Administrator Name',
        'config_address' => 'Address',
        'sms_username' => 'Username',
        'sms_password' => 'Password',
        'sms_mask' => 'Mask',
        'config_location_map' => 'Location Map',
        'config_email' => 'Administrator Email',
        'config_email_volunteer' => 'Volunteer Email',
        'config_email_career' => 'Career Email',
        'config_telephone' => 'Company Telephone',
        'config_application_status' => 'Default Service Application Status',
        'config_fax' => 'Fax',
        'config_title' => 'Title',
        'config_meta_description' => 'Meta Tag Description',
        'config_template' => 'Template',
        'config_description' => 'Welcome Message',
        'config_country_id' => 'Default Country',
        'config_zone_id' => 'Default Zone',
        'config_language' => 'Default Catalog Language',
        'config_admin_language' => 'Default Admin Language',
        'config_currency' => 'Default Currency',
        'config_length_class' => 'Default Length Unit',
        'config_weight_class' => 'Default Weight Unit',
        'config_tax' => 'Display Prices With Tax',
        'config_invoice_id' => 'Invoice Start No.:<br><span class="help">Set the starting number the invoices will begin from.</span>',
        'config_invoice_prefix' => 'Invoice Prefix:<br><span class="help">Set the invoice prefix e.g. IN/001</span>',
        'config_customer_group_id' => 'Customer Group:<br><span class="help">Default customer group.</span>',
        'config_customer_price' => 'Login Display Prices:<br><span class="help">Only show prices when a customer is logged in.</span>',
        'config_customer_approval' => 'Approve New Customers:<br><span class="help">Don\'t allow new customer to login until their account has been approved.</span>',
        'config_guest_checkout' => 'Guest Checkout:<br><span class="help">Allow customers to checkout without creating an account. This will not be available when a downloadable product is in the shopping cart.</span>',
        'config_account_id' => 'Account Terms:<br><span class="help">Forces people to agree to terms before an account can be created.</span>',
        'config_checkout_id' => 'Checkout Terms:<br><span class="help">Forces people to agree to terms before an a customer can checkout.</span>',
        'config_stock_display' => 'Display Stock:<br><span class="help">Display stock quantity on the product page.</span>',
        'config_stock_checkout' => 'Stock Checkout:<br><span class="help">Allow customers to still checkout if the products they are ordering are not in stock.</span>',
        'config_order_status_id' => 'Order Status:<br><span class="help">Set the default order status when an order is processed.</span>',
        'config_return_order_status' => 'Return Order Status:<br><span class="help">When Order is canceled.</span>',
        'config_review' => 'Allow Reviews:<br><span class="help">Enable/Disable new review entry and display of existing reviews</span>',
        'config_reward_status' => 'Reward Point Order Status:<br><span class="help">Select order status to add Reward point in customer autometically</span>',
        'config_allow_reward' => 'Allow Reward Points:<br><span class="help">Select Yes to allow shopping using Reward points</span>',
        'config_shipping_session' => 'Use Shipping Session:<br><span class="help">Saves shipping quotes to session to avoid re-quoting unnecessarily. Quotes will only be re-quoted if cart or address is changed.</span>',
        'config_icon' => 'Browser Icon:<br><span class="help">The icon should be a PNG that is 16px x 16px.</span>',
        'config_alert_mail' => 'Alert Mail:<br><span class="help">Send an email to the store owner when a new order is created.</span>',
        'config_alert_emails' => 'Additional Alert E-Mails:<br><span class="help">Any additional emails you want to receive the alert email, in addition to the main store email. (comma separated)</span>',
        'config_token_ignore' => 'Ignore Tokens on these pages:<br><span class="help">This version of Q-Cart has a token system for admin security. Modules that have not been updated for token support yet can be checked to ignore the token check and allow them to work as normal.</span>',
        'config_stock_status_id' => 'Stock Status:',
        'config_cart_weight' => 'Cart Weight:',
        'config_facebook_page' => 'Facebook Page Url:',
        'config_twitter_page' => 'Twitter Page Url:',
        'config_linkedin_page' => 'Linked In Page Url:',
        'config_instagram_page' => 'Instagram Page Url:',
        'config_googleplus_page' => 'Google Plus Page Url:',
        'config_pinterest_page' => 'Pinterest Page Url:',
        'config_google_map' => 'Google Map Code:',
        'config_image_thumb_width' => 'Product Image Thumb Size:',
        'config_image_popup_width' => 'Product Image Popup Size:',
        'config_image_category_width' => 'Category List Size:',
        'config_image_product_width' => 'Product List Size:',
        'config_image_additional_width' => 'Additional Product Image Size:',
        'config_image_related_width' => 'Related Product Image Size:',
        'config_image_cart_width' => 'Cart Image Size:',
        'config_image_blog_width' => 'Blog Image Size:',
        'config_smtp_timeout' => 'SMTP Timeout:',
        'config_logo' => 'Company Logo:',
        'config_category_new' => 'New Products in Category:',
        'config_currency_auto' => 'Auto Update Currency:<br><span class="help">Set your store to automatically update currencies daily.</span>',
        'config_auto_approve_customer' => 'Auto Approve Member?:<br /><span class="help">Auto approve the member as it signup or verifies?</span>',
        'config_newsletter_subscription' => 'Send Newsletter Subscription Mail:<br /><span class="help">Send mail to newsletter subscriber?</span>',
        'config_admin_limit' => 'Default Items per Page (Admin):<br><span class="help">Determines how many admin items are shown per page (orders, customers, etc)</span>',
        'config_catalog_limit' => 'Default Items per Page (Catalog):<br><span class="help">Determines how many catalog items are shown per page (products, categories, etc)</span>',
        'config_cross_sell_limit' => 'Show Items of Cross sell:<br><span class="help">Determines how many cross sell items are shown on cart</span>',
        'config_mail_protocol' => 'Mail Protocol:<span class="help">Only choose \'Mail\' unless your host has disabled the php mail function.</span>',
        'config_mail_parameter' => 'Mail Parameters:<span class="help">When using \'Mail\', additional mail parameters can be added here (e.g. "-femail@storeaddress.com".</spn>',
        'config_smtp_ssl' => 'SSL Enabled',
        'config_smtp_auth' => 'Authentication Required',
        'config_sms_local' => 'SMS Domain:<div class="help">Ex: echoemail.net System will send it to [mobile number]@echoemail.net </div>',
        'config_sms_countries' => 'Select SMS Countries:<div class="help">Countries where SMS service are available.</div>',
        'config_smtp_host' => 'SMTP Host',
        'config_smtp_username' => 'SMTP Username',
        'config_smtp_password' => 'SMTP Password',
        'config_smtp_port' => 'SMTP Port',
        'config_smtp_timeput' => 'SMTP Timeout',
        'config_smtp_alert_mail' => 'Alert Email',
        'config_smtp_alert_emails' => 'Alert Email CC:<span class="help">list of emails. saperated by comma.</span>',
        'config_alert_manufacturer_mail' => 'Send Designer Email:<br /><span class="help">Email send to designer on order creation</span>',
        'config_ssl' => 'Use SSL:<br /><span class="help">To use SSL check with your host if a SSL certificate is installed and added the SSL URL to the admin config file.</span>',
        'config_smtp_secure' => 'SMTP Secure:<br /><span class="help">Works only if SSL is enabled.Default value `ssl`. if using sendgrid value should be `tls`</span>',
        'config_maintenance' => 'Maintenance Mode:<br /><span class="help">Prevents customers from browsing your store. They will instead see a maintenance message. If logged in as admin, you will see the store as normal.</span>',
        'config_encryption' => 'Encryption Key:<br /><span class="help">Please provide a secret key that will be used to encrypt private information when processing orders.</span>',
        'config_seo_url' => 'Use SEO URL\'s:<br /><span class="help">To use SEO URL\'s apache module mod-rewrite must be installed and you need to rename the htaccess.txt to .htaccess.</span>',
        'config_compression' => 'Output Compression Level:<br /><span class="help">GZIP for more efficient transfer to requesting clients. Compression level must be between 0 - 9</span>',
        'config_error_display' => 'Display Errors',
        'config_error_log' => 'Log Errors',
        'config_error_filename' => 'Error Log Filename',
        'config_google_api_key' => 'Google API Key',
        'config_facebook_href' => 'Facebook Url',
        'config_linkedin_href' => 'Linked In Url',
        'config_twitter_href' => 'Twitter Url',
        'config_instagram_href' => 'instagram Url',
        'config_googleplus_href' => 'Google Plus Url',
        'config_twitter_href' => 'Twitter Url',
        'config_home' => 'Home page welcome page',
        'config_home_visa' => 'Home page visa page',
        'config_home_travel' => 'Home page travel page',
        'config_terms' => 'Terms and Condition page',
        'config_privacy' => 'Privacy Policy page',
        'created_at' => 'Date Added',
        'created_by_id' => 'Creator'
    );

    function getLaguages() {
        return Make::a('localisation/language');
    }

    public function getUser() {
        return $this->belongs_to('user/user', 'created_by_id');
    }

    public function init() {
        //TODO:initialize model
        parent::init();
        $this->orm->enable_audit = true;
    }

    public function beforeDelete() {
        //parent::beforeDelete();
        return false;
    }

    public function editSetting($sGroup, $aData) {
        $dels = ORM::for_table('setting')->where('group', $sGroup)->find_many();
        foreach ($dels as $d) {
            $d->delete();
        }
        foreach ($aData as $key => $value) {
            $oSetting = ORM::for_table('setting')->create();
            $oSetting->group = $sGroup;
            $oSetting->key = $key;
            $oSetting->value = $value;
            $oSetting->save();
        }
    }

    public function getTotalSettingByLike($group) {
        $oSetting = ORM::for_table('setting')
                ->select_expr('count(DISTINCT `group`)', ' group')
                ->where_raw('`group` LIKE ?', array('%' . $group . '%'))
                ->order_by_asc('id')
                ->find_one();
        //$query = $this->db->query("SELECT count(DISTINCT `group`) total FROM " . DB_PREFIX . "setting WHERE `group` LIKE '%" . $this->db->escape($group) . "%' ORDER BY setting_id");

        return $oSetting->group;
    }

    public function deleteSetting($group) {
        return ORM::raw_execute("DELETE FROM setting WHERE `group` = '" . $this->db->escape($group) . "'");
    }

    public function validateDelete() {
        //TODO:validate delete
    }

}

?>