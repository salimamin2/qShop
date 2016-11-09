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
                     <?php /* <div class="page-title">
                        <h1><?php echo $heading_title; ?></h1>
                    </div> */ ?>
                    <ul class="messages post-center">
                        <?php if ($success) { ?>
                        <li class="success-msg">
                            <ul>
                                <li><?php echo $success; ?></li>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>

                    <?php /*  
                    <div class="welcome-msg">
                        <p class="hello"><?php echo $welcome_title; ?></p>
                        <p><?php echo $text_welcome; ?></p>
                    </div>
                    */ ?>

                    <div class="box-account box-info">

                        <?php /* <div class="box-head">
                            <h2><?php echo __("Account Information"); ?></h2>
                        </div> */ ?>

                        <div class="row">
                            <div class="col-md-12">
                                <ul class="tabs-menu">
                                    <li class="current"><a href="#tab-1" id="tab1"><?php echo $text_my_orders; ?></a></li>
                                    <li><a href="#tab-2" class="ajax" id="tab2"><?php echo $text_contact_information; ?></a></li>
                                    <li><a href="#tab-3" id="tab3"><?php echo $text_address_book; ?></a></li>
                                </ul>

                                <div class="tab page-description">

                                    <div id="tab-1" class="tab-content post-content active">
                                        <?php /*<div class="">
                                            <a href="<?php echo str_replace('&', '&amp;', $history); ?>"><?php echo $text_history; ?></a>
                                        </div> */ ?>
                                        <?php /* if ($order_total_number == 0) { ?>
                                            <div class="if-orders">
                                                <img src="catalog/view/theme/default/image/img/emotion-cart.png">
                                                <p>You’ve not previously ordered anything as a registered user.</p>
                                                <a class="btn btn-cntinue">Continue Shopping</a>
                                            </div>
                                        <?php } */ ?>
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

                                        <?php /*
                                            <h4><?php echo $text_address_book; ?></h4>
                                            <a href="<?php echo str_replace('&', '&amp;', $address); ?>"><?php echo $text_edit; ?></a> 
                                        */ ?>

                                        <p>
                                            <?php /*<?php echo $address_entry; ?><br />*/ ?>
                                            <?php if ($address_edit != 0): ?>
                                                <a href="<?php echo str_replace('&', '&amp;', $address_edit); ?>"><?php echo $text_edit; ?></a>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div><!-- //tab -->
                            </div>
                        </div>

                        <?php /*
                        <div class="col-2">
                            <div class="box">
                                <div class="box-title">
                                    <h4><?php echo $text_my_newsletter; ?></h4>
                                    <a href="<?php echo str_replace('&', '&amp;', $newsletter); ?>"><?php echo $text_edit; ?></a>
                                </div>
                                <div class="box-content">
                                    <p>
                                        <?php echo $text_newsletter_status; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        */ ?>

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