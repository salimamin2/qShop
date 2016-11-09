<div class="box">
  <div class="head well">
    <h3><i class="icon-money"></i> <?php echo $heading_title; ?>
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
        <?php foreach ($languages as $language) { ?>
        <tr>
          <td><?php echo $entry_bank; ?><span class="required">*</span> </td>
          <td><textarea class="form-control" name="bank_transfer_bank_<?php echo $language['language_id']; ?>" cols="80" rows="10"><?php echo isset(${'bank_transfer_bank_' . $language['language_id']}) ? ${'bank_transfer_bank_' . $language['language_id']} : ''; ?></textarea>
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
            <?php if (isset(${'error_bank_' . $language['language_id']})) { ?>
            <span class="error"><?php echo ${'error_bank_' . $language['language_id']}; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td>
		  <div class="ui-select">
		  <select name="bank_transfer_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $bank_transfer_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><div class="ui-select"><select name="bank_transfer_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $bank_transfer_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="bank_transfer_status">
              <?php if ($bank_transfer_status) { ?>
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
          <td><input type="text" name="bank_transfer_sort_order" value="<?php echo $bank_transfer_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
