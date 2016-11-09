

<div class="table-wrapper products-table section">

    <div class="statistic col-md-7">

	<div class="range clearfix">

	    <div class="range-label"><?php echo $entry_range; ?></div>

	    <select id="range">

		<option value="day"><?php echo $text_day; ?></option>

		<option value="week"><?php echo $text_week; ?></option>

		<option value="month"><?php echo $text_month; ?></option>

		<option value="year"><?php echo $text_year; ?></option>

	    </select>

	</div>

	

	<h4><?php echo $text_statistics; ?></h4>
	<br />

	<div class="sales-customer-legend clearfix">

		<div class="sales-customer-legend-box stat-1">

		    <div class="sales-customer-legend-color">

			<div class="legend-color-box"></div>

		    </div>

		    <div class="sales-customer-legend-text"><?php echo $text_total_order; ?></div>

		</div>

		<div class="sales-customer-legend-box stat-2">

		    <div class="sales-customer-legend-color">

			<div class="legend-color-box"></div>

		    </div>

		    <div class="sales-customer-legend-text"><?php echo $text_total_customer; ?></div>

		</div>

	    </div>

	    <div id="report">

		<div id="sales-customer-graph"></div>

	    </div>

    </div>

    <div class="overview col-md-5">

	<h4><?php echo $text_overview; ?></h4>
	<br />

	<div class="dashboard-overview-top clearfix">

		<div class="sales-value-graph">

		    <input id="total_sale_raw" type="hidden" value="<?php echo substr($total_sale_raw, 0, -2); ?>" data-text_label="<?php echo $text_total_sale; ?>" data-currency_value="<?php echo $total_sale; ?>" />

		    <input id="total_sale_year_raw" type="hidden" value="<?php echo substr($total_sale_year_raw, 0, -2); ?>" data-text_label="<?php echo $text_total_sale_year; ?>" data-currency_value="<?php echo $total_sale_year; ?>" />

		    <input id="total_sales_previous_years_raw" type="hidden" value="<?php echo $total_sales_previous_years_raw; ?>" data-text_label="<?php echo $text_total_sales_previous_years; ?>" data-currency_value="<?php echo $total_sales_previous_years; ?>" />



		    <div id="sales-value-graph"></div>

		</div>

		<div class="sales-value-legend">

		    <div class="sales-this-year">

			<div class="number-stat-legend-color">

			    <div class="legend-color-box"></div>

			</div>

			<div class="number-stat-number"><?php echo $total_sale_year; ?></div>

			<div class="number-stat-text"><?php echo $text_total_sale_year; ?></div>

		    </div>

		    <div class="sales-previous-years">

			<div class="number-stat-legend-color">

			    <div class="legend-color-box"></div>

			</div>

			<div class="number-stat-number"><?php echo $total_sales_previous_years; ?></div>

			<div class="number-stat-text"><?php echo $text_total_sales_previous_years; ?></div>

		    </div>

		    <div class="sales-total">

			<div class="number-stat-legend-color">

			    <div class="legend-color-box"></div>

			</div>

			<div class="number-stat-number"><?php echo $total_sale; ?></div>

			<div class="number-stat-text"><?php echo $text_total_sale; ?></div>

		    </div>

		</div>

	    </div>

	    <div class="dashboard-overview-bottom clearfix">

		<div class="number-stat-box stat-1">

		    <div class="number-stat-number"><?php echo number_format($total_order); ?></div>

		    <div class="number-stat-text"><?php echo $text_total_order; ?></div>

		</div>

		<div class="number-stat-box stat-2">

		    <div class="number-stat-number"><?php echo number_format($total_customer); ?></div>

		    <div class="number-stat-text"><?php echo $text_total_customer; ?></div>

		</div>

		<div class="number-stat-box stat-3">

		    <div class="number-stat-number"><?php echo number_format($total_review); ?></div>

		    <div class="number-stat-text"><?php echo $text_total_review; ?></div>

		</div>

	    </div>

    </div>

<div class="clearfix"></div>
</div>

<div class="dashboard-bottom row">

    <div class="latest col-md-12">

	<h4><?php echo $text_latest_10_orders; ?></h4>
	
	<br />

	    <div class="table-responsive">

		<table class="table table-hover">

		    <thead>

			<tr>

			    <th class="right"><?php echo $column_order; ?></th>

			    <th class="left"><span class="line"></span><?php echo $column_customer; ?></th>

			    <th class="left"><span class="line"></span><?php echo $column_status; ?></th>

			    <th class="left"><span class="line"></span>Payment Method</th>

			    <th class="left"><span class="line"></span><?php echo $column_date_added; ?></th>

			    <th class="right"><span class="line"></span><?php echo $column_total; ?></th>

			    <th class="right"><span class="line"></span><?php echo $column_action; ?></th>

			</tr>

		    </thead>

		    <tbody>

			<?php if ($orders) { ?>

			    <?php foreach ($orders as $order) { ?>

				<tr>

				    <td class="right"><?php echo $order['order_id']; ?></td>

				    <td class="left"><?php echo $order['name']; ?></td>

				    <td class="left"><?php echo $order['status']; ?></td>

				    <td class="left"><?php echo $order['payment_method']; ?></td>

				    <td class="left"><?php echo $order['date_added']; ?></td>

				    <td class="right"><?php echo $order['total']; ?></td>

				    <td class="right"><?php foreach ($order['action'] as $action) { ?>

	    				[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]

					<?php } ?></td>

				</tr>

			    <?php } ?>

			<?php } else { ?>

    			<tr>

    			    <td class="center" colspan="6"><?php echo $text_no_results; ?></td>

    			</tr>

    			</tr>

			<?php } ?>

		    </tbody>

		</table>

	    </div>

    </div>

</div>



<div class="clear"></div>

</div>

