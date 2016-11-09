<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3><i class="icon-user"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="content">

    <div style="background: #E7EFEF; border: 1px solid #C6D7D7; padding: 3px; margin-bottom: 15px;">

      <table width="100%">

        <tr>

            <td width="26%"><?php echo $entry_date_start; ?><br />

            <input size="20" type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="12"  data-provide="datepicker-inline" /></td>

            <td width="26%"><?php echo $entry_date_end; ?><br />

            <input size="20" type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="12"  data-provide="datepicker-inline" />

          </td>

          <td width="38%"><button style="margin-top: 20px;" onclick="filter();" class="btn-flat btn-success"><span><?php echo $button_filter; ?></span></button></td>

          <td>&nbsp;</td>

          <td>&nbsp;</td>

        </tr>

      </table>

    </div>
 <div class="table-responsive">
    <table class="table table-hover">

      <thead>

        <tr>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_date; ?></th>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_customer; ?></th>

            <th width="50%" class="left"><span class="line"></span><?php echo $column_url; ?></th>

            <th width="10%" class="left"><span class="line"></span><?php echo $column_ip; ?></th>

            <th width="10%" class="left"><span class="line"></span>option</th>

        </tr>

      </thead>

      <tbody>

        <?php if (isset($online_customers)): ?>

            <?php foreach ($online_customers as $online_customer): ?>

            <tr>

              <td class="left"><?php echo $online_customer['date']; ?></td>

              <td class="left">

                  <?php if($online_customer['customer_id']) : ?>

                    <a href="<?php echo HTTPS_SERVER . 'sale/customer/update&customer_id=' . $online_customer['customer_id']; ?>"><?php echo $online_customer['customer']; ?></a>

                  <?php  else: ?>

                    <?php echo $online_customer['customer']; ?>

                  <?php endif; ?>

              </td>

              <td class="left">

                    <?php echo $online_customer['url']; ?>

              </td>

              <td class="left"><?php echo $online_customer['ip']; ?></td>

              <td class="left"><a href="<?php echo $online_customer['activity']; ?>" target='_blank'>View Activity</a></td>

            </tr>

            <?php endforeach; ?>

        <?php else: ?>

        <tr>

          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>

        </tr>

        <?php endif; ?>

      </tbody>

    </table>
   </div>
    <div class="row"><?php echo $pagination; ?></div>

  </div>

</div>

<script type="text/javascript"><!--

function filter() {

	url = 'report/online';

	

	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');

	

	if (filter_date_start) {

		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);

	}



	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');

	

	if (filter_date_end) {

		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);

	}



	location = url;

}

//--></script>

