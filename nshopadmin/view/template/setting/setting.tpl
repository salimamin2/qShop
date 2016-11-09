<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <input type="hidden" name="directory" value="" />
<div class="box">
    <div class="head well">
        <h3><i class="icon-gear"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                <a href="<?php echo $delete_cache; ?>" id="clear_cache" class="btn-flat btn-danger btn-sm"><span><?php echo __('Clear Cache'); ?></span></a>
                <button type="submit" class="btn-flat btn-success btn-sm"><span><?php echo __('button_save'); ?></span></button>
            </div>
        </h3>    
    </div>
        <?php if ($error_warning) : ?>
            <div class="alert alert-danger"><?php echo $error_warning; ?></div>
        <?php endif; ?>
        <?php if ($success) : ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>        
        <div class="content">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_general" data-toggle="tab"><span><?php echo __('General'); ?></span></a></li>
                <li><a href="#tab_store" data-toggle="tab"><span><?php echo __('Store'); ?></span></a></li>
                <li><a href="#tab_local" data-toggle="tab"><span><?php echo __('Local'); ?></span></a></li>
                <li><a href="#tab_option" data-toggle="tab"><span><?php echo __('Option'); ?></span></a></li>
                <li><a href="#tab_image" data-toggle="tab"><span><?php echo __('Image'); ?></span></a></li>
                <li><a href="#tab_mail" data-toggle="tab"><span><?php echo __('Mail'); ?></span></a></li>
                <li><a href="#tab_server" data-toggle="tab"><span><?php echo __('Server'); ?></span></a></li>
                <li><a href="#tab_layout" data-toggle="tab"><span><?php echo __('Page Layout'); ?></span></a></li>
                <li><a href="#tab_sms" data-toggle="tab"><span><?php echo __('SMS'); ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div id="tab_general" class="tab-pane active">
                    <table class="form">
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_name'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_name'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_url'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_url'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_owner'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_owner'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_address'); ?></td>
                            <td><?php echo html_entity_decode(CHtml::activeTextArea($model, 'config_address'), ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_email'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_email'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_telephone'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_telephone'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_fax'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_fax'); ?></td>
                        </tr>
                    </table>
                </div>

                <div id="tab_store" class="tab-pane">
                    <table class="form">
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_title'); ?></td>
                            <td><?php echo CHtml::activetextField($model, 'config_title', array('maxlength' => '70')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_meta_description'); ?></td>
                            <td><?php echo html_entity_decode(CHtml::activeTextArea($model, 'config_meta_description'), ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_meta_keywords'); ?></td>
                            <td><?php echo html_entity_decode(CHtml::activeTextArea($model, 'config_meta_keywords'), ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_template'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_template', $arrTemplates); ?></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td id="template"></td>
                        </tr>
                    </table>
                    <br />
                    <ul id="languages" class="nav nav-tabs">
                        <?php foreach ($languages as $language) : ?>
                        <li class="active"><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content">
                        <?php foreach ($languages as $language) : ?>
                        <div id="language<?php echo $language['language_id']; ?>" class="tab-pane active">
                            <table class="form">
                                <tr>
                                    <td><?php echo CHtml::activeLabelEx($model, 'config_description'); ?></td>
                                    <td><?php echo html_entity_decode(CHtml::activeTextArea($model, 'config_description_'.$language['language_id'], array('id'=>'description_'.$language['language_id'],'data-rel'=>'wyswyg')), ENT_QUOTES, 'UTF-8'); ?></td>
<!--                                        <td><?php echo $entry_description; ?></td>
                                    <td><textarea class="form-control" name="config_description_<?php echo $language['language_id']; ?>" id="description<?php echo $language['language_id']; ?>" data-rel="wyswyg"><?php echo ${'config_description_' . $language['language_id']}; ?></textarea></td>-->
                                </tr>
                            </table>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="tab_local" class="tab-pane">
                    <table class="form">
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_country_id'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_country_id', $arrCountries, array('id' => 'country')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_zone_id'); ?></td>
                            <td>
                                <select name="config_zone_id" id="zone">
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_language'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_language', $arrLanguages, array('id' => 'languages')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_admin_language'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_admin_language', $arrLanguages, array('id' => 'admin_language')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_currency'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_currency', $arrCurrencies); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_currency_auto'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_currency_auto', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_length_class'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_length_class', $arrLengths); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_weight_class'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_weight_class', $arrWeights); ?></td>
                        </tr>
                    </table>
                </div>

                <div id="tab_option" class="tab-pane">
                    <table class="form">
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_admin_limit'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_admin_limit', array('size' => '3')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_catalog_limit'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_catalog_limit', array('size' => '3')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_cross_sell_limit'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_cross_sell_limit', array('size' => '3')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_tax'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_tax', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_invoice_id'); ?></td>
                            <td><?php echo CHtml::activetextField($model, 'config_invoice_id', array('size' => '3')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_invoice_prefix'); ?></td>
                            <td><?php echo CHtml::activetextField($model, 'config_invoice_prefix', array('size' => '3')); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_customer_group_id'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_customer_group_id', $arrCustomerGroups); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_customer_price'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_customer_price', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_customer_approval'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_customer_approval', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_guest_checkout'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_guest_checkout', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_account_id'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_account_id', $arrInformations,array('prompt'=>__('text_none'))); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_checkout_id'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_checkout_id', $arrInformations,array('prompt'=>__('text_none'))); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_stock_display'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_stock_display', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_stock_checkout'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_stock_checkout', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_order_status_id'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_order_status_id', $arrOrderStatus); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_return_order_status'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_return_order_status', $arrOrderStatus); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_stock_status_id'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_stock_status_id', $arrStockStatus); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_review'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_review', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_cart_weight'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_cart_weight', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_reward_status'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_reward_status', $arrOrderStatus); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_allow_reward'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_allow_reward', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_shipping_session'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_shipping_session', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_facebook_page'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_facebook_page'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_twitter_page'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_twitter_page'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_pinterest_page'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_pinterest_page'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_linkedin_page'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_linkedin_page'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_instagram_page'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_instagram_page'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_googleplus_page'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_googleplus_page'); ?></td>
                        </tr>

                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_google_map'); ?></td>
                            <td><?php echo CHtml::activeTextArea($model, 'config_google_map'); ?></td>
                        </tr>

                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_category_new'); ?></td>
                            <td>
                                <div class="scrollbox">
                                    <?php $class = 'odd'; ?>
                                    <?php foreach ($categories as $category) : ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                    <div class="<?php echo $class; ?>">
                                        <input type="checkbox" name="config_category_new[]" value="<?php echo $category['category_id']; ?>" <?php if (!empty($model->config_category_new) && in_array($category['category_id'], $model->config_category_new)) : ?>checked="checked"<?php endif; ?> />
                                            <?php echo $category['name']; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="tab_image" class="tab-pane">
                    <table class="form">
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_logo'); ?></td>
                            <td>
                                <input type="hidden" name="config_logo" value="<?php echo $model->config_logo; ?>" id="logo" />
                                <img src="<?php echo $preview_logo; ?>" alt="" id="preview_logo" class="image" width="100" height="100" />
                                <span class="btn btn-success btn-sm fileinput-button top"><i class="icon-camera"></i>
                                    <span>Select Image...</span>
                                    <!-- The file input field used as target for the file upload widget -->
                                    <input type="file" name="image" rel="logo" class="fileupload">
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_icon'); ?></td>
                            <td>
                                <input type="hidden" name="config_icon" value="<?php echo $model->config_icon; ?>" id="icon" />
                                <img src="<?php echo $preview_icon; ?>" alt="" id="preview_icon" class="image" width="36" height="36"  />
                                <span class="btn btn-success btn-sm fileinput-button top"><i class="icon-camera"></i>
                                    <span>Select Image...</span>
                                    <!-- The file input field used as target for the file upload widget -->
                                    <input type="file" name="image" rel="icon" class="fileupload">
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_image_thumb_width'); ?></td>
                            <td>
                                <?php echo CHtml::activeTextField($model, 'config_image_thumb_width',array('size'=>3)); ?>
                                x
                                <?php echo CHtml::activeTextField($model, 'config_image_thumb_height',array('size'=>3)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_image_popup_width'); ?></td>
                            <td>
                                <?php echo CHtml::activeTextField($model, 'config_image_popup_width',array('size'=>3)); ?>
                                x
                                <?php echo CHtml::activeTextField($model, 'config_image_popup_height',array('size'=>3)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_image_category_width'); ?></td>
                            <td>
                                <?php echo CHtml::activeTextField($model, 'config_image_category_width',array('size'=>3)); ?>
                                x
                                <?php echo CHtml::activeTextField($model, 'config_image_category_height',array('size'=>3)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_image_product_width'); ?></td>
                            <td>
                                <?php echo CHtml::activeTextField($model, 'config_image_product_width',array('size'=>3)); ?>
                                x
                                <?php echo CHtml::activeTextField($model, 'config_image_product_height',array('size'=>3)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_image_additional_width'); ?></td>
                            <td>
                                <?php echo CHtml::activeTextField($model, 'config_image_additional_width',array('size'=>3)); ?>
                                x
                                <?php echo CHtml::activeTextField($model, 'config_image_additional_height',array('size'=>3)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_image_related_width'); ?></td>
                            <td>
                                <?php echo CHtml::activeTextField($model, 'config_image_related_width',array('size'=>3)); ?>
                                x
                                <?php echo CHtml::activeTextField($model, 'config_image_related_height',array('size'=>3)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_image_cart_width'); ?></td>
                            <td>
                                <?php echo CHtml::activeTextField($model, 'config_image_cart_width',array('size'=>3)); ?>
                                x
                                <?php echo CHtml::activeTextField($model, 'config_image_cart_height',array('size'=>3)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_image_blog_width'); ?></td>
                            <td>
                                <?php echo CHtml::activeTextField($model, 'config_image_blog_width',array('size'=>3)); ?>
                                x
                                <?php echo CHtml::activeTextField($model, 'config_image_blog_height',array('size'=>3)); ?>
                            </td>
                        </tr>

                    </table>
                </div>
                <div id="tab_mail" class="tab-pane">
                    <table class="form">
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_mail_protocol'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_mail_protocol', $arrMailProtocol); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_smtp_ssl'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_smtp_ssl', $arrYesNo); ?></td>
                        </tr>    
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_mail_parameter'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_mail_parameter'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_smtp_host'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_smtp_host'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_smtp_username'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_smtp_username'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_smtp_password'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_smtp_password'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_smtp_port'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_smtp_port'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_smtp_timeout'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_smtp_timeout'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_alert_mail'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_alert_mail', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_alert_manufacturer_mail'); ?></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_alert_manufacturer_mail', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_alert_emails'); ?></td>
                            <td><?php echo CHtml::activeTextArea($model, 'config_alert_emails'); ?></td>
                        </tr>
                    </table>
                </div>
                <div id="tab_server" class="tab-pane">
                    <table class="form">
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_ssl'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_ssl', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_maintenance'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_maintenance', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_encryption'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_encryption'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_seo_url'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_seo_url', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_compression'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_compression'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_error_display'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_error_display', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_error_log'); ?></td>
                            <td><?php echo CHtml::activeRadioButtonList($model, 'config_error_log', $arrYesNo); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_error_filename'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_error_filename'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'config_token_ignore'); ?></td>
                            <td>
                                <div class="scrollbox">
                                    <?php $class = 'odd'; ?>
                                    <?php foreach ($tokens as $ignore_token) : ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                    <div class="<?php echo $class; ?>">
                                        <input type="checkbox" name="config_token_ignore[]" value="<?php echo $ignore_token; ?>" <?php if (in_array($ignore_token, $model->config_token_ignore)) : ?>checked="checked"<?php endif; ?> />
                                            <?php echo $ignore_token; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>   
                <div id="tab_layout" class="tab-pane">
                    <div class="btn-group pull-right">
                        <button type="button" id="add-row" class="btn-sm btn-flat"><i class="icon-plus"></i> <?php echo __('Add Row') ?></button>
                    </div>
                    <br class="clearfix" />
                    <br />
                    <table class="table dataTable" id="layout-table">
                        <thead>
                            <tr>
                                <th><?php echo __('Page'); ?></th>
                                <th><?php echo __('Params'); ?> <a href="javascript:void(0);" class="btn-link btn-inverse btn-lg" data-toggle="tooltip" title="Seperate page layout by adding params Ex: path=2 or path=2&product_id=3"><i class="icon-question-sign"></i></a></th>
                                <th><?php echo __('layout'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($aModelPages as $oPage): ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="layout[<?php echo $oPage->id ?>][id]" value="<?php echo $oPage->id ?>" />
                                    <select name="layout[<?php echo $oPage->id ?>][page]" class="chg-page">
                                        <option value="">Select Option</option>
                                        <?php foreach($arrPages as $iId => $sPage): ?>
                                            <option value="<?php echo $sPage ?>" data-id="<?php echo $iId ?>" <?php if($iId == $oPage->page_id): ?>selected="selected"<?php endif; ?>><?php echo $sPage ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" class="hdn-page-id" name="layout[<?php echo $oPage->id ?>][page_id]" value="<?php echo $oPage->page_id ?>" />
                                </td>
                                <td><input type="text" name="layout[<?php echo $oPage->id ?>][params]" value="<?php echo $oPage->params ?>" /></td>
                                <td>
                                    <select name="layout[<?php echo $oPage->id ?>][layout]" class="chg-layout">
                                        <?php foreach($arrLayouts as $aLayout): ?>
                                            <option value="<?php echo $aLayout['id'] ?>" data-image="<?php echo $aLayout['image']; ?>" <?php if($aLayout['id'] == $oPage->layout): ?>selected="selected"<?php endif; ?>><?php echo $aLayout['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <img class="img-layout" />
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="hidden">
                            <tr>
                                <td>
                                    <select name="layout[-1][page]" class="chg-page">
                                        <option value="">Select Page</option>
                                        <?php foreach($arrPages as $iId => $sPage): ?>
                                            <option value="<?php echo $sPage ?>" data-id="<?php echo $iId ?>"><?php echo $sPage ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" class="hdn-page-id" name="layout[-1][page_id]" />
                                </td>
                                <td><input type="text" name="layout[-1][params]" /></td>
                                <td>
                                    <select name="layout[-1][layout]" class="chg-layout">
                                        <?php foreach($arrLayouts as $aLayout): ?>
                                            <option value="<?php echo $aLayout['id'] ?>" data-image="<?php echo $aLayout['image']; ?>"><?php echo $aLayout['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <img class="img-layout" />
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="tab_sms" class="tab-pane">
                    <table class="form">
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'sms_username'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_sms_username'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'sms_password'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_sms_password'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo CHtml::activeLabelEx($model, 'sms_mask'); ?></td>
                            <td><?php echo CHtml::activeTextField($model, 'config_sms_mask'); ?></td>
                        </tr>
                        <tr>
                            <td>Alert Mail:<br /><span class="help">Send a SMS Notification to the store owner</span></td>
                            <td><?php echo CHtml::activeDropDownList($model, 'config_sms_alert_mail', $arrYesNo); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $('#template').load('setting/setting/template&template=' + encodeURIComponent($('select[name=\'config_template\']').attr('value')));

    $('#zone').load('setting/setting/zone&country_id=<?php echo $config_country_id; ?>&zone_id=<?php echo $config_zone_id; ?>');

    $(document).on('change','select[name=\"config_country_id\"]',function(e) {
        var obj = $(this);
        $.ajax({
            url: '<?php echo $sUrl; ?>',
            type: 'GET',
            data:{'country_id':obj.val()},
            success: function(res) {
                $('#zone').html(res);
            }
        });
    });
</script>