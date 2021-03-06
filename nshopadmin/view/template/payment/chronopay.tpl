

<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>

<?php } ?>

<div class="box">

  <div class="left"></div>

  <div class="right"></div>

  <div class="heading">

    <h1><i class="icon-credit-card"></i> <?php echo $heading_title; ?></h1>

    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>

  </div>

  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="form">

        <tr>

          <td><span class="required">*</span> <?php echo $entry_product_id; ?></td>

          <td><input type="text" name="chronopay_product_id" value="<?php echo $chronopay_product_id; ?>" />

            <?php if ($error_product_id) { ?>

            <span class="error"><?php echo $error_product_id; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td><span class="required">*</span> <?php echo $entry_product_name; ?></td>

          <td><input type="text" name="chronopay_product_name" value="<?php echo $chronopay_product_name; ?>" />

            <?php if ($error_product_name) { ?>

            <span class="error"><?php echo $error_product_name; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td><?php echo $entry_callback; ?></td>

          <td><textarea cols="40" rows="5"><?php echo $callback; ?></textarea></td>

        </tr>

        <tr>

          <td><?php echo $entry_order_status; ?></td>

          <td><select name="chronopay_order_status_id">

              <?php foreach ($order_statuses as $order_status) { ?>

              <?php if ($order_status['order_status_id'] == $chronopay_order_status_id) { ?>

              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_geo_zone; ?></td>

          <td><select name="chronopay_geo_zone_id">

              <option value="0"><?php echo $text_all_zones; ?></option>

              <?php foreach ($geo_zones as $geo_zone) { ?>

              <?php if ($geo_zone['geo_zone_id'] == $chronopay_geo_zone_id) { ?>

              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_status; ?></td>

          <td><select name="chronopay_status">

              <?php if ($chronopay_status) { ?>

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

          <td><input type="text" name="chronopay_sort_order" value="<?php echo $chronopay_sort_order; ?>" size="1" /></td>

        </tr>

      </table>

    </form>

  </div>

</div>

