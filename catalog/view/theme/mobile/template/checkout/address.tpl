<?php echo $header; ?> <?php echo $left_bar; ?>

<div id="content" class="shadowbox checkout">
    <div class="box">
        <h1 class="top">
            <span><?php echo $heading_title; ?></span>
        </h1>
	<?php if ($error_warning) { ?>
    	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($error_shipping || $error_payment) { ?>
    	<div class="warning"><?php echo __('Oops! Error found in ' . ($error_shipping ? 'Shipping' : 'Payment') . ' address. Kindly check below in <span class="red">RED</span>'); ?></div>
	<?php } ?>
        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="checkout_address">
            <div class="" id="shipping_address_box">

		<?php if ($shipping_addresses) : ?>
    		<div class="right width-45">
    		    <h4><?php echo $text_entries; ?></h4>
    		    <div class="content">
    			<table cellpadding="3">
				<?php foreach ($shipping_addresses as $address) { ?>
				    <tr id="shipping_row_<?php echo $address['address_id']; ?>" class="shipping_row">
					<td width="1">
					    <input type="radio" name="shipping[address_id]" value="<?php echo $address['address_id']; ?>"
						   id="shipping_address_<?php echo $address['address_id']; ?>"
						   class="shipping_address_list"
						   style="margin: 0px;" />
					</td>
					<td><label for="shipping_address_<?php echo $address['address_id']; ?>" style="cursor: pointer;"><?php echo $address['address']; ?></label></td>
				    </tr>
				<?php } ?>
    			</table>
    			<a id="shipping_new" class="left hide"><?php echo $text_new_address; ?></a>
    			<div class="clear"></div>
    		    </div>
    		</div>
		<?php endif; ?>
                <div class="left width-50">
                    <h2><?php echo __('text_shipping_address') ?></h2>
                    <div class="content">
                        <table class="width-100">
                            <tr>
                                <td width="100%">
                                    <span class="required">*</span> <?php echo $entry_firstname; ?><br/>
                                    <input type="text" name="shipping[firstname]" id="shipping_firstname" value="<?php echo $shipping_address['firstname']; ?>"  />
				    <?php if (isset($error_shipping['firstname']) && $error_shipping['firstname']) { ?>
    				    <span class="error"><?php echo $error_shipping['firstname']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="required">*</span> <?php echo $entry_lastname; ?>  <br/>
                                    <input type="text" name="shipping[lastname]" id="shipping_lastname" value="<?php echo $shipping_address['lastname']; ?>"  />
				    <?php if (isset($error_shipping['lastname']) && $error_shipping['lastname']) { ?>
    				    <span class="error"><?php echo $error_shipping['lastname']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
				    <?php echo 'Shipping Phone:' ?>  <br/>
                                    <input type="text" name="shipping[company]" id="shipping_company" value="<?php echo $shipping_address['company']; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="required">*</span> <?php echo $entry_address_1; ?> <span class="small gray">&nbsp; Eg: 123 Main Street</span><br/>
                                    <input type="text" name="shipping[address_1]" id="shipping_address_1" value="<?php echo $shipping_address['address_1']; ?>" class="width-100" />

				    <?php if (isset($error_shipping['address_1']) && $error_shipping['address_1']) { ?>
    				    <span class="error"><?php echo $error_shipping['address_1']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_address_2; ?> <span class="small gray">(Optional) Eg: Suite 123 or Apt 123</span></br>
                                    <input type="text" name="shipping[address_2]" id="shipping_address_2" value="<?php echo $shipping_address['address_2']; ?>" class="width-100" />

                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_city; ?><br/>
                                    <input type="text" name="shipping[city]" id="shipping_city" value="<?php echo $shipping_address['city']; ?>"  />
				    <?php if (isset($error_shipping['city']) && $error_shipping['city']) { ?>
    				    <span class="error"><?php echo $error_shipping['city']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_postcode; ?></br>
                                    <input type="text" name="shipping[postcode]" id="shipping_postcode" value="<?php echo $shipping_address['postcode']; ?>" />
				    <?php if (isset($error_shipping['postcode']) && $error_shipping['postcode']) { ?>
    				    <span class="error"><?php echo $error_shipping['postcode']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_country; ?><br />
                                    <select name="shipping[country_id]"  id="shipping_country_id" onchange="loadZone('shipping_zone_id', this.value, '<?php echo $shipping_address['zone_id']; ?>');">
					<?php if (!$country_id): ?>
    					<option value=""><?php echo $text_select; ?></option>
					    <?php foreach ($countries as $country) : ?>
						<option value="<?php echo $country['country_id']; ?>" <?php if ($country['country_id'] == $shipping_address['country_id']) : ?>selected="selected"<?php endif; ?>><?php echo $country['name']; ?></option>
					    <?php endforeach; ?>
					<?php else: ?>
					    <?php
					    foreach ($countries as $country) {
						if ($country['country_id'] == $country_id) {
						    ?>
	    					<option value="<?php echo $country['country_id']; ?>"  selected="selected"><?php echo $country['name']; ?></option>
						    <?php
						}
					    }//endfor;
					endif;
					?>
                                    </select>
				    <?php if (isset($error_shipping['country_id']) && $error_shipping['country_id']) { ?>
    				    <span class="error"><?php echo $error_shipping['country_id']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_zone; ?></br>
                                    <select name="shipping[zone_id]" id="shipping_zone_id" >
                                    </select>
				    <?php if (isset($error_shipping['zone_id']) && $error_shipping['zone_id']) { ?>
    				    <span class="error"><?php echo $error_shipping['zone_id']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <hr  class="clear" />
            <div class="left width-50">
                <h2><?php echo __('text_payment_address') ?></h2>
            </div>
            <div class="check-shipping-payment right width-45">
                <label for="payment_check">
                    <input type="checkbox" name="same_payment" id="payment_check" <?php if (!$error_payment && (!$payment_address_id || ($payment_address_id == $shipping_address_id))): ?>checked="checked"<?php endif; ?> value="1" /> <?php echo __('entry_payment'); ?>
                </label>
            </div>
            <div class="clear"></div>
            <div id="payment_address_box">

		<?php if ($addresses) : ?>
    		<div class="right width-45">
    		    <h4><?php echo $text_entries; ?></h4>
    		    <div class="content">
    			<table cellpadding="3">
				<?php foreach ($addresses as $address) { ?>
				    <tr id="payment_row_<?php echo $address['address_id']; ?>" class="payment_row">
					<td width="1">
					    <input type="radio" 
						   name="payment[address_id]" 
						   value="<?php echo $address['address_id']; ?>" 
						   id="payment_address_<?php echo $address['address_id']; ?>"
						   class="payment_address_list"
						   style="margin: 0px;" />
					</td>
					<td><label for="payment_address_<?php echo $address['address_id']; ?>" style="cursor: pointer;"><?php echo $address['address']; ?></label></td>
				    </tr>
				<?php } ?>
    			</table>
    			<a id="payment_new" class="left hide"><?php echo $text_new_address; ?></a>
    			<div class="clear"></div>
    		    </div>
    		</div>
		<?php endif; ?>
                <div class="left width-50">
                    <div class="content">
                        <table class="width-100">
                            <tr>
                                <td width="150"><span class="required">*</span> <?php echo $entry_firstname; ?></br>
                                    <input type="text" name="payment[firstname]" id="payment_firstname" value="<?php echo $payment_address['firstname']; ?>"  />
				    <?php if (isset($error_payment['firstname']) && $error_payment['firstname']) { ?>
    				    <span class="error"><?php echo $error_payment['firstname']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_lastname; ?></br>
                                    <input type="text" name="payment[lastname]" id="payment_lastname" value="<?php echo $payment_address['lastname']; ?>"  />
				    <?php if (isset($error_payment['lastname']) && $error_payment['lastname']) { ?>
    				    <span class="error"><?php echo $error_payment['lastname']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo "Billing Phone:"; ?></br>
                                    <input type="text" name="payment[company]" id="payment_company" value="<?php echo $payment_address['company']; ?>" /></td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_address_1; ?> <span class="small gray">&nbsp; Eg: 123 Main Street</span><br/>
                                    <input type="text" name="payment[address_1]" id="payment_address_1" value="<?php echo $payment_address['address_1']; ?>"  class="width-100" />
				    <?php if (isset($error_payment['address_1']) && $error_payment['address_1']) { ?>
    				    <span class="error"><?php echo $error_payment['address_1']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_address_2; ?> <span class="small gray">(Optional) Eg: Suite 123 or Apt 123</span></br>
                                    <input type="text" name="payment[address_2]" id="payment_address_2" value="<?php echo $shipping_address['address_2']; ?>" class="width-100" /></td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_city; ?></br>
                                    <input type="text" name="payment[city]" id="payment_city" value="<?php echo $payment_address['city']; ?>"  />
				    <?php if (isset($error_payment['city']) && $error_payment['city']) { ?>
    				    <span class="error"><?php echo $error_payment['city']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_postcode; ?></br>
                                    <input type="text" name="payment[postcode]" id="payment_postcode" value="<?php echo $payment_address['postcode']; ?>" />
				    <?php if (isset($error_payment['postcode']) && $error_payment['postcode']) { ?>
    				    <span class="error"><?php echo $error_payment['postcode']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_country; ?></br>

                                    <select name="payment[country_id]"  id="payment_country_id" onchange="loadZone('payment_zone_id', this.value, '<?php echo $payment_address['zone_id']; ?>');">
                                        <option value=""><?php echo $text_select; ?></option>
					<?php foreach ($countries as $country) : ?>
    					<option value="<?php echo $country['country_id']; ?>" <?php if ($country['country_id'] == $payment_address['country_id']) : ?>selected="selected"<?php endif; ?>><?php echo $country['name']; ?></option>
					<?php endforeach; ?>
                                    </select>
				    <?php if (isset($error_payment['country_id']) && $error_payment['country_id']) { ?>
    				    <span class="error"><?php echo $error_payment['country_id']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_zone; ?></br>

                                    <select name="payment[zone_id]"  id="payment_zone_id">
                                    </select>
				    <?php if (isset($error_payment['zone_id']) && $error_payment['zone_id']) { ?>
    				    <span class="error"><?php echo $error_payment['zone_id']; ?></span>
				    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
	    <?php if ($payment_methods) : ?>
                <hr  class="clear" />	    
    	    <h2><?php echo __('text_payment_method'); ?></h2>
    	    <div class="content">
    		<p><?php echo __('text_payment_methods'); ?></p>
    		<table cellpadding="3">
			<?php foreach ($payment_methods as $payment_method) : ?>
			    <tr>
				<td width="1" valign="top"><input type="radio" name="payment_method" value="<?php echo $payment_method['id']; ?>" id="<?php echo $payment_method['id']; ?>" <?php if ($payment_method['id'] == $payment): ?>checked="checked"<?php endif; ?> /></td>
				<td class="payments <?php echo $payment_method['id']; ?>"><b><?php echo $payment_method['title']; ?></b></td>
			    </tr>
			    <tr>
				<td colspan="3"><div class="error"><?php echo $payment_method['error']; ?></div></td>
			    </tr>
			<?php endforeach; ?>
    		</table>
    	    </div>
	    <?php endif; ?>
            <hr  class="clear" />
            <h4><?php echo __('text_comments'); ?></h4>
            <textarea id="comment" name="comment" rows="8"><?php echo $comment; ?></textarea>
            <div class="buttons">
                <table>
                    <tr>
                        <td align="left"><a href="<?php echo $back; ?>" class="button"><span><?php echo __('Back'); ?></span></a></td>
                        <td align="right">
                            <span style="line-height: 26px;">
				<?php if ($text_agree) { ?>
				    <?php echo $text_agree; ?> &nbsp;&nbsp;
    				<input type="hidden" name="agree" value="1" />
				<?php } ?>
                            </span>
                            <a onclick="validateForm()" class="button"><span><?php echo $button_continue; ?></span></a>

                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript"><!--
    var shipping_address_id = '<?php echo $shipping_address_id ?>';
    var payment_address_id = '<?php echo $payment_address_id ?>';
    var bPaymentAddress = <?php echo empty($payment_address) ? 0 : 1 ?>;
    var bShippingAddress = <?php echo empty($payment_address) ? 0 : 1 ?>;
    var error_payment_count = <?php echo count($error_payment) ?>;
    var error_shipment_count = <?php echo count($error_shipment) ?>;

    $(document).ready(function() {
	if (!error_shipment_count)
	    $('.shipping_address_list[value=' + shipping_address_id + ']').attr('checked', true).trigger('click');
	else
	    $('.shipping_address_list[value=' + shipping_address_id + ']').attr('checked', true);
	if (!error_payment_count) {
	    if (!payment_address_id || (payment_address_id == shipping_address_id)) {
		$('#payment_address_box').hide();
	    } else {
		$('.payment_address_list[value=' + payment_address_id + ']').attr('checked', true).trigger('click');
	    }
	} else {
	    $('.payment_address_list[value=' + payment_address_id + ']').attr('checked', true);
	}
    });
    $('#payment_check').click(function() {
	if ($(this).is(':checked') == true) {
	    $('#payment_address_box').hide();
	} else {
	    $('#payment_address_box').show();
	    $('#payment_address_box input[type=text]').val('');
	    $('#payment_address_box select').val('');
	    $('#payment_address_box input[type=radio]').attr('checked', false);
	}
    });
    $('#payment_new').live('click', function() {
	$('.payment_address_list').attr('checked', false);
	$('#payment_address_box input[type=text]').val('');
	$('#payment_address_box select').val('');
    });
    $('#shipping_new').live('click', function() {
	$('.shipping_address_list').attr('checked', false);
	$('.payment_row').show();
	if ($('#payment_address_box .right').css('display') == 'none') {
	    $('#payment_address_box .right').show();
	}
	$('#shipping_address_box input[type=text]').val('');
	$('#shipping_address_box select').val('');
    });

    $('.shipping_address_list').live('click', function() {
	getAddress($(this), 'shipping');
	$('#shipping_new').show();
	$('.payment_row').show();
	$('#payment_row_' + $(this).val()).hide();
	if ($('.payment_address_list').length == 1) {
	    $('#payment_address_box .right').hide();
	}
	if ($('.payment_address_list:checked').val() == $(this).val())
	    $('#payment_new').trigger('click');

    });
    $('.payment_address_list').live('click', function() {
	$('#payment_new').show();
	getAddress($(this), 'payment');
    });

    $('#comment').blur(function() {
	if ($(this).val() != "") {
	    $.ajax({
		type: 'post',
		url: 'index.php?route=checkout/address/comment',
		dataType: 'json',
		data: 'comment=' + $(this).val(),
		success: function(html) {
		}
	    });
	}
    });

    function getAddress(obj, type) {
	$.ajax({
	    type: 'get',
	    url: 'index.php?route=checkout/address/getAddress',
	    dataType: 'json',
	    data: 'address_id=' + obj.val(),
	    success: function(res) {
		if (res.error == undefined) {
		    $.each(res, function(i, val) {
			if ($('#' + type + '_' + i).is('input'))
			    $('#' + type + '_' + i).val(val);
			else if ($('#' + type + '_' + i).is('select')) {
			    $('#' + type + '_' + i + ' option[value=' + val + ']').attr('selected', true);
			    if (i == 'country_id') {
				loadZone(type + '_zone_id', val, res.zone_id);
			    }
			}
		    });
		} else {
		    alert(res.error);
		}
	    }
	});
    }
    function validateForm() {
	$('div.error,span.error').remove();
	var obj = {
	    shipping_firstname: {required: true},
	    shipping_lastname: {required: true},
	    shipping_address_1: {required: true},
	    shipping_city: {required: true},
	    shipping_country_id: {required: true},
	    shipping_postcode: {
		required: $('#shipping_country_id').val() == 223
	    },
	    shipping_zone_id: {required: true}
	};

	if ($('#payment_check').is(':checked') == false) {
	    var payment_obj = {
		payment_firstname: {required: true},
		payment_lastname: {required: true},
		payment_address_1: {required: true},
		payment_city: {required: true},
		payment_country_id: {required: true},
		payment_postcode: {
		    required: $('#payment_country_id').val() == 223
		},
		payment_zone_id: {required: true}
	    }
	    for (var attrname in payment_obj) {
		obj[attrname] = payment_obj[attrname];
	    }
	}
	var bValid = true;
	$.each(obj, function(attr, val) {
	    if ($.trim($('#' + attr).val()) == '' && val.required == true) {
		$('#' + attr).after('<div class="error">This field is required.</div>');
		bValid = false;
	    }
	});
	if (bValid) {
	    $('#checkout_address').submit();
	    return true;
	}
	return false;
    }

    function loadZone(id, country_id, zone_id) {
	$('#' + id).load('index.php?route=checkout/address/zone&country_id=' + country_id + '&zone_id=' + zone_id);
    }

    loadZone('shipping_zone_id', '<?php echo $shipping_address['country_id']; ?>', '<?php echo $shipping_address['zone_id']; ?>');
    loadZone('payment_zone_id', '<?php echo $payment_address['country_id']; ?>', '<?php echo $payment_address['zone_id']; ?>');
//--></script>
<?php echo $footer; ?>
