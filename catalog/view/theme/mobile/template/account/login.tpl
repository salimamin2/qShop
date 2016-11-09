<div id="Content" class="col-main grid-full in-col1 content-main">
    <div class="account-login col-md-12">

        <div class="text-center">
            <p class="section-title-account">
                <span class="background-account">
                    <span class="border-account"><?php echo __('Login or Create An Account'); ?></span>
                </span>
            </p>
        </div>

        <ul class="messages">
        <?php if ($success) { ?>
            <li class="success-msg">
                <ul>
                    <li><span><?php echo $success; ?></span></li>
                </ul>
            </li>
        <?php } ?>
        <?php if ($error) { ?>
            <li class="error-msg">
                <ul>
                    <li><span><?php echo $error; ?></span></li>
                </ul>
            </li>
        <?php } ?>
        </ul>
        <form class="row" action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="login_create">
            <div class="new-users col-md-6">
                <div class="content">
                    <h2><?php echo $text_i_am_new_customer; ?></h2>
                    <p><?php echo $text_create_account; ?></p>
        	    <?php echo $this->load('module/hybrid_auth'); ?>
                </div>
                <div class="buttons-set">
                    <button type="button" onclick="location.href = '<?php echo $register; ?>'" title="Create an Account" class="btn btn-subs btn-acount-create" ><span><span><?php echo __('Create an Account'); ?></span></span></button>
                    <?php if ($guest_checkout) { ?>
                    <button type="button" title="Guest Checkout" class="button btn-checkout"><span><span><?php echo $text_guest; ?></span></span></button>
                    <?php } ?>
                </div>
            </div>

            <div class="registered-users col-md-6">
                <div class="content">
                    <h2><?php echo $text_returning_customer; ?></h2>
                    <p><?php echo $text_i_am_returning_customer; ?></p>
                    <ul class="form-list">
                        <li>
                            <!-- <label for="email" class="required"><em>*</em><?php echo __('Email Address'); ?></label> -->
                            <div class="input-box">
                                <input placeholder="*<?php echo __('Email Address'); ?>" type="email" name="email" id="email" class="input-text required-entry validate-email" />
                            </div>
                        </li>
                        <li>
                            <!-- <label for="password" class="required"><em>*</em><?php echo $entry_password; ?></label> -->
                            <div class="input-box">
                                <input placeholder="*<?php echo $entry_password; ?>" type="password" name="password" id="password" class="input-text required-entry validate-password" />
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="buttons-set">
                    <a class="f-left" href="<?php echo str_replace('&', '&amp;', $forgotten); ?>"><?php echo $text_forgotten_password; ?></a>
                    <div class="clearfix hidden-sm"></div>
                    <br class="hidden-sm" />
                    <button type="submit" class="btn btn-subs btn-acount-create validation-passed"><span><span><?php echo $button_login; ?></span></span></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
//    $(document).ready(function() {
//	if ($('input[name=account]:checked').val() == 'register') {
//	    $('.section-register').slideDown();
//	}
//	$('input[name=account]').change(function() {
//	    $('.section-register').slideUp();
//	    if ($(this).val() == 'register') {
//		$('.section-register').slideDown();
//
//	    }
//	});
//    });

//    function formValidate() {
//        jQuery("#login").validate();
//        if (jQuery('#login').validate()) {
//            jQuery('form#login').submit();
//        }
//    }

//    jQuery('form#login input').keydown(function(e) {
//        if (e.keyCode == 13 && jQuery('#login').valid()) {
//            jQuery('#login').submit();
//        }
//    });

    function policy() {
	    jQuery('#policy').fadeIn(1100);
    }

    function closePolicy() {
	    jQuery('#policy').fadeOut(1100);
    }
</script> 