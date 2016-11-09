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
      <table class="form">
        <tr>
          <td valign="top"><?php echo $entry_username; ?></td>
          <td valign="top"><input type="text" name="hbl_username" value="<?php echo $hbl_username; ?>" />
            <?php if ($error_username) { ?>
            <span class="error"><?php echo $error_username; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_password; ?></td>
          <td valign="top"><input type="text" name="hbl_password" value="<?php echo $hbl_password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_clientid; ?></td>
          <td valign="top"><input type="text" name="hbl_clientid" value="<?php echo $hbl_clientid; ?>" />
            <?php if ($error_clientid) { ?>
            <span class="error"><?php echo $error_clientid; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_test_status; ?></td>
          <td valign="top"><?php if ($hbl_test_status) { ?>
            <input type="radio" name="hbl_test_status" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="hbl_test_status" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="hbl_test_status" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="hbl_test_status" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_transtype; ?></td>
          <td valign="top"><select name="hbl_transtype">
              <?php foreach ($trans_types as $key=>$val) { ?>
                  <?php if ($hbl_transtype == $key) { ?>
                    <option value="<?php echo $key; ?>" selected="selected"><?php echo $val; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                  <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_order_status; ?></td>
          <td valign="top"><select name="hbl_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $hbl_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_geo_zone; ?></td>
          <td valign="top"><select name="hbl_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $hbl_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="hbl_status">
              <?php if ($hbl_status) { ?>
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
          <td><input type="text" name="hbl_sort_order" value="<?php echo $hbl_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>