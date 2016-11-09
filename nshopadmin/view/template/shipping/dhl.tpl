<div class="box">
    <div class="head well">
        <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
            </div>
        </h3>
    </div>	
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tr>
                    <td> <?php echo $entry_key; ?><span class="required">*</span></td>
                    <td><input class="form-control"  type="text" name="dhl_key" value="<?php echo $dhl_key; ?>" />
                        <?php if ($error_key) { ?>
                            <span class="error"><?php echo $error_key; ?></span>
                        <?php } ?></td>
                </tr>
                <tr>
                 <td><?php echo $entry_password; ?><span class="required">*</span></td>
                 <td><input class="form-control" type="text" name="dhl_password" value="<?php echo $dhl_password; ?>" />
                        <?php if ($error_password) { ?>
                            <span class="error"><?php echo $error_password; ?></span>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_shipper_account_number; ?></td>
                    <td><input class="form-control" type="text" name="dhl_shipper_account_number" value="<?php echo $dhl_shipper_account_number; ?>" />
                        <?php if ($error_shipper_account_number) { ?>
                            <span class="error"><?php echo $error_shipper_account_number; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_packaging; ?></td>
                    <td><div class="ui-select">
                        <select name="dhl_packaging">
                            <?php foreach ($packages as $package) { ?>
                                <?php if ($package['value'] == $dhl_packaging) { ?>
                                    <option value="<?php echo $package['value']; ?>" selected="selected"><?php echo $package['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $package['value']; ?>"><?php echo $package['text']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select></div>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_payment_type; ?></td>
                    <td><div class="ui-select">
                        <select name="dhl_payment_type">
                            <?php foreach ($payments as $payment) { ?>
                                <?php if ($payment['value'] == $dhl_payment_type) { ?>
                                    <option value="<?php echo $payment['value']; ?>" selected="selected"><?php echo $payment['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $payment['value']; ?>"><?php echo $payment['text']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select></div>
                    </td>
                </tr>
                <tr>
             <td><?php echo $entry_product_code; ?><span class="required">*</span></td>
                    <td><div class="ui-select">
                        <select name="dhl_product_code">
                            <?php foreach ($product_codes as $code) { ?>
                                <?php if ($code['value'] == $dhl_product_code) { ?>
                                    <option value="<?php echo $code['value']; ?>" selected="selected"><?php echo $code['text']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $code['value']; ?>"><?php echo $code['text']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select></div>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_city; ?></td>
                    <td><input class="form-control" type="text" name="dhl_city" value="<?php echo $dhl_city; ?>" />
                        <?php if ($error_city) { ?>
                            <span class="error"><?php echo $error_city; ?></span>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_state; ?></td>
                    <td><input class="form-control" type="text" name="dhl_state" value="<?php echo $dhl_state; ?>" /></td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_country; ?></td>
                    <td><input class="form-control" type="text" name="dhl_country" value="<?php echo $dhl_country; ?>" maxlength="2" />
                        <?php if ($error_country) { ?>
                            <span class="error"><?php echo $error_country; ?></span>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_postcode; ?></td>
                    <td><input class="form-control" type="text" name="dhl_postcode" value="<?php echo $dhl_postcode; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_test; ?></td>
                    <td><?php if ($dhl_test) { ?>
                            <input type="radio" name="dhl_test" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <input type="radio" name="dhl_test" value="0" />
                            <?php echo $text_no; ?>
                        <?php } else { ?>
                            <input type="radio" name="dhl_test" value="1" />
                            <?php echo $text_yes; ?>
                            <input type="radio" name="dhl_test" value="0" checked="checked" />
                            <?php echo $text_no; ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_weight_class; ?></td>
                    <td><div class="ui-select">
                        <select name="dhl_weight_class">
                            <?php foreach ($weight_classes as $weight_class) { ?>
                                <?php if ($weight_class['unit'] == $dhl_weight_class) { ?>
                                    <option value="<?php echo $weight_class['unit']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $weight_class['unit']; ?>"><?php echo $weight_class['title']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select></div>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_tax; ?></td>
                    <td><div class="ui-select">
                        <select name="dhl_tax_class_id">
                            <option value="0"><?php echo $text_none; ?></option>
                            <?php foreach ($tax_classes as $tax_class) { ?>
                                <?php if ($tax_class['tax_class_id'] == $dhl_tax_class_id) { ?>
                                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select></div>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_geo_zone; ?></td>
                    <td><div class="ui-select">
                        <select name="dhl_geo_zone_id">
                            <option value="0"><?php echo $text_all_zones; ?></option>
                            <?php foreach ($geo_zones as $geo_zone) { ?>
                                <?php if ($geo_zone['geo_zone_id'] == $dhl_geo_zone_id) { ?>
                                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select></div>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td><div class="ui-select">
                        <select name="dhl_status">
                            <?php if ($dhl_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></div>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_sort_order; ?></td>
                    <td><input type="text" name="dhl_sort_order" value="<?php echo $dhl_sort_order; ?>" size="1" /></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript"><!--
    $('select[name=\'dhl_origin\']').bind('change', function() {
        $('#service > div').hide();	
										 
        $('#' + this.value).show();	
    });

    $('select[name=\'dhl_origin\']').trigger('change');
    //--></script>
