<div class="box">
    <div class="head well">
        <h3><i class="icon-money"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                <a onclick="$('#form').submit();" class="btn btn-success"><span><?php echo $button_save; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a>
            </div>
        </h3> 
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-checkoutapipayment" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                    <select name="checkoutapipayment_status" id="input-status" class="form-control">
                        <?php if ($checkoutapipayment_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_status_on; ?></option>
                        <option value="0"><?php echo $text_status_off; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_status_on; ?></option>
                        <option value="0" selected="selected"><?php echo $text_status_off; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-mode"><?php echo $entry_test_mode; ?></label>
                <div class="col-sm-10">
                    <select name="checkoutapipayment_test_mode" id="input-mode" class="form-control">
                        <?php if ($checkoutapipayment_test_mode == 'sandbox') { ?>
                        <option value="sandbox" selected="selected"><?php echo $text_mode_sandbox; ?></option>
                        <?php } else { ?>
                        <option value="sandbox"><?php echo $text_mode_sandbox; ?></option>
                        <?php } ?>
                        <?php if ($checkoutapipayment_test_mode == 'live') { ?>
                        <option value="live" selected="selected"><?php echo $text_mode_live; ?></option>
                        <?php } else { ?>
                        <option value="live"><?php echo $text_mode_live; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-secret-key"><?php echo $entry_secret_key; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="checkoutapipayment_secret_key" value="<?php echo $checkoutapipayment_secret_key; ?>" placeholder="<?php echo $entry_secret_key; ?>" id="input-secret-key" class="form-control" />
                    <?php if ($error_secret_key) { ?>
                    <div class="text-danger"><?php echo $error_secret_key; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-merchant-id"><?php echo $entry_public_key; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="checkoutapipayment_public_key" value="<?php echo $checkoutapipayment_public_key; ?>" placeholder="<?php echo $entry_public_key; ?>" id="input-public-key" class="form-control" />
                    <?php if ($error_public_key) { ?>
                    <div class="text-danger"><?php echo $error_public_key; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-mode"><?php echo $entry_localpayment_enable; ?></label>
                    <div class="col-sm-10">
                        <select name="checkoutapipayment_localpayment_enable" id="input-mode" class="form-control">
                        <?php if ($checkoutapipayment_localpayment_enable == 'no') { ?>
                        <option value="no" selected="selected"><?php echo $text_localPayment_no; ?></option>
                        <?php } else { ?>
                        <option value="no"><?php echo $text_localPayment_no; ?></option>
                        <?php } ?>
                        <?php if ($checkoutapipayment_localpayment_enable == 'yes') { ?>
                        <option value="yes" selected="selected"><?php echo $text_localPayment_yes; ?></option>
                        <?php } else { ?>
                        <option value="yes"><?php echo $text_localPayment_yes; ?></option>
                        <?php } ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-mode"><?php echo $entry_pci_enable; ?></label>
                <div class="col-sm-10">
                    <select name="checkoutapipayment_pci_enable" id="input-pci-mode" class="form-control">
                        <?php if ($checkoutapipayment_pci_enable == 'no') { ?>
                        <option value="yes" selected="selected"><?php echo $text_pci_yes; ?></option>
                        <?php } else { ?>
                        <option value="yes"><?php echo $text_pci_yes; ?></option>
                        <?php } ?>
                        <?php if ($checkoutapipayment_pci_enable == 'no') { ?>
                        <option value="no" selected="selected"><?php echo $text_pci_no; ?></option>
                        <?php } else { ?>
                        <option value="no"><?php echo $text_pci_no; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-payment-action"><?php echo $entry_payment_action; ?></label>
                <div class="col-sm-10">
                    <select name="checkoutapipayment_payment_action" id="input-mode" class="form-control">
                        <?php if ($checkoutapipayment_payment_action == 'authorization') { ?>
                        <option value="authorization" selected="selected"><?php echo $text_auth_only; ?></option>
                        <?php } else { ?>
                        <option value="authorization"><?php echo $text_auth_only; ?></option>
                        <?php } ?>
                        <?php if ($checkoutapipayment_payment_action == 'capture') { ?>
                        <option value="capture" selected="selected"><?php echo $text_auth_capture; ?></option>
                        <?php } else { ?>
                        <option value="capture"><?php echo $text_auth_capture; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-autocapture-delay"><?php echo $entry_autocapture_delay; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="checkoutapipayment_autocapture_delay" value="0" id="input_autocapture_delay" class="form-control" />
                </div>
            </div>
            <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-gateway-timeout"><?php echo $entry_gateway_timeout; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="checkoutapipayment_gateway_timeout" value="0" id="input_gateway_timeout" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_successful_order_status; ?></label>
                <div class="col-sm-10">
                    <select name="checkoutapipayment_checkout_successful_order" id="input-order-status" class="form-control">
                        <?php foreach($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $checkoutapipayment_checkout_successful_order) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_failed_order_status; ?></label>
                <div class="col-sm-10">
                    <select name="checkoutapipayment_checkout_failed_order" id="input-order-status" class="form-control">
                        <?php foreach($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $checkoutapipayment_checkout_failed_order) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="checkoutapipayment_sort_order" value="<?php echo $checkoutapipayment_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
            </div>
            <fieldset>
                <legend><?php echo $text_button_settings; ?></legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-logo-url"><?php echo $entry_logo_url; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="checkoutapipayment_logo_url" value="<?php echo $checkoutapipayment_logo_url; ?>" placeholder="<?php echo $entry_logo_url; ?>" id="input-logo-url" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-theme-color"><?php echo $entry_theme_color; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="checkoutapipayment_theme_color" value="<?php echo $checkoutapipayment_theme_color; ?>" placeholder="<?php echo $entry_theme_color; ?>" id="input-theme-color" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-button-color"><?php echo $entry_button_color; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="checkoutapipayment_button_color" value="<?php echo $checkoutapipayment_button_color; ?>" placeholder="<?php echo $entry_button_color; ?>" id="input-button-color" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-icon-color"><?php echo $entry_icon_color; ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="checkoutapipayment_icon_color" value="<?php echo $checkoutapipayment_icon_color; ?>" placeholder="<?php echo $entry_icon_color; ?>" id="input-icon-color" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-currency-format"><?php echo $entry_currency_format; ?></label>
                    <div class="col-sm-10">
                        <select name="checkoutapipayment_currency_format" id="input-currency-format" class="form-control">
                            <?php if ($checkoutapipayment_currency_format == 'code') { ?>
                            <option value="true" selected="selected"><?php echo $text_code; ?></option>
                            <?php } else { ?>
                            <option value="true"><?php echo $text_code; ?></option>
                            <?php } ?>
                            <?php if ($checkoutapipayment_currency_format == 'symbol') { ?>
                            <option value="false" selected="selected"><?php echo $text_symbol; ?></option>
                            <?php } else { ?>
                            <option value="false"><?php echo $text_symbol; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
             </fieldset>
        </form>
    </div>
</div>
<script type="text/javascript"><!--
    $('#button-ip-add').on('click', function() {
        var ip = $.trim($('#input-ip').val());

        if (ip) {
            $('#ip-allowed').append('<div><i class="fa fa-minus-circle"></i> ' + ip + '<input type="hidden" name="amazon_checkout_ip_allowed[]" value="' + ip + '" /></div>');
        }

        $('#input-ip').val('');
    });

    $('#ip-allowed').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });

    $('input[name=\'amazon_checkout_cron_job_token\']').on('keyup', function() {
        $('#input-cron-job-url').val('<?php echo $store; ?>index.php?route=payment/amazon_checkout/cron&token=' + $(this).val());
    });
    //--></script></div>
<?php echo $footer; ?>