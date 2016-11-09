<div class="col-md-12" id="customerLogin">
    <div class="arrow-navigate">

    </div>
    <div class="middle">
        <div class="alert-warning hide"></div>
        <form action="<?php echo $action; ?>" method="post" id="login-form">
            <input type="hidden" name="checkout_login" value="checkout_login" />
            <div class="login-inner">
                <div class="login-heading col-md-12"><p><?php echo __('text_sign_heading'); ?> <?php echo $site_name; ?></p></div>

                <div class="col-md-12">
                    <input type="email" name="email" placeholder="<?php echo __('entry_email_address') ?>" required />
                </div>

                <div class="col-md-12">
                    <input type="password" name="password" placeholder="<?php echo __('entry_password') ?>" required />
                </div>

                <div class="col-xs-7 col-md-7">
                    <div class="forgot"><a href="account/forgotten"><?php echo __('text_forget_password'); ?></a></div>
                </div>

                <div class="col-xs-5 col-md-5">
                    <?php /*<a onclick="$('#login-form').submit();" class="btn btn-login right">
                        <span>Sign In</span>
                    </a>*/ ?>
                    <input type="button" id="module-sign-in" class="btn btn-login right" value="<?php echo $text_sign_in; ?>" />
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12 text-center">
                    <div class="reg-now">
                        <p><a class="" href="account/create"><?php echo __('text_new_customer'); ?></a></p>    
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>