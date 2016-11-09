
<div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
        <h1 style="background-image: url('view/image/report.png');"><?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
        <div style="background: #E7EFEF; border: 1px solid #C6D7D7; padding: 3px; margin-bottom: 15px;">
            <table width="100%" cellspacing="0" cellpadding="6">
                <tr>
                    <td width="20%"><?php echo $entry_date_start; ?><br />
                        <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="12" style="margin-top: 4px;" /></td>
                    <td width="20%"><?php echo $entry_date_end; ?><br />
                        <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="12" style="margin-top: 4px;" />
                    </td>
                    <td width="20%"><?php echo $entry_email; ?><br />
                        <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" size="20" style="margin-top: 4px;" />
                    </td>
                    <td width="20%"><?php echo $entry_customer; ?><br />
                        <input type="text" id="filter_customer" name="filter_customer" value="<?php echo $filter_customer; ?>" size="20" style="margin-top: 4px;" />
                        <input type="hidden" id="filter_customer_id" value="" style="font-size: 10px; width: 20px;" disabled="disabled" />
                    </td>
                    <td width="20%" align="left"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
                    <td width="20%">&nbsp;</td>
                    <td width="20%">&nbsp;</td>
                </tr>
            </table>
        </div>
        <table width="100%" class="list">
            <thead>
                <tr>
                    <td width="15%" class="left"><?php echo $column_date; ?></td>
                    <td width="15%" class="left"><?php echo $column_customer; ?></td>
                    <td width="50%" class="left"><?php echo $column_url; ?></td>
                    <td width="10%" class="left"><?php echo $column_ip; ?></td>
                    <td width="10%" class="left">option</td>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($online_customers)): ?>
                    <?php foreach ($online_customers as $online_customer): ?>
                        <tr>
                            <td class="left"><?php echo $online_customer['date']; ?></td>
                            <td class="left">
                                <?php if ($online_customer['customer_id']) : ?>
                                    <a href="<?php echo HTTPS_SERVER . 'sale/customer/update&customer_id=' . $online_customer['customer_id']; ?>"><?php echo $online_customer['customer']; ?></a>
                                <?php else: ?>
                                    <?php echo $online_customer['customer']; ?>
                                <?php endif; ?>
                            </td>
                            <td class="left">
                                <?php if ($online_customer['url']): ?>
                                    <a href="<?php echo $online_customer['url']; ?>"><?php echo $online_customer['url']; ?></a>
                                <?php else: ?>
                                    <?php echo $online_customer['url']; ?>
                                <?php endif; ?>
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
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>
<?php /*
<script type="text/javascript" src="view/javascript/jquery/bsn.AutoSuggest_c_2.0.js"></script>
<script type="text/javascript">
    var options = {
        script:"report/online/customer&json=true&",
        varname:"input",
        json:true,
        callback: function (obj) { document.getElementById('filter_customer_id').value = obj.id; }
    };
    var as_json = new AutoSuggest('filter_customer', options);
</script>
  */ ?>
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

        var filter_email = $('input[name=\'filter_email\']').attr('value');
        if (filter_email) {
            url += '&filter_email=' + encodeURIComponent(filter_email);
        }

        var filter_customer = $('input[name=\'filter_customer\']').attr('value');
        if (filter_customer) {
            url += '&filter_customer=' + encodeURIComponent(filter_customer);
        }

        location = url;
    }
    //--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
    $(document).ready(function() {
        $('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
        $('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
    });
    //--></script>
