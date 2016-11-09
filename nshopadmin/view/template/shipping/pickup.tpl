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
          <td><?php echo $entry_geo_zone; ?></td>
          <td><div class="ui-select"><select name="pickup_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $pickup_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="pickup_status">
              <?php if ($pickup_status) { ?>
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
          <td><input type="text" name="pickup_sort_order" value="<?php echo $pickup_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>