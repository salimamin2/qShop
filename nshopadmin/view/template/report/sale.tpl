<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
    </h3>   
  </div>
  <div class="content">

    <div style="background: #E7EFEF; border: 1px solid #C6D7D7; padding: 3px; margin-bottom: 15px;">

     <table width="100%">

        <tr>

          <td width="10%"><?php echo $entry_date_start; ?><br />

              <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="12" style="margin-top: 1px;" data-provide="datepicker-inline" /></td>
			  <td width="10%"><?php echo $entry_date_end; ?><br />
         <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="12"  data-provide="datepicker-inline" /></td>
		 <td width="10%">
            <?php echo $entry_group; ?><br />
            <div class="ui-select">
            <select name="filter_group" style="margin-top: 1px;">

              <?php foreach ($groups as $groups) { ?>

                <option value="<?php echo $groups['value']; ?>" <?php echo ($groups['value'] == $filter_group ? "selected='selected'" : ""); ?> ><?php echo $groups['text']; ?></option>

              <?php } ?>

            </select>
</div></td>
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
            <td width="10%">
            <?php echo $entry_status; ?><br />
			<div class="ui-select">

            <select name="filter_order_status_id" style="margin-top: 1px;">

              <option value="0"><?php echo $text_all_status; ?></option>

              <?php foreach ($order_statuses as $order_status) { ?>

                <option value="<?php echo $order_status['order_status_id']; ?>" <?php echo ($order_status['order_status_id'] == $filter_order_status_id ? "selected='selected'" : ""); ?>><?php echo $order_status['name']; ?></option>

              <?php } ?>

            </select>
            </div>
          </td>
		  <td width="26%"><button style="margin-top:20px"  onClick="filter();" class="btn-flat btn-success btn-sm"><span><?php echo $button_filter; ?></span></button>
          </td>

        </tr>

      </table>



    </div>

    <table class="table table-hover" >

      <thead>

        <tr>

          <th class="left"><span class="line"></span><?php echo $column_date_start; ?></th>

          <th class="left"><span class="line"></span><?php echo $column_date_end; ?></th>

          <th class="right"><span class="line"></span><?php echo $column_orders; ?></th>

          <th class="right"><span class="line"></span><?php echo $column_total; ?></th>

        </tr>

      </thead>

      <tbody>

        <?php if ($orders) { ?>

        <?php foreach ($orders as $order) { ?>

        <tr>

          <td class="left"><?php echo $order['date_start']; ?></td>

          <td class="left"><?php echo $order['date_end']; ?></td>

          <td class="right"><?php echo $order['orders']; ?></td>

          <td class="right"><?php echo $order['total']; ?></td>

        </tr>

        <?php } ?>

        <?php } else { ?>

        <tr>

          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>

        </tr>

        <?php } ?>

      </tbody>

    </table>

    <div class="row"><?php echo $pagination; ?></div>

  </div>

</div>

<script type="text/javascript"><!--

function filter() {

	url = 'report/sale&token=<?php echo $token; ?>';

	

	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');

	

	if (filter_date_start) {

		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);

	}



	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');

	

	if (filter_date_end) {

		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);

	}

		

	var filter_group = $('select[name=\'filter_group\']').attr('value');

	

	if (filter_group) {

		url += '&filter_group=' + encodeURIComponent(filter_group);

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

