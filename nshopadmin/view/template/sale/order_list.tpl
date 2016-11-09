<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" >
    <input type="hidden" class="form-control" name="directory" value="<?php echo $directory; ?>" />
<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
        <a onclick="$('#form').attr('action', '<?php echo $invoice; ?>'); $('#form').attr('target', '_blank'); $('#form').submit();" class="btn-flat btn-primary btn-sm"><span><?php echo $button_invoices; ?></span></a>
        <a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></a>
				</div>
      </h3>
		</div>

    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="content">
      <div class="table-responsive">

		<table class="table table-hover" data-rel="data-grid">

		    <thead> 
        <tr class="filter">

            <td></td>

            <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>

            <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>

            <td><select name="filter_order_status_id">

                <option value="*"></option>

                <?php if ($filter_order_status_id == '0') { ?>

                <option value="0" selected="selected"><?php echo $text_missing_orders; ?></option>

                <?php } else { ?>

                <option value="0"><?php echo $text_missing_orders; ?></option>

                <?php } ?>

                <?php foreach ($order_statuses as $order_status) { ?>

                <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>

                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>

                <?php } else { ?>

                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>

                <?php } ?>

                <?php } ?>

              </select></td>

            <td><input type="text" name="filter_name" value="<?php echo $filter_payment_method; ?>" /></td>  

            <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" id="date" /></td>

            <td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="4" style="text-align: right;" /></td>

            <td align="right"><button onclick="filter();" type="button" class="btn btn-info btn-xs"><?php echo $button_filter; ?></button></td>

          </tr>    
		    <tr>

            <th width="1" style="text-align: center;"> <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'o.order_id') { ?>

              <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_order; ?>"><?php echo $column_order; ?></a>

              <?php } ?></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'name') { ?>

              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>

              <?php } ?></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'status') { ?>

              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>

              <?php } ?></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'status') { ?>

              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>">Payment Method</a>

              <?php } else { ?>

              <a href="<?php echo $sort_status; ?>">Payment Method</a>

              <?php } ?></th> 


            <th class="left"><span class="line"></span><?php if ($sort == 'o.date_added') { ?>

              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>

              <?php } ?></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'o.total') { ?>

              <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>

              <?php } ?></th>

            <th class="left"><span class="line"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          

          <?php if ($orders) { ?>

          <?php foreach ($orders as $order) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($order['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />

              <?php } ?></td>

            <td class="right"><?php echo $order['order_id']; ?></td>

            <td class="left"><?php echo $order['name']; ?></td>

            <td class="left"><?php echo $order['status']; ?></td>
            
            <td class="left"><?php echo $order['payment_method']; ?></td>

            <td class="left"><?php echo $order['date_added']; ?></td>

            <td class="right"><?php echo $order['total']; ?></td>

            <td class="right btn-group"><?php foreach ($order['action'] as $action) { ?>

              <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip " href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>
              <a class="btn btn-primary btn-sm" href="<?php echo $link_shipment.$order['order_id']; ?>" title="Shipment Details">
                  <i class="icon-shopping-cart"></i>
              </a>

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } else { ?>

          <tr>

            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>

          </tr>

          <?php } ?>

        </tbody>

      </table>
	     </div>

	</div>

    </div>

    </form>


  </div>

</div>

<script type="text/javascript"><!--

function filter() {

	url = 'sale/order&token=<?php echo $token; ?>';

	

	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');

	

	if (filter_order_id) {

		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);

	}

	

	var filter_name = $('input[name=\'filter_name\']').attr('value');

	

	if (filter_name) {

		url += '&filter_name=' + encodeURIComponent(filter_name);

	}

	

	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');

	

	if (filter_order_status_id != '*') {

		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);

	}	



	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');

	

	if (filter_date_added) {

		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);

	}



	var filter_total = $('input[name=\'filter_total\']').attr('value');



	if (filter_total) {

		url += '&filter_total=' + encodeURIComponent(filter_total);

	}	

		

	location = url;

}

//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>

<script type="text/javascript"><!--

$(document).ready(function() {

	$('#date').datepicker({dateFormat: 'yy-mm-dd'});

});

//--></script>

