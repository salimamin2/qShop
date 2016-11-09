<div class="box">
    <div class="head well">
        <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
            <a onclick="$('#form').submit();" class="btn btn-success"><span><?php echo __('button_save'); ?></span></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo __('button_cancel'); ?></span></a>
            </div>
        </h3>
    </div>
       <?php if ($error_warning) { ?>
            <div class="warning"><?php echo $error_warning; ?></div>
        <?php } ?>
    <div class="content">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <table class="form" id="rates">

                <thead>

                    <tr>

                        <td><?php echo __('entry_rate'); ?></td>

                        <td><?php echo __('entry_geo_zone'); ?></td>

                    </tr>

                </thead>

                <?php foreach($blue_ex_rates as $result): ?>

                    <tr>

                        <td>

                            <select name="blue_ex_rates_geo_zone_id">

                                <option value="0"><?php echo $text_all_zones; ?></option>

                                <?php foreach ($geo_zones as $geo_zone) { ?>

                                    <?php if ($geo_zone['geo_zone_id'] == $blue_ex_geo_zone_id) { ?>

                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>

                                    <?php } else { ?>

                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>

                                    <?php } ?>

                                <?php } ?>

                            </select>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </table>



            <table class="form">

                <tr>

                    <td><?php echo __('entry_test'); ?></td>

                    <td><?php if ($blue_ex_test) { ?>

                            <input type="radio" name="blue_ex_test" value="1" checked="checked" />

                            <?php echo $text_yes; ?>

                            <input type="radio" name="blue_ex_test" value="0" />

                            <?php echo $text_no; ?>

                        <?php } else { ?>

                            <input type="radio" name="blue_ex_test" value="1" />

                            <?php echo $text_yes; ?>

                            <input type="radio" name="blue_ex_test" value="0" checked="checked" />

                            <?php echo $text_no; ?>

                        <?php } ?>

                    </td>

                </tr>

                <tr>

                    <td><?php echo __('entry_weight_class'); ?></td>

                    <td>

                        <select name="blue_ex_weight_class">

                            <?php foreach ($weight_classes as $weight_class) { ?>

                                <?php if ($weight_class['unit'] == $blue_ex_weight_class) { ?>

                                    <option value="<?php echo $weight_class['unit']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>

                                <?php } else { ?>

                                    <option value="<?php echo $weight_class['unit']; ?>"><?php echo $weight_class['title']; ?></option>

                                <?php } ?>

                            <?php } ?>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td><?php echo __('entry_tax'); ?></td>

                    <td>

                        <select name="blue_ex_tax_class_id">

                            <option value="0"><?php echo __('text_none'); ?></option>

                            <?php foreach ($tax_classes as $tax_class) { ?>

                                <?php if ($tax_class['tax_class_id'] == $blue_ex_tax_class_id) { ?>

                                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>

                                <?php } else { ?>

                                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>

                                <?php } ?>

                            <?php } ?>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td><?php echo __('entry_status'); ?></td>

                    <td>

                        <select name="blue_ex_status">

                            <?php if ($blue_ex_status) { ?>

                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

                                <option value="0"><?php echo $text_disabled; ?></option>

                            <?php } else { ?>

                                <option value="1"><?php echo $text_enabled; ?></option>

                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

                            <?php } ?>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td><?php echo __('entry_sort_order'); ?></td>

                    <td><input type="text" name="blue_ex_sort_order" value="<?php echo $blue_ex_sort_order; ?>" size="1" /></td>

                </tr>

            </table>

        </form>

    </div>

</div>

<script type="text/javascript"><!--

    $('select[name=\'blue_ex_origin\']').bind('change', function() {

        $('#service > div').hide();	

										 

        $('#' + this.value).show();	

    });



    $('select[name=\'blue_ex_origin\']').trigger('change');

    //--></script>

