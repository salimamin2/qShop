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
  <?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>

  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_position; ?></td>
          <td><div class="ui-select"><select name="category_position">
              <?php if ($category_position == 'none') { ?>
                <option value="none" selected="selected"><?php echo $text_none; ?></option>
              <?php } else { ?>
                 <option value="none"><?php echo $text_none; ?></option>
              <?php } ?>
              <?php if ($category_position == 'left') { ?>
              <option value="left" selected="selected"><?php echo $text_left; ?></option>
              <?php } else { ?>
              <option value="left"><?php echo $text_left; ?></option>
              <?php } ?>
              <?php if ($category_position == 'top') { ?>
              <option value="top" selected="selected"><?php echo $text_top; ?></option>
              <?php } else { ?>
              <option value="top"><?php echo $text_top; ?></option>
              <?php } ?>
              <?php if ($category_position == 'right') { ?>
              <option value="right" selected="selected"><?php echo $text_right; ?></option>
              <?php } else { ?>
              <option value="right"><?php echo $text_right; ?></option>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="category_status">
              <?php if ($category_status) { ?>
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
          <td><input type="text" name="category_sort_order" value="<?php echo $category_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
