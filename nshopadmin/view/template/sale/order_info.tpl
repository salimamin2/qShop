<div class="box">
    <div class="head well">
		<h3><i class="icon-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
	    		<a href="<?php echo $invoice; ?>" target="_blank" class="btn-flat btn-primary btn-sm"><span><?php echo $button_invoice; ?></span></a> <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
			</div>
		</h3>
	</div>

    <div class="content">

	<div class="tabbable">

        <ul class="nav nav-tabs">

            <li class="active"><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>

            <li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>

            <?php if ($shipping_method) { ?>

                <li><a href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>

            <?php } ?>

            <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>

            <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>

        </ul>

	</div>

    <div class="tab-content">

	<div id="tab-order" class="vtabs_page tab-pane active">

	    <table class="form">

		<tr>

		    <td><?php echo $text_order_id; ?></td>

		    <td>#<?php echo $order_id; ?></td>

		</tr>

		<tr>

		    <td><?php echo $text_invoice_no; ?></td>

		    <td><?php if ($invoice_no) { ?>

			    <?php echo $invoice_no; ?>

			<?php } else { ?>

    			<span id="invoice"><b>[</b> <a id="invoice-generate"><?php echo $text_generate; ?></a> <b>]</b></span>

			<?php } ?></td>

		</tr>

		<tr>

		    <td><?php echo $text_store_name; ?></td>

		    <td><?php echo $store_name; ?></td>

		</tr>

		<tr>

		    <td><?php echo $text_store_url; ?></td>

		    <td><a href="<?php echo $store_url; ?>" target="_blank"><u><?php echo $store_url; ?></u></a></td>

		</tr>

		<?php if ($customer) { ?>

    		<tr>

    		    <td><?php echo $text_customer; ?></td>

    		    <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>

    		</tr>

		<?php } else { ?>

    		<tr>

    		    <td><?php echo $text_customer; ?></td>

    		    <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>

    		</tr>

		<?php } ?>

		<?php if ($customer_group) { ?>

    		<tr>

    		    <td><?php echo $text_customer_group; ?></td>

    		    <td><?php echo $customer_group; ?></td>

    		</tr>

		<?php } ?>

		<tr>

		    <td><?php echo $text_email; ?></td>

		    <td><?php echo $email; ?></td>

		</tr>

		<tr>

		    <td><?php echo $text_telephone; ?></td>

		    <td><?php echo $telephone; ?></td>

		</tr>

		<?php if ($fax) { ?>

    		<tr>

    		    <td><?php echo $text_fax; ?></td>

    		    <td><?php echo $fax; ?></td>

    		</tr>

		<?php } ?>

		<tr>

		    <td><?php echo $text_total; ?></td>

		    <td><?php echo $total; ?></td>

		</tr>

		<?php if ($reward && $customer) { ?>

    		<tr>

    		    <td><?php echo $text_reward; ?></td>

    		    <td><?php echo $reward; ?>

			    <?php if (!$reward_total) { ?>

				<!--              <?php /* <span id="reward"><b>[</b> <a id="reward-add"><?php echo $text_reward_add; ?></a> <b>]</b></span> */ ?> -->

			    <?php } else { ?>

				<span id="reward"><b>[</b> <a id="reward-remove"><?php echo $text_reward_remove; ?></a> <b>]</b></span>

			    <?php } ?></td>

    		</tr>

		<?php } ?>

		<?php if ($order_status) { ?>

    		<tr>

    		    <td><?php echo $text_order_status; ?></td>

    		    <td id="order-status"><?php echo $order_status; ?></td>

    		</tr>

		<?php } ?>

		<?php if ($comment) { ?>

    		<tr>

    		    <td><?php echo $text_comment; ?></td>

    		    <td><?php echo $comment; ?></td>

    		</tr>

		<?php } ?>

		<?php if ($ip) { ?>

    		<tr>

    		    <td><?php echo $text_ip; ?></td>

    		    <td><?php echo $ip; ?></td>

    		</tr>

		<?php } ?>

		<?php if ($forwarded_ip) { ?>

    		<tr>

    		    <td><?php echo $text_forwarded_ip; ?></td>

    		    <td><?php echo $forwarded_ip; ?></td>

    		</tr>

		<?php } ?>

		<?php if ($user_agent) { ?>

    		<tr>

    		    <td><?php echo $text_user_agent; ?></td>

    		    <td><?php echo $user_agent; ?></td>

    		</tr>

		<?php } ?>

		<?php if (isset($accept_language) && $accept_language) { ?>

    		<tr>

    		    <td><?php echo $text_accept_language; ?></td>

    		    <td><?php echo $accept_language; ?></td>

    		</tr>

		<?php } ?>

		<tr>

		    <td><?php echo $text_date_added; ?></td>

		    <td><?php echo $date_added; ?></td>

		</tr>

		<tr>

		    <td><?php echo $text_date_modified; ?></td>

		    <td><?php echo $date_modified; ?></td>

		</tr>

	    </table>

	</div>

	<div id="tab-payment" class="vtabs_page tab-pane">

	    <table class="form">

		<tr>

		    <td><?php echo $text_firstname; ?></td>

		    <td><?php echo $payment_firstname; ?></td>

		</tr>

		<tr>

		    <td><?php echo $text_lastname; ?></td>

		    <td><?php echo $payment_lastname; ?></td>

		</tr>

		<?php if ($payment_company) { ?>

    		<tr>

    		    <td><?php echo $text_telephone; ?></td>

    		    <td><?php echo $payment_company; ?></td>

    		</tr>

		<?php } ?>

		<?php if ($payment_company_id) { ?>

    		<tr>

    		    <td><?php echo $text_company_id; ?></td>

    		    <td><?php echo $payment_company_id; ?></td>

    		</tr>

		<?php } ?>          

		<?php if ($payment_tax_id) { ?>

    		<tr>

    		    <td><?php echo $text_tax_id; ?></td>

    		    <td><?php echo $payment_tax_id; ?></td>

    		</tr>

		<?php } ?>            

		<tr>

		    <td><?php echo $text_address_1; ?></td>

		    <td><?php echo $payment_address_1; ?></td>

		</tr>

		<?php if ($payment_address_2) { ?>

    		<tr>

    		    <td><?php echo $text_address_2; ?></td>

    		    <td><?php echo $payment_address_2; ?></td>

    		</tr>

		<?php } ?>

		<tr>

		    <td><?php echo $text_city; ?></td>

		    <td><?php echo $payment_city; ?></td>

		</tr>

		<?php if ($payment_postcode) { ?>

    		<tr>

    		    <td><?php echo $text_postcode; ?></td>

    		    <td><?php echo $payment_postcode; ?></td>

    		</tr>

		<?php } ?>

		<tr>

		    <td><?php echo $text_zone; ?></td>

		    <td><?php echo $payment_zone; ?></td>

		</tr>

		<?php if ($payment_zone_code) { ?>

    		<tr>

    		    <td><?php echo $text_zone_code; ?></td>

    		    <td><?php echo $payment_zone_code; ?></td>

    		</tr>

		<?php } ?>

		<tr>

		    <td><?php echo $text_country; ?></td>

		    <td><?php echo $payment_country; ?></td>

		</tr>

		<tr>

		    <td><?php echo $text_payment_method; ?></td>

		    <td><?php echo $payment_method; ?></td>

		</tr>

	    </table>

	</div>

	<?php if ($shipping_method) { ?>

    	<div id="tab-shipping" class="vtabs_page tab-pane">

    	    <table class="form">

    		<tr>

    		    <td><?php echo $text_firstname; ?></td>

    		    <td><?php echo $shipping_firstname; ?></td>

    		</tr>

    		<tr>

    		    <td><?php echo $text_lastname; ?></td>

    		    <td><?php echo $shipping_lastname; ?></td>

    		</tr>

		    <?php if ($shipping_company) { ?>

			<tr>

			    <td><?php echo $text_telephone; ?></td>

			    <td><?php echo $shipping_company; ?></td>

			</tr>

		    <?php } ?>

    		<tr>

    		    <td><?php echo $text_address_1; ?></td>

    		    <td><?php echo $shipping_address_1; ?></td>

    		</tr>

		    <?php if ($shipping_address_2) { ?>

			<tr>

			    <td><?php echo $text_address_2; ?></td>

			    <td><?php echo $shipping_address_2; ?></td>

			</tr>

		    <?php } ?>

    		<tr>

    		    <td><?php echo $text_city; ?></td>

    		    <td><?php echo $shipping_city; ?></td>

    		</tr>

		    <?php if ($shipping_postcode) { ?>

			<tr>

			    <td><?php echo $text_postcode; ?></td>

			    <td><?php echo $shipping_postcode; ?></td>

			</tr>

		    <?php } ?>

    		<tr>

    		    <td><?php echo $text_zone; ?></td>

    		    <td><?php echo $shipping_zone; ?></td>

    		</tr>

		    <?php if ($shipping_zone_code) { ?>

			<tr>

			    <td><?php echo $text_zone_code; ?></td>

			    <td><?php echo $shipping_zone_code; ?></td>

			</tr>

		    <?php } ?>

    		<tr>

    		    <td><?php echo $text_country; ?></td>

    		    <td><?php echo $shipping_country; ?></td>

    		</tr>

		    <?php if ($shipping_method) { ?>

			<tr>

			    <td><?php echo $text_shipping_method; ?></td>

			    <td><?php echo $shipping_method; ?></td>

			</tr>

		    <?php } ?>

    	    </table>

    	</div>

	<?php } ?>

	<div id="tab-product" class="tab-pane">

        <br/>

        <div class="table-responsive">

	    <table class="table table-hover">

		<thead>

		    <tr>

			<th class="left"><span class="line"></span>
			<?php echo $column_image; ?></th>

			<th class="left"><span class="line"></span>
			<?php echo $column_product; ?></th>

			<th class="left"><span class="line"></span>
			<?php echo $column_model; ?></th>

			<th class="left"><span class="line"></span>
			<?php echo $column_quantity; ?></th>

			<th class="left"><span class="line"></span>
			<?php echo $column_price; ?></th>

			<th class="left"><span class="line"></span>
			<?php echo $column_total; ?></th>

		    </tr>

		</thead>

		<tbody>

		    <?php foreach ($products as $product) { ?>

    		    <tr>

    			<td align="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></td>

    			<td class="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>

				<?php foreach ($product['detail'] as $option) { ?>

				    <br />

				    &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>

				<?php } ?>

				<?php foreach ($product['option'] as $option) { ?>

				    <br />

				    <?php if ($option['type'] != 'file') { ?>

	    			    &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>

				    <?php } else { ?>

	    			    &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>

				    <?php } ?>

				    <?php } ?></td>

    			<td class="left"><?php echo $product['model']; ?></td>

    			<td class="right"><?php echo $product['quantity']; ?></td>

    			<td class="right"><?php echo $product['price']; ?></td>

    			<td class="right"><?php echo $product['total']; ?></td>

    		    </tr>

		    <?php } ?>
		    <?php if(isset($vouchers) && $vouchers){ ?>
		    <?php foreach ($vouchers as $voucher) { ?>

    		    <tr>

    			<td class="left" colspan="2"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>

    			<td class="left"></td>

    			<td class="right">1</td>

    			<td class="right"><?php echo $voucher['amount']; ?></td>

    			<td class="right"><?php echo $voucher['amount']; ?></td>

    		    </tr>

		    <?php } ?>
		    <?php } ?>

		</tbody>

		<?php foreach ($totals as $totals) { ?>

    		<tbody id="totals">

    		    <tr>

    			<td colspan="5" class="right" style="text-align:right"><?php echo $totals['title']; ?></td>

    			<td class="right" style="text-align:right"><?php echo $totals['text']; ?></td>

    		    </tr>

    		</tbody>

		<?php } ?>

	    </table>

        </div>

	    <?php if ($downloads) { ?>

    	    <h3><?php echo $text_download; ?></h3>

    	    <table class="list">

    		<thead>

    		    <tr>

    			<td class="left"><b><?php echo $column_download; ?></b></td>

    			<td class="left"><b><?php echo $column_filename; ?></b></td>

    			<td class="right"><b><?php echo $column_remaining; ?></b></td>

    		    </tr>

    		</thead>

    		<tbody>

			<?php foreach ($downloads as $download) { ?>

			    <tr>

				<td class="left"><?php echo $download['name']; ?></td>

				<td class="left"><?php echo $download['filename']; ?></td>

				<td class="right"><?php echo $download['remaining']; ?></td>

			    </tr>

			<?php } ?>

    		</tbody>

    	    </table>

	    <?php } ?>

	</div>

	<div id="tab-history" class="vtabs_page tab-pane">

        <br/>

	    <div id="history"><?php echo $this->load('sale/order/history'); ?></div>

        <hr/>

	    <table class="form">

		<tr>

		    <td><?php echo $entry_order_status; ?></td>

		    <td colspan="3"><div class="ui-select"><select name="order_status_id">

			    <?php foreach ($order_statuses as $order_statuses) { ?>

				<?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>

				    <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>

				<?php } else { ?>

				    <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>

				<?php } ?>

			    <?php } ?>

			</select></div></td>

		</tr>

		<tr>

		    <td><?php echo $entry_notify; ?></td>

		    <td width="200"><input type="checkbox" name="notify" value="1" /></td>

		    <td width="150"><?php echo $entry_notify_manufacturer; ?></td>

		    <td><input type="checkbox" name="notify_manufacturer" value="1" /></td>

		</tr>

		<tr>

		    <td><?php echo $entry_comment; ?></td>

		    <td colspan="3"><textarea name="comment" cols="40" rows="8" style="width: 99%"></textarea>

			<div style="margin-top: 10px; text-align: right;">

			    <a id="button-history" class="btn-flat btn-success btn-sm"><span><?php echo $button_add_history; ?></span></a>

			</div>

		    </td>

		</tr>

	    </table>

	</div>

    </div>

    </div>

</div>

<script type="text/javascript"><!--

$('#invoice-generate').live('click', function() {

	$.ajax({

	    url: 'sale/order/generate&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',

	    dataType: 'json',

	    beforeSend: function() {

		$('#invoice').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');

	    },

	    complete: function() {

		$('.loading').remove();

	    },

	    success: function(json) {

		$('.success, .warning').remove();



		if (json['error']) {

		    $('#tab-order').prepend('<div class="alert alert-warning warning" style="display: none;">' + json['error'] + '</div>');



		    $('.warning').fadeIn('slow');

		}



		if (json.invoice_no) {

		    $('#invoice').fadeOut('slow', function() {

			$('#invoice').html(json['invoice_no']);



			$('#invoice').fadeIn('slow');

		    });

		}

	    }

	});

    });



    $('#reward-add').live('click', function() {

	$.ajax({

	    url: 'sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',

	    type: 'post',

	    dataType: 'json',

	    beforeSend: function() {

		$('#reward').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');

	    },

	    complete: function() {

		$('.loading').remove();

	    },

	    success: function(json) {

		$('.success, .warning').remove();



		if (json['error']) {

		    $('.box').before('<div class="alert alert-warning warning" style="display: none;">' + json['error'] + '</div>');



		    $('.warning').fadeIn('slow');

		}



		if (json['success']) {

		    $('.box').before('<div class="alert alert-success success" style="display: none;">' + json['success'] + '</div>');



		    $('.success').fadeIn('slow');



		    $('#reward').html('<b>[</b> <a id="reward-remove"><?php echo $text_reward_remove; ?></a> <b>]</b>');

		}

	    }

	});

    });



    $('#reward-remove').live('click', function() {

	$.ajax({

	    url: 'sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',

	    type: 'post',

	    dataType: 'json',

	    beforeSend: function() {

		$('#reward').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');

	    },

	    complete: function() {

		$('.loading').remove();

	    },

	    success: function(json) {

		$('.success, .warning').remove();



		if (json['error']) {

		    $('.box').before('<div class="alert alert-warning warning" style="display: none;">' + json['error'] + '</div>');



		    $('.warning').fadeIn('slow');

		}



		if (json['success']) {

		    $('.box').before('<div class="alert alert-success success" style="display: none;">' + json['success'] + '</div>');



		    $('.success').fadeIn('slow');



		    $('#reward').html('<b>[</b> <a id="reward-add"><?php echo $text_reward_add; ?></a> <b>]</b>');

		}

	    }

	});

    });





    $('#button-history').on('click', function() {

	$.ajax({

	    url: 'sale/order/history&order_id=<?php echo $order_id; ?>&no-layout=1',

	    type: 'post',

	    dataType: 'html',

	    data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) +  '&notify_manufacturer=' + encodeURIComponent($('input[name=\'notify_manufacturer\']').attr('checked') ? 1 : 0) + '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),

	    beforeSend: function() {

		$('.success, .warning').remove();

		$('#button-history').attr('disabled', true);

		$('#history').prepend('<div class="alert alert-warning attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');

	    },

	    complete: function() {

		$('#button-history').attr('disabled', false);

		$('.attention').remove();

	    },

	    success: function(html) {

		    $('#history').html(html);

            $('table#history_table').dataTable();



		$('textarea[name=\'comment\']').val('');



		$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());

	    }

	});

    });

//--></script>

