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
          <td><?php echo $entry_total; ?></td>
          <td><input class="form-control" type="text" name="handling_total" value="<?php echo $handling_total; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_fee; ?></td>
          <td><input type="text" class="form-control" name="handling_fee" value="<?php echo $handling_fee; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_tax; ?></td>
          <td><div class="ui-select"><select name="handling_tax_class_id">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $handling_tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="handling_status">
              <?php if ($handling_status) { ?>
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
          <td><input type="text" name="handling_sort_order" value="<?php echo $handling_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>