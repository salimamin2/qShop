<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">

        <button href="<?php echo $action ?>" class="btn-flat btn-primary btn-sm"><span><?php echo $button_export; ?></span></a>
			</div>
    </h3>
	</div>
  <div class="content">

    <div style="background: #E7EFEF; border: 1px solid #C6D7D7; padding: 3px; margin-bottom: 15px;">

      <table>

        <tr>

          <td><?php echo $entry_date_start; ?><br />

            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="12" style="margin-top: 1px;" data-provide="datepicker-inline" /></td>

          <td><?php echo $entry_date_end; ?><br />

            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="12" style="margin-top: 1px;" data-provide="datepicker-inline" /></td>

          <td><?php echo $entry_customer; ?><br />
           <div class="ui-select">
            <select name="filter_customer" style="margin-top: 1px;">

                 <option value=""><?php echo $text_select_customer; ?></option>

              <?php foreach ($customers as $customer) { ?>

              <?php if ($customer['value'] == $filter_customer) { ?>

              <option value="<?php echo $customer['value']; ?>" selected="selected"><?php echo $customer['text']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $customer['value']; ?>"><?php echo $customer['text']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></td>
			</div>

          <td><?php echo $entry_customer_group; ?><br />
          <div class="ui-select" style="width: 164px;">
            <select name="filter_customer_group" style="margin-top: 1px;">

                <option value=""><?php echo $text_select_customer_group; ?></option>

              <?php foreach ($customer_groups as $customer_group) { ?>

              <?php if ($customer_group['value'] == $filter_customer_group) { ?>

              <option value="<?php echo $customer_group['value']; ?>" selected="selected"><?php echo $customer_group['text']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $customer_group['value']; ?>"><?php echo $customer_group['text']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></td>
         </div>
    <td width="10%">
        <?php echo __('entry_country'); ?><br />
        <div class="ui-select">

            <select name="filter_country_id" style="margin-top: 1px;">

                <option value="0"><?php echo __('text_countries'); ?></option>

                <?php foreach ($countries as $country) { ?>

                <option value="<?php echo $country['country_id']; ?>" <?php echo ($country['country_id'] == $filter_country_id ? "selected='selected'" : ""); ?>><?php echo $country['name']; ?></option>

                <?php } ?>

            </select>
        </div>
    </td>
          <td><?php echo $entry_status; ?><br />
            <div class="ui-select">
            <select name="filter_order_status_id" style="margin-top: 1px;">

              <option value="0"><?php echo $text_all_status; ?></option>

              <?php foreach ($order_statuses as $order_status) { ?>

              <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>

              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></td>
            </div>
          <td align="right"><button style="margin-top:20px"  onclick="filter();" class="btn-flat btn-success btn-sm"><span><?php echo $button_filter; ?></span></button></td>

        </tr>

      </table>

    </div>

      <div style="overflow:auto">

    <table class="table table-hover">

      <thead>

        <tr>

                <th class="left"><span class="line"></span><?php echo $column_order_id; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_invoice_prefix; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_store_name; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_customer_name; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_customer_group; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_telephone; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_fax; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_email; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_shipping_name; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_shipping_company; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_shipping_address; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_shipping_zone; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_shipping_country; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_shipping_method; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_payment_name; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_payment_company; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_payment_address; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_payment_city; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_payment_postcode; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_payment_zone; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_payment_country; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_payment_method; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_comment; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_currency_value; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_order_status; ?></th>

		<th class="left"><span class="line"></span><?php echo $column_total; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_currency; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_coupon_code; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_discount; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_date; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_ip; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_product_name; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_product_model; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_product_price; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_quantity; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_option_name; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_option_value; ?></th>

                <th class="left"><span class="line"></span><?php echo $column_option_price; ?></th>

           </tr>

      </thead>

      <tbody>

        <?php if ($orders) { ?>

        <?php foreach ($orders as $order) { ?>

        <tr>

                <td class="left"><?php echo $order['order_id']; ?></td>

                <td class="left"><?php echo $order['invoice_prefix']; ?></td>

                <td class="left"><?php echo $order['store_name']; ?></td>

                <td class="left"><?php echo $order['customer_name']; ?></td>

                <td class="left"><?php echo $order['customer_group']; ?></td>

                <td class="left"><?php echo $order['telephone']; ?></td>

                <td class="left"><?php echo $order['fax']; ?></td>

                <td class="left"><?php echo $order['email']; ?></td>

                <td class="left"><?php echo $order['shipping_name']; ?></td>

                <td class="left"><?php echo $order['shipping_company']; ?></td>

                <td class="left"><?php echo $order['shipping_address']; ?></td>

                <td class="left"><?php echo $order['shipping_zone']; ?></td>

                <td class="left"><?php echo $order['shipping_country']; ?></td>

                <td class="left"><?php echo $order['shipping_method']; ?></td>

                <td class="left"><?php echo $order['payment_name']; ?></td>

                <td class="left"><?php echo $order['payment_company']; ?></td>

                <td class="left"><?php echo $order['payment_address']; ?></td>

                <td class="left"><?php echo $order['payment_city']; ?></td>

                <td class="left"><?php echo $order['payment_postcode']; ?></td>

                <td class="left"><?php echo $order['payment_zone']; ?></td>

                <td class="left"><?php echo $order['payment_country']; ?></td>

                <td class="left"><?php echo $order['payment_method']; ?></td>

                <td class="left"><?php echo $order['comment']; ?></td>

                <td class="left"><?php echo $order['currency_value']; ?></td>

                <td class="left"><?php echo $order['order_status']; ?></td>

		<td class="left"><?php echo $order['total']; ?></td>

                <td class="left"><?php echo $order['currency']; ?></td>

                <td class="left"><?php echo $order['coupon_code']; ?></td>

                <td class="left"><?php echo $order['discount']; ?></td>

                <td class="left"><?php echo $order['date']; ?></td>

                <td class="left"><?php echo $order['ip']; ?></td>

                <td class="left"><?php echo $order['product_name']; ?></td>

                <td class="left"><?php echo $order['product_model']; ?></td>

                <td class="left"><?php echo $order['product_price']; ?></td>

                <td class="left"><?php echo $order['quantity']; ?></td>

                <td class="left"><?php echo $order['option_name']; ?></td>

                <td class="left"><?php echo $order['option_value'] ?></td>

                <td class="left"><?php echo $order['option_price'] ?></td>

        </tr>

        <?php } ?>

        <?php } else { ?>

        <tr>

          <td class="center" colspan="25"><?php echo $text_no_results; ?></td>

        </tr>

        <?php } ?>

      </tbody>

    </table>
   </div>
     

    <div class="row"><?php echo $pagination; ?></div>

  </div>

</div>
 

<script type="text/javascript"><!--

function filter() {

	url = 'report/order';

	

	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');

	

	if (filter_date_start) {

		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);

	}



	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');

	

	if (filter_date_end) {

		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);

	}

		

	var filter_customer = $('select[name=\'filter_customer\']').attr('value');

	

	if (filter_customer) {

		url += '&filter_customer=' + encodeURIComponent(filter_customer);

	}



	var filter_customer_group = $('select[name=\'filter_customer_group\']').attr('value');



	if (filter_customer_group) {

		url += '&filter_customer_group=' + encodeURIComponent(filter_customer_group);

	}

	

	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');

	

	if (filter_order_status_id) {

		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);

	}
    var filter_country_id = $('select[name=\'filter_country_id\']').attr('value');



    if (filter_country_id) {

        url += '&filter_country_id=' + encodeURIComponent(filter_country_id);

    }


	location = url;

}

//--></script>

