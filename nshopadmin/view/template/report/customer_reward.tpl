<div class="box table-wrapper products-table section">
	<div class="head well">
      <h3><i class="icon-folder-open"></i> <?php echo $heading_title; ?></h3>
  </div>
    <div class="content">

        <div style="background: #E7EFEF; border: 1px solid #C6D7D7; padding: 10px; margin-bottom: 15px;">

		<table width="100%">

        <tr>

            <td width="26%"><?php echo $entry_date_start; ?><br />

            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="20"  data-provide="datepicker-inline" /></td>

            <td width="26%"><?php echo $entry_date_end; ?><br />

            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="20" data-provide="datepicker-inline" />

          </td>
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

          <td width="38%"><button style="margin-top: 20px;"onclick="filter();" class="btn-flat btn-success"><span><?php echo $button_filter; ?></span></button></td>

          <td>&nbsp;</td>

          <td>&nbsp;</td>

        </tr>

      </table>

            <!--<div class="row">

                <div class="col-md-3">

                    <?php echo $entry_date_start; ?>

                    <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" data-provide="datepicker-inline" />

                </div>



                <div class="col-md-3">

                    <?php echo $entry_date_end; ?>

                    <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" data-provide="datepicker-inline" />

                </div>



                <div class="col-md-6">

                    <button onclick="filter();" class="btn-flat btn-success"><span><?php echo $button_filter; ?></span></button>

                </div>



            </div>-->

        </div>

  <div class="table-responsive">
    <table class="table table-hover">

        <thead>

          <tr>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_customer; ?></th>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_email; ?></th>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_customer_group; ?></th>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_status; ?></th>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_points; ?></th>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_orders; ?></th>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_total; ?></th>

            <th width="15%" class="left"><span class="line"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($customers) { ?>

          <?php foreach ($customers as $customer) { ?>

          <tr>

            <td class="left"><?php echo $customer['customer']; ?></td>

            <td class="left"><?php echo $customer['email']; ?></td>

            <td class="left"><?php echo $customer['customer_group']; ?></td>

            <td class="left"><?php echo $customer['status']; ?></td>

            <td class="right"><?php echo $customer['points']; ?></td>

            <td class="right"><?php echo $customer['orders']; ?></td>

            <td class="right"><?php echo $customer['total']; ?></td>

            <td class="right"><?php foreach ($customer['action'] as $action) { ?>

              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } else { ?>

          <tr>

            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>

          </tr>

          <?php } ?>

        </tbody>

      </table>
      </div>
      <div class="row"><?php echo $pagination; ?></div>

    </div>

  </div>

  <script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.7.2.custom.js"></script>

<script type="text/javascript"><!--

function filter() {

	url = 'report/customer_reward&token=<?php echo $token; ?>';

	

	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');

	

	if (filter_date_start) {

		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);

	}



	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');

	

	if (filter_date_end) {

		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);

	}

    var filter_country_id = $('select[name=\'filter_country_id\']').attr('value');



    if (filter_country_id) {

        url += '&filter_country_id=' + encodeURIComponent(filter_country_id);

    }

	location = url;

}

//--></script>

