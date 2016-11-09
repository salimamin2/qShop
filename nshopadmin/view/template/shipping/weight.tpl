<div class="box">
    <div class="head well">
      <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
        <div class="pull-right">
          <a onclick="$('#form').submit();" class="btn btn-success"><span><?php echo $button_save; ?></span></a>
          <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a>
        </div>
      </h3>
    </div>
  <?php if ($error_warning) { ?>
      <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="col-md-3" id="shipping_zones">
            <ul id="tabs" class="nav nav-pills nav-stacked">
                <li class="nav active">
                    <a href="#tab_general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <li class="nav"><a href="#tab_geo_zone<?php echo $geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $geo_zone['name']; ?></a></li>

                <?php } ?>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_general">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_tax; ?></td>
                            <td><select name="weight_tax_class_id">
                                    <option value="0"><?php echo $text_none; ?></option>
                                    <?php foreach ($tax_classes as $tax_class) { ?>
                                    <?php if ($tax_class['tax_class_id'] == $weight_tax_class_id) { ?>
                                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_status; ?></td>
                            <td><select name="weight_status">
                                    <?php if ($weight_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_sort_order; ?></td>
                            <td><input type="text" name="weight_sort_order" value="<?php echo $weight_sort_order; ?>" size="1" /></td>
                        </tr>
                    </table>
                </div>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <div class="tab-pane fade" id="tab_geo_zone<?php echo $geo_zone['geo_zone_id']; ?>">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_rate; ?></td>
                            <td><textarea name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_rate" cols="40" rows="5"><?php echo ${'weight_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_status; ?></td>
                            <td><select name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_status">
                                    <?php if (${'weight_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                    </table>

                </div>
                <?php } ?>
            </div>
        </div>
    </form>
  </div>
</div>