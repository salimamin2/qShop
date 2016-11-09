<div id="Content" class="my-account container">
    <div class="col-md-12">
        <?php echo $this->load('module/account'); ?>

        <div class="user-welcome">
            <p>Welcome</p>
            <p class="user-name"><?php echo $welcome_title; ?></p>
            <p><a href="account/logout">Log Out</a></p>
        </div>

        <div class="clearfix"></div>

        <div class="col-main">
            <div class="">
                <div class="dashboard">
                    <ul class="messages post-center">
                        <?php if ($errors) { ?>
                            <li class="error-msg">
                                <ul>
                                    <?php foreach($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($success) { ?>
                        <li class="success-msg">
                            <ul>
                                <li><?php echo $success; ?></li>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>

                    <div class="box-account box-info">

                        <div class="row">
                            <div class="col-md-12">
                                <ul class="tabs-menu">
                                    <li class="current"><a href="#tab-1"><?php echo $text_my_orders; ?></a></li>
                                    <li><a href="#tab-2" class="ajax" id="tab2"><?php echo $text_contact_information; ?></a></li>
                                    <li><a href="#tab-3" class="ajax" id="tab3"><?php echo $text_address_book; ?></a></li>
                                    <li><a href="#tab-4" class="ajax" id="tab4"><?php echo __('text_my_likes'); ?></a></li>
                                </ul>

                                <div class="tab page-description">

                                    <div id="tab-1" class="tab-content post-content active">
                                        <?php echo $this->load('account/history'); ?>
                                        <?php if ($reward): ?>
                                            <p><?php echo $text_order_reward ?> (<a href="<?php echo str_replace('&', '&amp;', $reward); ?>"><?php echo $text_detail ?></a>)</p>
                                        <?php endif; ?>
                                    </div>
                                    <div id="tab-2" class="tab-content post-content">
                                        <div class="">
                                            <?php echo $this->load('account/edit'); ?>
                                        </div>
                                    </div>
                                    <div id="tab-3" class="tab-content">                                   
                                        <?php if ($address_edit == 0) { ?>
                                            <?php if($this->customer->isLogged()): ?>
                                                <div class="shipping-addresses">
                                                    <h2>Shipping Addresses</h2>
                                                    <?php if ($shipping_addresses) { ?>
                                                        <ul class="form-list row">
                                                            <?php foreach($shipping_addresses as $i => $address): ?>
                                                                <li class="control col-sm-3">
                                                                    <div class="account-address">
                                                                        <?php if($address['address_id'] == $default): ?>
                                                                            <div class="default">Default</div>
                                                                        <?php else : ?>
                                                                            <div class="additional">Additional</div>
                                                                        <?php endif; ?>
                                                                        <label for="shipping_addr_<?php echo $address['address_id']; ?>" class="left">
                                                                            <p><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?></p>
                                                                            <p><?php echo $address['address']; ?></p>
                                                                        </label>
                                                                        <div class="clearfix"></div>
                                                                        <a class="edit-account" href="<?php echo $address['href']; ?>">Edit</a>
                                                                    <div class="clearfix"></div>
                                                                    </div>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php } else { ?>
                                                        <p><img src="catalog/view/theme/default/image/img/address-emotion.png"></p>
                                                        <p>You’ve not saved a shipping address yet.</p>
                                                    <?php } ?>
                                                </div>
                                                <hr />
                                                <div class="billing-addresses">
                                                    <h2>Billing Addresses</h2>
                                                    <?php if ($billing_addresses) { ?>
                                                        <ul class="form-list row">
                                                            <?php foreach($billing_addresses as $i => $address): ?>
                                                                <li class="control col-sm-3">
                                                                    <div class="account-address">
                                                                        <?php if($address['address_id'] == $default): ?>
                                                                            <div class="default">Default</div>
                                                                        <?php else : ?>
                                                                            <div class="additional">Additional</div>
                                                                        <?php endif; ?>
                                                                        <label for="shipping_addr_<?php echo $address['address_id']; ?>" class="left">
                                                                            <?php echo $address['address']; ?>
                                                                        </label>
                                                                        <div class="clearfix"></div>
                                                                        <a class="edit-account" href="<?php echo $address['href']; ?>">Edit</a>
                                                                    <div class="clearfix"></div>
                                                                    </div>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php } else { ?>
                                                        <p><img src="catalog/view/theme/default/image/img/address-emotion.png"></p>
                                                        <p>You’ve not saved a billing address yet.</p>
                                                    <?php } ?>
                                                </div>
                                                <div class="clearfix"></div>
                                            <?php endif; ?>

                                            <div class="if-orders">
                                                <div class="add-address">
                                                    <div class="row">
                                                        <p>Save all your shipping addresses to complete the order process quickly.<br />Save your preferred address as default and it will automatically appear in your Shopping Bag.</p>
                                                        <p><a class="btn btn-add-address" href="account/address/insert">Add a new address</a></p>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } else { ?>

                                        <?php } ?>

                                        <p>
                                            <?php if ($address_edit != 0): ?>
                                                <a href="<?php echo str_replace('&', '&amp;', $address_edit); ?>"><?php echo $text_edit; ?></a>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div id="tab-4" class="tab-content post-content">
                                        <div class="">
                                            <?php echo $this->load('account/wishlist'); ?>
                                        </div>
                                    </div>
                                </div><!-- //tab -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tab = '<?php echo $tab; ?>';
    jQuery(document).ready(function() {
        jQuery('a#' + tab).trigger('click');
    });

    jQuery(document).on('click',".tabs-menu a",function(e) {
        event.preventDefault();
        jQuery(this).parent().siblings().removeClass("current");
        jQuery(this).parent().addClass("current");
        var tab = jQuery(this).attr("href");
        jQuery(".tab-content").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
    });

    jQuery(document).on('click','.edit-password',function(e) {
        jQuery(this).closest('div.row').addClass('hide');
        jQuery('.edit-password-box').removeClass('hide');
        jQuery('.validate-password,.validate-cpassword').addClass('required-entry');
    });
</script>