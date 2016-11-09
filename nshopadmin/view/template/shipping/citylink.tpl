<div class="box">
  <div class="head well">
    <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
				<div class="pull-right">  	
            <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
				</div>
    </h3>
  </div>
  <?php if ($error_warning) { ?>
      <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_rate; ?></td>
          <td><textarea class="form-control" name="citylink_rate" cols="40" rows="5"><?php echo $citylink_rate; ?></textarea></td>
        </tr>
        <tr>
          <td><?php echo $entry_tax; ?></td>
          <td><div class="ui-select"><select name="citylink_tax_class_id">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $citylink_tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><div class="ui-select"><select name="citylink_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $citylink_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="citylink_status">
              <?php if ($citylink_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="citylink_sort_order" value="<?php echo $citylink_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
