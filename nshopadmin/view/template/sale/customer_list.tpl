<div class="box table-wrapper products-table section">
    <div class="head well">
        <h3><i class="icon-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                <a onclick="$('form').attr('action', '<?php echo $approve; ?>');
                    $('form').submit();" class="btn-flat btn-primary btn-sm"><span><?php echo $button_approve; ?></span></a>

                <a href="<?php echo $insert; ?>" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a>
                <a onclick="$('form').attr('action', '<?php echo $delete; ?>');
                    $('form').submit();" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></a>
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

        <form action="" method="post" enctype="multipart/form-data" id="form">

            <div class="table-responsive">

                <table class="table table-hover" data-rel="data-grid">

                    <thead>
                        <tr class="filter">

                            <td></td>

                            <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo __('entry_customer'); ?>" /></td>



                            <td><select name="filter_customer_group_id">

                                    <option value="*"><?php echo __('entry_select_group');?></option>

                                    <?php foreach ($customer_groups as $customer_group) { ?>

                                    <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>

                                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>

                                    <?php } else { ?>

                                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>

                                    <?php } ?>

                                    <?php } ?>

                                </select></td>

                            <td><select name="filter_status">

                                    <option value="*"><?php echo __('entry_select_status');?></option>

                                    <?php if ($filter_status) { ?>

                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

                                    <?php } else { ?>

                                    <option value="1"><?php echo $text_enabled; ?></option>

                                    <?php } ?>

                                    <?php if (!is_null($filter_status) && !$filter_status) { ?>

                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

                                    <?php } else { ?>

                                    <option value="0"><?php echo $text_disabled; ?></option>

                                    <?php } ?>

                                </select></td>

                            <td><select name="filter_approved">

                                    <option value="*"><?php echo __('entry_select_approved');?></option>

                                    <?php if ($filter_approved) { ?>

                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>

                                    <?php } else { ?>

                                    <option value="1"><?php echo $text_yes; ?></option>

                                    <?php } ?>

                                    <?php if (!is_null($filter_approved) && !$filter_approved) { ?>

                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>

                                    <?php } else { ?>

                                    <option value="0"><?php echo $text_no; ?></option>

                                    <?php } ?>

                                </select></td>

                            <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo __('entry_date_added'); ?>" size="12" id="date" /></td>
                            <td><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo __('entry_email'); ?>" /></td>
                            <td><select name="filter_country_id">

                                    <option value="*"><?php echo __('entry_select_country');?></option>

                                    <?php foreach ($countries as $country) { ?>

                                    <?php if ($country['country_id'] == $filter_country_id) { ?>

                                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>

                                    <?php } else { ?>

                                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>

                                    <?php } ?>

                                    <?php } ?>

                                </select></td>
                            <td><button onclick="filter();" type="button" class="btn btn-xs btn-flat btn-info"><?php echo $button_filter; ?></button>
                                <a href="<?php echo $reset; ?>" class="btn btn-xs btn-flat"><span><?php echo $button_reset; ?></span></a>
                            </td>

                        </tr>

                        <tr>

                            <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                            <th class="left"><?php echo $column_name; ?></th>
                            <th class="left"><?php echo $column_customer_group; ?></th>
                            <th class="left"><?php echo $column_status; ?></th>
                            <th class="left"><?php echo $column_approved; ?></th>
                            <th class="left"><?php echo $column_date_added; ?></th>
                            <th class="left"><?php echo $column_email; ?></th>
                            <th class="left"><?php echo $column_country; ?></th>
                            <th class="right"><?php echo $column_action; ?></th>
                        </tr>

                    </thead>

                    <tbody>

                        

                        <?php if ($customers) { ?>

                        <?php foreach ($customers as $customer) { ?>

                        <tr>

                            <td style="text-align: center;"><?php if ($customer['selected']) { ?>

                                <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />

                                <?php } else { ?>

                                <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />

                                <?php } ?></td>

                            <td class="left"><a href="<?php echo $customer['profile']; ?>"><?php echo $customer['name']; ?></a></td>



                            <td class="left"><?php echo $customer['customer_group']; ?></td>

                            <td class="left"><?php echo $customer['status']; ?></td>

                            <td class="left"><?php echo $customer['approved']; ?></td>

                            <td class="left"><?php echo $customer['date_added']; ?></td>

                            <td class="left"><?php echo $customer['email']; ?></td>

                            <td class="left"><?php echo $customer['country']; ?></td>

                            <td class="right"><?php foreach ($customer['action'] as $action) { ?>

                                <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip   " href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

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

        </form>

        <div class="pagination" style="display:none;"><?php echo $pagination; ?></div>

    </div>

</div>
</div>

<script type="text/javascript"><!--

    function filter() {

        url = 'sale/customer&token=<?php echo $token; ?>';



        var filter_name = $('input[name=\'filter_name\']').attr('value');



        if (filter_name) {

            url += '&filter_name=' + encodeURIComponent(filter_name);

        }



        var filter_email = $('input[name=\'filter_email\']').attr('value');



        if (filter_email) {

            url += '&filter_email=' + encodeURIComponent(filter_email);

        }



        var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').attr('value');



        if (filter_customer_group_id != '*') {

            url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);

        }

        var filter_country_id = $('select[name=\'filter_country_id\']').attr('value');



        if (filter_country_id != '*') {

            url += '&filter_country_id=' + encodeURIComponent(filter_country_id);

        }



        var filter_status = $('select[name=\'filter_status\']').attr('value');



        if (filter_status != '*') {

            url += '&filter_status=' + encodeURIComponent(filter_status);

        }



        var filter_approved = $('select[name=\'filter_approved\']').attr('value');



        if (filter_approved != '*') {

            url += '&filter_approved=' + encodeURIComponent(filter_approved);

        }



        var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');



        if (filter_date_added) {

            url += '&filter_date_added=' + encodeURIComponent(filter_date_added);

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

