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

          <td><?php echo $entry_cost; ?></td>

          <td><input class="form-control" type="text" name="flat_cost" value="<?php echo $flat_cost; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_tax; ?></td>

          <td><div class="ui-select"><select name="flat_tax_class_id">

              <option value="0"><?php echo $text_none; ?></option>

              <?php foreach ($tax_classes as $tax_class) { ?>

              <?php if ($tax_class['tax_class_id'] == $flat_tax_class_id) { ?>

              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></div></td>

        </tr>

        <tr>

          <td><?php echo $entry_geo_zone; ?></td>

          <td><div class="ui-select"><select name="flat_geo_zone_id">

              <option value="0"><?php echo $text_all_zones; ?></option>

              <?php foreach ($geo_zones as $geo_zone) { ?>

              <?php if ($geo_zone['geo_zone_id'] == $flat_geo_zone_id) { ?>

              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></div></td>

        </tr>

        <tr>

          <td><?php echo $entry_customer_group; ?></td>

          <td><div class="ui-select"><select name="flat_customer_group[]" multiple="mulitple">

                  <?php foreach($customer_groups as $customer_group):?>

                  <?php if(in_array($customer_group['customer_group_id'],$flat_customer_group)): ?>

                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>

                 <?php else : ?>

                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>

                 <?php endif; ?>

                <?php endforeach; ?>

            </select></div></td>

        </tr>

        <tr>

          <td><?php echo $entry_status; ?></td>

          <td><div class="ui-select"><select name="flat_status">

              <?php if ($flat_status) { ?>

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

          <td><input type="text" name="flat_sort_order" value="<?php echo $flat_sort_order; ?>" size="1" /></td>

        </tr>

      </table>

    </form>

  </div>

</div>

