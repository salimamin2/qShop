<div id="Content" class="container">
    <div class="col-sm-12 col-md-8 checkout-page">
        <div class="page-title">
            <?php if($this->customer->isLogged()) { ?>
                <input type="hidden" id="is_loged" value="1">
            <?php } else { ?>
                <input type="hidden" id="is_loged" value="0">
            <?php } ?>

            <ul class="messages">
                <?php if ($error_warning) { ?>
                    <li class="error-msg">
                        <ul>
                            <li><span><?php echo $error_warning; ?></span></li>
                        </ul>
                    </li>
                <?php } ?>

                <?php if ($error_shipping || $error_payment) { ?>
                    <li class="error-msg">
                        <ul>
                            <li>
                                <span>
                                    <?php echo __('Oops! Error found in ' . ($error_shipping ? 'Shipping' : 'Payment') . ' address. Kindly check below in <span class="red">RED</span>'); ?>
                                </span>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>

            <div class="messages hide" id="alert_messages"></div>

            <div class="opc">
                <div class="step-title" id="login_step">
                    <span class="number"><?php echo __('step_one'); ?></span>
                    <span class="acordian-heading"><?php echo __('login_option'); ?></span>
                    <div class="clearfix"></div>
                </div>

                <?php if (!$this->customer->isLogged()): ?>
                    <!--<p>
                        <a href="<?php echo $login_link ?>">Already registered? Please log in here.</a>
                    </p>-->
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="users col-sm-6">
                                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" id="login_form">
                                    <input type="hidden" name="checkout_login" id="checkout_login" value="checkout_login">

                                    <div class="content row">
                                        <div class="col-sm-12">
                                            <p class="sub-heading-acount"><?php echo __('text_returning_customer'); ?></p>
                                            <div class="line"></div>
                                        </div>

                                        <div class="col-sm-12">
                                            <p class="text-checkout">
                                                <a href="javascript:void(0)" onclick="create_account()" style="color: #a9a9a9">
                                                    <?php echo __('text_create_account'); ?>
                                                </a>
                                                or
                                                <a href="javascript:void(0)" onclick="login_account()" style="color: #a9a9a9">
                                                    <?php echo __('text_sign_in'); ?>
                                                </a>
                                                <?php echo __('text_info'); ?>
                                            </p>
                                        </div>

                                        <div class="fieldset login_register">
                                            <div>
                                                <div id="register-box" style="display: none;">
                                                    <div class="col-sm-12">
                                                        <div class="field">
                                                            <div class="input-box">
                                                                <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" class="input-text required-entry" size="53" placeholder="Enter your first Name *" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="field">
                                                            <div class="input-box">
                                                                <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" class="input-text required-entry" size="53"  placeholder="Enter your last Name *" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="field">
                                                        <div class="input-box">
                                                            <input type="text" id="email" name="email" value="<?php echo $email_address; ?>" class="input-text validate-email required-entry" size="53"  placeholder="Enter your email address *" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="field">
                                                        <div class="input-box">
                                                            <input type="password" id="password" name="password" value="<?php echo $password; ?>" class="input-text required-entry" size="53"  placeholder="Enter your password *" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="buttons-set row">
                                        <input type="hidden" name="login_type" id="login_type">

                                        <div class="col-sm-6">
                                            <a class="f-left link-forget" href="javascript:void(0)" id="link_forgot">
                                               <?php echo __('text_password_forget'); ?>
                                            </a>
                                        </div>
                                        <div class="col-sm-6">
                                            <button type="button" style="display: none;" class="validation-passed btn btn-cntinue btn-account right" id="register">
                                                <span>
                                                    <span> <?php echo __('text_register_now'); ?></span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                             <button type="button" class="validation-passed right btn btn-cntinue btn-account" id="sign_in">
                                                <span>
                                                    <span> <?php echo __('text_login'); ?></span>
                                                </span>
                                            </button>
                                        </div>
                                        
                                        <div class="message_show"></div>
                                    </div>

                                </form>

                                    <div id="forgotten" style="display:none;">
                                        <div class="fieldset">
                                            <p class="sub-heading-acount"><?php echo __('Retreive your password here'); ?></p>
                                            <div class="line"></div>
                                            
                                            <p><?php echo __('text_reset_password'); ?></p>
                                            
                                            <label for="email" class="required"><em>*</em><?php echo __('text_email'); ?></label>
                                            <div class="input-box">
                                                <input type="text" name="email" id="email_forgotten" value="<?php echo $email; ?>" class="input-text required-entry validate-email" size="48" />
                                            </div>
                                        </div>

                                        <div class="fieldset row">
                                        <div class="col-sm-6">
                                            <a class="f-left link-forget" href="javascript:void(0)" id="link_login_form">
                                               <?php echo __('text_login'); ?>
                                            </a>
                                        </div>
                                            <div class="buttons-set right col-sm-6">
                                                <button type="button" class="right btn" id="forget_password">
                                                    <span>
                                                        <span><?php echo __('Submit'); ?></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <div class="users col-sm-6">
                                <div class="content row">
                                    <div class="col-sm-12">
                                        <p class="sub-heading-acount"><?php echo __('Continue as a guest'); ?></p>
                                        <div class="line"></div>
                                    </div>

                                    <div class="col-sm-12">
                                        <button type="button" title="Continue as Guest" class="btn btn-cntinue btn-account" id="guest_btn">
                                            <span>
                                                <span><?php echo __('text_guest'); ?></span>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-12">
                                        <p class="guest-text"><?php echo __('text_guest_detail1'); ?><br><br>
                                        <?php echo __('text_guest_detail2'); ?></p></b>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php echo $this->load('module/hybrid_auth'); ?>
                    </div>
                   
                <?php endif; ?>

                <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="checkout_address" onsubmit="return false;">
                    <div class="clearfix"></div>
                    <div class="step-title">

                        <span class="number"><?php echo __('step_two'); ?></span>
                        <span class="acordian-heading"><?php echo __('text_shipping_address'); ?><?php echo __('text_payment_address'); ?></span>
                        <div class="clearfix"></div>

                    </div>

                    <div class="section active" id="address_information">
                        <div class="step a-item" id="shipping_option">

                            <div class="col-sm-12">
                                <p class="acctab sub-heading-acount" id="acctab-">
                                  Shipping Information
                                </p>
                                <div class="line"></div>
                            </div>

                            <div id="shipping_select" class="col-sm-10 no-padding">
                                <?php if($this->customer->isLogged()): ?>
                                    <div class="col-sm-12">
                                        <?php if ($shipping_addresses) : ?>
                                            <ul class="form-list">
                                                <?php foreach($shipping_addresses as $i => $address): ?>
                                                    <li class="control col-sm-12 no-padding address-pane">
                                                        <div class="skin-minimal">
                                                            <input type="radio" name="shipping[address_id]" value="<?php echo $address['address_id']; ?>" id="shipping_addr_<?php echo $address['address_id']; ?>" class="shipping_address_list address radio left"/>
                                                            <label for="shipping_addr_<?php echo $address['address_id']; ?>" class="left">
                                                                <?php echo $address['address']; ?>
                                                            </label>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-sm-4">
                                        <button type="button" id="shipping_new" class="btn btn-cntinue btn-account btn-another" style="display: <?php echo ($shipping_addresses ? 'block' : 'none'); ?>"><span><span><?php echo $text_new_address; ?></span></span></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="clearfix"></div>
                            <div id="shipping_form" style="display: <?php echo ($shipping_addresses ? 'none' : 'block'); ?>" >
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="sub-heading-acount"><?php echo __('Add new shipping address'); ?></p>
                                            <div class="line"></div>
                                         </div>
                                        <div class="col-sm-6">
                                            <!-- <label for="shipping_firstname" class="required"><em>*</em> <?php echo $entry_firstname; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="shipping[<?php echo $form_code ?>][firstname]" id="shipping_firstname" class="input-text validate-alpha required-entry" value="<?php echo $shipping_address['firstname']; ?>" placeholder="*<?php echo $entry_firstname; ?>"  />
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <!-- <label for="shipping_lastname" class="required"><em>*</em> <?php echo $entry_lastname; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="shipping[<?php echo $form_code ?>][lastname]" class="input-text validate-alpha required-entry" id="shipping_lastname" value="<?php echo $shipping_address['lastname']; ?>" placeholder="*<?php echo $entry_lastname; ?>"  />
                                            </div>
                                        </div>
                                            
                                        <div class="col-sm-6">
                                            <!-- <label for="shipping_company" class="required"><em>*</em> <?php echo 'Shipping Phone:' ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="shipping[<?php echo $form_code ?>][company]" id="shipping_company" value="<?php echo $shipping_address['company']; ?>" class="input-text required-entry validate-phoneStrict" placeholder="*<?php echo 'Shipping Phone:' ?>" />
                                            </div>
                                        </div>

                                        <?php if(!$this->customer->isLogged()) : ?>
                                            <div class="col-sm-6">
                                                <!-- <label for="shipping_email" class="required"><em>*</em> <?php echo __('Shipping Email'); ?></label> -->
                                                <div class="input-box">
                                                    <input type="text" name="shipping[<?php echo $form_code ?>][email]" id="shipping_email" class="input-text validate-email required-entry" value="<?php echo $shipping_address['email']; ?>" placeholder="*<?php echo __('Shipping Email'); ?>" />
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                           
                                        <div class="clearfix"></div>
                                        <div class="col-sm-6">
                                            <!-- <label for="shipping_address_1" class="required"><em>*</em> <?php echo $entry_address_1; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="shipping[<?php echo $form_code ?>][address_1]" id="shipping_address_1" value="<?php echo $shipping_address['address_1']; ?>" class="input-text required-entry" placeholder="<?php echo __('text_address1')?>" />
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="input-box">
                                                <input type="text" name="shipping[<?php echo $form_code ?>][address_2]" id="shipping_address_2" value="<?php echo $shipping_address['address_2']; ?>" class="input-text" placeholder="<?php echo __('text_address2')?>" />
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-6">
                                            <!-- <label for="shipping_city" class="required"><em>*</em> <?php echo $entry_city; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="shipping[<?php echo $form_code ?>][city]" id="shipping_city" value="<?php echo $shipping_address['city']; ?>" class="input-text validate-alpha required-entry" placeholder="*<?php echo $entry_city; ?>" />
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <!-- <label for="shipping_postcode" class="required"><em>*</em><?php echo $entry_postcode; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="shipping[<?php echo $form_code ?>][postcode]" id="shipping_postcode" value="<?php echo $shipping_address['postcode']; ?>" class="input-text validate-zip-international required-entry" placeholder="*<?php echo $entry_postcode; ?>" />
                                                <?php if (isset($error_shipping['postcode']) && $error_shipping['postcode']) { ?>
                                                <span class="error"><?php echo $error_shipping['postcode']; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-6">
                                            <label for="shipping_zone_id" class="required"><em>*</em> <?php echo $entry_zone; ?></label>
                                            <div class="input-box">
                                                <select name="shipping[<?php echo $form_code ?>][zone_id]" id="shipping_zone_id" class="validate-select required-entry" ></select>
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-6">
                                            <label for="shipping_country_id" class="required"><em>*</em> <?php echo $entry_country; ?></label>
                                            <div class="input-box">
                                                <select name="shipping[<?php echo $form_code ?>][country_id]"  id="shipping_country_id" class="validate-select required-entry" onchange="loadZone('shipping_zone_id', this.value, '<?php echo $shipping_address['zone_id']; ?>');">
                                                    <option value=""><?php echo $text_select; ?></option>
                                                    <?php foreach ($countries as $country) : 
                                                    $shId = isset($shipping_address['country_id']) ? $shipping_address['country_id'] : '' ;
                                                    ?>
                                                    <option value="<?php echo $country['country_id']; ?>" <?php if ($country['country_id'] ==  $shId) : ?>selected="selected"<?php endif; ?>><?php echo $country['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?php if (isset($error_shipping['country_id']) && $error_shipping['country_id']) { ?>
                                                <span class="error"><?php echo $error_shipping['country_id']; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <input type="hidden" name="shipping[<?php echo $form_code ?>][address_type]" value="2" />
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="step a-item payment_option">

                            <div class="col-sm-12">
                                <p class="acctab sub-heading-acount" id="acctab-">
                                  Billing Information
                                </p>
                                <div class="line"></div>
                            </div>

                            <div class="control check-shipping-payment col-sm-12">
                                <div class="skin-minimal">
                                    <input type="checkbox" name="same_payment" id="payment_check" checked value="1" />
                                    <label for="payment_check"><?php echo __('entry_payment'); ?></label>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div id="payment_address_select">
                                <?php if($this->customer->isLogged()): ?>
                                    <div class="col-sm-12">
                                        <?php if ($billing_addresses) : ?>
                                            <ul class="form-list">
                                                <?php foreach($billing_addresses as $i => $address): ?>
                                                    <li class="control col-sm-12 no-padding address-pane">
                                                        <div class="skin-minimal">
                                                            <div class="col-xs-2 col-sm-1 no-padding">
                                                                <input type="radio" name="payment[address_id]" value="<?php echo $address['address_id']; ?>" id="payment_addr_<?php echo $address['address_id']; ?>" class="payment_address_list address radio left"/>
                                                            </div>
                                                            <div class="col-xs-10 col-sm-11 no-padding">
                                                            <label for="payment_addr_<?php echo $address['address_id']; ?>" class="left"><?php echo $address['address']; ?></label>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-sm-4">
                                        <button type="button" id="payment_new" class="btn btn-cntinue btn-account btn-another"><span><span><?php echo $text_new_address; ?></span></span></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="clear"></div>

                            <div id="payment_address_box">
                                <div class="col-sm-10">       
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="sub-heading-acount"><?php echo __('Add new billing address'); ?></p>
                                            <div class="line"></div>
                                         </div>


                                        <div class="col-sm-6">
                                            <!-- <label for="payment_firstname" class="required"><em>*</em> <?php echo $entry_firstname; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="payment[<?php echo $form_code ?>][firstname]" id="payment_firstname" class="input-text required-entry validate-alpha" value="<?php echo $payment_address['firstname']; ?>" placeholder="*<?php echo $entry_firstname; ?>" />

                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <!-- <label for="payment_lastname" class="required"><em>*</em> <?php echo $entry_lastname; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="payment[<?php echo $form_code ?>][lastname]" class="input-text required-entry validate-alpha" id="payment_lastname" value="<?php echo $payment_address['lastname']; ?>" placeholder="*<?php echo $entry_lastname; ?>" />

                                            </div>
                                        </div>
                                         
                                        <div class="col-sm-6">
                                            <!-- <label for="payment_company" class="required"><em>*</em> <?php echo 'Billing Phone:' ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="payment[<?php echo $form_code ?>][company]" id="payment_company validate-phoneStrict required-entry" value="<?php echo $payment_address['company']; ?>" class="input-text" placeholder="*<?php echo 'Billing Phone:' ?>" />
                                            </div>
                                        </div>

                                        <?php if(!$this->customer->isLogged()): ?>
                                        <div class="col-sm-6">
                                            <!-- <label for="payment_email" class="required"><em>*</em> <?php echo __('Billing Email'); ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="payment[<?php echo $form_code ?>][email]" id="payment_email" class="input-text validate-email required-entry" value="<?php echo $payment_address['email']; ?>"  placeholder="*<?php echo __('Billing Email'); ?>"/>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="col-sm-6">
                                            <!-- <label for="payment_address_1" class="required"><em>*</em> <?php echo $entry_address_1; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="payment[<?php echo $form_code ?>][address_1]" id="payment_address_1" value="<?php echo $payment_address['address_1']; ?>" class="input-text required-entry" placeholder="<?php echo __('text_address1')?>"/>

                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="input-box">
                                                <input type="text" name="payment[<?php echo $form_code ?>][address_2]" id="payment_address_2" value="<?php echo $payment_address['address_2']; ?>" class="input-text"  placeholder="<?php echo __('text_address2')?>"/>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="col-sm-6">
                                            <!-- <label for="payment_postcode"><?php echo $entry_postcode; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="payment[<?php echo $form_code ?>][postcode]" id="payment_postcode" value="<?php echo $payment_address['postcode']; ?>" class="input-text validate-zip-international required-entry"  placeholder="<?php echo $entry_postcode; ?>"/>

                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <!-- <label for="shipping_city" class="required"><em>*</em> <?php echo $entry_city; ?></label> -->
                                            <div class="input-box">
                                                <input type="text" name="payment[<?php echo $form_code ?>][city]" id="payment_city" value="<?php echo $payment_address['city']; ?>" class="input-text validate-alpha required-entry" placeholder="*<?php echo $entry_city; ?>"/>

                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="col-sm-6">
                                            <label for="payment_zone_id" class="required"><em>*</em> <?php echo $entry_zone; ?></label>
                                            <div class="input-box">
                                                <select name="payment[<?php echo $form_code ?>][zone_id]" id="payment_zone_id" class="validate-select required-entry" >
                                                    <option value=""><?php echo $text_select; ?></option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="payment_country_id" class="required"><em>*</em> <?php echo $entry_country; ?></label>
                                            <div class="input-box">
                                                <select name="payment[<?php echo $form_code ?>][country_id]"  id="payment_country_id" class="validate-select required-entry" onchange="loadZone('payment_zone_id', this.value, '<?php echo $payment_address['zone_id']; ?>');">
                                                    <option value=""><?php echo $text_select; ?></option>
                                                    <?php foreach ($countries as $country) : ?>
                                                    <option value="<?php echo $country['country_id']; ?>" <?php if ($country['country_id'] == $payment_address['country_id']) : ?>selected="selected"<?php endif; ?>><?php echo $country['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?php if (isset($error_payment['country_id']) && $error_payment['country_id']) { ?>
                                                <span class="error"><?php echo $error_payment['country_id']; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <input type="hidden" name="payment[<?php echo $form_code ?>][address_type]" value="1" />
                                    </div>      
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-sm-5">
                                <button type="button" id="next_step" class="btn btn-cntinue btn-account"><span><span><?php echo __('payment_continue'); ?></span></span></button>
                                <div class="clear"></div>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="step-title">
                        <span class="number"><?php echo __('step_three'); ?></span>
                        <span class="acordian-heading"><?php echo __('text_shipping_method'); ?></span>
                        <div class="clearfix"></div>
                    </div>

                    <div class="section active" id="shipping-methods-block">
                        <div class="step a-item">
                            <div>
                                <?php if($shipping_methods): ?>
                                    <?php foreach($shipping_methods as $id => $method): ?>
                                    <div class="col-sm-12 no-padding"><p class="acctab sub-heading-acount"><?php echo $method['title']; ?></p></div>
                                    <div class="line"></div>
                                    <div class="col-sm-12">
                                        <ul class="form-list">
                                            <?php foreach($method['quote'] as $method_id => $aVal): ?>
                                            <li class="control col-sm-12 no-padding skin-minimal">
                                                <div class="col-sm-1">
                                                    <input type="radio" class="shipping_method radio" name="shipping_method[<?php echo $form_code ?>]" value="<?php echo $aVal['id'] ?>" id="shipping_<?php echo $aVal['id'] ?>" />
                                                </div>
                                                <div class="col-sm-11">
                                                    <label for="shipping_<?php echo $id ?>"><?php echo $aVal['title']; ?> <span class="price"><?php echo $aVal['text']; ?></span></label>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <p class="red">No Shipping Method Found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <button type="button" class="btn btn-cntinue btn-account btn-shipping"><span><span><?php echo __('payment_continue'); ?></span></span></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="step-title">
                        <span class="number"><?php echo __('step_four'); ?></span>
                        <span class="acordian-heading"><?php echo __('text_payment_confirmation'); ?></span>
                        <div class="clearfix"></div>
                    </div>

                    <div id="payment_and_information" class="section active">
                        <div class="col-sm-12" >

                            <div class="clear"></div>

                            <?php if ($payment_methods) : ?>

                                <div class="step a-item payment_information_option row">
                                    <div class="col-sm-10">

                                        <div class="col-sm-12 padding-top">
                                            <p class="acctab sub-heading-acount" id="acctab-<?php echo $payment_method['id']; ?>" style="margin:0px;" ><?php echo __('payment_confirmation'); ?></p>
                                            <div class="line"></div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div id="product-tabs">

                                            <div class="Tabs clearer payment-select row">
                                                <?php $i = 0; ?>
                                                <?php foreach ($payment_methods as $payment_method): ?>
                                                    <div class="col-sm-6">
                                                        <div class="list-payment col-sm-12 control skin-minimal">
                                                            <input type="radio" value="<?php echo $payment_method['id']; ?>" id="payment_<?php echo $payment_method['id']; ?>" name="payment_method[<?php echo $form_code; ?>]" class="payment_method"/>
                                                            <label class="payment-text" for="payment_<?php echo $payment_method['id']; ?>">
                                                                <?php echo $payment_method['title']; ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="tabs-panels">
                                                <div class="skin-minimal">
                                                    <input type="checkbox" name="agree" value="1" id="term_condition" />
                                                    <label for="term_condition"><?php echo $text_agree; ?></label>
                                                </div>
                                                <div id="payment-buttons-container" class="buttons-set row">
                                                    <!-- <p class="required">* <?php echo __('text_required_fields'); ?></p> -->
                                                    <div class="col-sm-4">
                                                        <a class="go-back" href="<?php echo str_replace('&', '&amp;', $back); ?>" class="btn left"><?php echo __('Go Back'); ?></a>
                                                    </div>

                                                    <div class="right col-xs-12 col-sm-6">
                                                        <button type="button" class="btn btn-final-checkout" rel=""><span><span><?php echo __('button_confirm'); ?></span></span></button>
                                                    </div>
                                                <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </form>
                <?php foreach ($payment_methods as $i => $payment_method): ?>
                    <div class="payment-panels" id="<?php echo $payment_method['id']; ?>">
                        <div class="box-collateral box-reviews" id="customer-<?php echo $payment_method['id']; ?>">
                            <div class="form-add">
                                <div class="payment-detail">
                                    <?php
                                    if ($payment_method['error']):
                                        echo $payment_method['error'];
                                    else:
                                        echo $this->load('payment/' . $payment_method['id']);
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <script async src="http://sandbox.checkout.com/js/v1/checkoutkit.js"></script>
        <script type="text/javascript">
            var logged = '<?php echo ($this->customer->isLogged() ? 1 : 0); ?>';
            var default_country = '<?php echo $country_id; ?>';
            var shipping_address_id = '<?php echo $shipping_address_id ?>';
            var payment_address_id = '<?php echo $payment_address_id ?>';
            var bPaymentAddress = <?php echo empty($payment_address) ? 0 : 1 ?>;
            var bShippingAddress = <?php echo empty($payment_address) ? 0 : 1 ?>;
            var error_payment_count = <?php echo count($error_payment) ?>;
            var error_shipment_count = <?php echo count($error_shipment) ?>;
            var bPost = true;
            var form_code = '<?php echo $form_code; ?>';
            var free_shipping_amount = <?php echo $free_shipping_amount; ?>;
            var cart_subtotal = <?php echo $cart_subtotal; ?>;
            var comment_url = "<?php echo makeUrl('checkout/confirm/comment', array(), true) ?>";
            var shipping_country_id = "<?php echo $shipping_address['country_id']; ?>";
            var shipping_zone_id = "<?php echo $shipping_address['zone_id']; ?>";
            var payment_country_id = "<?php echo $payment_address['country_id']; ?>";
            var payment_zone_id = "<?php echo $payment_address['zone_id']; ?>";
            var zone_url = "<?php echo makeUrl('layout/zone', array('no-layout=1'), true); ?>";
            var shipping_url = '<?php echo $shipping_action; ?>';
            var shipping_method_url = '<?php echo $shipping_method_action; ?>';
            var url = "<?php echo $success_action ?>";
            var login_url = '<?php echo $action; ?>';
            var text_new_address = '<?php echo $text_new_address; ?>';
            var success_register = "<?php echo $success_register ?>";
            var register_url = '<?php echo $register; ?>';
            var forgotten_url = '<?php echo $forgotten_password; ?>';
            var validate_url = '<?php echo $validateUrl; ?>';
            var coupon_action = '<?php echo $coupon_action; ?>';
        </script>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4">
        <div id="cart-summary">
            <?php echo $this->load('checkout/left_bar') ?>
        </div>
    </div>
</div>