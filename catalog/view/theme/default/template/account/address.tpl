<div id="Content" class="my-account container">

    <div class="my-account col-md-12 page-description">
    
        <?php echo $this->load('module/account'); ?>
        
        <?php /*<div class="page-title">
            <h1><?php //echo $heading_title; ?></h1>
        </div>*/ ?>

        <div class="post-content">

            <ul class="messages">
                <?php if($errors): ?>
                    <li class="error-msg">
                        <ul>
                            <?php foreach($errors as $error): ?>
                                <li><span><?php echo $error; ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="address">
                
                <p class="sub-heading-acount"><?php echo __('Contact Information'); ?></p>
                <div class="line"></div>

                <div class="row">
                    <div class="col-sm-6">
                        <?php /*<label for="firstname" class="required"><em>*</em><?php echo $entry_firstname; ?></label>*/ ?>
                        <div class="input-box">
                            <input type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>" class="input-text required-entry" minlength="3" maxlength="64" placeholder="<?php echo $entry_firstname; ?>" />
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <?php /*<label for="lastname" class="required"><em>*</em><?php echo $entry_lastname; ?></label>*/ ?>
                        <div class="input-box">
                            <input type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>" class="input-text required-entry" minlength="3" maxlength="64" placeholder="<?php echo $entry_lastname; ?>" />
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <?php /*<label for="company" class="required"><em>*</em><?php echo $entry_company; ?></label>*/ ?>
                        <div class="input-box">
                            <input class="input-text required-entry" type="text" name="company" id="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" />
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <p class="sub-heading-acount"><?php echo __('Address'); ?></p>
                <div class="line"></div>

                <div class="row">
                    <div class="col-sm-6">
                        <?php /*<label for="address_1" class="required"><em>*</em><?php echo $entry_address_1; ?></label>*/ ?>
                        <div class="input-box">
                            <input type="text" name="address_1" id="address_1" value="<?php echo $address_1; ?>" class="input-text  required-entry" minlength="3" maxlength="64" placeholder="<?php echo __('text_address1')?>" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-box">
                            <input class="input-text" type="text" name="address_2" id="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo __('text_address2')?>" />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <label for="country_id" class="required"><span class="color-red">*</span>&nbsp;<?php echo $entry_country; ?></label>
                        <div class="input-box">
                            <select class="validate-select country_id" name="country_id" id="country_id">
                                <option value=""><?php //echo $text_select; ?> Select Country</option>
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?php echo $country['country_id']; ?>" <?php echo ($country['country_id'] == $country_id ? "selected" : ''); ?> ><?php echo $country['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="zone_id" class="required"><span class="color-red">*</span>&nbsp;<?php echo $entry_zone; ?></label>
                        <input type="hidden" class="zone" value="<?php echo $zone_id; ?>" />
                        <div class="input-box">
                            <select class="validate-select zone_id" name="zone_id" class="required-entry" id="zone_id">
                                <option>Select Region Or State</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php /*<label for="city" class="required"><em>*</em><?php echo $entry_city; ?></label>*/ ?>
                        <div class="input-box">
                            <input type="text" name="city" id="city" value="<?php echo $city; ?>" class="input-text required-entry" minlength="3" maxlength="64" placeholder="<?php echo $entry_city; ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php /*<label for="postcode"><?php echo $entry_postcode; ?></label>*/ ?>
                        <div class="input-box">
                            <input type="text" name="postcode" id="postcode" value="<?php echo $postcode; ?>" class="input-text" minlength="2" maxlength="10" placeholder="<?php echo $entry_postcode; ?>" />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <label for="address_type"><?php echo $entry_address_type; ?></label>
                        <div class="input-box">
                            <?php echo CHtml::dropDownList('address_type',$address_type,array(1 => 'Billing',2 => 'Shipping'),array('class' => 'validate-select address_type')); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6" style="margin:10px 0px;">
                        <div class="skin-minimal">
                            <input type="checkbox" name="default" id="default" class="checkbox" <?php echo ($default ? "checked" : ""); ?> />&nbsp;&nbsp;
                            <label for="default" class="checkbox-inline"><?php echo __('Make this address default'); ?></label>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="buttons-set">
                        <?php /*<p class="required">* Required Fields</p>
                            <p class="back-link">
                                <a href="<?php echo str_replace('&', '&amp;', $back); ?>"><small>&laquo; </small><?php echo $button_back; ?></a>
                            </p>
                        */ ?>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-cntinue btn-account"><span><span><?php echo __('Save'); ?></span></span></button>
                        </div>
                    </div>
                <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // jQuery(document).ready(function(){
    //     jQuery('.skin-minimal input').iCheck({
    //         checkboxClass: 'icheckbox_square-grey',
    //         radioClass: 'iradio_square-grey',
    //         increaseArea: '80%'
    //     });
    // });
</script>

