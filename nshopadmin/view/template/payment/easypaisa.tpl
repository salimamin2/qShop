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
          <td> <?php echo $entry_email; ?><span class="required">*</span> </td>
          <td><input class="form-control" type="text" name="easypaisa_email" value="<?php echo $easypaisa_email; ?>" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_test; ?></td>
          <td><?php if ($easypaisa_test) { ?>
            <input type="radio" name="easypaisa_test" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="easypaisa_test" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="easypaisa_test" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="easypaisa_test" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_transaction; ?></td>
          <td><div class="ui-select"><select name="easypaisa_transaction">
              <?php if (!$easypaisa_transaction) { ?>
              <option value="0" selected="selected"><?php echo $text_authorization; ?></option>
              <?php } else { ?>
              <option value="0"><?php echo $text_authorization; ?></option>
              <?php } ?>
              <?php if ($easypaisa_transaction) { ?>
              <option value="1" selected="selected"><?php echo $text_sale; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_sale; ?></option>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><div class="ui-select"><select name="easypaisa_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $easypaisa_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><div class="ui-select"><select name="easypaisa_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $easypaisa_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="easypaisa_status">
              <?php if ($easypaisa_status) { ?>
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
          <td><input type="text" name="easypaisa_sort_order" value="<?php echo $easypaisa_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
